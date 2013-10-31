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
 */

/**
 * Renders a string by passing it to a TYPO3 parseFunc.
 * You can either specify a path to the TypoScript setting or set the parseFunc options directly.
 * By default lib.parseFunc_RTE is used to parse the string.
 *
 * == Examples ==
 *
 * <code title="Default parameters">
 * <f:format.html>foo <b>bar</b>. Some <LINK 1>link</LINK>.</f:format.html>
 * </code>
 * <output>
 * <p class="bodytext">foo <b>bar</b>. Some <a href="index.php?id=1" >link</a>.</p>
 * (depending on your TYPO3 setup)
 * </output>
 *
 * <code title="Custom parseFunc">
 * <f:format.html parseFuncTSPath="lib.parseFunc">foo <b>bar</b>. Some <LINK 1>link</LINK>.</f:format.html>
 * </code>
 * <output>
 * foo <b>bar</b>. Some <a href="index.php?id=1" >link</a>.
 * </output>
 *
 * <code title="Inline notation">
 * {someText -> f:format.html(parseFuncTSPath: 'lib.parseFunc')}
 * </code>
 * <output>
 * foo <b>bar</b>. Some <a href="index.php?id=1" >link</a>.
 * </output>
 *
 * @see http://typo3.org/documentation/document-library/references/doc_core_tsref/4.2.0/view/1/5/#id4198758
 *
 */
class Tx_CabagExtbase_ViewHelpers_Format_HtmlViewHelper extends Tx_Fluid_ViewHelpers_Format_HtmlViewHelper {

	/**
	 * @param string $parseFuncTSPath path to TypoScript parseFunc setup.
	 * @return the parsed string.
	 * @author Bastian Waidelich <bastian@typo3.org>
	 * @author Niels Pardon <mail@niels-pardon.de>
	 */
	public function render($parseFuncTSPath = 'lib.parseFunc_RTE') {
		if (TYPO3_MODE === 'BE') {
			$this->simulateFrontendEnvironment();
		}

		$value = $this->renderChildren();
		$value = preg_replace('#\?id=L:(\d+)/(\d+)#i', '?id=$2&L=$1',$value);
		$content = $this->contentObject->parseFunc($value, array(), '< ' . $parseFuncTSPath);

		if (TYPO3_MODE === 'BE') {
			$this->resetFrontendEnvironment();
		}
		return $content;
	}
}

?>
