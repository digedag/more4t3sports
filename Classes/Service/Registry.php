<?php

namespace Sys25\More4T3sports\Service;

use tx_rnbase;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2012-2023 Rene Nitzsche (rene@system25.de)
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

/**
 * Access a service instance.
 *
 * @deprecated use DI
 */
class Registry
{
    private $socialsService;

    public function __construct(
        ?T3socialsService $socialsService = null
    ) {
        $this->socialsService = $socialsService;
    }

    /**
     * @return self
     */
    private static function getInstance()
    {
        return tx_rnbase::makeInstance(Registry::class);
    }

    /**
     * Liefert den Network-Service.
     *
     * @return T3socialsService
     */
    public static function getSocialService()
    {
        return self::getInstance()->socialsService;
    }
}
