<?php

namespace Buggy\Form\Decorator;

use Zend\Form\Decorator\HtmlTag;

/**
 * Ez_Form_Decorator_BootstrapTag
 *
 * Wraps content in an HTML block tag.
 *
 * Options accepted are:
 * - tag: tag to use in decorator
 * - noAttribs: do not render attributes in the opening tag
 * - placement: 'append' or 'prepend'. If 'append', renders opening and
 *   closing tag after content; if prepend, renders opening and closing tag
 *   before content.
 * - openOnly: render opening tag only
 * - closeOnly: render closing tag only
 *
 * Any other options passed are processed as HTML attributes of the tag.
 */

class BootstrapTag extends HtmlTag
{
    /**
     * Render content wrapped in an HTML tag
     *
     * @param  string $content
     * @return string
     */
    public function render($content)
    {
        $tag       = $this->getTag();
        $placement = $this->getPlacement();
        $noAttribs = $this->getOption('noAttribs');
        $openOnly  = $this->getOption('openOnly');
        $closeOnly = $this->getOption('closeOnly');
        $this->removeOption('noAttribs');
        $this->removeOption('openOnly');
        $this->removeOption('closeOnly');

        $attribs = null;
        if (!$noAttribs) {
            $attribs = $this->getOptions();
        }

        switch ($placement) {
            case self::APPEND:
                if ($closeOnly) {
                    return $content . $this->_getCloseTag($tag);
                }
                if ($openOnly) {
                    return $content . $this->_getOpenTag($tag, $attribs);
                }
                return $content
                     . $this->_getOpenTag($tag, $attribs)
                     . $this->_getCloseTag($tag);
            case self::PREPEND:
                if ($closeOnly) {
                    return $this->_getCloseTag($tag) . $content;
                }
                if ($openOnly) {
                    return $this->_getOpenTag($tag, $attribs) . $content;
                }
                return $this->_getOpenTag($tag, $attribs)
                     . $this->_getCloseTag($tag)
                     . $content;
            default:
                return (($openOnly || !$closeOnly) ? $this->_getOpenTag($tag, $attribs) : '')
                     . $content
                     . (($closeOnly || !$openOnly) ? $this->_getCloseTag($tag) : '');
        }
    }
    
}