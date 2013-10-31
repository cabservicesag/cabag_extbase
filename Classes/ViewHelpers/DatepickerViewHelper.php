<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2012 Florian Mast <fm@cabag.ch>, cab services ag
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
 * The DatepickerViewHelper is used render a date picker utility input with jQuery UI. It has all the options of a common TextboxViewhelper.
 *
 * {namespace cab=Tx_CabagExtbase_ViewHelpers}<cab:datepicker property="someProperty" />
 *
 */
class Tx_CabagExtbase_ViewHelpers_DatepickerViewHelper extends Tx_Fluid_ViewHelpers_Form_TextfieldViewHelper {
	/**
	 * The calling extensions name.
	 * @var string
	 */
 	protected $callingExtensionName;

 	/**
 	 * The labels to extract from the locallang.
 	 * @var array
 	 */
 	protected $datepickerLabels = array(
		'clearText' => true,
		'clearStatus' => true,
		'closeText' => true,
		'closeStatus' => true,
		'prevText' => true,
		'prevStatus' => true,
		'nextText' => true,
		'nextStatus' => true,
		'currentText' => true,
		'currentStatus' => true,
		'monthNames' => array(
			'january',
			'february',
			'march',
			'april',
			'may',
			'june',
			'july',
			'august',
			'september',
			'october',
			'november',
			'december'
		),
		'monthNamesShort' => array(
			'january',
			'february',
			'march',
			'april',
			'may',
			'june',
			'july',
			'august',
			'september',
			'october',
			'november',
			'december'
		),
		'monthStatus' => true,
		'yearStatus' => true,
		'weekHeader' => true,
		'weekStatus' => true,
		'dayNames' => array(
			'sunday',
			'monday',
			'tuesday',
			'wednesday',
			'thursday',
			'friday',
			'saturday',
		),
		'dayNamesShort' => array(
			'sunday',
			'monday',
			'tuesday',
			'wednesday',
			'thursday',
			'friday',
			'saturday',
		),
		'dayNamesMin' => array(
			'sunday',
			'monday',
			'tuesday',
			'wednesday',
			'thursday',
			'friday',
			'saturday',
		),
		'dayStatus' => true,
		'dateStatus' => true,
		'dateFormat' => true,
		'initStatus' => true,
		'firstDay' => true,
		'isRTL' => true
 	);

	/**
	 * reads the translation for the label in the locallang.xml file of the extension, formates for the datePicker JavaScript
	 *
	 * @param string $label the label to translate
	 * @param string $extensionName Name of the extension
	 * @return string the translated label.
	 */
	protected function translateForJavaScript($label, $content = true) {
		if (is_array($content) && count($content)) {
			$parts = array();
			foreach ($content as $subLabel) {
				$parts[] = $this->translate($label . '.' . $subLabel);
			}
			return $parts;
		} else {
			return $this->translate($label);
		}
 	}
 	
 	/**
 	 * Returns the translated content for the given label from the calling extension.
 	 */
 	protected function translate($label) {
 		return trim(Tx_Extbase_Utility_Localization::translate('datePicker.' . $label, $this->callingExtensionName));
 	}

	/**
	 * Initialize the arguments.
	 *
	 * @return void
	 * @api
	 */
	public function initializeArguments() {
		parent::initializeArguments();
		$this->registerArgument('addJavascriptToHeader', 'boolean', 'Determines if javascript should be included in header or not', FALSE, TRUE);
	}

	/**
	 * Renders the textfield.
	 *
	 * @param boolean $required If the field is required or not
	 * @param string $type The field type, e.g. "text", "email", "url" etc.
	 * @param string $datePickerOptions Options for the JQuery DatePicker
	 * @return string
	 * @api
	 */
	public function render($required = NULL, $type = 'text', $datePickerOptions = array('changeMonth'=>true, 'changeYear'=>true, 'showOtherMonths'=> true)) {
		
		$jQueryArgument = json_encode($datePickerOptions);
	
		$request = $this->controllerContext->getRequest();
		$this->callingExtensionName = $request->getControllerExtensionName();
		
		$uniqueId = uniqid();
		$this->tag->addAttribute('id', $uniqueId);
		
		$jsKey = 'Tx_CabagExtbase_ViewHelpers_DatepickerViewHelper';

		if ($this->arguments['addJavascriptToHeader']) {
			if (!isset($GLOBALS['TSFE']->additionalHeaderData[$jsKey])) {
				$labels = array();
				foreach ($this->datepickerLabels as $label => $content) {
					$labels[$label] = $this->translateForJavaScript($label, $content);
				}
				
				$GLOBALS['TSFE']->additionalHeaderData[$jsKey] = '
	<script type="text/javascript">
		jQuery(function($) {
			$.datepicker.setDefaults(' . json_encode($labels) . ');
		});
	</script>';
			}
			
			$GLOBALS['TSFE']->additionalHeaderData[$jsKey . $uniqueId] = '
<script type="text/javascript">
	jQuery(function($) {
		$(\'#' . $uniqueId . '\').datepicker(' . $jQueryArgument . ');
	});
</script>';
		}

		return parent::render($required, $type);
	}
}
?>