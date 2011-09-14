<?php

use Zend\Config\Ini as Config, 
	Zend\Di\Configuration as DiConfig,
    Zend\Di\Definition,
    Zend\Di\Definition\Builder,
    Zend\Di\DependencyInjector,
    Zf2Mvc\Router\Http\Literal as LiteralRoute;	

use Buggy\Controller\Plugin\LayoutSwitcher;
	
class Bootstrap 
{
	/**
     * Application config
     * 
     * @var Config
     */
    protected $config = null;
    
    public function __construct($config)
    {
        if (is_string($config)) {
            $config = new Config($config);
        }
        
        $this->config = $config;
    }
    
    public function bootstrap($application)
    {
        $this->initDi($application);
        $this->initRouting($application);
    }
    
	protected function initDi($application)
    {
        $diConfig = new Config(APPLICATION_PATH . '/configs/di.ini', APPLICATION_ENV);
        $diConfig = $diConfig->di;
        
        $definitionAggregator = new Definition\AggregateDefinition();
        $definitionAggregator->addDefinition(new Definition\RuntimeDefinition());
        
        $di = new DependencyInjector();
        $di->setDefinition($definitionAggregator);
        
        $config = new DiConfig($diConfig);
        $config->configure($di);
        
        // recommended by ralphschindler, will have to see what this does
        // $di->getDefinition()->getIntrospectionRuleset()->addSetterRule('paramCanBeOptional', false)
        
        $application->setLocator($di);
    }
    
    protected function initRouting($application)
    {
        $router = $application->getRouter();
        
        $route = new LiteralRoute(
                array(
                    'route' => '/admin',
                    'defaults' => array(
                        'controller' => 'Contact\Controller\Contact'
                    )
                )
        );
        
        $router->addRoute('admin', $route);
    }
    
    
    
    
    /**
	 * Init View Options
	 * 
	 * @return void
	 */
	protected function _initViewOptions()
    {
        $this->bootstrap('view');
        $view = $this->getResource('view');
        $view->broker('doctype')->setDoctype('HTML5');
        $view->broker('headTitle')->setSeparator(' | ')
        	->prepend('Buggy');
        $view->broker('headScript')->appendFile('/js/jquery.min.js');
        $view->broker('headLink')->appendStylesheet('/css/bootstrap.min.css');
    }
    
	/**
     * Initializing action helpers
     */
    public function _initPlugins()
    {
        $this->bootstrap('FrontController');
        $fc = $this->getResource('FrontController');
        $fc->registerPlugin(new LayoutSwitcher());
    }
    
	/**
     * Initializing action helpers
     */
    public function _initActionHelpers()
    {
        $this->bootstrap('FrontController');
        $fc = $this->getResource('FrontController');
        $test = $fc->getHelperBroker()->getClassLoader()->registerPlugin('baseinit', 'Buggy\Controller\Action\Helper\BaseInit');
    }

}