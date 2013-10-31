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
 * Resolves a given path over a given object (or array).
 *
 * <c:objectAccessor subject="{someModelObject}" path="field.subfield.someTextField" />
 * c:objectAccessor(subject: someModelObject, path: 'field.subfield.someTextField')
 */
class Tx_CabagExtbase_ViewHelpers_ObjectAccessorViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper {

	/**
	 * Renders the given value as json.
	 *
	 * @param mixed $subject The subject to resolve the path upon.
	 * @param string $path The path to get.
	 * @return mixed The resolved item.
	 */
	public function render($subject, $path) {
		return $this->getPropertyPath($subject, $path);
	}

	/**
	 * Copied (and adapted) from Tx_Fluid_Core_Parser_SyntaxTree_ObjectAccessorNode
	 *
	 * @see Tx_Fluid_Core_Parser_SyntaxTree_ObjectAccessorNode::getPropertyPath()
	 */
	protected function getPropertyPath($subject, $propertyPath) {
		$propertyPathSegments = explode('.', $propertyPath);
		foreach ($propertyPathSegments as $pathSegment) {
			if (is_object($subject) && Tx_Extbase_Reflection_ObjectAccess::isPropertyGettable($subject, $pathSegment)) {
				$subject = Tx_Extbase_Reflection_ObjectAccess::getProperty($subject, $pathSegment);
			} elseif ((is_array($subject) || $subject instanceof ArrayAccess) && isset($subject[$pathSegment])) {
				$subject = $subject[$pathSegment];
			} else {
				return NULL;
			}
		}
		return $subject;
	}
}

?>
