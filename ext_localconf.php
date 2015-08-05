<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

$TYPO3_CONF_VARS['FE']['eID_include']['tx_cabagextbase_flashupload'] = 'EXT:cabag_extbase/Classes/eID/Flashupload.php';

if (version_compare(TYPO3_version, '6.2', '>=')) {
	$TYPO3_CONF_VARS['FE']['eID_include']['tx_cabagextbase_fixformtoken'] = 'EXT:cabag_extbase/Classes/eID/FixFormToken.php';
}