<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2014 Nils Blattner <nb@cabag.ch>
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

use \TYPO3\CMS\Core\Utility\GeneralUtility;
/**
 * Utility class to fix form token problems when sending backend links via mail (or other channels).
 *
 * @version $Id:$
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License, version 2
 */
class Tx_CabagExtbase_Utility_FixFormToken {
	/**
	 * Fix the given backend link to use the eID.
	 *
	 * @param string $link The link to fix.
	 * @param boolean $absolute Create an absolute link (default true).
	 * @return string The generated link.
	 */
	public function fixLink($link, $absolute = true) {
		if (version_compare(TYPO3_version, '6.2', '<')) {
			return $link;
		}
		
		$fakeDomain = false;
		if (!preg_match('#^https?://[^\./]*\.[^\./]*#', $link)) {
			$link = 'http://example.com/' . ltrim($link, '/');
			$fakeDomain = true;
		}
		
		$urlParts = parse_url($link);
		
		if (!isset($urlParts['query']) || empty($urlParts['query'])) {
			return $link;
		}
		
		if ($fakeDomain) {
			unset($urlParts['scheme']);
			unset($urlParts['host']);
		}
		
		$query = array();
		parse_str($urlParts['query'], $query);
		
		$generatedToken = \TYPO3\CMS\Core\FormProtection\FormProtectionFactory::get()->generateToken('moduleCall', $query['M']);
		if ($generatedToken !== $query['moduleToken']) {
			return $link;
		}
		
		unset($query['moduleToken']);
		
		$salt = GeneralUtility::getRandomHexString(10);
		
		$token = GeneralUtility::hmac(serialize($query), $salt);
		
		$query = array(
			'parameters' => $query
		);
		
		$query['token'] = $token;
		$query['salt'] = $salt;
		$query['url'] = $urlParts['path'];
		$query['eID'] = 'tx_cabagextbase_fixformtoken';
		
		if (isset($urlParts['fragment'])) {
			$query['fragment'] = $urlParts['fragment'];
			unset($urlParts['fragment']);
		}
		
		$urlParts['query'] = substr(GeneralUtility::implodeArrayForUrl(false, $query), 1);
		
		$urlParts['path'] = 'index.php';
		
		return $this->assembleLink($urlParts);
	}
	
	/**
	 * Takes an array from parse_url output and assembles a link.
	 *
	 * @param array $parts The parse_url parts.
	 * @return string The link.
	 */
	public function assembleLink($parts, $absolute = true) {
		$link = '';
		
		if ($absolute) {
			$link .= (isset($parts['scheme']) ? $parts['scheme'] : 'http') . '://';
			if (isset($parts['user'])) {
				$link .= $parts['user'];
				if (isset($parts['pass'])) {
					$link .= ':' . $parts['pass'];
				}
				$link .= '@';
			}
			$link .= (isset($parts['host']) ? $parts['host'] : GeneralUtility::getIndpEnv('TYPO3_HOST_ONLY'));
			if (isset($parts['port'])) {
				$link .= ':' . $parts['port'];
			} else if (!isset($parts['host']) && GeneralUtility::getIndpEnv('TYPO3_PORT') != '80') {
				$link .= ':' . GeneralUtility::getIndpEnv('TYPO3_PORT');
			}
			$link .= '/';
		}
		
		if (isset($parts['path'])) {
			$link .= ltrim($parts['path'], '/');
		}
		
		if (isset($parts['query'])) {
			$link .= '?' . ltrim($parts['query'], '?&');
		}
		
		if (isset($parts['fragment'])) {
			$link .= '#' . ltrim($parts['path'], '#');
		}
		return $link;
	}
}
