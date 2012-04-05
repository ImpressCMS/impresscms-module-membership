<?php
/**
 *
 * File: /icms_version.php
 * 
 * module informations and config
 * 
 * @copyright	Copyright QM-B (Steffen Flohrer) 2012
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * ----------------------------------------------------------------------------------------------------------
 * 				Membership
 * @since		2.40
 * @author		QM-B <qm-b@hotmail.de>
 * @version		$Id$
 * @package		membership
 *
 */

defined("ICMS_ROOT_PATH") or die("ICMS root path not defined");

/**  General Information  */
$modversion = array(
						"name"						=> _MI_MSHIP_NAME,
						"version"					=> 2.4,
						"description"				=> _MI_MSHIP_DSC,
						"author"					=> "SMD <webmaster@xoopsmalaysia.org> http://www.xoopsmalaysia.org & Trabis<lusopoemas@gmail.com> http://www.xuups.com",
						"credits"					=> "SMD <webmaster@xoopsmalaysia.org> http://www.xoopsmalaysia.org & Trabis<lusopoemas@gmail.com> http://www.xuups.com",
						"help"						=> "",
						"license"					=> "GNU General Public License (GPL)",
						"official"					=> 0,
						"dirname"					=> basename(dirname(__FILE__)),
						"modname"					=> "membership",
					
					/**  Images information  */
						"iconsmall"					=> "images/membership_logo.png",
						"iconbig"					=> "images/membership_logo.gif",
						"image"						=> "images/membership_logo.gif", /* for backward compatibility */
					
					/**  Development information */
						"status_version"			=> "2.4",
						"status"					=> "RC",
						"date"						=> "",
						"author_word"				=> "",
						"warning"					=> _CO_ICMS_WARNING_RC,
					
					/** Administrative information */
						"hasAdmin"					=> 1,
						"adminindex"				=> "admin/index.php",
						"adminmenu"					=> "admin/menu.php",
					
					/** Install and update informations */
						//"onInstall"					=> "include/onupdate.inc.php",
						//"onUpdate"					=> "include/onupdate.inc.php",
					
					/** Search information */
						"hasSearch"					=> 0,
					
					/** Menu information */
						"hasMain"					=> 1,
				);

/** other possible types: testers, translators, documenters and other */
$modversion['people']['developers'][] = "[url=http://community.impresscms.org/userinfo.php?uid=318]Trabis[/url]";
$modversion['people']['developers'][] = "[url=http://community.impresscms.org/userinfo.php?uid=1314]QM-B[/url]";

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////// SUPPORT //////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////

$modversion['support_site_url'] = 'http://community.impresscms.org/modules/newbb/viewforum.php?forum=9';
$modversion['support_site_name']= 'ImpressCMS Community Forum';

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////// DATABASE /////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////

// Tables
$modversion['sqlfile']['mysql'] = "sql/mysql.sql";
$i=0;
$modversion['tables'][$i] = "mship_ips";

//Templates
$i=0;
$i++;
$modversion['templates'][$i]['file'] = "membership_index.html";
$modversion['templates'][$i]['description'] = '';
$i++;
$modversion['templates'][$i]['file'] = "membership_rank.html";
$modversion['templates'][$i]['description'] = '';

// Blocks
$modversion['blocks'][1]['file'] 		= "membership_block.php";
$modversion['blocks'][1]['show_func'] 	= "show_membership_block";
$modversion['blocks'][1]['name'] 		= _MI_MSHIP_BLOCK_TITLE;
$modversion['blocks'][1]['description'] = _MI_MSHIP_BLOCK_DSC;
$modversion['blocks'][1]['edit_func'] = "membership_edit";
$modversion['blocks'][1]['options'] = "1";
$modversion['blocks'][1]['template'] = 'membership_block.html';


//Configs
$i = 0;
$i++;
$modversion['config'][$i]['name'] = 'membersperpage';
$modversion['config'][$i]['title'] = '_MI_MSHIP_MPAGE';
$modversion['config'][$i]['description'] = '_MI_MSHIP_MPAGE_DSC';
$modversion['config'][$i]['formtype'] = 'textbox';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 10;

$i++;
$modversion['config'][$i]['name'] = 'defaultavatar';
$modversion['config'][$i]['title'] = '_MI_MSHIP_DAVATAR';
$modversion['config'][$i]['description'] = '_MI_MSHIP_DAVATAR_DSC';
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 0;

//Menu
$i=0;
$i++;
$modversion['sub'][$i]['name'] = _MI_MSHIP_SMLIST;
$modversion['sub'][$i]['url'] = "index.php";
$i++;
$modversion['sub'][$i]['name'] = _MI_MSHIP_SMRANK;
$modversion['sub'][$i]['url'] = "rank.php";
