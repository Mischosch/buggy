<?php

namespace Application\Model;

use Doctrine\ORM\EntityRepository;

class UsersRepository extends EntityRepository
{
	/**
	 * Find Projects by title
	 * 
	 * @param string $description
	 * @return object
	 */
	public function findActiveProjectsByTitle($title) {
	    $qb = $this->_em->createQueryBuilder();
	    $qb->select('p')
	        ->from('Application\Model\Projects', 'p')
	        ->where('p.title = :title')
	        ->setParameter('title', $title)
	        ->orderBy('p.title');
	        
    	return $qb->getQuery()->getResult();
	}
}