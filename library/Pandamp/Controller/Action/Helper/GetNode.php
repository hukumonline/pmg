<?php

class Pandamp_Controller_Action_Helper_GetNode
{
    public function getNode($guid)
    {
        $rowset = App_Model_Show_CatalogFolder::show()->getCatalogByGuid($guid);

        if ($rowset)
                return $rowset['folderGuid'];
        else
                return '';
    }
}
