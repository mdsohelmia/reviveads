<?php
require_once MAX_PATH . '/plugins/bannerTypeHtml/djaxvastInlineBannerTypeHtml/common.php';
require_once MAX_PATH . '/plugins/bannerTypeHtml/djaxvastInlineBannerTypeHtml/vastinline.php';
require_once MAX_PATH . '/plugins/bannerTypeHtml/djaxvastInlineBannerTypeHtml/vastoverlay.php';
require_once MAX_PATH . '/plugins/bannerTypeHtml/djaxvastInlineBannerTypeHtml/vastwrapper_inline.php';
require_once MAX_PATH . '/plugins/bannerTypeHtml/djaxvastInlineBannerTypeHtml/vastwrapper_overlay.php';
require_once MAX_PATH . '/plugins/bannerTypeHtml/djaxvastInlineBannerTypeHtml/vmapvast_inline.php';

if($GLOBALS['_MAX']['CONF']['database']['type']=='mysql')
{
require_once MAX_PATH . '/lib/OA/Dal/Delivery/mysql.php';
}
else if($GLOBALS['_MAX']['CONF']['database']['type']=='pgsql')
{
require_once MAX_PATH . '/lib/OA/Dal/Delivery/pgsql.php';
}
else if($GLOBALS['_MAX']['CONF']['database']['type']=='mysqli')
{
require_once MAX_PATH . '/lib/OA/Dal/Delivery/mysqli.php';
}


function gen_uuid() {
    return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        // 32 bits for "time_low"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
 
        // 16 bits for "time_mid"
        mt_rand( 0, 0xffff ),
 
        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        mt_rand( 0, 0x0fff ) | 0x4000,
 
        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        mt_rand( 0, 0x3fff ) | 0x8000,
 
        // 48 bits for "node"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
    );
}
function sectooffset($seconds) {
  $t = round($seconds);
  return sprintf('%02d:%02d:%02d', ($t/3600),($t/60%60), $t%60);
}
function dynamic_url($aOut, $pluginType, $vastAdDescription,$format,$aBanner)
{
		/*IP address*/
			if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR']) {
				$clientIpAddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
			} else {
				$clientIpAddress = $_SERVER['REMOTE_ADDR'];
			}
		/*IP address*/
		/*VAST version*/	
		if($format=='vast3' || $format=='vast_pod')
		{$version='3';}
		else if($format=='vast2')
		{$version='2';}
		else if($format == 'vast2_wrapper')
		{$version='5';}
		else if($format == 'vast3_wrapper' || $format=='vast_pod_wrapper')
		{$version='6';}
		else if($format=='vast4' || $format == 'vast4_pod')
		{$version='7';}
		else if($format == 'vast4_wrapper' || $format=='vast4_pod_wrapper')
		{$version='8';}
		else if($format=='vast4.1' || $format == 'vast4.1_pod')
		{$version='11';}
		else if($format == 'vast4.1_wrapper' || $format=='vast4.1_pod_wrapper')
		{
			$version='12';
		}
		else
		{
			$version='-1';
		}
		/*VAST version*/
		$zoneid=$_REQUEST['zoneid'];
		if($format=='vast_pod_wrapper' || $format=='vast4_pod_wrapper' || $format=='vast4.1_pod_wrapper')
		{
		$adcount=!empty($GLOBALS['_MAX']['adpodcount'])?$GLOBALS['_MAX']['adpodcount']:'-1';
		}
		else
		{
		$adcount='-1';
		}
		if($pluginType=='djaxvastOverlay')
		{
		$url= $aBanner['vast_overlay_wrapper'];
		}
		else
		{
		$url = $aBanner['vast_wrapper_url'];
		}
		$transid=gen_uuid();
		$appbundle=!empty($_REQUEST['APPBUNDLE'])?$_REQUEST['APPBUNDLE']:'-1';
		$transcation_id=!empty($_REQUEST['transaction_id'])?$_REQUEST['transaction_id']:$transid;
		$milli_sec=!empty($aOut['vast_video_duration'])?sectooffset($aOut['vast_video_duration']):'-1';
		$ts=strtotime(date('Y-m-d', time()). '00:00:00');
		$timestamp=!empty($_GET['timestamp'])?$_GET['timestamp']:$ts;
		if(!empty($_GET['loc']))
		{
		$pageurl=$_GET['loc'];
		$urlparts = parse_url($pageurl);
		$domain = $urlparts['host'];
		}
		else
		{
		$pageurl='-1';
		$domain ='-1';
		}
		if(!empty($_GET['REGULATIONS']))
		{
		$regulation=$_GET['REGULATIONS'];
		}
		else
		{
		$regulation='-1';
		}
		if(!empty($_GET['GDPRCONSENT']))
		{
		$consent=$_GET['GDPRCONSENT'];
		}
		else
		{
		$consent='-1';
		}
		if(!empty($_GET['referrer']))
		{
		$referrer=$_GET['referrer'];
		}
		else
		{
		$referrer='-1';
		}
		if(!empty($_GET['sourcetag']))
		{
		$sourcetag=$_GET['sourcetag'];
		}
		else
		{
		$sourcetag='-1';
		}
		$url = @str_replace('[ZONEID]', urlencode($zoneid), $url);						
		$url = @str_replace('[WIDTH]', urlencode($_GET['width']),$url );	
		$url = @str_replace('[HEIGHT]', urlencode($_GET['height']), $url);
		$url = @str_replace('[REFERRER]', urlencode($referrer), $url);
		$url = @str_replace('[PAGEURL]', urlencode($pageurl), $url);
		$url = @str_replace('[DOMAIN]', urlencode($domain), $url);
		$url = @str_replace('[SOURCE]', urlencode($sourcetag), $url); 
		$url = @str_replace('[BANNERID]', urlencode($aOut['adid']), $url);
		$url = @str_replace('[TIMESTAMP]', urlencode($timestamp), $url);//pending
		$url = @str_replace('[CLICKURL]', urlencode($aOut['clickUrl']), $url);//pending
		$url = @str_replace('[CB]', urlencode(substr(md5(uniqid(time(), true)), 0, 10)), $url);//pending
		$url = @str_replace('[CACHEBUSTING]', urlencode(substr(md5(uniqid(time(), true)), 0, 10)), $url);//pending
		$url = @str_replace('[MEDIAPLAYHEAD]',$milli_sec, $url);//pending
		$pos = !empty($_REQUEST['position'])?$_REQUEST['position']:'-1';
		$url = @str_replace('[BREAKPOSITION]',$pos, $url);//pending//pos
		$url = @str_replace('[ADCOUNT]',$adcount, $url);//pending//pos
		$url = @str_replace('[ADTYPE]','video', $url);//pending//pos
		//$url = @str_replace('[ADCATEGORIES]','video', $url);
		//$url = @str_replace('[BLOCKEDADCATEGORIES]','video', $url);
		$url = @str_replace('[REGULATIONS]',$regulation, $url);
		$url = @str_replace('[GDPRCONSENT]',$consent, $url);
		
		$url = @str_replace('[COUNTRY]',urlencode($GLOBALS['_MAX']['CLIENT_GEO']['country_code']), $url);
		$url = @str_replace('[UA]', urlencode($_SERVER['HTTP_USER_AGENT']), $url);
		$url = @str_replace('[DEVICEUA]',urlencode($_SERVER['HTTP_USER_AGENT']), $url);
		$url = @str_replace('[DEVICEIP]',$clientIpAddress, $url);
		$url = @str_replace('[APPBUNDLE]',$appbundle, $url);
		$url = @str_replace('[VASTVERSIONS]',$version, $url);
		$url = @str_replace('[TRANSACTIONID]',$transcation_id, $url);	
		$url = @str_replace('[LOGURL]', urlencode($aOut['impressionUrl']), $url);
		
		$url = @str_replace('[DESCRIPTION_URL_UNESC]', urlencode($pageurl), $url);
		$url = @str_replace('[DESCRIPTION_URL_ESC]', urlencode($pageurl), $url);
		$url = @str_replace('[DESCRIPTION_URL_ESC_ESC]', urlencode($pageurl), $url); 
		
		$url = @str_replace('%%VIDEO_ID%%', urlencode($aOut['adid']), $url); 
		$url = @str_replace('%%VIDEO_TITLE%%', urlencode($aOut['name']), $url); 
		$url = @str_replace('%%VIDEO_DURATION%%', urlencode($milli_sec), $url); 
		$url = @str_replace('%%VIDEO_DURATION_SEC%%', urlencode($aOut['vast_video_duration']), $url);
		$url = @str_replace('%%USER_IP%%', urlencode($clientIpAddress), $url);
		if(!empty($GLOBALS['_MAX']['CLIENT_GEO']['latitude']) && !empty($GLOBALS['_MAX']['CLIENT_GEO']['longitude']))
		{
			$latlon=$GLOBALS['_MAX']['CLIENT_GEO']['latitude'].','.$GLOBALS['_MAX']['CLIENT_GEO']['longitude'];
		}
		else
		{
			$latlon='-1';
		}
		$url = @str_replace('%%VIDEO_METADATA:key%%', urlencode($vastparameters['vast_video_metadata']), $url);
		$url = @str_replace('%%LATITUDE%%', urlencode($GLOBALS['_MAX']['CLIENT_GEO']['latitude']), $url);
		$url = @str_replace('%%LONGITUDE%%', urlencode($GLOBALS['_MAX']['CLIENT_GEO']['longitude']), $url);
		$url = @str_replace('[LATLONG]',$latlon, $url);
		
		return $url;
}
function getVmapXMLHeader($charset,$version,$format)
{
  $header   = "<vmap:VMAP xmlns:vmap=\"http://www.iab.net/vmap-1.0\" version=\"1.0\">\n";
  return $header;
	
}
function getVmapXMLFooter($charset,$version,$format)
{
  $footer   = "</vmap:VMAP>\n";
  return $footer;
	
}
function djaxdeliverVastAd($pluginType, &$aBanner, $zoneId=0, $source='', $ct0='', $withText=false, $logClick=true, $logView=true, $useAlt=false, $richMedia=true, $loc, $referer)
{
    global $format;
    $format=$_REQUEST['format'];
    djaxextractVastParameters( $aBanner );
    $campaigndetails=djaxcampaignParameters($aBanner );
    $aOutputParams = array();
    $aOutputParams['format'] = $format;
    $aOutputParams['ad_id'] = $aBanner['ad_id'];
    $aOutputParams['videoPlayerSwfUrl'] = djaxgetVideoPlayerUrl('flowplayerSwfUrl');
    $aOutputParams['videoPlayerJsUrl'] = djaxgetVideoPlayerUrl('flowplayerJsUrl');
    $aOutputParams['videoPlayerRtmpPluginUrl'] = djaxgetVideoPlayerUrl('flowplayerRtmpPluginUrl');
    $aOutputParams['videoPlayerControlsPluginUrl'] = djaxgetVideoPlayerUrl('flowplayerControlsPluginUrl');

    if ( djaxgetVideoPlayerSetting('isAutoPlayOfVideoInOpenXAdminToolEnabled' )){
        $aOutputParams['isAutoPlayOfVideoInOpenXAdminToolEnabled'] = "true";
    } else {
        $aOutputParams['isAutoPlayOfVideoInOpenXAdminToolEnabled'] = "false";
    }
    if(!empty($aBanner['vast_thirdparty_impression'])) {
        $aOutputParams['thirdPartyImpressionUrl'] = $aBanner['vast_thirdparty_impression'];
    }

	if(!empty($aBanner['vast_thirdparty_clicktracking'])) 
	{
        $aOutputParams['vast_thirdparty_clicktracking'] = $aBanner['vast_thirdparty_clicktracking'];
    	}
	if(!empty($aBanner['vast_thirdparty_clickcustom'])) 
	{
        $aOutputParams['vast_thirdparty_clickcustom'] = $aBanner['vast_thirdparty_clickcustom'];
    	}
	if($campaigndetails['revenue_type']==1)
	{
		$pricingtype='CPM';
	}
	if($campaigndetails['revenue_type']==2)
	{
		$pricingtype='CPC';
	}
	if($campaigndetails['revenue_type']==3)
	{
		$pricingtype='CPA';
	}
	
	$aOutputParams['pricingtype']=$pricingtype;
	$aOutputParams['price']=$campaigndetails['revenue'];
   $aOutputParams['vast4_max_bitrate']=$aBanner['vast4_max_bitrate'];
   $aOutputParams['vast4_min_bitrate']=$aBanner['vast4_min_bitrate'];
   $aOutputParams['vast_thirdparty_companion_assetwidth']=$aBanner['vast_thirdparty_companion_assetwidth'];
   $aOutputParams['vast_thirdparty_companion_assetheight']=$aBanner['vast_thirdparty_companion_assetheight'];
   $aOutputParams['vast_thirdparty_companion_alttext']=$aBanner['vast_thirdparty_companion_alttext'];
     $aOutputParams['vast_thirdparty_companion_pxratio']=$aBanner['vast_thirdparty_companion_pxratio'];
     
     $aOutputParams['icon_url'] = $aBanner['vast_icon_filename'];
	$aOutputParams['icon_width'] = $aBanner['vast_icon_width'];
	$aOutputParams['icon_height'] = $aBanner['vast_icon_height'];
	$aOutputParams['icon_xposition'] = $aBanner['vast_icon_xposition'];
	$aOutputParams['icon_yposition'] = $aBanner['vast_icon_yposition'];
	$aOutputParams['icon_duration'] = $aBanner['vast_icon_duration'];
	$aOutputParams['icon_offset'] = $aBanner['vast_icon_offset'];
	$aOutputParams['icon_click_url'] = $aBanner['icon_click_url'];
	$aOutputParams['icon_track_url'] = $aBanner['icon_track_url'];

    djaxprepareCompanionBanner($aOutputParams, $aBanner, $zoneId, $source, $ct0, $withText, $logClick, $logView, $useAlt, $loc, $referer);
    djaxprepareVideoParams( $aOutputParams, $aBanner );
    djaxprepareOverlayParams( $aOutputParams, $aBanner );

    $player = "";
    djaxprepareTrackingParams( $aOutputParams, $aBanner, $zoneId, $source, $loc, $ct0, $logClick, $referer );

    if ( $format=='vmap' || $format == 'vast1'  || $format == 'vast2' ||  $format == 'vast3' || $format =='vast1_wrapper' || $format =='vast2_wrapper' || $format =='vast3_wrapper'  || $format == 'vast4' || $format == 'vast4_wrapper' || $format =='vast3_wrapper' || $format == 'vast4.1' || $format == 'vast4.1_wrapper' )
    { 
        if ( $pluginType == 'djaxvastInline' )
	{
		   if($format == 'vast1')
		   {
		   $player .= renderOutput_inlineVast1( $aOutputParams, $pluginType, "Inline Video Ad",$format,$aBanner);
		   }	
		   else if($format == 'vast2')
		   {
		   $player .= renderOutput_inlineVast2( $aOutputParams, $pluginType, "Inline Video Ad",$format,$aBanner);
		   }
		   else if($format == 'vast3')
		   { 
		   $player .= renderOutput_inlineVast3( $aOutputParams, $pluginType, "Inline Video Ad",$format,$aBanner,$sequence=false);
		   }
	       else if($format == 'vast4')
		   { 
		   $player .= renderOutput_inlineVast4( $aOutputParams, $pluginType, "Inline Video Ad",$format,$aBanner,$sequence=false);
		   }
		   else if($format =='vast4.1')
		   {
			$player .= renderOutput_inlineVast41( $aOutputParams, $pluginType, "Inline Video Ad",$format,$aBanner,$sequence=false);
		   }
		   else if($format =='vast1_wrapper')
		   {
			$player .= renderOutput_inlinewrapperVast1( $aOutputParams, $pluginType, "Inline Video Ad",$format,$aBanner,$sequence=false);
		   }
		   else if($format =='vast2_wrapper')
		   {
			$player .= renderOutput_inlinewrapperVast2( $aOutputParams, $pluginType, "Inline Video Ad",$format,$aBanner,$sequence=false);
		   }
		   else if($format =='vast3_wrapper')
		   {
			$player .= renderOutput_inlinewrapperVast3( $aOutputParams, $pluginType, "Inline Video Ad",$format,$aBanner,$sequence=false);
		   }
		   else if($format =='vast4_wrapper')
		   {
			$player .= renderOutput_inlinewrapperVast4( $aOutputParams, $pluginType, "Inline Video Ad",$format,$aBanner,$sequence=false);
		   }  	   
		   else if($format =='vast4.1_wrapper')
		   {
			$player .= renderOutput_inlinewrapperVast41( $aOutputParams, $pluginType, "Inline Video Ad",$format,$aBanner,$sequence=false);
		   }  	
		   else if($format=='vmap')
		   {
			  $player .= renderOutput_inlinevmapVast3( $aOutputParams, $pluginType, "Inline Video Ad",$format,$aBanner);
			   
		   }
		   
        }
	else if ( $pluginType == 'djaxvastOverlay' ) 
	{

           if($format == 'vast1')
		   {
		   $player .= renderOutput_inlineVast1( $aOutputParams, $pluginType, "Overlay Video Ad",$format,$aBanner);
		   }	
		   else if($format == 'vast2')
		   {
		   $player .= renderOutput_nonlinearVast2( $aOutputParams, $pluginType, "Overlay Video Ad",$format,$aBanner);
		   }
		   else if($format == 'vast3')
		   {
		   $player .= renderOutput_nonlinearVast3( $aOutputParams, $pluginType, "Overlay Video Ad",$format,$aBanner);
		   }
		    else if($format == 'vast4')
		   {
		   $player .= renderOutput_nonlinearVast4( $aOutputParams, $pluginType, "Overlay Video Ad",$format,$aBanner);
		   }
		   else if($format == 'vast4.1')
		   { 
		   $player .= renderOutput_nonlinearVast41( $aOutputParams, $pluginType, "Overlay Video Ad",$format,$aBanner);
		   }
		   else if($format =='vast1_wrapper')
		   {
			$player .= renderOutput_nonlinearwrapperVast1( $aOutputParams, $pluginType, "Overlay Video Ad",$format,$aBanner);
		   }
		   else if($format =='vast2_wrapper')
		   {
			$player .= renderOutput_nonlinearwrapperVast2( $aOutputParams, $pluginType, "Overlay Video Ad",$format,$aBanner);
		   }
		   else if($format =='vast3_wrapper')
		   {
			$player .= renderOutput_nonlinearwrapperVast3( $aOutputParams, $pluginType, "Overlay Video Ad",$format,$aBanner);
		   }
		   else if($format =='vast4_wrapper')
		   {
			$player .= renderOutput_nonlinearwrapperVast4( $aOutputParams, $pluginType, "Overlay Video Ad",$format,$aBanner);
		   }
		    else if($format =='vast4.1_wrapper')
		   {
			$player .= renderOutput_nonlinearwrapperVast41( $aOutputParams, $pluginType, "Overlay Video Ad",$format,$aBanner);
		   }
        }
	else 
	{
            throw new Exception("Uncatered for vast plugintype|$pluginType|");
        }
    } else {


        if ( $pluginType == 'djaxvastInline' ){
            $player .= djaxrenderPlayerInPage($aOutputParams);
            $player .= djaxrenderCompanionInAdminTool($aOutputParams);
        } else if ( $pluginType == 'djaxvastOverlay' ) {
            $player .= djaxrenderOverlayInAdminTool($aOutputParams, $aBanner);
            $player .= djaxrenderCompanionInAdminTool($aOutputParams);
            $player .= djaxrenderPlayerInPage($aOutputParams);
        } else {
            throw new Exception("Uncatered for vast plugintype|$pluginType|");
        }
    }
    return $player;
}

function djaxgetVastXMLHeader($charset,$version,$format)
{
	//~ print_r($version);
	//~ exit;
	$header   = "<?xml version=\"1.0\" encoding=\"".djaxxmlspecialchars($charset)."\"?>\n";

    	if($format=='vast1' || $format=='vast1_wrapper')
	{
    	$header  .= "<VideoAdServingTemplate xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:noNamespaceSchemaLocation=\"vast.xsd\">\n";
	}
	else
	{
	    $header  .= "<VAST version=\"$version\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:noNamespaceSchemaLocation=\"vast.xsd\">\n";
	}
    return $header;
}

function djaxgetVastXMLFooter($format)
{
	if($format=='vast1' || $format=='vast1_wrapper')
	{
	$footer = "</VideoAdServingTemplate>\n";
	}
	else
	{
	$footer = "</VAST>\n";
	}
	return $footer;
}

function djaxgetVideoPlayerUrl($parameterId)
{
    static $aDefaultPlayerFiles = array(
        'flowplayerSwfUrl'=> "flowplayer/3.1.1/flowplayer-3.1.1.swf",
        'flowplayerJsUrl'=> "flowplayer/3.1.1/flowplayer-3.1.1.min.js",
        'flowplayerControlsPluginUrl' =>  "flowplayer/3.1.1/flowplayer.controls-3.1.1.swf",
        'flowplayerRtmpPluginUrl'=> "flowplayer/3.1.1/flowplayer.rtmp-3.1.0.swf",
    );

    $conf = $GLOBALS['_MAX']['CONF'];

    // you can set this by adding a setting under [vastServeVideoPlayer] in the hostname.conf.php config file
    $fullFileLocationUrl = $GLOBALS['_MAX']['SSL_REQUEST'] ? 'https://' . $conf['webpath']['deliverySSL'] : 'http://' .  $conf['webpath']['delivery'];

    $fullFileLocationUrl .= "/fc.php?script=deliveryLog:vastServeVideoPlayer:player&file_to_serve=";

    if(isset( $conf['vastServeVideoPlayer'][$parameterId])) {
        $configFileLocation = $conf['vastServeVideoPlayer'][$parameterId];
        $fullFileLocationUrl .= $configFileLocation;
    } else {
        if(!isset($aDefaultPlayerFiles[$parameterId])) {
            throw new Exception("Uncatered for setting type in getVideoPlayerUrl() |$parameterId| in <pre>" . print_r( $aDefaultPlayerFiles, true) . '</pre>' );
        } else {
            $fullFileLocationUrl .= $aDefaultPlayerFiles[$parameterId];
        }
    }
    return $fullFileLocationUrl;
}

function djaxextractVastParameters( &$aBanner )
{
	$query="select * from rv_djaxbanner_vast_element where banner_id='".$aBanner['ad_id']."'";
	$rvastInfo = OA_Dal_Delivery_query($query);
    $avastInfo = OA_Dal_Delivery_fetchAssoc($rvastInfo);
        $vastVariables = $avastInfo;
        $aBanner = array_merge($aBanner, $vastVariables);
      return $aBanner;
}
function djaxcampaignParameters( &$aBanner )
{
	$query="select * from rv_campaigns where campaignid='".$aBanner['placement_id']."'";
	$rcampaignInfo = OA_Dal_Delivery_query($query);
    $acampaignInfo = OA_Dal_Delivery_fetchAssoc($rcampaignInfo);
    return $acampaignInfo;
}
function djaxprepareVideoParams(&$aOutputParams, $aBanner)
{
	
	$vastdata=unserialize($aBanner['parameters']);
	//~ print_r( $vastdata); exit;
	/*DAC015*/
	if($vastdata['vast_video_delivery']=='vast')
	{
		
 	$aOutputParams['vast_wrapper']=$vastdata['vast_wrapper_url'];
	$aOutputParams['vastversion']=$vastdata['vast_version'];
	}
	else if($vastdata['vast_overlay_format']=='wrapper_overlay')
	{
		
	$aOutputParams['vast_wrapper']=$vastdata['vast_overlay_wrapper'];
	$aOutputParams['vastversion']=$vastdata['vast_overlay_version'];
	}
	$aOutputParams['vast_video_skip_duration']=$vastdata['vast_video_skip_duration'];
	$aOutputParams['vast_video_skip_progress_duration']=$vastdata['vast_video_skip_progress_duration'];

	//$aOutputParams['vpaid_enable']=$aBanner['vpaid_enable'];
	/*DAC015*/
    $aOutputParams['name'] = $aBanner['name'];

    if(isset($aBanner['internal_file'] ) && $aBanner['internal_file']) {	
       $aAdminParamsNotUsed = array();
       djaxparseVideoUrl($aBanner, $aOutputParams, $aAdminParamsNotUsed );
       $aOutputParams['vastVideoDuration'] = djaxsecondsToVASTDuration( $aBanner['vast_video_duration'] );
       $aOutputParams['vastVideoBitrate'] = $aBanner['vast_video_bitrate'];
       $aOutputParams['vastVideoWidth']= $aBanner['vast_video_width'];
       $aOutputParams['vastVideoHeight'] = $aBanner['vast_video_height'];
       $aOutputParams['vastVideoId'] =  $aBanner['bannerid'];
       $aOutputParams['vastVideoType'] = $aBanner['vast_video_type'];
       $aOutputParams['vastVideoDelivery'] = $aBanner['vast_video_delivery']; 
    }else{
		if(isset($aBanner['vast_video_outgoing_filename'] ) && $aBanner['vast_video_outgoing_filename']) {	
		   $aAdminParamsNotUsed = array();
		   djaxparseVideoUrl($aBanner, $aOutputParams, $aAdminParamsNotUsed );
		   $aOutputParams['vastVideoDuration'] = djaxsecondsToVASTDuration( $aBanner['vast_video_duration'] );
		   $aOutputParams['vastVideoBitrate'] = $aBanner['vast_video_bitrate'];
		   $aOutputParams['vastVideoWidth']= $aBanner['vast_video_width'];
		   $aOutputParams['vastVideoHeight'] = $aBanner['vast_video_height'];
		   $aOutputParams['vastVideoId'] =  $aBanner['bannerid'];
		   $aOutputParams['vastVideoType'] = $aBanner['vast_video_type'];
		   $aOutputParams['vastVideoDelivery'] = $aBanner['vast_video_delivery']; 
		}
   }
}

function djaxprepareOverlayParams(&$aOutputParams, $aBanner)
{
    $aOutputParams['overlayHeight'] = $aBanner['vast_overlay_height'];
    $aOutputParams['overlayWidth'] = $aBanner['vast_overlay_width'];
    $aOutputParams['overlayDestinationUrl'] = $aBanner['url'];
    if (isset($aBanner['htmltemplate'])) {
        $aOutputParams['overlayMarkupTemplate'] = $aBanner['htmltemplate'];
    }
    if(!empty($aBanner['filename'])) {
        $aOutputParams['overlayFilename'] = $aBanner['filename'];
    }
    if(!empty($aBanner['contenttype'])) {
        $aOutputParams['overlayContentType'] = $aBanner['vast_creative_type'];
    }
    $aOutputParams['overlayFormat'] = $aBanner['vast_overlay_format'];
    switch($aOutputParams['overlayFormat']) {
        case VAST_OVERLAY_FORMAT_TEXT:
            $aOutputParams['overlayTextTitle'] = $aBanner['vast_overlay_text_title'];
            $aOutputParams['overlayTextDescription'] = $aBanner['vast_overlay_text_description'];
            $aOutputParams['overlayTextCall'] = $aBanner['vast_overlay_text_call'];
            $aOutputParams['overlayHeight'] = VAST_OVERLAY_DEFAULT_HEIGHT;
            $aOutputParams['overlayWidth'] = VAST_OVERLAY_DEFAULT_WIDTH;
        break;

        case VAST_OVERLAY_FORMAT_HTML:
            $aOutputParams['overlayHeight'] = VAST_OVERLAY_DEFAULT_HEIGHT;
            $aOutputParams['overlayWidth'] = VAST_OVERLAY_DEFAULT_WIDTH;
        break;
    }
//    var_dump($aBanner);
//    var_dump($aOutputParams);exit;
}

function djaxprepareCompanionBanner(&$aOutputParams, $aBanner, $zoneId=0, $source='', $ct0='', $withText=false, $logClick=true, $logView=true, $useAlt=false, $loc, $referer)
{
    // If we have a companion banner to serve
    if ( isset( $aBanner['vast_companion_banner_id']  )
        && ($aBanner['vast_companion_banner_id'] != 0) )
    {
        $companionBannerId = $aBanner['vast_companion_banner_id'];

        // VAST supports the concept of an ad having multlple  companions returned(each with different formats and sizes
        // its then the role of the player to choose the appropriate companion ad to display based on users screen size etc
        // However for now we just focus on serving a single companion banner. Also in vast - I think - the player should be adding the click tracking
        // for now we are doing this server side.
        global $context;

        if (isset($context) && !is_array($context)) {
            $context = MAX_commonUnpackContext($context);
        }
        if (!is_array($context)) {
            $context = array();
        }
        $companionOutput = MAX_adSelect("bannerid:$companionBannerId", '', "", $source, $withText, '', $context, true, $ct0, $loc, $referer);

        //$aBanner = _adSelectDirect("bannerid:$companionBannerId", '', $context, $source);
        //$companionOutput = MAX_adRender($aBanner, 0, '', '', '', true, '', false, false);
        //$aOutputParams['companionId'] = $companionBannerId;
        if ( !empty($companionOutput['html'] )){
            // We only regard  a companion existing, if we have some markup to output
            $html = $companionOutput['html'];

            // deal with the case where the companion code itself contains a CDATA
            $html = str_replace(']]>', ']]]]><![CDATA[>', $html);
            $aOutputParams['companionMarkup'] = $html;
	    $aOutputParams['companionbannerid'] = $companionBannerId;
            $aOutputParams['companionWidth'] = $companionOutput['width'];
            $aOutputParams['companionHeight'] = $companionOutput['height'];
            $aOutputParams['companionClickUrl'] = $companionOutput['url'];
		if(!empty($aBanner['vast_thirdparty_companion_clicktracking'])) 
		{
		$aOutputParams['vast_thirdparty_companion_clicktracking'] = $aBanner['vast_thirdparty_companion_clicktracking'];
	    	}
		if(!empty($aBanner['vast_thirdparty_companion_expandedwidth'])) 
		{
		$aOutputParams['vast_thirdparty_companion_expandedwidth'] = $aBanner['vast_thirdparty_companion_expandedwidth'];
	    	}
		if(!empty($aBanner['vast_thirdparty_companion_expandedheight'])) 
		{
		$aOutputParams['vast_thirdparty_companion_expandedheight'] = $aBanner['vast_thirdparty_companion_expandedheight'];
	    	}
        }
    }
}

function djaxprepareTrackingParams(&$aOutputParams, $aBanner, $zoneId, $source, $loc, $ct0, $logClick, $referer)
{
    $conf = $GLOBALS['_MAX']['CONF'];
    $aOutputParams['impressionUrl'] =  _adRenderBuildLogURL($aBanner, $zoneId, $source, $loc, $referer, '&');
    if ($aOutputParams['format'] == 'vmap' || $aOutputParams['format'] == 'vast1' || $aOutputParams['format'] == 'vast2' || $aOutputParams['format'] == 'vast3' || $aOutputParams['format'] =='vast1_wrapper' || $aOutputParams['format'] == 'vast2_wrapper' || $aOutputParams['format'] == 'vast3_wrapper' || $aOutputParams['format'] == 'vast_pod' || $aOutputParams['format'] == 'vast_pod_wrapper' || $aOutputParams['format'] == 'vast4' ||  $aOutputParams['format'] == 'vast4_wrapper' ||  $aOutputParams['format'] == 'vast4_pod' || $aOutputParams['format'] == 'vast4_pod_wrapper'
    || $aOutputParams['format'] == 'vast4.1' ||  $aOutputParams['format'] == 'vast4.1_wrapper' ||  $aOutputParams['format'] == 'vast4.1_pod' || $aOutputParams['format'] == 'vast4.1_pod_wrapper'){

		if(!empty($aBanner['bannerid']))
		{
			$djbannerid=$aBanner['bannerid'];
			
		}
		else
		{
			$djbannerid=$aBanner['ad_id'];
		}
		$deliverypath=MAX_commonGetDeliveryUrl($conf['file']['frontcontroller']);
		$getfcpath=str_replace('www/delivery/','',$deliverypath);
        $trackingUrl = $getfcpath."?script=rmvideoAds:rmvastEvent&bannerid={$djbannerid}&zoneid={$zoneId}";
        if (!empty($source)) {
            $trackingUrl .= "&source=".urlencode($source);
        }
        $aOutputParams['b_id']=$djbannerid;
        $aOutputParams['track_url']=$trackingUrl;
        $aOutputParams['trackUrlStart'] = $trackingUrl . '&event=start';
        $aOutputParams['trackUrlMidPoint'] = $trackingUrl . '&event=midpoint';
        $aOutputParams['trackUrlFirstQuartile'] = $trackingUrl . '&event=firstquartile';
        $aOutputParams['trackUrlThirdQuartile'] = $trackingUrl . '&event=thirdquartile';
        $aOutputParams['trackUrlComplete'] = $trackingUrl . '&event=complete';
        $aOutputParams['trackUrlMute'] = $trackingUrl . '&event=mute';
        $aOutputParams['trackUrlPause'] = $trackingUrl . '&event=pause';
        $aOutputParams['trackReplay'] = $trackingUrl . '&event=replay';
        $aOutputParams['trackUrlFullscreen'] = $trackingUrl . '&event=fullscreen';
        $aOutputParams['trackUrlStop'] = $trackingUrl . '&event=stop';
        $aOutputParams['trackUrlUnmute'] = $trackingUrl . '&event=unmute';
        $aOutputParams['trackUrlResume'] = $trackingUrl . '&event=resume';
        $aOutputParams['trackUrlloaded'] = $trackingUrl . '&event=loaded';//VAST 4.1
        $aOutputParams['trackUrlnotUsed'] = $trackingUrl . '&event=notUsed';//VAST 4.1
	/*DAC015-Nonliner tracking*/
		$aOutputParams['trackUrlrewind'] = $trackingUrl . '&event=rewind';
		$aOutputParams['trackUrlcreativeView'] = $trackingUrl . '&event=creativeView';
        $aOutputParams['trackUrlexpand'] = $trackingUrl . '&event=expand';
        $aOutputParams['trackUrlcollapse'] = $trackingUrl . '&event=collapse';
        $aOutputParams['trackUrlacceptInvitation'] = $trackingUrl . '&event=acceptInvitation';
        $aOutputParams['trackUrlclose'] = $trackingUrl . '&event=close';
		$aOutputParams['trackUrlprogress'] = $trackingUrl . '&event=progress';
        $aOutputParams['trackUrlskip'] = $trackingUrl . '&event=skip';
		$aOutputParams['trackUrlacceptInvitationLinear'] = $trackingUrl . '&event=acceptInvitationLinear';
		$aOutputParams['trackUrlacceptInvitation'] = $trackingUrl . '&event=acceptInvitation';
		$aOutputParams['trackUrlcloseLinear'] = $trackingUrl . '&event=closeLinear';//VAST 4.1&VAST 3(Deprecated in VAST 4.0)
		$aOutputParams['trackUrlexitFullscreen'] = $trackingUrl . '&event=exitfullscreen';
	/*Viewable impression tracking*/
		$aOutputParams['trackUrlviewable'] = $trackingUrl . '&event=viewable';
		$aOutputParams['trackUrlnotviewable'] = $trackingUrl . '&event=notviewable';
		$aOutputParams['trackUrlundetermined'] = $trackingUrl . '&event=viewundetermined';
	/*DAC015-Nonliner tracking*/
	/*VMAP tracking*/
	 $aOutputParams['trackUrlbreakStart'] = $trackingUrl . '&event=breakStart';
     $aOutputParams['trackUrlbreakEnd'] = $trackingUrl . '&event=breakEnd';
     $aOutputParams['trackUrlerror'] = $trackingUrl . '&event=error';
	 /*VMAP tracking*/
	/*Additional Event tracking*/
		$aOutputParams['trackUrltimeSpentViewing'] = $trackingUrl . '&event=timeSpentViewing';
		$aOutputParams['trackUrlotherAdInteraction'] = $trackingUrl . '&event=otherAdInteraction';
		$aOutputParams['trackUrlplayerExpand'] = $trackingUrl . '&event=playerExpand';
		$aOutputParams['trackUrlplayerCollapse'] = $trackingUrl . '&event=playerCollapse';
		$aOutputParams['trackUrliconimpression'] = $trackingUrl . '&event=iconimpression';
        $aOutputParams['trackUrliconclick'] = $trackingUrl . '&event=iconclick';
        $aOutputParams['trackUrlicontrack'] = $trackingUrl . '&event=icontrack';
		$aOutputParams['trackUrloverlayViewDuration'] = $trackingUrl . '&event=overlayViewDuration';
        $aOutputParams['trackUrlotherAdInteraction'] = $trackingUrl . '&event=otherAdInteraction';
        $aOutputParams['vastVideoClickThroughUrl'] = _djaxadRenderBuildVideoClickThroughUrl($aBanner, $zoneId, $source, $ct0 );
    }
	$conf = $GLOBALS['_MAX']['CONF'];
	if ($GLOBALS['_MAX']['SSL_REQUEST']) {
	$proto = 'https://';
	}
	else
	{
	$proto = 'http://';
	}
    $aOutputParams['clickUrl'] = _adRenderBuildClickUrl($aBanner, $zoneId, $source, $ct0, $logClick);	
    $aOutputParams['errorUrl']=$proto.$conf['webpath']['delivery']."/errorcode.php?zoneid={$zoneId}&errorcode=[ERRORCODE]";
}

/**
 * This function builds the Click through URL for this ad
 *
 * @param array   $aBanner      The ad-array for the ad to render code for
 * @param int     $zoneId       The zone ID of the zone used to select this ad (if zone-selected)
 * @param string  $source       The "source" parameter passed into the adcall
 * @param string  $ct0          The 3rd party click tracking URL to redirect to after logging
 * @param bookean $logClick     Should this click be logged (clicks in admin should not be logged)
 *
 * @return string The click URL
 */
function _djaxadRenderBuildVideoClickThroughUrl($aBanner, $zoneId=0, $source='', $ct0='', $logClick=true){

    // We dont pass $aBanner by reference - so the changes to this $aBanner are lost - which is a good thing
    // we need the url attribute of aBanner to contain the url we want created
    $clickUrl = '';
    if(!empty($aBanner['vast_video_clickthrough_url'])) {
        $aBanner['url'] = $aBanner['vast_video_clickthrough_url'];
        $clickUrl = _adRenderBuildClickUrl($aBanner, $zoneId, $source, $ct0, $logClick);
    }
    return $clickUrl;
}



function djaxgetImageUrlFromFilename($filename)
{
    return _adRenderBuildImageUrlPrefix() . "/" . $filename;
}



function djaxrenderPlayerInPage($aOut)
{

	$player = "";
	if(empty($aOut['vast_wrapper']))
	{ 
	if ( isset($aOut['fullPathToVideo'] ) ){
		$player = <<<PLAYER
			<h3>Video ad preview</h3>
			<script type="text/javascript" src="{$aOut['videoPlayerJsUrl']}"></script>
			<style>
			a.player {
			    display:block;
			    width:640px;
			    margin:25px 0;
			    text-align:center;
			}
			</style>

			<a class="player" id="player"></a>
PLAYER;

		// encode data before echoing to the browser to prevent xss
		$aOut['videoFileName'] = djaxencodeUserSuppliedData( $aOut['videoFileName'] );
        $aOut['videoNetConnectionUrl'] = djaxencodeUserSuppliedData( $aOut['videoNetConnectionUrl'] );

		//~ $httpPlayer = <<<HTTP_PLAYER

		    //~ <!-- http flowplayer setup -->
            //~ <script language="JavaScript">
            //~ flowplayer("a.player", "${aOut['videoPlayerSwfUrl']}", {
               //~ playlist: [ '${aOut['videoFileName']}' ],
                //~ clip: {
                       //~ autoPlay: ${aOut['isAutoPlayOfVideoInOpenXAdminToolEnabled']}
               //~ },
               //~ plugins: {

                   //~ controls: {
                        //~ url: escape('${aOut['videoPlayerControlsPluginUrl']}')
                   //~ }
               //~ }

            //~ });
            //~ </script>
//~ HTTP_PLAYER;
        $httpPlayer = <<<HTTP_PLAYER

            <!-- HTML5 Mezzanine setup -->
            <script type="text/javascript">
                (function (p) {
                    p.html('<video controls  width="300" height="300" name="media"><source src="{$aOut['fullPathToVideo']}" type="video/mp4"></video>');
                })($("#player"));
            </script>

HTTP_PLAYER;
        $rtmpPlayer = <<<RTMP_PLAYER

            <!-- rmtp flowplayer setup -->
            <script language="JavaScript">
            flowplayer("a.player", "${aOut['videoPlayerSwfUrl']}", {
               clip: {
                       url: '${aOut['videoFileName']}',
                       provider: 'streamer',
                       autoPlay: ${aOut['isAutoPlayOfVideoInOpenXAdminToolEnabled']}
               },

               plugins: {
                   streamer: {
                        // see http://flowplayer.org/forum/8/15861 for reason I use encode() function
                        url: escape('${aOut['videoPlayerRtmpPluginUrl']}'),
                        netConnectionUrl: '${aOut['videoNetConnectionUrl']}'
                   },
                   controls: {
                        url: escape('${aOut['videoPlayerControlsPluginUrl']}')
                   }
               }

            });
            </script>
RTMP_PLAYER;

        $webmPlayer = <<<WEBM_PLAYER

            <!-- HTML5 Webm setup -->
            <script type="text/javascript">
                (function (p) {
                    p.html('<video width="640" height="360" controls><source src="{$aOut['fullPathToVideo']}" type="{$aOut['vastVideoType']}"/>You need an HTML5 compatible player, sorry</video>');
                })($("#player"));
            </script>

WEBM_PLAYER;
        $mezzaninePlayer = <<<MEZZ_PLAYER

            <!-- HTML5 Mezzanine setup -->
            <script type="text/javascript">
                (function (p) {
                    p.html('<video controls  width="400" height="400" autoplay name="media"><source src="{$aOut['fullPathToVideo']}" type="video/mp4"></video>');
                })($("#player"));
            </script>

MEZZ_PLAYER;

        if ( $aOut['videoDelivery'] == 'player_in_http_mode' ){
            if ($aOut['vastVideoType'] == 'video/webm') {
                $player .= $webmPlayer;
            } else {
                $player .= $httpPlayer;
            }
        }
        else if ( $aOut['videoDelivery'] == 'player_in_rtmp_mode' ) {
            $player .= $rtmpPlayer;
        }
        else if ( $aOut['videoDelivery'] == 'mezzanine_mode' ) {
            $player .= $mezzaninePlayer;
        }
        
        else {
            // default to rtmp play format
            $player .= $rtmpPlayer;
        }
    }
}
    else 
{

	$player.=renderVastWrapper($aOut);
	
}
    return $player;
}

function djaxrenderCompanionInAdminTool($aOut)
{
    $player = "";
    if(isset($aOut['companionMarkup'])) {
        $player .=  "<h3>Companion Preview (" .$aOut['companionWidth'] . "x" . $aOut['companionHeight'] . ")</h3>";
        $player .= $aOut['companionMarkup'];
        /*$aBanner = Admin_DA::getAd($aOut['companionId']);
        $aBanner['bannerid'] = $aOut['companionId'];
        $bannerCode = MAX_adRender($aBanner, 0, '', '', '', true, '', false, false);
        $player .=  "<h3>Companion Preview</h3>";
        $player .= "This companion banner will appear during the duration of the Video Ad in the DIV specified in the video player plugin configuration. ";
        if(!empty($aOut['companionWidth'])) {
            $player .= " It has the following dimensions: width = ". $aOut['companionWidth'] .", height = ".$aOut['companionHeight'] .". ";
        }
        $player .= "<a href='".VideoAdsHelper::djagetHelpLinkVideoPlayerConfig()."' target='_blank'>Learn more</a><br/><br/>";
        $player .= $bannerCode;*/
        $player .= "<br>";
    }
    return $player;
}

function djaxrenderOverlayInAdminTool($aOut, $aBanner)
{

    $title =  "Overlay Preview";
    $borderStart = "<div style='color:black;text-decoration:none;border:1px solid black;padding:15px;'>";
    $borderEnd = "</div>";
    $htmlOverlay = '';
    switch($aOut['overlayFormat']) {
        case VAST_OVERLAY_FORMAT_HTML:
            $htmlOverlay = $borderStart . $aOut['overlayMarkupTemplate'] . $borderEnd;
        break;

        case VAST_OVERLAY_FORMAT_IMAGE:
            $title = "Image Overlay Preview";
            $imagePath = djaxgetImageUrlFromFilename($aOut['overlayFilename']);
            $htmlOverlay = "<img border='0' src='$imagePath' />";
        break;

        case VAST_OVERLAY_FORMAT_SWF:
            $title = "SWF Overlay Preview";
            // we need to set a special state for adRenderFlash to work (which tie us to this implementation...)
            $aBanner['type'] = 'web';
            $aBanner['width'] = $aOut['overlayWidth'];
            $aBanner['height'] = $aOut['overlayHeight'];
            $htmlOverlay = _adRenderFlash($aBanner, $zoneId=0, $source='', $ct0='', $withText=false, $logClick=false, $logView=false);
        break;

        case VAST_OVERLAY_FORMAT_TEXT:
            $title = "Text Overlay Preview";
            $overlayTitle = $aOut['overlayTextTitle'];
            $overlayDescription = str_replace("\n","<br/>",$aOut['overlayTextDescription']);
            $overlayCall = $aOut['overlayTextCall'];
            $htmlOverlay = "
            	$borderStart
                    <div style='font-family:arial;font-size:18pt;font-weight:bold;'>$overlayTitle </div>
                    <div style='font-family:arial;font-size:15pt;'>$overlayDescription</div>
                    <div style='font-family:arial;font-size:15pt;font-weight:bold;color:orange;'>$overlayCall</div>
                $borderEnd
            ";
        break;
    }


    $htmlOverlayPrepend = 'The overlay will appear on top of video content during video play.';

    switch($aOut['overlayFormat']) {
        case VAST_OVERLAY_FORMAT_IMAGE:
        case VAST_OVERLAY_FORMAT_SWF:
            $htmlOverlayPrepend .= " This overlay has the following dimensions: width = " . $aOut['overlayWidth'] . ", height = " . $aOut['overlayHeight'] . ".";
        break;
    }
    if ($aOut['overlayDestinationUrl']) {
        $htmlOverlayPrepend .= ' In the video player, this overlay will be clickable.';
        $htmlOverlay =  "<a target=\"_blank\" href=\"${aOut['overlayDestinationUrl']}\"> {$htmlOverlay}</a>";
    }

    $htmlOverlay = $htmlOverlayPrepend . '<br/><br/>' . $htmlOverlay;

    $player = "<h3>$title</h3>";
    $player .= $htmlOverlay;
    $player .= "<br>";
    return $player;
}


//preview third party MANIVASAKI
function renderVastWrapper($aOut)
{
	
	$conf = $GLOBALS['_MAX']['CONF'];
$previewurl=$conf['defaultpreview']['defaultpreview'];
	 $vast_wrapper=$aOut['vast_wrapper'];
	

	$pvideo=$previewurl;

	 	$player = "";
		$player = <<<PLAYER
			<h3>Video ad preview</h3>
			<script type="text/javascript" src="{$aOut['videoPlayerJsUrl']}"></script>
			<style>
			a.player {
			    display:block;
			    width:640px;		    
			    margin:25px 0;
			    text-align:center;
			}
			</style>
			<a class="player" id="player"></a>
			
PLAYER;


			$httpPlayer = <<<HTTP_PLAYER
	
		<script type="text/javascript" src="jwplayer.js"></script>
		<script type="text/javascript">jwplayer.key = "XbCmwICYTU4sZT3u37Ti4UR+d5ZnEH7boZW3TA==";</script>
           <script language="JavaScript">
            

				jwplayer("player").setup({
				file: "$pvideo",
				width: "640",
				height: "360",
				autostart: "true",
				primary: "html5",
				skin: {active: "#0390a5", inactive: "#ffffff", name: "glow", background: "#303440" },
				mute: "true",
				repeat: "false",
				stretching: "exactfit",
				title: "Ads",
				description: "Inline ads",
				advertising: {
				client: "vast",
				companiondiv: { id: "companion", width: "300", height: "250" },
				schedule: {
				preroll: {
					offset: "pre",
					tag:"$vast_wrapper",
	
				},
				
						
				},

				
				}});
           

            </script>
HTTP_PLAYER;
	    
                $player .= $httpPlayer;

    return $player;
}


// if bcmath php extension not installed
if ( !(function_exists('bcmod'))) {
    /**
     * for extremely large numbers of seconds this will break
     * but for video we will never have extremely large numbers of seconds
     *
     * see http://www.php.net/manual/en/language.operators.arithmetic.php
     **/
    function bcmod( $x, $y )
    {
        $mod= $x % $y;

        return (int)$mod;
    }

}// end of if bcmath php extension not installed

 if (!function_exists('djaxsecondsToVASTDuration')) {
function djaxsecondsToVASTDuration($seconds)
{
    $hours = intval(intval($seconds) / 3600);
    $minutes = bcmod((intval($seconds) / 60),60);
    $seconds = bcmod(intval($seconds),60);
    $ret = sprintf( "%02d:%02d:%02d", $hours, $minutes, $seconds );
    return $ret;
}
}
