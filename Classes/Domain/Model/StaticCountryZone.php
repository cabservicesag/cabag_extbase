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
class Tx_CabagExtbase_Domain_Model_StaticCountryZone extends Tx_Extbase_DomainObject_AbstractEntity {
	
	/**
	 * The relative path to the flag directory.
	 * @var string
	 */
	protected $relativeFlagPath = 'fileadmin/template_com/images/country_zones/';
	
	/**
	 * The two digit iso abbreviation.
	 * @var string
	 */
	protected $znCountryIso2 = '';

	/**
	 * Sets The two digit iso abbreviation.
	 *
	 * @param string $znCountryIso2 The two digit iso abbreviation.
	 * @return void
	 */
	public function setZnCountryIso2($znCountryIso2) {
		$this->znCountryIso2 = $znCountryIso2;
	}

	/**
	 * Returns The two digit iso abbreviation.
	 *
	 * @return string The two digit iso abbreviation.
	 */
	public function getZnCountryIso2() {
		return $this->znCountryIso2;
	}

	/**
	 * The three digit iso abbreviation.
	 * @var string
	 */
	protected $znCountryIso3 = '';

	/**
	 * Sets The three digit iso abbreviation.
	 *
	 * @param string $znCountryIso3 The three digit iso abbreviation.
	 * @return void
	 */
	public function setZnCountryIso3($znCountryIso3) {
		$this->znCountryIso3 = $znCountryIso3;
	}

	/**
	 * Returns The three digit iso abbreviation.
	 *
	 * @return string The three digit iso abbreviation.
	 */
	public function getZnCountryIso3() {
		return $this->znCountryIso3;
	}

	/**
	 * The iso number.
	 * @var int
	 */
	protected $znCountryIsoNr = 0;

	/**
	 * Sets The iso number.
	 *
	 * @param int $znCountryIsoNr The iso number.
	 * @return void
	 */
	public function setZnCountryIsoNr($znCountryIsoNr) {
		$this->znCountryIsoNr = $znCountryIsoNr;
	}

	/**
	 * Returns The iso number.
	 *
	 * @return int The iso number.
	 */
	public function getZnCountryIsoNr() {
		return $this->znCountryIsoNr;
	}

	/**
	 * The zone code.
	 * @var int
	 */
	protected $znCode = 0;

	/**
	 * Sets The parent territory iso number.
	 *
	 * @param int $znCode The parent territory iso number.
	 * @return void
	 */
	public function seZnCode($znCode) {
		$this->znCode = $znCode;
	}

	/**
	 * Returns The parent territory iso number.
	 *
	 * @return int The parent territory iso number.
	 */
	public function getZnCode() {
		return $this->znCode;
	}

	/**
	 * The local name.
	 * @var string
	 */
	protected $znNameLocal = '';

	/**
	 * Sets The local name.
	 *
	 * @param string $znNameLocal The local name.
	 * @return void
	 */
	public function setZnNameLocal($znNameLocal) {
		$this->znNameLocal = $znNameLocal;
	}

	/**
	 * Returns The local name.
	 *
	 * @return string The local name.
	 */
	public function getZnNameLocal() {
		return $this->znNameLocal;
	}

	/**
	 * The official english name.
	 * @var string
	 */
	protected $znNameEn = '';

	/**
	 * Sets The official english name.
	 *
	 * @param string $znNameEn The official english name.
	 * @return void
	 */
	public function setZnNameEn($znNameEn) {
		$this->znNameEn = $znNameEn;
	}

	/**
	 * Returns The official english name.
	 *
	 * @return string The official english name.
	 */
	public function getZnNameEn() {
		return $this->znNameEn;
	}

	/**
	 * The german short name.
	 * @var string
	 */
	protected $znNameDe = '';

	/**
	 * Sets The german short name.
	 *
	 * @param string $znNameDe The german short name.
	 * @return void
	 */
	public function setZnNameDe($znNameDe) {
		$this->znNameDe = $znNameDe;
	}

	/**
	 * Returns The german short name.
	 *
	 * @return string The german short name.
	 */
	public function getZnNameDe() {
		return $this->znNameDe;
	}

	/**
	 * The french short name.
	 * @var string
	 */
	protected $znNameFr = '';

	/**
	 * Sets The french short name.
	 *
	 * @param string $znNameFr The french short name.
	 * @return void
	 */
	public function setZnNameFr($znNameFr) {
		$this->znNameFr = $znNameFr;
	}

	/**
	 * Returns The french short name.
	 *
	 * @return string The french short name.
	 */
	public function getZnNameFr() {
		return $this->znNameFr;
	}

	/**
	 * The italian short name.
	 * @var string
	 */
	protected $znNameIt = '';

	/**
	 * Sets The italian short name.
	 *
	 * @param string $znNameIt The italian short name.
	 * @return void
	 */
	public function setZnNameIt($znNameIt) {
		$this->znNameIt = $znNameIt;
	}

	/**
	 * Returns The italian short name.
	 *
	 * @return string The italian short name.
	 */
	public function getZnNameIt() {
		return $this->znNameIt;
	}
	
	/**
	 * the name of in the current localization
	 */
	protected $currentLocalName = '';
	
	/**
	 * @param string $currentLocalName
	 * @return void
	 */
	public function setCurrentLocalName($title) {
		$this->title = $title;
	}
	
	/**
	 * @return string
	 */
	public function getCurrentLocalName() {
		switch(t3lib_div::_GP('L')) {
		case '0':
			return $this->znNameLocal;
		break;
		case '1':
			return $this->znNameFr;
			break;
		case '2':
			return $this->znNameIt;
		break;
		default:
			return $this->znNameLocal;
			break;
		}
	}
}
?>
