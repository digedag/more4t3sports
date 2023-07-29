<?php

if (!(defined('TYPO3') || defined('TYPO3_MODE'))) {
    exit('Access denied.');
}

call_user_func(function () {
    $extKey = 'more4t3sports';

    if (Sys25\RnBase\Utility\Extensions::isLoaded('news')) {
        Sys25\RnBase\Utility\Extensions::addStaticFile($extKey, 'Configuration/Typoscript/news/', 'T3sports with news');
    } elseif (Sys25\RnBase\Utility\Extensions::isLoaded('tt_news')) {
        Sys25\RnBase\Utility\Extensions::addStaticFile($extKey, 'Configuration/Typoscript/tt_news/', 'T3sports with tt_news');
    }
});
