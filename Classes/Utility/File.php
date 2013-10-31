<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2009 Nils Blattner <nb@cabag.ch>
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
 * The File utility class
 *
 * @version $Id:$
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License, version 2
 */
class Tx_CabagExtbase_Utility_File {
	
	/**
	 * @var string Absolute path to the uploads/tx_myext/
	 */
	public $uploadsDir;
	
	/**
	 * @var string Relative path to the extension upload dir.
	 */
	public $relativeUploadsDir;
	
	/**
	 * @var int Maximum file size in bytes.
	 */
	protected $maximumSize = 0;
	
	/**
	 * @var boolean Just return the file.
	 */
	protected $justFile = false;
	
	/**
	 * @var string The regex part of the filename.extension.
	 */
	protected $fileNameRegex = '.*';
	
	/**
	 * Constructor, takes one argument holding the relative path to the uploads directory
	 *
	 * @param string $relativeUploadsDir The relative uploads directory
	 * @return Tx_CabagExtbase_Utility_File The constructed object.
	 */
	public function __construct($relativeUploadsDir = '/uploads/tx_cabagextbase/') {
		// remove the starting slash if there is one
		$relativeUploadsDir = preg_replace('/^\//', '', $relativeUploadsDir);
		$this->relativeUploadsDir = '/' . $relativeUploadsDir;
		$this->uploadsDir = PATH_site . $relativeUploadsDir;
	}
	
	/**
	 * Manually sets the absolute uploads path (and additionally the relative seperatly).
	 *
	 * @param string $absolute The absolute uploads path.
	 * @param string $relative The relative uploads path.
	 * @return void
	 */
	public function injectUploadDirectories($absolute, $relative = null) {
		if (!empty($absolute)) $this->uploadsDir = $absolute;
		if ($relative != null) $this->relativeUploadsDir = $relative;
	}
	
	/**
	 * Moves the uploaded file to the upload directory and returns its new path.
	 *
	 * @param array $settings Settings array as returned by $_FILES['tx_myext_pi2']
	 * @param array $path Array of the form array('level0name', 'level1name', ...)
	 * @param string $allowed Regex pattern of allowed file extensions, default to picture extensions
	 * @return mixed Returns false if it failed, the relative path to the file otherwise.
	 */
	public function moveUploadedFile($settings, $path, $allowed = "(jpg)|(png)|(gif)") {
		$record = $this->resolveRecordOfFile($settings, $path);
		
		if ($record['size'] > $this->maximumSize) {
			return false;
		}
		
		$matches = array();
		
		$record['name'] = str_replace(' ', '_', $record['name']);
		
		$regex = '/^(' . $this->fileNameRegex . ')\.(' . $allowed . ')$/ix';
		
		// no fancy file names
		if (!preg_match($regex, $record['name'], $matches)) {
			return false;
		}
		
		$base = $matches[1];
		
		$forceUTF8 = $GLOBALS['TYPO3_CONF_VARS']['SYS']['UTF8filesystem'];
		$GLOBALS['TYPO3_CONF_VARS']['SYS']['UTF8filesystem'] = 0;
		$base = t3lib_basicFileFunctions::cleanFileName($base);
		$GLOBALS['TYPO3_CONF_VARS']['SYS']['UTF8filesystem'] = $forceUTF8;
		
		$ext = $matches[2];
		$file = $base . '.' . $ext;
		$c = 1;
		
		// finds the spot for the new file (so no old one is overwritten)
		while (file_exists($this->uploadsDir . $file)) {
			$file = $base . '_' . $c . '.' . $ext;
			$c++;
		}
		
		$ok = t3lib_div::upload_copy_move($record['tmp_name'], $this->uploadsDir . $file);
		
		if (!$ok) return false;
		
		return ($this->justFile ? '' : $this->relativeUploadsDir) . $file;
	}
	
	/**
	 * Resolves the record of a file as given by $_FILES
	 *
	 * @param array $settings Settings array as returned by $_FILES['tx_myext_pi2']
	 * @param array $path Array of the form array('level0name', 'level1name', ...)
	 * @param array $vars Array of properties to take over to the new record.
	 * @return mixed Returns false if it failed, the record otherwise.
	 */
	public function resolveRecordOfFile($settings, $path, $vars = array('name', 'tmp_name', 'size')) {
		if (empty($settings) || !is_array($settings) || empty($path) || !is_array($path)) {
			return false;
		}
		
		//$vars = array('name', 'type', 'tmp_name', 'error', 'size', );
		$record = array();
		
		// creates an entry in $record for each of the $vars with the respective path
		foreach ($vars as $key) {
			$t_setting = $settings[$key];
			foreach ($path as $p) {
				if (empty($t_setting)) return false;
				$t_setting = $t_setting[$p];
			}
			$record[$key] = $t_setting;
		}
		
		return $record;
	}
	
	/**
	 * Set the maximum accepted filesize.
	 *
	 * @param int $maximumSize The maximum filesize.
	 * @return void
	 */
	public function setMaximumSize($maximumSize) {
		$this->maximumSize = $maximumSize;
	}
	
	/**
	 * Get the maximum accepted filesize.
	 *
	 * @return int The maximum filesize.
	 */
	public function getMaximumSize() {
		return $this->maximumSize;
	}
	
	/**
	 * Set if just the file should be returned.
	 *
	 * @param int $justFile If just the file should be returned.
	 * @return void
	 */
	public function setJustFile($justFile) {
		$this->justFile = $justFile;
	}
	
	/**
	 * Get if just the file should be returned.
	 *
	 * @return int If just the file should be returned.
	 */
	public function getJustFile() {
		return $this->justFile;
	}
	
	/**
	 * Helper function to convert commaseparated file extensions to a regular expression.
	 *
	 * @param string $csv The commaseparated file extensions.
	 */
	public function csvExtensionToPreg($csv) {
		$extensions = t3lib_div::trimExplode(',', $csv, true);
		if (count($extensions) === 0) {
			return '';
		}
		
		return '(' . implode(')|(', $extensions) . ')';
	}
	
	/**
	 * Set the regex to check what filename is allowed.
	 * 
	 * @param string $fileNameRegex The regex part (do not use any round brackets).
	 * @return void
	 */
	public function setFileNameRegex($fileNameRegex) {
		$this->fileNameRegex = $fileNameRegex;
	}
	
	/**
	 * Get the regex to check what filename is allowed.
	 * 
	 * @return string The regex part (do not use any round brackets).
	 */
	public function getFileNameRegex() {
		return $this->fileNameRegex;
	}

}

?>
