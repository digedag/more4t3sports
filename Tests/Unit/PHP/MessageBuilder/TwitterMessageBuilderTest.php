<?php

namespace Sys25\More4T3sports\Tests\MessageBuilder;

use tx_rnbase;

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

class TwitterMessageBuilderTest extends tx_phpunit_testcase
{
    public function testBuildTickerMessageExact()
    {
        $builder = new DummyTwitterMessageBuilder();
        $account = tx_rnbase::makeInstance('tx_t3socials_models_Network', ['uid' => 1]);
        $message = tx_rnbase::makeInstance('tx_t3socials_models_Message', 'liveticker');

        // Jetzt genau auf 140 Zeichen testen
        $message->setHeadline('BVB Borussia Dormund-Chemnitzer FC 10:13'); // 40 Zeichen
        $message->setIntro('Das sind einunddreissig Zeichen. Das sind einunddreissig Zeichen. Das sind einunddreissig Zeichen..'); // 99 Zeichen
        $msg = $builder->buildTickerMessage($message, $account, 'twitter.');
        $this->assertEquals(140, strlen($msg));

        $message->setHeadline('BVB Borussia Dormund-Chemnitzer FC 10:13'); // 40 Zeichen
        $message->setIntro('Das sind einunddreissig Zeichen. Das sind einunddreissig Zeichen.'); // 65 Zeichen
        $message->setMessage('Das sind einunddreissig Zeichen..'); // 33 Zeichen
        $msg = $builder->buildTickerMessage($message, $account, 'twitter.');
        $this->assertEquals(140, strlen($msg));

        // Jetzt genau auf 141 Zeichen testen
        $message->setHeadline('BVB Borussia Dormund-Chemnitzer FC 10:13'); // 40 Zeichen
        $message->setIntro('Das sind einunddreissig Zeichen. Das sind einunddreissig Zeichen. Das sind einunddreissig Zeichen...'); // 100 Zeichen
        $msg = $builder->buildTickerMessage($message, $account, 'twitter.');
        $this->assertEquals(0, strlen($msg));
    }

    public function testBuildTickerMessage()
    {
        $account = tx_rnbase::makeInstance('tx_t3socials_models_Network', ['uid' => 1]);

        $message = tx_rnbase::makeInstance('tx_t3socials_models_Message', 'liveticker');
        $message->setHeadline('BVB-CFC 0:3');
        $message->setUrl('http://derserver.de'); // Genau 19 Zeichen
        $message->setMessage('Super!');
        $message->setIntro('Tor durch Fink');

        $builder = new DummyTwitterMessageBuilder();
        $msg = $builder->buildTickerMessage($message, $account, 'twitter.');
        $this->assertTrue(strlen($msg) <= 140);
        $this->assertEquals('BVB-CFC 0:3 Tor durch Fink Super! http://derserver.de', $msg);

        $message->setMessage('Ein super Tooor durch Fiiink!!! Das ist ein langer Kommentar, der abgeschnitten werden muss, damit die Meldung noch bei Twitter reinpasst.');
        $msg = $builder->buildTickerMessage($message, $account, 'twitter.');
        $this->assertTrue(strlen($msg) <= 140);
        // Die URL ist hier raus
        $this->assertEquals('BVB-CFC 0:3 Tor durch Fink Ein super Tooor durch Fiiink!!! Das ist ein langer Kommentar, der abgeschnitten werden muss, damit die Meldung...', $msg);

        $message->setIntro('');
        $msg = $builder->buildTickerMessage($message, $account, 'twitter.');
        $this->assertTrue(strlen($msg) <= 140);
        // Die URL ist hier raus
        $this->assertEquals('BVB-CFC 0:3 Ein super Tooor durch Fiiink!!! Das ist ein langer Kommentar, der abgeschnitten werden muss, damit die Meldung noch bei...', $msg, 'Meldung ohne Intro mit langem Kommentar');

        $message->setMessage('');
        $msg = $builder->buildTickerMessage($message, $account, 'twitter.');
        $this->assertTrue(strlen($msg) <= 140);
        // Die URL ist hier raus
        $this->assertEquals('BVB-CFC 0:3 http://derserver.de', $msg, 'Meldung ohne Intro und Kommentar');
    }
}
