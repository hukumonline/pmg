<?php

/**
 * Description of Vote
 *
 * @author nihki <nihki@madaniyah.com>
 */
class App_Model_Show_Vote extends App_Model_Db_DefaultAdapter
{
    /**
     * class instance object
     */
    private static $_instance;

    /**
     * de-activate constructor
     */
    final private function  __construct() {}

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
    public function getRating($id, $ip)
    {
        $db = parent::_dbSelect();
        $select = $db->from('vote')
                    ->where('guid=?',$id)
                    ->where('ip=?',$ip);

        $row = parent::_getDefaultAdapter()->fetchRow($select);

        return $row;
    }
}
