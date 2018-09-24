<?php
/**
 * Plugin Name: Uppercase Thread Titles
 * Description: Converts thread titles to all uppercase letters.
 * Author: Brian. ( https://community.mybb.com/user-115119.html )
 * Version: 1.2
 * File: uppercase.php
**/
 
if(!defined("IN_MYBB"))
{
    	die("Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.");
}

$plugins->add_hook("datahandler_post_insert_thread", "uppercase_newthreads");
$plugins->add_hook("datahandler_post_update_thread", "uppercase_editthreads");

function uppercase_info()
{
	return array(
		"name"			=> "Uppercase Thread Titles",
		"description"	=> "Converts thread titles to all uppercase letters.",
		"website"		=> "https://community.mybb.com/user-115119.html",
		"author"		=> "Brian.",
		"authorsite"	=> "https://community.mybb.com/user-115119.html",
		"version"		=> "1.2",
		"compatibility" => "16*,18*"
	);
}

function uppercase_activate()
{
	global $db;
	$uppercase_settingsgroup = array(
		"gid"    => "0",
		"name"  => "uppercase_settingsgroup",
		"title"      => "Uppercase Titles Settings",
		"description"    => "These options allow you to set the plugin to use all uppercase letters for thread title\'s.",
		"disporder"    => "1",
		"isdefault"  => "0",
	);

	$db->insert_query("settinggroups", $uppercase_settingsgroup);
	$gid = $db->insert_id();
	$uppercase_capitalthreads = array(
		"sid"            => "0",
		"name"        => "uppercase_capitalthreads",
		"title"            => "Use all uppercase letters in thread title\'s",
		"description"    => "If you would like to use all uppercase letters in thread title\'s, select yes below.",
		"optionscode"    => "yesno",
		"value"        => "1",
		"disporder"        => "1",
		"gid"            => intval($gid),
	);
	
	$db->insert_query("settings", $uppercase_capitalthreads);
  	rebuild_settings();
	
}

function uppercase_newthreads($datahandler)
{
	global $mybb, $db;
		if ($mybb->settings['uppercase_capitalthreads'] == 1)
		{
			$datahandler->thread_insert_data['subject'] = strtoupper($datahandler->thread_insert_data['subject']);
		}
}

function uppercase_editthreads($datahandler)
{
	global $mybb, $db;
		if ($mybb->settings['uppercase_capitalthreads'] == 1 && $datahandler->thread_update_data['subject'])
		{
			$datahandler->thread_update_data['subject'] = strtoupper($datahandler->thread_update_data['subject']);
		}
}

function uppercase_deactivate()
{
	global $db;
		$db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name IN('uppercase_capitalposts', 'uppercase_settingsgroup')");
		$db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name IN('uppercase_capitalthreads', 'uppercase_settingsgroup')");
		$db->query("DELETE FROM ".TABLE_PREFIX."settinggroups WHERE name='uppercase_settingsgroup'");
		rebuild_settings();
}
?>