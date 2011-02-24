//      data.js
//      
//      Copyright 2011 Indra Sutriadi Pipii <indra.sutriadi@gmail.com>
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
		$('#formulir').submit( function() {
			var df=document.formulir.dofirst
			if (df.selectedIndex!=0) {
				$("#validateTips").text("Yakin akan melakukan "+df.options[df.selectedIndex].text).effect("highlight", {}, 1500);
				$('#dialog').dialog("option", "buttons", {
					OK: function() {
						var sData = $('input', oTable.fnGetNodes()).serialize();
						document.formulir.submit();
						document.formulir.dofirst.selectedIndex=0;
						chform('', '');
						$(this).dialog('close');
					}
				}),
				$('#dialog').dialog('open');
			}
			return false;
		} );
		$("#card_conf_accordion, #cert_conf_accordion").accordion({
			autoHeight: false,
			collapsible: true,
			header: "h3"
		});
		var dialog_conf = {
			bgiframe: true,
			autoOpen: false,
			modal: true,
			height: "400",
			width: "600",
			buttons: {
				Cancel: function() {
					$(this).dialog('close');
				}
			},
		}
		var cert_conf = {
			buttons: {
				OK: function() {
					$.post(
						"./php/setup.php?conf=cert",
						$('form[name="cert_conf_form"]').serializeArray(),
						function(data){
							$("#validateTips").text("Data sudah disimpan!").effect("highlight", {}, 1500);
							$('#dialog').dialog("option", "buttons", {"OK": function() { $(this).dialog("close"); } } );
							$('#dialog').dialog('open');
						}
					);
					$(this).dialog('close');
				},
			},
		}
		var card_conf = {
			buttons: {
				OK: function() {
					$.post(
						"./php/setup.php?conf=card",
						$('form[name="card_conf_form"]').serializeArray(),
						function(data){
							alert(data.track);
							$("#validateTips").text("Data sudah disimpan!").effect("highlight", {}, 1500);
							$('#dialog').dialog("option", "buttons", {"OK": function() { $(this).dialog("close"); } } );
							$('#dialog').dialog('open');
						},
						"json"
					);
					$(this).dialog('close');
				},
			},
		}
		$.extend(true, cert_conf, dialog_conf);
		$.extend(true, card_conf, dialog_conf);
		$("#cert_conf").dialog(eval(cert_conf));
		$("#card_conf").dialog(eval(card_conf));
		$("#dialog").dialog({
			bgiframe: true,
			autoOpen: false,
			modal: true,
			buttons: {
				Cancel: function() {
					$(this).dialog('close');
				},
			},
		});

		$('#tutup').click(function() { window.close() });
		$('#reload').click(function() { window.location.reload() });
		
		$('button').button();
		$('#to-conf-card').button().click(function() {
			$('#card_conf').dialog("open");
		});
		$('#to-conf-cert').button().click(function() {
			$('#cert_conf').dialog("open");
		});

		oTable=$('#members').dataTable( {
			"bProcessing": true,
			"bServerSide": true,
			"bAutoWidth": false,
			"bJQueryUI": true,
			"bFilter": true,
			"aLengthMenu": [[5, 10, 20, 30, 40, 50], [5, 10, 20, 30, 40, 50]],
			"sPaginationType": "full_numbers",
			"sAjaxSource": "./php/processing.php",
			"fnServerData": fnDataTablesPipeline,
			"aoColumns": [
				{ "sClass": "center", "bSortable": false },
				null,
				null,
				null,
				null,
				null,
			],
			"sScrollY": "200px",
			"oLanguage": {
				"sSearch": "Search all:"
			}
		} );

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

	} );

	/*** theme function ***/

	var fileadded=''

	function reload(css,dir)
	{
		if(fileadded.length!=0)
			remove()
		if(fileadded!=css){
			var filename=dir+css+"/jquery-ui-1.8.9.custom.css"
			var fileref=document.createElement("link")
			fileref.setAttribute("rel", "stylesheet")
			fileref.setAttribute("type", "text/css")
			fileref.setAttribute("href", filename)
			document.getElementsByTagName("head")[0].appendChild(fileref)
			fileadded=css
			dir=dir
		}
	}

	function remove()
	{
		var filename=fileadded
		var targetattr='href'
		var allsuspects=document.getElementsByTagName('link')
		for(var i=allsuspects.length;i>=0;i--){
			if(allsuspects[i]&&allsuspects[i].getAttribute(targetattr)!=null&&allsuspects[i].getAttribute(targetattr).indexOf(filename)!=-1)
				allsuspects[i].parentNode.removeChild(allsuspects[i])
		}
	}

	/*** some addition function ***/
	
	function chform(target, action)
	{
		var f=document.formulir
		f.target=target
		f.action=action
	}

	function allcheck(t)
	{
		f=t.form
		cb=f.elements['members[]']
		for(n=0;n<cb.length;n++)
			cb[n].checked=true
	}
	
	function alluncheck(t)
	{
		f=t.form
		cb=f.elements['members[]']
		for(n=0;n<cb.length;n++)
			cb[n].checked=false
	}
	
	function invertcheck(t)
	{
		f=t.form
		cb=f.elements['members[]']
		for(n=0;n<cb.length;n++)
			cb[n].checked=cb[n].checked==true?false:true
	}
	
	function chtheme(t)
	{
		s=t.selectedIndex
		if(s!=0)
			alert('No theme!')
	}
