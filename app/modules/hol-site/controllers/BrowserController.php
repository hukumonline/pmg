<?php

/**
 * Description of BrowserController
 *
 * @author nihki <nihki@madaniyah.com>
 */
class HolSite_BrowserController extends Zend_Controller_Action
{
    function  preDispatch()
    {
        
    }
    function detailAction()
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
			
	    	$this->_helper->layout->setLayout('layout-detail');
	        $catalogGuid = ($this->_getParam('guid'))? $this->_getParam('guid') : '';
	        $page = ($this->_getParam('page'))? $this->_getParam('page') : '';
	        $this->view->catalogGuid = $catalogGuid;
	        $this->view->page = $page;
			
		}
       	
    }
    function detailIssueAction()
    {
    	$this->_helper->layout->setLayout('layout-detailissue');
        $catalogGuid = ($this->_getParam('guid'))? $this->_getParam('guid') : '';
        $this->view->catalogGuid = $catalogGuid;
    }
    function fotoAction()
    {
    	$this->_helper->layout->setLayout('layout-detailfoto');
        $catalogGuid = ($this->_getParam('guid'))? $this->_getParam('guid') : '';
        $this->view->catalogGuid = $catalogGuid;
    }
}
