<?php

namespace Buggy\ModelBase;

use Doctrine\ORM\Mapping as ORM,
	Gedmo\Timestampable,
	EdpCommon\Model\ModelAbstract;

/**
 * @ORM\MappedSuperclass
 */
class ProjectBase extends ModelAbstract
{
	/** 
	 * @ORM\Id 
	 * @ORM\Column(type="smallint")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
    private $id;
    
    /** 
     * @ORM\Column(length=255) 
     */
    private $title;
    
    /** 
     * @ORM\Column(type="text") 
     */
    private $description;
    
    /** 
     * @ORM\Column(type="integer") 
     */
    private $public;
    
    /**
     * @ORM\Column(type="integer")
     * @version
     */
    private $version;
    
    /**
     * @var datetime $created
     *
     * @gedmo:Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @var datetime $updated
     *
     * @gedmo:Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    private $updated;
    
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
	 * @return the $public
	 */
	public function getPublic($kind = 'int') {
		if ($kind == 'string') {
			if ($this->public == 1) {
				return 'yes';
			} else {
				return 'no';
			}
		}
		return $this->public;
	}
	
	/**
	 * @return the $version
	 */
	public function getVersion() {
		return $this->version;
	}

	/**
	 * @return $created
	 */
	public function getCreated()
    {
    	if (is_object($this->created) && get_class($this->created) == 'DateTime') {
    		return $this->created->format('Y-m-d H:i:s');
    	}
        return false;
    }

    /**
	 * @return $updated
	 */
    public function getUpdated()
    {
        return $this->updated;
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
	
	/**
	 * @param int $public
	 */
	public function setPublic($public) {
		$this->public = $public;
	}
	
	/**
	 * @param int $version
	 */
	public function setVersion($version) {
		$this->version = $version;
	}

}