<?php

namespace Buggy\Model;

use Doctrine\ORM\EntityRepository;

class ProjectRepository extends EntityRepository
{
	/**
	 * Get Project List
	 * 
	 * @return array
	 */
	public function getProjectList() {
	    $qb = $this->_em->createQueryBuilder();
	    $qb->select('p')
	        ->from('Buggy\Model\Project', 'p')
	        ->orderBy('p.title');
	        
    	return $qb->getQuery()->getResult();
	}
	
	/**
	 * Find Projects by title
	 * 
	 * @param string $description
	 * @return object
	 */
	public function findActiveProjectsByTitle($title) {
	    $qb = $this->_em->createQueryBuilder();
	    $qb->select('p')
	        ->from('Buggy\Model\Project', 'p')
	        ->where('p.title = :title')
	        ->setParameter('title', $title)
	        ->orderBy('p.title');
	        
    	return $qb->getQuery()->getResult();
	}
}