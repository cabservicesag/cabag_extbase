<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2011 Dimitri König <dk@cabag.ch>, cab services ag
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
 * IfContains view helper, only renders the content once!
 *
 * 	<c:ifContains value="value" in="objectOrArrayOrString">
 * 		<div class="doSomething">
 * 			Do something if found.
 * 		</div>
 * 	</c:ifError>
 */
class Tx_CabagExtbase_ViewHelpers_IfContainsViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractConditionViewHelper {

	/**
	 * Renders the content in case value is found in object/array
	 *
	 * @param mixed $value The value which is search for in the object/array
     * @param mixed $in Object/Array which will be searched for value
	 * @return string Rendered string
	 * @api
	 */
	public function render($value, $in) {
        $this->transformToArray($in);
        $this->transformToArray($value);

        if (count($value)) {
            foreach ($value as $singleValue) {
                if (in_array($singleValue, $in))  {
                    return $this->renderThenChild();
                }
            }
        }

        return $this->renderElseChild();
	}

    /**
     * @param mixed $var var which is transformed into array
     * @return void
     */
    public function transformToArray(&$var) {
        if (is_string($var)) {
            $var = t3lib_div::trimExplode(',', $var, true);
        }

        if (is_object($var) && $var instanceof Tx_Extbase_Persistence_ObjectStorage) {
            $uidArray = array();
            foreach ($var as $singleObject) {
            	$uidArray[] = $singleObject->getUid();
            }
            $var = $uidArray;
        }

        if (!is_array($var)) {
            $var = array();
        }
    }
}

?>
