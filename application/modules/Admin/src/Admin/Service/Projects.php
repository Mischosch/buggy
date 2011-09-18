<?php

namespace Admin\Service;

use Buggy\Form\AbstractForm, 
	Admin\Form\Project as ProjectForm;

class Projects
{

	/**
	 * @var Admin\Form\Project
	 */
	protected $projectForm;
	
	/**
	 * @return Buggy\Form\AbstractForm $projectForm
	 */
	public function getProjectForm() {
		if (!$this->projectForm instanceof AbstractForm) {
            $this->projectForm = new ProjectForm();
        }
		return $this->projectForm;
	}

	/**
	 * @param  ProjectForm $projectForm
	 * @return Admin\Service\Projects
	 */
	public function setProjectForm(AbstractForm $projectForm) {
		$this->projectForm = $projectForm;
	}

	
	

}