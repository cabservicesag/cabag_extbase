<?php

/**
 * *************************************************************
 *
 * Copyright notice
 *
 * (c) 2014 Enrico Ludwig <el@cabag.ch>, cab services ag
 *
 * All rights reserved
 *
 * This script is part of the TYPO3 project. The TYPO3 project is
 * free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * This script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * This copyright notice MUST APPEAR in all copies of the script!
 * *************************************************************
 */

/**
 * This Viewhelper parses a TypoLink and generates a valid HTML a tag
 *
 * @package TYPO3
 * @subpackage Fluid
 * @version
 *
 */
class Tx_CabagExtbase_ViewHelpers_TypoLinkViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

    // Tokens
    const TOKEN_DELIMETER = ' ';
    const TOKEN_STRING = '"';
    const TOKEN_STRING_DELIMETER = ' ';
    const TOKEN_NOT_SET = '-';

    /**
     * @var TYPO3\CMS\Core\Resource\FileRepository
     * @inject
     */
    protected $fileRepository;

    /**
     * Renders the TypoLink to valid HTML link
     *
     * @param string $link Link to convert and render
     * @param boolean $useHttps Set to TRUE to apply HTTPS to the URL
     * @param string $title Title for Link
     * @param string $class Class for Link
     * @param string $replacementTag Tag to be used, when Link-URL can not be generated
     * @param string $returnPart Set to the part to return, e.g. 'url'
     * @param string $style Style for Link
     * @param boolean $setLinkAccessRestrictedPages Set to TRUE to create Links on access restricted pages
     * @param boolean $cutLinktext Set to TRUE to display url without 'http://' or 'https://'
     * @return string Valid HTML link tag
     * @author Enrico Ludwig
     */
    public function render($link, $useHttps = FALSE, $title = '', $class = '', $replacementTag = '', $returnPart = '', $style = '', $setLinkAccessRestrictedPages = FALSE, $cutLinktext = FALSE) {
	$this->fileRepository = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Core\Resource\FileRepository');

	// Prepare parts
	$parts = array(
	    'url' => '',
	    'target' => '',
	    'class' => '',
	    'title' => '',
	    'additionalParams' => '',
		'additionalAttributes' => '',
	    'style' => '',
	);
	// Parse TypoLink
	$this->parseTypoLink($link, $parts);

	// Check if linktarget is startpage
	$startpageID = $GLOBALS['TSFE']->rootLine['0']['uid'];
	if ($GLOBALS['TSFE']->rootLine['1']['is_siteroot']) {
	    $startpageID = $GLOBALS['TSFE']->rootLine['1']['uid'];
	}
	$linkToStartpage = 0;
	if ($parts['url'] == $startpageID) {
	    $linkToStartpage = 1;
	}

	// check beginning of additionalParams and correct it
	if ($parts['additionalParams']) {
	    if ($parts['additionalParams'][0] == '&') {
		$parts['additionalParams'][0] = '?';
	    } elseif ($parts['additionalParams'][0] == '?') {
		// do nothing
	    } else {
		$parts['additionalParams'] = '?' . $parts['additionalParams'];
	    }
	}

	// Apply post-check to parts
	$this->postCheck($parts, $useHttps, $setLinkAccessRestrictedPages);

	// build class-attribute from parameter and link-field
	$classes = '';
	if ($class) {
	    $classes .= $class;
	    if ($parts['class']) {
		$classes .= ' ' . $parts['class'];
	    }
	} elseif ($parts['class']) {
	    $classes .= $parts['class'];
	}
	if ($classes) {
	    $parts['class'] = 'class="' . $classes . '"';
	} else {
	    $parts['class'] = '';
	}
	
	if ($style) {
		$parts['style'] = 'style="'.$style.'"';
	} else {
		$parts['style'] = '';
	}
	
	if ($parts['additionalAttributes']) {
		$parts['additionalAttributes'] = $this->getAdditionalttributes($parts['additionalAttributes']);
	} else {
		$parts['additionalAttributes'] = '';
	}
	
	


	// build title-attribute from parameter and link-field
	$titles = '';
	if ($title) {
	    $titles .= $title;
	    if ($parts['title']) {
		$titles .= ' ' . $parts['title'];
	    }
	} elseif ($parts['title']) {
	    $titles .= $parts['title'];
	}
	if ($titles) {
	    $parts['title'] = 'title="' . $titles . '"';
	}

	if ($parts['target']) {
	    $parts['target'] = 'target="' . $parts['target'] . '"';
	}

	// if returnParts parameter is set, return parts
	if ($returnPart) {
	    if (isset($parts[$returnPart])) {
		return $parts[$returnPart];
	    }
	}

	// Build <a> tag
	$linkTag = $this->buildATag($parts, $replacementTag, $linkToStartpage, $style, $cutLinktext);

	return $linkTag;
    }

    /**
     * Parses the given TypoLink and puts the result to given array
     * The array must have these keys: url, target, css, title, additionalParams
     *
     * @param string $link The TypoLink to parse
     * @param array &$parts The array reference where the results will be put in
     */
    protected function parseTypoLink($link, &$parts) {
	// Get words splitted by delimeter
	$partsRaw = explode(self::TOKEN_DELIMETER, $link);
	$partKeys = array_keys($parts);

	// Parse raw parts
	$inString = FALSE;
	$curString = '';
	for ($partCnt = 0, $realPart = 0; $partCnt < count($partsRaw); $partCnt++) {
	    $curPart = $partsRaw[$partCnt];
	    $curPartKey = $partKeys[$realPart];

	    // Check if current part is not set
	    if ($curPart === self::TOKEN_NOT_SET) {
		// Move to next part
		$realPart++;
		continue;
	    }

	    // Start of string
	    if ($curPart[0] === self::TOKEN_STRING) {
		$curString = substr($curPart, 1) . self::TOKEN_STRING_DELIMETER;
		$inString = TRUE;
	    }
	    // Still in string
	    elseif ($inString && $curPart[strlen($curPart) - 1] !== self::TOKEN_STRING) {
		$curString .= $curPart . self::TOKEN_STRING_DELIMETER;
	    }
	    // End of string
	    elseif ($inString && $curPart[strlen($curPart) - 1] === self::TOKEN_STRING) {
		$curString .= substr($curPart, 0, strlen($curPart) - 1);

		// Apply generated part
		$parts[$curPartKey] = $curString;

		// Reset temp string
		$curString = '';
		$inString = FALSE;

		// Move to next part
		$realPart++;
	    }
	    // Not in string, simple part
	    else {
		$parts[$curPartKey] = $curPart;
		$realPart++;
	    }
	}
	
    }

    /**
     * Applies a post-check to url. So you can ensure that you get a valid URL.
     *
     * @param array &$parts Reference to TypoLink data array
     * @param boolean $useHttps Set to TRUE to ensure the URL uses HTTPS
     * @param boolean $setLinkAccessRestrictedPages Set to TRUE to create Links on access restricted pages
     */
    protected function postCheck(&$parts, $useHttps, $setLinkAccessRestrictedPages) {
	// Check URL
	$urlParts = !empty($parts) ? parse_url($parts['url']) : FALSE;

	// Abort if url is not present
	if ($urlParts === FALSE) {
	    return;
	}
	if ($parts['additionalParams']) {
	    $urlParts['additionalParams'] = $parts['additionalParams'];
	}

	// Check if type if link
	if ($urlParts['host'] && !$urlParts['port']) {
	    // External Link
	    // Check if HTTPS should be applied
	    if (!$urlParts['scheme']) {
		if ($useHttps) {
		    $urlParts['scheme'] = 'https';
		} else {
		    $urlParts['scheme'] = 'http';
		}
	    }

	    /*	     * ************** */
	    /** Rebuild URL * */
	    /*	     * ************** */

	    // Add scheme
	    $url = $urlParts['scheme'] . '://';

	    // Add user and password if present
	    if (!empty($urlParts['user'])) {
		if (!empty($urlParts['pass'])) {
		    $url .= $urlParts['user'] . ':' . $urlParts['pass'] . '@';
		} else {
		    $url .= $urlParts['user'] . '@';
		}
	    }

	    // Add host with path
	    $url .= $urlParts['host'] . $urlParts['path'] . '/';

	    // Add query if present
	    if (!empty($urlParts['query'])) {
		$url .= '?' . $urlParts['query'];
	    }

	    // Add fragment if present
	    if (!empty($urlParts['fragment'])) {
		$url .= $urlParts['fragment'];
	    }

	    // Reassign url
	    $parts['url'] = $url;
	} elseif ($urlParts['host'] && $urlParts['port']) {
	    // File Link
	    $file = $this->fileRepository->findByUid(intval($urlParts['port']))->toArray();

	    $parts['url'] = $file['url'];
	} elseif ($urlParts['scheme'] == 'tel') {
		// tel link
		$parts['url'] = $urlParts['scheme'] . ':' . $urlParts['path'];
	}else {
	    // Internal or Mail Link
	    $uriBuilder = $this->controllerContext->getUriBuilder();
	    $url = $uriBuilder->reset()
				->setLinkAccessRestrictedPages($setLinkAccessRestrictedPages)	
				->setTargetPageUid($urlParts['path'])
				->build();

	    $parts['url'] = $url;
	}
	if ($urlParts['additionalParams']) {
	    $parts['url'] .= $urlParts['additionalParams'];
	}
    }


	/**
	 * Generates a valid HTML link tag from given param array.
	 * The array must have these keys: url, target, css, title, additionalParams
	 * If param shall not be used, set to FALSE.
	 *
	 * @param array $parts Params to apply to the link
	 * @param array $replacementTag Tag to be used, when Link-URL can not be generated
	 * @param boolean $cutLinktext Set to TRUE to display url without 'http://' or 'https://'
	 * @return string An HTML <a> tag generated from given parts
	 */
	protected function buildATag($parts, $replacementTag, $linkToStartpage, $style, $cutLinktext) {
		// check if protocol is to be removed from linktext
		if ($cutLinktext) {
		    $linktext = str_replace(array('http://','https://'), '', $parts['url']);
		    // remove trailing slash
		    if (substr($linktext, -1,1) == '/') {
			$linktext = substr($linktext, 0, strlen($linktext) - 1);
		    }
		}
		else {
		    $linktext = $this->renderChildren();
		}
	    
		// define link template
		$urlTemplate = '<a href="%s" %s %s %s %s %s>%s</a>';
		// Parse template
		$parsedTemplate = sprintf($urlTemplate, $parts['url'], $parts['target'], $parts['title'], $parts['class'], $parts['style'], $parts['additionalAttributes'], $linktext);
		$replacementTagOutput = '<'.$replacementTag.' '.$parts['class'].'>' . $linktext . '</'.$replacementTag.'>';
		
		if ($parts['url'] OR $linkToStartpage) {
			return $parsedTemplate;
		} elseif ($replacementTag) {
			return $replacementTagOutput;
		} else {
			return $linktext;
		}
	}
	
	/**
	 * build tag attributes from link string
	 * 
	 * @param string $attributesString
	 * @return string
	 */
	protected function getAdditionalttributes($attributesString) {
		$attributesString = preg_replace('/^(&)(.*)/', '$2', $attributesString);
		$attributes = explode('&', $attributesString);
		$tagAttributes = '';
		
		forEach($attributes as $attribute) {
			$parts = explode('=', $attribute, 2);
			if(count($parts) == 1) {
				$tagAttributes .= htmlspecialchars($parts[0]) . ' ';
			} elseif(count($parts) == 2) {
				$tagAttributes .= htmlspecialchars($parts[0]) . '="' . htmlspecialchars($parts[1]) . '" ';
			}
		}
		
		return $tagAttributes;
	}

}

?>
