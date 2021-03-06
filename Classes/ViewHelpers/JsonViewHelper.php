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
 * Render a given value as a json array.
 *
 * <c:json value="{some: 'array'}" />
 */
class Tx_CabagExtbase_ViewHelpers_JsonViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper {

	/**
	 * Renders the given value as json.
	 *
	 * @param mixed $value The value to render as json.
	 * @param int $options The options to use when encoding, only works in PHP 5.3 and up (optional) @see http://ch2.php.net/manual/en/function.json-encode.php
	 * @return string Rendered string
	 */
	public function render($value = array(), $options = 0) {
		$options = intval($options);
		
		if (version_compare(phpversion(), '5.3', '>=')) {
			return json_encode($value, $options);
		} else {
			return json_encode($value);
		}
	}
}

