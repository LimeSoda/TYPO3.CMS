<?php
namespace TYPO3\CMS\Fluid\ViewHelpers;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3\CMS\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * This ViewHelper generates a HTML dump of the tagged variable.
 *
 * = Examples =
 *
 * <code title="Simple">
 * <f:debug>{testVariables.array}</f:debug>
 * </code>
 * <output>
 * foobarbazfoo
 * </output>
 *
 * <code title="All Features">
 * <f:debug title="My Title" maxDepth="5" blacklistedClassNames="{0:'Tx_BlogExample_Domain_Model_Administrator'}" plainText="TRUE" ansiColors="FALSE" inline="TRUE" blacklistedPropertyNames="{0:'posts'}">{blogs}</f:debug>
 * </code>
 * <output>
 * [A HTML view of the var_dump]
 * </output>
 */
class DebugViewHelper extends AbstractViewHelper
{
    /**
     * This prevents double escaping as the output is encoded in DebuggerUtility::var_dump
     *
     * @var bool
     */
    protected $escapeChildren = false;

    /**
     * Output of this viewhelper is already escaped
     *
     * @var bool
     */
    protected $escapeOutput = false;

    /**
     * A wrapper for \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump().
     *
     * @param string $title optional custom title for the debug output
     * @param int $maxDepth Sets the max recursion depth of the dump (defaults to 8). De- or increase the number according to your needs and memory limit.
     * @param bool $plainText If TRUE, the dump is in plain text, if FALSE the debug output is in HTML format.
     * @param bool $ansiColors If TRUE, ANSI color codes is added to the plaintext output, if FALSE (default) the plaintext debug output not colored.
     * @param bool $inline if TRUE, the dump is rendered at the position of the <f:debug> tag. If FALSE (default), the dump is displayed at the top of the page.
     * @param array $blacklistedClassNames An array of class names (RegEx) to be filtered. Default is an array of some common class names.
     * @param array $blacklistedPropertyNames An array of property names and/or array keys (RegEx) to be filtered. Default is an array of some common property names.
     * @return string
     */
    public function render($title = null, $maxDepth = 8, $plainText = false, $ansiColors = false, $inline = false, $blacklistedClassNames = null, $blacklistedPropertyNames = null)
    {
        return static::renderStatic(
            array(
                'title' => $title,
                'maxDepth' => $maxDepth,
                'plainText' => $plainText,
                'ansiColors' => $ansiColors,
                'inline' => $inline,
                'blacklistedClassNames' => $blacklistedClassNames,
                'blacklistedPropertyNames' => $blacklistedPropertyNames
            ),
            $this->buildRenderChildrenClosure(),
            $this->renderingContext
        );
    }

    /**
     * @param array $arguments
     * @param callable $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     *
     * @return string
     */
    public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext)
    {
        return DebuggerUtility::var_dump($renderChildrenClosure(), $arguments['title'], $arguments['maxDepth'], (bool)$arguments['plainText'], (bool)$arguments['ansiColors'], (bool)$arguments['inline'], $arguments['blacklistedClassNames'], $arguments['blacklistedPropertyNames']);
    }
}
