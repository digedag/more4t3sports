<?php

namespace Sys25\More4T3sports\T3socials\MessageBuilder;

use Sys25\RnBase\Utility\Misc;
use tx_rnbase;
use tx_t3socials_models_Base;
use tx_t3socials_models_IMessage;
use tx_t3socials_models_Message;
use tx_t3socials_models_Network;
use tx_t3socials_models_TriggerConfig;
use tx_t3socials_trigger_IMessageBuilder;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2014-2023 Rene Nitzsche <rene@system25.de>
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
 * Message Builder für Tipspiel-Update von T3sportsbet.
 */
class BetgameMessageBuilder implements tx_t3socials_trigger_IMessageBuilder
{
    private $betgame;

    public function buildGenericMessage(tx_t3socials_models_Base $model)
    {
        // Not used
    }

    public function buildGenericBetGameUpdated($betgame, $calculatedBets)
    {
        // Das wird nochmal für den Link benötigt
        $this->betgame = $betgame;
        /**
         * @var tx_t3socials_models_Message $message
         */
        $message = tx_rnbase::makeInstance('tx_t3socials_models_Message', 'betgameUpdated');
        $message->setHeadline('Tippspiel aktualisiert');
        $message->setMessage('Es wurden insgesamt '.$calculatedBets.' Tipps ausgewertet.');

        return $message;
    }

    /**
     * Spezielle Netzwerk und Triggerabhängige Dinge durchführen.
     *
     * @param tx_t3socials_models_IMessage $message
     * @param tx_t3socials_models_Network $network
     * @param tx_t3socials_models_TriggerConfig $trigger
     *
     * @return void
     */
    public function prepareMessageForNetwork(tx_t3socials_models_IMessage $message, tx_t3socials_models_Network $network, tx_t3socials_models_TriggerConfig $trigger)
    {
        // Warum ist das abhängig vom Account?
        $config = $network->getConfigurations();
        Misc::prepareTSFE();
        $link = $config->createLink();
        $link->designatorString = 't3sportsbet'; // tx_ttnews[tt_news]
        $link->initByTS($config, $network->getNetwork().'.betgameUpdated.link.show.', []);
        $url = $link->makeUrl(false);
        $message->setUrl($url);
    }
}
