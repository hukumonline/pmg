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

}
