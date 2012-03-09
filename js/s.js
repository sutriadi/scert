//      s.js
//      
//      Copyright 2011 Indra Sutriadi Pipii <indra@sutriadi.web.id>
//      
//      This program is free software; you can redistribute it and/or modify
//      it under the terms of the GNU General Public License as published by
//      the Free Software Foundation; either version 2 of the License, or
//      (at your option) any later version.
//      
//      This program is distributed in the hope that it will be useful,
//      but WITHOUT ANY WARRANTY; without even the implied warranty of
//      MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//      GNU General Public License for more details.
//      
//      You should have received a copy of the GNU General Public License
//      along with this program; if not, write to the Free Software
//      Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//      MA 02110-1301, USA.

	/*** jQuery dataTables ***/

	var asInitVals = new Array();
	var oTable;
	var oCache = { iCacheLower: -1 };

	function fnSetKey( aoData, sKey, mValue )
	{
		for ( var i=0, iLen=aoData.length ; i<iLen ; i++ )
		{
			if ( aoData[i].name == sKey )
			{
				aoData[i].value = mValue
			}
		}
	}

	function fnGetKey( aoData, sKey )
	{
		for ( var i=0, iLen=aoData.length ; i<iLen ; i++ )
		{
			if ( aoData[i].name == sKey )
			{
				return aoData[i].value
			}
		}
		return null;
	}

	function fnDataTablesPipeline ( sSource, aoData, fnCallback ) {
		$.ajax( {
			"dataType": 'json', 
			"type": "POST", 
			"url": sSource, 
			"data": aoData, 
			"success": fnCallback
		} );
		var iPipe = 5; /* Ajust the pipe size */
		
		var bNeedServer = false;
		var sEcho = fnGetKey(aoData, "sEcho");
		var iRequestStart = fnGetKey(aoData, "iDisplayStart");
		var iRequestLength = fnGetKey(aoData, "iDisplayLength");
		var iRequestEnd = iRequestStart + iRequestLength;
		oCache.iDisplayStart = iRequestStart;
		
		/* outside pipeline? */
		if ( oCache.iCacheLower < 0 || iRequestStart < oCache.iCacheLower || iRequestEnd > oCache.iCacheUpper )
		{
			bNeedServer = true;
		}
		
		/* sorting etc changed? */
		if ( oCache.lastRequest && !bNeedServer )
		{
			for( var i=0, iLen=aoData.length ; i<iLen ; i++ )
			{
				if ( aoData[i].name != "iDisplayStart" && aoData[i].name != "iDisplayLength" && aoData[i].name != "sEcho" )
				{
					if ( aoData[i].value != oCache.lastRequest[i].value )
					{
						bNeedServer = true;
						break;
					}
				}
			}
		}
		
		/* Store the request for checking next time around */
		oCache.lastRequest = aoData.slice();
		
		if ( bNeedServer )
		{
			if ( iRequestStart < oCache.iCacheLower )
			{
				iRequestStart = iRequestStart - (iRequestLength*(iPipe-1));
				if ( iRequestStart < 0 )
				{
					iRequestStart = 0;
				}
			}
			
			oCache.iCacheLower = iRequestStart;
			oCache.iCacheUpper = iRequestStart + (iRequestLength * iPipe);
			oCache.iDisplayLength = fnGetKey( aoData, "iDisplayLength" );
			fnSetKey( aoData, "iDisplayStart", iRequestStart );
			fnSetKey( aoData, "iDisplayLength", iRequestLength*iPipe );
			
			$.getJSON( sSource, aoData, function (json) { 
				/* Callback processing */
				oCache.lastJson = jQuery.extend(true, {}, json);
				
				if ( oCache.iCacheLower != oCache.iDisplayStart )
				{
					json.aaData.splice( 0, oCache.iDisplayStart-oCache.iCacheLower );
				}
				json.aaData.splice( oCache.iDisplayLength, json.aaData.length );
				
				fnCallback(json)
			} );
		}
		else
		{
			json = jQuery.extend(true, {}, oCache.lastJson);
			json.sEcho = sEcho; /* Update the echo for each response */
			json.aaData.splice( 0, iRequestStart-oCache.iCacheLower );
			json.aaData.splice( iRequestLength, json.aaData.length );
			fnCallback(json);
			return;
		}
	}

	$(document).ready(function() {
		eval($('#module').val());
		
		$('#btnSubmit').click(function() {
			if($('input[name="member[]"]').serializeArray().length!=0)
				document.formulir.submit();
			else{
				$('#validateTips').text('No member(s) selected!');
				$('#dialog').dialog("open");
			}
		});
		
		var jsDef = {
			"bProcessing": true,
			"bServerSide": true,
			"bAutoWidth": false,
			"bJQueryUI": true,
			"bFilter": true,
			"aLengthMenu": [[5, 10, 20, 30, 40, 50], [5, 10, 20, 30, 40, 50]],
			"sPaginationType": "full_numbers",
			"sAjaxSource": "../../library/dataTables/php/processing.php?plugin=scert&table=scert",
			"fnServerData": fnDataTablesPipeline,
			"sScrollY": "200px",
			"oLanguage": {
				"sSearch": "Search all:"
			}
		};
		
		$.extend(true, jsDef, phpDef);

		oTable=$('#members').dataTable( jsDef );
		
		$('#tabular').tabs({
			ajaxOptions: {
				error: function( xhr, status, index, anchor ) {
					$( anchor.hash ).html(
						"Couldn't load this tab. We'll try to fix this as soon as possible. " +
						"If this wouldn't be a demo." );
				}
			}
		});
		
		$('#accordion').accordion({
			autoHeight: false,
			collapsible: true,
			header: "h3"
		});
		
		var default_dialog = {
			'autoOpen': false,
			'modal': true,
		}
		
		var information_dialog = {
			buttons: {
				'OK': function() {
					$(this).dialog("close");
				}
			}
		}
		
		$.extend(true, information_dialog, default_dialog);
		
		$('#dialog').dialog(eval(information_dialog));
		$('#quick_options').dialog(default_dialog);
		$('#field_label').dialog(default_dialog);
		$('#field_sortable').dialog(default_dialog);
		
		$('button').button();
		
		$('#btnQ').click(function() {
			$('#quick_options').dialog("open");
		});
		
		$('#btnS').click(function() {
			alert("Not implemented");
		});
		
		$('#btnO').click(function() {
			alert("Not implemented");
		});

		$("tfoot input").keyup( function () {
			/* Filter on the column (the index) of this element */
			oTable.fnFilter( this.value, $("tfoot input").index(this) );
		} );
		
		/*
		 * Support functions to provide a little bit of 'user friendlyness' to the textboxes in 
		 * the footer
		 */
		$("tfoot input").each( function (i) {
			asInitVals[i] = this.value;
		} );
		
		$("tfoot input").focus( function () {
			if ( this.className == "search_init" )
			{
				this.className = "";
				this.value = "";
			}
		} );
		
		$("tfoot input").blur( function (i) {
			if ( this.value == "" )
			{
				this.className = "search_init";
				this.value = asInitVals[$("tfoot input").index(this)];
			}
		} );

		/* untuk ngetes doang */
		//~ $("#ngetes").click( function() {
			//~ $.get("../../library/dataTables/php/processing.php?plugin=smember&table=smember", {},
			   //~ function(data){
				 //~ alert(data);
			   //~ }, "html"
			//~ );
		//~ });

	} );

	/*** some addition function ***/
	
	function chtarget(target, action)
	{
		var f=document.formulir
		
		f.target=target
		f.action=action
	}

	function allcheck(t)
	{
		f=t.form
		cb=f.elements['member[]']
		if(cb){
			for(n=0;n<cb.length;n++)
				cb[n].checked=true
		}
	}
	
	function alluncheck(t)
	{
		f=t.form
		cb=f.elements['member[]']
		if(cb){
			for(n=0;n<cb.length;n++)
				cb[n].checked=false
		}
	}
	
	function invertcheck(t)
	{
		f=t.form
		cb=f.elements['member[]']
		if(cb){
			for(n=0;n<cb.length;n++)
				cb[n].checked=cb[n].checked==true?false:true
		}
	}

	Drupal.behaviors.scert_Style = function (context) {
		//~ $("input").addClass("ui-widget ui-state-default ui-corner-all");
	}
