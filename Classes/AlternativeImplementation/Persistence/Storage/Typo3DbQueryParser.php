<?php

/***************************************************************
*  Copyright notice
*
*  (c) 2016 Tizian Schmidlin <st@cabag.ch>, cab services ag
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

namespace Cabag\CabagExtbase\AlternativeImplementation\Persistence\Storage;

class Typo3DbQueryParser extends \TYPO3\CMS\Extbase\Persistence\Generic\Storage\Typo3DbQueryParser {
	/**
	* Constants representing the direction when ordering result sets.
	*/
	const ORDER_FIELD_ASCENDING = 'ASC_FIELD';
	const ORDER_FIELD_DESCENDING = 'DESC_FIELD';
	const ORDER_DONTPARSE = '';

	/**
	* Transforms orderings into SQL.
	*
	* @param array $orderings An array of orderings (TYPO3\CMS\Extbase\Persistence\Generic\Qom\Ordering)
	* @param TYPO3\CMS\Extbase\Persistence\Generic\Qom\SourceInterface $source The source
	* @param array &$sql The query parts
	* @throws \TYPO3\CMS\Extbase\Persistence\Generic\Exception\UnsupportedOrderException
	* @return void
	*/
	protected function parseOrderings(array $orderings, \TYPO3\CMS\Extbase\Persistence\Generic\Qom\SourceInterface $source, array &$sql) {
		foreach ($orderings as $propertyName => $order) {
			switch ($order) {

			case \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING:
				$order = 'ASC';
				$field = false;
				break;
				
			case \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING:
				$order = 'DESC';
				$field = false;
				break;

			case Typo3DbQueryParser::ORDER_FIELD_ASCENDING:
				$order = 'ASC';
				$field = true;
				break;

			case Typo3DbQueryParser::ORDER_FIELD_DESCENDING:
				$order = 'DESC';
				$field = true;
				break;

			case Typo3DbQueryParser::ORDER_DONTPARSE:
				$order = '';
				$dontParse = true;
				break;

			default:
				throw new \TYPO3\CMS\Extbase\Persistence\Generic\Exception\UnsupportedOrderException('Unsupported order encountered.', 1242816074);
			}
			$className = '';
			$tableName = '';
			if ($source instanceof TYPO3\CMS\Extbase\Persistence\Generic\Qom\SelectorInterface) {
				$className = $source->getNodeTypeName();
				$tableName = $this->dataMapper->convertClassNameToTableName($className);
				while (strpos($propertyName, '.') !== FALSE) {
					$this->addUnionStatement($className, $tableName, $propertyName, $sql);
				}
			} elseif ($source instanceof TYPO3\CMS\Extbase\Persistence\Generic\Qom\JoinInterface) {
				$tableName = $source->getLeft()->getSelectorName();
			}
			$aConcatFields = array();
			// cab st: feature from earlier cabag_extbase
			if(preg_match('/(concat)\(([^)]*)\)\s*as\s+([^\s]+)/i', $propertyName, $aConcatFields)) {
				$currentTable = $sql['fields'];

				// just get the first table name to simulate a proper table field
				foreach($currentTable as $table => $fields) {
					$sql['fields'][$table] = $fields . ',' . $aConcatFields[1] . '('.$aConcatFields[2].') as ' . $aConcatFields[3];
				}
				// $sql['fields'] = $aConcatFields[1] . '('.$aConcatFields[3].') as ' . $aConcatFields[4];
				$columnName = $aConcatFields[3];

				$tableName = '';

			} elseif(!$dontParse && !stristr($propertyName,'replace') && !stristr($className, 'replace')) {
				$columnName = $this->dataMapper->convertPropertyNameToColumnName($propertyName, $className);
			}else {
				$columnName = $propertyName;
				$tableName = '';
			}
			// cab st: feature end

			if($field) {
				if ($tableName !== '') {
					$sql['orderings'][] = 'FIELD(' . $tableName . '.' . $propertyName . ') ' . $order;
				} else {
					$sql['orderings'][] = 'FIELD(' . $propertyName . ') ' . $order;
				}
			}
			elseif($dontParse) {
				$sql['orderings'][] = $propertyName;
			} else {
				if ($tableName !== '') {
					$sql['orderings'][] = $tableName . '.' . $columnName . ' ' . $order;
				} else {
					$sql['orderings'][] = $columnName . ' ' . $order;
				}
			}
		}
	}
}
