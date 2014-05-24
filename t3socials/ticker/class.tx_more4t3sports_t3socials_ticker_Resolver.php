<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2014 Rene Nitzsche <rene@system25.de>
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
require_once t3lib_extMgm::extPath('rn_base', 'class.tx_rnbase.php');
tx_rnbase::load('tx_t3socials_models_TriggerConfig');

/**
 * Resolver of match_notes
 */
class tx_more4t3sports_t3socials_ticker_Resolver implements tx_t3socials_util_IResolver {
	/**
	 * Der Resolver lädt den zu indizierenden Datensatz aus der Datenbank.
	 *
	 * @param string $tableName
	 * @param int $uid
	 * @return object
	 */
	public function getRecord($tableName, $uid) {
		$note = tx_rnbase::makeInstance('tx_cfcleague_models_MatchNote', $id);
		return $note;
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/more4t3sports/t3socials/ticker/class.tx_more4t3sports_t3socials_ticker_Resolver.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/more4t3sports/t3socials/ticker/class.tx_more4t3sports_t3socials_ticker_Resolver.php']);
}
