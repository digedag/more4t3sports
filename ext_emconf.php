<?php

########################################################################
# Extension Manager/Repository config file for ext "rn_base".
#
# Auto generated 14-01-2012 13:44
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'More for T3sports',
	'description' => 'Useful extensions for T3sports.',
	'category' => 'misc',
	'shy' => 0,
	'version' => '0.3.0',
	'dependencies' => 'cms',
	'conflicts' => '',
	'priority' => '',
	'loadOrder' => '',
	'module' => '',
	'state' => 'stable',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearcacheonload' => 0,
	'lockType' => '',
	'author' => 'Rene Nitzsche',
	'author_email' => 'rene@system25.de',
	'author_company' => 'System 25',
	'CGLcompliance' => '',
	'CGLcompliance_note' => '',
	'constraints' => array(
		'depends' => array(
			'typo3' => '4.5.0-6.2.99',
			'php' => '5.3.7-5.6.99',
			'rn_base' => '0.15.0-0.0.0',
			'cfc_league' => '1.0.2-0.0.0',
		),
		'conflicts' => array(
		),
		'suggests' => array(
			't3socials' => '1.0.5-0.0.0',
			'tt_news' => '3.6.0-0.0.0',
		),
	),
	'_md5_values_when_last_written' => 'a:124:{s:9:"ChangeLog";s:4:"c2e3";s:10:"README.txt";s:4:"b282";s:19:"class.tx_rnbase.php";s:4:"03c5";s:34:"class.tx_rnbase_configurations.php";s:4:"a688";s:30:"class.tx_rnbase_controller.php";s:4:"8cdb";s:30:"class.tx_rnbase_parameters.php";s:4:"06fe";s:21:"ext_conf_template.txt";s:4:"0867";s:12:"ext_icon.gif";s:4:"1bdc";s:17:"ext_localconf.php";s:4:"04c5";s:13:"locallang.xml";s:4:"a61a";s:38:"action/class.tx_rnbase_action_Base.php";s:4:"f7a7";s:41:"action/class.tx_rnbase_action_BaseIOC.php";s:4:"b83e";s:53:"action/class.tx_rnbase_action_CacheHandlerDefault.php";s:4:"b544";s:50:"action/class.tx_rnbase_action_CacheHandlerUser.php";s:4:"1217";s:47:"action/class.tx_rnbase_action_ICacheHandler.php";s:4:"be01";s:38:"cache/class.tx_rnbase_cache_ICache.php";s:4:"8901";s:39:"cache/class.tx_rnbase_cache_Manager.php";s:4:"4a97";s:39:"cache/class.tx_rnbase_cache_NoCache.php";s:4:"a759";s:42:"cache/class.tx_rnbase_cache_TYPO3Cache.php";s:4:"c004";s:44:"cache/class.tx_rnbase_cache_TYPO3Cache46.php";s:4:"f6a9";s:19:"doc/wizard_form.dat";s:4:"49ea";s:20:"doc/wizard_form.html";s:4:"f188";s:44:"filter/class.tx_rnbase_filter_BaseFilter.php";s:4:"0409";s:44:"filter/class.tx_rnbase_filter_FilterItem.php";s:4:"04a7";s:50:"filter/class.tx_rnbase_filter_FilterItemMarker.php";s:4:"dd4c";s:37:"maps/class.tx_rnbase_maps_BaseMap.php";s:4:"bc11";s:35:"maps/class.tx_rnbase_maps_Coord.php";s:4:"d42b";s:43:"maps/class.tx_rnbase_maps_DefaultMarker.php";s:4:"3140";s:37:"maps/class.tx_rnbase_maps_Factory.php";s:4:"f0f7";s:38:"maps/class.tx_rnbase_maps_IControl.php";s:4:"d095";s:36:"maps/class.tx_rnbase_maps_ICoord.php";s:4:"9c36";s:35:"maps/class.tx_rnbase_maps_IIcon.php";s:4:"539c";s:34:"maps/class.tx_rnbase_maps_IMap.php";s:4:"e7ac";s:37:"maps/class.tx_rnbase_maps_IMarker.php";s:4:"3462";s:42:"maps/class.tx_rnbase_maps_TypeRegistry.php";s:4:"9925";s:34:"maps/class.tx_rnbase_maps_Util.php";s:4:"eb4f";s:51:"maps/google/class.tx_rnbase_maps_google_Control.php";s:4:"56ab";s:48:"maps/google/class.tx_rnbase_maps_google_Icon.php";s:4:"4d50";s:47:"maps/google/class.tx_rnbase_maps_google_Map.php";s:4:"4a60";s:38:"misc/class.tx_rnbase_misc_EvalDate.php";s:4:"7ad5";s:39:"mod/class.tx_rnbase_mod_BaseModFunc.php";s:4:"1852";s:38:"mod/class.tx_rnbase_mod_BaseModule.php";s:4:"4c5a";s:43:"mod/class.tx_rnbase_mod_ExtendedModFunc.php";s:4:"f840";s:38:"mod/class.tx_rnbase_mod_IDecorator.php";s:4:"25b0";s:36:"mod/class.tx_rnbase_mod_IModFunc.php";s:4:"41ec";s:39:"mod/class.tx_rnbase_mod_IModHandler.php";s:4:"6a81";s:35:"mod/class.tx_rnbase_mod_IModule.php";s:4:"2eab";s:34:"mod/class.tx_rnbase_mod_Tables.php";s:4:"582e";s:32:"mod/class.tx_rnbase_mod_Util.php";s:4:"79cf";s:17:"mod/locallang.xml";s:4:"7bf8";s:17:"mod/template.html";s:4:"4577";s:44:"mod/base/class.tx_rnbase_mod_base_Lister.php";s:4:"4e67";s:36:"model/class.tx_rnbase_model_base.php";s:4:"2776";s:37:"model/class.tx_rnbase_model_media.php";s:4:"1c8f";s:37:"plot/class.tx_rnbase_plot_Builder.php";s:4:"fb2a";s:42:"plot/class.tx_rnbase_plot_DataProvider.php";s:4:"8f14";s:44:"plot/class.tx_rnbase_plot_DataProviderTS.php";s:4:"0b37";s:43:"plot/class.tx_rnbase_plot_IDataProvider.php";s:4:"72f4";s:22:"res/simplegallery.html";s:4:"7c49";s:32:"sv1/class.tx_rnbase_sv1_Base.php";s:4:"cddf";s:39:"sv1/class.tx_rnbase_sv1_MediaPlayer.php";s:4:"b15b";s:17:"sv1/dewplayer.swf";s:4:"4e96";s:21:"sv1/ext_localconf.php";s:4:"dfee";s:47:"tests/class.tx_rnbase_tests_Logger_testcase.php";s:4:"6967";s:51:"tests/class.tx_rnbase_tests_basemarker_testcase.php";s:4:"7b6a";s:46:"tests/class.tx_rnbase_tests_cache_testcase.php";s:4:"073a";s:49:"tests/class.tx_rnbase_tests_calendar_testcase.php";s:4:"34dc";s:55:"tests/class.tx_rnbase_tests_configurations_testcase.php";s:4:"27c7";s:52:"tests/class.tx_rnbase_tests_listbuilder_testcase.php";s:4:"8be4";s:45:"tests/class.tx_rnbase_tests_misc_testcase.php";s:4:"a46c";s:47:"tests/class.tx_rnbase_tests_rnbase_testcase.php";s:4:"2dd4";s:56:"tests/class.tx_rnbase_tests_util_SearchBase_testcase.php";s:4:"f294";s:53:"tests/class.tx_rnbase_tests_util_Strings_testcase.php";s:4:"015c";s:17:"tests/phpunit.xml";s:4:"50af";s:75:"tests/fixtures/classes/class.tx_rnbase_tests_fixtures_classes_Decorator.php";s:4:"5913";s:69:"tests/fixtures/classes/class.tx_rnbase_tests_fixtures_classes_Mod.php";s:4:"007c";s:55:"tests/mod/class.tx_rnbase_tests_mod_Tables_testcase.php";s:4:"29c5";s:57:"tests/model/class.tx_rnbase_tests_model_Base_testcase.php";s:4:"2cb2";s:53:"tests/util/class.tx_rnbase_tests_util_DB_testcase.php";s:4:"25a1";s:56:"tests/util/class.tx_rnbase_tests_util_Dates_testcase.php";s:4:"0a69";s:62:"tests/util/class.tx_rnbase_tests_util_PageBrowser_testcase.php";s:4:"a71d";s:60:"tests/util/class.tx_rnbase_tests_util_Templates_testcase.php";s:4:"24a9";s:36:"util/class.tx_rnbase_util_Arrays.php";s:4:"8be1";s:37:"util/class.tx_rnbase_util_BEPager.php";s:4:"4812";s:40:"util/class.tx_rnbase_util_BaseMarker.php";s:4:"3b9f";s:38:"util/class.tx_rnbase_util_Calendar.php";s:4:"61c9";s:32:"util/class.tx_rnbase_util_DB.php";s:4:"d075";s:35:"util/class.tx_rnbase_util_Dates.php";s:4:"efde";s:35:"util/class.tx_rnbase_util_Debug.php";s:4:"54cd";s:39:"util/class.tx_rnbase_util_Exception.php";s:4:"2774";s:35:"util/class.tx_rnbase_util_Files.php";s:4:"0523";s:38:"util/class.tx_rnbase_util_FormTool.php";s:4:"29a5";s:38:"util/class.tx_rnbase_util_FormUtil.php";s:4:"3b0d";s:40:"util/class.tx_rnbase_util_FormatUtil.php";s:4:"7dc0";s:43:"util/class.tx_rnbase_util_IListProvider.php";s:4:"e168";s:34:"util/class.tx_rnbase_util_Json.php";s:4:"de4a";s:34:"util/class.tx_rnbase_util_Link.php";s:4:"49ac";s:41:"util/class.tx_rnbase_util_ListBuilder.php";s:4:"3706";s:45:"util/class.tx_rnbase_util_ListBuilderInfo.php";s:4:"a19a";s:40:"util/class.tx_rnbase_util_ListMarker.php";s:4:"a678";s:44:"util/class.tx_rnbase_util_ListMarkerInfo.php";s:4:"4a40";s:42:"util/class.tx_rnbase_util_ListProvider.php";s:4:"ab96";s:36:"util/class.tx_rnbase_util_Logger.php";s:4:"9658";s:41:"util/class.tx_rnbase_util_MediaMarker.php";s:4:"3fea";s:34:"util/class.tx_rnbase_util_Misc.php";s:4:"090c";s:41:"util/class.tx_rnbase_util_PageBrowser.php";s:4:"fce3";s:47:"util/class.tx_rnbase_util_PageBrowserMarker.php";s:4:"342a";s:35:"util/class.tx_rnbase_util_Queue.php";s:4:"9edb";s:40:"util/class.tx_rnbase_util_SearchBase.php";s:4:"4051";s:43:"util/class.tx_rnbase_util_SearchGeneric.php";s:4:"02f3";s:42:"util/class.tx_rnbase_util_SimpleMarker.php";s:4:"e971";s:34:"util/class.tx_rnbase_util_Spyc.php";s:4:"aa5d";s:37:"util/class.tx_rnbase_util_Strings.php";s:4:"46ca";s:35:"util/class.tx_rnbase_util_TSDAM.php";s:4:"3ced";s:35:"util/class.tx_rnbase_util_TYPO3.php";s:4:"9268";s:39:"util/class.tx_rnbase_util_Templates.php";s:4:"5c54";s:45:"util/db/class.tx_rnbase_util_db_Exception.php";s:4:"d144";s:45:"util/db/class.tx_rnbase_util_db_IDatabase.php";s:4:"d65c";s:41:"util/db/class.tx_rnbase_util_db_MySQL.php";s:4:"c853";s:41:"util/db/class.tx_rnbase_util_db_TYPO3.php";s:4:"00f6";s:34:"view/class.tx_rnbase_view_Base.php";s:4:"c6f6";s:34:"view/class.tx_rnbase_view_List.php";s:4:"5656";s:36:"view/class.tx_rnbase_view_Single.php";s:4:"0942";s:47:"view/class.tx_rnbase_view_phpTemplateEngine.php";s:4:"498d";}',
	'suggests' => array(
	),
);
