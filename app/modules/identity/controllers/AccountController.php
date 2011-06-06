<?php

/**
 * Description of AccountController
 *
 * @author nihki <nihki@madaniyah.com>
 */
class Identity_AccountController extends Zend_Controller_Action
{
    protected $_user;

    function  preDispatch()
    {
        $this->_helper->layout->setLayout('layout-login');

        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity())
        {
            $this->_user = $auth->getIdentity();
        }
    }
    function loginAction()
    {
        $returnTo = ($this->_getParam('sReturn'))? $this->_getParam('sReturn') : '';

        $tblCatalog = new App_Model_Db_Table_Catalog();
        $rowset = $tblCatalog->fetchRow("shortTitle='halaman-depan-login' AND status=99");

        if(!empty($rowset))
        {
            $fixedContent = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($rowset->guid,'fixedContent');
        }
        else
        {
            $fixedContent = '';
        }

        $this->view->content = $fixedContent;

        $this->view->identity = 'Profile';

        $sso = new Pandamp_Session_Remote();

        $this->view->broker = $sso->broker;

        if ($this->getRequest()->isPost())
        {
            $request = $this->getRequest();

            $username = ($request->getParam('username'))? $request->getParam('username') : '';
            $password = ($request->getParam('password'))? $request->getParam('password') : '';

            $returnUrl = base64_decode($returnTo);

            $authAdapter = new Pandamp_Auth_Manager($username, $password);

            $authResult = $authAdapter->authenticate();
            if ($authResult->isValid())
            {
                $this->_redirect($returnUrl);
            }
            else
            {
                $messages = $authResult->getMessages();
                $this->view->message = $messages[0];
            }
        }
    }
    function logoutAction()
    {
        $this->_helper->viewRenderer->setNoRender(TRUE);
        $this->_helper->layout->disableLayout();

        $returnTo = ($this->_getParam('sReturn'))? $this->_getParam('sReturn') : '';

        $sso = new Pandamp_Session_Remote();
        $sso->logout();

        $this->_redirect(base64_decode($returnTo));
    }
    function profileAction()
    {
        $this->_helper->layout->setLayout('layout-profile');
        $this->view->identity = 'Profile';
    }
    function registerAction()
    {
        $tblCatalog = new App_Model_Db_Table_Catalog();
        $rowset = $tblCatalog->fetchRow("shortTitle='halaman-depan-login' AND status=99");

        if(!empty($rowset))
        {
            $fixedContent = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($rowset->guid,'fixedContent');
        }
        else
        {
            $fixedContent = '';
        }

        $this->view->content = $fixedContent;

        $this->view->identity = 'Register';

        $sReturn = "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
        $sReturn = base64_encode($sReturn);

        $this->view->login = $sReturn;

        $r = $this->getRequest();

        if ($r->isPost())
        {
            $username = $r->getParam('username');
            $password = $r->getParam('password');
            $email = $r->getParam('email');
            $package = $r->getParam('aro_groups');

            $obj = new Pandamp_Crypt_Password();
            $data = array(
                'kopel'		=> $this->generateKopel()
                ,'username'	=> $username
                ,'password'	=> $obj->encryptPassword($password)
                ,'email'	=> $email
                ,'packageId'	=> $package
                ,'createdDate'	=> date('Y-m-d h:i:s')
                ,'createdBy'	=> $username
            );

            $modelUser = new App_Model_Db_Table_User();
            $modelUser->insert($data);

            $this->updateKopel();

            $acl = Pandamp_Acl::manager();
            $acl->addUser($username,"Member Free");

        }
    }
    function aturanpakaiAction()
    {
        $tblCatalog = new App_Model_Db_Table_Catalog();
        $rowset = $tblCatalog->fetchRow("shortTitle='aturan-pakai' AND profileGuid='kutu_signup'");

        $this->view->title = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($rowset->guid,'fixedTitle');
        $this->view->content = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($rowset->guid,'fixedContent');
        
        $this->view->identity = 'Aturan Pakai';
        
    }
    function checkusernameAction()
    {
        $this->_helper->viewRenderer->setNoRender(TRUE);

        $username = ($this->_getParam('username'))? $this->_getParam('username') : '';

        $modelUser = new App_Model_Db_Table_User();
        $rowset = $modelUser->fetchRow("username='$username'");

        if($rowset)
            $valid = 'false';
        else
            $valid = 'true';

        echo $valid;
        die();
    }
    protected function generateKopel()
    {
        $rowset = App_Model_Show_Number::show()->getNumber();
        $num = $rowset['user'];
        $totdigit = 5;
        $num = strval($num);
        $jumdigit = strlen($num);
        $kopel = str_repeat("0",$totdigit-$jumdigit).$num;

        return $kopel;
    }
    protected function updateKopel()
    {
        $modelNumber = new App_Model_Db_Table_Number();
        $rowset = $modelNumber->fetchRow();
        $rowset->user = $rowset->user += 1;
        $rowset->save();
    }
}
