<?php

/**
 * Description of Formater
 *
 * @author nihki <nihki@madaniyah.com>
 */
class Pandamp_Lib_Formater
{
    static function string_limit_words($string, $word_count=100)
    {
        $trimmed = "";
        $string = preg_replace("/\040+/"," ", trim($string));
        $stringc = explode(" ",$string);
        if($word_count >= sizeof($stringc))
        {
            // nothing to do, our string is smaller than the limit.
          return $string;
        }
        elseif($word_count < sizeof($stringc))
        {
            // trim the string to the word count
            for($i=0;$i<$word_count;$i++)
            {
                $trimmed .= $stringc[$i]." ";
            }

            if(substr($trimmed, strlen(trim($trimmed))-1, 1) == '.')
              return trim($trimmed).'..';
            else
              return trim($trimmed).'...';
        }
    }
    // see if url exists (for picture on remote host as well)
    function url_exists($url) {
        $a_url = parse_url($url);
        if (!isset($a_url['port'])) $a_url['port'] = 80;
        $errno = 0;
        $errstr = '';
        $timeout = 5;
        if(isset($a_url['host']) && $a_url['host']!=gethostbyname($a_url['host'])){
            $fid = @fsockopen($a_url['host'], $a_url['port'], $errno, $errstr, $timeout);
            if (!$fid) return false;
            $page = isset($a_url['path'])  ?$a_url['path']:'';
            $page .= isset($a_url['query'])?'?'.$a_url['query']:'';
            fputs($fid, 'HEAD '.$page.' HTTP/1.0'."\r\n".'Host: '.$a_url['host']."\r\n\r\n");
            $head = fread($fid, 4096);
            fclose($fid);
            return preg_match('#^HTTP/.*\s+[200|302]+\s#i', $head);
        } else {
            return false;
        }
    }
    static function thumb_exists($thumbnail)
    {
        $pos = strpos($thumbnail,"://");
        if ($pos === false) {
                return file_exists($thumbnail);
        }
        else
        {
            return Pandamp_Lib_Formater::url_exists($thumbnail);
        }
    }
    static function getRealIpAddr()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
        {
          $ip=$_SERVER['HTTP_CLIENT_IP'];
        }
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
        {
          $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else
        {
          $ip=$_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
    static function get_date($tanggal) {
        $id = $tanggal;
        $id = substr($id,8,2).".".substr($id,5,2).".".substr($id,2,2)." ".substr($id,11,2).":".substr($id,14,2);
        return $id;
    }
    static function _cleanMsWordHtml($textHtml)
    {
        $sTmp = $textHtml;
        /// <summary>
        /// Removes all FONT and SPAN tags, and all Class and Style attributes.
        /// Designed to get rid of non-standard Microsoft Word HTML tags.
        /// </summary>
        // start by completely removing all unwanted tags

        $sTmp = preg_replace("/<(\/)?(font|span|del|ins)[^>]*>/","",$sTmp);

        // then run another pass over the html (twice), removing unwanted attributes

        $sTmp = preg_replace("/<([^>]*)(class|lang|style|size|face)=(\"[^\"]*\"|'[^']*'|[^>]+)([^>]*)>/","<\\1>",$sTmp);
        $sTmp = preg_replace("/<([^>]*)(class|lang|style|size|face)=(\"[^\"]*\"|'[^']*'|[^>]+)([^>]*)>/","<\\1>",$sTmp);

        $sTmp = str_replace("<P ><SPAN ><o:p><FONT >&nbsp;</FONT></o:p></SPAN></P>", '', $sTmp);
        $sTmp = str_replace("<P ><SPAN ><FONT ><FONT ><SPAN >&nbsp;</SPAN><o:p></o:p></FONT></FONT></SPAN></P>", '', $sTmp);
        $sTmp = str_replace("<P ><SPAN ><o:p><FONT >&nbsp;</FONT></o:p></SPAN><SPAN ><o:p><FONT >&nbsp;</FONT></o:p></SPAN></P>", '', $sTmp);

        $anehaneh = array("<P ><SPAN ><o:p><FONT >&nbsp;</FONT></o:p></SPAN><SPAN ><o:p><FONT >&nbsp;</FONT></o:p></SPAN></P>",
                                                "<P ><SPAN ><SPAN >&nbsp;</SPAN><o:p></o:p></SPAN></P>",
                                                "<P ><B><SPAN ><o:p>&nbsp;</o:p></SPAN></B></P>",
                                                "<P ><B><SPAN ><o:p>&nbsp;</o:p></SPAN></B><SPAN ><o:p>&nbsp;</o:p></SPAN></P>",
                                                "<P ><SPAN ><o:p>&nbsp;</o:p></SPAN></P>",
                                                "<b><span><o:p>&nbsp;</o:p></span></b>",
                                                "<p>&nbsp;</p>",
                                                "<p><b><span><o:p>&nbsp;</o:p></span></b></p>",
                                                "<P ><B ><SPAN ><o:p>&nbsp;</o:p></SPAN></B></P>",
                                                "<?xml:namespace prefix = o ns = ".'"urn:schemas-microsoft-com:office:office"'." /><o:p></o:p>",
                                                "<?xml:namespace prefix = st1 ns = ".'"urn:schemas-microsoft-com:office:smarttags"'." />",
                                                "<P ><SPAN ></SPAN>&nbsp;</P>");
        $sTmp = str_replace($anehaneh, '', $sTmp);

        return $sTmp;
    }
    static function findCatalog($query)
    {
        $indexingEngine = Pandamp_Search::manager();
        $hits = $indexingEngine->find($query,0,1);
        $num = $hits->response->numFound;
        return $num;
    }
}
