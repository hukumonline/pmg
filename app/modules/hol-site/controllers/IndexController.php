<?php
class HolSite_IndexController extends Zend_Controller_Action
{
	public function init()
	{
        $this->_helper->cache(array('index'), array('entries'));
	}	
	//function index_Action()
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
        $r = $this->getRequest();
        $node = ($r->getParam('node'))? $r->getParam('node') : 'root';

        $menuFolder = App_Model_Show_Folder::show()->getMenu($node);
        $this->view->menuFolder = $menuFolder;
        
        $rowset = App_Model_Show_Catalog::show()->getCatalogByProfile("kategoriklinik");

        $content = 0;
        $data = array();

        foreach ($rowset as $row)
        {
            $data[$content][0] = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row['guid'],'fixedTitle');
            $data[$content][1] = $row['guid'];
            $content++;
        }

        $num_rows = count($rowset);

        $this->view->numberOfRows = $num_rows;
        $this->view->data = $data;
    }
    function header80Action()
    {
        
    }
    function footerAction()
    {
        
    }
    function footer80Action()
    {
        
    }
    function sidebarAction()
    {
        $this->_helper->viewRenderer->setNoRender(TRUE);
    }
}
?>