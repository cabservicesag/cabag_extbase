<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "cabag_extbase".
 *
 * Auto generated 22-01-2015 16:23
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array (
	'title' => 'Extbase Library',
	'description' => 'Extbase library extension',
	'category' => 'plugin',
	'author' => 'Nils Blattner',
	'author_email' => 'nb@cabag.ch',
	'author_company' => 'cab AG',
	'state' => 'stable',
	'uploadfolder' => 0,
	'createDirs' => '',
	'clearCacheOnLoad' => 0,
	'version' => '1.0.1',
	'constraints' =>
	array (
		'depends' =>
		array (
			'cms' => '',
			'extbase' => '',
			'fluid' => '',
		),
		'conflicts' =>
		array (
		),
		'suggests' =>
		array (
		),
	),
	'_md5_values_when_last_written' => 'a:81:{s:13:"ChangeLog.txt";s:4:"7c7c";s:16:"ext_autoload.php";s:4:"0e77";s:21:"ext_conf_template.txt";s:4:"5dbe";s:12:"ext_icon.gif";s:4:"e922";s:17:"ext_localconf.php";s:4:"8082";s:14:"ext_tables.php";s:4:"c85e";s:14:"ext_tables.sql";s:4:"e1c0";s:10:"README.txt";s:4:"b1b1";s:37:"Classes/Controller/BaseController.php";s:4:"3af8";s:38:"Classes/Domain/Model/StaticCountry.php";s:4:"4fe4";s:42:"Classes/Domain/Model/StaticCountryZone.php";s:4:"4346";s:39:"Classes/Domain/Model/StaticLanguage.php";s:4:"0469";s:53:"Classes/Domain/Repository/StaticCountryRepository.php";s:4:"4e92";s:57:"Classes/Domain/Repository/StaticCountryZoneRepository.php";s:4:"24ac";s:54:"Classes/Domain/Repository/StaticLanguageRepository.php";s:4:"6729";s:26:"Classes/Object/Manager.php";s:4:"758b";s:36:"Classes/Persistence/QueryFactory.php";s:4:"008e";s:34:"Classes/Persistence/Repository.php";s:4:"2a0d";s:51:"Classes/Persistence/Storage/CabagTypo3DbBackend.php";s:4:"1c1b";s:35:"Classes/Utility/AbstractWizicon.php";s:4:"c377";s:24:"Classes/Utility/File.php";s:4:"29a6";s:27:"Classes/Utility/Logging.php";s:4:"0557";s:24:"Classes/Utility/Mail.php";s:4:"736b";s:44:"Classes/Validator/BoundNotEmptyValidator.php";s:4:"9238";s:36:"Classes/Validator/TwiceValidator.php";s:4:"3740";s:39:"Classes/ViewHelpers/CdataViewHelper.php";s:4:"1173";s:42:"Classes/ViewHelpers/Date2CalViewHelper.php";s:4:"a242";s:44:"Classes/ViewHelpers/DatepickerViewHelper.php";s:4:"f602";s:39:"Classes/ViewHelpers/DebugViewHelper.php";s:4:"b43b";s:40:"Classes/ViewHelpers/EscapeViewHelper.php";s:4:"3848";s:38:"Classes/ViewHelpers/FormViewHelper.php";s:4:"79c7";s:44:"Classes/ViewHelpers/IfContainsViewHelper.php";s:4:"b9f8";s:41:"Classes/ViewHelpers/IfErrorViewHelper.php";s:4:"9f26";s:38:"Classes/ViewHelpers/IfOrViewHelper.php";s:4:"21cf";s:43:"Classes/ViewHelpers/ImageLazyViewHelper.php";s:4:"625e";s:41:"Classes/ViewHelpers/ImplodeViewHelper.php";s:4:"5829";s:38:"Classes/ViewHelpers/JsonViewHelper.php";s:4:"19a9";s:48:"Classes/ViewHelpers/ObjectAccessorViewHelper.php";s:4:"2959";s:45:"Classes/ViewHelpers/PregReplaceViewHelper.php";s:4:"b9bb";s:37:"Classes/ViewHelpers/RawViewHelper.php";s:4:"22ed";s:40:"Classes/ViewHelpers/SelectViewHelper.php";s:4:"a23c";s:42:"Classes/ViewHelpers/StrftimeViewHelper.php";s:4:"57c1";s:45:"Classes/ViewHelpers/Format/HtmlViewHelper.php";s:4:"b0b7";s:47:"Classes/ViewHelpers/Link/LightboxViewHelper.php";s:4:"092e";s:52:"Classes/ViewHelpers/Widget/FlashuploadViewHelper.php";s:4:"d432";s:63:"Classes/ViewHelpers/Widget/Controller/FlashuploadController.php";s:4:"6337";s:27:"Classes/eID/Flashupload.php";s:4:"f0ca";s:22:"Classes/eID/upload.log";s:4:"5023";s:25:"Configuration/TCA/tca.php";s:4:"eb44";s:34:"Configuration/TypoScript/setup.txt";s:4:"0918";s:33:"Migrations/Code/ClassAliasMap.php";s:4:"87e0";s:40:"Resources/Private/Language/locallang.xml";s:4:"368a";s:43:"Resources/Private/Language/locallang_db.xml";s:4:"ade7";s:44:"Resources/Private/Mail/Logging/Template.html";s:4:"5d37";s:43:"Resources/Private/Mail/Logging/Template.txt";s:4:"9183";s:69:"Resources/Private/Templates/ViewHelpers/Widget/Flashupload/Index.html";s:4:"aea4";s:35:"Resources/Public/Icons/relation.gif";s:4:"e615";s:34:"Resources/Public/Images/button.png";s:4:"9f46";s:52:"Resources/Public/Images/defaultStaticCountryFlag.gif";s:4:"d3e9";s:43:"Resources/Public/JavaScript/fileprogress.js";s:4:"ac2c";s:39:"Resources/Public/JavaScript/handlers.js";s:4:"c0c5";s:36:"Resources/Public/JavaScript/pager.js";s:4:"b5ce";s:40:"Resources/Public/JavaScript/swfupload.js";s:4:"469b";s:46:"Resources/Public/JavaScript/swfupload.queue.js";s:4:"9953";s:56:"Resources/Public/JavaScript/swfupload/Core Changelog.txt";s:4:"d484";s:59:"Resources/Public/JavaScript/swfupload/swfupload license.txt";s:4:"f368";s:50:"Resources/Public/JavaScript/swfupload/swfupload.js";s:4:"3ed0";s:62:"Resources/Public/JavaScript/swfupload/Documentation/index.html";s:4:"057c";s:54:"Resources/Public/JavaScript/swfupload/Flash/deploy.bat";s:4:"1d7a";s:59:"Resources/Public/JavaScript/swfupload/Flash/ExternalCall.as";s:4:"b134";s:55:"Resources/Public/JavaScript/swfupload/Flash/FileItem.as";s:4:"0c76";s:64:"Resources/Public/JavaScript/swfupload/Flash/SWFUpload v2.as3proj";s:4:"f95d";s:56:"Resources/Public/JavaScript/swfupload/Flash/SWFUpload.as";s:4:"12c6";s:57:"Resources/Public/JavaScript/swfupload/Flash/swfupload.swf";s:4:"bd5a";s:70:"Resources/Public/JavaScript/swfupload/Flash/obj/SWFUpload-v2Config.old";s:4:"e86c";s:70:"Resources/Public/JavaScript/swfupload/Flash/obj/SWFUpload-v2Config.xml";s:4:"e86c";s:67:"Resources/Public/JavaScript/swfupload/plugins/SWFObject License.txt";s:4:"5645";s:66:"Resources/Public/JavaScript/swfupload/plugins/swfupload.cookies.js";s:4:"f694";s:64:"Resources/Public/JavaScript/swfupload/plugins/swfupload.queue.js";s:4:"83b6";s:64:"Resources/Public/JavaScript/swfupload/plugins/swfupload.speed.js";s:4:"d840";s:68:"Resources/Public/JavaScript/swfupload/plugins/swfupload.swfobject.js";s:4:"9cf8";}',
);
