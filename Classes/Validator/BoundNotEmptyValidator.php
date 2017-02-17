<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2013 Nils Blattner <nb@cabag.ch>
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
 * Validate that at least a certain amount of fields is not empty.
 */
class Tx_CabagExtbase_Validator_BoundNotEmptyValidator extends \TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator {
	/**
	 * @var array Stores the values.
	 */
	protected static $info = array();
	
	/**
	 * This contains the supported options, their default values, types and descriptions.
	 *
	 * @var array
	 */
	protected $supportedOptions = array(
		'key' => array('', 'The key to validate', 'string', TRUE), 
		'total' => array('', 'The total amount', 'string', TRUE), 
		'minimum' => array('', 'The  minimum amount', 'string', TRUE)
	);
	
	
	/**
	 * Returns TRUE, the minimum amount of fields is non empty.
	 *
	 * If total is 5 (in total 5 fields are bound this way) and at least 2 must be non-empty, but in effect all are empty, only the last two called fields will return an error.
	 *
	 * @param mixed $value The value that should be validated
	 * @return boolean If there are enough possible non empty fields left
	 */
	public function isValid($value) {
		$key = (isset($this->options['key'])) ? $this->options['key'] : 0;
		$total = (isset($this->options['total'])) ? $this->options['total'] : 1;
		$minimum = (isset($this->options['minimum'])) ? $this->options['minimum'] : 1;
		$result = false;
		
		if (!isset(self::$info[$key])) {
			self::$info[$key] = array(
				'hit' => 0,
				'nonempty' => 0
			);
		}
		self::$info[$key]['hit']++;
		self::$info[$key]['nonempty'] += !empty($value);
		
		if (($total - (self::$info[$key]['hit'] - self::$info[$key]['nonempty'])) < $minimum) {
			$this->addError('Of ' . $total . ' fields at least ' . $minimum . ' must be filled out!', 1377005798);
		} else {
			$result = true;
		}
		
		return $result;
	}
}

