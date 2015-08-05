<?php

if (!defined ('PATH_typo3conf')) die ('Could not access this script directly!');

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2014 Nils Blattner <nb@cabag.ch>, cab services ag
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

use \TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Redirect the user to the backend with a correct form token.
 *
 * @package cabag_extbase
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class Tx_CabagExtbase_Eid_FixFormToken {
	/**
	 * @var string The url to redirect to.
	 */
	protected $redirectTo;
	
	/**
	 * Initialize the parameters and generate the redirect url.
	 */
	public function init() {
		\TYPO3\CMS\Core\Core\Bootstrap::initializeBackendUser();
		if (!$GLOBALS['BE_USER']->user['uid'] > 0) {
			$this->redirectTo = 'typo3/';
			return $this;
		}
		
		$token = GeneralUtility::_GP('token');
		$salt = GeneralUtility::_GP('salt');
		$fragment = GeneralUtility::_GP('fragment');
		$path = GeneralUtility::_GP('url');
		$parameters = GeneralUtility::_GP('parameters');
		
		$generatedToken = GeneralUtility::hmac(serialize($parameters), $salt);
		
		if ($token === $generatedToken) {
			$parameters['moduleToken'] = \TYPO3\CMS\Core\FormProtection\FormProtectionFactory::get('TYPO3\\CMS\\Core\\FormProtection\\BackendFormProtection')
				->generateToken('moduleCall', $parameters['M']);
			
			$this->redirectTo = $path;
			$this->redirectTo .= '?' . substr(GeneralUtility::implodeArrayForUrl(false, $parameters), 1);
			if (!empty($fragment)) {
				$this->redirectTo .= '#' . $fragment;
			}
		} else {
			$this->redirectTo = 'typo3/';
		}
		
		return $this;
	}
	
	/**
	 * Send the redirect.
	 */
	public function redirect() {
		\TYPO3\CMS\Core\Utility\HttpUtility::redirect($this->redirectTo);
		return $this;
	}
}

$controller = t3lib_div::makeInstance('Tx_CabagExtbase_Eid_FixFormToken');
$controller
	->init()
	->redirect();
