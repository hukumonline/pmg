<?php

/**
 * Description of ContentController
 *
 * @author nihki <nihki@madaniyah.com>
 */
class Berita_Widgets_ContentController extends Zend_Controller_Action
{
    function utamaByDateAction()
    {
        $givenDate = ($this->_getParam('givenDate'))? $this->_getParam('givenDate') : '';

        if ($givenDate) {
            $rowset = App_Model_Show_Catalog::show()->fetchFromFolderByDate('lt4aaa29322bdbb',$givenDate);
        }
        else {
            $rowset = App_Model_Show_Catalog::show()->fetchFromFolder('lt4aaa29322bdbb',0,5);
        }

        $content = 0;
        $data = array();

        foreach ($rowset as $row)
        {
            $rowSumComment = App_Model_Show_Comment::show()->getParentCommentCount($row['guid']);

            $data[$content][0] = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row['guid'],'fixedTitle');
            $data[$content][1] = date("d/m/y",strtotime($row['createdDate']));
            $data[$content][2] = $row['guid'];
            $data[$content][3] = $row['shortTitle'];
            $data[$content][4] = ($rowSumComment <> 0)? '('.$rowSumComment.'&nbsp;tanggapan)' : '';
            $content++;
        }

        $num_rows = count($rowset);

        $this->view->numberOfRows = $num_rows;
        $this->view->data = $data;
    }
    function terbaruByDateAction()
    {
        $givenDate = ($this->_getParam('givenDate'))? $this->_getParam('givenDate') : '';

        if ($givenDate) {
            $rowset = App_Model_Show_Catalog::show()->fetchFromFolderByDate('fb16',$givenDate);
        }
        else {
            $rowset = App_Model_Show_Catalog::show()->fetchFromFolder('fb16',0,5);
        }

        $content = 0;
        $data = array();

        foreach ($rowset as $row)
        {
            $data[$content][0] = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row['guid'],'fixedTitle');
            $data[$content][1] = $row['shortTitle'];
            $data[$content][2] = date("d/m/y",strtotime($row['createdDate']));
            $data[$content][3] = $row['guid'];
            $content++;
        }

        $num_rows = count($rowset);

        $this->view->numberOfRows = $num_rows;
        $this->view->data = $data;
    }
    function fokusByDateAction()
    {
        $givenDate = ($this->_getParam('givenDate'))? $this->_getParam('givenDate') : '';

        if ($givenDate) {
            $rowset = App_Model_Show_Catalog::show()->fetchFromFolderByDate('fb4',$givenDate);
        }
        else {
            $rowset = App_Model_Show_Catalog::show()->fetchFromFolder('fb4',0,5);
        }

        $content = 0;
        $data = array();

        foreach ($rowset as $row)
        {
            $rowSumComment = App_Model_Show_Comment::show()->getParentCommentCount($row['guid']);

            $data[$content][0] = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row['guid'],'fixedTitle');
            $data[$content][1] = $row['shortTitle'];
            $data[$content][2] = date("d/m/y",strtotime($row['createdDate']));
            $data[$content][3] = $row['guid'];
            $data[$content][4] = ($rowSumComment <> 0)? '('.$rowSumComment.'&nbsp;tanggapan)' : '';
            $content++;
        }

        $num_rows = count($rowset);

        $this->view->numberOfRows = $num_rows;
        $this->view->data = $data;
    }
    function isuhangatByDateAction()
    {
        $givenDate = ($this->_getParam('givenDate'))? $this->_getParam('givenDate') : '';

        if ($givenDate) {
            $rowset = App_Model_Show_Catalog::show()->fetchFromFolderByDate('lt4a6f7d5377193',$givenDate);
        }
        else {
            $rowset = App_Model_Show_Catalog::show()->fetchFromFolder('lt4a6f7d5377193',0,13);
        }

        $content = 0;
        $data = array();

        foreach ($rowset as $row)
        {
            $data[$content][0] = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row['guid'],'fixedTitle');
            $data[$content][1] = $row['shortTitle'];
            $data[$content][2] = date("d/m/y",strtotime($row['createdDate']));
            $data[$content][3] = $row['guid'];
            $content++;
        }

        $num_rows = count($rowset);

        $this->view->numberOfRows = $num_rows;
        $this->view->data = $data;
    }
    function tajukByDateAction()
    {
        $givenDate = ($this->_getParam('givenDate'))? $this->_getParam('givenDate') : '';

        if ($givenDate) {
            $rowset = App_Model_Show_Catalog::show()->fetchFromFolderByDate('fb18',$givenDate);
        }
        else {
            $rowset = App_Model_Show_Catalog::show()->fetchFromFolder('fb18',0,10);
        }

        $content = 0;
        $data = array();

        foreach ($rowset as $row)
        {
            $data[$content][0] = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row['guid'],'fixedTitle');
            $data[$content][1] = $row['shortTitle'];
            $data[$content][2] = date("d/m/y",strtotime($row['createdDate']));
            $data[$content][3] = $row['guid'];
            $content++;
        }

        $num_rows = count($rowset);

        $this->view->numberOfRows = $num_rows;
        $this->view->data = $data;
    }
    function kolomByDateAction()
    {
        $givenDate = ($this->_getParam('givenDate'))? $this->_getParam('givenDate') : '';

        if ($givenDate) {
            $rowset = App_Model_Show_Catalog::show()->fetchFromFolderByDate('fb7',$givenDate);
        }
        else {
            $rowset = App_Model_Show_Catalog::show()->fetchFromFolder('fb7',0,5);
        }

        $content = 0;
        $data = array();

        foreach ($rowset as $row)
        {
            $rowSumComment = App_Model_Show_Comment::show()->getParentCommentCount($row['guid']);

            $data[$content][0] = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row['guid'],'fixedTitle');
            $data[$content][1] = $row['shortTitle'];
            $data[$content][2] = date("d/m/y",strtotime($row['createdDate']));
            $data[$content][3] = $row['guid'];
            $data[$content][4] = ($rowSumComment <> 0)? '('.$rowSumComment.'&nbsp;tanggapan)' : '';
            $content++;
        }

        $num_rows = count($rowset);

        $this->view->numberOfRows = $num_rows;
        $this->view->data = $data;
    }
    function jedaByDateAction()
    {
        $givenDate = ($this->_getParam('givenDate'))? $this->_getParam('givenDate') : '';

        if ($givenDate) {
            $rowset = App_Model_Show_Catalog::show()->fetchFromFolderByDate('fb14',$givenDate);
        }
        else {
            $rowset = App_Model_Show_Catalog::show()->fetchFromFolder('fb14',0,5);
        }

        $content = 0;
        $data = array();

        foreach ($rowset as $row)
        {
            $data[$content][0] = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row['guid'],'fixedTitle');
            $data[$content][1] = $row['shortTitle'];
            $data[$content][2] = date("d/m/y",strtotime($row['createdDate']));
            $data[$content][3] = $row['guid'];
            $content++;
        }

        $num_rows = count($rowset);

        $this->view->numberOfRows = $num_rows;
        $this->view->data = $data;
    }
    function resensiByDateAction()
    {
        $givenDate = ($this->_getParam('givenDate'))? $this->_getParam('givenDate') : '';

        if ($givenDate) {
            $rowset = App_Model_Show_Catalog::show()->fetchFromFolderByDate('fb17',$givenDate);
        }
        else {
            $rowset = App_Model_Show_Catalog::show()->fetchFromFolder('fb17',0,5);
        }

        $content = 0;
        $data = array();

        foreach ($rowset as $row)
        {
            $rowSumComment = App_Model_Show_Comment::show()->getParentCommentCount($row['guid']);

            $data[$content][0] = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row['guid'],'fixedTitle');
            $data[$content][1] = $row['shortTitle'];
            $data[$content][2] = date("d/m/y",strtotime($row['createdDate']));
            $data[$content][3] = $row['guid'];
            $data[$content][4] = ($rowSumComment <> 0)? '('.$rowSumComment.'&nbsp;tanggapan)' : '';
            $content++;
        }

        $num_rows = count($rowset);

        $this->view->numberOfRows = $num_rows;
        $this->view->data = $data;
    }
    function tokohByDateAction()
    {
        $givenDate = ($this->_getParam('givenDate'))? $this->_getParam('givenDate') : '';

        if ($givenDate) {
            $rowset = App_Model_Show_Catalog::show()->fetchFromFolderByDate('fb12',$givenDate);
        }
        else {
            $rowset = App_Model_Show_Catalog::show()->fetchFromFolder('fb12',0,5);
        }

        $content = 0;
        $data = array();

        foreach ($rowset as $row)
        {
            $rowSumComment = App_Model_Show_Comment::show()->getParentCommentCount($row['guid']);

            $data[$content][0] = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row['guid'],'fixedTitle');
            $data[$content][1] = $row['shortTitle'];
            $data[$content][2] = date("d/m/y",strtotime($row['createdDate']));
            $data[$content][3] = $row['guid'];
            $data[$content][4] = ($rowSumComment <> 0)? '('.$rowSumComment.'&nbsp;tanggapan)' : '';
            $content++;
        }

        $num_rows = count($rowset);

        $this->view->numberOfRows = $num_rows;
        $this->view->data = $data;
    }
    function infoByDateAction()
    {
        $givenDate = ($this->_getParam('givenDate'))? $this->_getParam('givenDate') : '';

        if ($givenDate) {
            $rowset = App_Model_Show_Catalog::show()->fetchFromFolderByDate('fb9',$givenDate);
        }
        else {
            $rowset = App_Model_Show_Catalog::show()->fetchFromFolder('fb9',0,5);
        }

        $content = 0;
        $data = array();

        foreach ($rowset as $row)
        {
            $data[$content][0] = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row['guid'],'fixedTitle');
            $data[$content][1] = $row['shortTitle'];
            $data[$content][2] = date("d/m/y",strtotime($row['createdDate']));
            $data[$content][3] = $row['guid'];
            $content++;
        }

        $num_rows = count($rowset);

        $this->view->numberOfRows = $num_rows;
        $this->view->data = $data;
    }
    function aktualByDateAction()
    {
        $givenDate = ($this->_getParam('givenDate'))? $this->_getParam('givenDate') : '';

        if ($givenDate) {
            $rowset = App_Model_Show_Catalog::show()->fetchFromFolderByDate('fb29',$givenDate);
        }
        else {
            $rowset = App_Model_Show_Catalog::show()->fetchFromFolder('fb29',0,5);
        }

        $content = 0;
        $data = array();

        foreach ($rowset as $row)
        {
            $data[$content][0] = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row['guid'],'fixedTitle');
            $data[$content][1] = $row['shortTitle'];
            $data[$content][2] = date("d/m/y",strtotime($row['createdDate']));
            $data[$content][3] = $row['guid'];
            $content++;
        }

        $num_rows = count($rowset);

        $this->view->numberOfRows = $num_rows;
        $this->view->data = $data;
    }

    function beritaBawahAction()			{}
    function terbaruBeritaBawahAction()			{}
    function fokusBeritaBawahAction()			{}
    function isuhangatBeritaBawahAction()		{}
    function tajukBeritaBawahAction()			{}
    function kolomBeritaBawahAction()			{}
    function jedaBeritaBawahAction()			{}
    function resensiBeritaBawahAction()			{}
    function tokohBeritaBawahAction()			{}
    function infoBeritaBawahAction()			{}
    function aktualBeritaBawahAction()			{}

    function indexwartaAction()
    {
        $time_start = microtime(true);

        $folderGuid = ($this->_getParam('folderGuid'))? $this->_getParam('folderGuid') : '';

        $data = array();

        $num_rows = App_Model_Show_Catalog::show()->getWartaCount($folderGuid);
        $limit = 50;

        $data['folderGuid'] = $folderGuid;
        $data['totalCount'] = $num_rows;
        $data['totalCountRows'] = $num_rows;
        $data['limit'] = $limit;

        $this->view->aData = $data;

        $time_end = microtime(true);
        $time = $time_end - $time_start;

        $this->view->time = round($time,2) . ' detik';
    }
    function detailIndexWartaAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

        $folderGuid = ($this->_getParam('folderGuid'))? $this->_getParam('folderGuid') : '';
        $start		= ($this->_getParam('start'))? $this->_getParam('start') : 0;
        $limit		= ($this->_getParam('limit'))? $this->_getParam('limit') : 0;

        $a = array();

        $rowset = App_Model_Show_Catalog::show()->fetchFromFolder($folderGuid,$start,$limit);

        $a['folderGuid'] = $folderGuid;
        $a['totalCount'] = App_Model_Show_Catalog::show()->getWartaCount($folderGuid);

        $ii = 0;

        if ($a['totalCount']!=0)
        {
            foreach ($rowset as $row)
            {
                $rowSumComment = App_Model_Show_Comment::show()->getParentCommentCount($row['guid']);

                $a['index'][$ii]['title'] 	= App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row['guid'],'fixedTitle');
                $a['index'][$ii]['shortTitle']	= $row['shortTitle'];
                $a['index'][$ii]['createdDate']	= date("d/m/y",strtotime($row['createdDate']));
                $a['index'][$ii]['guid']	= $row['guid'];
                $a['index'][$ii]['comment']	= ($rowSumComment <> 0)? '('.$rowSumComment.'&nbsp;tanggapan)' : '';
                $ii++;
            }
        }
        if ($a['totalCount'] == 0)
        {
            $a['index'][0]['title'] = "-";
            $a['index'][0]['shortTitle'] = "-";
            $a['index'][0]['createdDate'] = "-";
            $a['index'][0]['guid'] = "-";
            $a['index'][0]['comment'] = "-";
        }
        echo Zend_Json::encode($a);
    }
    function indexwartawcomAction()
    {
        $time_start = microtime(true);

        $folderGuid = ($this->_getParam('folderGuid'))? $this->_getParam('folderGuid') : '';

        $data = array();

        $num_rows = App_Model_Show_Catalog::show()->getWartaCount($folderGuid);
        $limit = 50;

        $data['folderGuid'] = $folderGuid;
        $data['totalCount'] = $num_rows;
        $data['totalCountRows'] = $num_rows;
        $data['limit'] = $limit;

        $this->view->aData = $data;

        $time_end = microtime(true);
        $time = $time_end - $time_start;

        $this->view->time = round($time,2) . ' detik';
    }
    function detailIndexWartaWComAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

        $folderGuid = ($this->_getParam('folderGuid'))? $this->_getParam('folderGuid') : '';
        $start		= ($this->_getParam('start'))? $this->_getParam('start') : 0;
        $limit		= ($this->_getParam('limit'))? $this->_getParam('limit') : 0;

        $a = array();

        $rowset = App_Model_Show_Catalog::show()->fetchFromFolder($folderGuid,$start,$limit);

        $a['folderGuid'] = $folderGuid;
        $a['totalCount'] = App_Model_Show_Catalog::show()->getWartaCount($folderGuid);

        $ii = 0;

        if ($a['totalCount']!=0)
        {
            foreach ($rowset as $row)
            {
                $a['indexcom'][$ii]['title']        = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row['guid'],'fixedTitle');
                $a['indexcom'][$ii]['shortTitle']   = $row['shortTitle'];
                $a['indexcom'][$ii]['createdDate']  = date("d/m/y",strtotime($row['createdDate']));
                $a['indexcom'][$ii]['guid']         = $row['guid'];
                $ii++;
            }
        }
        if ($a['totalCount'] == 0)
        {
                $a['indexcom'][0]['title'] = "-";
                $a['indexcom'][0]['shortTitle'] = "-";
                $a['indexcom'][0]['createdDate'] = "-";
                $a['indexcom'][0]['guid'] = "-";
        }
        echo Zend_Json::encode($a);
    }

}
