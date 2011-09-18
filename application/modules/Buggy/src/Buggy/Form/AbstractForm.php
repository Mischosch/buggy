<?php

namespace Buggy\Form;

use Zend\Form\Form;

class AbstractForm extends Form
{
	/**
     * Build Bootstrap Error Decorators
     */
    public function buildBootstrapErrorDecorators() {
        foreach ($this->getErrors() AS $key=>$errors) {
            $htmlTagDecorator = $this->getElement($key)->getDecorator('HtmlTag');
            if (empty($htmlTagDecorator)) {
                continue;
            }
            if (empty($errors)) {
                continue;
            }
            $class = $htmlTagDecorator->getOption('class');
            $htmlTagDecorator->setOption('class', $class . ' error');
        }
    }
}