<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2012 Nils Blattner <nb@cabag.ch>
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
 * Abstract class for wizicon classes.
 */
abstract class tx_CabagExtbase_Utility_AbstractWizicon {
	/**
	 * @var array The locallang values.
	 */
	protected static $localLang = null;
	
	/**
	 * Must return the extension key that the wizicons will be generated for.
	 */
	abstract function getExtensionKey();
	
	/**
	 * Adds the plugin wizard icon
	 *
	 * @param array Input array with wizard items for plugins
	 * @return array Modified input array, having the additional items.
	 */
	public abstract function proc($wizardItems);
	
	/**
	 * Returns the wizard elements for a given plugin signature.
	 *
	 * @param string $pluginSignature The plugin signature (results for cabagextbase_results).
	 * @return array The wizard elements.
	 */
	protected function getWizard($pluginSignature) {
		return array(
			'icon' => $this->getIcon($pluginSignature),
			'title' => $this->getTitle($pluginSignature),
			'description' => $this->getDescription($pluginSignature),
			'params' => '&defVals[tt_content][CType]=list&defVals[tt_content][list_type]=' . str_replace('_', '', $this->getExtensionKey()) . '_' . $pluginSignature
		);
	}
	
	/**
	 * Returns the translated text.
	 *
	 * @param string $key The locallang key.
	 * @return string The translated text.
	 */
	function translate($key) {
		if (self::$localLang === null) {
			self::$localLang = array();
		}
		
		$extensionKey = $this->getExtensionKey();
		if (!isset(self::$localLang[$extensionKey])) {
			$file = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($extensionKey) . 'Resources/Private/Language/locallang_db.xml';
			self::$localLang[$extensionKey] = \TYPO3\CMS\Core\Utility\GeneralUtility::readLLfile($file, $GLOBALS['LANG']->lang);
		}
		
		return $GLOBALS['LANG']->getLLL($key, self::$localLang[$extensionKey]);
	}
	
	/**
	 * Returns the title for a given plugin.
	 *
	 * @param string $pluginSignature The plugin signature (results for cabagextbase_results).
	 * @return string The title for the given plugin signature.
	 */
	function getTitle($pluginSignature) {
		return $this->translate('plugin.' . $pluginSignature);
	}
	
	/**
	 * Returns the description for a given plugin.
	 *
	 * @param string $pluginSignature The plugin signature (results for cabagextbase_results).
	 * @return string The description for the given plugin signature.
	 */
	function getDescription($pluginSignature) {
		return $this->translate('plugin.' . $pluginSignature . '.description');
	}
	
	/**
	 * Returns the icon for a given plugin.
	 *
	 * @param string $pluginSignature The plugin signature (results for cabagextbase_results).
	 * @return string The icon path for the given plugin signature.
	 */
	function getIcon($pluginSignature) {
		return \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($this->getExtensionKey()) . 'Resources/Public/Icons/Wizicon_' . $pluginSignature . '.gif';
	}
}
