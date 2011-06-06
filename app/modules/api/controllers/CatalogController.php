<?php

/**
 * Description of CatalogController
 *
 * @author nihki <nihki@madaniyah.com>
 */
class Api_CatalogController extends Zend_Controller_Action
{
    public function getcatalogsinfolderAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

        $r = $this->getRequest();
        $folderGuid = $r->getParam('folderGuid');
        $start = ($r->getParam('start'))? $r->getParam('start') : 0;
        $limit = ($r->getParam('limit'))? $r->getParam('limit'): 0;
        $sort = ($r->getParam('sort'))? $r->getParam('sort') : 'regulationType desc, year desc';

        $a = array();
        $a['folderGuid'] = $folderGuid;

        $db = Zend_Db_Table::getDefaultAdapter()->query
        ("SELECT catalogGuid as guid from KutuCatalogFolder where folderGuid='$folderGuid'");

        $rowset = $db->fetchAll(Zend_Db::FETCH_OBJ);

        $solrAdapter = Pandamp_Search::manager();

        $numi = count($rowset);
        $sSolr = "id:(";
        for($i=0;$i<$numi;$i++)
        {
            $row = $rowset[$i];
            $sSolr .= $row->guid .' ';
        }
        $sSolr .= ')';

        if(!$numi)
                $sSolr="id:(hfgjhfdfka)";

        $solrResult = $solrAdapter->findAndSort($sSolr,$start,$limit, array('sort'=>$sort));
        $solrNumFound = count($solrResult->response->docs);

        $ii=0;
        if($solrNumFound==0)
        {
            $a['catalogs'][0]['guid']= 'XXX';
            $a['catalogs'][0]['title']= "No Data";
            $a['catalogs'][0]['subTitle']= "";
            $a['catalogs'][0]['createdDate']= '';
            $a['catalogs'][0]['modifiedDate']= '';
        }
        else
        {
            if($solrNumFound>$limit)
                $numRowset = $limit ;
            else
                $numRowset = $solrNumFound;

            for($ii=0;$ii<$numRowset;$ii++)
            {
                $row = $solrResult->response->docs[$ii];
                if(!empty($row))
                {
                    $a['catalogs'][$ii]['guid']= $row->id;

                    if($row->profile == 'kutu_doc')
                            $title = 'File : '.$row->title;
                    else
                            $title = $row->title;

                    $a['catalogs'][$ii]['title']= $title;

                    if(!isset($row->subTitle))
                    $a['catalogs'][$ii]['subTitle'] = '';
                else
                    $a['catalogs'][$ii]['subTitle']= $row->subTitle;

                    $a['catalogs'][$ii]['createdDate']= $row->createdDate;
                    $a['catalogs'][$ii]['modifiedDate']= $row->modifiedDate;
                }
            }
        }

        echo Zend_Json::encode($a);
    }
    public function getcatalogsearchAction()
    {
        $this->_helper->layout()->disableLayout();
        
        $r = $this->getRequest();

        $query = ($r->getParam('query'))? $r->getParam('query') : '';
        $category = ($r->getParam('ct'))? $r->getParam('ct') : '';
        $start = ($r->getParam('start'))? $r->getParam('start') : 0;
        $limit = ($r->getParam('limit'))? $r->getParam('limit'): 25;
        $orderBy = ($r->getParam('orderBy'))? $r->getParam('sortBy') : 'publishedDate';
        $sortOrder = ($r->getParam('sortOrder'))? $r->getParam('sortOrder') : ' desc';

        $a = array();

        $a['query']	= $query;

        $indexingEngine = Pandamp_Search::manager();
        
        if(empty($query))
        {
            $hits = $indexingEngine->find("fjkslfjdkfjls",0, 1);
            
        } else {
            
            if ($category)
            {
                $querySolr = $query.' -profile:kutu_doc status:99 profile:'.$category.';'.$orderBy.$sortOrder;
            }
            else
            {
                $querySolr = $query.' -profile:kutu_doc status:99;'.$orderBy.$sortOrder;
            }

            $hits = $indexingEngine->find($querySolr, $start, $limit);
        }

        $num = $hits->response->numFound;

        //$solrNumFound = count($hits->response->docs);
        $solrNumFound = $num;

        $ii=0;
        if($solrNumFound==0)
        {
            $a['catalogs'][0]['guid']= 'XXX';
            $a['catalogs'][0]['title']= "No Data";
            $a['catalogs'][0]['subTitle']= "";
            $a['catalogs'][0]['createdDate']= '';
            $a['catalogs'][0]['modifiedDate']= '';
        }
        else
        {
            if($solrNumFound>$limit)
                $numRowset = $limit ;
            else
                $numRowset = $solrNumFound;

            for($ii=0;$ii<$numRowset;$ii++)
            {
            	if (isset($hits->response->docs[$ii])) {
                $row = $hits->response->docs[$ii];
                if(!empty($row))
                {
                    if ($row->profile == "klinik")
                    {
                        if (isset($hits->highlighting->{$row->id}->title[0]))
                        {
                            if (isset($hits->highlighting->{$row->id}->kategori[0]))
                                $title = "[<b><font color='#FFAD29'>".$hits->highlighting->{$row->id}->kategori[0]."</font></b>]&nbsp;<a href='".ROOT_URL."/klinik/detail/".$row->id."' class='searchlink'>".$hits->highlighting->{$row->id}->title[0]."</a>";
                            else
                                $title = "[<b><font color='#FFAD29'>".$row->kategori."</font></b>]&nbsp;<a href='".ROOT_URL."/klinik/detail/".$row->id."' class='searchlink'>".$hits->highlighting->{$row->id}->title[0]."</a>";

                        } else {
                            
                            if (isset($hits->highlighting->{$row->id}->kategori[0]))
                                $title = "[<b><font color='#FFAD29'>".$hits->highlighting->{$row->id}->kategori[0]."</font></b>]&nbsp;<a href='".ROOT_URL."/klinik/detail/".$row->id."' class='searchlink'><b>$row->title</b></a>";
                            else
                                $title = "[<b><font color='#FFAD29'>".$row->kategori."</font></b>]&nbsp;<a href='".ROOT_URL."/klinik/detail/".$row->id."' class='searchlink'><b>$row->title</b></a>";
                            
                        }

                        if (isset($hits->highlighting->{$row->id}->commentQuestion[0]))
                            $description = ($row->commentQuestion)? "<u>Pertanyaan</u>: ".$hits->highlighting->{$row->id}->commentQuestion[0] : '';
                        else
                            $description = ($row->commentQuestion)? "<u>Pertanyaan</u>: ".$row->commentQuestion : '';

                    }
                    else if ($row->profile == "article")
                    {
                        if (isset($hits->highlighting->{$row->id}->title[0]))
                            $title = "<a href='".ROOT_URL."/berita/baca/".$row->id."/".$row->shortTitle."' class='searchlink'><b>".$hits->highlighting->{$row->id}->title[0]."</b></a>";
                        else
                            $title = "<a href='".ROOT_URL."/berita/baca/".$row->id."/".$row->shortTitle."' class='searchlink'><b>$row->title</b></a>";

                        
                        $description = (isset($row->description))? $row->description : '';
                    }
                    else if ($row->profile == "kutu_peraturan" || $row->profile == "kutu_peraturan_kolonial" || $row->profile == "kutu_rancangan_peraturan" || $row->profile == "kutu_putusan")
                    {
                        $tblCatalogFolder = new App_Model_Db_Table_CatalogFolder();
                        $rowsetCatalogFolder = $tblCatalogFolder->fetchRow("catalogGuid='$row->id'");
                        if ($rowsetCatalogFolder)
                            $parentGuid= $rowsetCatalogFolder->folderGuid;
                        else
                            $parentGuid='';

                        $node = $this->getNode($parentGuid);

                        if (isset($hits->highlighting->{$row->id}->title[0]))
                            $title = "<a href='".ROOT_URL."/pusatdata/detail/".$row->id."/".$node."/".$parentGuid."' class='searchlink'><b>".$hits->highlighting->{$row->id}->title[0]."</b></a>";
                        else
                            $title = "<a href='".ROOT_URL."/pusatdata/detail/".$row->id."/".$node."/".$parentGuid."' class='searchlink'><b>$row->title</b></a>";


                        $description = (isset($row->description))? $row->description : '';
                    }
                    else
                    {
                        $title = (isset($row->title))? $row->title : '';
                        $description = (isset($row->description))? $row->description : '';
                    }


                    if (isset($hits->highlighting->{$row->id}->subTitle[0]))
                        $subTitle = (isset($row->subTitle))? "<span class='subjudul'>".$hits->highlighting->{$row->id}->subTitle[0]."</span><br>" : '';
                    else
                        $subTitle = (isset($row->subTitle))? "<span class='subjudul'>".$row->subTitle."</span><br>" : '';


                    $profileSolr = str_replace("kutu_", "", $row->profile);
                    $a['catalogs'][$ii]['profile']= $profileSolr;
                    $a['catalogs'][$ii]['title']= ($title)? $title : '';
                    $a['catalogs'][$ii]['guid']= $row->id;
                    $a['catalogs'][$ii]['score']= "score:".$row->score;

                    $a['catalogs'][$ii]['description'] = $description;
                    $a['catalogs'][$ii]['subTitle']= (isset($subTitle))? $subTitle : '';


                    $array_hari = array(1=>"Senin","Selasa","Rabu","Kamis","Jumat","Sabtu","Minggu");
                    $hari = $array_hari[date("N",strtotime($row->publishedDate))];

                    $a['catalogs'][$ii]['publishedDate']= $hari . ', '. date("d F Y",strtotime($row->publishedDate));
                }
            	}
            }
        }

        echo Zend_Json::encode($a);
    }
    function getNode($node)
    {
        $tblFolder = new App_Model_Db_Table_Folder();
        $rowFolder = $tblFolder->find($node)->current();
        if ($rowFolder) {
            $path = explode("/",$rowFolder->path);
            $rpath = $path[0];
            $rowFolder1 = $tblFolder->find($rpath)->current();
            if ($rowFolder1) {
                $rowFolder2 = $tblFolder->find($rowFolder1->parentGuid)->current();
                if ($rowFolder2) {
                    if ($rowFolder2->title == "Peraturan") {
                        return "nprt";
                    }
                    elseif ($rowFolder2->title == "Putusan") {
                        return "npts";
                    }
                    else
                    {
                        return "node";
                    }
                }
                else
                {
                    return "node";
                }
            }
            else
            {
                    return "node";
            }
        }
        else
        {
            return "node";
        }
    }
    public function getclinicsearchAction()
    {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
        
        $r = $this->getRequest();

        $query = ($r->getParam('query'))? $r->getParam('query') : '';
        $category = ($r->getParam('ct'))? $r->getParam('ct') : '';
        $start = ($r->getParam('start'))? $r->getParam('start') : 0;
        $limit = ($r->getParam('limit'))? $r->getParam('limit'): 20;
        $orderBy = ($r->getParam('orderBy'))? $r->getParam('sortBy') : 'regulationOrder';
        $sortOrder = ($r->getParam('sortOrder'))? $r->getParam('sortOrder') : ' asc';

        $a = array();

        if ($category)
        {
            $query = $query.' profile:klinik status:99 kategoriklinik:'.$category;
        }
        else
        {
            $query = $query.' profile:klinik status:99';
        }

        $a['query']	= $query;

        $indexingEngine = Pandamp_Search::manager();

        $hits = $indexingEngine->find($query, $start, $limit);

        $num = $hits->response->numFound;

        $solrNumFound = count($hits->response->docs);

        $ii=0;
        if($solrNumFound==0)
        {
            $a['catalogs'][0]['guid']= 'XXX';
            $a['catalogs'][0]['title']= "No Data";
            $a['catalogs'][0]['subTitle']= "";
            $a['catalogs'][0]['createdBy']= "";
            $a['catalogs'][0]['kategori']= "";
            $a['catalogs'][0]['kategoriklinik']= '';
            $a['catalogs'][0]['createdDate']= '';
            $a['catalogs'][0]['modifiedDate']= '';
        }
        else
        {
            if($solrNumFound>$limit)
                $numRowset = $limit ;
            else
                $numRowset = $solrNumFound;

            for($ii=0;$ii<$numRowset;$ii++)
            {
                $row = $hits->response->docs[$ii];
                if(!empty($row))
                {
                    $a['catalogs'][$ii]['title']= $row->title;
                    $a['catalogs'][$ii]['guid']= $row->id;

                    if(!isset($row->commentQuestion))
                    $a['catalogs'][$ii]['commentQuestion'] = '';
                else
                    $a['catalogs'][$ii]['commentQuestion']= $row->commentQuestion;

                    $a['catalogs'][$ii]['createdBy'] = 'Pertanyaan oleh:'.$row->createdBy;
                    $a['catalogs'][$ii]['kategori'] = $row->kategori;
                    $a['catalogs'][$ii]['kategoriklinik'] = $row->kategoriklinik;
                    $a['catalogs'][$ii]['createdDate']= $row->createdDate;
                    $a['catalogs'][$ii]['modifiedDate']= $row->modifiedDate;
                }
            }
        }

        echo Zend_Json::encode($a);
    }

}
