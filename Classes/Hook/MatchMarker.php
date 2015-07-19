<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2015 Rene Nitzsche (rene@system25.de)
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

tx_rnbase::load('tx_rnbase_util_BaseMarker');

class Tx_More4t3sports_Hook_MatchMarker {
	/**
	 * Integrates output of preview and matchreport fields to matches.
	 * @param array $params
	 * @param tx_cfcleaguefe_util_MatchMarker $parent
	 */
	public function addNewsRecords($params, $parent) {
		$template = $params['template'];
		$match = $params['match'];
		$formatter = $params['formatter'];
		$confId = $params['confid'];

		$template = $this->addNews($match, $template, $params['marker'], $confId, $formatter, 'newsreport');
		$template = $this->addNews($match, $template, $params['marker'], $confId, $formatter, 'newspreview');
		$params['template'] = $template;
	}
	/**
	 *
	 * @param tx_cfcleague_models_Match $match
	 * @param string $template
	 * @param string $markerPrefix
	 * @param string $confId
	 * @param tx_rnbase_util_FormatUtil $formatter
	 */
	protected function addNews($match, $template, $markerPrefix, $confId, $formatter, $fieldName) {
		$confId = $confId.$fieldName.'.';
		$markerPrefix = $markerPrefix.'_'.strtoupper($fieldName);
		if(!tx_rnbase_util_BaseMarker::containsMarker($template, $markerPrefix)) {
			return $template;
		}
		$configurations = $formatter->getConfigurations();
		$newsTemplate = tx_rnbase_util_Templates::getSubpartFromFile(
			$configurations->get($confId.'_template.path'),
			$configurations->get($confId.'_template.subpartName'));

		$item = $this->loadNews($match->record[$fieldName]);
		$newsReport = '';
		if($item) {
			/* @var $marker tx_rnbase_util_SimpleMarker */
			$marker = tx_rnbase::makeInstance('tx_rnbase_util_SimpleMarker');
			$newsReport = $marker->parseTemplate($newsTemplate, $item, $formatter, $confId, 'NEWS');
		}
		$markerArray = array(
				'###'.$markerPrefix.'###' => $newsReport,
		);
		$template = tx_rnbase_util_Templates::substituteMarkerArrayCached($template, $markerArray);
		return $template;
	}

	/**
	 *
	 * @param int $uid
	 * @return Ambigous <NULL, tx_rnbase_model_base>
	 */
	protected function loadNews($uid) {
		$options = array(
				'wrapperclass' => 'tx_rnbase_model_base',
				'where' => 'uid = '.$uid,
		);
		$items = tx_rnbase_util_DB::doSelect('*', 'tt_news', $options);
		return empty($items) ? NULL : reset($items);
	}
}