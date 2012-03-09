<?php
/*
 *      uninstall.php
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

if (!defined('MODULES_WEB_ROOT_DIR')) {
	exit();
}

$scert_dir = FILES_UPLOAD_DIR . 'plugins' . DIRECTORY_SEPARATOR . 'scert';

variable_del('scert_global');
unlink($scert_dir . DIRECTORY_SEPARATOR . 'logo.png');
if (file_exists($scert_dir . DIRECTORY_SEPARATOR . 'custom_logo.png'));
	unlink($scert_dir . DIRECTORY_SEPARATOR . 'custom_logo.png');
rmdir($scert_dir);

$sql = "ALTER TABLE `member` DROP COLUMN `cert_number`";
$dbs->query($sql);
