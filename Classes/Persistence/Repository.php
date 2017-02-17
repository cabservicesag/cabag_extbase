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
 * Repository with extended features:
 * - Allows setting the QuerySettings in the Repository for all the queries.
 *
 * @package Extbase
 * @subpackage Persistence
 * @version $ID:$
 * @api
 */
class Tx_CabagExtbase_Persistence_Repository extends \TYPO3\CMS\Extbase\Persistence\Repository {

	/**
	 * The query settings.
	 *
	 * @var Tx_Extbase_Persistence_QuerySettingsInterface
	 */
	protected $querySettings = null;

	/**
	 * Constructs a new Repository
	 *
	 */
	public function __construct() {
		parent::__construct();

		if (class_exists('\\TYPO3\\CMS\\Extbase\\Object\\ObjectManager')) {
			// extbase 1.3
			$objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
			$this->queryFactory = $objectManager->get('Tx_CabagExtbase_Persistence_QueryFactory');
		} else {
			$this->queryFactory = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('Tx_CabagExtbase_Persistence_QueryFactory'); // singleton
		}
	}

	/**
	 * Sets the Query Settings. These Query settings must match the settings expected by
	 * the specific Storage Backend.
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\Generic\QuerySettingsInterface $querySettings The Query Settings
	 * @return void
	 */
	public function setQuerySettings(\TYPO3\CMS\Extbase\Persistence\Generic\QuerySettingsInterface $querySettings) {
		$this->querySettings = $querySettings;
	}

	/**
	 * Returns the Query Settings.
	 *
	 * @return \TYPO3\CMS\Extbase\Persistence\Generic\QuerySettingsInterface $querySettings The Query Settings
	 */
	public function getQuerySettings() {
		return $this->querySettings;
	}

}
