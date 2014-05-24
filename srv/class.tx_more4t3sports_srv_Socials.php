<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2012 Rene Nitzsche (rene@system25.de)
 *  All rights reserved
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

require_once(t3lib_extMgm::extPath('rn_base') . 'class.tx_rnbase.php');
require_once(PATH_t3lib.'class.t3lib_svbase.php');
tx_rnbase::load('tx_rnbase_util_DB');
tx_rnbase::load('tx_rnbase_util_Logger');


/**
 * Service for accessing network account information
 * 
 * @author Rene Nitzsche
 */
class tx_more4t3sports_srv_Socials extends t3lib_svbase {

	/**
	 * Versenden eine Twittermeldung bei Aktualisierung des Tippspiels
	 *
	 * @param tx_t3sportsbet_models_Betgame $betgame
	 * @param int $calculatedBets
	 */
	public function sendBetGameUpdated($betgame, $calculatedBets) {
		$accounts = tx_t3socials_srv_ServiceRegistry::getNetworkService()->findAccounts('betgameUpdated');
		if(empty($accounts)) return;

		// Die generische Message bauen
		$message = $this->buildGenericBetGameUpdated($betgame, $calculatedBets);
		foreach($accounts As $account) {
			// Für den Account die Connectionklasse laden
			/**
			 * @var tx_t3socials_network_IConnection
			 */
			$connection = tx_t3socials_srv_ServiceRegistry::getNetworkService()->getConnection($account);
			$connection->setNetwork($account);
			$message->setUrl($this->buildUrl4BetgameUpdated($betgame, $account));
			$connection->sendMessage($message);
		}
	}
	/**
	 * URL auf Tipspiel-Gesamtwertung bauen
	 *
	 * @param tx_rnbase_model_base $betgame
	 * @param tx_t3socials_models_Network $account
	 * @return string
	 */
	protected function buildUrl4BetgameUpdated($betgame, $account) {
		$config = $account->getConfigurations();
		tx_rnbase::load('tx_rnbase_util_Misc');
		tx_rnbase_util_Misc::prepareTSFE();
		$link = $config->createLink();
		$link->designatorString = 't3sportsbet'; // tx_ttnews[tt_news]
		$link->initByTS($config, $account->getNetwork().'.betgameUpdated.link.show.', array());
		$url = $link->makeUrl(false);
		return $url;
	}
	protected function buildGenericBetGameUpdated($betgame, $calculatedBets) {
		/**
		 * @var tx_t3socials_models_Message
		 */
		$message = tx_rnbase::makeInstance('tx_t3socials_models_Message', 'betgameUpdated');
		$message->setHeadline('Tippspiel aktualisiert');
		$message->setMessage('Es wurden insgesamt ' . $calculatedBets .' Tipps ausgewertet.');
		return $message;
	}

	/**
	 * Versendet eine Meldung an die Netzwerke bei Erstellung einer neuen MatchNote.
	 * Die Nachricht wird nur unter bestimmten Bedingungen angeschickt:
	 * - der Typ muss konfiguriert sein
	 * - Es muss ein Livetickerspiel sein
	 * Die Implementierung ist derzeit fest auf Twitter ausgelegt.
	 * 
	 * @param tx_cfcleague_models_MatchNote $ticker
	 */
	public function sendLiveTicker($ticker) {
		// Zuerst das Spiel holen. Tickermeldungen nur von Spielen, die als Liveticker verlinkt sind
		$match = $this->getLiveMatch4Ticker($ticker);
		if(!$match) return; // Nix zu tun

		if(!$this->isTickerable($match, $ticker))
			return;

		$accounts = tx_t3socials_srv_ServiceRegistry::getNetworkService()->findAccounts('liveticker');
		if(empty($accounts)) return;

		$message = $this->buildTickerMessage($match, $ticker);
		if($message)
			tx_t3socials_srv_ServiceRegistry::getNetworkService()->sendMessage($message, 'liveticker');
//			$this->sendMessage($message, $accounts);
		
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
		$msg = $this->buildGenericMessage($match, $ticker);
		return $msg;
	}
	/**
	 * 
	 * @param tx_cfcleague_models_Match $match
	 * @param tx_cfcleague_models_MatchNote $ticker
	 * @return tx_t3socials_models_Message
	 */
	protected function buildGenericMessage($match, $ticker) {
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
	 * @param tx_cfcleague_models_MatchNote $ticker
	 * @return tx_cfcleague_models_Match
	 */
	private function getLiveMatch4Ticker($ticker) {
		// TODO: Auf basis model umstellen. Ticker fehlen noch
		$match = tx_rnbase::makeInstance('tx_cfcleaguefe_models_match', $ticker->getGame());
		if($match->record['link_ticker'])
			return $match;
//		return $match; // FIXME: REMOVE IT!!!

		return false;
	}
	
	/**
	 * Versand einer Nachricht, mit dem aktuellen Spielstatus.
	 * @param tx_cfcleague_models_Match $match
	 */
	public function sendMatchStateChanged($match) {
		if(!($match->record['link_ticker']))
			return;
		$message = $this->buildMatchStatusMessage($match, 'matchstatus');
		if($message)
			tx_t3socials_srv_ServiceRegistry::getNetworkService()->sendMessage($message, 'matchstatus');
	}
	protected function buildMatchStatusMessage($match, $trigger) {
		if(!($match->isRunning() || $match->isFinished()))
			return false;
		$message = tx_rnbase::makeInstance('tx_t3socials_models_Message', $trigger);
		$message->setData($match);

		// Spielstand
		$prefix = $match->getHomeNameShort() . '-' . $match->getGuestNameShort();
		if($match->record['status'] > 0) {
			$prefix .= ' ' . $match->getGoalsHome() .':' . $match->getGoalsGuest();
		}
		// Paarung und Spielstand als Headline
		$message->setHeadline($prefix);

		if($match->isRunning()) {
			// Anstoß
			$message->setIntro('Anstoß');
		}
		else {
			// Abpfiff
			$message->setIntro('Spielende');
		}
		return $message;
	}
}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/more4t3sports/srv/class.tx_more4t3sports_srv_Socials.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/more4t3sports/srv/class.tx_more4t3sports_srv_Socials.php']);
}

