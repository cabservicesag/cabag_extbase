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
class Tx_CabagExtbase_Domain_Model_StaticCountry extends Tx_Extbase_DomainObject_AbstractEntity {
	
	/**
	 * The relative path to the flag directory.
	 * @var string
	 */
	protected $relativeFlagPath = 'fileadmin/template_com/images/countries/';
	
	/**
	 * The two digit iso abbreviation.
	 * @var string
	 */
	protected $cnIso2 = '';

	/**
	 * Sets The two digit iso abbreviation.
	 *
	 * @param string $iso2 The two digit iso abbreviation.
	 * @return void
	 */
	public function setIso2($iso2) {
		$this->cnIso2 = $iso2;
	}

	/**
	 * Returns The two digit iso abbreviation.
	 *
	 * @return string The two digit iso abbreviation.
	 */
	public function getIso2() {
		return $this->cnIso2;
	}

	/**
	 * The three digit iso abbreviation.
	 * @var string
	 */
	protected $cnIso3 = '';

	/**
	 * Sets The three digit iso abbreviation.
	 *
	 * @param string $iso3 The three digit iso abbreviation.
	 * @return void
	 */
	public function setIso3($iso3) {
		$this->cnIso3 = $iso3;
	}

	/**
	 * Returns The three digit iso abbreviation.
	 *
	 * @return string The three digit iso abbreviation.
	 */
	public function getIso3() {
		return $this->cnIso3;
	}

	/**
	 * The iso number.
	 * @var int
	 */
	protected $cnIsoNr = 0;

	/**
	 * Sets The iso number.
	 *
	 * @param int $isoNr The iso number.
	 * @return void
	 */
	public function setIsoNr($isoNr) {
		$this->cnIsoNr = $isoNr;
	}

	/**
	 * Returns The iso number.
	 *
	 * @return int The iso number.
	 */
	public function getIsoNr() {
		return $this->cnIsoNr;
	}

	/**
	 * The parent territory iso number.
	 * @var int
	 */
	protected $cnParentTrIsoNr = 0;

	/**
	 * Sets The parent territory iso number.
	 *
	 * @param int $parentTrIsoNr The parent territory iso number.
	 * @return void
	 */
	public function setParentTrIsoNr($parentTrIsoNr) {
		$this->cnParentTrIsoNr = $parentTrIsoNr;
	}

	/**
	 * Returns The parent territory iso number.
	 *
	 * @return int The parent territory iso number.
	 */
	public function getParentTrIsoNr() {
		return $this->cnParentTrIsoNr;
	}

	/**
	 * The local name.
	 * @var string
	 */
	protected $cnOfficialNameLocal = '';

	/**
	 * Sets The local name.
	 *
	 * @param string $officialNameLocal The local name.
	 * @return void
	 */
	public function setOfficialNameLocal($officialNameLocal) {
		$this->cnOfficialNameLocal = $officialNameLocal;
	}

	/**
	 * Returns The local name.
	 *
	 * @return string The local name.
	 */
	public function getOfficialNameLocal() {
		return $this->cnOfficialNameLocal;
	}

	/**
	 * The official english name.
	 * @var string
	 */
	protected $cnOfficialNameEn = '';

	/**
	 * Sets The official english name.
	 *
	 * @param string $officialNameEn The official english name.
	 * @return void
	 */
	public function setOfficialNameEn($officialNameEn) {
		$this->cnOfficialNameEn = $officialNameEn;
	}

	/**
	 * Returns The official english name.
	 *
	 * @return string The official english name.
	 */
	public function getOfficialNameEn() {
		return $this->cnOfficialNameEn;
	}

	/**
	 * The capital.
	 * @var string
	 */
	protected $cnCapital = '';

	/**
	 * Sets The capital.
	 *
	 * @param string $capital The capital.
	 * @return void
	 */
	public function setCapital($capital) {
		$this->cnCapital = $capital;
	}

	/**
	 * Returns The capital.
	 *
	 * @return string The capital.
	 */
	public function getCapital() {
		return $this->cnCapital;
	}

	/**
	 * The toplevel domain.
	 * @var string
	 */
	protected $cnTldomain = '';

	/**
	 * Sets The toplevel domain.
	 *
	 * @param string $tldomain The toplevel domain.
	 * @return void
	 */
	public function setTldomain($tldomain) {
		$this->cnTldomain = $tldomain;
	}

	/**
	 * Returns The toplevel domain.
	 *
	 * @return string The toplevel domain.
	 */
	public function getTldomain() {
		return $this->cnTldomain;
	}

	/**
	 * The three digit currency iso abbreviation.
	 * @var string
	 */
	protected $cnCurrencyIso3 = '';

	/**
	 * Sets The three digit currency iso abbreviation.
	 *
	 * @param string $currencyIso3 The three digit currency iso abbreviation.
	 * @return void
	 */
	public function setCurrencyIso3($currencyIso3) {
		$this->cnCurrencyIso3 = $currencyIso3;
	}

	/**
	 * Returns The three digit currency iso abbreviation.
	 *
	 * @return string The three digit currency iso abbreviation.
	 */
	public function getCurrencyIso3() {
		return $this->cnCurrencyIso3;
	}

	/**
	 * The currency iso number.
	 * @var int
	 */
	protected $cnCurrencyIsoNr = 0;

	/**
	 * Sets The currency iso number.
	 *
	 * @param int $currencyIsoNr The currency iso number.
	 * @return void
	 */
	public function setCurrencyIsoNr($currencyIsoNr) {
		$this->cnCurrencyIsoNr = $currencyIsoNr;
	}

	/**
	 * Returns The currency iso number.
	 *
	 * @return int The currency iso number.
	 */
	public function getCurrencyIsoNr() {
		return $this->cnCurrencyIsoNr;
	}

	/**
	 * The phone prefix ('00' . $cnPhone).
	 * @var int
	 */
	protected $cnPhone = 0;

	/**
	 * Sets The phone prefix ('00' . $cnPhone).
	 *
	 * @param int $phone The phone prefix ('00' . $cnPhone).
	 * @return void
	 */
	public function setPhone($phone) {
		$this->cnPhone = $phone;
	}

	/**
	 * Returns The phone prefix ('00' . $cnPhone).
	 *
	 * @return int The phone prefix ('00' . $cnPhone).
	 */
	public function getPhone() {
		return $this->cnPhone;
	}

	/**
	 * If the country is an EU member.
	 * @var boolean
	 */
	protected $cnEuMember = false;

	/**
	 * Sets If the country is an EU member.
	 *
	 * @param boolean $euMember If the country is an EU member.
	 * @return void
	 */
	public function setEuMember($euMember) {
		$this->cnEuMember = $euMember;
	}

	/**
	 * Returns If the country is an EU member.
	 *
	 * @return boolean If the country is an EU member.
	 */
	public function getEuMember() {
		return $this->cnEuMember;
	}

	/**
	 * The address format.
	 * @var int
	 */
	protected $cnAddressFormat = 0;

	/**
	 * Sets The address format.
	 *
	 * @param int $addressFormat The address format.
	 * @return void
	 */
	public function setAddressFormat($addressFormat) {
		$this->cnAddressFormat = $addressFormat;
	}

	/**
	 * Returns The address format.
	 *
	 * @return int The address format.
	 */
	public function getAddressFormat() {
		return $this->cnAddressFormat;
	}

	/**
	 * The zone flag (only set for USA).
	 * @var int
	 */
	protected $cnZoneFlag = 0;

	/**
	 * Sets The zone flag (only set for USA).
	 *
	 * @param int $zoneFlag The zone flag (only set for USA).
	 * @return void
	 */
	public function setZoneFlag($zoneFlag) {
		$this->cnZoneFlag = $zoneFlag;
	}

	/**
	 * Returns The zone flag (only set for USA).
	 *
	 * @return int The zone flag (only set for USA).
	 */
	public function getZoneFlag() {
		return $this->cnZoneFlag;
	}

	/**
	 * The local short name.
	 * @var string
	 */
	protected $cnShortLocal = '';

	/**
	 * Sets The local short name.
	 *
	 * @param string $shortLocal The local short name.
	 * @return void
	 */
	public function setShortLocal($shortLocal) {
		$this->cnShortLocal = $shortLocal;
	}

	/**
	 * Returns The local short name.
	 *
	 * @return string The local short name.
	 */
	public function getShortLocal() {
		return $this->cnShortLocal;
	}

	/**
	 * The english short name.
	 * @var string
	 */
	protected $cnShortEn = '';

	/**
	 * Sets The english short name.
	 *
	 * @param string $shortEn The english short name.
	 * @return void
	 */
	public function setShortEn($shortEn) {
		$this->cnShortEn = $shortEn;
	}

	/**
	 * Returns The english short name.
	 *
	 * @return string The english short name.
	 */
	public function getShortEn() {
		return $this->cnShortEn;
	}

	/**
	 * If the country is a UNO member.
	 * @var boolean
	 */
	protected $cnUnoMember = false;

	/**
	 * Sets If the country is a UNO member.
	 *
	 * @param boolean $unoMember If the country is a UNO member.
	 * @return void
	 */
	public function setUnoMember($unoMember) {
		$this->cnUnoMember = $unoMember;
	}

	/**
	 * Returns If the country is a UNO member.
	 *
	 * @return boolean If the country is a UNO member.
	 */
	public function getUnoMember() {
		return $this->cnUnoMember;
	}

	/**
	 * The german short name.
	 * @var string
	 */
	protected $cnShortDe = '';

	/**
	 * Sets The german short name.
	 *
	 * @param string $shortDe The german short name.
	 * @return void
	 */
	public function setShortDe($shortDe) {
		$this->cnShortDe = $shortDe;
	}

	/**
	 * Returns The german short name.
	 *
	 * @return string The german short name.
	 */
	public function getShortDe() {
		return $this->cnShortDe;
	}

	/**
	 * The french short name.
	 * @var string
	 */
	protected $cnShortFr = '';

	/**
	 * Sets The french short name.
	 *
	 * @param string $shortFr The french short name.
	 * @return void
	 */
	public function setShortFr($shortFr) {
		$this->cnShortFr = $shortFr;
	}

	/**
	 * Returns The french short name.
	 *
	 * @return string The french short name.
	 */
	public function getShortFr() {
		return $this->cnShortFr;
	}

	/**
	 * The italian short name.
	 * @var string
	 */
	protected $cnShortIt = '';

	/**
	 * Sets The italian short name.
	 *
	 * @param string $shortIt The italian short name.
	 * @return void
	 */
	public function setShortIt($shortIt) {
		$this->cnShortIt = $shortIt;
	}

	/**
	 * Returns The italian short name.
	 *
	 * @return string The italian short name.
	 */
	public function getShortIt() {
		return $this->cnShortIt;
	}
	
	/**
	 * Returns the relative path of the flag.
	 *
	 * @return string The relative path to the flag.
	 */
	public function getFlagPath() {
		$relativeFlagPath = $this->relativeFlagPath . strtolower($this->cnIso2) . '.gif';
		
		if (!file_exists(PATH_site . $relativeFlagPath)) {
			$relativeFlagPath = t3lib_extmgm::siteRelPath('cabag_extbase') . 'Resources/Public/Images/defaultStaticCountryFlag.gif';
		}
		
		return $relativeFlagPath;
	}
	
	/**
	 * Returns the relative path of the flag.
	 *
	 * @return string The relative path to the flag.
	 */
	public function getTinyFlagPath() {
		$relativeFlagPath = $this->relativeFlagPath . 'small/' . strtolower($this->cnIso2) . '.png';
		
		if (!file_exists(PATH_site . $relativeFlagPath)) {
			$relativeFlagPath = t3lib_extmgm::siteRelPath('cabag_extbase') . 'Resources/Public/Images/defaultStaticCountryFlag.gif';
		}
		
		return $relativeFlagPath;
	}
	
	/**
	 * Returns the relative path of the flag.
	 *
	 * @return string The relative path to the flag.
	 */
	public function getSmallFlagPath() {
		$relativeFlagPath = $this->relativeFlagPath . 'mediumsmall/' . strtolower($this->cnIso2) . '.png';
		
		if (!file_exists(PATH_site . $relativeFlagPath)) {
			$relativeFlagPath = t3lib_extmgm::siteRelPath('cabag_extbase') . 'Resources/Public/Images/defaultStaticCountryFlag.gif';
		}
		
		return $relativeFlagPath;
	}
	
	/**
	 * Returns the relative path of the flag.
	 *
	 * @return string The relative path to the flag.
	 */
	public function getMediumFlagPath() {
		$relativeFlagPath = $this->relativeFlagPath . 'mediumlarge/' . strtolower($this->cnIso2) . '.png';
		
		if (!file_exists(PATH_site . $relativeFlagPath)) {
			$relativeFlagPath = t3lib_extmgm::siteRelPath('cabag_extbase') . 'Resources/Public/Images/defaultStaticCountryFlag.gif';
		}
		
		return $relativeFlagPath;
	}
	
	/**
	 * Returns the relative path of the flag.
	 *
	 * @return string The relative path to the flag.
	 */
	public function getLargeFlagPath() {
		$relativeFlagPath = $this->relativeFlagPath . 'large/' . strtolower($this->cnIso2) . '.png';
		
		if (!file_exists(PATH_site . $relativeFlagPath)) {
			$relativeFlagPath = t3lib_extmgm::siteRelPath('cabag_extbase') . 'Resources/Public/Images/defaultStaticCountryFlag.gif';
		}
		
		return $relativeFlagPath;
	}
}