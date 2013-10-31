<?php

/*                                                                        *
 * This script is part of the TYPO3 project - inspiring people to share!  *
 *                                                                        *
 * TYPO3 is free software; you can redistribute it and/or modify it under *
 * the terms of the GNU General Public License version 2 as published by  *
 * the Free Software Foundation.                                          *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General      *
 * Public License for more details.                                       *
 *                                                                        */

/**
 * Simulate t3lib_utility_Debug::debug() so that it can be used in the BE aswell to "pretty print" stuff.
 *
 * = Examples =
 *
 * <code title="Simple">
 * {namespace c=Tx_CabagExtbase_ViewHelpers}<c:debug>{testVariables.array}</c:debug>
 * </code>
 * <output>
 * foobarbazfoo
 * </output>
 */
class Tx_CabagExtbase_ViewHelpers_DebugViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper {

	/**
	 * Simulate t3lib_utility_Debug::debug() so that it can be used in the BE aswell to "pretty print" stuff.
	 *
	 * @param string $title
	 * @return string the altered string.
	 * @author Nils Blattner <nb@cabag.ch>
	 */
	public function render($title = NULL) {
		$debug = t3lib_utility_Debug::convertVariableToString($this->renderChildren());
		if ($title) {
			$debug = sprintf(t3lib_utility_Debug::DEBUG_TABLE_TEMPLATE, htmlspecialchars((string) $title), $debug);
		}
		return $debug;
	}
}


?>
