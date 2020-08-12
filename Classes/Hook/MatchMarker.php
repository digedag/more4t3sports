<?php

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2015-2018 Rene Nitzsche (rene@system25.de)
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

class Tx_More4t3sports_Hook_MatchMarker
{

    /**
     * Integrates output of preview and matchreport fields to matches.
     *
     * @param array $params
     * @param tx_cfcleaguefe_util_MatchMarker $parent
     */
    public function addNewsRecords($params, $parent)
    {
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
    protected function addNews($match, $template, $markerPrefix, $confId, $formatter, $fieldName)
    {
        $confId = $confId . $fieldName . '.';
        $markerPrefix = $markerPrefix . '_' . strtoupper($fieldName);
        if (! tx_rnbase_util_BaseMarker::containsMarker($template, $markerPrefix)) {
            return $template;
        }

        $configurations = $formatter->getConfigurations();
        $pluginRendered = false;
        $newsExt = null;
        if (tx_rnbase_util_TYPO3::isExtLoaded('news')) {
            $newsExt = 'news';
            // Wird ein Plugin verwendet?
            if ($pluginUid = $configurations->get($confId.'_template.newsPlugin', true)) {
                $newsReport = $this->renderContent($configurations, $pluginUid, $match->getProperty($fieldName));
                $pluginRendered = true;
            }
        }
        elseif (tx_rnbase_util_TYPO3::isExtLoaded('tt_news')) {
            $newsExt = 'tt_news';
        }
        if (!$newsExt) {
            return $template;
        }

        if (!$pluginRendered) {
            // Use marker template
            $newsTemplate = tx_rnbase_util_Templates::getSubpartFromFile($configurations->get($confId . '_template.path'), $configurations->get($confId . '_template.subpartName'));
            $item = $this->loadNews($match->getProperty($fieldName), $newsExt);

            $newsReport = '';
            if ($item) {
                /* @var $marker tx_rnbase_util_SimpleMarker */
                $marker = tx_rnbase::makeInstance('tx_rnbase_util_SimpleMarker');
                $newsReport = $marker->parseTemplate($newsTemplate, $item, $formatter, $confId, 'NEWS');
            }
        }

        $markerArray = [
            '###' . $markerPrefix . '###' => $newsReport
        ];
        $template = tx_rnbase_util_Templates::substituteMarkerArrayCached($template, $markerArray);
        return $template;
    }

    protected function renderContent(\Sys25\RnBase\Configuration\ConfigurationInterface $configurations, int $contentUid, int $newsUid)
    {
        $ttContent = tx_rnbase_util_TYPO3::getSysPage()->checkRecord(
            'tt_content',
            $contentUid
        );
        $cObj = $configurations->getCObj('news');
        // http://t3s87.local/index.php?id=62&tx_news_pi1%5Bnews%5D=1&tx_news_pi1%5Bcontroller%5D=News&tx_news_pi1%5Baction%5D=detail
        // jetzt das contentelement parsen
        $cObj->start($ttContent, 'tt_content');
        $GLOBALS['TSFE']->register['T3SPORTS_NEWSUID'] = $newsUid;

        $content = $cObj->cObjGetSingle('<tt_content', []);
        return $content;
    }
    /**
     *
     * @param int $uid
     * @param string $newsExt
     * @return \tx_rnbase_model_base
     */
    protected function loadNews($uid, $newsExt)
    {
        $table = $newsExt == 'news' ? 'tx_news_domain_model_news' : 'tt_news';
        $options = [
            'wrapperclass' => 'tx_rnbase_model_base',
            'where' => 'uid = ' . $uid
        ];
        $items = Tx_Rnbase_Database_Connection::getInstance()->doSelect('*', $table, $options);
        return empty($items) ? NULL : reset($items);
    }
}
