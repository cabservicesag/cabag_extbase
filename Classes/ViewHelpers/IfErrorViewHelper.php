<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2011 Nils Blattner <nb@cabag.ch>, cab services ag
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
 * Error messages view helper, only renders the content once!
 *
 * 	<c:ifError for="object.property">
 * 		<div class="errorMessage">
 * 			This is an error message.
 * 		</div>
 * 	</c:ifError>
 */
class Tx_CabagExtbase_ViewHelpers_IfErrorViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper {

	/**
	 * Renders the content in case there is an error.
	 *
	 * @param string $for The name of the error name (e.g. argument name or property name). This can also be a property path (like blog.title), and will then only display the validation errors of that property.
	 * @return string Rendered string
	 * @api
	 */
	public function render($for = '') {
		$errors = $this->controllerContext->getRequest()->getErrors();
		if ($for !== '') {
			$propertyPath = explode('.', $for);
			foreach ($propertyPath as $currentPropertyName) {
				$errors = $this->getErrorsForProperty($currentPropertyName, $errors);
			}
		}
		
		$output = '';
		if (count($errors)) {
			$output = $this->renderChildren();
		}
		return $output;
	}

	/**
	 * Find errors for a specific property in the given errors array
	 *
	 * @param string $propertyName The property name to look up
	 * @param array $errors An array of Tx_Fluid_Error_Error objects
	 * @return array An array of errors for $propertyName
	 * @author Christopher Hlubek <hlubek@networkteam.com>
	 */
	protected function getErrorsForProperty($propertyName, $errors) {
		foreach ($errors as $error) {
			if ($error instanceof Tx_Extbase_Validation_PropertyError) {
				if ($error->getPropertyName() === $propertyName) {
					return $error->getErrors();
				}
			}
		}
		return array();
	}
}

