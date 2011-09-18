<?php

namespace Buggy\Form\Decorator;

use Zend\Form\Decorator\HtmlTag;

/**
 * Ez_Form_Decorator_BootstrapErrors
 *
 * Wraps errors in span with class help-inline
 */

class BootstrapErrors extends HtmlTag
{
    /**
     * Render content wrapped in an HTML tag
     *
     * @param  string $content
     * @return string
     */
    public function render($content)
    {
        $element = $this->getElement();
        $view    = $element->getView();
        if (null === $view) {
            return $content;
        }

        $errors = $element->getMessages();
        if (empty($errors)) {
            return $content;
        }

        $separator = $this->getSeparator();
        $placement = $this->getPlacement();
        $formErrorHelper = $view->plugin('formErrors');
        $formErrorHelper->setElementStart('<span%s>')
            ->setElementSeparator('<br />')
            ->setElementSeparator('</span>');
        $errors = $formErrorHelper->direct($errors, array('class' => 'help-inline'));
 
        switch ($placement) {
            case 'PREPEND':
                return $errors . $separator . $content;
            case 'APPEND':
            default:
                return $content . $separator . $errors;
        }
    }
    
}