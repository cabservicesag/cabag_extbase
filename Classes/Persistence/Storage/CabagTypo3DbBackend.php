<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2011 Tizian Schmidlin <st@cabag.ch>
*  All rights reserved
*
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
 * A Storage backend with extended sorting capability and count improvement
 *
 * @package Cabag_Extbase
 * @subpackage Persistence\Storage
 * @version $Id: Typo3DbBackend.php 2297
 */
class Tx_CabagExtbase_Persistence_Storage_CabagTypo3DbBackend extends \TYPO3\CMS\Extbase\Persistence\Generic\Storage\Typo3DbBackend {

	/**
	 * Returns the number of tuples matching the query.
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface $query
	 * @return integer The number of matching tuples
	 */
	public function getObjectCountByQuery(\TYPO3\CMS\Extbase\Persistence\QueryInterface $query) {
		$constraint = $query->getConstraint();
		if($constraint instanceof \TYPO3\CMS\Extbase\Persistence\Generic\Qom\ConstraintInterface) {
			throw new \TYPO3\CMS\Extbase\Persistence\Generic\Storage\Exception\BadConstraintException('Could not execute count on queries with a constraint of type TYPO3\CMS\Extbase\Persistence\Generic\Qom\ConstraintInterface', 1256661045);
		}
		$parameters = array();
		$statementParts = $this->parseQuery($query, $parameters);
		// if limit is set, we need to count the rows "manually" as COUNT(*) ignores LIMIT constraints
		if (!empty($statementParts['limit'])) {
			$statement = $this->buildQuery($statementParts, $parameters);
			$this->replacePlaceholders($statement, $parameters);
			$result = $this->databaseHandle->sql_query($statement);
			$this->checkSqlErrors($statement);
			$count = $this->databaseHandle->sql_num_rows($result);
		} else {
			$statementParts['fields'] = array('COUNT(*)');
			// Backup the ordering statement
			$oderings = $statementParts['orderings'];
			// remove it since for count we do not need any ordering
			$statementParts['orderings'] = null;
			// make the query
			$statement = $this->buildQuery($statementParts, $parameters);
			// put back the orderings to the statemet parts
			$statementParts['orderings'] = $orderings;
			unset($orderings);
			$this->replacePlaceholders($statement, $parameters);
			$result = $this->databaseHandle->sql_query($statement);
			$this->checkSqlErrors($statement);
			$rows = $this->getRowsFromResult($query->getSource(), $result);
			$count = current(current($rows));
		}
		$this->databaseHandle->sql_free_result($result);
		return $count;
	}

	/**
	 * Transforms orderings into SQL.
	 *
	 * @param array $orderings An array of orderings (\TYPO3\CMS\Extbase\Persistence\Generic\Qom\Ordering)
	 * @param \TYPO3\CMS\Extbase\Persistence\Generic\Qom\SourceInterface $source The source
	 * @param array &$sql The query parts
	 * @return void
	 */
	protected function parseOrderings(array $orderings, \TYPO3\CMS\Extbase\Persistence\Generic\Qom\SourceInterface $source, array &$sql) {
		foreach ($orderings as $propertyName => $order) {
			switch ($order) {
				case Tx_Extbase_Persistence_QOM_QueryObjectModelConstantsInterface::JCR_ORDER_ASCENDING: // Deprecated since Extbase 1.1
				case \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING:
					$order = 'ASC';
					break;
				case Tx_Extbase_Persistence_QOM_QueryObjectModelConstantsInterface::JCR_ORDER_DESCENDING: // Deprecated since Extbase 1.1
				case \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING:
					$order = 'DESC';
					break;
				default:
					throw new \TYPO3\CMS\Extbase\Persistence\Generic\Exception\UnsupportedOrderException('Unsupported order encountered.', 1242816074);
			}
			if ($source instanceof \TYPO3\CMS\Extbase\Persistence\Generic\Qom\SelectorInterface) {
				$className = $source->getNodeTypeName();
				$tableName = $this->dataMapper->convertClassNameToTableName($className);
				while (strpos($propertyName, '.') !== FALSE) {
					$this->addUnionStatement($className, $tableName, $propertyName, $sql);
				}
			} elseif ($source instanceof \TYPO3\CMS\Extbase\Persistence\Generic\Qom\JoinInterface) {
				$tableName = $source->getLeft()->getSelectorName();
			}
			
			$aConcatFields = array();
			if(preg_match('/(concat)\(([^)]*)\)\s*as\s+([^\s]+)/i', $propertyName, $aConcatFields)) {
				$currentTable = $sql['fields'];

				// just get the first table name to simulate a proper table field
				foreach($currentTable as $table => $fields) {
					$sql['fields'][$table] = $fields . ',' . $aConcatFields[1] . '('.$aConcatFields[2].') as ' . $aConcatFields[3];
				}
				// $sql['fields'] = $aConcatFields[1] . '('.$aConcatFields[3].') as ' . $aConcatFields[4];
				$columnName = $aConcatFields[3];
				
				$tableName = '';
				
			} elseif(!stristr($propertyName,'replace') && !stristr($className, 'replace')) {
				$columnName = $this->dataMapper->convertPropertyNameToColumnName($propertyName, $className);
			}else {
				$columnName = $propertyName;
				$tableName = '';
			}
			
			if (strlen($tableName) > 0) {
				$sql['orderings'][] = $tableName . '.' . $columnName . ' ' . $order;
			} else {
				$sql['orderings'][] = $columnName . ' ' . $order;
			}
		}
	}

	/**
	 * Parse a Comparison into SQL and parameter arrays.
	 * Provides a security patch for typo3 db backend storage
	 * @see https://review.typo3.org/#/c/18721/
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\Generic\Qom\ComparisonInterface $comparison The comparison to parse
	 * @param \TYPO3\CMS\Extbase\Persistence\Generic\Qom\SourceInterface $source The source
	 * @param array &$sql SQL query parts to add to
	 * @param array &$parameters Parameters to bind to the SQL
	 * @param array $boundVariableValues The bound variables in the query and their values
	 * @return void
	 */
	protected function parseComparison(\TYPO3\CMS\Extbase\Persistence\Generic\Qom\ComparisonInterface $comparison, \TYPO3\CMS\Extbase\Persistence\Generic\Qom\SourceInterface $source, array &$sql, array &$parameters) {
		$operand1 = $comparison->getOperand1();
		$operator = $comparison->getOperator();
		$operand2 = $comparison->getOperand2();

			/**
			 * This if enables equals() to behave like in(). Use in() instead.
			 * @deprecated since Extbase 1.3; will be removed in Extbase 1.5
			 */
		if (($operator === \TYPO3\CMS\Extbase\Persistence\QueryInterface::OPERATOR_EQUAL_TO) && (is_array($operand2) || ($operand2 instanceof ArrayAccess) || ($operand2 instanceof Traversable))) {
			$operator = \TYPO3\CMS\Extbase\Persistence\QueryInterface::OPERATOR_IN;
		}

		if ($operator === \TYPO3\CMS\Extbase\Persistence\QueryInterface::OPERATOR_IN) {
			$items = array();
			$hasValue = FALSE;
			foreach ($operand2 as $value) {
				$value = $this->getPlainValue($value);
				if ($value !== NULL) {
					$items[] = $value;
					$hasValue = TRUE;
				}
			}
			if ($hasValue === FALSE) {
				$sql['where'][] = '1<>1';
			} else {
				$this->parseDynamicOperand($operand1, $operator, $source, $sql, $parameters, NULL, $operand2);
				$parameters[] = $items;
			}
		} elseif ($operator === \TYPO3\CMS\Extbase\Persistence\QueryInterface::OPERATOR_CONTAINS) {
			if ($operand2 === NULL) {
				$sql['where'][] = '1<>1';
			} else {
				$className = $source->getNodeTypeName();
				$tableName = $this->dataMapper->convertClassNameToTableName($className);
				$propertyName = $operand1->getPropertyName();
				while (strpos($propertyName, '.') !== FALSE) {
					$this->addUnionStatement($className, $tableName, $propertyName, $sql);
				}
				$columnName = $this->dataMapper->convertPropertyNameToColumnName($propertyName, $className);
				$dataMap = $this->dataMapper->getDataMap($className);
				$columnMap = $dataMap->getColumnMap($propertyName);
				$typeOfRelation = $columnMap->getTypeOfRelation();
				if ($typeOfRelation === \TYPO3\CMS\Extbase\Persistence\Generic\Mapper\ColumnMap::RELATION_HAS_AND_BELONGS_TO_MANY) {
					$relationTableName = $columnMap->getRelationTableName();
					$sql['where'][] = $tableName . '.uid IN (SELECT ' . $columnMap->getParentKeyFieldName() . ' FROM ' . $relationTableName . ' WHERE ' . $columnMap->getChildKeyFieldName() . '=?)';
					$parameters[] = intval($this->getPlainValue($operand2));
				} elseif ($typeOfRelation === \TYPO3\CMS\Extbase\Persistence\Generic\Mapper\ColumnMap::RELATION_HAS_MANY) {
					$parentKeyFieldName = $columnMap->getParentKeyFieldName();
					if (isset($parentKeyFieldName)) {
						$childTableName = $columnMap->getChildTableName();
						$sql['where'][] = $tableName . '.uid=(SELECT ' . $childTableName . '.' . $parentKeyFieldName . ' FROM ' . $childTableName . ' WHERE ' . $childTableName . '.uid=?)';
						$parameters[] = intval($this->getPlainValue($operand2));
					} else {
						$sql['where'][] = 'FIND_IN_SET(?,' . $tableName . '.' . $columnName . ')';
						$parameters[] = intval($this->getPlainValue($operand2));
					}
				} else {
					throw new \TYPO3\CMS\Extbase\Persistence\Generic\Exception\RepositoryException('Unsupported relation for contains().', 1267832524);
				}
			}
		} else {
			if ($operand2 === NULL) {
				if ($operator === \TYPO3\CMS\Extbase\Persistence\QueryInterface::OPERATOR_EQUAL_TO) {
					$operator = self::OPERATOR_EQUAL_TO_NULL;
				} elseif ($operator === \TYPO3\CMS\Extbase\Persistence\QueryInterface::OPERATOR_NOT_EQUAL_TO) {
					$operator = self::OPERATOR_NOT_EQUAL_TO_NULL;
				}
			}
			$this->parseDynamicOperand($operand1, $operator, $source, $sql, $parameters);
			$parameters[] = $this->getPlainValue($operand2);
		}
	}

	/**
	 * Transforms limit and offset into SQL
	 * Provides a security patch for typo3 db backend storage
	 * @see https://review.typo3.org/#/c/18721/
	 *
	 * @param int $limit
	 * @param int $offset
	 * @param array &$sql
	 * @return void
	 */
	protected function parseLimitAndOffset($limit, $offset, array &$sql) {
		if ($limit !== NULL && $offset !== NULL) {
			$sql['limit'] = intval($offset) . ', ' . intval($limit);
		} elseif ($limit !== NULL) {
			$sql['limit'] = intval($limit);
		}
	}
}

