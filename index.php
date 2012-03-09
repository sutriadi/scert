<?php
/*
 *      index.php
 *      
 *      Copyright 2011 Indra Sutriadi Pipii <indra@sutriadi.web.id>
 *      
 *      This program is free software; you can redistribute it and/or modify
 *      it under the terms of the GNU General Public License as published by
 *      the Free Software Foundation; either version 2 of the License, or
 *      (at your option) any later version.
 *      
 *      This program is distributed in the hope that it will be useful,
 *      but WITHOUT ANY WARRANTY; without even the implied warranty of
 *      MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *      GNU General Public License for more details.
 *      
 *      You should have received a copy of the GNU General Public License
 *      along with this program; if not, write to the Free Software
 *      Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 *      MA 02110-1301, USA.
 */

define('INDEX_AUTH', '1');

if (!defined('SENAYAN_BASE_DIR')) {
    require '../../../../../sysconfig.inc.php';
    require SENAYAN_BASE_DIR.'admin/default/session.inc.php';
}

require SENAYAN_BASE_DIR.'admin/default/session_check.inc.php';

$can_read = utility::havePrivilege('plugins', 'r');
$can_write = utility::havePrivilege('plugins', 'w');

if (!$can_read && !$can_write) {
	die(sprintf('<div class="errorBox">%s</div>', __('You dont have enough privileges to view this section')));
}

$conf = $_SESSION['plugins_conf'];
include('../../func.php');
include('../../s_datatables/func.php');

checkip();
checken();
checkref();

$info = (object) plugin_get('scert');
$name = isset($info->plugin_name) ? $info->plugin_name : 'SCert';
$version = isset($info->plugin_version) ? $info->plugin_version : 'beta';
$version .= isset($info->plugin_build) ? " build $info->plugin_build" : '';
$dirmods = "./mods";
$files = scandir($dirmods);
sort($files);
$options = array();
foreach ($files as $file)
{
	$dirmod = $dirmods . '/' . $file;
	$optfile = $dirmod . '/opt.php';
	$mainfile = $dirmod . '/index.php';
	if ($file != "." AND $file != ".." AND ! is_dir($dirmod))
		continue;
	if (file_exists($optfile) AND file_exists($mainfile))
	{
		include($optfile);
		$options[] = array(
			'text' => $opt['text'],
			'target' => $opt['target'],
			'action' => $dirmod . '/' . $opt['action'],
			'selected' => $file == 'html' ? 'selected' : ''
		);
		
		unset($opt);
	}
}

$module_opt = '';
foreach ($options as $option)
	$module_opt .= sprintf('<option value="chtarget(\'%s\', \'%s\')" %s>%s</option>',
		$option['target'],
		$option['action'],
		$option['selected'],
		$option['text']
	);

include('./template.php');

exit();
