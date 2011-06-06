<?php

/**
 * Description of GetClinicCategory
 *
 * @author nihki <nihki@madaniyah.com>
 */
class Pandamp_Controller_Action_Helper_GetClinicCategory
{
    public function getClinicCategory($catalogGuid)
    {
        $rowset = App_Model_Show_Catalog::show()->getCatalogByGuid($catalogGuid);

        if ($rowset)
        {
            $category = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($rowset['guid'],'fixedKategoriKlinik');
            /* Get Category from profile clinic_category */
            $findCategory = App_Model_Show_Catalog::show()->getCatalogByGuid($category);
            if (isset($findCategory)) {
                $category = App_Model_Show_CatalogAttribute::show()->getCatalogAttributeValue($findCategory['guid'],'fixedTitle');
            }

            return $category;
        }
    }

}
