<?php

/**
 * Description of FormController
 *
 * @author nihki <nihki@madaniyah.com>
 */
class Klinik_FormController extends Zend_Controller_Action
{
    function preDispatch()
    {
        $this->_helper->layout->setLayout('layout-klinik');
    }
    function indexAction()
    {

    }
    function addClinicAction()
    {
        $gen = new Pandamp_Form_Helper_KlinikInputGenerator();
        $aRender = $gen->generateFormAdd();
        $this->view->aRenderedAttributes = $aRender;
    }

}
