<?php

/**
 * Description of IndexController
 *
 * @author nihki <nihki@madaniyah.com>
 */
class Talks_IndexController extends Zend_Controller_Action
{
    function init()
    {
        $this->_helper->cache(array('index'), array('talks'));

        Zend_Session::start();
    }
    function  preDispatch()
    {
        $this->_helper->layout->setLayout('layout-talks');
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
    function viewcartAction() {}
    function viewAction() {}
    function galleryAction()
    {
        $r = $this->getRequest();
        $catalogGuid = ($r->getParam("id"))? $r->getParam("id") : '';

        $this->view->id = $catalogGuid;
    }
    function indexAction()
    {
        
    }
}
