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
require_once t3lib_extMgm::extPath('rn_base', 'class.tx_rnbase.php');
tx_rnbase::load('tx_t3socials_models_TriggerConfig');

/**
 * Trigger configuration for match_notes
 */
class tx_more4t3sports_t3socials_ticker_TriggerConfig
	extends tx_t3socials_models_TriggerConfig {

	/**
	 * @return void
	 */
	protected function initConfig() {
		parent::initConfig();
		$this->uid = 't3sports_ticker';
		$this->setProperty('trigger_id', $this->uid);
		$this->setProperty('table', 'tx_cfcleague_match_notes');
		$this->setProperty('message_builder', 'tx_more4t3sports_t3socials_ticker_MessageBuilder');
	}

}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/more4t3sports/t3socials/ticker/class.tx_more4t3sports_t3socials_ticker_TriggerConfig.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/more4t3sports/t3socials/ticker/class.tx_more4t3sports_t3socials_ticker_TriggerConfig.php']);
}
