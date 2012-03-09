<?php
/*
 *      index.php
 *      
 *      Copyright 2012 Indra Sutriadi Pipii <indra@sutriadi.web.id>
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
 *      
 *      
 */

define('INDEX_AUTH', '1');

$level = '../../../../../../../';
require $level . 'sysconfig.inc.php';

require SENAYAN_BASE_DIR.'admin/default/session.inc.php';
require SENAYAN_BASE_DIR.'admin/default/session_check.inc.php';

$can_read = utility::havePrivilege('plugins', 'r');

if ( ! $can_read)
	die(sprintf('<div class="errorBox">%s</div>', __('You dont have enough privileges to view this section')));

$conf = $_SESSION['plugins_conf'];
require('../../../../func.php');
include('../../../../s_datatables/func.php');

checkip();
checken();
checkref('plugin');

$plugin = checkname();

// mengambil konfigurasi
$s_global = variable_get('scert_global', '', 'serial', true);
$scert_dir = SENAYAN_WEB_ROOT_DIR . FILES_DIR . DIRECTORY_SEPARATOR . 'plugins' . DIRECTORY_SEPARATOR . $plugin;
$base_cols_name = base_cols_name('member');

if (isset($_POST['member']))
{
	$count = count($_POST['member']);
	$header = sprintf('<img class="cert_logo" src="%s">', $scert_dir . DIRECTORY_SEPARATOR . $s_global->cert_logo_fname);
	$header .= '<h1 class="cert_header">' . $s_global->cert_library_name . '</h1>';
	$header .= '<address class="cert_header_addr">' . $s_global->cert_library_addr . '</address>';
	$header .= '<hr class="header_line" />';
	$header .= '<p class="cert_title">' . $s_global->cert_title . '</p>';
	
	$serial_number = explode("\n", $s_global->cert_serial_num_syntax);
	unset($serial_number[0]);
	$serial_number = implode($s_global->cert_serial_num_separator, $serial_number);

	$paragraph = '<div class="paragraph">';
	$paragraph .= '<p class="par">' . $s_global->paragraph_opening . '</p>';
	$paragraph .= '%s';
	$paragraph .= '<p class="par">' . $s_global->paragraph_statement . '</p>';
	$paragraph .= '<p class="par">' . $s_global->paragraph_purpose . '</p>';
	$paragraph .= '<p class="par">' . $s_global->paragraph_closing . '</p>';
	$paragraph .= '</div>';
	
	$stamp = '<div class="stamp">';
	$stamp .= sprintf('<p class="stamp stamp_location">%s, %s</p>', $s_global->stamp_location, date('d F Y'));
	$stamp .= '<p class="stamp stamp_wtk">' . $s_global->stamp_word_to_know .'</p>';
	$stamp .= '<p class="stamp stamp_off_level">' . $s_global->stamp_officer_level .'</p>';
	$stamp .= '<p class="stamp stamp_off_name">' . $s_global->stamp_officer_name .'</p>'; 
	$stamp .= sprintf('<p class="stamp stamp_off_idnum">%s</p>', trim($s_global->stamp_officer_id_number) == '' ? '' : (trim($s_global->stamp_id_prefix) != '' ? $s_global->stamp_id_prefix : '') . $s_global->stamp_officer_id_number);
	$stamp .= '</div>';
	
	$end = '<div class="end"></div>';
	
	$output = '';
	foreach ($_POST['member'] as $memberid)
	{
		$sql = sprintf("SELECT %s FROM `member` LEFT JOIN `mst_member_type` ON `mst_member_type`.`member_type_id` = `member`.`member_type_id` WHERE `member_id` = '%s'",
			implode(', ', $s_global->fields),
			$memberid
		);
		$mdata = $dbs->query($sql);
		$mfield = '';
		while ($member = $mdata->fetch_row())
		{
			foreach ($s_global->fields as $numfield => $field)
			{
				$mfield .= sprintf('<p class="fields %s"><label class="field_label">%s</label>: %s</p>',
					str_replace('.', '_', $field),
					$base_cols_name[$field],
					trim($member[$numfield]) != '' ? $member[$numfield] : '-'
				);
			}
		}
		
		$output .= '<div class="item">';
		$output .= $header;
		$output .= sprintf('<p class="cert_serial_num">%s %s</p>', $s_global->cert_serial_num_label, $serial_number);
		$output .= sprintf($paragraph, $mfield);
		$output .= $stamp;
		$output .= $end;
		$output .= '</div>';
		$count--;
		if ($count > 0)
			$output .= '<span class="break"></span>';
	}
	
	if (trim($output) != '')
	{
		$output = str_replace('@space', str_repeat("&nbsp; ", 3), $output);
		$output = str_replace('@month', date('m'), $output);
		$output = str_replace('@year', date('Y'), $output);
		$output = str_replace('@surat_nama_perpustakaan', $s_global->cert_library_name, $output);
		$output = str_replace('@surat_keperluan', $s_global->cert_purpose, $output);
	}
}
?>
<html>
	<head>
		<title><?php echo __('HTML Output SCert');?></title>
		<script type="text/javascript" src="./js/html.js"></script>
		<link href="./css/html.css" rel="stylesheet" type="text/css" />
	</head>
	<body>
		<?php echo $output;?>
		<div id="bar">
			<button id="btnCetak" onclick="window.print();"><?php echo __('Print');?></button>
			<button id="btnTutup" onclick="window.close();"><?php echo __('Close');?></button>
		</div>
	</body>
</html>
