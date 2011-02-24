<?php
/*
 *      pasang.php
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
/*
pengaturan penomoran
*/
$certconf_numbering = array(
	0 => array('nomor', null, 'autoin'),
	1 => array('hal', 'BP', 'string'),
	2 => array('institusi', 'NP', 'string'),
	3 => array('bulan', 'm', 'date-romawi'),
	4 => array('tahun', 'Y', 'date'),
	// fungsi:
	// autoin: otomatis isi angka
	// string: teks statis
	// date: tanggal dalam angka latin
	// date-romawi: tanggal dalam angka romawi
	// number: angka statis
	//~ contoh hasil (diurutkan dari atas) : .../BP/NP/X/2010
);

/*
pengaturan field
*/
$certconf_field = array(
	'member_name' => 'Nama',
	'inst_name' => 'Institusi',
	'member_type_name' => 'Jenis Anggota',
);

/*
pengaturan umum
*/
$certconf_global = array(
	'scert_style' => 'default',
	'surat_nama_perpustakaan' => 'Nama Perpustakaan',
	'surat_judul' => 'Keterangan Bebas Pinjam Perpustakaan',
	'surat_nomor_label' => 'Nomor:',
	'surat_nomor_auto' => true, // true, false
	'surat_nomor_mfield' => 'cert_number',
	'surat_nomor_reset' => 'never', // never, month, year
	'surat_nomor_kode_sep' => '/', // karakter pemisah: /, -, _, .
	'surat_logo_perpustakaan' => 'penguin.png',
	'surat_alamat_perpustakaan' => 'Jl. Kutub Utara-Selatan, No. 01, Kel. Iglo, Kab. Kutub, Prop. Es',
	
	'surat_kalimat_awal' => 'Kami yang bertanda tangan di bawah ini menerangkan bahwa:',
	'surat_kalimat_tengah' => 'Menurut pemeriksaan dan catatan kami, yang bersangkutan sudah tidak mempunyai tanggungan atau pinjaman di',
	'surat_kalimat_tengah2' => 'Adapun surat keterangan ini dipergunakan untuk',
	'surat_kalimat_keperluan' => 'Pengurusan Surat Bebas',
	'surat_kalimat_akhir' => 'Demikian surat keterangan ini dibuat untuk dipergunakan sebagaimana mestinya',
	
	'surat_lokasi' => 'Kotamobagu',
	'surat_kata_mengetahui' => 'Mengetahui,',
	'surat_posisi_pejabat' => 'Kepala Perpustakaan',
	'surat_nama_pejabat' => 'Ilsin Nurkomalasari',
	'surat_nip_pejabat' => '198712242911912001',
	'surat_nip_prefix' => 'NIP. ',

	'surat_format_xml' => 1, // 0 = flat xml odt (fodt); 1 = ms word 2003 xml (wml);

	'field_look_string' => 'member_notes',
	'string_begin' => '##begin##',
	'string_sep' => ';',
	'field_show' => array(
		'member_name',
		'inst_name',
		'member_type_name',
	),
	'use_string_custom' => true,
	'string_custom_label' => '--',
	'string_custom_value' => ':',
	/**
	contoh format pengisian:
		##begin##--NIM:421402001;--Program Studi/Jurusan:Fisika/Fisika;--Fakultas:MIPA;--Perguruan Tinggi:Universitas Negeri Kutub Utara
	**/

	//~ untuk administrasi dgn file csv
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

variable_set('smember_cert_global', json_encode($certconf_global, JSON_FORCE_OBJECT));
variable_set('smember_cert_numbering', json_encode($certconf_numbering, JSON_FORCE_OBJECT));
variable_set('smember_cert_field', json_encode($certconf_field, JSON_FORCE_OBJECT));

$sql = 
