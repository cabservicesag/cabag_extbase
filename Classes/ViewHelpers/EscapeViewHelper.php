<?php

/*                                                                        *
 * This script belongs to the FLOW3 package "Fluid".                      *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License as published by the *
 * Free Software Foundation, either version 3 of the License, or (at your *
 * option) any later version.                                             *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser       *
 * General Public License for more details.                               *
 *                                                                        *
 * You should have received a copy of the GNU Lesser General Public       *
 * License along with the script.                                         *
 * If not, see http://www.gnu.org/licenses/lgpl.html                      *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

/**
 * The EscapeViewHelper is used to escape variable content in various ways. By
 * default HTML is the target.
 *
 * = Examples =
 *
 * <code title="HTML">
 * <f:escape>{text}</f:escape>
 * </code>
 * <output>
 * Text with & " ' < > * replaced by HTML entities (htmlspecialchars applied).
 * </output>
 *
 * <code title="Entities">
 * <f:escape type="entities">{text}</f:escape>
 * </code>
 * <output>
 * Text with all possible chars replaced by HTML entities (htmlentities applied).
 * </output>
 *
 * <code title="URL">
 * <f:escape type="url">{text}</f:escape>
 * </code>
 * <output>
 * Text encoded for URL use (rawurlencode applied).
 * </output>
 *
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 * @api
 */
class Tx_CabagExtbase_ViewHelpers_EscapeViewHelper extends Tx_Fluid_ViewHelpers_EscapeViewHelper {

	protected $specials = array(
		'laquo',
		'raquo',
		'lsaquo',
		'rsaquo',
		'ldquo',
		'rdquo',
		'lsquo',
		'rsquo',
		'ndash',
		'deg',
		'nbsp',
		'shy'
	);
	
	/**
	 * Escapes special characters with their escaped counterparts as needed.
	 *
	 * @param string $value
	 * @param string $type The type, one of html, entities, url
	 * @param string $encoding
	 * @return string the altered string.
	 * @author Karsten Dambekalns <karsten@typo3.org>
	 * @api
	 */
	public function render($value = NULL, $type = 'html', $encoding = NULL) {
		$result = parent::render($value, $type, $encoding);
		
		if ($type === 'html' || $type === 'entities') {
			$result = preg_replace('/&amp;(' . implode('|', $this->specials) . ');/', '&$1;', $result);
		}
		
		return $result;
	}
}
