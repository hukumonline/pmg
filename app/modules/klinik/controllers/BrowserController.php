<?php

/**
 * Description of BrowserController
 *
 * @author nihki <nihki@madaniyah.com>
 */
class Klinik_BrowserController extends Zend_Controller_Action
{
    function preDispatch()
    {
        $this->_helper->layout->setLayout('layout-klinik');
    }
    function detailAction()
    {
        $catalogGuid = ($this->_getParam('guid'))? $this->_getParam('guid') : '';
        $this->view->catalogGuid = $catalogGuid;
    }
    function direktoriAction()
    {
    	
    }
    function authorAction()
    {
        $catalogGuid = ($this->_getParam('guid'))? $this->_getParam('guid') : '';
        $partnerGuid = ($this->_getParam('mitra'))? $this->_getParam('mitra') : '';

        $this->view->catalogGuid = $catalogGuid;
        $this->view->partnerGuid = $partnerGuid;
    }
    function searchAction()
    {
        $query 		= ($this->_getParam('searchQuery'))? $this->_getParam('searchQuery') : '';
        $category 	= ($this->_getParam('category'))? $this->_getParam('category') : '';

        $this->_helper->layout()->searchQuery = $query;
        $this->_helper->layout()->categorySearchQuery = $category;
        $this->view->query = $query;
        $this->view->category = $category;
    }
    function categoryAction()
    {
        $catalogGuid = ($this->_getParam('guid'))? $this->_getParam('guid') : '';
        $this->view->catalogGuid = $catalogGuid;
    }

}
