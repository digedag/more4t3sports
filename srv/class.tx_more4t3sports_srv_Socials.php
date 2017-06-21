<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013-2017 Rene Nitzsche (rene@system25.de)
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

tx_rnbase::load('Tx_Rnbase_Service_Base');
tx_rnbase::load('tx_rnbase_util_DB');
tx_rnbase::load('tx_rnbase_util_Logger');


/**
 * Service for accessing network account information
 *
 * @author Rene Nitzsche
 */
class tx_more4t3sports_srv_Socials extends Tx_Rnbase_Service_Base {

	/**
	 * Versenden eine Twittermeldung bei Aktualisierung des Tippspiels
	 *
	 * @param tx_t3sportsbet_models_Betgame $betgame
	 * @param int $calculatedBets
	 */
	public function sendBetGameUpdated($betgame, $calculatedBets) {
		$trigger = 'betgameUpdated';
		$accounts = tx_t3socials_srv_ServiceRegistry::getNetworkService()->findAccounts($trigger);
		if(empty($accounts)) return;

		$builder = tx_rnbase::makeInstance('tx_more4t3sports_t3socials_betgame_MessageBuilder');
		// Die generische Message bauen
		$message = $builder->buildGenericBetGameUpdated($betgame, $calculatedBets);
		tx_rnbase::load('tx_t3socials_trigger_Config');
		/* @var tx_t3socials_models_TriggerConfig $triggerConfig */
		$triggerConfig = tx_t3socials_trigger_Config::getTriggerConfig($trigger);

		return tx_t3socials_srv_ServiceRegistry::getNetworkService()->sendMessage($message, $accounts, $builder, $triggerConfig);
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

		$trigger = 'liveticker';
		$accounts = tx_t3socials_srv_ServiceRegistry::getNetworkService()->findAccounts($trigger);
		if(empty($accounts)) return;

		$builder = tx_rnbase::makeInstance('tx_more4t3sports_t3socials_ticker_MessageBuilder');
		// Die generische Message bauen
		$message = $builder->buildTickerMessage($match, $ticker);

		tx_rnbase::load('tx_t3socials_trigger_Config');
		/* @var tx_t3socials_models_TriggerConfig $triggerConfig */
		$triggerConfig = tx_t3socials_trigger_Config::getTriggerConfig($trigger);

		if($message)
			return tx_t3socials_srv_ServiceRegistry::getNetworkService()->sendMessage($message, $accounts, $builder, $triggerConfig);
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
		$trigger = 'matchstatus';
		if(!($match->getProperty('link_ticker')))
			return;

		$accounts = tx_t3socials_srv_ServiceRegistry::getNetworkService()->findAccounts($trigger);
		if(empty($accounts)) return;

		$builder = tx_rnbase::makeInstance('tx_more4t3sports_t3socials_messagebuilder_MatchStatus');
		$message = $builder->buildGenericMatchStatusMessage($match, $trigger);

		if($message) {
			tx_rnbase::load('tx_t3socials_trigger_Config');
			/* @var tx_t3socials_models_TriggerConfig $triggerConfig */
			$triggerConfig = tx_t3socials_trigger_Config::getTriggerConfig($trigger);
			$states = tx_t3socials_srv_ServiceRegistry::getNetworkService()->sendMessage($message, $accounts, $builder, $triggerConfig);
			$this->logSuccessfulNotifications($states, $trigger);
		}
	}
	protected function logSuccessfulNotifications($states, $trigger) {
		$infos = array();
		foreach ($states As $status) {
			if($status->isStateSuccess()) {
				$infos[] = $status->getMessage();
			}
		}
		if(!empty($infos))
			tx_rnbase_util_Logger::warn('Notification for '.$trigger.' send!', 'more4t3sports', $infos);
	}

}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/more4t3sports/srv/class.tx_more4t3sports_srv_Socials.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/more4t3sports/srv/class.tx_more4t3sports_srv_Socials.php']);
}

