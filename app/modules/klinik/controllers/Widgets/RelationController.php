<?php

/**
 * Description of RelationController
 *
 * @author nihki <nihki@madaniyah.com>
 */
class Klinik_Widgets_RelationController extends Zend_Controller_Action
{
    function beritaterkaitAction()
    {
        $catalogGuid = ($this->_getParam('guid'))? $this->_getParam('guid') : '';

        $tblRelatedItem = new App_Model_Db_Table_RelatedItem();

        $where = "relatedGuid='$catalogGuid' AND relateAs='RELATED_Clinic'";
        $rowsetRelatedItem = $tblRelatedItem->fetchAll($where);

        $num_rows = count($rowsetRelatedItem);

        $this->view->numberOfRows = $num_rows;
        $this->view->rowsetRelatedItem = $rowsetRelatedItem;
    }
	function dasarhukumAction()
	{
        $catalogGuid = ($this->_getParam('guid'))? $this->_getParam('guid') : '';

        $tblRelatedItem = new App_Model_Db_Table_RelatedItem();

        $where = "relatedGuid='$catalogGuid' AND relateAs='RELATED_CLINIC_BASE'";
        $rowsetRelatedItem = $tblRelatedItem->fetchAll($where);

        $num_rows = count($rowsetRelatedItem);

        $this->view->numberOfRows = $num_rows;
        $this->view->rowsetRelatedItem = $rowsetRelatedItem;
	}
}
