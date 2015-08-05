<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2011 Michael Bossart <mb@cabag.ch>, cab services ag
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
 * 	<c:ifOr condition="value" or="othervalue">
 * 		<f:then>
 *		</f:then>
 * 	</c:ifOr>
 */
class Tx_CabagExtbase_ViewHelpers_IfOrViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractConditionViewHelper {

   /**
     * renders <f:then> child if $condition or $or is true, otherwise renders <f:else> child.
     *
     * @param boolean $condition View helper condition
     * @param boolean $or View helper condition
     * @return string the rendered string
     */
    public function render($condition, $or) {
        if ($condition || $or) {
            return $this->renderThenChild();
        } else {
            return $this->renderElseChild();
        }
    }
}

