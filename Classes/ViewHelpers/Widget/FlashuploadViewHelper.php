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
 * Simple flash upload widget
 *
 * @version $Id:$
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License, version 2
 */
class Tx_CabagExtbase_ViewHelpers_Widget_FlashuploadViewHelper extends Tx_Fluid_Core_Widget_AbstractWidgetViewHelper {
	/**
	 * @var Tx_CabagExtbase_ViewHelpers_Widget_Controller_FlashuploadController
	 */
	protected $controller;

	/**
	 * @param Tx_CabagExtbase_ViewHelpers_Widget_Controller_FlashuploadController $controller
	 * @return void
	 */
	public function injectController(Tx_CabagExtbase_ViewHelpers_Widget_Controller_FlashuploadController $controller) {
		$this->controller = $controller;
	}

	/**
	 * Renders widget
	 *
	 * @param string $name Name of form element
	 * @param array $configuration Configuration array
	 * @return string
	 */
	public function render($name, array $configuration = array()) {
		return $this->initiateSubRequest();
	}
}
