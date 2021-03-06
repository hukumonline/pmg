<?php

/**
 * Description of ContentController
 *
 * @author nihki <nihki@madaniyah.com>
 */
class Klinik_Widgets_ContentController extends Zend_Controller_Action
{
    function kategoriAction()
    {
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
    function terbaruAction()
    {
        $rowset = App_Model_Show_Catalog::show()->fetchFromFolder('lt4a0a533e31979',0,5);
        
        $dateDiff = new Pandamp_Lib_DateDiff();

        $content = 0;
        $data = array();

        foreach ($rowset as $row)
        {
            $rowCatalogTitle = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row['guid'],'fixedCommentTitle');
            $rowCatalogQuestion = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row['guid'],'fixedCommentQuestion');
            $rowCatalogSelectCat = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row['guid'],'fixedKategoriKlinik');
            $author = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row['guid'],'fixedSelectNama');
            $source = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row['guid'],'fixedSelectMitra');

            $data[$content][0] = $rowCatalogTitle;
            $data[$content][1] = $rowCatalogQuestion;
            $data[$content][2] = $rowCatalogSelectCat;
            $data[$content][3] = $row['guid'];
            $data[$content][4] = $row['createdBy'];
            $data[$content][5] = (isset($author))? $author : '';
            $data[$content][6] = (isset($source))? $source : '';
            $data[$content][7] = $dateDiff->ago(strftime('%Y-%m-%d %H:%M:%S',strtotime($row['publishedDate'])));
            $content++;
        }

        $num_rows = count($rowset);

        $this->view->numberOfRows = $num_rows;
        $this->view->data = $data;
    }
    function popularAction()
    {
        $tblAssetSetting = new App_Model_Db_Table_AssetSetting();
        $rowset = $tblAssetSetting->fetchAll("valueText='klinik'","valueInt DESC",6);
        
        $dateDiff = new Pandamp_Lib_DateDiff();

        $content = 0;
        $data = array();

        foreach ($rowset as $row)
        {
            $rowCatalog = App_Model_Show_Catalog::show()->getCatalogByGuid($row->guid);

            $rowCatalogTitle = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row['guid'],'fixedCommentTitle');
            $rowCatalogQuestion = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row['guid'],'fixedCommentQuestion');
            $rowCatalogSelectCat = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row['guid'],'fixedKategoriKlinik');
            $author = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row['guid'],'fixedSelectNama');
            $source = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row['guid'],'fixedSelectMitra');

            $data[$content][0] = $rowCatalogTitle;
            $data[$content][1] = $rowCatalogQuestion;
            $data[$content][2] = $rowCatalogSelectCat;
            $data[$content][3] = $row->guid;
            $data[$content][4] = $rowCatalog['createdBy'];
            $data[$content][5] = (isset($author))? $author : '';
            $data[$content][6] = (isset($source))? $source : '';
            $data[$content][7] = $row->valueInt;
            $data[$content][8] = $dateDiff->ago(strftime('%Y-%m-%d %H:%M:%S',strtotime($rowCatalog['publishedDate'])));
            $content++;
        }

        $num_rows = count($rowset);

        $this->view->numberOfRows = $num_rows;
        $this->view->data = $data;
    }
    function bundleAction()
    {
    	
    }

    /**
     * @todo SOLR for category clinic
     */
    function kadetAction()
    {
        $this->_helper->layout->disableLayout();
        $catalogGuid = ($this->_getParam('guid'))? $this->_getParam('guid') : '';

        $rowset = App_Model_Show_Catalog::show()->getCatalogByGuid($catalogGuid);

        if ($rowset)
        {
            $category = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($rowset['guid'],'fixedKategoriKlinik');
            /* Get Category from profile clinic_category */
            $findCategory = App_Model_Show_Catalog::show()->getCatalogByGuid($category);
            if (isset($findCategory)) {
                $category = $findCategory['guid'];
            }
        }

        //$c = str_replace(' ','%',$category);
        $query = "profile:klinik kategoriklinik:$category status:99 -id:$catalogGuid;publishedDate desc";

        $indexingEngine = Pandamp_Search::manager();
        $hits = $indexingEngine->find($query,0,10);

        $solrNumFound = count($hits->response->docs);

        $content = 0;
        $data = array();

        for($ii=0;$ii<$solrNumFound;$ii++) {
            $row = $hits->response->docs[$ii];
            $data[$content][0] = $row->id;
            $data[$content][1] = $row->title;
            $content++;
        }

        $num_rows = $solrNumFound;

        $this->view->numberOfRows = $num_rows;
        $this->view->data = $data;
    }
    function vcAction()
    {
		$time_start = microtime(true);
		
		$author = ($this->_getParam('guid'))? $this->_getParam('guid') : '';
		
        $data = array();

        $query = "profile:klinik status:99 kontributor:$author";

        $indexingEngine = Pandamp_Search::manager();
        $hits = $indexingEngine->find($query);

        $num_rows = count($hits->response->docs);

        $limit = 50;

        $data['folderGuid'] = $author;
        $data['totalCount'] = $num_rows;
        $data['totalCountRows'] = $num_rows;
        $data['limit'] = $limit;

        $this->view->aData = $data;

        $time_end = microtime(true);
        $time = $time_end - $time_start;

        $this->view->time = round($time,2) . ' detik';
    }
	function klinikBawahAction()			{}
	function indexklinikAction()
	{
		$time_start = microtime(true);
		
		$folderGuid = 'lt4a0a533e31979';
		
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
	function detailIndexKlinikAction()
	{
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
		
        $dateDiff = new Pandamp_Lib_DateDiff();
        
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
			    $arraypictureformat = array("jpg", "jpeg", "gif");
			    $txt_allowedformat = implode('; ', $arraypictureformat);
			    $registry = Zend_Registry::getInstance();
			    $config = $registry->get(Pandamp_Keys::REGISTRY_APP_OBJECT);
			    $cdn = $config->getOption('cdn');
			    
			    $sDir = $cdn['static']['dir']['photo'];
			    $sDir2 = $cdn['static']['url']['photo'].'/';
			    $smg = $cdn['static']['images'];
			    
			    $modelUser = App_Model_Show_User::show()->getUserByName($row['createdBy']);
			    
			    $x = 0;
			    foreach ($arraypictureformat as $key => $val) {
			        if (is_file($sDir."/".$modelUser['kopel'].".".$val)) {
			            $myphoto = $sDir."/".$modelUser['kopel'].".".$val;
			            $myext = $val;
			            $x = 1;
			            break;
			        }
			    }
			    if ($x == 1) {
			        $myphotosize = getimagesize($myphoto);
			        $dis = "";
			        if (isset($myext) && is_file($sDir."/".$modelUser['kopel'].".".$myext))
			            $txt_existence = "<img src=\"".$sDir2.$modelUser['kopel'].".".$myext."\" class=\"avatar\" width=\"38\" height=\"38\" />";
			
			    }
			    else
			    {
			        $txt_existence = "<img src=\"".$smg."/gravatar-140.png\" width=\"38\" height=\"38\" class=\"avatar\" border=\"0\" />";
			    }
			    
			    
			    
	            $rowCatalogTitle = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row['guid'],'fixedCommentTitle');
	            $rowCatalogQuestion = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row['guid'],'fixedCommentQuestion');
	            $rowCatalogSelectCat = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row['guid'],'fixedKategoriKlinik');
	            $author = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row['guid'],'fixedSelectNama');
	            $source = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row['guid'],'fixedSelectMitra');
	
	            $a['index'][$ii]['title'] = $rowCatalogTitle;
	            $a['index'][$ii]['question'] = $rowCatalogQuestion;
	            $a['index'][$ii]['secat'] = $rowCatalogSelectCat;
	            $a['index'][$ii]['category'] = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($rowCatalogSelectCat,'fixedTitle');
	            $a['index'][$ii]['guid'] = $row['guid'];
	            $a['index'][$ii]['createdBy'] = $row['createdBy'];
	            $a['index'][$ii]['author'] = (isset($author))? $author : '';
	            if (isset($source)) {
	            	$a['index'][$ii]['source'] = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($source,'fixedTitle');
	            	$a['index'][$ii]['sid'] = $source;
	            }
	            else 
	            {
	            	$a['index'][$ii]['source'] = '';
	            	$a['index'][$ii]['sid'] = '';
	            }
	            $a['index'][$ii]['publishedDate'] = $dateDiff->ago(strftime('%Y-%m-%d %H:%M:%S',strtotime($row['publishedDate'])));
	            $a['index'][$ii]['existence'] = '<div style="float:left;padding:2px;margin: 1px 10px 10px 0px;"><a href="">'.$txt_existence.'</a></div>';
	            
                $ii++;
            }
        }
        if ($a['totalCount'] == 0)
        {
            $a['index'][0]['title'] = "-";
            $a['index'][0]['question'] = "-";
            $a['index'][0]['category'] = "-";
            $a['index'][0]['guid'] = "-";
            $a['index'][0]['createdBy'] = "-";
            $a['index'][0]['author'] = "-";
            $a['index'][0]['source'] = "-";
            $a['index'][0]['publishedDate'] = "-";
        }
        
        
        
        
        echo Zend_Json::encode($a);
	}
}
