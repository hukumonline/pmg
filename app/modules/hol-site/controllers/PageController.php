<?php

/**
 * Description of PageController
 *
 * @author nihki <nihki@madaniyah.com>
 */
class HolSite_PageController extends Zend_Controller_Action
{
    function preDispatch()
    {
        $this->_helper->layout->setLayout('layout-kategori');
    }
    function indexAction()
    {
        $r = $this->getRequest();

        $row = App_Model_Show_Folder::show()->getIdByTitle($r->getParam('page'));
        $this->view->id = $row['guid'];
        $this->view->title = $row['title'];
    }
    function browseAction()
    {
        $r = $this->getRequest();
        $page = $r->getParam('page');

        $rowset = App_Model_Show_Catalog::show()->fetchFromFolder($page);

        $content = 0;
        $data = array();

        foreach ($rowset as $row)
        {

            $title = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row['guid'],'fixedTitle');
            $description = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row['guid'],'fixedDescription');

            $data[$content][0] = $title;
            $data[$content][1] = $row['createdDate'];
            $data[$content][2] = $description;
            $data[$content][3] = $row['guid'];
            $data[$content][4] = $row['shortTitle'];
            $content++;
        }

        $num_rows = count($rowset);

        $this->view->numberOfRows = $num_rows;
        $this->view->data = $data;
    }

}
