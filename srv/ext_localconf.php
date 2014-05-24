<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

require_once(t3lib_extMgm::extPath('rn_base') . 'class.tx_rnbase.php');
tx_rnbase::load('tx_more4t3sports_srv_Registry');
tx_rnbase::load('tx_rnbase_util_SearchBase');

t3lib_extMgm::addService($_EXTKEY,  't3sports_srv' /* sv type */,  'tx_more4t3sports_srv_Socials' /* sv key */,
	array(
		'title' => 'Social networks', 'description' => 'Handles communications with social networks', 
		'subtype' => 'socials',
		'available' => TRUE, 'priority' => 50, 'quality' => 50,
		'os' => '', 'exec' => '',
		'classFile' => t3lib_extMgm::extPath($_EXTKEY).'srv/class.tx_more4t3sports_srv_Socials.php',
		'className' => 'tx_more4t3sports_srv_Socials',
	)
);


