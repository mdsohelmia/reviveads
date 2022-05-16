<?php

function renderOutput_inlinevmapVast3($aOut, $pluginType, $vastAdDescription,$format,$aBanner)
{ 
	if ($GLOBALS['_MAX']['SSL_REQUEST']) {
					$djprotocol='https://';
					}
					else
					{
					$djprotocol='http://';
					}
			
			$pp=$GLOBALS['_MAX']['CONF'];
			$way=$djprotocol. $pp['webpath']['delivery'];
	$table_prefix = $GLOBALS['_MAX']['CONF']['table']['prefix'];
	$query=OA_Dal_Delivery_query("select * from {$table_prefix}djaxbanner_vast_element where banner_id='".$aBanner['ad_id']."'");
	$query_fetch=OA_Dal_Delivery_fetchAssoc($query);
	$obj = json_decode($query_fetch['vmapobjects'],true);
	$count=$query_fetch['vmapcount'];
	$tempcount=$count-1;
	$a = $obj['repeataftertime'][0];
	$b = $obj['repeataftertime'][0];

	if($count > 0)
	{
		$vmapobject=$obj['vmapurl'];
		foreach($vmapobject as $key => $vlaue)
		{
			$i=$key;
			
			$offset_time=$obj['vmap'][$i];
			$repeatafter = $obj['repeataftertime'][$i];
			$offsetsetting = $obj['offsetsetting'][$i];
			$allowmultipleAds = $obj['allowmultipleAds'][$i];
			$vmapurl = $obj['vmapurl'][$i];
			$adbreakoffset = $obj['vmap_offset_adbreak'][$i];
			$externaladbreaks = $obj['adbreak'][$i];
			
			if($adbreakoffset=='00:00:00')
			{
				$adpos='Preroll-ad';
			}
			else if($count==$key)
			{
				$adpos='Postroll-ad';
			}
			else
			{
				$adpos='Midroll-ad';
			}
				
			// allow multipleads
			if($allowmultipleAds == '1')
			{
				$allowmultipleAdsoption = 'true';
			}
			else
			{
				
				$allowmultipleAdsoption = 'false';
			}
				$limit='';
			// internel and external adbreaks
			if($vmapurl =='Internal')
			{
				$zoneid=$obj['zoneid'][$i];
				$query_new=OA_Dal_Delivery_query("SELECT ve.vast_overlay_format,ve.vast_overlay_version,ve.vast_video_delivery,
				ve.vast_version FROM {$table_prefix}djaxbanner_vast_element as ve JOIN {$table_prefix}ad_zone_assoc as assoc ON assoc.ad_id=ve.banner_id where zone_id='".$zoneid."'");
			$query_fetch_new=OA_Dal_Delivery_fetchAssoc($query_new);
			$type=$query_fetch_new['vast_overlay_format'];
		
			if($query_fetch_new['vast_overlay_version'] == "2")
			{
				if($query_fetch_new['vast_overlay_version'] == "2")
				{
					$version= "vast2_wrapper";	
				}
				else
				{
					$version= "vast3_wrapper";	
				}
				
			}
			elseif($query_fetch_new['vast_video_delivery'] == "vast")
			{
				if($query_fetch_new['vast_version'] == "2")
				{
					$version= "vast2_wrapper";
				}
				else
				{
					$version= "vast3_wrapper";
				}	
				if($allowmultipleAdsoption=='true')
				{
					$version= "vast_pod_wrapper";
					$limit=$_REQUEST['limit'];
					$limit='&limit='.$limit;
				}
			}
			else
			{
				if($allowmultipleAdsoption=='false')
				{
				$version= "vast3";
				}
				else
				{
				$version="vast_pod";
				$limit=$_REQUEST['limit'];
				$limit='&limit='.$limit;
				}
			}
			
			
			$tag= $way.'/fc.php?script=bannerTypeHtml:vastInlineBannerTypeHtml:vastInlineHtml&zones=postroll:0.0-0%3D'.$zoneid.'&nz=1&source=&r=R0.05822725687175989&block=1&format='.$version.$limit.'&charset=UTF-8';
			
				
			}
			if($vmapurl =='External')
			{
				
				$tag= $externaladbreaks;
				
			}
			
			
			// repeat after enable
			
			if($obj['repeatafter'][0] == '1' && $obj['repeataftertime'][0] != '')
			{
				
				
				if($i == 0)
				{
					$aOut['vmap_offset_adbreak3'] = $obj['repeataftertime'][0];
				}
				if($i > 0)
				{
				$secs = strtotime($a)-strtotime("00:00:00");
				$b = date("H:i:s",strtotime($b)+$secs);
			    $aOut['vmap_offset_adbreak3'] = $b;
			    }
			  

				
			}
			elseif($obj['offsetsetting'][0] == 'percentage')
			{
				
			$aOut['vmap_offset_adbreak3']=$adbreakoffset.'%';
			
			}
			else
			{
			$aOut['vmap_offset_adbreak3']=$adbreakoffset;
			
			}
			
			if($tempcount==$i &&  $_REQUEST['type']=='postroll')
			{
				$aOut['vmap_offset_adbreak3']='end';
			}
			$player .= " <vmap:AdBreak timeOffset=\"${aOut['vmap_offset_adbreak3']}\" breakType=\"Linear\" breakId=\"VMAP-Ad-$i\">";
			$player .= " <vmap:AdSource id=\"$adpos\" allowMultipleAds=\"${allowmultipleAdsoption}\" followRedirects=\"true\">";
			$player .= " <vmap:AdTagURI templateType=\"vast3\">\n";	
			$player .= "<![CDATA[".$tag."]]>";
			$player .= " </vmap:AdTagURI>\n";
			$player .= " </vmap:AdSource>\n";
			$player .= " 	<vmap:TrackingEvents>\n";
			$player .= " <vmap:Tracking event=\"breakStart\">
			<![CDATA[${aOut['trackUrlbreakStart']}]]> ";
			$player .= " </vmap:Tracking>";
			$player .= " <vmap:Tracking event=\"breakEnd\">
		    <![CDATA[${aOut['trackUrlbreakEnd']}]]> ";
		    $player .= " </vmap:Tracking>";
		    $player .= " <vmap:Tracking event=\"error\">
			<![CDATA[${aOut['trackUrlerror']}]]> ";
			$player .= " </vmap:Tracking>";
			$player .= " </vmap:TrackingEvents>";
			$player .= " </vmap:AdBreak>\n";

	
   }
   return $player;
  }
}


function renderOutput_nonlinearvmapVast3($aOut, $pluginType, $vastAdDescription,$format,$aBanner)
{
	if ($GLOBALS['_MAX']['SSL_REQUEST']) {
					$djprotocol='https://';
					}
					else
					{
					$djprotocol='http://';
					}
			
			$pp=$GLOBALS['_MAX']['CONF'];
			$way=$djprotocol. $pp['webpath']['delivery'];
	$table_prefix = $GLOBALS['_MAX']['CONF']['table']['prefix'];
	$query=OA_Dal_Delivery_query("select * from {$table_prefix}banner_vast_element where banner_id='".$aBanner['ad_id']."'");
	$query_fetch=OA_Dal_Delivery_fetchAssoc($query);
	$obj = json_decode($query_fetch['vmapobjects'],true);
	
	
	$count=$query_fetch['vmapcount'];
	$a = $obj['repeataftertime'][0];
	$b = $obj['repeataftertime'][0];
	
	if($count > 0)
	{
		for($i=0;$i<$count;$i++)
		{
			
			$offset_time=$obj['vmap'][$i];
			$repeatafter = $obj['repeataftertime'][$i];
			$offsetsetting = $obj['offsetsetting'][$i];
			$allowmultipleAds = $obj['allowmultipleAds'][$i];
			$vmapurl = $obj['vmapurl'][$i];
			$adbreakoffset = $obj['vmap_offset_adbreak'][$i];
			$externaladbreaks = $obj['adbreak'][$i];
			
			// allow multipleads
			if($allowmultipleAds == '1')
			{
				$allowmultipleAdsoption = 'true';
			}
			else
			{
				
				$allowmultipleAdsoption = 'false';
			}
			
			// internel and external adbreaks
			if($vmapurl =='Internal')
			{
				$zoneid=$obj['zoneid'][$i];
				$query_new=OA_Dal_Delivery_query("SELECT ve.vast_overlay_format,ve.vast_overlay_version,ve.vast_video_delivery,ve.vast_version FROM {$table_prefix}banner_vast_element as ve JOIN {$table_prefix}ad_zone_assoc as assoc ON assoc.ad_id=ve.banner_id where zone_id='".$zoneid."'");
				
				
			$query_fetch_new=OA_Dal_Delivery_fetchAssoc($query_new);
			$type=$query_fetch_new['vast_overlay_format'];
		
			if($query_fetch_new['vast_overlay_version'] == "2")
			{
				if($query_fetch_new['vast_overlay_version'] == "2")
				{
					$version= "vast2_wrapper";	
				}
				else
				{
					$version= "vast3_wrapper";	
				}
				
			}
			elseif($query_fetch_new['vast_video_delivery'] == "vast")
			{
				if($query_fetch_new['vast_version'] == "2")
				{
					$version= "vast2_wrapper";
				}
				else
				{
					$version= "vast3_wrapper";
				}	
			}
			else
			{
				$version= "vast3";
			}
			
		
			
			
			$tag= $way.'/fc.php?script=bannerTypeHtml:vastInlineBannerTypeHtml:vastInlineHtml&zones=postroll:0.0-0%3D'.$zoneid.'&nz=1&source=&r=R0.05822725687175989&block=1&format='.$version.'&charset=UTF-8&loc=\"+location.protocol+window.location.hostname+\"';
			//~ $tag="http://182.72.85.2/djaxtesting/revive_vmapimplement/www/delivery/fc.php?script=bannerTypeHtml:vastInlineBannerTypeHtml:vastInlineHtml&zones=postroll:0.0-0%3D$zoneid&nz=1&source=&r=R0.05822725687175989&block=1&format=$version&charset=UTF-8";
			//$buffer .= $way."/fc.php?script=bannerTypeHtml:vastInlineBannerTypeHtml:vastInlineHtml&zones=$vtype:0.0-0%3D$zoneid&nz=1&source=&r=R0.05822725687175989&block=1&format=vmap&charset=UTF-8&loc=\"+location.protocol+window.location.hostname+\"";

			//~ 
			
				
			}
			if($vmapurl =='External')
			{
				
				$tag= $externaladbreaks;
				
			}
			
			
			// repeat after enable
			
			if($obj['repeatafter'][0] == '1' && $obj['repeataftertime'][0] != '')
			{
				
				
				if($i == 0)
				{
					$aOut['vmap_offset_adbreak3'] = $obj['repeataftertime'][0];
				}
				if($i > 0)
				{
				$secs = strtotime($a)-strtotime("00:00:00");
				$b = date("H:i:s",strtotime($b)+$secs);
			    $aOut['vmap_offset_adbreak3'] = $b;
			    }
			  

				
			}
			elseif($obj['offsetsetting'][0] == 'percentage')
			{
				
			$aOut['vmap_offset_adbreak3']=$adbreakoffset.'%';
			
			}
			else
			{
			$aOut['vmap_offset_adbreak3']=$adbreakoffset;
			
			}
			
			
			
			$player .= " <vmap:AdBreak timeOffset=\"${aOut['vmap_offset_adbreak3']}\" breakType=\"nonlinear\" breakId=\"overlay-ad-3\">";
			$player .= " <vmap:AdSource id=\"midroll-ad\" allowMultipleAds=\"${allowmultipleAdsoption}\" followRedirects=\"true\">";
			$player .= " <vmap:AdTagURI templateType=\"vast3\">\n";	
			$player .= "<![CDATA[".$tag."]]>";
			$player .= " </vmap:AdTagURI>\n";
			$player .= " </vmap:AdSource>\n";
			$player .= " 	<vmap:TrackingEvents>\n";
			$player .= " <vmap:Tracking event=\"breakStart\">
			<![CDATA[${aOut['trackUrlbreakStart']}]]> ";
			$player .= " </vmap:Tracking>";
			$player .= " <vmap:Tracking event=\"breakEnd\">
		   <![CDATA[${aOut['trackUrlbreakEnd']}]]> ";
		    $player .= " </vmap:Tracking>";
		   $player .= " <vmap:Tracking event=\"error\">
		<![CDATA[${aOut['trackUrlerror']}]]> ";
		$player .= " </vmap:Tracking>";
					$player .= " </vmap:TrackingEvents>";
			$player .= " </vmap:AdBreak>\n";
		}
		
		
		//~ exit;
	}
	else
	{

	$adSystem = $GLOBALS['_MAX']['CONF']['ui']['applicationName'] ? $GLOBALS['_MAX']['CONF']['ui']['applicationName'] : 'Revive Adserver';
    $vmap_overlay_count=$aOut['vmap_overlay_count'];
    //$vmap_overlay_count=4;
   

		$player .= "<vmap:AdBreak timeOffset=\"${aOut['vmap_offset_adbreak1']}\" breakType=\"Nonlinear\" breakId=\"VMAP-Ad-$i\">\n";
		$player .= " 	<vmap:AdSource id=\"overlay-ad-1\" allowMultipleAds=\"false\" followRedirects=\"true\">\n";
		$player .= " 	<vmap:AdTagURI templateType=\"vast3\">\n";
		$player .= "<![CDATA[".$aOut['adbreak_tag1']."]]>";
		$player .= " 	</vmap:AdTagURI>";
		$player .= " 	</vmap:AdSource>\n";
		$player .= " 	<vmap:TrackingEvents>\n";
		$player .= " <vmap:Tracking event=\"breakStart\">
		<![CDATA[${aOut['trackUrlbreakStart']}]]> ";
		$player .= " </vmap:Tracking>";
		$player .= " <vmap:Tracking event=\"breakEnd\">
		<![CDATA[${aOut['trackUrlbreakEnd']}]]> ";
		$player .= " </vmap:Tracking>";
		$player .= " <vmap:Tracking event=\"error\">
		<![CDATA[${aOut['trackUrlerror']}]]> ";
		$player .= " </vmap:Tracking>";
		$player .= " </vmap:TrackingEvents>";
		$player .= " </vmap:AdBreak>";
		$player .= " 	<vmap:AdBreak timeOffset=\"${aOut['vmap_offset_adbreak2']}\" breakType=\"nonlinear\" breakId=\"overlay-ad-2\">";
		$player .= " <vmap:AdSource id=\"midroll-ad\" allowMultipleAds=\"false\" followRedirects=\"true\">";
		$player .= " <vmap:AdTagURI templateType=\"vast3\">\n";	
		$player .= "<![CDATA[".$aOut['adbreak_tag2']."]]>";
		$player .= " </vmap:AdTagURI>\n";
		$player .= " </vmap:AdSource>\n";
		$player .= " 	<vmap:TrackingEvents>\n";
		$player .= " <vmap:Tracking event=\"breakStart\">
		<![CDATA[${aOut['trackUrlbreakStart']}]]> ";
		$player .= " </vmap:Tracking>";
		$player .= " <vmap:Tracking event=\"breakEnd\">
		<![CDATA[${aOut['trackUrlbreakEnd']}]]> ";
		$player .= " </vmap:Tracking>";
		$player .= " <vmap:Tracking event=\"error\">
		<![CDATA[${aOut['trackUrlerror']}]]> ";
		$player .= " </vmap:Tracking>";
		$player .= " </vmap:TrackingEvents>";
		$player .= " </vmap:AdBreak>\n";
	

    if($vmap_overlay_count==3)
    {
		$player .= "<vmap:AdBreak timeOffset=\"${aOut['vmap_offset_adbreak3']}\" breakType=\"Nonlinear\" breakId=\"VMAP-Ad-$i\">";
		$player .= " <vmap:AdSource id=\"midroll-ad\" allowMultipleAds=\"false\" followRedirects=\"true\">";
		$player .= " <vmap:AdTagURI templateType=\"vast3\">\n";	
		$player .= "<![CDATA[".$aOut['adbreak_tag3']."]]>";
		$player .= " </vmap:AdTagURI>\n";
		$player .= " </vmap:AdSource>\n";
		$player .= " 	<vmap:TrackingEvents>\n";
		$player .= " <vmap:Tracking event=\"breakStart\">
		<![CDATA[${aOut['trackUrlbreakStart']}]]> ";
		$player .= " </vmap:Tracking>";
		$player .= " <vmap:Tracking event=\"breakEnd\">
		<![CDATA[${aOut['trackUrlbreakEnd']}]]> ";
		$player .= " </vmap:Tracking>";
		$player .= " <vmap:Tracking event=\"error\">
		<![CDATA[${aOut['trackUrlerror']}]]> ";
		$player .= " </vmap:Tracking>";
		$player .= " </vmap:TrackingEvents>";
		$player .= " </vmap:AdBreak>\n";
		
	}
	else if($vmap_overlay_count==4)
	{   

		$player .= " <vmap:AdBreak timeOffset=\"${aOut['vmap_offset_adbreak3']}\" breakType=\"Nonlinear\" breakId=\"VMAP-Ad-$i\">";
		$player .= " <vmap:AdSource id=\"midroll-ad\" allowMultipleAds=\"false\" followRedirects=\"true\">";
		$player .= " <vmap:AdTagURI templateType=\"vast3\">\n";	
		$player .= "<![CDATA[".$aOut['adbreak_tag3']."]]>";
		$player .= " </vmap:AdTagURI>\n";
		$player .= " </vmap:AdSource>\n";
		$player .= " 	<vmap:TrackingEvents>\n";
		$player .= " <vmap:Tracking event=\"breakStart\">
		<![CDATA[${aOut['trackUrlbreakStart']}]]> ";
		$player .= " </vmap:Tracking>";
		$player .= " <vmap:Tracking event=\"breakEnd\">
		<![CDATA[${aOut['trackUrlbreakEnd']}]]> ";
		$player .= " </vmap:Tracking>";
         $player .= " <vmap:Tracking event=\"error\">
		<![CDATA[${aOut['trackUrlerror']}]]> ";
		$player .= " </vmap:Tracking>";
		$player .= " </vmap:TrackingEvents>";
		$player .= " </vmap:AdBreak>\n";
		
		$player .= " <vmap:AdBreak timeOffset=\"${aOut['vmap_offset_adbreak4']}\" breakType=\"Nonlinear\" breakId=\"VMAP-Ad-$i\">";
		$player .= " <vmap:AdSource id=\"midroll-ad\" allowMultipleAds=\"false\" followRedirects=\"true\">";
		$player .= " <vmap:AdTagURI templateType=\"vast3\">\n";	
		$player .= "<![CDATA[".$aOut['adbreak_tag4']."]]>";
		$player .= " </vmap:AdTagURI>\n";
		$player .= " </vmap:AdSource>\n";
		$player .= " 	<vmap:TrackingEvents>\n";
		$player .= " <vmap:Tracking event=\"breakStart\">
		<![CDATA[${aOut['trackUrlbreakStart']}]]> ";
		$player .= " </vmap:Tracking>";
		$player .= " <vmap:Tracking event=\"breakEnd\">
		<![CDATA[${aOut['trackUrlbreakEnd']}]]> ";
		$player .= " </vmap:Tracking>";
		$player .= " <vmap:Tracking event=\"error\">
		<![CDATA[${aOut['trackUrlerror']}]]> ";
		$player .= " </vmap:Tracking>";
		$player .= " </vmap:TrackingEvents>";
		$player .= " </vmap:AdBreak>\n";
	}
	else if($vmap_overlay_count==5)
	{
	    $player .= " <vmap:AdBreak timeOffset=\"${aOut['vmap_offset_adbreak3']}\" breakType=\"Nonlinear\" breakId=\"VMAP-Ad-$i\">";
		$player .= " <vmap:AdSource id=\"midroll-ad\" allowMultipleAds=\"false\" followRedirects=\"true\">";
		$player .= " <vmap:AdTagURI templateType=\"vast3\">\n";	
		$player .= "<![CDATA[".$aOut['adbreak_tag3']."]]>";
		$player .= " </vmap:AdTagURI>\n";
		$player .= " </vmap:AdSource>\n";
		$player .= " 	<vmap:TrackingEvents>\n";
		$player .= " <vmap:Tracking event=\"breakStart\">
		<![CDATA[${aOut['trackUrlbreakStart']}]]> ";
		$player .= " </vmap:Tracking>";
		$player .= " <vmap:Tracking event=\"breakEnd\">
		<![CDATA[${aOut['trackUrlbreakEnd']}]]> ";
		$player .= " </vmap:Tracking>";
		$player .= " <vmap:Tracking event=\"error\">
		<![CDATA[${aOut['trackUrlerror']}]]> ";
		$player .= " </vmap:Tracking>";
		$player .= " </vmap:TrackingEvents>";
		$player .= " </vmap:AdBreak>\n";
		$player .= " <vmap:AdBreak timeOffset=\"${aOut['vmap_offset_adbreak4']}\" breakType=\"nonlinear\" breakId=\"overlay-ad-4\">";
		$player .= " <vmap:AdSource id=\"midroll-ad\" allowMultipleAds=\"false\" followRedirects=\"true\">";
		$player .= " <vmap:AdTagURI templateType=\"vast3\">\n";	
		$player .= "<![CDATA[".$aOut['adbreak_tag4']."]]>";
		$player .= " </vmap:AdTagURI>\n";
		$player .= " </vmap:AdSource>\n";
		$player .= " 	<vmap:TrackingEvents>\n";
		$player .= " <vmap:Tracking event=\"breakStart\">
		<![CDATA[${aOut['trackUrlbreakStart']}]]> ";
		$player .= " </vmap:Tracking>";
		$player .= " <vmap:Tracking event=\"breakEnd\">
		<![CDATA[${aOut['trackUrlbreakEnd']}]]> ";
		$player .= " </vmap:Tracking>";
		$player .= " <vmap:Tracking event=\"error\">
		<![CDATA[${aOut['trackUrlerror']}]]> ";
		$player .= " </vmap:Tracking>";
		$player .= " </vmap:TrackingEvents>";
		$player .= " </vmap:AdBreak>\n";
		
		$player .= "<vmap:AdBreak timeOffset=\"${aOut['vmap_offset_adbreak5']}\" breakType=\"Nonlinear\" breakId=\"VMAP-Ad-$i\">";
		$player .= " <vmap:AdSource id=\"midroll-ad\" allowMultipleAds=\"false\" followRedirects=\"true\">";
		$player .= " <vmap:AdTagURI templateType=\"vast3\">\n";	
		$player .= "<![CDATA[".$aOut['adbreak_tag5']."]]>";
		$player .= " </vmap:AdTagURI>\n";
		$player .= " </vmap:AdSource>\n";
		$player .= " 	<vmap:TrackingEvents>\n";
		$player .= " <vmap:Tracking event=\"breakStart\">
		<![CDATA[${aOut['trackUrlbreakStart']}]]> ";
		$player .= " </vmap:Tracking>";
		$player .= " <vmap:Tracking event=\"breakEnd\">
		<![CDATA[${aOut['trackUrlbreakEnd']}]]> ";
		$player .= " </vmap:Tracking>";
		$player .= " <vmap:Tracking event=\"error\">
		<![CDATA[${aOut['trackUrlerror']}]]> ";
		$player .= " </vmap:Tracking>";
		$player .= " </vmap:TrackingEvents>";
		$player .= " </vmap:AdBreak>\n";
	
		
	}
}	

	return $player;


}
/*function getVastVmapVideoAdOutput($aOut,$format,$vastAdDescription)
{
	
	if($format=='vmap' && $videotype!='Overlay Video Ad')
	{
		
		$vastVideoMarkup =<<<VMAP_VIDEO_AD_TEMPLATE
                <TrackingEvents>
                    <Tracking event="start">
                        <URL id="primaryAdServer"><![CDATA[${aO['trackUrlStart']}]]></URL>
                    </Tracking>
                    <Tracking event="midpoint">
                        <URL id="primaryAdServer"><![CDATA[${aO['trackUrlMidPoint']}]]></URL>
                    </Tracking>
                    <Tracking event="firstQuartile">
                        <URL id="primaryAdServer"><![CDATA[${aO['trackUrlFirstQuartile']}]]></URL>
                    </Tracking>
                    <Tracking event="thirdQuartile">
                        <URL id="primaryAdServer"><![CDATA[${aO['trackUrlThirdQuartile']}]]></URL>
                    </Tracking>
                    <Tracking event="complete">
                        <URL id="primaryAdServer"><![CDATA[${aO['trackUrlComplete']}]]></URL>
                    </Tracking>
                    <Tracking event="mute">
                        <URL id="primaryAdServer"><![CDATA[${aO['trackUrlMute']}]]></URL>
                    </Tracking>
                    <Tracking event="pause">
                        <URL id="primaryAdServer"><![CDATA[${aO['trackUrlPause']}]]></URL>
                    </Tracking>
                    <Tracking event="replay">
                        <URL id="primaryAdServer"><![CDATA[${aO['trackReplay']}]]></URL>
                    </Tracking>
                    <Tracking event="fullscreen">
                        <URL id="primaryAdServer"><![CDATA[${aO['trackUrlFullscreen']}]]></URL>
                    </Tracking>
                    <Tracking event="stop">
                        <URL id="primaryAdServer"><![CDATA[${aO['trackUrlStop']}]]></URL>
                    </Tracking>
                    <Tracking event="unmute">
                        <URL id="primaryAdServer"><![CDATA[${aO['trackUrlUnmute']}]]></URL>
                    </Tracking>
                   <Tracking event="resume">
                        <URL id="primaryAdServer"><![CDATA[${aO['trackUrlResume']}]]></URL>
                    </Tracking>
                </TrackingEvents>
		
VMAP_VIDEO_AD_TEMPLATE;

    return $vastVideoMarkup;
		
		
		
		
		
	}
}*/
