<?php

/*                                                                        *
 * This script is part of the TYPO3 project - inspiring people to share!  *
 *                                                                        *
 * TYPO3 is free software; you can redistribute it and/or modify it under *
 * the terms of the GNU General Public License version 2 as published by  *
 * the Free Software Foundation.                                          *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General      *
 * Public License for more details.                                       *
 *                                                                        */

/**
 * A view helper for creating links to TYPO3 pages.
 *
 * = Examples =
 *
 * <code title="link to the current page">
 * <c:link.lightbox>page link</c:linklightboxpage>
 * </code>
 * <output>
 * <a href="index.php?id=123">page link</a>
 * (depending on the current page and your TS configuration)
 * </output>
 *
 * <code title="query parameters">
 * <c:link.lightbox pageUid="1" additionalParams="{foo: 'bar'}">page link</c:link.lightbox>
 * </code>
 * <output>
 * <a href="index.php?id=1&foo=bar">page link</a>
 * (depending on your TS configuration)
 * </output>
 *
 * <code title="query parameters for extensions">
 * <c:link.lightbox pageUid="1" additionalParams="{extension_key: {foo: 'bar'}}">page link</c:link.lightbox>
 * </code>
 * <output>
 * <a href="index.php?id=1&extension_key[foo]=bar">page link</a>
 * (depending on your TS configuration)
 * </output>
 *
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 */
class Tx_CabagExtbase_ViewHelpers_Link_LightboxViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractTagBasedViewHelper {

	/**
	 * @var string
	 */
	protected $tagName = 'a';

	/**
	 * Arguments initialization
	 *
	 * @return void
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	public function initializeArguments() {
		$this->registerUniversalTagAttributes();
		$this->registerTagAttribute('target', 'string', 'Target of link', FALSE);
		//$this->registerTagAttribute('dataWidth', 'integer', 'Width of Lightbox', FALSE);
		//$this->registerTagAttribute('dataHeight', 'integer', 'Height of Lightbox', FALSE);
		$this->registerTagAttribute('rel', 'string', 'Specifies the relationship between the current document and the linked document', FALSE);
	}

	/**
	 * @param integer $page target page. See TypoLink destination
	 * @param array $additionalParams query parameters to be attached to the resulting URI
	 * @param integer $pageType type of the target page. See typolink.parameter
	 * @param boolean $noCache set this to disable caching for the target page. You should not need this.
	 * @param boolean $noCacheHash set this to supress the cHash query parameter created by TypoLink. You should not need this.
	 * @param string $section the anchor to be added to the URI
	 * @param boolean $linkAccessRestrictedPages If set, links pointing to access restricted pages will still link to the page even though the page cannot be accessed.
	 * @param boolean $absolute If set, the URI of the rendered link is absolute
	 * @param boolean $addQueryString If set, the current query parameters will be kept in the URI
	 * @param array $argumentsToBeExcludedFromQueryString arguments to be removed from the URI. Only active if $addQueryString = TRUE
	 * @param integer $dataHeight height of the lightbox
	 * @param integer $dataWidth width of the lightbox
	 * @return string Rendered page URI
	 * @author Bastian Waidelich <bastian@typo3.org>
	 */
	public function render($pageUid = NULL, array $additionalParams = array(), $pageType = 0, $noCache = FALSE, $noCacheHash = FALSE, $section = '', $linkAccessRestrictedPages = FALSE, $absolute = FALSE, $addQueryString = FALSE, array $argumentsToBeExcludedFromQueryString = array(), $dataWidth = NULL, $dataHeight = NULL) {
		
		
		$setup = array(
			'dataWidth' => $dataWidth,
			'dataHeight' => $dataHeight
		);
		
		$uriBuilder = $this->controllerContext->getUriBuilder();
		$uri = $uriBuilder
			->reset()
			->setTargetPageUid($pageUid)
			->setTargetPageType($pageType)
			->setNoCache($noCache)
			->setUseCacheHash(!$noCacheHash)
			->setSection($section)
			->setLinkAccessRestrictedPages($linkAccessRestrictedPages)
			->setArguments($additionalParams)
			->setCreateAbsoluteUri($absolute)
			->setAddQueryString($addQueryString)
			->setArgumentsToBeExcludedFromQueryString($argumentsToBeExcludedFromQueryString)
			->build();

		$this->tag->addAttribute('href', $uri);
		$this->tag->addAttribute('data-width', $dataWidth);
		$this->tag->addAttribute('data-height', $dataHeight);
		$this->tag->setContent($this->renderChildren());

		return $this->tag->render();
	}
}


