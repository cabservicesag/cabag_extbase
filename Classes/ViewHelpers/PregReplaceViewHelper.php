<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2012 Tizian Schmidlin <st@cabag.ch>, cab services ag
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
 * The PregReplaceViewHelpber is used to replace content in a source.
 *
 * <c:pregReplace from="/<[^>]*>([^<]*)<\/[^>]>/" to="$1">...</c:pregReplace>
 *
 */
class Tx_CabagExtbase_ViewHelpers_PregReplaceViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper {

	/**
	 * Escapes special characters with their escaped counterparts as needed.
	 *
	 * @return string the altered string.
	 * @param string $from ID
	 * @param string $to name
	 * @author Tizian Schmidlin <st@cabag.ch>
	 * @api
	 */
	public function render($from, $to) {
		return preg_replace($from, $to, $this->renderChildren());
	}
}
?>
