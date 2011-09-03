<?php
namespace Application\Model;

/**
 * @Entity(repositoryClass="Application\Model\ProjectsRepository")
 * @Table(name="projects")
 */
class Projects 
{
	/** 
	 * @Id 
	 * @Column(type="smallint")
	 * @GeneratedValue
	 */
    private $id;
    
    /** 
     * @Column(length=255) 
     */
    private $title;
    
    /** 
     * @Column(type="text") 
     */
    private $description;
    
	/**
	 * @return the $id
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @return the $title
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * @return the $description
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * @param string $id
	 */
	public function setId($id) {
		$this->id = $id;
	}

	/**
	 * @param string $title
	 */
	public function setTitle($title) {
		$this->title = $title;
	}

	/**
	 * @param string $description
	 */
	public function setDescription($description) {
		$this->description = $description;
	}
}