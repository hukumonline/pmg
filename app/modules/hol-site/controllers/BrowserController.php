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
    	$this->_helper->layout->setLayout('layout-detail');
        $catalogGuid = ($this->_getParam('guid'))? $this->_getParam('guid') : '';
        $page = ($this->_getParam('page'))? $this->_getParam('page') : '';
        $this->view->catalogGuid = $catalogGuid;
        $this->view->page = $page;
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
