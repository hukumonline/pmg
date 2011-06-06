<?php
class Pandamp_Controller_Action_Helper_GetFotoAttributeName extends Zend_Controller_Action_Helper_Abstract
{
    public function getFotoAttributeName($catalogGuid)
    {
        $rowset = App_Model_Show_Catalog::show()->getCatalogByGuid($catalogGuid);
        if (isset($rowset))
            $title = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($rowset['guid'],'fixedTitle');

            return $title;
    }
}