<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2010 Nils Blattner <nb@cabag.ch>, cab AG
*  All rights reserved
*
*  This class is a backport of the corresponding class of FLOW3.
*  All credits go to the v5 team.
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
 * static_countries Repository
 *
 * @package Extbase
 * @subpackage Persistence
 * @version $ID:$
 * @api
 */
class Tx_CabagExtbase_Domain_Repository_StaticLanguageRepository extends Tx_CabagExtbase_Persistence_Repository {
	
	/**
	 * Constructor, ensures PID = 0 is searched.
	 */
	function __construct() {
		parent::__construct();
		$querySettings = t3lib_div::makeInstance('Tx_Extbase_Persistence_Typo3QuerySettings');
		$querySettings->setRespectStoragePage(false);
		$this->setQuerySettings($querySettings);
	}
}
