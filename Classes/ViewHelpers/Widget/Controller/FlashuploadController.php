<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2011 Dimitri König <dk@cabag.ch>
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
 * @version $Id:$
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License, version 2
 */
class Tx_CabagExtbase_ViewHelpers_Widget_Controller_FlashuploadController extends Tx_Fluid_Core_Widget_AbstractWidgetController {
	/**
	 * Extension path
	 * @var string
	 */
	public $extensionPath = '';

	/**
	 * Javascript path
	 * @var string
	 */
	public $jsPath = '';

	/**
	 * @return void
	 */
	public function initializeAction() {
		$this->extensionPath = t3lib_extMgm::siteRelPath('cabag_extbase');
		$this->jsPath = $this->extensionPath . 'Resources/Public/JavaScript/';

		$GLOBALS['TSFE']->additionalHeaderData['cabag_extbase'] = '
			<script type="text/javascript" src="' . $this->jsPath . 'swfupload/swfupload.js"></script>
			<script type="text/javascript" src="' . $this->jsPath . 'swfupload/plugins/swfupload.queue.js"></script>
			<script type="text/javascript" src="' . $this->jsPath . 'handlers.js"></script>
		';
	}

	/**
	 * @return void
	 */
	public function indexAction() {
		$config = t3lib_div::array_merge_recursive_overrule($this->settings['flashupload'], $this->widgetConfiguration['configuration'], TRUE);
		$name = $this->widgetConfiguration['name'];
		$uniqueID = md5($this->widgetConfiguration['name']);
		$uploadsDir = $config['uploadsDir'];
		$verification = md5($name . $uploadsDir . $GLOBALS['TYPO3_CONF_VARS']['SYS']['encryptionKey']);
		$lookupid = rawurlencode($GLOBALS['TSFE']->fe_user->id.'-'.md5($GLOBALS['TSFE']->fe_user->id.'/'.$GLOBALS['TYPO3_CONF_VARS']['SYS']['encryptionKey']));

		$this->view->assign('extensionPath', $this->extensionPath);
		$this->view->assign('jsPath', $this->jsPath);
		$this->view->assign('config', $config);
		$this->view->assign('verification', $verification);
		$this->view->assign('lookupid', $lookupid);
		$this->view->assign('name', $name);
		$this->view->assign('uniqueID', $uniqueID);
	}
}

?>