<?php

/**
 * Description of CatalogController
 *
 * @author nihki <nihki@madaniyah.com>
 */
class Klinik_Widgets_CatalogController extends Zend_Controller_Action
{
    function klideAction()
    {
        $catalogGuid = ($this->_getParam('guid'))? $this->_getParam('guid') : '';

        $ip = Pandamp_Lib_Formater::getRealIpAddr();

        $rowset = App_Model_Show_Catalog::show()->getCatalogByGuid($catalogGuid);

        if ($rowset)
        {
                $rowAsset = App_Model_Show_AssetSetting::show()->getAssetNumOfClick($catalogGuid);
                $data = array(
                        'guid'		=> $catalogGuid,
                        'application'	=> 'ASSET',
                        'part'		=> 'MOST_READABLE_CLINIC',
                        'valueType'	=> $ip,
                        'valueInt'	=> 1,
                        'valueText'	=> 'klinik'
                );
                $asset = App_Model_Show_AssetSetting::show()->addCounterAsset($rowset['guid'],$data);

                $title = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($rowset['guid'],'fixedCommentTitle');
                $question = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($rowset['guid'],'fixedCommentQuestion');
                $category = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($rowset['guid'],'fixedKategoriKlinik');
                $answer = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($rowset['guid'],'fixedAnswer');
                $author = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($rowset['guid'],'fixedSelectNama');
                $source = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($rowset['guid'],'fixedSelectMitra');
                
                $dateDiff = new Pandamp_Lib_DateDiff();

                $this->view->title 		= (isset($title))? $title : '';
                $this->view->question 		= (isset($question))? $question : '';
                $this->view->category 		= (isset($category))? $category : '';
                $this->view->answer 		= (isset($answer))? $answer : '';
                $this->view->author 		= (isset($author))? $author : '';
                $this->view->source 		= (isset($source))? $source : '';
                $this->view->createdBy 		= $rowset['createdBy'];
                //$this->view->publishedDate	= Pandamp_Lib_Formater::get_date($rowset['publishedDate']);
                $this->view->publishedDate	= $dateDiff->ago(strftime('%Y-%m-%d %H:%M:%S',strtotime($rowset['publishedDate'])));
                $this->view->createdDate	= $dateDiff->ago(strftime('%Y-%m-%d %H:%M:%S',strtotime($rowset['createdDate'])));
                $this->view->numofclick		= (isset($rowAsset))? $rowAsset['valueInt'] : 0;

                // get votes
                $rowRate = App_Model_Show_Vote::show()->getRating($catalogGuid,$ip);
                $val = ($rowRate)? $rowRate['value'] : 0;
                $counter = ($rowRate)? $rowRate['counter'] : 0;

                if ($counter < 1) {
                    $count = 0;
                } else {
                    $count=$counter; //how many votes total
                }
                $current_rating = $val;
                $tense=($count==1) ? "vote" : "votes"; //plural form votes/vote
                $rating = @number_format($current_rating/$count,1);

                $drawrating = '('.$count.' '.$tense.', average: '. $rating .' out of 5)';

                $this->view->drawrating = $drawrating;

                $this->view->catalogGuid = $catalogGuid;
        }
    }
    function detailmitraAction()
    {
        $catalogGuid = ($this->_getParam('guid'))? $this->_getParam('guid') : '';

        $rowset = App_Model_Show_Catalog::show()->getCatalogByGuid($catalogGuid);

        if ($rowset)
        {
            $this->view->title          = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($rowset['guid'],'fixedTitle');
            $this->view->content 	= App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($rowset['guid'],'fixedContent');
            $this->view->catalogGuid    = $catalogGuid;
        }
    }
    function authorAction()
    {
        $this->_helper->layout->disableLayout();
        $mkGuid = ($this->_getParam('guid'))? $this->_getParam('guid') : '';

        $query = "profile:author sumber:$mkGuid;publishedDate desc";

        $indexingEngine = Pandamp_Search::manager();
        $hits = $indexingEngine->find($query);

        $solrNumFound = count($hits->response->docs);

        $content = 0;
        $data = array();

        for($ii=0;$ii<$solrNumFound;$ii++) {
            $row = $hits->response->docs[$ii];
            $data[$content][0] = $row->title;
            $data[$content][1] = $row->subTitle;
            $data[$content][2] = $row->id;
            $content++;
        }

        $num_rows = $solrNumFound;

        $this->view->numberOfRows = $num_rows;
        $this->view->data = $data;
        $this->view->catalogGuid = $mkGuid;
    }
    function contributorAction()
    {
        $this->_helper->layout->disableLayout();
        
        $catalogGuid = ($this->_getParam('guid'))? $this->_getParam('guid') : '';

        $query = "profile:klinik sumber:$catalogGuid status:99";

        $indexingEngine = Pandamp_Search::manager();
        $hits = $indexingEngine->find($query);

        $solrNumFound = count($hits->response->docs);

        $num_rows = $solrNumFound;

        $limit = 20;

        $data['catalogGuid'] = $catalogGuid;
        $data['totalCount'] = $num_rows;
        $data['limit'] = $limit;

        $this->view->aData = $data;
    }
    function pengasuhAction()
    {
        $author = ($this->_getParam('guid'))? $this->_getParam('guid') : '';

        $rowset = App_Model_Show_Catalog::show()->getCatalogByGuid($author);

        $this->view->author = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($rowset['guid'],'fixedTitle');
        $this->view->description = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($rowset['guid'],'fixedSubTitle');
    }
    function viewerClinicAction()
    {
        $this->_helper->layout->disableLayout();
        
        $author = ($this->_getParam('guid'))? $this->_getParam('guid') : '';

        $query = "profile:klinik status:99 kontributor:$author;publishedDate desc";

        $indexingEngine = Pandamp_Search::manager();
        $hits = $indexingEngine->find($query);

        $solrNumFound = count($hits->response->docs);

        $content = 0;
        $data = array();

        for($ii=0;$ii<$solrNumFound;$ii++) {
            $row = $hits->response->docs[$ii];
            $data[$content][0] = $row->title;
            $data[$content][1] = $row->commentQuestion;
            $data[$content][2] = $row->kategori;
            $data[$content][3] = $row->id;
            $data[$content][4] = $row->createdBy;
            $data[$content][5] = $row->createdBy;
            $data[$content][6] = $row->createdBy;
            $content++;
        }

        $num_rows = $solrNumFound;

        $this->view->numberOfRows = $num_rows;
        $this->view->data = $data;
    }
    function kategoriklinikAction()
    {
        $this->_helper->layout->disableLayout();
        
        $catalogGuid = ($this->_getParam('guid'))? $this->_getParam('guid') : '';

        $query = "kategoriklinik:$catalogGuid status:99";

        $indexingEngine = Pandamp_Search::manager();
        $hits = $indexingEngine->find($query);

        $solrNumFound = count($hits->response->docs);

        $num_rows = $solrNumFound;

        $limit = 20;

        $data['catalogGuid'] = $catalogGuid;
        $data['totalCount'] = $num_rows;
        $data['limit'] = $limit;

        $this->view->aData = $data;
    }

}
