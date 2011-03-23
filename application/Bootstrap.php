<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	protected function _initAutoload()
	{
		$autoloader = new Zend_Application_Module_Autoloader(array(
	            'namespace' => '',
	            'basePath'  => dirname(__FILE__),
		));
	
		$autoloader = Zend_Loader_Autoloader::getInstance();
		$autoloader->registerNamespace('My_');
	
		return $autoloader;
	}

	protected function _initConfig()
	{
		$config = new Zend_Config($this->getOptions());
		Zend_Registry::set('config', $config);
	}	
	
	protected function _initModules()
	{
	

		$frontController = Zend_Controller_Front::getInstance();
		$restRoute = new Zend_Rest_Route($frontController);
		$frontController->getRouter()->addRoute('default', $restRoute);
	
		$config = Zend_Registry::get('config');
		
		// REST authentication
		$frontController->registerPlugin(new My_Controller_Plugin_RestAuth($config->threescale->api_key));
	
	}	
	
}

