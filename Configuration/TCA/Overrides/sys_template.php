<?php

if (!(defined('TYPO3') || defined('TYPO3_MODE'))) {
    exit('Access denied.');
}

call_user_func(function () {
    $extKey = 'more4t3sports';

    if (tx_rnbase_util_TYPO3::isExtLoaded('news')) {
        tx_rnbase_util_Extensions::addStaticFile($extKey, 'Configuration/Typoscript/news/', 'T3sports with news');
    } elseif (tx_rnbase_util_TYPO3::isExtLoaded('tt_news')) {
        tx_rnbase_util_Extensions::addStaticFile($extKey, 'Configuration/Typoscript/tt_news/', 'T3sports with tt_news');
    }
});
