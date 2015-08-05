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
 * @package contentstage
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class Tx_CabagExtbase_Utility_Logging {
	/**
	 * @const int The additional information severity.
	 */
	const INFORMATION = 100;
	
	/**
	 * @const int The OK severity.
	 */
	const OK = 200;
	
	/**
	 * @const int The warning severity.
	 */
	const WARNING = 300;
	
	/**
	 * @const int The error severity.
	 */
	const ERROR = 400;
	
	/**
	 * @const int The fatal error severity.
	 */
	const FATAL = 500;
	
	/**
	 * @var array Text that represents the severity.
	 */
	protected static $severityText = array(
		100 => 'INFORMATION',
		200 => 'OK',
		300 => 'WARNING',
		400 => 'ERROR',
		500 => 'FATAL'
	);
	
	/**
	 * @var array Temporary data for the current logging run.
	 */
	protected $log = array();
	
	/**
	 * @var array Index by severity.
	 */
	protected $severityIndex = array();
	
	/**
	 * @var string Default tag.
	 */
	protected $defaultTag = '';
	
	/**
	 * @var int Automatic write severity.
	 */
	protected $writeSeverity = 400;
	
	/**
	 * @var array The output "handlers".
	 */
	protected $outputs = array();
	
	/**
	 * Constructor.
	 *
	 * @param string $defaultTag The default tag to use.
	 * @param int $writeSeverity The severity that causes direct write output.
	 */
	public function __construct($defaultTag = 'General', $writeSeverity = 400) {
		$this->defaultTag = $defaultTag;
		$this->writeSeverity = $writeSeverity;
		register_shutdown_function(array($this, '__destruct'));
	}
	
	/**
	 * Deconstructor. Makes sure the log is written!
	 */
	public function __destruct() {
		try {
			$this->write();
		} catch (Exception $e) {
			// must not throw an exception!
		}
	}
	
	/**
	 * Sets the default tag.
	 *
	 * @param string $defaultTag The default tag.
	 * @return Tx_CabagExtbase_Utility_Logging Self reference.
	 */
	public function setDefaultTag($defaultTag) {
		$this->defaultTag = $defaultTag;
		return $this;
	}
	
	/**
	 * Gets the default tag.
	 *
	 * @return string The default tag.
	 */
	public function getDefaultTag() {
		return $this->defaultTag;
	}
	
	/**
	 * Sets the write severity.
	 *
	 * @param int $writeSeverity The write severity.
	 * @return Tx_CabagExtbase_Utility_Logging Self reference.
	 */
	public function setWriteSeverity($writeSeverity) {
		$this->writeSeverity = intval($writeSeverity);
		return $this;
	}
	
	/**
	 * Gets the write severity.
	 *
	 * @return int The write severity.
	 */
	public function getWriteSeverity() {
		return $this->writeSeverity;
	}
	
	/**
	 * Find out if the log run produced the given severity. If $exact is false (default) this also gives true if a higher severity was recorded.
	 *
	 * @param int $severity The severity to look for.
	 * @param boolean $exact If set to true only matches if the exact severity was recorded.
	 * @return boolean Whether or not the given severity was recorded.
	 */
	public function hasSeverity($severity, $exact = false) {
		$severity = intval($severity);
		if (isset($this->severityIndex[$severity])) {
			return true;
		} else if ($exact) {
			return false;
		}
		
		$found = false;
		
		foreach ($this->severityIndex as $key => &$messageContainer) {
			if ($key >= $severity) {
				$found = true;
				break;
			}
		}
		
		return $found;
	}
	
	/**
	 * Finds the log messages with the given severity. If $exact is false (default) this also gives all messages with a higher severity.
	 *
	 * @param int $severity The severity to look for.
	 * @param boolean $exact If set to true only matches if the exact severity was recorded.
	 * @return array The messages with the given severity.
	 */
	public function getWithSeverity($severity, $exact = false) {
		$severity = intval($severity);
		$found = array();
		
		foreach ($this->log as $key => &$entry) {
			if (($exact && $entry['severity'] == $severity) || (!$exact && $entry['severity'] >= $severity)) {
				$found[] = $entry;
			}
		}
		
		return $found;
	}
	
	/**
	 * Checks if there have been any errors.
	 *
	 * @return boolean If any errors occured.
	 */
	public function hasErrors() {
		return $this->hasSeverity(self::ERROR);
	}
	
	/**
	 * Returns any errors.
	 *
	 * @return array Array with the error messages (if any are present).
	 */
	public function getErrors() {
		return $this->getWithSeverity(self::ERROR);
	}
	
	/**
	 * Logs a message.
	 *
	 * @param string $message The message to log.
	 * @param int $severity The severity to log, default is info.
	 * @param mixed $data The extra data to log.
	 * @param string $tag Alternative tag to use.
	 * @return Tx_CabagExtbase_Utility_Logging Self reference.
	 */
	public function log($message, $severity = self::INFORMATION, $data = null, $tag = null) {
		$now = microtime(true);
		$severity = intval($severity);
		$entry = array(
			'message' => (string)$message,
			'severity' => $severity,
			'severityText' => $this->getSeverityTypeText($severity),
			'data' => $data,
			'tag' => ($tag === null ? $this->defaultTag : (string)$tag),
			'time' => $now
		);
		
		$this->log[] = $entry;
		if ($severity > $this->writeSeverity) {
			$this->write();
		}
		return $this;
	}
	
	/**
	 * Actually write the log to the outputs.
	 *
	 * @return Tx_CabagExtbase_Utility_Logging Self reference.
	 */
	public function write() {
		if (count($this->log) === 0) {
			return;
		}
		
		foreach ($this->outputs as $output) {
			$function = 'write'. ucfirst($output['type']);
			$messages = $this->getWithSeverity($output['severity']);
			if (count($messages) > 0) {
				$this->$function($output, $messages);
			}
		}
		
		$this->log = array();
		return $this;
	}

	
	/**
	 * Add an output "handler".
	 *
	 * @param string $handle A handle to remove it again.
	 * @param mixed $type The type, currently may be file, mail or devlog.
	 * @param array $options Additional options for the given type.
	 * @return boolean true if added successfull, false otherwise (depending on options-check).
	 */
	public function addOutput($handle, $type, array $options = array()) {
		$function = 'checkOptions' . ucfirst($type);
		
		if (!isset($options['severity']) || $options['severity'] < 0) {
			return false;
		}
		
		if (!method_exists($this, $function)) {
			return false;
		}
		
		$ok = $this->$function($options);
		
		if ($ok) {
			$this->outputs[$handle] = array_merge($options, array('type' => $type));
		}
		return $ok;
	}
	
	/**
	 * Remove an output "handler".
	 *
	 * @param string $handle A handle to remove it again.
	 * @return boolean true if removed successfull, false otherwise.
	 */
	public function removeOutput($handle) {
		if (isset($this->outputs[$handle])) {
			unset($this->outputs[$handle]);
			return true;
		}
		return false;
	}
	
	/**
	 * Validates the options for a given file type output.
	 *
	 * @param array $options The options.
	 * @return boolean OK/NOK.
	 */
	protected function checkOptionsFile($options) {
		return isset($options['file']);
	}
	
	/**
	 * Validates the options for a given mail type output.
	 *
	 * @param array $options The options.
	 * @return boolean OK/NOK.
	 */
	protected function checkOptionsMail($options) {
		return !empty($options['emails']) && !empty($options['subject']) && !empty($options['from']);
	}
	
	/**
	 * Validates the options for a given devlog type output.
	 *
	 * @param array $options The options.
	 * @return boolean OK/NOK.
	 */
	protected function checkOptionsDevlog($options) {
		return true;
	}
	
	/**
	 * Validates the options for a given flashMessage type output.
	 *
	 * @param array $options The options.
	 * @return boolean OK/NOK.
	 */
	protected function checkOptionsFlash($options) {
		return $options['flashMessageContainer'] instanceof Tx_Extbase_MVC_Controller_FlashMessages;
	}
	
	/**
	 * Write the file log.
	 *
	 * @param array $options The file log options.
	 * @param array $entries The log entries.
	 * @return boolean Whether it worked.
	 */
	protected function writeFile($options, $entries) {
		$file = strftime($options['file']);
		$file = preg_match('#^/#', $file) ? $file : PATH_site . $file;
		@mkdir(dirname($file), octdec($GLOBALS['TYPO3_CONF_VARS']['BE']['folderCreateMask']), true);
		
		$f = @fopen($file, 'a');
		
		if (!$f) {
			return false;
		}
		
		$ok = true;
		
		foreach ($entries as $entry) {
			$message = $this->getStringForEntry($entry) . PHP_EOL;
			$ok = @fwrite($f, $message) && $ok;
			if (!$ok) {
				break;
			}
		}
		
		@fclose($f);
		
		return $ok;
	}
	
	/**
	 * Write the mail log.
	 *
	 * @param array $options The file log options.
	 * @param array $entries The log entries.
	 * @return boolean Whether it worked.
	 */
	protected function writeMail($options, $entries) {
		$templateFile = t3lib_div::getFileAbsFileName($options['template']);
		if (!is_file($templateFile)) {
			$templateFile = t3lib_div::getFileAbsFileName('EXT:cabag_extbase/Resources/Private/Mail/Logging/Template.' . (empty($options['plain']) ? 'html' : 'txt'));
		}
		
		$mail = t3lib_div::makeInstance(
			'Tx_CabagExtbase_Utility_Mail',
			null,
			$templateFile
		);
		$mail->setUseSwiftmailer(true);
		
		foreach ($entries as &$entry) {
			$entry['stringForEntry'] = $this->getStringForEntry($entry);
		}
		
		$mail->assign('options', $options);
		$mail->assign('entries', $entries);
		
		$mail->sendMail(
			$options['emails'],
			$options['subject'],
			$options['from'],
			empty($options['fromName']) ? $options['from'] : $options['fromName'],
			empty($options['plain'])
		);
	}
	
	/**
	 * Write the devlog. Automatically translates the HTTP return codes into devlog severity.
	 *
	 * @param array $options The file log options.
	 * @param array $entries The log entries.
	 * @return boolean Whether it worked.
	 */
	protected function writeDevlog($options, $entries) {
		foreach ($entries as $entry) {
			$severity = min(floor(max(intval($entry['severity']), 0) / 100), 4);
			$severity = $severity == 2 ? -1 : ($severity > 2 ? $severity - 1 : $severity);
			t3lib_div::devLog($entry['message'], $entry['tag'], $severity, $entry['data']);
		}
		return true;
	}
	
	/**
	 * Write the flash messages. Automatically translates the HTTP return codes into flash message severity.
	 *
	 * @param array $options The file log options.
	 * @param array $entries The log entries.
	 * @return boolean Whether it worked.
	 */
	protected function writeFlash($options, $entries) {
		$container = &$options['flashMessageContainer'];
		foreach ($entries as $entry) {
			$severity = min(floor(max(intval($entry['severity']), 0) / 100), 4) - 2;
			
			$container->add((string)$entry['message'], $entry['tag'], $severity);
		}
		return true;
	}
	
	/**
	 * Returns a string representation for a given log entry.
	 *
	 * @param array $entry The entry.
	 * @return string The text representation.
	 */
	protected function getStringForEntry($entry) {
		$timestamp = floor($entry['time']);
		$stamp = date('Ymd H:i:s', $timestamp) . '.' . sprintf('%06d', floor(($entry['time'] - $timestamp) * 1000000));
		return $stamp . ' [' . $this->getSeverityTypeText($entry['severity']) . '] (' . $entry['tag'] . ') ' . $entry['message'];
	}
	
	/**
	 * Returns an uppercase text that represents the given severity.
	 *
	 * @param int $severity The severity.
	 * @return string The type text.
	 */
	public function getSeverityTypeText($severity) {
		$severity = min(max(1, floor($severity / 100)), 5) * 100;
		return self::$severityText[$severity];
	}
}
