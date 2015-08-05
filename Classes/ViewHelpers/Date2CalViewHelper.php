<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2012 Sonja Scholz <sonja.scholz@typo3.org>, cab services ag
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
 * The Date2CalViewHelper is used to simply use date2cal extension in a template. By
 * default HTML is the target.
 *
 *<cab:Date2Cal id="wishedEndDateTime" prefix="tx_jamesservices" name="tx_jamesservices_pi1[newOrder][singleorders][{service.uid}][wishedEndDateTime]" />
 *
 */
class Tx_CabagExtbase_ViewHelpers_Date2CalViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper {

	/**
	 * Escapes special characters with their escaped counterparts as needed.
	 *
	 * @return string the altered string.
	 * @param string $id ID
	 * @param string $name name
	 * @param string $prefix prefix
	 * @param string $dateFormat The strftime date format.
	 * @author Sonja Scholz <sonja.scholz@typo3.org>
	 * @api
	 */
	public function render($id, $name, $prefix, $dateFormat = '%d.%m.%y %H:%M') {
		$id = $id.rand();
		include_once(t3lib_extMgm::siteRelPath('date2cal') . '/src/class.jscalendar.php');
		
		$JSCalendar = JSCalendar::getInstance();
		
		$JSCalendar->setLanguage($GLOBALS['TSFE']->language);
		$JSCalendar->setInputField($id);
		$JSCalendar->setDateFormat(false, $dateFormat);
		
		$date2calCalendar = $JSCalendar->render('');
		
		$date2calCalendar = str_replace('name="'.$id.'_hr"', 'name="'.$name.'"',$date2calCalendar);
		
		if (($jsCode = $JSCalendar->getMainJS()) != '') {
			$GLOBALS['TSFE']->additionalHeaderData[$prefix] = $jsCode;
		}
		
		return htmlspecialchars_decode($date2calCalendar);
	}
}
