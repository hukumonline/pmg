<?php

/**
 * Description of ContributorController
 *
 * @author nihki <nihki@madaniyah.com>
 */

class Klinik_ContributorController extends Zend_Controller_Action 
{
    function preDispatch()
    {
        $this->_helper->layout->setLayout('layout-klinik');
    }
	function detailAction()
	{
        $catalogGuid = ($this->_getParam('guid'))? $this->_getParam('guid') : '';
        $this->view->catalogGuid = $catalogGuid;
	}
}