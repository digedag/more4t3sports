<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2014 Rene Nitzsche <rene@system25.de>
* All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
require_once t3lib_extMgm::extPath('rn_base', 'class.tx_rnbase.php');
tx_rnbase::load('tx_t3socials_trigger_MessageBuilder');
tx_rnbase::load('tx_more4t3sports_t3socials_ticker_TriggerConfig');


/**
 * Message Builder für Tickermeldungen von T3sports
 */
class tx_more4t3sports_t3socials_ticker_MessageBuilder
	extends tx_t3socials_trigger_MessageBuilder {

	/**
	 * Erzeugt eine generische Nachricht für den versand über die Netzwerke.
	 *
	 * @param tx_t3socials_models_Message $message
	 * @param tx_cfcleague_models_MatchNote $model
	 * @return tx_t3socials_models_IMessage
	 */
	protected function buildMessage(tx_t3socials_models_Message $message, $model) {
		// wir bekommen eine einfache MatchNote übergeben.
		// Zuerst das Spiel holen. Tickermeldungen nur von Spielen, die als Liveticker verlinkt sind
		$match = $this->getLiveMatch4Ticker($ticker);
		if(!$match) return; // Nix zu tun
		if(!$this->isTickerable($match, $ticker))
			return;
		$tickerType = tx_more4t3sports_t3socials_ticker_TriggerConfig::TICKER_TYPE;

		// TODO: Diese Prüfung könnte auch zentral erledigt werden...
		$accounts = tx_t3socials_srv_ServiceRegistry::getNetworkService()->findAccounts($tickerType);
		if(empty($accounts)) return;

		$message = $this->buildTickerMessage($match, $ticker);

		$message->setHeadline($model->getTitle());
		$message->setIntro($model->getShort());
		$message->setMessage($model->getBodytext());
		$message->setData($model);
		return $message;
	}

	/**
	 * @param tx_cfcleague_models_Match $match
	 * @param tx_cfcleague_models_MatchNote $ticker
	 * @return tx_t3socials_models_IMessage
	 */
	protected function buildTickerMessage($match, $ticker) {
		// Alle Ticker laden
		tx_rnbase::load('tx_cfcleaguefe_util_MatchTicker');
		$tickerArr =& tx_cfcleaguefe_util_MatchTicker::getTicker4Match($match);
		$tickerArr = array_reverse($tickerArr);
		$found = false;
		foreach($tickerArr As $matchTicker) {
			if($matchTicker->uid == $ticker->uid) {
				$found = true;
				$ticker = $matchTicker;
				break;
			}
		}
		if(!$found)
			return false;
		$msg = $this->buildGenericMessage4Ticker($match, $ticker);
		return $msg;
	}

	/**
	 *
	 * @param tx_cfcleague_models_Match $match
	 * @param tx_cfcleague_models_MatchNote $ticker
	 * @return tx_t3socials_models_Message
	 */
	protected function buildGenericMessage4Ticker($match, $ticker) {
		$message = tx_rnbase::makeInstance('tx_t3socials_models_Message', 'liveticker');
		$message->setData($ticker);
	
		// Spielstand
		$prefix = $match->getHomeNameShort() . '-' . $match->getGuestNameShort();
		if($match->record['status'] > 0 || $ticker->getMinute() > 0) {
			// Das Ergebnis aus dem Ticker lesen, da es aktueller ist
			$prefix .= ' ' . $ticker->record['goals_home'] .':' . $ticker->record['goals_guest'];
			//$prefix .= ' ' . $match->getGoalsHome() .':' . $match->getGoalsGuest();
		}
		// Paarung und Spielstand als Headline
		$message->setHeadline($prefix);
	
		$player = $ticker->getPlayer();
		if(!(is_object($player) && $player->isValid())) $player = false;
	
		$msg = '';
		switch($ticker->getType()) {
			case 10:
			case 11:
			case 12:
			case 13:
				if(!$player) return; // Tor ohne Spieler geht nicht
				$msg .= 'Tor durch ' . $player->getName();
				break;
			case 30:
				if(!$player) return; // Tor ohne Spieler geht nicht
				$msg .= 'Eigentor durch ' . $player->getName();
				break;
			case 70:
				if(!$player) return; // Tor ohne Spieler geht nicht
				$msg .= 'Gelbe Karte für ' . $player->getName();
				break;
			case 71:
				if(!$player) return; // Tor ohne Spieler geht nicht
				$msg .= 'Gelb-Rot für ' . $player->getName();
				break;
			case 72:
				if(!$player) return; // Tor ohne Spieler geht nicht
				$msg .= 'Rote Karte für ' . $player->getName();
				break;
				//			default:
				//				$msg .= $player->getName();
		}
	
		// Die automatische Meldung ist der Subtitle
		$message->setIntro($msg);
	
		$message->setMessage($ticker->record['comment']);
	
		return $message;
	}

	/**
	 * @param tx_cfcleague_models_MatchNote $ticker
	 * @return tx_cfcleague_models_Match
	 */
	private function getLiveMatch4Ticker($ticker) {
		// TODO: Auf basis model umstellen. Ticker fehlen noch
		$match = tx_rnbase::makeInstance('tx_cfcleaguefe_models_match', $ticker->getGame());
		if($match->record['link_ticker'])
			return $match;
		return false;
	}
	/**
	 * Nicht alle Notes werden getickert. Ist das aber
	 * hier wirklich notwendig?? Warum sind die 
	 * Spielerwechsel hier drin?
	 * @param tx_cfcleague_models_Match $match
	 * @param tx_cfcleague_models_MatchNote $ticker
	 * @return boolean
	 */
	private function isTickerable($match, $ticker) {
		$ignoreTypes = array(200, 80,81,31);
		if(in_array($ticker->getType(), $ignoreTypes))
			return false;
		return true;
	
		//tx_cfcleague_match_notes.type.ticker', '100');
		//tx_cfcleague_match_notes.type.goal', '10');
		//tx_cfcleague_match_notes.type.goal.header', '11');
		//tx_cfcleague_match_notes.type.goal.penalty', '12');
		//tx_cfcleague_match_notes.type.goal.own', '30');
		//tx_cfcleague_match_notes.type.goal.assist', '31');
		//tx_cfcleague_match_notes.type.penalty.forgiven', '32');
		//tx_cfcleague_match_notes.type.corner', '33');
		//tx_cfcleague_match_notes.type.yellow', '70');
		//tx_cfcleague_match_notes.type.yellowred', '71');
		//tx_cfcleague_match_notes.type.red', '72');
		//tx_cfcleague_match_notes.type.changeout', '80');
		//tx_cfcleague_match_notes.type.changein', '81');
	//tx_cfcleague_match_notes.type.captain', '200');
	
	
	}
	/**
	 * Spezielle Netzwerk und Triggerabhängige Dinge durchführen.
	 *
	 * @param tx_t3socials_models_IMessage &$message
	 * @param tx_t3socials_models_Network $network
	 * @param tx_t3socials_models_TriggerConfig $trigger
	 * @return void
	 */
	public function prepareMessageForNetwork(
		tx_t3socials_models_IMessage &$message,
		tx_t3socials_models_Network $network,
		tx_t3socials_models_TriggerConfig $trigger
	) {
		$confId = $network->getNetwork() . '.' . $trigger->getTriggerId() . '.';

		tx_rnbase::load('tx_rnbase_util_Misc');
		$tsfe = tx_rnbase_util_Misc::prepareTSFE();

		$news = $message->getData();
		$config = $network->getConfigurations();
		$link = $config->createLink();
		// tx_ttnews[tt_news]
		$link->designator('tx_ttnews');
		$link->initByTS($config, $confId . 'link.show.', array('tt_news' => $news->getUid()));
		// wenn nicht anders konfiguriert, immer eine absoplute url setzesetzen!
		if (!$config->get($confId . 'link.show.absurl')) {
			$link->setAbsUrl(TRUE);
		}
		tx_rnbase::load('tx_t3socials_util_Link');
		$url = tx_t3socials_util_Link::getRealUrlAbsUrlForLink($link);

		$message->setUrl($url);
	}

}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/more4t3sports/t3socials/ticker/class.tx_more4t3sports_t3socials_ticker_MessageBuilder.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/more4t3sports/t3socials/ticker/class.tx_more4t3sports_t3socials_ticker_MessageBuilder.php']);
}
