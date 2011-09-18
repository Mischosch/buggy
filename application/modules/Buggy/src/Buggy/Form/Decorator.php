<?php

namespace Buggy\Form;

use Zend\Form\Form;

/**
 * Default Decorators Set
 * 
 * General usage:
 * EasyBib_Form_Decorator::setFormDecorator($form, 'div', 'submit', 'cancel');
 * EasyBib_Form_Decorator::setFormDecorator(
 *   Instance of form, 
 *   Decorator Mode - 3 different options:
 *      - EasyBib_Form_Decorator::TABLE     (html table style)
 *      - EasyBib_Form_Decorator::DIV       (div style)
 *      - EasyBib_Form_Decorator::BOOTSTRAP (twitter bootstrap style)
 *   Name of submit button, 
 *   Name of cancel button
 * );
 */

class Decorator
{
    /**
     * Constants Definition for Decorator
     */
    const TABLE = 'table';

    const DIV = 'div';
    
    const BOOTSTRAP = 'bootstrap';

    /**
     * Element Decorator
     * 
     * @staticvar array
     */
    protected static $_ElementDecorator = array(
        'table' => array(
            'ViewHelper', 
            array(
                'Description', 
                array(
                    'tag' => '', 
                    'escape' => false, 
                    'image' => '/images/icons/information.png'
                )
            ), 
            'Errors', 
            array(
                array(
                    'data' => 'HtmlTag'
                ), 
                array(
                    'tag' => 'td'
                )
            ), 
            array(
                'Label', 
                array(
                    'tag' => 'td'
                )
            ), 
            array(
                array(
                    'row' => 'HtmlTag'
                ), 
                array(
                    'tag' => 'tr'
                )
            )
        ), 
        'div' => array(
            array(
                'ViewHelper'
            ), 
            array(
                'Errors'
            ), 
            array(
                'Description', 
                array(
                    'tag'   => 'span', 
                    'class' => 'hint'
                )
            ), 
            array(
                'Label'
            ), 
            array(
                'HtmlTag', 
                array(
                    'tag' => 'div'
                )
            )
        ), 
        'bootstrap' => array(
            array(
                'ViewHelper'
            ), 
            array(
                'BootstrapErrors'
            ), 
            array(
                'Description', 
                array(
                    'tag'   => 'span', 
                    'class' => 'help-inline'
                )
            ),  
            array(
                'BootstrapTag', 
                array(
                    'class' => 'input'
                )
            ),
            array(
                'Label'
            ), 
            array(
                'HtmlTag', 
                array(
                    'tag'   => 'div', 
                    'class' => 'clearfix'
                )
            )
        )
    );

    /**
     * Submit Element Decorator
     * 
     * @staticvar array
     */
    protected static $_SubmitDecorator = array(
        'table' => array(
            'ViewHelper'
        ), 
        'div' => array(
            'ViewHelper'
        ), 
        'bootstrap' => array(
            'ViewHelper', 
            array(
                'HtmlTag', 
                array(
                    'tag'   => 'div', 
                    'class' => 'actions',
                    'openOnly' => false
                )
            )
        )
    );
    
    /**
     * Reset Element Decorator
     * 
     * @staticvar array
     */
    protected static $_ResetDecorator = array(
        'table' => array(
            'ViewHelper'
        ), 
        'div' => array(
            'ViewHelper'
        ), 
        'bootstrap' => array(
            'ViewHelper', 
            array(
                'HtmlTag', 
                array(
                    'closeOnly' => false
                )
            )
        )
    );

    /**
     * Hiden Element Decorator
     * 
     * @staticvar array
     */
    protected static $_HiddenDecorator = array(
        'table' => array(
            'ViewHelper'
        ), 
        'div' => array(
            'ViewHelper'
        ), 
        'bootstrap' => array(
            'ViewHelper'
        )
    );

    /**
     * Form Element Decorator
     * 
     * @staticvar array
     */
    protected static $_FormDecorator = array(
        'table' => array(
            'FormElements', 
            'FormDecorator'
        ), 
        'div' => array(
            'FormElements', 
            'FormDecorator'
        ), 
        'bootstrap' => array(
            'FormElements', 
            'FormDecorator'
        )
    );

    /**
     * DisplayGroup Decorator
     * 
     * @staticvar array
     */
    protected static $_DisplayGroupDecorator = array(
        'table' => array(
            'FormElements', 
            array(
                'HtmlTag', 
                array(
                    'tag' => 'table', 
                    'summary' => ''
                )
            ), 
            'Fieldset'
        ), 
        'div' => array(
            'FormElements', 
            'Fieldset'
        ), 
        'bootstrap' => array(
            'FormElements', 
            'Fieldset'
        )
        
    );

    /**
     * Set the form decorators by the given string format or by the default div style
     * 
     * @param object $objForm        Zend_Form pointer-reference
     * @param string $constFormat    Project_Plugin_FormDecoratorDefinition constants
     * @return NULL
     */
    public static function setFormDecorator(Form $form, $format = self::BOOTSTRAP, $submit_str = 'submit', $cancel_str = 'cancel') {
        /**
         * - disable default decorators
         * - set form & displaygroup decorators
         */
        $form->setDisableLoadDefaultDecorators(true);
        $form->setDisplayGroupDecorators(self::$_DisplayGroupDecorator[$format]);
        $form->setDecorators(self::$_FormDecorator[$format]);
        
        // set needed prefix path for bootstrap decorators
        if ($format == self::BOOTSTRAP) {
            $form->addElementPrefixPath(
                'Buggy\Form\Decorator', 
                'Buggy/Form/Decorator', 
                Form::DECORATOR
            );
        }
        
        // set form element decorators
        $form->setElementDecorators(self::$_ElementDecorator[$format]);
        
        // set submit button decorators
        if ($form->getElement($submit_str)) {
            $form->getElement($submit_str)->setDecorators(self::$_SubmitDecorator[$format]);
            if ($format == self::BOOTSTRAP) {
                $attribs = $form->getElement($submit_str)->getAttrib('class');
                if (empty($attribs)) {
                    $attribs = array('btn', 'primary');
                } else {
                    if (is_string($attribs)) {
                        $attribs = array($attribs);
                    }
                    $attribs = array_unique(array_merge(array('btn'), $attribs));
                }
                $form->getElement($submit_str)
                    ->setAttrib('class', $attribs)
                    ->setAttrib('type', 'submit');
                if ($form->getElement($cancel_str)) {
                    $form->getElement($submit_str)->getDecorator('HtmlTag')
                        ->setOption('openOnly', true);
                }
            }
        }
        
        // set cancel button decorators
        if ($form->getElement($cancel_str)) {
            $form->getElement($cancel_str)->setDecorators(self::$_ResetDecorator[$format]);
            if ($format == self::BOOTSTRAP) {
                $attribs = $form->getElement($cancel_str)->getAttrib('class');
                if (empty($attribs)) {
                    $attribs = array('btn');
                } else {
                    if (is_string($attribs)) {
                        $attribs = array($attribs);
                    }
                    $attribs = array_unique(array_merge(array('btn'), $attribs));
                }
                $form->getElement($cancel_str)
                    ->setAttrib('class', $attribs)
                    ->setAttrib('type', 'submit');
                if ($form->getElement($submit_str)) {
                    $form->getElement($cancel_str)->getDecorator('HtmlTag')
                        ->setOption('closeOnly', true);
                }
            }
        }
        
        // set hidden input decorators
        foreach ($form->getElements() as $e) {
            if ($e->getType() == 'Zend_Form_Element_Hidden') {
                $e->setDecorators(self::$_HiddenDecorator[$format]);
            }
        }
    }
}  