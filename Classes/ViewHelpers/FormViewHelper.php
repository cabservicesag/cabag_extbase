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
 */

/**
 * Form view helper. Generates a <form> Tag.
 * Has some extra options to deactivate certain "features":
 * - dontRenderReferrer - If set supresses rendering of the referrer information
 * - dontRenderHmac - If set supresses rendering of the hmac information
 *
 * @see Tx_Fluid_ViewHelpers_FormViewHelper
 */
class Tx_CabagExtbase_ViewHelpers_FormViewHelper extends \TYPO3\CMS\Fluid\ViewHelpers\FormViewHelper {
	
	/**
	 * We need the arguments of the formActionUri on requesthash calculation
	 * therefore we will store them in here right after calling uriBuilder
	 *
	 * @var array
	 */
	protected $formActionUriArguments;


	/**
	 * Initialize arguments.
	 *
	 * @return void
	 */
	public function initializeArguments() {
		$this->registerArgument('dontRenderReferrer', 'boolean', 'If set supresses rendering of the referrer information');
		$this->registerArgument('dontRenderHmac', 'boolean', 'If set supresses rendering of the hmac information');
		$this->registerArgument('dontRenderTrusted', 'boolean', 'If set supresses rendering of the __trustedProperties information');
		$this->registerArgument('renderArgumentsAsHidden', 'boolean', 'If set, the GET parameters of the action will also be rendered as hidden fields');
		
		parent::initializeArguments();
	}

	/**
	 * Renders hidden form fields for referrer information about
	 * the current controller and action.
	 *
	 * @return string Hidden fields with referrer information
	 * @todo filter out referrer information that is equal to the target (e.g. same packageKey)
	 */
	protected function renderHiddenReferrerFields() {
		if ((is_array($this->arguments) || $this->arguments->hasArgument('dontRenderReferrer')) && !empty($this->arguments['dontRenderReferrer'])) {
			return '';
		}
		return parent::renderHiddenReferrerFields();
	}

	/**
	 * Render the request hash field
	 *
	 * @return string the hmac field
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	protected function renderRequestHashField() {
		if ((is_array($this->arguments) || $this->arguments->hasArgument('dontRenderHmac')) && !empty($this->arguments['dontRenderHmac'])) {
			return '';
		}
		return parent::renderRequestHashField();
	}

	/**
	 * Helper method which triggers the rendering of everything between the
	 * opening and the closing tag.
	 * Edit: Allows the rendering of the additional hidden elements from the get parameters.
	 *
	 * @return mixed The finally rendered child nodes.
	 */
	public function renderChildren() {
		$hiddenContent = '';
		
		if ((is_array($this->arguments) || $this->arguments->hasArgument('renderArgumentsAsHidden')) && !empty($this->arguments['renderArgumentsAsHidden'])) {
			$uri = '';
			if ((is_array($this->arguments) && isset($this->arguments['actionUri'])) || (is_object($this->arguments) && $this->arguments->hasArgument('actionUri'))) {
				$uri = $this->arguments['actionUri'];
			} else {
				$uri = 'a?' . http_build_query($this->controllerContext->getUriBuilder()->getLastArguments(), NULL, '&');
			}
			
			$query = parse_url($uri, PHP_URL_QUERY);
			
			$hiddenContent .= '<div style="display: none">' . LF;
			foreach (explode('&', $query) as $queryPart) {
				$parts = explode('=', $queryPart);
				$hiddenContent .= '<input type="hidden" name="' . urldecode($parts[0]) . '" value="' . (count($parts) > 1 ? urldecode($parts[1]) : '1') . '" />' . LF;
			}
			$hiddenContent .= '</div>' . LF;
		}
		return $hiddenContent . parent::renderChildren();
	}

	/**
	 * Render the request hash field
	 *
	 * @return string The hmac field
	 */
	protected function renderTrustedPropertiesField() {
		if ((is_array($this->arguments) || $this->arguments->hasArgument('dontRenderTrusted')) && !empty($this->arguments['dontRenderTrusted'])) {
			return '';
		}
		return parent::renderTrustedPropertiesField();
	}
}

