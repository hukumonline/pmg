<?php

/**
 * Description of ManagerController
 *
 * @author nihki <nihki@madaniyah.com>
 */
class Polling_ManagerController extends Zend_Controller_Action
{
    function viewAction()
    {
		$tblPolling = new App_Model_Db_Table_Poll();
		$tblOption = new App_Model_Db_Table_PollOption();
		
		$time = time();
		$date = date("Y-m-d H:i:s", $time);
		
		$rowPoll = $tblPolling->fetchRow("checkedTime < '$date'","checkedTime DESC");
		$this->view->rowPoll = $rowPoll;
		
		$rowOpt = $tblOption->fetchAll("pollGuid='$rowPoll->guid'","text ASC");
		$this->view->rowOpt = $rowOpt;
    }
    function browseAction()
    {
        $this->_helper->layout->setLayout("layout-polling");

        $pollId = ($this->_getParam('guid'))? $this->_getParam('guid') : '';

        $this->view->pollId = $pollId;
    }
    function detailAction()
    {
        $this->_helper->layout->setLayout('layout-polling');

        $pollId = ($this->_getParam('guid'))? $this->_getParam('guid') : '';

        $tblPolling = new App_Model_Db_Table_Poll();

        $time = time();
        $date = date("Y-m-d H:i:s", $time);

        $rowPoll = $tblPolling->fetchRow("guid='$pollId' AND checkedTime < '$date'","checkedTime DESC");
        $this->view->rowPoll = $rowPoll;

        $this->view->pollId = $pollId;
    }
    function pollAction()
    {
        $this->_helper->layout->setLayout('layout-polling');

        $pollId = ($this->_getParam('guid'))? $this->_getParam('guid') : '';

        $tblPolling = new App_Model_Db_Table_Poll();

        $time = time();
        $date = date("Y-m-d H:i:s", $time);

        $rowPoll = $tblPolling->fetchAll("guid NOT IN('$pollId') AND checkedTime < '$date'","checkedTime DESC");
        $this->view->rowPoll = $rowPoll;

        $this->view->pollId = $pollId;
    }
}
?>
