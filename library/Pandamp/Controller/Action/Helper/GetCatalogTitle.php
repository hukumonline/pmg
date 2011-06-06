<?php

/**
 * Description of GetCatalogTitle
 *
 * @author nihki <nihki@madaniyah.com>
 */
class Pandamp_Controller_Action_Helper_GetCatalogTitle
{
    public function getCatalogTitle($catalogGuid, $attributeValue)
    {
        $title = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($catalogGuid, $attributeValue);
        
        if(isset($title) && !empty($title))
                return $title;
        else
                return 'No-Title';
    }
}
