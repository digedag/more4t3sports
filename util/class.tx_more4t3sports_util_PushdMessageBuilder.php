<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013-2017 Rene Nitzsche (rene@system25.de)
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

tx_rnbase::load('tx_rnbase_util_Logger');
tx_rnbase::load('tx_t3socials_network_pushd_MessageBuilder');

/**
 * Baut PushNotifications für Sportmeldungen. Derzeit nur Liveticker.
 */
class tx_more4t3sports_util_PushdMessageBuilder extends tx_t3socials_network_pushd_MessageBuilder
{
    /**
     * Creates a tweet from generic message.
     *
     * @param tx_t3socials_models_IMessage $message
     * @param tx_t3socials_models_Network $account
     * @param string $confId
     *
     * @return array
     */
    public function build($message, $account, $confId)
    {
        if ('liveticker' == $message->getMessageType()) {
            return $this->buildTickerMessage($message, $account, $confId);
        }

        return null;
    }

    /**
     * Wichtig ist die Festlegung des Event-Typs. Bei den Tickermeldungen,
     * sollen verschiedene Events angeboten werden, damit man z.B. nur Tore
     * abonnieren kann.
     *
     * @param tx_t3socials_models_IMessage $message
     * @param tx_t3socials_models_Network $account
     * @param string $confId
     *
     * @return array
     */
    protected function buildTickerMessage($message, $account, $confId)
    {
        $data = [];
        // alle Tags entfernen
        // Wenn ein Intro vorhanden ist, wird dieses bevorzugt.
        $msg = htmlspecialchars_decode(strip_tags(trim($message->getIntro() ? $message->getIntro() : $message->getMessage())), ENT_QUOTES);
        $title = htmlspecialchars_decode(strip_tags(trim($message->getHeadline())), ENT_QUOTES);
        $charsAvailable = 50;
        $msg = self::cropText($msg, $charsAvailable, '...', true);

        $data['title'] = $title;
        $data['msg'] = $msg;
        // Die Spielaktion auf einen Event mappen
        $ticker = $message->getData();
        if (!$ticker) {
            return;
        }
        // liveticker.eventmap
        $event = $this->findEvent($ticker, $account->getConfigData($confId.'eventmap.'));
        if (!$event) {
            return false;
        } // Ohne Event wird nix verschickt.

        $data['event'] = $event;

        return $data;
    }

    /**
     * @param unknown_type $ticker
     * @param unknown_type $mappings
     */
    protected function findEvent($ticker, $mappings)
    {
        $type = $ticker->getType();
        foreach ($mappings as $event => $typeString) {
            $types = array_flip(Tx_Rnbase_Utility_Strings::trimExplode(',', $typeString));
            if (array_key_exists($type, $types)) {
                return $event;
            }
        }

        return false;
    }
}
