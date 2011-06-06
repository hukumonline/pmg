<?php

/**
 * Description of StarrateController
 *
 * @author nihki <nihki@madaniyah.com>
 */
class Api_StarrateController extends Zend_Controller_Action
{
    function getratingAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

        $catalogGuid = ($this->_getParam('guid'))? $this->_getParam('guid') : '';
        $ip = Pandamp_Lib_Formater::getRealIpAddr();
        $row = App_Model_Show_Vote::show()->getRating($catalogGuid,$ip);
        $val = ($row)? $row['value'] : 0;
        $counter = ($row)? $row['counter'] : 0;
        $ipFromDb = ($row)? $row['ip'] : '';
        $guid = ($row)? $row['id'] : '';
        $rating = (@round($val / $counter,1)) * 20;

        $aResponse = array();

        if ($row) {
            $aResponse['error'] = -1;
            $aResponse['data'] = $rating;
        }
        else
        {
            $aResponse['data'] = $rating;
        }

        echo Zend_Json::encode($aResponse);
    }
    function rateAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

        $text = ($this->_getParam('rating'))? $this->_getParam('rating') : '';
        $catalogGuid = ($this->_getParam('guid'))? $this->_getParam('guid') : '';

        $aResponse = array();

        $auth = Zend_Auth::getInstance();

        //$sso = new Pandamp_Session_Remote();
        //$user = $sso->getInfo();

        if (!$auth->hasIdentity()) {
            $aResponse['error'] = -1;
            $aResponse['message'] = "&nbsp;<font color=red>Sign in to vote!</font>";
        }
        else
        {
            $userId = $auth->getIdentity()->guid;
            $ip = Pandamp_Lib_Formater::getRealIpAddr();
            $modelVote = new App_Model_Db_Table_Vote();
            $modelVote->addRating($catalogGuid, array(
                'guid'		=> $catalogGuid,
                'userid'	=> $userId,
                'ip'		=> $ip,
                'counter'	=> 1,
                'value'		=> $text
            ));

            // update vote
            $aResponse['message'] = $this->uvote($catalogGuid);
        }

        echo Zend_Json::encode($aResponse);
    }
    private function uvote($catalogGuid)
    {
        // get current ip address
        $ip = Pandamp_Lib_Formater::getRealIpAddr();
        // get votes
        $rowRate = App_Model_Show_Vote::show()->getRating($catalogGuid,$ip);
        $val = ($rowRate)? $rowRate['value'] : 0;
        $counter = ($rowRate)? $rowRate['counter'] : 0;

        if ($counter < 1) {
            $count = 0;
        } else {
            $count=$counter; //how many votes total
        }
        $current_rating = $val;
        $tense=($count==1) ? "vote" : "votes"; //plural form votes/vote
        $rating = @number_format($current_rating/$count,1);

        $drawrating = '('.$count.' '.$tense.', average: '. $rating .' out of 5)';

        return $drawrating;
    }

}
