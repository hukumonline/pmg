<?php

/**
 * Description of BrowserController
 *
 * @author nihki <nihki@madaniyah.com>
 */
class Hold_BrowserController extends Zend_Controller_Action
{
    function preDispatch()
    {
        $this->_helper->layout->setLayout('layout-pusatdata');
    }
    function viewAction()
    {
        $node = ($this->_getParam('node'))? $this->_getParam('node') : '';
        $npts = ($this->_getParam('npts'))? $this->_getParam('npts') : '';
        $nprt = ($this->_getParam('nprt'))? $this->_getParam('nprt') : '';
        $this->view->npts = $npts;
        $this->view->nprt = $nprt;
        $this->view->node = $node;
    }
    function browseAction() {}
    function detailAction()
    {
        $catalogGuid = ($this->_getParam('guid'))? $this->_getParam('guid') : '';
        $node = ($this->_getParam('node'))? $this->_getParam('node') : '';
        $npts = ($this->_getParam('npts'))? $this->_getParam('npts') : '';
        $nprt = ($this->_getParam('nprt'))? $this->_getParam('nprt') : '';

        if ($node) $fd = $node;
        if ($npts) $fd = $npts;
        if ($nprt) $fd = $nprt;

        $sReturn = "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
        $sReturn = base64_encode($sReturn);

//        $registry = Zend_Registry::getInstance();
//        $config = $registry->get(Pandamp_Keys::REGISTRY_APP_OBJECT);
//        $url = $config->getOption('login');
//
//        $remoteLogin = $url['remote']['url'];
//        $this->view->remoteLogin = $remoteLogin;
//        $loginUrl = $remoteLogin.'/customer/login/sReturn/'.$sReturn;

		$identity = Pandamp_Application::getResource('identity');
		$signIn = $identity->loginUrl;
		$loginUrl = $signIn.'?returnTo='.$sReturn;

        $rowset = App_Model_Show_Catalog::show()->getCatalogByGuid($catalogGuid);

        if (isset($rowset)) {
            $modelAsset = new App_Model_Db_Table_AssetSetting();
            $rowAsset = $modelAsset->find($catalogGuid)->current();

            if ($rowAsset) {
                $rowAsset->valueInt = $rowAsset->valueInt + 1;
            }
            else
            {
                $rowAsset = $modelAsset->fetchNew();
                $rowAsset->guid = $catalogGuid;
                $rowAsset->detail = $fd;
                $rowAsset->application = $rowset['profileGuid'];
                $rowAsset->part = "MOST_READABLE_DATACENTER";
                $rowAsset->valueInt = 1;
                $rowAsset->valueText = 'pusatdata';
            }

            $rowAsset->save();

            $auth = Zend_Auth::getInstance();

            //$sso = new Pandamp_Session_Remote();
            //$user = $sso->getInfo();

            if ($rowset['profileGuid'] == 'kutu_putusan') {
                if (!$auth->hasIdentity()) {
                    $this->_redirect($loginUrl);
                }
            }

            $rowsetCatalogAttributeJenis = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($rowset['guid'],'prtJenis');
            if (!empty($rowsetCatalogAttributeJenis))
            {
                if (($rowsetCatalogAttributeJenis == 'Undang-Undang ') || ($rowsetCatalogAttributeJenis == "uu") || ($rowsetCatalogAttributeJenis == "pp") || ($rowsetCatalogAttributeJenis == "Peraturan Pemerintah") || ($rowsetCatalogAttributeJenis == "konstitusi"))
                {
                }
                else
                {
                    if (!$auth->hasIdentity()) {
                        $this->_redirect($loginUrl);
                    }
                    else
                    {
                        $username = $auth->getIdentity()->username;
                        $acl = Pandamp_Acl::manager();
                        $aReturn = $acl->getUserGroupIds($username);
                        //print_r($aReturn[1]);die;
                        if (isset($aReturn[0])) {

                            if ($aReturn[0] == "member_gratis") {
                                $this->_helper->redirector('restricted', "browser", 'hold');
                            }

                        }
                    }
                }
            }

            $this->view->catalogGuid = $catalogGuid;
            $this->view->node = $node;
            $this->view->npts = $npts;
            $this->view->nprt = $nprt;
        }
    }
    function restrictedAction() {}
    function downloadFileAction()
    {
    	$this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

    	$catalogGuid = $this->_getParam('guid');
    	$parentGuid = $this->_getParam('parent');

    	$tblCatalog = new App_Model_Db_Table_Catalog();
    	$rowsetCatalog = $tblCatalog->find($catalogGuid);

    	if(count($rowsetCatalog))
    	{
    		$auth = Zend_Auth::getInstance();
                
                //$sso = new Pandamp_Session_Remote();
                //$user = $sso->getInfo();

    		if ($auth->hasIdentity())
    		{
    			$guidUser = $auth->getIdentity()->guid;
    		}

    		$tblAsetSetting = new App_Model_Db_Table_AssetSetting();
    		$rowAset = $tblAsetSetting->find($catalogGuid)->current();
    		if ($rowAset)
    		{
    			$rowAset->valueInt = $rowAset->valueInt + 1;
    		}
    		else
    		{
    			$rowAset = $tblAsetSetting->fetchNew();
				$rowAset->guid = $catalogGuid;
				$rowAset->application = "kutu_doc";
				$rowAset->part = (isset($guidUser))? $guidUser : '';
				$rowAset->valueType = gethostbyaddr($_SERVER['REMOTE_ADDR']);
				$rowAset->valueInt = 1;
    		}

    		$rowAset->save();

    		$rowCatalog = $rowsetCatalog->current();
    		$rowsetCatAtt = $rowCatalog->findDependentRowsetCatalogAttribute();

	    	$contentType = $rowsetCatAtt->findByAttributeGuid('docMimeType')->value;
			$filename = $systemname = $rowsetCatAtt->findByAttributeGuid('docSystemName')->value;
			$oriName = $oname = $rowsetCatAtt->findByAttributeGuid('docOriginalName')->value;

			$tblRelatedItem = new App_Model_Db_Table_RelatedItem();
			$rowsetRelatedItem = $tblRelatedItem->fetchAll("itemGuid='$catalogGuid' AND relateAs='RELATED_FILE'");

			$flagFileFound = false;

			foreach($rowsetRelatedItem as $rowRelatedItem)
			{
				if(!$flagFileFound)
				{
					$parentGuid = $rowRelatedItem->relatedGuid;
					$sDir1 = ROOT_DIR.DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.$systemname;
					$sDir2 = ROOT_DIR.DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.$parentGuid.DIRECTORY_SEPARATOR.$systemname;
					$sDir3 = ROOT_DIR.DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.$oname;
					$sDir4 = ROOT_DIR.DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.$parentGuid.DIRECTORY_SEPARATOR.$oname;

					if(file_exists($sDir1))
					{
						$flagFileFound = true;
						header("Content-type: $contentType");
						header("Content-Disposition: attachment; filename=$oriName");
						@readfile($sDir1);
						die();
					}
					else
						if(file_exists($sDir2))
						{
							$flagFileFound = true;
							header("Content-type: $contentType");
							header("Content-Disposition: attachment; filename=$oriName");
							@readfile($sDir2);
							die();
						}
						if (file_exists($sDir3))
						{
							$flagFileFound = true;
							header("Content-type: $contentType");
							header("Content-Disposition: attachment; filename=$oriName");
							@readfile($sDir3);
							die();
						}
						if (file_exists($sDir4))
						{
							$flagFileFound = true;
							header("Content-type: $contentType");
							header("Content-Disposition: attachment; filename=$oriName");
							@readfile($sDir4);
							die();
						}
						else
						{
							$flagFileFound = false;
							$this->_forward('forbidden','browser','hold');
						}
				}
			}

    	}
    	else
    	{
            $flagFileFound = false;
            $this->_forward('forbidden','browser','hold');
    	}
    }
    function forbiddenAction() {}
}
