<?php

if (!defined ('PATH_typo3conf')) die ('Could not access this script directly!');

class Tx_CabagExtbase_eID_Flashupload {
	var $uploadsDir = '';

	/**
	 * Moves the given file to the target directory.
	 *
	 * @return void
	 */
	public function move() {
		tslib_eidtools::connectDB();
		$this->checkIfDisabled();
		if (!isset($_FILES['Filedata']) || !isset($_FILES['Filedata']['name']) || !isset($_FILES['Filedata']['tmp_name']) || intval($_FILES['Filedata']['error']) !== 0) {
			$this->error(1); // You should not see this!
		}
		
		$conf = $this->getVerifiedParameters();
		
		if (empty($conf)) {
			$this->error(2); // Empty or invalid parameters!
		}

		if (intval($_FILES['Filedata']['size']) > intval($conf['max_size'])) {
			$this->error(3); // File too big!
		}
		
		$fileExtension = pathinfo($_FILES['Filedata']['name'], PATHINFO_EXTENSION);
		
		if (!$this->containsExtension($conf['file_types'], $fileExtension)) {
			$this->error(4); // File extension not allowed!
		}

		$this->uploadsDir = PATH_site . $_POST['uploadsDir'];
		$okAndFilename = $this->moveUploadedFile($_FILES['Filedata']['tmp_name'], $_FILES['Filedata']['name']);
		
		if (!$okAndFilename) {
			$this->error(5); // Cannot move the file!
		}

		$this->injectFileDataIntoFESession($_POST['name'], $okAndFilename);
	}

	/**
	 * Does the actual moving of the file.
	 *
	 * @param string $sourceFile The (absolute) path to the source file.
	 * @param string $destinationFilename The file name to use.
	 * @return mixed FALSE if something failed, otherwise the new file name.
	 */
	public function moveUploadedFile($sourceFile, $destinationFilename) {
		$forceUTF8 = $GLOBALS['TYPO3_CONF_VARS']['SYS']['UTF8filesystem'];
		$GLOBALS['TYPO3_CONF_VARS']['SYS']['UTF8filesystem'] = 0;
		$destinationFilename = t3lib_basicFileFunctions::cleanFileName($destinationFilename);
		$GLOBALS['TYPO3_CONF_VARS']['SYS']['UTF8filesystem'] = $forceUTF8;
		
		$file = $this->uploadsDir . $destinationFilename;
		$fileData = pathinfo($file);

		// finds the spot for the new file (so no old one is overwritten)
		$c = 1;
		while (file_exists($file)) {
			$file = $fileData['dirname'] . '/' . $fileData['filename'] . '_' . $c . '.' . $fileData['extension'];
			$c++;
		}
		
		$ok = t3lib_div::upload_copy_move($sourceFile, $file);
		if (!$ok) {
			return FALSE;
		} else {
			return pathinfo($file, PATHINFO_BASENAME);
		}
	}

	/**
	 * Returns the parameters only if they were verified against the verification key.
	 *
	 * @return array The verified parameters.
	 */
	public function getVerifiedParameters() {
		if (empty($_POST['name']) || empty($_POST['max_size']) || empty($_POST['file_types']) || empty($_POST['uploadsDir'])) {
			return array();
		}

		$verification = md5(
			$_POST['name'] . $_POST['uploadsDir'] . $GLOBALS['TYPO3_CONF_VARS']['SYS']['encryptionKey']
		);

		if ($verification === $_POST['verification']) {
			return $_POST;
		}
		
		return array();
	}
	
	/**
	 * Checks if $given file extension is contained in the list of $allowed.
	 *
	 * @param string $allowed Comma separated list of file extensions ('jpg,gif,png') or '*'.
	 * @param string $given The file extension to check for.
	 * @return boolean Whether or not the file extension is allowed.
	 */
	public function containsExtension($allowed, $given) {
		if ($given === '') {
			return false;
		} else if ($allowed === '*' || in_array(strtolower($given), explode(',', preg_replace('/ +/', '', strtolower($allowed))))) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Injects filedata into fe user session
	 *
	 * @param string $name
	 * @param string $filename
	 */
	public function injectFileDataIntoFESession($name, $filename) {
		$feUserSessionID = $this->getFeUserSessionID();

		$statement = $GLOBALS['TYPO3_DB']->prepare_SELECTquery(
			'*',
			'fe_session_data',
			'hash = :hash'
		);

		try {
			$statement->execute(array(':hash' => $feUserSessionID));
			if (($sesDataRow = $statement->fetch()) !== FALSE) {
				$sesData = unserialize($sesDataRow['content']);
				$sesData['uploadedFiles'][$name] = $filename;
	
				$GLOBALS['TYPO3_DB']->exec_DELETEquery(
					'fe_session_data',
					'hash=' . $GLOBALS['TYPO3_DB']->fullQuoteStr($feUserSessionID, 'fe_session_data')
				);
	
				$insertFields = array (
					'hash' => $feUserSessionID,
					'content' => serialize($sesData),
					'tstamp' => $GLOBALS['EXEC_TIME'],
				);
				$GLOBALS['TYPO3_DB']->exec_INSERTquery('fe_session_data', $insertFields);
			} else {
				throw new Exception('no sessiondata found');
			}
		} catch (Exception $e) {
			$GLOBALS['TYPO3_DB']->sql_query('COMMIT');
			$this->error($e->getMessage());
		}

		$statement->free();
		exit();
	}

	/**
	 * Returns the session id of the current fe_user.
	 *
	 * @return string The session id.
	 */
	public function getFeUserSessionID() {
			// Check if a session is transferred:
		if ($_POST['lookupid'])	{
			$fe_sParts = explode('-', $_POST['lookupid']);
			if (!strcmp(md5($fe_sParts[0] . '/' . $GLOBALS['TYPO3_CONF_VARS']['SYS']['encryptionKey']), $fe_sParts[1]))	{	// If the session key hash check is OK:
				return $fe_sParts[0];
			}
		}

		$this->error(6); //no feuser session id given
	}
	
	/**
	 * Checks if the flash upload is disabled in the extension configuration and dies.
	 *
	 * @return void
	 */
	public function checkIfDisabled() {
		$extConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['cabag_extbase']);
		if (!empty($extConf['disableFlashUpload'])) {
			$this->error('Extension configuration disableFlashUpload is set!');
			die('Flash upload is disabled');
		}
	}

	/**
	 * Send an error to the client.
	 *
	 * @param int $id The error id.
	 */
	protected function error($id) {
		t3lib_div::devLog(
			'Flashupload error',
			'cabag_extbase',
			3,
			array(
				'time' => date('Y-m-d H:i:s'),
				'error' => $id,
				'$_GET' => $_GET,
				'$_POST' => $_POST,
				'$_FILES' => $_FILES,
				'verification' => md5(
					$_POST['name'] . $_POST['uploadsDir'] . $GLOBALS['TYPO3_CONF_VARS']['SYS']['encryptionKey']
				),
				'pathinfo' => pathinfo($_FILES['Filedata']['name'], PATHINFO_EXTENSION),
				'exts' => explode(',', preg_replace('/ +/', '', strtolower($_POST['file_types']))),
				'PATH_site' => PATH_site,
			)
		);
		header('HTTP/1.0 400 Bad Request');
		die($id);
	}
}

$uploader = new Tx_CabagExtbase_eID_Flashupload();
$uploader->move();

?>
