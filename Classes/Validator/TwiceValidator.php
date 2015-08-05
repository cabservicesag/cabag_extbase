<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2011 Nils Blattner <nb@cabag.ch>
*  All rights reserved
*
*  This class is a backport of the corresponding class of FLOW3.
*  All credits go to the v5 team.
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
 * Validate that two consecutive values entered are the same.
 */
class Tx_CabagExtbase_Validator_TwiceValidator extends Tx_Extbase_Validation_Validator_AbstractValidator {
	/**
	 * @var array Stores the values.
	 */
	protected static $values = array();
	
	/**
	 * This contains the supported options, their default values, types and descriptions.
	 *
	 * @var array
	 */
	protected $supportedOptions = array(
		'key' => array('', 'The key to validate', 'string', TRUE),
	);
	
	/**
	 * Returns TRUE, if two consecutive values match.
	 *
	 * Always returns true for the first value.
	 *
	 * @param mixed $value The value that should be validated
	 * @return boolean If the two values match
	 */
	public function isValid($value) {
		$key = (isset($this->options['key'])) ? $this->options['key'] : 0;
		if (!isset(self::$values[$key])) {
			self::$values[$key] = $value;
			return true;
		}
		
		$result = false;
		
		if (self::$values[$key] === $value) {
			$result = true;
		} else {
			$this->addError('The values (' . self::$values[$key] . ' <> ' . $value . ') do not match!', 1252561046);
		}
		
		unset(self::$values[$key]);
		return $result;
	}
}

