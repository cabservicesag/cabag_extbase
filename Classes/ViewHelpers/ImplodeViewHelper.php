<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2012 Nils Blattner <nb@cabag.ch>, cab services ag
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
 * Takes an array or array like object and implodes it.
 *
 * <c:implode value="{0: 'some', 1: 'text'}" glue=", " /> -> some, text
 * <c:implode value="{0: 'b', 1: 'c', 2: 'a'}" glue="|" sort="1" /> -> a|b|c
 * <c:implode value="{3: 'b', 1: 'c', 0: 'a'}" glue=":" sort="numeric" reverse="1" sortByKeys="1" /> -> b:c:a
 */
class Tx_CabagExtbase_ViewHelpers_ImplodeViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper {

	/**
	 * Takes an array or array like object and implodes it.
	 *
	 * @param mixed $value The value to implode.
	 * @param string $glue The value to glue the values together.
	 * @param mixed $sort If set, the value is sorted. Possible values are: Any nonempty -> normal sort(), numeric -> compares numerically, string -> compares as strings, locale -> compares as locale strings.
	 * @param boolean $reverse If set, the array is reversed.
	 * @param boolean $sortByKeys If set, the array is sorted by keys instead.
	 * @return string Rendered string
	 */
	public function render($value = array(), $glue = '', $sort = false, $reverse = false, $sortByKeys = false) {
		if ($value instanceof Iterator) {
			$value = iterator_to_array($value);
		}
		
		$result = '';
		if (is_array($value)) {
			if (!empty($sort)) {
				if (!empty($sortByKeys)) {
					ksort($value, $this->resolveFlag($sort));
				} else {
					sort($value, $this->resolveFlag($sort));
				}
			}
			
			if (!empty($reverse)) {
				$values = array_reverse($value);
			}
			
			$result = implode($glue, $value);
		}
		
		return $result;
	}
	
	/**
	 * Resolves the sorting flag.
	 *
	 * @param string $type Possible values are: Any nonempty -> normal sort(), numeric -> compares numerically, string -> compares as strings, locale -> compares as locale strings 
	 * @return int The flag to use in sort().
	 */
	protected function resolveFlag($type) {
		$type = strtolower($type);
		$flag = SORT_REGULAR;
		switch ($type) {
			case 'numeric':
				$flag = SORT_NUMERIC;
				break;
			case 'string':
				$flag = SORT_STRING;
				break;
			case 'locale':
				$flag = SORT_LOCALE_STRING;
				break;
		}
		return $flag;
	}
}

