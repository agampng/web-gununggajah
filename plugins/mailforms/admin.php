<?php

/*
============================================================
CMSimple Plugin MailForms 
Based on "Secure PHP Form Mailer Script" from dagondesign.com (2009)
============================================================
Version:    MailForms 1.6
Released:   03/2016
Copyright:  Gert Ebersbach - www.ge-webdesign.de 
============================================================ 
License:
- free for noncommercial websites
- fee required for commercial websites
============================================================ 
utf-8 check: äöü 
*/

if (!function_exists('sv') || preg_match('#plugins/mailforms/admin.php#i', $_SERVER['SCRIPT_NAME']))
{
	die('no direct access');
}

initvar('mailforms');
if ($mailforms) 
{
	if(!defined('CMSIMPLE_VERSION'))
	{
		$o.= '<p>This plugin requires <b>CMSimple 4.2</b> or higher.</p><p><a href="http://www.cmsimple.org/">CMSimple Download & Updates &raquo;</a></p>';
		return;
	}
	
	if(defined('XH_ADM'))
	{
		$o.= '<div style="font-size: 15px; font-family: arial, sans-serif; letter-spacing: 0;">
<h4><a href="?mailforms">Plugin Info &raquo;</a></h4>
<p style="line-height: 1.6em;"><b>MailForms</b>&nbsp;by&nbsp;<a href="http://www.ge-webdesign.de/cmsimpleplugins/"><u>ge-webdesign.de&nbsp;&raquo;</u></a> &nbsp; 
for&nbsp;<b>CMSimple&nbsp;4.2</b>&nbsp;or&nbsp;higher&nbsp;from&nbsp;<a href="http://www.cmsimple.org/"><u>cmsimple.org&nbsp;&raquo;</u></a></p>
</div>';
	}
	else
	{
		$o.= '<div style="font-size: 15px; font-family: arial, sans-serif; letter-spacing: 0; padding: 0 0 6px 0; margin-top: 6px;">
<b><a href="?mailforms">Plugin Info &raquo;</a></b>
</div>
';
	}
	
	global $plugin_cf,$plugin_tx;
	
	$plugin='mailforms';
	$o.=print_plugin_admin('on');
	if ($admin != 'plugin_main') 
	{
		$hint=array();
		$hint['mode_donotshowvarnames'] = false;
		$o.=plugin_admin_common($action, $admin, $plugin, $hint);
	}
	
	$o.='<div id="mailformsMain">';

	if ($admin == '') 
	{
		$o.= '<h4>MailForms Plugin</h4>
<p>Based on <a href="http://www.dagondesign.com/articles/secure-php-form-mailer-script/#advancedform" target="_blank">Secure PHP Form Mailer Script</a><br>
by <a href="http://www.dagondesign.com/" target="_blank">dagondesign.com</a> (2009)</p>
<div style="font-family: arial, sans-serif; font-size: 15px; letter-spacing: 0; border: 4px double; padding: 6px 16px; margin: 2px 0 8px 0;">
<p style="text-align: center;">This Plugin is made for <a href="http://www.cmsimple.org/"><span style="font-weight: 700; padding: 0 6px; ">CMSimple 4.2 &raquo;</span></a> or higher.</p>
<p style="text-align: center;">Optimized for <span style="font-weight: 700; padding: 0 6px; ">CMSimple 4.4</span> or higher.</p>
<p style="text-align: center;">Recommended is the current version of CMSimple from <a href="http://www.cmsimple.org/">cmsimple.org&nbsp;&raquo;</a></p>
</div>
';
		
		$o.= '
<p><b>Version: </b>1.6</p>
<p><b>Developed by: </b>Gert Ebersbach <a href="http://www.ge-webdesign.de/cmsimpleplugins/">ge-webdesign.de</a></p>
<p><b>CopyRight: </b>Gert Ebersbach <a href="http://www.ge-webdesign.de/cmsimpleplugins/">ge-webdesign.de</a></p>
<p><b>Released: </b>03/2016</p>
<hr />
<p><b>Licenses:</b></p>
<ul>
<li>Freeware License (private websites)</li>
<li>Commercial License (commercial websites - for costs)</li>
</ul>
<p><b>For License Prices see: </b><a href="http://www.ge-webdesign.de/cmsimpleplugins/">ge-webdesign.de</a><br />';

	}

/* 
#####################################
           begin backend
##################################### 
*/
	
	if ($admin == 'plugin_main') 
	{
		// activate backend template, if exists
		if(file_exists($pth['folder']['templates'].'__cmsimple_backend__/template.htm'))
		{
			$pth['folder']['template'] = $pth['folder']['templates'].'__cmsimple_backend__/';
			$pth['file']['template'] = $pth['folder']['template'].'template.htm';
			$pth['file']['stylesheet'] = $pth['folder']['template'].'stylesheet.css';
			$pth['folder']['menubuttons'] = $pth['folder']['template'].'menu/';
			$pth['folder']['templateimages'] = $pth['folder']['template'].'images/';
			$cf['meta']['robots'] = 'noindex, nofollow';
		}
		
		$o.='
<p><b>Mailforms - Backend</b></p>
';

		// write data file or show message
		if(@$_REQUEST['ddmf_write_data'] == 'done')
		{
			if(isset($_POST['ddmf_data_file']))
			{
				if(!is_writeable($plugin_cf['mailforms']['data_path'] . $_SESSION['ddmf_active_mailform']))
				{
					die('<p>The file</p><p style="font-family: courier new, monospace; font-weight: 700;">' . $plugin_cf['mailforms']['data_path'] . $_SESSION['ddmf_active_mailform'] . '</p><p>is not writeable, please check the writing permissions of the file.</p>');
				}
				else
				{
					$ddmfDataFile = fopen($plugin_cf['mailforms']['data_path'] . $_SESSION['ddmf_active_mailform'], 'w+');
					ftruncate($ddmfDataFile, 0);
					$o.= '<p class="cmsimplecore_warning" style="text-align: center; font-weight: 700; line-height: 2em;">' . $plugin_tx['mailforms']['admin_file_saved'] . '</p>';
				}
				fwrite($ddmfDataFile, trim(stripslashes($_POST['ddmf_data_file'])));
				fclose($ddmfDataFile);
			}
			$o.='<br><p style="text-align: center; font-weight: 700;"><a href="./?mailforms&admin=plugin_main&action=plugin_text">' . $plugin_tx['mailforms']['admin_back_to_mailform'] . '</a></p>';
		}
		
		
		
		// delete selected form
		if
		(
			$adm && 
			isset($_GET['ddfm_delform']) && 
			isset($_SESSION['ddmf_active_mailform']) && 
			$_SESSION['ddmf_active_mailform'] != '' && 
			!strpos(file_get_contents($pth['file']['content']), str_replace('.txt','','n:dd_mailform(\'' . $_SESSION['ddmf_active_mailform']))
		)
		{
			unlink($plugin_cf['mailforms']['data_path'] . $_SESSION['ddmf_active_mailform']);
			$o.= '<div class="cmsimplecore_warning" style="float: left;"><p style="padding: 3px 10px; margin: 0;">' . $plugin_tx['mailforms']['delete_the_form'] . ' <b>"' . str_replace('.txt','',$_SESSION['ddmf_active_mailform']) . '"</b> ' . $plugin_tx['mailforms']['delete_was_deleted'] . '.</p></div>
<div style="clear: both;"></div>
';
			unset($_SESSION['ddmf_active_mailform']);
		}
		
		
		
		// create new mailform file
		if
		(
			$adm && isset($_POST['ddmf_newform']) && $_POST['ddmf_newform'] != ''
		)
		{
			if(isset($_SESSION['ddmf_active_mailform']) && $_SESSION['ddmf_active_mailform'] != '' && $_SESSION['ddmf_active_mailform'] != $plugin_tx['mailforms']['admin_select_file'])
			{
				$ddmf_newFileContent = file_get_contents($plugin_cf['mailforms']['data_path'] . $_SESSION['ddmf_active_mailform']);
			}
			else
			{
				$ddmf_newFileContent = '';
			}
			
			if(!file_exists($plugin_cf['mailforms']['data_path'] . $_POST['ddmf_newform'] . '.txt') && preg_match("/^[a-zA-Z0-9_\-]+$/", $_POST['ddmf_newform']))
			{
				$handle = fopen($plugin_cf['mailforms']['data_path'] . $_POST['ddmf_newform'] . '.txt', 'w+');
				fwrite($handle,$ddmf_newFileContent);
				fclose($handle);
				
				$o.= '<div class="cmsimplecore_warning" style="float: left;"><p style="padding: 3px 10px; margin: 0;">' . $plugin_tx['mailforms']['create_form_with_name'] . ' <b>"' . $_POST['ddmf_newform'] . '"</b> ' . $plugin_tx['mailforms']['create_was_created'] . '</p></div>
<p style="clear: both; font-weight: 700;"><br><a href="./?mailforms&admin=plugin_main&action=plugin_text">' . $plugin_tx['mailforms']['admin_back_to_mailform'] . '</a></p>';
				
				// activate new mailform
				if(isset($_POST['ddmf_newform']))
				{
					$_SESSION['ddmf_active_mailform'] = $_POST['ddmf_newform'] . '.txt';
				}
				chmod($plugin_cf['mailforms']['data_path'] . $_SESSION['ddmf_active_mailform'], 0666);
				return;
			}
			
			if(isset($_POST['ddmf_newform']) && !preg_match("/^[a-zA-Z0-9_\-]+$/", $_POST['ddmf_newform']))
			{
				$o.= '<div class="cmsimplecore_warning" style="float: left;"><p style="padding: 3px 10px; margin: 0;">' . $plugin_tx['mailforms']['create_wrong_chars'] . '</p></div>
<p style="clear: both; font-weight: 700;"><br><a href="./?mailforms&admin=plugin_main&action=plugin_text">' . $plugin_tx['mailforms']['admin_back_to_mailform'] . '</a></p>';
				return;
			}
			
			if(isset($_POST['ddmf_newform']) && file_exists($plugin_cf['mailforms']['data_path'] . $_POST['ddmf_newform'] . '.txt'))
			{
				$o.= '<div class="cmsimplecore_warning" style="float: left;"><p style="padding: 3px 10px; margin: 0;">' . $plugin_tx['mailforms']['create_form_with_name'] . ' <b>"' . $_POST['ddmf_newform'] . '"</b> ' . $plugin_tx['mailforms']['create_form_already_exists'] . '</p></div>
<p style="clear: both; font-weight: 700;"><br><a href="./?mailforms&admin=plugin_main&action=plugin_text">' . $plugin_tx['mailforms']['admin_back_to_mailform'] . '</a></p>';
				return;
			}
		}
		
		if(isset($_POST['ddmfSelectedMailform']) && $_POST['ddmfSelectedMailform'] != '')
		{
			unset($_SESSION['ddmf_active_mailform']);
			$_SESSION['ddmf_active_mailform'] = $_POST['ddmfSelectedMailform'];
		}
		
		
		
		// create mailforms array
		if($ddmfHandle = @opendir($plugin_cf['mailforms']['data_path']))
		{
			while($ddmfMailformFile = readdir($ddmfHandle))
			{
				if ($ddmfMailformFile != "." && $ddmfMailformFile != "..")
				{
					$ddmfMailformFiles[] = $ddmfMailformFile;
					sort($ddmfMailformFiles);
				}
			}
		} 
		
/*
################################################################
                B A C K E N D   O U T P U T
################################################################
*/
		
		if(!isset($_REQUEST['ddmf_write_data']) && (!isset($_POST['ddmf_newform']) || $_POST['ddmf_newform'] == ''))
		{
			// mailforms selectbox
			$o.='
<h4>' . $plugin_tx['mailforms']['admin_select_file'] . '</h4>
<form method="post" action="?mailforms&admin=plugin_main&action=plugin_text">
<select name="ddmfSelectedMailform" onchange="this.form.submit()" style="border: 2px solid #999; padding: 2px;">
<option style="padding: 0 6px;">' . $plugin_tx['mailforms']['admin_select_file'] . '</option>' . "\n";
			
			foreach ($ddmfMailformFiles as $ddmfSelectedMailform)
			{
				if($ddmfSelectedMailform == @$_SESSION['ddmf_active_mailform'])
				{
					$o.='<option selected="selected" style="padding: 0 6px;" value="' . $ddmfSelectedMailform . '">' . $ddmfSelectedMailform . '</option>' . "\n";
				}
				else
				{
					$o.= '<option style="padding: 0 6px;" value="' . $ddmfSelectedMailform . '">' . $ddmfSelectedMailform . '</option>' . "\n";
				}
			}
			
			$o.='</select>
</form>';

			// link "delete selected form"
			if
			(
				isset($_SESSION['ddmf_active_mailform']) && 
				$_SESSION['ddmf_active_mailform'] != '' && 
				$_SESSION['ddmf_active_mailform'] != $plugin_tx['mailforms']['admin_select_file'] && 
				!strpos(file_get_contents($pth['file']['content']), str_replace('.txt','','n:dd_mailform(\'' . $_SESSION['ddmf_active_mailform']))
			)
			{
				$o.= '<p><a href="./?&mailforms&admin=plugin_main&action=plugin_text&ddfm_delform=del" onClick="return confirm(\'' . $plugin_tx['mailforms']['delete_really'] . '\')"><b>' . $plugin_tx['mailforms']['delete_delete_selected_form'] . '</b></a></p>';
			}
			
			if
			(
				isset($_SESSION['ddmf_active_mailform']) && 
				strpos(file_get_contents('./content/content.php'), str_replace('.txt','','n:dd_mailform(\'' . $_SESSION['ddmf_active_mailform']))
			)
			{
				$o.= '<p><b>' . $plugin_tx['mailforms']['delete_form_in_use'] . '</b></p>';
			}

			$o.= '<hr />

<h4>' . $plugin_tx['mailforms']['create_headline'] . '</h4>';

			if
			(
				(isset($_POST['ddmf_newform']) || @$_POST['ddmf_newform'] == '') && 
				(!isset($_SESSION['ddmf_active_mailform']) || @$_SESSION['ddmf_active_mailform'] == '' || $_SESSION['ddmf_active_mailform'] == $plugin_tx['mailforms']['admin_select_file'])
			)
			{
				$o.= '<p style="float: left; background: #600; color: #fff; text-align: center; padding: 6px 9px;">' . $plugin_tx['mailforms']['create_no_form_selected'] . '</p>';
			}

			$o.= '<p style="clear: both;">' . $plugin_tx['mailforms']['create_hint1'] . '</p>
<p>' . $plugin_tx['mailforms']['create_hint2'] . '</p>

<form method="post" action="?mailforms&admin=plugin_main&action=plugin_text">
<input type="text" name="ddmf_newform">
<input type="submit" class="mailforms_admin_submit_button" value="' . $plugin_tx['mailforms']['create_button'] . '" name="save" id="save">
</form>';

			if(isset($_POST['ddmf_newform']) && @$_POST['ddmf_newform'] == '')
			{
				$o.= '<div class="cmsimplecore_warning" style="float: left;"><p style="padding: 3px 10px; margin: 0;">' . $plugin_tx['mailforms']['create_no_name'] . '</p></div>';
			}

			$o.= '
<hr>

<h4>' . $plugin_tx['mailforms']['admin_edit_form'] . '</h4>';
			
			if(isset($_SESSION['ddmf_active_mailform']) && $_SESSION['ddmf_active_mailform'] != $plugin_tx['mailforms']['admin_select_file'])
			{
				// mailforms textarea
				$o.= '<p>' . $plugin_tx['mailforms']['admin_selected_file'] . ': &nbsp; <b>' . $_SESSION['ddmf_active_mailform'] . '</b></p>
<form method="post" action="' . $sn . '?mailforms&admin=plugin_main&action=plugin_text&ddmf_write_data=done">
<input type="submit" class="mailforms_admin_submit_button" style="margin-bottom: 12px;" value="' . $plugin_tx['mailforms']['admin_submit_button'] . '" />
<textarea id="ddmf_data_file" name="ddmf_data_file" style="width: 98%; height: ' . $plugin_cf['mailforms']['admin_textarea_height'] . '; font-family: courier new, monospace; font-size: 14px; line-height: 1.4em; color: #444;">' . 
file_get_contents($plugin_cf['mailforms']['data_path'] . $_SESSION['ddmf_active_mailform']). '
</textarea>
<div style="clear: both;"></div>
<input type="submit" class="mailforms_admin_submit_button" value="' . $plugin_tx['mailforms']['admin_submit_button'] . '" />
</form>' . "\n"
;
			}
			else
			{
				$o.= '<p><b>' . $plugin_tx['mailforms']['admin_no_file_selected'] . '</b></p>';
			}
			
			
			
			if(isset($_SESSION['ddmf_active_mailform']) && $_SESSION['ddmf_active_mailform'] != $plugin_tx['mailforms']['admin_select_file'])
			{
				$o.='
<hr>
<h4>' . $plugin_tx['mailforms']['copycode_elements_for_copy'] . '</h4>

<p style="float: left; background: #600; color: #fff; text-align: center; padding: 6px 9px;">' . $plugin_tx['mailforms']['copycode_hint_fieldnames'] . '</p><br><br><br>

<b>' . $plugin_tx['mailforms']['copycode_label_html'] . '</b><br>
<code class="ddmfAdminCode">type=html|text=&lt;p&gt;Ein wenig Text (some text)&lt;/p&gt;</code><br>

<b>' . $plugin_tx['mailforms']['copycode_label_fieldset'] . '</b><br>
<code class="ddmfAdminCode">type=openfieldset|legend=legend<br>
...<br>
type=closefieldset</code><br>

<b>' . $plugin_tx['mailforms']['copycode_label_recipient'] . '</b><br>
<code class="ddmfAdminCode">type=selrecip|class=ddmf_select|label=Empfänger:|data=Bitte wählen Sie (select):,recipient1,recipient1@#####.##,recipient2,recipient2@#####.##</code><br>

<b>' . $plugin_tx['mailforms']['copycode_label_input'] . '</b><br>
<code class="ddmfAdminCode">type=text|class=ddmf_text|label=Eingabe:|fieldname=ddmf_input|max=250|req=true</code><br>

<b>' . $plugin_tx['mailforms']['copycode_label_selectbox'] . '</b><br>
<code class="ddmfAdminCode">type=select|class=ddmf_select|label=Alter:|fieldname=ddmf_select_age|data=keine Angabe,0-30,31-60,61-90,91-120</code><br>

<b>' . $plugin_tx['mailforms']['copycode_label_selectbox_multi'] . '</b><br>
<code class="ddmfAdminCode">type=select|class=ddmf_select|label=Kataloge:|fieldname=ddmf_kataloge|multi=true|data=Katalog1,Katalog2,Katalog3,Katalog4</code><br>

<b>' . $plugin_tx['mailforms']['copycode_label_selectbox_cats'] . '</b><br>
<code class="ddmfAdminCode">type=select|class=ddmf_select|label=Kategorien:|fieldname=ddmf_categories|data=#Kategorie 1,Auswahl 1.1,Auswahl 1.2,#Kategorie 2,Auswahl 2.1,Auswahl 2.2</code><br>

<b>' . $plugin_tx['mailforms']['copycode_label_radio'] . '</b><br>
<code class="ddmfAdminCode">type=radio|class=ddmf_radio|label=Alter:|fieldname=ddmf_radio_age|data=keine Angabe,0-30,31-60,61-90,91-120|default=1</code><br>

<b>' . $plugin_tx['mailforms']['copycode_label_checkbox'] . '</b><br>
<code class="ddmfAdminCode">type=checkbox|class=ddmf_check|label=Interessen:|data=ddmf_checkbox1_fieldname1,Computer,false,false,ddmf_checkbox1_fieldname2,Smartphones,false,false,ddmf_checkbox1_fieldname3,andere,false,false</code><br>

<b>' . $plugin_tx['mailforms']['copycode_label_datepicker'] . '</b><br>
<code class="ddmfAdminCode">type=date|class=ddmf_date|label=Wunschtermin:|fieldname=ddmf_termin</code><br>

<b>' . $plugin_tx['mailforms']['copycode_label_textarea_narrow'] . '</b><br>
<code class="ddmfAdminCode">type=textarea|class=ddmf_text|label=Ihre Nachricht:|fieldname=ddmf_message2|max=5000|rows=6</code><br>

<b>' . $plugin_tx['mailforms']['copycode_label_textarea_wide'] . '</b><br>
<code class="ddmfAdminCode">type=widetextarea|class=ddmf_textwide|label=Ihre Nachricht:|fieldname=ddmf_message1|max=5000|rows=6|req=true</code><br>

<b>' . $plugin_tx['mailforms']['copycode_label_attach_file'] . '</b><br>
<code class="ddmfAdminCode">type=file|class=ddmf_file|fieldname=ddmf_file_attach</code><br>

<b>' . $plugin_tx['mailforms']['copycode_label_verify'] . '</b><br>
<code class="ddmfAdminCode">type=verify|class=ddmf_verify|label=Spamschutz:</code><br>
';
			}
		}
	}
	$o.='</div>';
}
?>