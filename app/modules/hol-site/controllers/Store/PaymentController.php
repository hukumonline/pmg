<?php

/**
 * Description of PaymentController
 *
 * @author nihki <nihki@madaniyah.com>
 */
class HolSite_Store_PaymentController extends Zend_Controller_Action
{
    protected $_user;
    protected $_userFinanceInfo;
    protected $_paymentVars;
    protected $_testMode;
    protected $_orderIdNumber;
    protected $_defaultCurrency;
    protected $_currencyValue;
    protected $_holMail;

    function  preDispatch()
    {
        $this->_helper->layout->setLayout('layout-storepayment');
        
        $this->_testMode=true;
		$this->_defaultCurrency='USD';
		$tblPaymentSetting = new App_Model_Db_Table_PaymentSetting();
		$usdIdrEx = $tblPaymentSetting->fetchAll($tblPaymentSetting->select()->where(" settingKey= 'USDIDR'"));
		$this->_currencyValue = $usdIdrEx[0]->settingValue;
		
		Zend_Session::start();
		
        $tblPaymentSetting = new App_Model_Db_Table_PaymentSetting();        
        $rowSet = $tblPaymentSetting->fetchAll();
        
        for($iRow=0; $iRow<count($rowSet);$iRow++){
            $key=$rowSet[$iRow]->settingKey;
            $this->_paymentVars[$key]=$rowSet[$iRow]->settingValue;
        }
		
        $tblSetting = new App_Model_Db_Table_PaymentSetting();
        $this->_holMail = $tblSetting->fetchAll($tblSetting->select()->where("settingKey = 'paypalBusiness'"));
    }
	function indexAction()
	{
		//[TODO] must check if orderId has been paid before to avoid double charge, if somehow user can access directly to payment controller.
		
		$this->_helper->viewRenderer->setNoRender(TRUE);	
		$this->_checkAuth();
		
		$orderId = $this->_request->getParam('orderId');
		$this->_orderIdNumber = $orderId;
		
		if(empty($orderId))
		{
			echo "kosong";
			die();
		}
		
		$modelAppStore = new App_Model_Store();
		if(!$modelAppStore->isUserOwnOrder($this->_user->kopel, $orderId))
		{
			//forward to error page
			$this->_helper->redirector->gotoSimple('error', 'store', 'hol-site',array('view'=>'notowner'));
			die();
		}
		if($modelAppStore->isOrderPaid($orderId))
		{
			//forward to error page
			$this->_helper->redirector->gotoSimple('error', 'store', 'hol-site',array('view'=>'orderalreadypaid'));
			die();
		}
		
		
		$items = App_Model_Show_Order::show()->getOrderDetail($orderId);
		
		$tmpMethod = $this->_request->getParam('method');
		if(!empty($tmpMethod))
		{
			$items[0]['paymentMethod'] = $tmpMethod;
		}
		
		switch($items[0]['paymentMethod'])
		{ 
			case 'nsiapay' :
				
                require_once('PaymentGateway/Nsiapay.php');  // include the class file
                $paymentObject = new Nsiapay;             // initiate an instance of the class
                
                if($this->_testMode){
                	$paymentObject->enableTestMode();
                }
                
                $paymentObject->addField('TYPE',"IMMEDIATE");
                
                $subTotal=0;
                
                for($iCart=0;$iCart<count($items);$iCart++)
                {
                	$i=$iCart+1;
                	$basket[] = $items[$iCart]['documentName'].",".$items[$iCart]['price'].".00".",".$items[$iCart]['qty'].",".$items[$iCart]['finalPrice'].".00";
                	$subTotal += $items[$iCart]['price'] * $items[$iCart]['qty'];              
                }
                
                $ca = implode(";", $basket);
                
                $merchantId = "000100090000028";
                
                $paymentObject->addField("BASKET",$ca);
                $paymentObject->addField("MERCHANTID",$merchantId);
                $paymentObject->addField("CHAINNUM","NA");
                $paymentObject->addField("TRANSIDMERCHANT",$items[0]['invoiceNumber']);
                $paymentObject->addField("AMOUNT",$subTotal);
                $paymentObject->addField("CURRENCY","360");
                $paymentObject->addField("PurchaseCurrency","360");
                $paymentObject->addField("acquirerBIN","360");
                $paymentObject->addField("password","123456");
                $paymentObject->addField("URL",ROOT_URL);
                $paymentObject->addField("MALLID","199");
                $paymentObject->addField("SESSIONID",Zend_Session::getId());
                $sha1 = sha1($subTotal.".00".$merchantId."08iIWbWvO16w".$items[0]['invoiceNumber']);
//                echo $subTotal.".00".$merchantId."08iIWbWvO16w".$items[0]['invoiceNumber']."<br>";
//                echo $sha1;die;
                $paymentObject->addField("WORDS",$sha1);
                
                $ivnum = $this->updateInvoiceMethod($orderId, 'nsiapay', 1, 0, 'paid with nsiapay method');
                
                $data['orderId'] = $orderId;
                $data['starttime'] = date('YmdHis');
                $data['amount'] = $subTotal;
                $data['transidmerchant'] = $items[0]['invoiceNumber'];
                $tblNsiapay = new App_Model_Db_Table_Nsiapay();
                $tblNsiapay->insert($data);
                
                $nhis['orderId'] = $items[0]['invoiceNumber'];
                $nhis['paymentStatus'] = 'requested';
                $nhis['dateAdded'] = date('YmdHis');
                $tblNhis = new App_Model_Db_Table_NsiapayHistory();
                $tblNhis->insert($nhis);
                
                //$paymentObject->dumpFields();die();
                $this->_helper->layout->disableLayout();
                
                $paymentObject->submitPayment();
                
				break;
				
            case 'paypal':
				/*
                 - Detect Multi Item and set accordingly
                 - Logic for test mode 
                */
                require_once('PaymentGateway/Paypal.php');  // include the class file
                $paymentObject = new Paypal;             // initiate an instance of the class
                
                if($this->_testMode){                    
                    $paymentObject->addField('business', $this->_paymentVars['paypalTestBusiness']);
                    $paymentObject->addField('return', $this->_paymentVars['paypalTestSuccessUrl']);
                    $paymentObject->addField('cancel_return', $this->_paymentVars['paypalTestCancelUrl']);
                    $paymentObject->addField('notify_url', $this->_paymentVars['paypalTestNotifyUrl']);
                    $paymentObject->enableTestMode();
                }else{                
                    $paymentObject->addField('business', $this->_paymentVars['paypalBusiness']);
                    $paymentObject->addField('return', $this->_paymentVars['paypalSuccessUrl']);
                    $paymentObject->addField('cancel_return', $this->_paymentVars['paypalCancelUrl']);
                    $paymentObject->addField('notify_url', $this->_paymentVars['paypalNotifyUrl']);
                }
                
                for($iCart=0;$iCart<count($items);$iCart++){
                    $i=$iCart+1;
                    $paymentObject->addField("item_number_".$i, $items[$iCart]['itemId']); 
                    $paymentObject->addField("item_name_".$i, $items[$iCart]['documentName']); //nama barang [documentName]
                    $paymentObject->addField("amount_".$i, $items[$iCart]['price']); //harga satuan [price]
                    $paymentObject->addField("quantity_".$i, $items[$iCart]['qty']); //jumlah barang [qty]\
                }
                $paymentObject->addField('tax_cart',$items[0]['orderTax']);
                $paymentObject->addField('currency_code',$this->_defaultCurrency);

				//$paymentObject->addField('custom',$_SESSION['_orderIdNumber']);
                $paymentObject->addField('custom',$orderId);
                
				$ivnum = $this->updateInvoiceMethod($orderId, 'paypal', 1, 0, 'paid with paypal method');
				
				//$paymentObject->dumpFields();
				$this->_helper->layout->disableLayout();
                $paymentObject->submitPayment();
				
				//setting payment and status as pending (1), notify = 0, notes = 'paid with...'
				break;
				
			case 'manual':
			case 'bank':
                /*
                 1. update order status
                 2. redirect to instruction page 
                */

				//setting payment and status as pending (1), notify = 0, notes = 'paid with...'
				$this->updateInvoiceMethod($orderId, 'bank', 1, 0, 'paid with manual method');
				
				// HAP: i think we should send this notification when user were on page "Complete Order" and after confirmation made by user is approved;
				//$this->Mailer($orderId, 'admin-order', 'admin');
				//$this->Mailer($orderId, 'user-order', 'user');
				
				$this->_helper->redirector('instruction','store_payment','site',array('orderId'=>$orderId));
                break;

			case 'postpaid':
                /*
                 1. validate POSTPAID status of the client 
                 2. validate CREDIT LIMIT (per user) with current Outstanding Bill + New Bill
                 3. update order status
                 4. redirect to success or failed 
                */
				/*
                * if userid isn't listed as postpaid user will be redirected
                */
                if(!$this->_userFinanceInfo->isPostPaid){
                    echo 'Not Post Paid Customer';
                    //$paymentObject->submitPayment();
                    return $this->_helper->redirector('notpostpaid');
                }
                /*====================VALIDATE CREDIT LIMIT=====================*/
                /*
                * validate credit limit :
                * 1. count total transaction 
                * 2. counting total previous unpaid postpaid transaction
                * 3. validate
                */
                //$cart = $this->completeItem();

                /*-----count total amount of prevous unpaid transaction------*/
                //$tblOrder = new Pandamp_Modules_Payment_Order_Model_Order(); 
				//table kutuOrder
                //select previous transaction that are postpaid based on userid
				//echo ($tblOrder->outstandingUserAmout($this->_userInfo->userId));
                $outstandingAmount=App_Model_Show_Order::show()->outstandingUserAmout($this->_userFinanceInfo->userId);
				
                /*count total amount of prevous unpaid transaction------*/ 
                if($this->_userFinanceInfo->creditLimit == 0){
                            $limit = 'Unlimited';
                            $netLimit = 'Unlimited';
                    }else{
                            $limit = number_format($this->_userFinanceInfo->creditLimit,2);
                            $netLimit = $limit - $outstandingAmount;
                            $netLimit = number_format($netLimit,2);
                    }
                //$superTotal = $cart['grandTotal']+$outstandingAmount;
				$superTotal = $items[0]['orderTotal'] + $outstandingAmount;
				
                if(($this->_userFinanceInfo->creditLimit != 0) AND ($this->_userFinanceInfo->creditLimit <  $superTotal )){
                    echo $superTotal.$limit;

                    $this->_helper->redirector('postpaidlimit');
                    echo 'Credit Limit Reached, Please Contact Our Billing';

                /*====================VALIDATE CREDIT LIMIT=====================*/
                } else {

                    $this->view->type = "postpaid";
					$this->view->limit = $limit;
					$this->view->outstandingAmount = $outstandingAmount;
					$this->view->grandTotal = $items[0]['orderTotal'];
					$this->view->netLimit = $netLimit;
					$this->view->taxInfo = $items[0];
					$this->view->orderId = $orderId;

                }
                break;
		}
		
	}
	protected function updateInvoiceMethod($orderId, $payMethod, $status, $notify, $note){        
        $tblOrder = new App_Model_Db_Table_Order();
		
		$rows = $tblOrder->find($orderId)->current();
		$row = array();
		
		$ivnum = $rows->invoiceNumber;
		
		/*if(empty($ivnum)){
			if($status==3 || $status==5 || (!empty($_SESSION['_method'])&&($_SESSION['_method'] =='paypal')))
			$ivnum = $this->getInvoiceNumber();
			//$row=array ('invoiceNumber'	=> $ivnum);
		}*/
		//if( )$ivnum = $this->getInvoiceNumber();
		
		
		$row=array ('orderStatus'	=> $status, 'paymentMethod' => $payMethod);
		
		//$_SESSION['_method'] = '';
		/*$this->_paymentMethod=$payMethod;//set payment method on table
		$row->paymentMethod=$this->_paymentMethod;*/
		
		$tblOrder->update($row, 'orderId = '. $orderId);
		
		$tblHistory = new App_Model_Db_Table_OrderHistory();
		$rowHistory = $tblHistory->fetchNew();
		
		$rowHistory->orderId = $orderId;
		$rowHistory->orderStatusId = $status;
		$rowHistory->dateCreated = date('YmdHis');
		$rowHistory->userNotified = $notify;
		$rowHistory->note = $note;
		$rowHistory->save();
		
		return $ivnum;
	}
    public function listAction()
    {
        $this->_checkAuth();

        $where=$this->_user->kopel;

        $rowsetTotal = App_Model_Show_Order::show()->countOrders("'".$where."'");
        $rowset = App_Model_Show_Order::show()->getOrderSummary("'".$where."'");

        $this->view->numCount = $rowsetTotal;
        $this->view->listOrder = $rowset;
    }
    public function transactionAction()
    {
        $this->_checkAuth();

        $where=$this->_user->kopel;

        $rowsetTotal = App_Model_Show_Order::show()->countOrders("'".$where."' AND (orderStatus = 3 OR orderStatus = 5)");
        $rowset = App_Model_Show_Order::show()->getOrderSummary("'".$where."' AND (orderStatus = 3 OR orderStatus = 5)");

        $this->view->numCount = $rowsetTotal;
        $this->view->listOrder = $rowset;
    }
    public function confirmAction()
    {
        $this->_checkAuth();
        
        $userId = $this->_user->kopel;

        $rowset = App_Model_Show_Order::show()->getTransactionToConfirm($userId);
        $numCount = App_Model_Show_Order::show()->getTransactionToConfirmCount($userId);

        $modelPaymentSetting = new App_Model_Db_Table_PaymentSetting();
        $bankAccount = $modelPaymentSetting->fetchAll("settingKey = 'bankAccount'");

        if($this->_request->get('sended') == 1){
            $this->view->sended = 'Payment Confirmation Sent';
        }

        $this->view->numCount = $numCount;
        $this->view->rowset = $rowset;
        $this->view->bankAccount = $bankAccount;
    }
    public function payconfirmAction()
    {
        $this->_checkAuth();

        //if there is orderId send by previous page
        $tmpOrderId = $this->_request->getParam('orderId');

        if(empty($tmpOrderId))
        {
            $this->_helper->redirector->gotoSimple('error', 'store', 'hol-site',array('view'=>'noorderfound'));
            die();
        }

        //[TODO]
        // 1. must check if user who sent the confirmation is the user who own the orderId.
        // 2. if no.1 above return false for at least one orderId, then forward to Error Page.

        $modelAppStore = new App_Model_Store();
        foreach($this->_request->getParam('orderId') as $key=>$value)
        {
            if(!$modelAppStore->isUserOwnOrder($this->_user->kopel, $value))
            {
                //forward to error page
                $this->_helper->redirector->gotoSimple('error', 'store', 'site',array('view'=>'notowner'));
                die();
            }
        }

        $tblConfirm = new App_Model_Db_Table_PaymentConfirmation();
        $tblOrder = new App_Model_Db_Table_Order();
        $r = $this->getRequest();

        $amount = 0;

        foreach($r->getParam('orderId') as $ksy=>$value){
            $amount += App_Model_Show_Order::show()->getAmount($value);
        }
        foreach($r->getParam('orderId')as $key=>$row)
        {
            $data = $tblConfirm->fetchNew();

            $data['paymentMethod'] = $r->getParam('paymentMethod');
            $data['destinationAccount'] = $r->getParam('destinationAccount');
            $data['paymentDate'] = $r->getParam('paymentDate');
            $data['amount'] = $amount;
            $data['currency'] = $r->getParam('currency');
            $data['senderAccount'] = $r->getParam('senderAccount');
            $data['senderAccountName'] = $r->getParam('senderAccountName');
            $data['bankName'] = $r->getParam('bankName');
            $data['note'] = $r->getParam('note');
            $data['orderId'] = $row;
            $data->save();

            $statdata['orderStatus'] = 4;
            $tblOrder->update($statdata, 'orderId = '.$data['orderId']);

            $tblHistory = new App_Model_Db_Table_OrderHistory();

            //add history
            $dataHistory = $tblHistory->fetchNew();
            //history data
            $dataHistory['orderId'] = $data['orderId'];

            $dataHistory['orderStatusId'] = 6;
            $dataHistory['dateCreated'] = date('Y-m-d');
            $dataHistory['userNotified'] = 1;
            $dataHistory['note'] = 'Waiting Confirmation';
            $dataHistory->save();

            $mod = new App_Model_Store_Mailer();
            $mod->sendUserBankConfirmationToAdmin($data['orderId']);
        }
        
        $this->_helper->redirector->gotoSimple('confirm', 'store_payment', 'hol-site', array('sended' => '1'));
    }
    public function billingAction()
    {
        $this->_checkAuth();

        $rowset = App_Model_Show_UserFinance::show()->getUserFinance($this->_user->kopel);
        $this->view->rowset = $rowset;

        $outstandingAmount = App_Model_Show_Order::show()->outstandingUserAmout($this->_userFinanceInfo->userId);
        $this->view->outstandingAmount = $outstandingAmount;

        if($this->_request->isPost('save')){
            $data['taxNumber'] = $this->_request->getParam('taxNumber');
            $data['taxCompany'] = $this->_request->getParam('taxCompany');
            $data['taxAddress'] = $this->_request->getParam('taxAddress');
            $data['taxCity'] = $this->_request->getParam('taxCity');
            $data['taxProvince'] = $this->_request->getParam('province');
            $data['taxZip'] = $this->_request->getParam('taxZip');
            $data['taxPhone'] = $this->_request->getParam('taxPhone');
            $data['taxFax'] = $this->_request->getParam('taxFax');
            $data['taxCountryId'] = $this->_request->getParam('taxCountryId');
            $where = "userId = '".$this->_user->kopel."'";
            
            $userFinance = new App_Model_Db_Table_UserFinance();
            $userFinance->update($data,$where);
            
            $this->_helper->redirector('bilupdsuc');
        }
    }
    public function bilupdsucAction()
    {
        $this->_checkAuth();
        $this->_redirect(ROOT_URL.'/store/payment/billing');
    }
    public function documentAction()
    {
        $this->_checkAuth();

        $userId = $this->_userFinanceInfo->userId;

        $rowset = App_Model_Show_Order::show()->getDocumentSummary($userId);
        $rowsetTotal = App_Model_Show_Order::show()->countDocument($userId);

        $this->view->numCount = $rowsetTotal;
        $this->view->rowset = $rowset;
    }
    private function _checkAuth()
    {
        //$sso = new Pandamp_Session_Remote();
        //$sso->getInfo();

        $auth = Zend_Auth::getInstance();
        if (!$auth->hasIdentity())
        {
            //$this->_helper->redirector('login','account','identity',array('sReturn'=>$sReturn));
            
	        $sReturn = "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
	        $sReturn = base64_encode($sReturn);
	
			$identity = Pandamp_Application::getResource('identity');
			$loginUrl = $identity->loginUrl;

			$this->_redirect($loginUrl.'?returnTo='.$sReturn);     
            
        }
        else
        {
            $this->_user = $auth->getIdentity();
        }

        $modelUserFinance = new App_Model_Db_Table_UserFinance();
        $this->_userFinanceInfo = $modelUserFinance->find($this->_user->kopel)->current();
        if (empty($this->_userFinanceInfo))
        {
            $finance = $modelUserFinance->fetchNew();
            $finance['userId'] = $this->_user->kopel;
            $finance->save();
            $this->_userFinanceInfo = $modelUserFinance->find($this->_user->kopel)->current();
        }
    }
}
