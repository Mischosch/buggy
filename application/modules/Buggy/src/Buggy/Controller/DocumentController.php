<?php

/** @namespace */
namespace Buggy;

use Zend\Controller\Action as ActionController,
	Application\Document,
    Zend\Registry,
    Zend\Debug;

class DocumentController extends ActionController
{

	/**
	 * @var Doctrine\ODM\CouchDB\DocumentManager
	 */
	protected $dm;
	
    public function init()
    {
    	$this->dm = Registry::get('dm');
    }

    public function indexAction()
    {
        $this->view->articles = $this->dm->getRepository('Application\Document\Article')->findAll();
       
    }

    public function addAction(){
    	
        $article = new Document\Article();
        $article->setTitle("Neuer TestArtikel");
        $article->setBody("Find out! whoooooooooo");
        $article->addTag("Test");
                
        $this->dm->persist($article);
        $this->dm->flush();
        $this->dm->clear();
    	
    
    }

}

