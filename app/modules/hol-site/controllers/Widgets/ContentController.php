<?php

/**
 * Description of ContentController
 *
 * @author nihki <nihki@madaniyah.com>
 */
class HolSite_Widgets_ContentController extends Zend_Controller_Action
{
	public function init()
	{
	    $ajaxContext = $this->_helper->getHelper('AjaxContext');
	    $ajaxContext->addActionContext('aktual', 'html')
	                ->initContext();
	}	
    function pusatdataAction()
    {
        
    }
    function pusatdata80Action()
    {
        
    }
    function aktualAction()
    {
        $modelCatalog = App_Model_Show_Catalog::show()->fetchFromFolder('fb29',0,5);
        $this->view->rowset = $modelCatalog;
    }
    function klinikAction()
    {
        $rowset = App_Model_Show_Catalog::show()->fetchFromFolder('lt4a0a533e31979',0,4);

        $content = 0;
        $data = array();

        foreach ($rowset as $row)
        {
            $rowCatalogTitle = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row['guid'],'fixedCommentTitle');
            $rowCatalogQuestion = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row['guid'],'fixedCommentQuestion');
            $rowCatalogSelectCat = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row['guid'],'fixedKategoriKlinik');
            $author = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row['guid'],'fixedSelectNama');
            $source = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row['guid'],'fixedSelectMitra');

            /* Get Category from profile clinic_category */
            $findCategory = App_Model_Show_Catalog::show()->getCatalogByGuid($rowCatalogSelectCat);
            $category = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($findCategory['guid'],'fixedTitle');

            /* Get Author from profile author */
            $findAuthor = App_Model_Show_Catalog::show()->getCatalogByGuid($author);
            $author = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($findAuthor['guid'],'fixedTitle');

            /* Get Source from profile partner */
            $findSource = App_Model_Show_Catalog::show()->getCatalogByGuid($source);

            if ($findSource) {
                    $source = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($findSource['guid'],'fixedTitle');
            }

            $data[$content][0] = $rowCatalogTitle;
            $data[$content][1] = $rowCatalogQuestion;
            $data[$content][2] = $category;
            $data[$content][3] = $row['guid'];
            $data[$content][4] = $row['createdBy'];
            $data[$content][5] = (isset($author))? $author : '';
            $data[$content][6] = (isset($source))? $source : '';
            $content++;
        }

        $num_rows = count($rowset);

        $this->view->numberOfRows = $num_rows;
        $this->view->data = $data;
    }
    function klinik80Action()
    {
        $rowset = App_Model_Show_Catalog::show()->fetchFromFolder('lt4a0a533e31979',0,4);

        $content = 0;
        $data = array();

        foreach ($rowset as $row)
        {
            $rowCatalogTitle = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row['guid'],'fixedCommentTitle');
            $rowCatalogQuestion = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row['guid'],'fixedCommentQuestion');
            $rowCatalogSelectCat = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row['guid'],'fixedKategoriKlinik');
            $author = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row['guid'],'fixedSelectNama');
            $source = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row['guid'],'fixedSelectMitra');

            /* Get Category from profile clinic_category */
            $findCategory = App_Model_Show_Catalog::show()->getCatalogByGuid($rowCatalogSelectCat);
            $category = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($findCategory['guid'],'fixedTitle');

            /* Get Author from profile author */
            $findAuthor = App_Model_Show_Catalog::show()->getCatalogByGuid($author);
            $author = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($findAuthor['guid'],'fixedTitle');

            /* Get Source from profile partner */
            $findSource = App_Model_Show_Catalog::show()->getCatalogByGuid($source);

            if ($findSource) {
                    $source = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($findSource['guid'],'fixedTitle');
            }

            $data[$content][0] = $rowCatalogTitle;
            $data[$content][1] = $rowCatalogQuestion;
            $data[$content][2] = $category;
            $data[$content][3] = $row['guid'];
            $data[$content][4] = $row['createdBy'];
            $data[$content][5] = (isset($author))? $author : '';
            $data[$content][6] = (isset($source))? $source : '';
            $content++;
        }

        $num_rows = count($rowset);

        $this->view->numberOfRows = $num_rows;
        $this->view->data = $data;
    }
    function utamaAction()
    {
        $rowset = App_Model_Show_Catalog::show()->fetchFromFolder('lt4aaa29322bdbb',0,4);

        $content = 0;
        $data = array();

        foreach ($rowset as $row)
        {
            $rowSumComment = App_Model_Show_Comment::show()->getParentCommentCount($row['guid']);

            $data[$content][0] = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row['guid'],'fixedTitle');
            $data[$content][1] = strftime("%H:%M",strtotime($row['createdDate']));
            $data[$content][2] = $row['guid'];
            $data[$content][3] = $row['shortTitle'];
            $data[$content][4] = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row['guid'],'fixedAuthor');
            $data[$content][5] = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row['guid'],'fixedDescription');
            $data[$content][6] = $rowSumComment;
            $content++;
        }

        $num_rows = count($rowset);

        $this->view->numberOfRows = $num_rows;
        $this->view->data = $data;
    }
    function utama80Action()
    {
        $rowset = App_Model_Show_Catalog::show()->fetchFromFolder('lt4aaa29322bdbb',0,4);

        $content = 0;
        $data = array();

        foreach ($rowset as $row)
        {
            $rowSumComment = App_Model_Show_Comment::show()->getParentCommentCount($row['guid']);

            $data[$content][0] = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row['guid'],'fixedTitle');
            $data[$content][1] = strftime("%H:%M",strtotime($row['createdDate']));
            $data[$content][2] = $row['guid'];
            $data[$content][3] = $row['shortTitle'];
            $data[$content][4] = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row['guid'],'fixedAuthor');
            $data[$content][5] = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row['guid'],'fixedDescription');
            $data[$content][6] = $rowSumComment;
            $content++;
        }

        $num_rows = count($rowset);

        $this->view->numberOfRows = $num_rows;
        $this->view->data = $data;
    }
    function hfotoAction()
    {
        $rowset = App_Model_Show_Catalog::show()->fetchFromFolder('lt4de5c32a53bd4',0,50);

        $content = 0;
        $data = array();

        foreach ($rowset as $row)
        {
            $data[$content][0] = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row['guid'],'fixedTitle');
            $data[$content][1] = $row['guid'];
            $data[$content][2] = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row['guid'],'fixedContent');

            $content++;
        }

        $num_rows = count($rowset);

        $this->view->numberOfRows = $num_rows;
        $this->view->data = $data;
    }
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

        $r = $this->getRequest();
        $node = ($r->getParam('node'))? $r->getParam('node') : 'root';

        $menuFolder = App_Model_Show_Folder::show()->getMenu($node);
        $this->view->menuFolder = $menuFolder;
    }
    function getmenuchildAction()
    {
        $r = $this->getRequest();
        $node = $r->getParam('node');

        $rowset = App_Model_Show_Folder::show()->getMenu($node);
        $this->view->rowset = $rowset;
    }
    function fokusAction()
    {
        $rowset = App_Model_Show_Catalog::show()->fetchFromFolder('fb4',0,1);

        $content = 0;
        $data = array();

        foreach ($rowset as $row)
        {
            $rowSumComment = App_Model_Show_Comment::show()->getParentCommentCount($row['guid']);

            $data[$content][0] = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row['guid'],'fixedTitle');
            $data[$content][1] = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row['guid'],'fixedAuthor');
            $data[$content][2] = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row['guid'],'fixedDescription');
            $data[$content][3] = $rowSumComment;
            $data[$content][4] = $row['guid'];
            $data[$content][5] = $row['shortTitle'];
            $content++;
        }

        $num_rows = count($rowset);

        $this->view->numberOfRows = $num_rows;
        $this->view->data = $data;
    }
    function isuhangatAction()
    {
        $rowset = App_Model_Show_Catalog::show()->fetchFromFolder('lt4a6f7d5377193',0,5);

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
    function resensiAction()
    {
        $rowset = App_Model_Show_Catalog::show()->fetchFromFolder('fb17',0,2);

        $columns = 2;
        $content = 0;
        $data = array();

        foreach ($rowset as $row)
        {
            $rowSumComment = App_Model_Show_Comment::show()->getParentCommentCount($row['guid']);

            $data[$content][0] = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row['guid'],'fixedTitle');
            $data[$content][1] = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row['guid'],'fixedAuthor');
            $data[$content][2] = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row['guid'],'fixedDescription');
            $data[$content][3] = $rowSumComment;
            $data[$content][4] = $row['guid'];
            $data[$content][5] = $row['shortTitle'];
            $content++;
        }

        $num_rows = count($rowset);
        $rows = ceil($num_rows/$columns);

        if ($num_rows < 2) {
            $columns = $num_rows;
        }
        if ($num_rows == 0) {}

        $this->view->numberOfRows = $num_rows;
        $this->view->data = $data;
        $this->view->columns = $columns;
        $this->view->rows = $rows;
    }
    function columnAction()
    {
        $rowset = App_Model_Show_Catalog::show()->fetchFromFolder('fb7',0,2);

        $columns = 2;
        $content = 0;
        $data = array();

        foreach ($rowset as $row)
        {
            $rowSumComment = App_Model_Show_Comment::show()->getParentCommentCount($row['guid']);

            $data[$content][0] = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row['guid'],'fixedTitle');
            $data[$content][1] = (App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row['guid'],'fixedAuthor'))? "[".App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row['guid'],'fixedAuthor')."]" : '';
            $data[$content][2] = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row['guid'],'fixedDescription');
            $data[$content][3] = $rowSumComment;
            $data[$content][4] = $row['guid'];
            $data[$content][5] = $row['shortTitle'];
            $content++;
        }

        $num_rows = count($rowset);
        $rows = ceil($num_rows/$columns);

        if ($num_rows < 2) {
            $columns = $num_rows;
        }
        if ($num_rows == 0) {}

        $this->view->numberOfRows = $num_rows;
        $this->view->data = $data;
        $this->view->columns = $columns;
        $this->view->rows = $rows;
    }
    function kolomAction()
    {
        $rowset = App_Model_Show_Catalog::show()->fetchFromFolder('fb7',0,3);

        $content = 0;
        $data = array();

        foreach ($rowset as $row)
        {
            $rowSumComment = App_Model_Show_Comment::show()->getParentCommentCount($row['guid']);

            $data[$content][0] = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row['guid'],'fixedTitle');
            $data[$content][1] = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row['guid'],'fixedAuthor');
            $data[$content][2] = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row['guid'],'fixedDescription');
            $data[$content][3] = $rowSumComment;
            $data[$content][4] = $row['guid'];
            $data[$content][5] = $row['shortTitle'];

            $content++;
        }

        $num_rows = count($rowset);

        $this->view->numberOfRows = $num_rows;
        $this->view->data = $data;
    }
    function suarapembacaAction()
    {
        $rowset = App_Model_Show_Catalog::show()->fetchFromFolder('fb8',0,4);

        $content = 0;
        $data = array();

        foreach ($rowset as $row)
        {
            $data[$content][0] = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row['guid'],'fixedTitle');
            $data[$content][1] = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row['guid'],'fixedDescription');
            $data[$content][2] = $row['guid'];
            $data[$content][3] = $row['shortTitle'];
            $content++;
        }

        $num_rows = count($rowset);

        $this->view->numberOfRows = $num_rows;
        $this->view->data = $data;
    }
    function talkAction()
    {
        $rowset = App_Model_Show_Catalog::show()->fetchFromFolder('lt4a607b5e3c2f2',0,4);

        $content = 0;
        $data = array();

        foreach ($rowset as $row)
        {
            $data[$content][0] = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row['guid'],'fixedTitle');
            $data[$content][1] = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row['guid'],'fixedDescription');
            $data[$content][2] = $row['guid'];
            $data[$content][3] = $row['shortTitle'];
            $content++;
        }

        $num_rows = count($rowset);

        $this->view->numberOfRows = $num_rows;
        $this->view->data = $data;
    }
    function komunitasAction()
    {
        $rowset = App_Model_Show_Catalog::show()->fetchFromFolder('fb19',0,2);

        $content = 0;
        $data = array();

        foreach ($rowset as $row)
        {
            $data[$content][0] = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row['guid'],'fixedTitle');
            $data[$content][1] = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row['guid'],'fixedDescription');
            $data[$content][2] = $row['guid'];
            $data[$content][3] = $row['shortTitle'];
            $content++;
        }

        $num_rows = count($rowset);

        $this->view->numberOfRows = $num_rows;
        $this->view->data = $data;
    }
    function ijtAction()
    {
        $folderGuid = ($this->_getParam('folderGuid'))? $this->_getParam('folderGuid') : '';
        $ta = ($this->_getParam('title'))? $this->_getParam('title') : '';
        $rw = ($this->_getParam('rw'))? $this->_getParam('rw') : 1;

        $rowset = App_Model_Show_Catalog::show()->fetchFromFolder($folderGuid,0,$rw);

        $content = 0;
        $data = array();

        foreach ($rowset as $row)
        {
            $rowSumComment = App_Model_Show_Comment::show()->getParentCommentCount($row['guid']);

            $data[$content][0] = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row['guid'],'fixedTitle');
            $data[$content][1] = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row['guid'],'fixedAuthor');
            $data[$content][2] = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($row['guid'],'fixedDescription');
            $data[$content][3] = $rowSumComment;
            $data[$content][4] = $row['guid'];
            $data[$content][5] = $row['shortTitle'];
            $content++;
        }

        $num_rows = count($rowset);

        $this->view->numberOfRows = $num_rows;
        $this->view->data = $data;
        $this->view->title = $ta;
    }
    function peraturanAction()
    {
        $this->_helper->layout->disableLayout();
        
        $query = "profile:(kutu_peraturan OR kutu_peraturan_kolonial OR kutu_rancangan_peraturan OR kutu_putusan);year desc, regulationOrder desc";

        $indexingEngine = Pandamp_Search::manager();
        $hits = $indexingEngine->find($query,0,4);

        $solrNumFound = count($hits->response->docs);

        $content = 0;
        $data = array();

        for($ii=0;$ii<$solrNumFound;$ii++)
        {
            $row = $hits->response->docs[$ii];
            $data[$content][0] = $row->title;
            $data[$content][1] = $row->subTitle;
            $data[$content][2] = $row->id;
            $content++;
        }

        $num_rows = $solrNumFound;

        $this->view->numberOfRows = $num_rows;
        $this->view->data = $data;

    }
    function peraturanpilihanAction()
    {
        $rowset = App_Model_Show_AssetSetting::show()->getAsset("valueText='pusatdata'", 3);

        $content = 0;
        $data = array();

        foreach ($rowset as $row)
        {
            $rowCatalog = App_Model_Show_Catalog::show()->getCatalogByGuid($row['guid']);
            if ($rowCatalog)
            {
                $title = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($rowCatalog['guid'],'fixedTitle');
                $subTitle = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($rowCatalog['guid'],'fixedSubTitle');

                $data[$content][0] = $title;
                $data[$content][1] = (isset($subTitle))? $subTitle : '';
                $data[$content][2] = $row['guid'];
                $data[$content][3] = $row['detail'];
            }
            $content++;
        }

        $num_rows = count($rowset);

        $this->view->numberOfRows = $num_rows;
        $this->view->data = $data;
    }
}
