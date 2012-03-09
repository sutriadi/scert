<?php
/*
 *      class.php
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

class Cert
{
	public $conf = array();
	
	function __construct()
	{
		$this->conf = (object) $conf;
	}
	
	protected function encpicture($file)
	{
		$file = "../../spatch/images/logos/$file";
		if (file_exists($file))
		{
			$contents = file_get_contents($file);
			$encoded = trim(base64_encode($contents));
		} else $encoded = '';
		return $encoded;
	}

	
	protected function cert_fields()
	{
	}
	
	protected function cert_stamp()
	{
	}
	
	protected function cert_body()
	{
	}
	
	protected function cert_header()
	{
		
	}
	
	protected function cert_number()
	{
	}
	
	protected function create()
	{
		
	}
}
