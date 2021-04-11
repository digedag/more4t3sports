<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2014-2019 Rene Nitzsche <rene@system25.de>
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
 * Message Builder für Tickermeldungen von T3sports.
 */
class tx_more4t3sports_t3socials_ticker_MessageBuilder implements tx_t3socials_trigger_IMessageBuilder
{
    public function buildGenericMessage(tx_t3socials_models_Base $model)
    {
        // Not used
    }

    /**
     * @param tx_cfcleague_models_Match $match
     * @param tx_cfcleague_models_MatchNote $ticker
     *
     * @return tx_t3socials_models_IMessage
     */
    public function buildTickerMessage($match, $ticker)
    {
        // Alle Ticker laden
        tx_rnbase::load('tx_cfcleaguefe_util_MatchTicker');
        $tickerArr = &tx_cfcleaguefe_util_MatchTicker::getTicker4Match($match);
        $tickerArr = array_reverse($tickerArr);
        $found = false;
        foreach ($tickerArr as $matchTicker) {
            if ($matchTicker->uid == $ticker->uid) {
                $found = true;
                $ticker = $matchTicker;
                break;
            }
        }
        if (!$found) {
            return false;
        }
        $msg = $this->buildGenericMessage4Ticker($match, $ticker);

        return $msg;
    }

    /**
     * @param tx_cfcleague_models_Match $match
     * @param tx_cfcleague_models_MatchNote $ticker
     *
     * @return tx_t3socials_models_Message
     */
    protected function buildGenericMessage4Ticker($match, $ticker)
    {
        $message = tx_rnbase::makeInstance('tx_t3socials_models_Message', 'liveticker');
        $message->setData($ticker);

        // Spielstand
        $prefix = $match->getHomeNameShort().'-'.$match->getGuestNameShort();
        if ($match->getProperty('status') > 0 || $ticker->getMinute() > 0) {
            // Das Ergebnis aus dem Ticker lesen, da es aktueller ist
            $prefix .= ' '.$ticker->getProperty('goals_home').':'.$ticker->getProperty('goals_guest');
            // $prefix .= ' ' . $match->getGoalsHome() .':' . $match->getGoalsGuest();
        }
        // Paarung und Spielstand als Headline
        $message->setHeadline($prefix);

        $player = $ticker->getPlayerInstance();
        if (!(is_object($player) && $player->isValid())) {
            $player = false;
        }

        $msg = '';
        switch ($ticker->getType()) {
            case 10:
            case 11:
            case 12:
            case 13:
                if (!$player) {
                    return; // Tor ohne Spieler geht nicht
                }
                $msg .= 'Tor durch '.$player->getName();
                break;
            case 30:
                if (!$player) {
                    return; // Tor ohne Spieler geht nicht
                }
                $msg .= 'Eigentor durch '.$player->getName();
                break;
            case 70:
                if (!$player) {
                    return; // Tor ohne Spieler geht nicht
                }
                $msg .= 'Gelbe Karte für '.$player->getName();
                break;
            case 71:
                if (!$player) {
                    return; // Tor ohne Spieler geht nicht
                }
                $msg .= 'Gelb-Rot für '.$player->getName();
                break;
            case 72:
                if (!$player) {
                    return; // Tor ohne Spieler geht nicht
                }
                $msg .= 'Rote Karte für '.$player->getName();
                break;
            // default:
            // $msg .= $player->getName();
        }

        // Die automatische Meldung ist der Subtitle
        $message->setIntro($msg);
        $message->setMessage($ticker->getProperty('comment'));

        return $message;
    }

    /**
     * @param tx_cfcleague_models_MatchNote $ticker
     *
     * @return tx_cfcleague_models_Match
     */
    private function getLiveMatch4Ticker($ticker)
    {
        // TODO: Auf basis model umstellen. Ticker fehlen noch
        $match = tx_rnbase::makeInstance('tx_cfcleaguefe_models_match', $ticker->getGame());
        if ($match->getProperty('link_ticker')) {
            return $match;
        }

        return false;
    }

    /**
     * Spezielle Netzwerk und Triggerabhängige Dinge durchführen.
     *
     * @param
     *            tx_t3socials_models_IMessage &$message
     * @param tx_t3socials_models_Network $network
     * @param tx_t3socials_models_TriggerConfig $trigger
     *
     * @return void
     */
    public function prepareMessageForNetwork(tx_t3socials_models_IMessage $message, tx_t3socials_models_Network $network, tx_t3socials_models_TriggerConfig $trigger)
    {
        // FIXME: flexibel gestalten
        $url = $network->getConfigData($network->getNetwork().'.liveticker.message.url');
        $message->setUrl($url);
    }
}
