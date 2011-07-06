<?php

class IndexController extends Zend_Controller_Action  
{
	function init()
	{
		$this->_helper->viewRenderer->setNoRender(TRUE);         
	}
	public function __call($method, $args)
	{
		$tw = Zend_Registry::get('twurfl');
		$tw->GetDeviceCapabilitiesFromAgent($_SERVER['HTTP_USER_AGENT'],true);
		$cap = $tw->capabilities;
		
		// check if this device is mobile
		if($cap['product_info']['is_wireless_device']){
			
		    $registry = Zend_Registry::getInstance();
		    $config = $registry->get(Pandamp_Keys::REGISTRY_APP_OBJECT);
		    $cdn = $config->getOption('mobile');
		    
		    $uri = $_SERVER['REQUEST_URI'];
		    
			header("Location:".$cdn['url'].$uri);
			
		}else{
			
			$this->_forward('index','index','hol-site');			
			
		}
       	
       	
	}
}

