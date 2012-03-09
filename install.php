<?php
/*
 *      install.php
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

if (!defined('MODULES_WEB_ROOT_DIR')) { exit(); }

/*
konfigurasi surat keterangan bebas tagihan/pinjaman perpustakaan
*/

$logo_data = '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';
$logo_data = base64_decode($logo_data);

$logo_fname = 'logo.png';
if ( ! file_exists(FILES_UPLOAD_DIR . 'plugins'))
	mkdir(FILES_UPLOAD_DIR . 'plugins');
$scert_dir = FILES_UPLOAD_DIR . 'plugins' . DIRECTORY_SEPARATOR . 'scert';
if ( ! file_exists($scert_dir))
	mkdir($scert_dir);
if (file_exists($scert_dir . DIRECTORY_SEPARATOR . $logo_fname))
	unlink($scert_dir . DIRECTORY_SEPARATOR . $logo_fname);
else
	file_put_contents($scert_dir . DIRECTORY_SEPARATOR . $logo_fname, $logo_data);

/*
pengaturan umum
*/
$certconf_global = array(
	/* general */
	'cert_library_name' => $sysconf['library_name'], // Library Name
	'cert_title' => 'Surat Keterangan Bebas Pinjam Perpustakaan', // Certificate Title
	'cert_library_addr' => 'Jl. Kutub Utara-Selatan, No. 01, Kel. Iglo, Kab. Kutub, Prop. Es',
	'cert_purpose' => 'Pengurusan Surat Bebas',
	'cert_logo_fname' => $logo_fname,

	/* database */
	'db_serial_data_column' => 'member_cert_serial',
	'db_custom_fields_column' => 'member_cert_customs',
	
	'surat_nomor_auto' => true, // true, false
	'surat_nomor_reset' => 'never', // never, month, year
	
	/* serial numbering */
	'cert_serial_num_label' => 'Nomor:', // Number Label
	'cert_serial_num_separator' => '/', // karakter pemisah: /, -, _, .
	'cert_serial_num_syntax' => "\n@space\nSKBPP\nPERPUS\n@month\n@year", // karakter pemisah: /, -, _, .
	
	/* paragraph */
	'paragraph_opening' => 'Kami yang bertanda tangan di bawah ini menerangkan bahwa:',
	'paragraph_statement' => 'Menurut pemeriksaan dan catatan kami, yang bersangkutan sudah tidak mempunyai tanggungan atau pinjaman di @surat_nama_perpustakaan.',
	'paragraph_purpose' => 'Adapun surat keterangan ini dipergunakan untuk @surat_keperluan.',
	'paragraph_closing' => 'Demikian surat keterangan ini dibuat untuk dipergunakan sebagaimana mestinya.',
	
	/* stamp area */
	'stamp_location' => 'Kotamobagu',
	'stamp_word_to_know' => 'Mengetahui,',
	'stamp_officer_level' => 'Kepala Perpustakaan',
	'stamp_officer_name' => 'Ilsin Nurkomalasari',
	'stamp_officer_id_number' => '198712242911912001',
	'stamp_id_prefix' => 'NIP. ',

	/* fields */
	'fields' => array(
		'member.member_name',
		'member.inst_name',
		'mst_member_type.member_type_name',
	),

	/* custom fields annotation */
	'custom_enabled' => true,
	'custom_string_begin' => '##begin##',
	'custom_string_sep' => ';',
	'custom_string_label' => '--',
	'custom_string_value' => ':',
	/**
	contoh format pengisian:
		##begin##--NIM:421402001;--Program Studi/Jurusan:Fisika/Fisika;--Fakultas:MIPA;--Perguruan Tinggi:Universitas Negeri Kutub Utara
	**/

	/* csv */
	'csv_file' => 'csvfile',
	'csv_max_filesize' => 81920000,
	'csv_sep' => ',',
	'csv_member_id_title' => 'Id',
	'csv_field' => array(
		'Nama' => 'member_name',
		'Institusi' => 'inst_name',
		'Jenis Anggota' => 'member_type_name',
	),
	'csv_custom' => array(
		'Program Studi', 'Jurusan', 'Fakultas', 'Perguruan Tinggi'
	),
	'csv_ordered' => true,
);

variable_set('scert_global', $certconf_global, 'serial');

$dtables = array(
	'table' => 'scert',
	'type' => 'member',
	'title' => 'SCert',
	'desc' => __('DataTables for plugin SCert.'),
	'first_col' => 'radio',
	'base_cols' => '["member_id","member_name","member_type_id","member_email","inst_name"]',
	'end_cols' => '',
	'php_code' => 0,
	'add_code' => '',
	'windowed' => 1,
	'sort' => '{"member.member_id":"0","member.member_name":"1","mst_member_type.member_type_name":"2","member.member_email":"5","member.inst_name":"4"}',
);
dtable_set($dtables);

$sql = "ALTER TABLE `member` ADD COLUMN `cert_number` VARCHAR(255) NOT NULL AFTER `expire_date`";
$dbs->query($sql);
