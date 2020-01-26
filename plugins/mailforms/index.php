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

if (count($_POST) > 0) 
{
	$lastpost = isset($_COOKIE['lastpost']) ? $_COOKIE['lastpost'] : '';
	if ($lastpost != md5(serialize($_POST))) 
	{
		setcookie('lastpost', md5(serialize($_POST)));
		$_POST['_REPEATED'] = 0;
	} 
	else 
	{
		$_POST['_REPEATED'] = 1;
	}
}

if (!defined('CMSIMPLE_VERSION') || preg_match('#/plugins/mailforms/index.php#i', $_SERVER['SCRIPT_NAME']))
{
	die('no direct access');
}

global $hjs;

if(file_exists($pth['folder']['base'] . 'plugins/mailforms/javascript/date_chooser_' . $sl . '.js'))
{
	$hjs.='<script type="text/javascript" src="' . $pth['folder']['base'] . 'plugins/mailforms/javascript/date_chooser_' . $sl . '.js"></script>' . "\n";
}
else
{
	$hjs.='<script type="text/javascript" src="' . $pth['folder']['base'] . 'plugins/mailforms/javascript/date_chooser.js"></script>' . "\n";
}


if ($f=='mailform' && file_exists($plugin_cf['mailforms']['data_path'] . '_replaceSMF.txt') && $cf['mailform']['email'] != '' && $plugin_cf['mailforms']['replace_cmsimple_mailform'] == 'true') 
{
	$f = ''; 
	$o.= '<h1>'.$tx['title']['mailform'].'</h1>';
	$o.= dd_mailform('_replaceSMF',$cf['mailform']['email'],$plugin_cf['mailforms']['replace_with_copy_to_sender']);
}


function dd_mailform($dataFileName, $ddmfMailAdress=null, $copyToSender='true', $ddmfLayout=null, $ddmfSubject=null) 
{
	global $form_input, $adm, $cf, $tx, $hjs, $plugin_cf, $form_submitted, $recipients, $show_required, $verify_method, $script_path, $form_struct;
	
	global $ddmfCopyToSender;
	$ddmfCopyToSender = $copyToSender;
	$ddfmOutput = '';

	if(!file_exists($plugin_cf['mailforms']['data_path'] . $dataFileName . '.txt'))
	{
		copy($plugin_cf['mailforms']['data_path'] . '_default.txt',$plugin_cf['mailforms']['data_path'] . $dataFileName . '.txt');
		chmod($plugin_cf['mailforms']['data_path'] . $dataFileName . '.txt',0666);
	}
	
	if ($dataFileName==null || $dataFileName=='')
	{
		$form_struct='type=fullblock|class=fmfullblock|text=No data block is selected.';
	}
	else 
	{
		$form_struct = file_get_contents($plugin_cf['mailforms']['data_path'] . $dataFileName . '.txt');
	}
	
	if(isset($ddmfMailAdress) && $ddmfMailAdress != '')
	{
		$recipients = $ddmfMailAdress;
	}
	else
	{
		$recipients = $plugin_cf['mailforms']['admin_mail_adress'];
	}
	
	@include_once('dd-formmailer.php');
	
	return $ddfmOutput;
}
?>