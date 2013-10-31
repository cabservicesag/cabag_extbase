<?php

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Nils Blattner <nb@cabag.ch>, cab services ag
 *  
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
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
 *
 *
 * @package cabag_extbase
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class Tx_CabagExtbase_ViewHelpers_SelectViewHelper extends Tx_Fluid_ViewHelpers_Form_SelectViewHelper {

	/**
	 * Initialize arguments.
	 *
	 * @return void
	 * @api
	 */
	public function initializeArguments() {
		parent::initializeArguments();
		$this->registerArgument('emptyOption', 'string', 'Default option to display.', FALSE, '');
		$this->registerArgument('emptyOptionValue', 'string', 'Default option value.', FALSE, '0');
	}

	/**
	 * Render the option tags.
	 *
	 * @param array $options the options for the form.
	 * @return string rendered tags.
	 */
	protected function renderOptionTags($options) {
		$output = parent::renderOptionTags($options);
		
		if (!empty($this->arguments['emptyOption'])) {
			$output = $this->renderOptionTag($this->arguments['emptyOptionValue'], $this->arguments['emptyOption']) . $output;
		}
		
		return $output;
	}
}	
