<?php

/***************************************************************
*  Copyright notice
*
*  (c) 2010 Nils Blattner <nb@cabag.ch>, cab AG
*  			
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
 * static_countries
 *
 * @version $Id$
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Tx_CabagExtbase_Domain_Model_StaticLanguage extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {
	
	
	/**
	 * Constructor.
	 *
	 * @return void
	 */
	public function __construct() {
		$this->setCurrentLocalName($this->getCurrentLocalName());
	}
	
	/**
	 * The relative path to the flag directory.
	 * @var string
	 */
	protected $relativeFlagPath = 'fileadmin/template_com/images/country_zones/';
	
	/**
	 * The two digit iso abbreviation.
	 * @var string
	 */
	protected $lgIso2 = '';

	/**
	 * Sets The two digit iso abbreviation.
	 *
	 * @param string $lgIso2 The two digit iso abbreviation.
	 * @return void
	 */
	public function setLgIso2($lgIso2) {
		$this->lgIso2 = $lgIso2;
	}

	/**
	 * Returns The two digit iso abbreviation.
	 *
	 * @return string The two digit iso abbreviation.
	 */
	public function getLgIso2() {
		return $this->lgIso2;
	}

	/**
	 * The three digit iso abbreviation.
	 * @var string
	 */
	protected $lgTypo3 = '';

	/**
	 * Sets The two digit typo3 abbreviation.
	 *
	 * @param string $lg_typo3 The three digit iso abbreviation.
	 * @return void
	 */
	public function setLgTypo3($lgTypo3) {
		$this->lgTypo3 = $lgTypo3;
	}

	/**
	 * Returns The three digit iso abbreviation.
	 *
	 * @return string The three digit iso abbreviation.
	 */
	public function getLgTypo3() {
		return $this->lgTypo3;
	}

	/**
	 * The iso number.
	 * @var int
	 */
	protected $lgCountryIso2 = 0;

	/**
	 * Sets The iso number.
	 *
	 * @param int $lgCountryIso2 The iso number.
	 * @return void
	 */
	public function setLgCountryIso2($lgCountryIso2) {
		$this->lgCountryIso2 = $lgCountryIso2;
	}

	/**
	 * Returns The iso number.
	 *
	 * @return int The iso number.
	 */
	public function getLgCountryIso2() {
		return $this->lgCountryIso2;
	}

	/**
	 * The zone code.
	 * @var string
	 */
	protected $lgCollateLocale = '';

	/**
	 * Sets The parent territory iso number.
	 *
	 * @param string $lgCollateLocale The local collation.
	 * @return void
	 */
	public function setLgCollateLocale($lgCollateLocale) {
		$this->lgCollateLocale = $lgCollateLocale;
	}

	/**
	 * Returns The parent territory iso number.
	 *
	 * @return int The local collation
	 */
	public function getLgCollateLocale() {
		return $this->lgCollateLocale;
	}

	/**
	 * The local name.
	 * @var string
	 */
	protected $lgNameLocal = '';

	/**
	 * Sets The local name.
	 *
	 * @param string $lgNameLocal The local name.
	 * @return void
	 */
	public function setLgNameLocal($lgNameLocal) {
		$this->lgNameLocal = $lgNameLocal;
	}

	/**
	 * Returns The local name.
	 *
	 * @return string The local name.
	 */
	public function getLgNameLocal() {
		return $this->lgNameLocal;
	}
	
	/**
	 * Is it a sacred language?
	 * @var int
	 */
	protected $lgSacred = 0;

	/**
	 * Sets The official english name.
	 *
	 * @param int $lgSacred Is it a sacred language?
	 * @return void
	 */
	public function setLgSacred($lgSacred) {
		$this->lgSacred = $lgSacred;
	}

	/**
	 * Returns The official english name.
	 *
	 * @return string The official english name.
	 */
	public function getLgSacred() {
		return $this->lgSacred;
	}

	/**
	 * The official english name.
	 * @var string
	 */
	protected $lgNameEn = '';

	/**
	 * Sets The official english name.
	 *
	 * @param string $lgNameEn The official english name.
	 * @return void
	 */
	public function setLgNameEn($lgNameEn) {
		$this->lgNameEn = $lgNameEn;
	}

	/**
	 * Returns The official english name.
	 *
	 * @return string The official english name.
	 */
	public function getLgNameEn() {
		return $this->lgNameEn;
	}

	/**
	 * The german short name.
	 * @var string
	 */
	protected $lgNameDe = '';

	/**
	 * Sets The german short name.
	 *
	 * @param string $lgNameDe The german short name.
	 * @return void
	 */
	public function setLgNameDe($lgNameDe) {
		$this->lgNameDe = $lgNameDe;
	}

	/**
	 * Returns The german short name.
	 *
	 * @return string The german short name.
	 */
	public function getLgNameDe() {
		return $this->lgNameDe;
	}

	/**
	 * The french short name.
	 * @var string
	 */
	protected $lgNameFr = '';

	/**
	 * Sets The french short name.
	 *
	 * @param string $lgNameFr The french short name.
	 * @return void
	 */
	public function setLgNameFr($lgNameFr) {
		$this->lgNameFr = $lgNameFr;
	}

	/**
	 * Returns The french short name.
	 *
	 * @return string The french short name.
	 */
	public function getLgNameFr() {
		return $this->lgNameFr;
	}

	/**
	 * The italian short name.
	 * @var string
	 */
	protected $lgNameIt = '';

	/**
	 * Sets The italian short name.
	 *
	 * @param string $lgNameIt The italian short name.
	 * @return void
	 */
	public function setLgNameIt($lgNameIt) {
		$this->lgNameIt = $lgNameIt;
	}

	/**
	 * Returns The italian short name.
	 *
	 * @return string The italian short name.
	 */
	public function getLgNameIt() {
		return $this->lgNameIt;
	}
	
	/**
	 * Returns the name in the currently active locale, defaults back to english.
	 * 
	 * @return string The language name for the currently active language.
	 */
	public function getCurrentLocalName() {
		$field = 'lgName' . ucfirst(strtolower($GLOBALS['TSFE']->sys_language_isocode));
		
		if (isset($this->$field)) {
			return $this->$field;
		}
		return $this->lgNameEn;
	}
}
