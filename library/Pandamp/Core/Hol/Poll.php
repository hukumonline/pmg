<?php

/**
 * Description of Poll
 *
 * @author nihki <nihki@madaniyah.com>
 */
class Pandamp_Core_Hol_Poll
{
    function poll($aData)
    {
        $voteid = (isset($aData['poll']))? $aData['poll'] : '';
        $id = ($aData['id'])? $aData['id'] : '';

        $tblPolliP = new App_Model_Db_Table_PollIp();
        $ip_result = $tblPolliP->fetchRow("ip='".Pandamp_Lib_Formater::getRealIpAddr()."' AND pollGuid='".$id."'");

        if (!isset($ip_result))
        {
            $rowIp = $tblPolliP->fetchNew();
            $rowIp->dateOfPoll 	= date("Y-m-d H:i:s");
            $rowIp->ip		= Pandamp_Lib_Formater::getRealIpAddr();
            $rowIp->voteId	= $voteid;
            $rowIp->pollGuid	= $id;
            $rowIp->save();

            if ($voteid)
            {
                $tblPoll = new App_Model_Db_Table_Poll();
                $rowPoll = $tblPoll->find($id)->current();

                if ($rowPoll)
                {
                    $rowPoll->voters = $rowPoll->voters + 1;
                    $rowPoll->save();
                }

                $tblOption = new App_Model_Db_Table_PollOption();
                $rowOption = $tblOption->fetchRow("guid='$voteid' AND pollGuid='$id'");

                if ($rowOption)
                {
                    $rowOption->hits = $rowOption->hits + 1;
                    $rowOption->save();
                }
            }
        }
    }
    
}
