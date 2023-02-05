<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2012-2017 Rene Nitzsche (rene@system25.de)
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

/**
 * Baut Twittermeldungen für Sportmeldungen. Derzeit nur Liveticker.
 */
class tx_more4t3sports_util_TwitterMessageBuilder
{
    /**
     * Creates a tweet from generic message.
     *
     * @param tx_t3socials_models_IMessage $message
     * @param tx_t3socials_models_Network $account
     * @param string $confId
     */
    public function build($message, $account, $confId)
    {
        if ('liveticker' == $message->getMessageType()) {
            return $this->buildTickerMessage($message, $account, $confId);
        }

        return null;
    }

    /**
     * Creates a tweet from generic message of liveticker
     * Beim Ticker kommt es darauf an die entscheidenden Info
     * zu liefern. Die URL hat keine Prio, weil sie immer gleich
     * ist, und dann bei anderen Meldungen kommt.
     *
     * @param tx_t3socials_models_IMessage $message
     * @param tx_t3socials_models_Network $account
     * @param string $confId
     *
     * @return string
     */
    protected function buildTickerMessage($message, $account, $confId)
    {
        $url = $message->getUrl();
        // Paarung und Spielstand als Headline
        $prefix = $message->getHeadline();
        $prefix .= ' ';

        // Die automatische Meldung ist der Subtitle
        $msg = $message->getIntro();
        $msgLen = strlen($msg);

        // Jetzt die Länge berechnen
        // 140 Gesamt, 20 Url
        // Die URL hängen wir am Ende nur ran, wenn noch Platz ist
        $chars = 140 - (strlen($prefix) + ($msgLen ? $msgLen : 0));
        if ($chars < 0) {
            return false;
        } // Reicht schon nicht.

        $msg = $prefix.$msg;
        $comment = $message->getMessage();

        if ($comment) {
            $comment = $this->cropComment($comment, $chars, '...', 1);
            $msg = trim($msg).' '.$comment;
        }

        $msg = trim($msg);
        if (!strlen($msg)) {
            return false;
        }
        // Prüfen, ob noch Platz für die URL ist (19 + 1 Leerzeichen)
        if ($url && (140 - strlen($msg)) > 19) {
            $msg .= ' '.$url;
        }

        return $msg;
    }

    /**
     * Crop text. This method is taken from TYPO3 stdWrap.
     *
     * @param string $text
     * @param int $chars maximum length of string
     * @param string $afterstring Something like "..."
     * @param bool $crop2space crop on last space character
     *
     * @return string
     */
    public static function cropText($text, $chars, $afterstring, $crop2space)
    {
        if (strlen($text) < $chars) {
            return $text;
        }
        // Kürzen
        $text = substr($text, 0, $chars - strlen($afterstring));
        $trunc_at = strrpos($text, ' ');
        $text = ($trunc_at && $crop2space) ? substr($text, 0, $trunc_at).$afterstring : $text.$afterstring;

        return $text;
    }

    private function cropComment($comment, $chars, $afterstring, $crop2space)
    {
        if (strlen($comment) < $chars) {
            return $comment;
        }
        // Kürzen
        $comment = substr($comment, 0, $chars - strlen($afterstring));
        $trunc_at = strrpos($comment, ' ');
        $comment = ($trunc_at && $crop2space) ? substr($comment, 0, $trunc_at).$afterstring : $comment.$afterstring;

        return $comment;
    }
}
