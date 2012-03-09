<?php
/*
 *      template.php
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

if ( ! defined('SENAYAN_BASE_DIR')) { exit(); }
if ( ! $can_read)
	die(sprintf('<div class="errorBox">%s</div>', __('You dont have enough privileges to view this section')));

// mengambil konfigurasi kartu
$s_global = variable_get('scert_global', '', 'serial', true);

// mengambil daftar nama kolom tabel member, dan urutan kolom
$base_cols_name = base_cols_name('member');
$fcols = cols_get('scert');

$dtables = table_render('scert');
extract($dtables);

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php echo $name;?> <?php echo $version;?></title>
	<style type="text/css" title="currentStyle">
		@import "../../library/dataTables/css/demo_table_jui.css";
		@import "../../<?php echo css_get();?>";
		@import "./css/s.css";
		@import "./css/custom.css";
	</style>
	<script type="text/javascript">
		<?php if (isset($php_js)) echo $php_js;?>
	</script>
	<script type="text/javascript" language="javascript" src="../../library/js/jquery.min.js"></script>
	<script type="text/javascript" language="javascript" src="../../library/js/drupal.js"></script>
	<script type="text/javascript" language="javascript" src="../../library/ui/js/jquery-ui.custom.min.js"></script>
	<script type="text/javascript" language="javascript" src="../../library/dataTables/js/jquery.dataTables.js"></script>
	<script type="text/javascript" charset="utf-8" language="javascript" src="./js/s.js"></script>
	<script type="text/javascript" charset="utf-8" language="javascript" src="./js/custom.js"></script>

</head>
<body>
	<div id="container">
		<div id="menubar" class="ui-widget-header">
			<span>
				<?php echo $name;?>
				<?php echo $version;?>
			<span>
			<div id="buttonbar">
				<button id="btnQ"><?php echo __('Quick Options');?></button>
			</div>
		</div>
		<div id="tabular">
			<ul>
				<li><a href="#frontpage"><?php echo __('Home');?></a></li>
				<li><a href="#settings"><?php echo __('Settings');?></a></li>
				<li><a href="#fields"><?php echo __('Fields');?></a></li>
				<li><a href="#file"><?php echo __('File');?></a></li>
			</ul>
			<div id="frontpage">
				<div id="buttonbar">
					<div style="width: 40%; display: inline; ">
						<!--button id="btnO" accesskey="O" title="Alt+Shift+O" ><?php echo __('Options');?></button-->
						<label for="module">Module :</label>
						<select id="module" accesskey="M" title="Alt+Shift+M" onchange="eval(this.value);">
							<?php echo $module_opt;?>
						</select>
					</div>
					<div style="text-align: right; width: 40%; display: inline; float: right; ">
						<button id="btnSubmit" accesskey="S" title="Alt+Shift+S"><?php echo __('Submit');?></button>
					</div>
				</div>
				<form id="formulir" name="formulir" target="cert_result" action="" method="POST">
					<div style="margin: 5px 0px;" width="100%">
						<button type="button" onclick="allcheck(this);" accesskey="A" title="Alt+Shift+A" class="ui-button ui-state-default ui-corner-all"><?php echo __('Check <u>A</u>ll');?></button>
						<button type="button" onclick="alluncheck(this);" accesskey="U" title="Alt+Shift+U" class="ui-button ui-state-default ui-corner-all"><?php echo __('<u>U</u>ncheck All');?></button>
						<button type="button" onclick="invertcheck(this);" accesskey="I" title="Alt+Shift+I" class="ui-button ui-state-default ui-corner-all"><?php echo __('Check <u>I</u>nvert');?></button>
					</div>
					<table cellpadding="0" cellspacing="0" border="0" class="display" id="members">
						<?php echo $thead;?>
						<?php echo $tbody;?>
						<?php echo $tfoot;?>
					</table>
					<div style="margin: 5px 0px;" width="100%">
						<button type="button" onclick="allcheck(this);" title="Alt+Shift+A" class="ui-button ui-state-default ui-corner-all"><?php echo __('Check <u>A</u>ll');?></button>
						<button type="button" onclick="alluncheck(this);" title="Alt+Shift+U" class="ui-button ui-state-default ui-corner-all"><?php echo __('<u>U</u>ncheck All');?></button>
						<button type="button" onclick="invertcheck(this);" title="Alt+Shift+I" class="ui-button ui-state-default ui-corner-all"><?php echo __('Check <u>I</u>nvert');?></button>
					</div>
				</form>
			</div>
			<div id="settings">
				<div id="accordion">
					<div>
						<h3><a href="#"><?php echo __('General');?></a></h3>
						<div>
							<p>
								<label for="cert_library_name" class=""><?php echo __('Library Name');?>:</label>
								<input type="text" id="cert_library_name" name="cert_library_name" size="50" value="<?php echo $s_global->cert_library_name;?>" />
							</p>
							<p>
								<label for="cert_library_addr" class=""><?php echo __('Library Address');?>:</label>
								<input type="text" id="cert_library_addr" name="cert_library_addr" size="50" value="<?php echo $s_global->cert_library_addr;?>" />
							</p>
							<p>
								<label for="cert_title" class=""><?php echo __('Certificate Title');?>:</label>
								<input type="text" id="cert_title" name="cert_title" size="50" value="<?php echo $s_global->cert_title;?>" />
							</p>
							<p>
								<label for="cert_purpose" class=""><?php echo __('Certificate Purpose');?>:</label>
								<input type="text" id="cert_purpose" name="cert_purpose" size="50" value="<?php echo $s_global->cert_purpose;?>" />
							</p>
						</div>
					</div>
					<div>
						<h3><a href="#"><?php echo __('Database');?></a></h3>
						<div>
							<p>
								<label><?php echo __('Serial Data Column');?>:</label>
							</p>
							<p>
								<label><?php echo __('Custom Fields Column');?>:</label>
							</p>
						</div>
					</div>
					<div>
						<h3><a href="#"><?php echo __('Serial Number');?></a></h3>
						<div>
							<p>
								<label for="cert_serial_num_label" class=""><?php echo __('Label');?>:</label>
								<input type="text" id="cert_serial_num_label" name="cert_serial_num_label" size="30" value="<?php echo $s_global->cert_serial_num_label;?>" />
							</p>
							<p>
								<label for="cert_serial_num_separator" class=""><?php echo __('Separator');?>:</label>
								<input type="text" id="cert_serial_num_separator" name="cert_serial_num_separator" size="1" maxlength="1" value="<?php echo $s_global->cert_serial_num_separator;?>" />
							</p>
							<p>
								<label for="cert_serial_num_syntax" class=""><?php echo __('Syntax');?>:</label>
								<textarea cols="30" rows="5"><?php echo $s_global->cert_serial_num_syntax;?></textarea>
							</p>
						</div>
					</div>
					<div>
						<h3><a href="#"><?php echo __('Stamp Area');?></a></h3>
						<div>
							<p>
								<label for="stamp_location" class=""><?php echo __('Location');?>:</label>
								<input type="text" id="stamp_location" name="stamp_location" size="30" value="<?php echo $s_global->stamp_location;?>" />
							</p>
							<p>
								<label for="stamp_word_to_know" class=""><?php echo __('Word To Know');?>:</label>
								<input type="text" id="stamp_word_to_know" name="stamp_word_to_know" size="30" value="<?php echo $s_global->stamp_word_to_know;?>" />
							</p>
							<p>
								<label for="stamp_officer_level" class=""><?php echo __('Officer Level');?>:</label>
								<input type="text" id="stamp_officer_level" name="stamp_officer_level" size="30" value="<?php echo $s_global->stamp_officer_level;?>" />
							</p>
							<p>
								<label for="stamp_officer_name" class=""><?php echo __('Officer Name');?>:</label>
								<input type="text" id="stamp_officer_name" name="stamp_officer_name" size="50" value="<?php echo $s_global->stamp_officer_name;?>" />
							</p>
							<p>
								<label for="stamp_officer_id_number" class=""><?php echo __('Officer ID Number');?>:</label>
								<input type="text" id="stamp_officer_id_number" name="stamp_officer_id_number" size="30" value="<?php echo $s_global->stamp_officer_id_number;?>" />
							</p>
							<p>
								<label for="stamp_id_prefix" class=""><?php echo __('ID Prefix');?>:</label>
								<input type="text" id="stamp_id_prefix" name="stamp_id_prefix" size="30" value="<?php echo $s_global->stamp_id_prefix;?>" />
							</p>
						</div>
					</div>
					<div>
						<h3><a href="#"><?php echo __('Paragraph');?></a></h3>
						<div>
							<p>
								<label for="paragraph_opening" class=""><?php echo __('Opening paragraph');?>:</label>
								<textarea id="paragraph_opening" name="paragraph_opening" cols="50" rows="3"><?php echo $s_global->paragraph_opening;?></textarea>
							</p>
							<p>
								<label for="paragraph_statement" class=""><?php echo __('Statement paragraph');?>:</label>
								<textarea id="paragraph_statement" name="paragraph_statement" cols="50" rows="3"><?php echo $s_global->paragraph_statement;?></textarea>
							</p>
							<p>
								<label for="paragraph_purpose" class=""><?php echo __('Purpose paragraph');?>:</label>
								<textarea id="paragraph_purpose" name="paragraph_purpose" cols="50" rows="3"><?php echo $s_global->paragraph_purpose;?></textarea>
							</p>
							<p>
								<label for="paragraph_closing" class=""><?php echo __('Closing paragraph');?>:</label>
								<textarea id="paragraph_closing" name="paragraph_closing" cols="50" rows="3"><?php echo $s_global->paragraph_closing;?></textarea>
							</p>
						</div>
					</div>
					<div>
						<h3><a href="#"><?php echo __('Fields');?></a></h3>
						<p>
							<button id="open_fields_tab_button"><?php echo __('Open Fields Tab');?></button>
						</p>
					</div>
					<div>
						<h3><a href="#"><?php echo __('Custom Fields');?></a></h3>
						<div>
							<p>
								<label for="custom_enabled" class=""><?php echo __('Enable Custom Fields?');?></label>
								<input type="checkbox" id="custom_enabled" name="custom_enabled" />
								<label style="float: none;" for="custom_enabled"><?php echo __('Yes');?></label>
							</p>
							<p>
								<label for="custom_string_begin" class=""><?php echo __('String Begin');?>:</label>
								<input type="text" id="custom_string_begin" name="custom_string_begin" value="<?php echo $s_global->custom_string_begin;?>" />
							</p>
							<p>
								<label for="custom_string_sep" class=""><?php echo __('String Separator');?>:</label>
								<input type="text" id="custom_string_sep" name="custom_string_sep" value="<?php echo $s_global->custom_string_sep;?>" />
							</p>
							<p>
								<label for="custom_string_label" class=""><?php echo __('String Label');?>:</label>
								<input type="text" id="custom_string_label" name="custom_string_label" value="<?php echo $s_global->custom_string_label;?>" />
							</p>
							<p>
								<label for="custom_string_value" class=""><?php echo __('String Value');?>:</label>
								<input type="text" id="custom_string_value" name="custom_string_value" value="<?php echo $s_global->custom_string_value;?>" />
							</p>
						</div>
					</div>
					<div>
						<h3><a href="#"><?php echo __('CSV File');?></a></h3>
						<div>
							<p>
								<label><?php echo __('Filename');?>:</label>
							</p>
							<p>
								<label><?php echo __('Maximum Filesize');?>:</label>
							</p>
							<p>
								<label><?php echo __('Column Separator');?>:</label>
							</p>
							<p>
								<label><?php echo __('Member ID Label');?>:</label>
							</p>
							<p>
								<label><?php echo __('Fields');?>:</label>
							</p>
							<p>
								<label><?php echo __('Custom Fields');?>:</label>
							</p>
							<p>
								<label><?php echo __('Ordered');?>:</label>
							</p>
						</div>
					</div>
					<div>
						<h3><a href="#"><?php echo __('Replacement String');?></a></h3>
						<div>
							<p>%surat_nama_perpustakaan = <?php echo __('Library Name');?></p>
							<p>%surat_keperluan = <?php echo __('Certificate Purpose');?></p>
							<p>%bulan = <?php echo __('Month');?></p>
							<p>%tahun = <?php echo __('Year');?></p>
						</div>
					</div>
					<p>
						<button id="settings_submit"><?php echo __('Save');?></button>
					</p>
				</div>
			</div>
			<div id="fields">
				<div>
<?php
	foreach ($base_cols_name as $field => $field_name):
		$idfield = 'fields[' . $field .'][sort]';
		$options_fields_sort = '';
		for ($x = 0; $x <= count($base_cols_name); $x++)
		{
			$sort_value = 0;
			if (isset($s_global->fields[$field]))
			{
				$sort_value = 1;
				if (isset($s_global->fields[$field]['sort']))
					$sort_value = $s_global->fields[$field]['sort'];
			}
			$selected = ($x == $sort_value) ? 'selected' : '';
			$options_fields_sort .= sprintf('<option %s value="%s">%s</option>', $selected, $x, $x);
		}
?>

					<p class="left">
						<label for="<?php echo $idfield;?>" class="l120"><?php echo $field_name;?>:</label>
						<select id="<?php echo $idfield;?>" name="<?php echo $idfield;?>">
							<?php echo $options_fields_sort;?>
						</select>
					</p>
<?php
	endforeach;
	unset($options_fields_sort);
?>

				</div>
				<p class="clear">
					<button id="sort_fields_submit"><?php echo __('Save');?></button>
				</p>
			</div>
			<div id="file">
				<fieldset><legend><?php echo __('Upload Logo');?></legend>
				</fieldset>
				<fieldset><legend><?php echo __('Download CSV');?></legend>
				</fieldset>
				<fieldset><legend><?php echo __('Upload CSV');?></legend>
				</fieldset>
			</div>
		</div>
	</div>
	<div id="quick_options">
		<p>
			<label><?php echo __('Enabled Numbering');?>:</label>
		</p>
		<p>
			<label><?php echo __('Reset Number To');?>:</label>
		</p>
	</div>
	<div id="field_label">
		<p>
			<label><?php echo __('Field');?>:</label>
		</p>
		<p>
			<label><?php echo __('Label');?>:</label>
		</p>
	</div>
	<div id="field_sortable">
		
	</div>
</div>
<div id="dialog" title="<?php echo __('Information');?>">
	<p id="validateTips"></p>
</div>

</body>
</html>
