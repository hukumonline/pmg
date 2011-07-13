<?php

/**
 * Description of SidebarController
 *
 * @author nihki <nihki@madaniyah.com>
 */
class HolSite_Widgets_SidebarController extends Zend_Controller_Action
{
    function terbaruAction()
    {
        $rowset = App_Model_Show_Catalog::show()->fetchFromFolder('fb16',0,10);

        $content = 0;
        $data = array();

        foreach ($rowset as $row)
        {
            $data[$content][0] = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row['guid'],'fixedTitle');
            $data[$content][1] = $row['shortTitle'];
            $data[$content][2] = date("d/m/y",strtotime($row['createdDate']));
            $data[$content][3] = $row['guid'];
            $data[$content][4] = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row['guid'],'fixedDescription');
            $content++;
        }

        $num_rows = count($rowset);

        $this->view->numberOfRows = $num_rows;
        $this->view->data = $data;
    }
    function foseAction()
    {
        $r = $this->getRequest();
        $notGuid = ($r->getParam('guid'))? $r->getParam('guid') : '';
    	
        $array_hari = array(1=>"Senin","Selasa","Rabu","Kamis","Jumat","Sabtu","Minggu");
        
    	$rowset = App_Model_Show_Catalog::show()->fetchFromFolderException('lt4de5c32a53bd4',$notGuid,0,7);
    	
        $content = 0;
        $data = array();

        foreach ($rowset as $row)
        {
            $data[$content][0] = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row['guid'],'fixedTitle');
            $data[$content][1] = $row['guid'];
            $data[$content][2] = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row['guid'],'fixedContent');
            
        	$hari = $array_hari[date("N",strtotime($row['publishedDate']))];

            $data[$content][3] = $hari . ' '. date("d/m/y H:i",strtotime($row['publishedDate']));

            $content++;
        }

        $num_rows = count($rowset);

        $this->view->numberOfRows = $num_rows;
        $this->view->data = $data;
    }
}
