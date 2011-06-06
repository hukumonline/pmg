<?php

/**
 * Description of Folder
 *
 * @author nihki <nihki@madaniyah.com>
 */

class App_Model_Show_Folder extends App_Model_Db_DefaultAdapter
{
    /**
     * class instance object
     */
    private static $_instance;

    /**
     * de-activate constructor
     */
    final private function  __construct()
    {
    }

     /**
      * de-activate object cloning
      */
    final private function  __clone() {}

    /**
     * @return obj
     */
    public function show()
    {
        if (!isset(self::$_instance)) {
                $show = __CLASS__;
                self::$_instance = new $show;
        }
        return self::$_instance;
    }

    /**
     * @return obj
     */
    public function getMenu($node='root')
    {
        $db = parent::_dbSelect();
        $parentGuid = $node;
        if($parentGuid == 'root')
    	{
            $select = $db->from('KutuFolder')
                    ->where("parentGuid=guid")
                    ->where("cmsParams like '%".'"menu":true'."%'")
                    ->order('viewOrder ASC');

    	}
    	else
    	{
            $select = $db->from('KutuFolder')
                    ->where("parentGuid = '$parentGuid' AND NOT parentGuid=guid")
                    ->where("cmsParams like '%".'"menu":true'."%'")
                    ->order('viewOrder ASC');

            
//            $select = $select->__toString();
//            print_r($select);exit();

    	}

        $rows = parent::_getDefaultAdapter()->fetchAll($select);
        return $rows;
    }
    public function getIdByTitle($st)
    {
        $db = parent::_dbSelect();
        $row = parent::_getDefaultAdapter()->fetchRow($db->from('KutuFolder')->where("cmsParams like '%".'"menu":true,"st":"'.$st.'"'."%'"));

        return $row;
    }

}
