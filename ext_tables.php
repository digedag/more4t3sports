<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

tx_rnbase::load('tx_rnbase_util_TYPO3');

if (tx_rnbase_util_TYPO3::isExtLoaded('news')) {
    tx_rnbase_util_Extensions::addStaticFile($_EXTKEY,'Configuration/Typoscript/news/', 'T3sports with news');
}
elseif (tx_rnbase_util_TYPO3::isExtLoaded('tt_news')) {
    tx_rnbase_util_Extensions::addStaticFile($_EXTKEY,'Configuration/Typoscript/tt_news/', 'T3sports with tt_news');
}
