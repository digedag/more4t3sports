<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2012 Rene Nitzsche <rene@system25.de>
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

require_once(t3lib_extMgm::extPath('cfc_league') . 'class.tx_cfcleague_db.php');

class tx_more4t3sports_hooks_T3sportsBet {

	public function analyseBets($params, $parent) {

		$calculatedBets = $params['calculatedBets'];
		if(!$calculatedBets) return;
		$betgame = $params['betgame'];
		// Nachricht twittern
		tx_more4t3sports_srv_Registry::getSocialService()->sendBetGameUpdated($betgame, $calculatedBets);
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/more4t3sports/hooks/class.tx_more4t3sports_hooks_T3sportsBet.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/more4t3sports/hooks/class.tx_more4t3sports_hooks_T3sportsBet.php']);
}

