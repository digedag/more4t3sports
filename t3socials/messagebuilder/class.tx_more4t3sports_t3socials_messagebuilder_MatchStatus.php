<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2015-2018 Rene Nitzsche <rene@system25.de>
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
tx_rnbase::load('tx_t3socials_trigger_IMessageBuilder');

/**
 * Message Builder für Spielstatus
 */
class tx_more4t3sports_t3socials_messagebuilder_MatchStatus implements tx_t3socials_trigger_IMessageBuilder
{

    public function buildGenericMessage(tx_t3socials_models_Base $model)
    {
        // Not used
    }

    /**
     *
     * @param tx_cfcleague_models_Match $match
     * @return tx_t3socials_models_Message
     */
    public function buildGenericMatchStatusMessage($match, $trigger)
    {
        if (! ($match->isRunning() || $match->isFinished())) {
            return false;
        }
        /* @var $message tx_t3socials_models_Message */
        $message = tx_rnbase::makeInstance('tx_t3socials_models_Message', $trigger);
        $message->setData($match);

        // Spielstand
        $prefix = $match->getHomeNameShort() . '-' . $match->getGuestNameShort();
        if ($match->getProperty('status') > 0) {
            $prefix .= ' ' . $match->getGoalsHome() . ':' . $match->getGoalsGuest();
        }
        // Paarung und Spielstand als Headline
        $message->setHeadline($prefix);

        if ($match->isRunning()) {
            // Anstoß
            $message->setIntro('Anstoß');
        } else {
            // Abpfiff
            $message->setIntro('Spielende');
        }
        return $message;
    }

    /**
     * Spezielle Netzwerk und Triggerabhängige Dinge durchführen.
     *
     * @param
     *            tx_t3socials_models_IMessage &$message
     * @param tx_t3socials_models_Network $network
     * @param tx_t3socials_models_TriggerConfig $trigger
     * @return void
     */
    public function prepareMessageForNetwork(tx_t3socials_models_IMessage $message, tx_t3socials_models_Network $network, tx_t3socials_models_TriggerConfig $trigger)
    {
        // FIXME: flexibel gestalten
        $url = $network->getConfigData($network->getNetwork() . '.liveticker.message.url');
        $message->setUrl($url);
    }
}
