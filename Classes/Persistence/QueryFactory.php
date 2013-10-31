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
 * The QueryFactory is used to create queries against the storage backend.
 * This class specifically allows to inject query settings into the factory.
 *
 * @package CabagExtbase
 * @subpackage Persistence
 */
class Tx_CabagExtbase_Persistence_QueryFactory extends Tx_Extbase_Persistence_QueryFactory {

	/**
	 * Creates a query object working on the given class name
	 * Finds out if it was called by a child of Tx_CabagExtbase_Persistence_Repository and fetches the query settings from there.
	 * Uses debug_backtrace() ... is there a better way to do it?
	 *
	 * @param string $className The class name
	 * @return Tx_Extbase_Persistence_QueryInterface
	 */
	public function create($className) {
		$query = parent::create($className);
		
		$trace = debug_backtrace(true);
		if (count($trace) > 1 && isset($trace[1]['object'])) {
			$caller = $trace[1]['object'];
		
			if ($caller instanceof Tx_CabagExtbase_Persistence_Repository) {
				$querySettings = $caller->getQuerySettings();
				if ($querySettings instanceof Tx_Extbase_Persistence_QuerySettingsInterface) {
					$query->setQuerySettings($querySettings);
				}
			}
		}
		return $query;
	}
}
?>
