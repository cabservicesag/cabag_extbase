<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Nils Blattner <nb@cabag.ch>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
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
 * Mail utility class.
 *
 * @version $Id:$
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License, version 2
 */
class Tx_CabagExtbase_Utility_Mail {
	
	/**
	 * @var Tx_Fluid_View_TemplateView The fluid template.
	 */
	protected $view = null;
	
	/**
	 * @var boolean Whether or not to send mails (for debugging purposes).
	 */
	protected $sendMail = true;
	
	/**
	 * @var Tx_Extbase_Object_Manager The global object manager.
	 */
	protected $objectManager = null;
	
	/**
	 * @var string The last message.
	 */
	protected $lastMessage = '';
	
	/**
	 * @var boolean Wheter or not the swiftmailer class should be used
	 */
	protected $useSwiftmailer = false;
	
	/**
	 * @var array attachments to add - looks like array(1 => 'typo3temp/path/filename.csv')
	 */
	protected $attachments = array();
	
	/**
	 * Constructor.
	 *
	 * @param Tx_Extbase_MVC_Controller_ControllerContext $controllerContext The context to the calling controller.
	 * @param string $filePath The path to the template file.
	 * @param array $settings Typoscript setting array (optional).
	 * @return Tx_CabagExtbase_Utility_Mail The constructed mail utility class.
	 */
	public function __construct(Tx_Extbase_MVC_Controller_ControllerContext $controllerContext = null, $filePath, $settings = array()) {
	 	$this->reset($controllerContext, $filePath, $settings);
	}
	
	/**
	 * Initializes the view.
	 *
	 * @param Tx_Extbase_MVC_Controller_ControllerContext $controllerContext The context to the calling controller.
	 * @param string $filePath The path to the template file.
	 * @param array $settings Typoscript setting array (optional).
	 * @return void
	 */
	public function reset(Tx_Extbase_MVC_Controller_ControllerContext $controllerContext = null, $filePath, $settings = array()) {
		if ($this->objectManager === null) {
			// t3lib_singleton
			$this->objectManager = t3lib_div::makeInstance('Tx_Extbase_Object_Manager');
		}
		
	 	$view = $this->objectManager->getObject('Tx_Fluid_View_StandaloneView');
	 	if ($controllerContext !== null) {
	 		$view->setControllerContext($controllerContext);
		}
		
		// Template Path Override
		$view->setTemplatePathAndFilename(t3lib_div::getFileAbsFileName($filePath));
		
		if (method_exists($view, 'injectSettings')) {
			$view->injectSettings($settings);
		}
		$view->initializeView(); // In FLOW3, solved through Object Lifecycle methods, we need to call it explicitely
		$view->assign('settings', $settings); // same with settings injection.
		$this->view = $view;
		$this->lastMessage = '';
		
		$this->useSwiftmailer = false;
		$this->attachments = array();
		
		return $this;
	}
	
	/**
	 * Assign variables to the view.
	 *
	 * @param string $key The key under which the value will be accessible in the template.
	 * @param mixed $value The value.
	 * @return Tx_Fluid_View_TemplateView The template, so calls can be chained.
	 */
	public function assign($key, $value) {
		return $this->view->assign($key, $value);
	}
	
	/**
	 * Sends a plain text mail with the rendered view.
	 *
	 * @param string $email E-Mail to send to.
	 * @param string $subject E-Mail subject to user.
	 * @param string $fromEmail E-Mail to send from.
	 * @param string $fromName Name to send from (optional, $fromEmail is used if not supplied).
	 * @return boolean Whether or not the sending was successfull.
	 */
	public function sendPlainTextMail($email = '', $subject = '', $fromEmail = '', $fromName = '') {
		return $this->sendMail($email, $subject, $fromEmail, $fromName);
	}
	/**
	 * Sends a mail with the rendered view.
	 *
	 * @param mixed $email E-Mail to send to. Can be an array of 'email@example.com' => 'Some Name' tuples or a string with a comma separated list of emails. The array version only works for swiftmailer!
	 * @param string $subject E-Mail subject to user.
	 * @param string $fromEmail E-Mail to send from.
	 * @param string $fromName Name to send from (optional, $fromEmail is used if not supplied).
	 * @param boolean $htmlMail Whether or not to send the mail as a html mail.
	 * @return boolean Whether or not the sending was successfull.
	 */
	public function sendMail($email = '', $subject = '', $fromEmail = '', $fromName = '', $htmlMail = false) {
		try {
			$message = $this->view->render();
		} catch (Exception $e) {
			$this->lastMessage = 'Error: ' . $e->getMessage();
			return false;
		}
		$this->lastMessage = $message;
		
		// error handling
		if (!$this->sendMail) {
			return true;
		}
		if (empty($email) || empty($subject) || empty($fromEmail) || empty($message)) {
			return false;
		}
		if (empty($fromName)) {
			$fromName = $fromEmail;
		}
		
		if($this->useSwiftmailer === true) {
			// Swiftmailer
			$mail = t3lib_div::makeInstance('t3lib_mail_Message');
			$mail->setFrom(array($fromEmail => $fromName));
			
			// support direct array and comma separated list of recipients
			if (is_array($email)) {
				$recipients = $email;
			} else {
				$explodedString = t3lib_div::trimExplode(',',$email);
				foreach ($explodedString as $key => $email) {
					$recipients[$email] = $email;
				}
			}
			
			$mail->setTo($recipients);
			$mail->setSubject($subject);
			
			// Add attachments if available
			if(count($this->attachments) > 0) {
				foreach($this->attachments as $key => $filePath) {
					//echo $filePath.' was added:'.$mail->addAttachment($filePath);
					$mail->attach(Swift_Attachment::fromPath($filePath));
				}
			}
			
			if ($htmlMail) {
				$mail->setBody($message, 'text/html');
			} else {
				$mail->setBody($message, 'text/plain');
			}
			
			return $mail->send();
		} else {
			// Backwards compatibilty - use t3lib_htmlmail class
			$mail = t3lib_div::makeInstance('t3lib_htmlmail');
			$mail->start();
			$mail->subject = $subject;
			$mail->recipient = $email;
			$mail->from_name = $fromName;
			$mail->from_email = $fromEmail;
			$mail->returnPath = $fromEmail;
			
			// Add attachments if available
			if(count($this->attachments) > 0) {
				foreach($this->attachments as $key => $filePath) {
					$mail->addAttachment($filePath);
				}
			}
			
			if ($htmlMail) {
				$mail->extractHtmlInit($message, '');
				$mail->extractMediaLinks();
				$mail->fetchHTMLMedia();
				$mail->extractHyperLinks();
				$mail->substHREFsInHTML();
				$mail->substMediaNamesInHTML(0);
				$mail->setHTML($mail->encodeMsg($mail->theParts["html"]["content"]));
			} else {
				$mail->addPlain($message);
			}
			
			$mail->setHeaders();
			$mail->setContent();
			return $mail->sendTheMail();
		}
	}
	
	/**
	 * Allows to activate/deactivate the sending of the mail.
	 *
	 * @param boolean $sendMail If the mails should be sent.
	 * @return void
	 */
	public function setSendMail($sendMail) {
		$this->sendMail = $sendMail;
		
		return $this;
	}
	
	/**
	 * Allows to check if the mails are sent.
	 *
	 * @return boolean If the mails are sent.
	 */
	public function getSendMail() {
		return $this->sendMail;
	}
	
	/**
	 * Get the last sent message
	 *
	 * @return string The last sent message.
	 */
	public function getLastMessage() {
		return $this->lastMessage;
	}
	
	/**
	 * Adds an attachment to the mail
	 *
	 * @param	string		$file: the path and filename to add
	 * @return	void
	 */
	public function addAttachment($file) {
		$this->attachments[] = $file;
		
		return $this;
	}
	
	/**
	 * Activate swiftmailer
	 *
	 * @param boolean to activate swiftmailer set to true
	 * @return	void
	 */
	public function setUseSwiftmailer($useSwiftmailer) {
		$this->useSwiftmailer = $useSwiftmailer;
		
		return $this;
	}
	
	/**
	 * Activate swiftmailer
	 *
	 * @return	boolean wheter swiftmailer is activated or not
	 */
	public function getUseSwiftmailer() {
		return $this->useSwiftmailer;
	}
	
}
?>
