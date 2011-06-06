<?php

class Talks_BrowserController extends Zend_Controller_Action 
{
	function viewAction()
	{
		
	}
	function indextalksAction()
	{
        $folderGuid = ($this->_getParam('folderGuid'))? $this->_getParam('folderGuid') : 'lt4a607b5e3c2f2';
        
        $data = array();
        
        $rowset = $rowset = App_Model_Show_Catalog::show()->fetchFromFolder('lt4a607b5e3c2f2');

        $num_rows = count($rowset);
        $limit = 10;

        $data['folderGuid'] = $folderGuid;
        $data['totalCount'] = $num_rows;
        $data['totalCountRows'] = $num_rows;
        $data['limit'] = $limit;

        $this->view->aData = $data;

	}
}