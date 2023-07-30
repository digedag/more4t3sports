<?php

namespace Sys25\More4T3sports\Hook;

use Sys25\RnBase\Configuration\ConfigurationInterface;
use Sys25\RnBase\Database\Connection;
use Sys25\RnBase\Domain\Model\BaseModel;
use Sys25\RnBase\Frontend\Marker\BaseMarker;
use Sys25\RnBase\Frontend\Marker\FormatUtil;
use Sys25\RnBase\Frontend\Marker\SimpleMarker;
use Sys25\RnBase\Frontend\Marker\Templates;
use Sys25\RnBase\Utility\TYPO3;
use System25\T3sports\Frontend\Marker\MatchMarker;
use System25\T3sports\Model\Fixture;
use tx_rnbase;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2015-2023 Rene Nitzsche (rene@system25.de)
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

class MatchMarkerHook
{
    /**
     * Integrates output of preview and matchreport fields to matches.
     *
     * @param array $params
     * @param MatchMarker $parent
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
     * @param Fixture $match
     * @param string $template
     * @param string $markerPrefix
     * @param string $confId
     * @param FormatUtil $formatter
     */
    protected function addNews($match, $template, $markerPrefix, $confId, $formatter, $fieldName)
    {
        $confId = $confId.$fieldName.'.';
        $markerPrefix = $markerPrefix.'_'.strtoupper($fieldName);
        if (!BaseMarker::containsMarker($template, $markerPrefix)) {
            return $template;
        }

        $configurations = $formatter->getConfigurations();
        $pluginRendered = false;
        $newsExt = null;
        if (TYPO3::isExtLoaded('news')) {
            $newsExt = 'news';
            // Wird ein Plugin verwendet?
            if ($pluginUid = $configurations->get($confId.'_template.newsPlugin', true)) {
                $newsReport = $this->renderContent($configurations, $pluginUid, $match->getProperty($fieldName));
                $pluginRendered = true;
            }
        } elseif (TYPO3::isExtLoaded('tt_news')) {
            $newsExt = 'tt_news';
        }
        if (!$newsExt) {
            return $template;
        }

        if (!$pluginRendered) {
            // Use marker template
            $newsTemplate = Templates::getSubpartFromFile($configurations->get($confId.'_template.path'), $configurations->get($confId.'_template.subpartName'));
            $item = $this->loadNews($match->getProperty($fieldName), $newsExt);

            $newsReport = '';
            if ($item) {
                $marker = tx_rnbase::makeInstance(SimpleMarker::class);
                $newsReport = $marker->parseTemplate($newsTemplate, $item, $formatter, $confId, 'NEWS');
            }
        }

        $markerArray = [
            '###'.$markerPrefix.'###' => $newsReport,
        ];
        $template = Templates::substituteMarkerArrayCached($template, $markerArray);

        return $template;
    }

    protected function renderContent(ConfigurationInterface $configurations, int $contentUid, int $newsUid)
    {
        $ttContent = TYPO3::getSysPage()->checkRecord(
            'tt_content',
            $contentUid
        );

        $cObj = $configurations->getCObj('news');
        // http://t3s87.local/index.php?id=62&tx_news_pi1%5Bnews%5D=1&tx_news_pi1%5Bcontroller%5D=News&tx_news_pi1%5Baction%5D=detail
        // jetzt das contentelement parsen
        $cObj->start($ttContent, 'tt_content');
        // Wird vom Listener abgegriffen
        $GLOBALS['TSFE']->register['T3SPORTS_NEWSUID'] = $newsUid;

        $content = $cObj->cObjGetSingle('<tt_content', []);

        return $content;
    }

    /**
     * @param int $uid
     * @param string $newsExt
     *
     * @return BaseModel|null
     */
    protected function loadNews($uid, $newsExt)
    {
        $table = 'news' == $newsExt ? 'tx_news_domain_model_news' : 'tt_news';
        $options = [
            'wrapperclass' => BaseModel::class,
            'where' => 'uid = '.$uid,
        ];
        $items = Connection::getInstance()->doSelect('*', $table, $options);

        return empty($items) ? null : reset($items);
    }
}
