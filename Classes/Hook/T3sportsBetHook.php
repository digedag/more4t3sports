<?php

namespace Sys25\More4T3sports\Hook;

use Sys25\More4T3sports\Service\Registry;
use Sys25\More4T3sports\Service\T3socialsService;

/***************************************************************
*  Copyright notice
*
*  (c) 2012-2023 Rene Nitzsche <rene@system25.de>
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

class T3sportsBetHook
{
    private $t3socialsService;

    public function __construct(
        T3socialsService $t3socialsService
    ) {
        $this->t3socialsService = $t3socialsService ?: Registry::getSocialService();
    }

    public function analyseBets($params, $parent)
    {
        $calculatedBets = $params['calculatedBets'];
        if (!$calculatedBets) {
            return;
        }
        $betgame = $params['betgame'];
        // Nachricht twittern
        $this->t3socialsService->sendBetGameUpdated($betgame, $calculatedBets);
    }
}
