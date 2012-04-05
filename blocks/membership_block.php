<?php
//  Author: SMD & Trabis
//  URL: http://www.xoopsmalaysia.org  & http://www.xuups.com
//  E-Mail: webmaster@xoopsmalaysia.org  & lusopoemas@gmail.com

if( ! defined( 'ICMS_ROOT_PATH' ) ) exit ;

function show_membership_block($options) {
    global $icmsConfig, $_SERVER;
    $online_handler = icms::handler('icms_core_online');
	mt_srand((double)microtime()*1000000);
	// set gc probabillity to 10% for now..
	if (mt_rand(1, 100) < 70) {
		$online_handler->gc(300);
	}
    if (is_object(icms::$user)) {
		$uid = icms::$user->getVar('uid');
		$uname = icms::$user->getVar('uname');
    } else {
		$uid = 0;
		$uname = '';
    }

    if (is_object(icms::$module)) {
		$online_handler->write($uid, $uname, time(), icms::$module->getVar('mid'), $_SERVER['REMOTE_ADDR']);
	} else {
		$online_handler->write($uid, $uname, time(), 0, $_SERVER['REMOTE_ADDR']);
	}


    // status online
    $onlines =& $online_handler->getAll();
	if (false != $onlines) {
        $module_handler = icms::handler('icms_module');
        $modules =& $module_handler->getList(new icms_db_criteria_Item('isactive', 1));
		$total = count($onlines);
		$block = array();
		$guests = 0;
		$guess = '';
		$members = '';
		$rows = $list = $alt = array();
		//added bots by Rew-weR
		$bots = 0;
		$findbot  = 'crawl';
		$findsearch = 'search';

        // lets get the listing
		if ($options[0] == 1 ) {
            // lets save some queries here
            $sql = "SELECT * FROM ".icms::$xoopsDB->prefix("mship_ips")." WHERE ";
            for ($i = 0; $i < $total; $i++) {
                $ip = $onlines[$i]['online_ip'];
                $numbers=explode (".",$ip);
                $code=($numbers[0] * 16777216) + ($numbers[1] * 65536) + ($numbers[2] * 256) + ($numbers[3]);
                if ($i > 0) $sql.= "OR ";
                $sql.= "(ipstart <= '".$code."' AND ipend >= '".$code."') ";
            }
            if (!$result = icms::$xoopsDB->query($sql)) die(icms::$xoopsDB->error());
            while ($row = icms::$xoopsDB->fetchArray($result)){
                $rows[] = $row;
            }

            for ($i = 0; $i < $total; $i++) {
                $onlineUsers[$i]['ip'] = $onlines[$i]['online_ip'];
                $onlineUsers[$i]['module'] = ($onlines[$i]['online_module'] > 0) ? $modules[$onlines[$i]['online_module']] : _MB_MSHIP_HOME;
                $ip = $onlines[$i]['online_ip'];
                $numbers=explode (".",$ip);
                $code=($numbers[0] * 16777216) + ($numbers[1] * 65536) + ($numbers[2] * 256) + ($numbers[3]);
                $country = "A2";
                $alt = '';
                foreach($rows as $row) {
                    if($code >= $row['ipstart'] && $code <= $row['ipend']) {
                        $country = $row['ccode'];
                        $alt = $row['cname'];
                    }
                }
                $ipadd= explode(".",$onlines[$i]['online_ip']);
                $add1 = ($numbers[0]);
                $add2 = ($numbers[1]);
                $add3 = ($numbers[2]);
                $add4 = ($numbers[3]);
                $censored = "$add1.$add2.$add3.***";

                //if is a user
                if ($onlines[$i]['online_uid'] > 0) {
                    $members .= '<table class="outer" cellspacing="0"><tr><td class="even" width="40%"><a href="'.ICMS_URL.'/userinfo.php?uid='.$onlines[$i]['online_uid'].'">'.$onlines[$i]['online_uname'].'</td><td class="odd" align="center">'.$onlineUsers[$i]['module'].'</td><td class="even" align="center" width="10%"><img src="'.ICMS_URL.'/modules/membership/images/flags/'.$country.'.gif" alt="'.$alt.'" title="'.$alt.'"></td></tr></table>';
                //if is not a user
                } else {
                    $hostname = strtolower(gethostbyaddr($onlines[$i]['online_ip']));
                    $pos1 = strpos($hostname, $findbot);
                    $pos2 = strpos($hostname, $findsearch);

                    //if is bot
                    if ($pos1 !== false || $pos2 !== false) {
                        $alt = $hostname;
                        $guess .= '<table class="outer" cellspacing="0"><tr><td class="even" colspan="2">'.$censored.'</td><td class="odd" align="center">'.$onlineUsers[$i]['module'].'</td><td width="10%" align="center" class="odd"><img src="'.ICMS_URL.'/modules/membership/images/bots.gif" alt="'.$alt.'" title="'.$alt.'"></td></tr></table>';
                        $bots++;
                    } else {
                        $guess .= '<table class="outer" cellspacing="0"><tr><td class="even" colspan="2">'.$censored.'</td><td class="odd" align="center">'.$onlineUsers[$i]['module'].'</td><td width="10%" align="center" class="odd"><img src="'.ICMS_URL.'/modules/membership/images/flags/'.$country.'.gif" alt="'.$alt.'" title="'.$alt.'"></td></tr></table>';
                    }
                    $guests++;
                }
            }
        } else {
            //no listing, lets get gots anyway
            for ($i = 0; $i < $total; $i++) {
                //if is not a user
                //added bots by Rew-weR
                if (!$onlines[$i]['online_uid'] > 0) {
                    $hostname = strtolower(gethostbyaddr($onlines[$i]['online_ip']));
                    $pos1 = strpos($hostname, $findbot);
                    $pos2 = strpos($hostname, $findsearch);

                    //if is bot
                    if ($pos1 !== false || $pos2 !== false) {
                         $bots++;
                    }
                    $guests++;
                }

            }
        }
        $block['online_total'] = sprintf(_ONLINEPHRASE, $total);
        if (is_object(icms::$module)) {
        	$criteria = new icms_db_criteria_Compo();
			$criteria->add(new icms_db_criteria_Item("online_module", icms::$module->getVar("mid")));
            $mytotal = $online_handler->getCount(new icms_db_criteria_Compo(new icms_db_criteria_Item('online_module', icms::$module->getVar('mid'))));
            $block['online_total'] .= ' ('.sprintf(_ONLINEPHRASEX, $mytotal, icms::$module->getVar('name')).')';
        }
        // statistik keahlian
        $member_handler = icms::handler('icms_member');
        $hari_ini = formatTimestamp(time());
        $level_criteria = new icms_db_criteria_Item('level', 0, '>');
        $criteria = new icms_db_criteria_Compo($level_criteria);
        $criteria24 = new icms_db_criteria_Compo($level_criteria);
        $criteria48 = new icms_db_criteria_Compo($level_criteria);
        $total_active_users = $member_handler->getUserCount($level_criteria);
        //Fixing stats for last 24 and 48 hours
        $users_reg_24 = $member_handler->getUserCount($criteria24->add(new icms_db_criteria_Item('user_regdate', (mktime(0,0,0)-(24*3600)), '>=')),'AND');
        $users_reg_48 = $member_handler->getUserCount($criteria48->add(new icms_db_criteria_Item('user_regdate', (mktime(0,0,0)-(48*3600)), '>=')),'AND');
        $limit = 1;
        $criteria->setOrder('DESC');
        $criteria->setSort('user_regdate');
        $criteria->setLimit($limit);
        $lastmembers = $member_handler->getUsers($criteria);
        $lastname = $lastmembers[0]->getVar('uname');
        $lastid = $lastmembers[0]->getVar('uid');
        // penerimaan data
        $block['activeusers'] = $total_active_users;
        $block['todayreg'] = $users_reg_24;
        $block['yesterdayreg'] = $users_reg_48 - $users_reg_24;
        $block['online_names'] = $members;
        $block['online_guest'] = $guess;
        $block['online_members'] = $total - $guests;
        //added bots by Rew-weR
        $block['online_guests'] = $guests - $bots;
		$block['online_bots'] = $bots;

        $block['total_online'] = $total;
        $block['latest'] = $lastname;
        $block['latest_id'] = $lastid;
        // definisi bahasa
        $block['membership_lang'] = _MB_MSHIP_MEMBERSHIP;
        $block['today_lang'] = _MB_MSHIP_TODAY;
        $block['yesterday_lang'] = _MB_MSHIP_YESTERDAY;
        $block['overall_lang'] = _MB_MSHIP_OVERALL;
        $block['online_lang'] = _MB_MSHIP_ONLINE;
        $block['guests_lang'] = _MB_MSHIP_GUESTS;
        $block['members_lang'] = _MB_MSHIP_MEMBERS;
        $block['total_lang'] = _MB_MSHIP_TOTAL;
        $block['list_lang'] = _MB_MSHIP_LIST;
        $block['popup_lang'] = _MB_MSHIP_POPUP;
        $block['latest_lang'] = _MB_MSHIP_LATEST;
        //added bots by Rew-weR
        $block['bots_lang'] = _MB_MSHIP_BOTS;
        return $block;
	} else {
		return false;
	}
}

function membership_edit($options) {
	$form = _MB_MSHIP_SHOWLIST."&nbsp;";
	if ( $options[0] == 1 ) {
		$chk = " checked='checked'";
	}
	$form .= "<input type='radio' name='options[]' value='1'".$chk." />&nbsp;"._YES."";
	$chk = "";
	if ( $options[0] == 0 ) {
		$chk = " checked='checked'";
	}
	$form .= "&nbsp;<input type='radio' name='options[]' value='0'".$chk." />"._NO."<br />";
	return $form;
}