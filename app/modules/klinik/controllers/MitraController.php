<?php

/**
 * Description of MitraController
 *
 * @author nihki <nihki@madaniyah.com>
 */
class Klinik_MitraController extends Zend_Controller_Action
{
    function preDispatch()
    {
        $this->_helper->layout->setLayout('layout-klinik');
    }
    function _mitraKlinikAction()
    {
        $tblCatalog = new App_Model_Db_Table_Catalog();
        $rowset = $tblCatalog->fetchAll("guid!='lt4b1a5aadda0f1' AND profileGuid='partner'",'createdDate DESC');

        $content = 0;
        $data = array();

        foreach ($rowset as $row)
        {
            $data[$content][0] = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row->guid,'fixedTitle');
            $data[$content][1] = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row->guid,'fixedContent');
            $data[$content][2] = $row->guid;
            $content++;
        }

        $num_row = count($rowset);

        $this->view->numberOfRows = $num_row;
        $this->view->aData = $data;
    }
    function mitraKlinikAction()
    {
		$query = "profile:klinik status:99";    	
		$indexingEngine = Pandamp_Search::manager();
		$hits = $indexingEngine->find($query,0,1);
		$content=0;
		$data=array();
		foreach ($hits->facet_counts->facet_fields->sumber as $facet => $count)
		{
			if (!$facet)
			{
				continue;
			}
			else 
			{
				$data[$content][0]=$facet;
				$data[$content][1]=$count;
			}
			
			$content++;
		}
		
		$this->view->aData = $data;
		
		/*
		echo '<pre>';
		print_r($data);
		echo '</pre>';
		*/
    }
    function mitralainAction()
    {
        $catalogGuid = ($this->_getParam('guid'))? $this->_getParam('guid') : '';
        
		$query = "profile:klinik status:99 -sumber:$catalogGuid";    	
		$indexingEngine = Pandamp_Search::manager();
		$hits = $indexingEngine->find($query,0,1);
		$content=0;
		$data=array();
		foreach ($hits->facet_counts->facet_fields->sumber as $facet => $count)
		{
			if (!$facet || $count == 0)
			{
				continue;
			}
			else 
			{
				$data[$content][0]=$facet;
				$data[$content][1]=$count;
			}
			
			$content++;
		}
		
		$this->view->aData = $data;
    }
    function detailAction()
    {
        $catalogGuid = ($this->_getParam('guid'))? $this->_getParam('guid') : '';
        $this->view->catalogGuid = $catalogGuid;
    }
	function pokrolAction()
	{
		$ct = ($this->_getParam('ko'))? $this->_getParam('ko') : '';
		
		$rowset = App_Model_Show_Catalog::show()->getCatalogByGuid("lt4b457ff0c3e1b");
		
		$title = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($rowset['guid'],'fixedTitle');
		$content = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($rowset['guid'],'fixedContent');
		
		$this->view->title = $title;
		$this->view->content = $content;
		$this->view->catalogGuid = "lt4b457ff0c3e1b";
		$this->view->ct = $ct;
	}
}
