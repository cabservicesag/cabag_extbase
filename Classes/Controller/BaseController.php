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
 *
 *
 * @package cabag_extbase
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class Tx_CabagExtbase_Controller_BaseController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {
	/**
	 * The last thrown and caught exception.
	 *
	 * @var Exception
	 */
	protected $lastException = null;
	
	/**
	 * Translate a given key.
	 *
	 * @param string $key The locallang key.
	 * @param array $arguments If given, it will be passed to vsprintf.
	 * @return string The locallized string.
	 */
	public function translate($key, $arguments = null) {
		return \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate($key, $this->extensionName, $arguments);
	}

	/**
	 * Ensures that the user has a login for the current page.
	 * Must be called before any actions are called.
	 *
	 * @param int $loginPage The page uid with the login plugin.
	 * @param boolean $logout If set, the user is logged out first.
	 * @return void
	 * @TODO Enable POSTvars to be sent with the return_url
	 */
	public function ensureLogin($loginPage, $logout = false) {
		if (intval($loginPage) > 0 && (empty($GLOBALS['TSFE']->fe_user->user['usergroup']) || $logout)) {
			$arguments = array('return_url' => $this->request->getRequestURI());
			if ($logout) {
				$arguments['logintype'] = 'logout';
			}
			$loginUri = $this->uriBuilder->reset()
				->setTargetPageUid($loginPage)
				->setArguments($arguments)
				->build();
			$this->redirectToURI($loginUri);
		}
	}
	
	/**
	 * Handles exceptions within an action and acts according to typoscript.
	 *
	 * @return void
	 */
	protected function callActionMethod() {
		try {
			parent::callActionMethod();
		} catch (\TYPO3\CMS\Extbase\Mvc\Exception\StopActionException $ignoredException) {
			throw $ignoredException;
		} catch (Exception $exception) {
			$this->lastException = $exception;
			
			if (!empty($this->settings['addExceptionToFlashMessage'])) {
				$this->flashMessageContainer->add($exception->getMessage(), '', \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR);
			}
			if (!empty($this->settings['addStackTraceToFlashMessage'])) {
				$this->flashMessageContainer->add($exception->getTraceAsString(), '', \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR);
			}
			if (!empty($this->settings['exceptionAction'])) {
				$errorAction = preg_replace('/action$/i', '', $this->settings['exceptionAction']) . 'Action';
				if (!method_exists($this, $errorAction)) {
					$errorAction = $this->errorMethodName;
				}
				$actionResult = call_user_func(array($this, $errorAction));
				
				if ($actionResult !== NULL && is_string($actionResult) && strlen($actionResult) > 0) {
					$this->response->appendContent($actionResult);
				}
			} else {
				throw $exception;
			}
		}
	}
}
