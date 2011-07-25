<?php
class HolSite_IndexController extends Zend_Controller_Action
{
	public function init()
	{
        $this->_helper->cache(array('index'), array('entries'));
	}	
	function indexAction()
	{
	}
    function attackAction()
    {
        
    }
    function miniprofileAction()
    {
        $sReturn = "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
        $sReturn = base64_encode($sReturn);

		$identity = Pandamp_Application::getResource('identity');
		$loginUrl = $identity->loginUrl;
		$logoutUrl = $identity->logoutUrl;
		$signUp = $identity->signUp;
		$profile = $identity->profile;
		
		$this->view->loginUrl = $loginUrl.'?returnTo='.$sReturn;
		$this->view->logoutUrl = $logoutUrl.'/'.$sReturn;
		$this->view->signUp = $signUp;
		$this->view->profile = $profile;
    	
		/*
        $registry = Zend_Registry::getInstance();
        $config = $registry->get(Pandamp_Keys::REGISTRY_APP_OBJECT);
        $url = $config->getOption('login');
		
        $this->view->remoteLogin = $url['remote']['url'];
        */
    }
    function miniprofile80Action()
    {
        $sReturn = "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
        $sReturn = base64_encode($sReturn);

		$identity = Pandamp_Application::getResource('identity');
		$loginUrl = $identity->loginUrl;
		$logoutUrl = $identity->logoutUrl;
		$signUp = $identity->signUp;
		$profile = $identity->profile;
		
		$this->view->loginUrl = $loginUrl.'?returnTo='.$sReturn;
		$this->view->logoutUrl = $logoutUrl.'/'.$sReturn;
		$this->view->signUp = $signUp;
		$this->view->profile = $profile;
    }
    function headerAction()
    {
        
    }
    function header80Action()
    {
        
    }
    function footerAction()
    {
        
    }
    function sidebarAction()
    {
        $this->_helper->viewRenderer->setNoRender(TRUE);
    }
}
?>