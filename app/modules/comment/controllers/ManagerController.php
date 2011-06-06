<?php

/**
 * Description of ManagerController
 *
 * @author nihki <nihki@madaniyah.com>
 */
class Comment_ManagerController extends Zend_Controller_Action
{
    function  preDispatch()
    {
    }
    function browseAction()
    {
        $catalogGuid 	= ($this->_getParam('guid'))? $this->_getParam('guid') : '';
        $content	= ($this->_getParam('content'))? $this->_getParam('content') : '';
        $bbCoded	= "&nbsp;";

        if ($content != "")	{
            //	Convert BBCode Tags to HTML Tags
            $bbCoded	= Pandamp_Lib_Bbcode::parseBBCode($content);
            $aResult['success'] = true;
            $aResult['data'] = $bbCoded;
            echo Zend_Json::encode($aResult);
            die;
        }

        //	Write ControlPanel
        $notShown	= array(9,10,11);
        $bbCodeCP	= Pandamp_Lib_Bbcode::writeBbCode("tanggapan","theField",$notShown);
        $this->view->bbCodeCP = $bbCodeCP;

        $this->view->catalogGuid = $catalogGuid;
    }
    function list4Action()
    {
        $catalogGuid = ($this->_getParam('guid'))? $this->_getParam('guid') : '';
        $page = ($this->_getParam('page'))? $this->_getParam('page') : 1;

        $rows = App_Model_Show_Comment::show()->getCommentParentByGuidwAjax($catalogGuid);

        $num_rows = App_Model_Show_Comment::show()->getParentCommentCount($catalogGuid);

        $paginator = Zend_Paginator::factory($rows);
        $paginator->setItemCountPerPage(10);
        $paginator->setCurrentPageNumber($page);

        $this->view->paginator = $paginator;

        $this->view->rows = $rows;
        $this->view->numrows = $num_rows;
    }
}
