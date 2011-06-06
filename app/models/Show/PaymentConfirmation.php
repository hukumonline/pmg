<?php

/**
 * Description of PaymentConfirmation
 *
 * @author nihki <nihki@madaniyah.com>
 */
class App_Model_Show_PaymentConfirmation extends App_Model_Db_DefaultAdapter
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
    function unconfirmListCount($where)
    {
        $db = parent::_getDefaultAdapter();
        $query = $db->query("Select count(paymentId) AS count
                                FROM
                                    KutuPaymentConfirmation AS KPC,
                                    KutuOrder AS KO,
                                    KutuUser AS KU
                                WHERE
                                    KO.orderId = KPC.orderId
                                AND
                                    KU.guid = KO.userId
                                AND
                                    confirmed = 0
                                $where");

        $result = $query->fetchAll(Zend_Db::FETCH_OBJ);

        return $result[0]->count;
    }
    public function unconfirmList($where, $limit, $offset)
    {
        $db = parent::_getDefaultAdapter();
        $query = $db->query("SELECT
                                KPC.*,KO.*, KU.*, KOS.ordersStatus
                            FROM
                                KutuPaymentConfirmation AS KPC,
                                KutuOrder AS KO,
                                KutuUser AS KU,
                                KutuOrderStatus AS KOS
                            WHERE
                                KO.orderId = KPC.orderId
                            AND
                                KU.guid = KO.userId
                            AND
                                confirmed = 0
                            AND
                                KO.orderStatus = KOS.orderStatusId
                            $where
                                LIMIT $offset, $limit");

        $result = $query->fetchAll(Zend_Db::FETCH_ASSOC);

        return $result;
    }
}
?>
