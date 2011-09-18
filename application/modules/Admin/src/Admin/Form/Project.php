<?php

namespace Admin\Form;

use Buggy\Form\AbstractForm, 
	Zend\Form\Element\Hidden, 
	Zend\Form\Element\Text, 
	Zend\Form\Element\Select,
	Zend\Form\Element\Button, 
	Buggy\Form\Decorator, 
	Buggy\Model\Project as ProjectModel;

class Project extends AbstractForm
{
	public function init()
	{
		// form options
		$this->setAction('/admin/projects/add')
			->setAttrib('id', 'projectForm')
			->setMethod('post');
			
		// create elements
		$id 		 = new Hidden('id');
		$title 		 = new Text('title');
		$description = new Text('description');
		$public 	 = new Select('public');
		$submit 	 = new Button('save');
		$cancel 	 = new Button('cancel');
		
		// elements config
		$id->addValidator('digits');
		$title->setRequired(true)
			->setLabel('Title');	
		$description->setLabel('Description');
		$public->setRequired(true)
			->setLabel('Public')
			->setMultiOptions(array('0' => 'no', '1' => 'yes'))
			->addValidator('digits');
		
		$this->addElements(array($id, $title, $description, $public, $submit, $cancel));
		
		Decorator::setFormDecorator($this, Decorator::BOOTSTRAP, 'save', 'cancel');
	}
	
	/**
	 * 
	 * @param Application\Model\Project $projectRecord
	 */
	public function setValues(ProjectModel $projectRecord)
	{
		$this->getElement('id')->setValue($projectRecord->getId());
		$this->getElement('title')->setValue($projectRecord->getTitle());
		$this->getElement('description')->setValue($projectRecord->getDescription());
		$this->getElement('public')->setValue($projectRecord->getPublic());
	}
}