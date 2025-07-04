<?php

namespace Sys25\More4T3sports\Service;

use Sys25\More4T3sports\T3socials\MessageBuilder\BetgameMessageBuilder;
use Sys25\More4T3sports\T3socials\MessageBuilder\MatchStatusMessageBuilder;
use Sys25\More4T3sports\T3socials\MessageBuilder\MatchTickerMessageBuilder;
use Sys25\RnBase\Utility\Logger;
use System25\T3sports\Model\Fixture;
use tx_rnbase;
use tx_t3socials_models_State;
use tx_t3socials_trigger_Config;
use tx_t3sportsbet_models_Betgame;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013-2023 Rene Nitzsche (rene@system25.de)
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
 * Service for accessing network account information.
 *
 * @author Rene Nitzsche
 */
class T3socialsService
{
    private $mtMsgBuilder;
    private $msMsgBuilder;
    private $networkSrv;

    public function __construct(
        \DMK\T3socials\Service\Network $networkSrv,
        MatchTickerMessageBuilder $mtMsgBuilder,
        MatchStatusMessageBuilder $msMsgBuilder
    ) {
        $this->networkSrv = $networkSrv;
        $this->mtMsgBuilder = $mtMsgBuilder;
        $this->msMsgBuilder = $msMsgBuilder;
    }

    /**
     * Versenden eine Twittermeldung bei Aktualisierung des Tippspiels.
     *
     * @param tx_t3sportsbet_models_Betgame $betgame
     * @param int $calculatedBets
     */
    public function sendBetGameUpdated($betgame, $calculatedBets)
    {
        $trigger = 'betgameUpdated';
        $accounts = $this->networkSrv->findAccounts($trigger);
        if (empty($accounts)) {
            return;
        }

        /** @var BetgameMessageBuilder $builder */
        $builder = tx_rnbase::makeInstance(BetgameMessageBuilder::class);
        // Die generische Message bauen
        $message = $builder->buildGenericBetGameUpdated($betgame, $calculatedBets);
        /* @var tx_t3socials_models_TriggerConfig $triggerConfig */
        $triggerConfig = tx_t3socials_trigger_Config::getTriggerConfig($trigger);

        return $this->networkSrv->sendMessage($message, $accounts, $builder, $triggerConfig);
    }

    /**
     * Versendet eine Meldung an die Netzwerke bei Erstellung einer neuen MatchNote.
     * Die Nachricht wird nur unter bestimmten Bedingungen angeschickt:
     * - der Typ muss konfiguriert sein
     * - Es muss ein Livetickerspiel sein
     * Die Implementierung ist derzeit fest auf Twitter ausgelegt.
     *
     * @param \System25\T3sports\Model\MatchNote $ticker
     */
    public function sendLiveTicker($ticker)
    {
        // Zuerst das Spiel holen. Tickermeldungen nur von Spielen, die als Liveticker verlinkt sind
        $match = $this->getLiveMatch4Ticker($ticker);
        if (!$match) {
            return; // Nix zu tun
        }

        if (!$this->isTickerable($match, $ticker)) {
            return;
        }

        $trigger = 'liveticker';
        $accounts = $this->networkSrv->findAccounts($trigger);
        if (empty($accounts)) {
            return;
        }

        // Die generische Message bauen
        $message = $this->mtMsgBuilder->buildTickerMessage($match, $ticker);

        /** @var tx_t3socials_models_TriggerConfig $triggerConfig */
        $triggerConfig = tx_t3socials_trigger_Config::getTriggerConfig($trigger);
        if ($message) {
            $this->networkSrv->sendMessage($message, $accounts, $this->mtMsgBuilder, $triggerConfig);
        }
    }

    private function isTickerable($match, $ticker)
    {
        $ignoreTypes = [
            200,
            80,
            81,
            31,
        ];
        if (in_array($ticker->getType(), $ignoreTypes)) {
            return false;
        }

        return true;

        // tx_cfcleague_match_notes.type.ticker', '100');
        // tx_cfcleague_match_notes.type.goal', '10');
        // tx_cfcleague_match_notes.type.goal.header', '11');
        // tx_cfcleague_match_notes.type.goal.penalty', '12');
        // tx_cfcleague_match_notes.type.goal.own', '30');
        // tx_cfcleague_match_notes.type.goal.assist', '31');
        // tx_cfcleague_match_notes.type.penalty.forgiven', '32');
        // tx_cfcleague_match_notes.type.corner', '33');
        // tx_cfcleague_match_notes.type.yellow', '70');
        // tx_cfcleague_match_notes.type.yellowred', '71');
        // tx_cfcleague_match_notes.type.red', '72');
        // tx_cfcleague_match_notes.type.changeout', '80');
        // tx_cfcleague_match_notes.type.changein', '81');
        // tx_cfcleague_match_notes.type.captain', '200');
    }

    /**
     * @param \System25\T3sports\Model\MatchNote $ticker
     *
     * @return Fixture|bool
     */
    private function getLiveMatch4Ticker($ticker)
    {
        // TODO: Auf basis model umstellen. Ticker fehlen noch
        /** @var Fixture $match */
        $match = tx_rnbase::makeInstance(Fixture::class, $ticker->getGame());
        if ($match->getProperty('link_ticker')) {
            return $match;
        }

        return false;
    }

    /**
     * Versand einer Nachricht, mit dem aktuellen Spielstatus.
     *
     * @param Fixture $match
     */
    public function sendMatchStateChanged($match)
    {
        $trigger = 'matchstatus';
        if (!$match->getProperty('link_ticker')) {
            return;
        }

        $accounts = $this->networkSrv->findAccounts($trigger);
        if (empty($accounts)) {
            return;
        }

        $message = $this->msMsgBuilder->buildGenericMatchStatusMessage($match, $trigger);

        if ($message) {
            /* @var tx_t3socials_models_TriggerConfig $triggerConfig */
            $triggerConfig = tx_t3socials_trigger_Config::getTriggerConfig($trigger);
            $states = $this->networkSrv->sendMessage($message, $accounts, $this->msMsgBuilder, $triggerConfig);
            $this->logSuccessfulNotifications($states, $trigger);
        }
    }

    /**
     * @param tx_t3socials_models_State[] $states
     * @param string $trigger
     */
    protected function logSuccessfulNotifications($states, $trigger)
    {
        $infos = [];
        foreach ($states as $status) {
            if ($status->isStateSuccess()) {
                $infos[] = $status->getMessage();
            }
        }

        if (!empty($infos)) {
            Logger::warn('Notification for '.$trigger.' send!', 'more4t3sports', $infos);
        }
    }
}
