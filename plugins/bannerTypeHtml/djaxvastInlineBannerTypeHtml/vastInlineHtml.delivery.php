<?php

if ($GLOBALS['_MAX']['SSL_REQUEST']) {
					$djprotocol='https://';
					}
					else
					{
					$djprotocol='http://';
					}
if(isset($_REQUEST['loc']))
{
	$var=$_REQUEST['loc'];
	$parsed = parse_url($var);
	if(empty($parsed['scheme']))
	{
	$loc=$djprotocol.$_REQUEST['loc'];
	}
	else
	{
	$loc=$_REQUEST['loc'];
	}
}
if(!empty($loc))
{
header('Access-Control-Allow-Origin: '.$loc);
}
else
{
header('Access-Control-Allow-Origin:*');
}
header('Access-Control-Allow-Credentials: true');


$format=$_REQUEST['format'];

if($format == 'vast4.1' || $format == 'vast4.1_pod' || $format == 'vast4.1_wrapper' || $format=='vast4.1_pod_wrapper')
{
	if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR']) {
    $clientIpAddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $clientIpAddress = $_SERVER['REMOTE_ADDR'];
}
header('X-Device-IP:'.$clientIpAddress);
header('X-Device-User-Agent:'.$_SERVER['HTTP_USER_AGENT']);
header('X-Device-Accept-Language:'.$_SERVER['HTTP_ACCEPT_LANGUAGE']);
header('X-Device-Referer:'.$_REQUEST['loc']);
}

MAX_commonRegisterGlobalsArray(array('format', 'clientdebug'));
require_once MAX_PATH . '/plugins/bannerTypeHtml/djaxvastInlineBannerTypeHtml/commonDelivery.php';
require_once MAX_PATH . '/plugins/bannerTypeHtml/djaxvastInlineBannerTypeHtml/vastinline.php';
require_once MAX_PATH . '/plugins/bannerTypeHtml/djaxvastInlineBannerTypeHtml/vastwrapper_inline.php';
if(!is_callable('MAX_adSelect')) {
    require_once MAX_PATH . '/lib/max/Delivery/adSelect.php';
}

function Plugin_BannerTypeHTML_djaxvastInlineBannerTypeHtml_vastHtml_delivery()
{
    return true;
}

function Plugin_BannerTypeHTML_djaxvastInlineBannerTypeHtml_vastHtml_Delivery_postAdRender()
{
    return true;
}


function Plugin_bannerTypeHtml_djaxvastInlineBannerTypeHtml_vastInlineHtml_Delivery_adRender(&$aBanner, $zoneId=0, $source='', $ct0='', $withText=false, $logClick=true, $logView=true, $useAlt=false, $richMedia=true, $loc, $referer)
{
	  return djaxdeliverVastAd('djaxvastInline', $aBanner, $zoneId, $source, $ct0, $withText, $logClick, $logView, $useAlt,$richMedia, $loc, $referer);
}

// End of functions

if ( !empty($format) && ($format=='vmap' || $format=='vast4.1' || $format=='vast_pod' || $format=='vast_pod_wrapper' ||  $format=='vast4_pod_wrapper' || $format == 'vast4_pod' || $format == 'vast1' || $format == 'vast2' || $format == 'vast1_wrapper' || $format == 'vast2_wrapper' || $format == 'vast3' || $format == 'vast3_wrapper' || $format=='vast4' || $format == 'vast4_wrapper' || $format=='vast4.1_pod' || $format == 'vast4.1_wrapper' || $format=='vast4.1_pod_wrapper'))
{
    // ----------------- MARK start of cut-and-paste from spc.php ---------------
	require_once MAX_PATH . '/plugins/bannerTypeHtml/djaxvastInlineBannerTypeHtml/adSelect.php';
    //require_once MAX_PATH . '/lib/max/Delivery/adSelect.php';
    require_once MAX_PATH . '/lib/max/Delivery/flash.php';
    require_once MAX_PATH . '/lib/max/Delivery/javascript.php';
    ###START_STRIP_DELIVERY
    OX_Delivery_logMessage('starting delivery script '.__FILE__, 7);
    ###END_STRIP_DELIVERY
  //  MAX_commonSetNoCacheHeaders();
    MAX_commonRegisterGlobalsArray(array('zones' ,'source', 'block', 'blockcampaign', 'exclude', 'mmm_fo', 'q', 'nz'));
    $source = MAX_commonDeriveSource($source);
    $zones = array($_REQUEST['zoneid']);

	if($format=='vast3' || $format=='vast_pod'  || $format=='vast_pod_wrapper' || $format == 'vast3_wrapper')
	{$version='3.0';}
	else if($format=='vast2' || $format == 'vast2_wrapper'){$version='2.0';}
	else if($format=='vast4' || $format == 'vast4_wrapper' || $format=='vast4_pod_wrapper' || $format == 'vast4_pod'){$version='4.0';}
	else if($format=='vast4.1' || $format == 'vast4.1_wrapper' || $format=='vast4.1_pod_wrapper' || $format == 'vast4.1_pod'){$version='4.1';}else{$version='1.0';}
    // ----------------- MARK end of cut-and-paste from spc.php ---------------
    if ( $format == 'vast1' || $format=='vast_pod' || $format=='vast4_pod_wrapper' || $format=='vast_pod_wrapper' || $format == 'vast2' || $format == 'vast3' || $format == 'vast1_wrapper' || $format == 'vast2_wrapper' ||  $format == 'vast3_wrapper' || $format=='vast4' || $format == 'vast4_wrapper' || $format == 'vast4_pod' || $format=='vast4.1' || $format == 'vast4.1_wrapper' || $format == 'vast4.1_pod' || $format=='vast4.1_pod_wrapper')
	{
        $spc_output  = djaxgetVastXMLHeader($charset,$version,$format);
    }
	else if($format == 'vmap')
	{
		  $spc_output  = getVmapXMLHeader($charset,$version,$format);
		 
	}
    // -------------- MARK start cut-and-paste from spc.php --------------------
    // This code was cut and pasted as we also need access to this business logic
    else {
        $spc_output = 'var ' . $conf['var']['prefix'] . 'output = new Array(); ' . "\n";
    }
    foreach ($zones as $thisZone) { 
        if (empty($thisZone)) continue;
        // nz is set when "named zones" are being used, this allows a zone to be selected more than once
        if (!empty($nz)) {
            @list($zonename,$thisZoneid) = explode('=', $thisZone);
            $varname = $zonename;
        } else {
            $thisZoneid = $varname = $thisZone;
        }

        ###START_STRIP_DELIVERY
        djaxappendClientMessage( "Processing zoneid:|$thisZoneid| zonename:|$varname|" );
        ###END_STRIP_DELIVERY

        $what = 'zone:'.$thisZone;

        ###START_STRIP_DELIVERY
        OX_Delivery_logMessage('$what='.$what, 7);
        OX_Delivery_logMessage('$context='.print_r($context,true), 7);
        ###END_STRIP_DELIVERY
        // Get the banner
        $output = djaxMAX_adSelect($what, $clientid, $target, $source, $withtext, $charset, $context, true, $ct0, $GLOBALS['loc'], $GLOBALS['referer']);
	if( $format=='vast_pod' || $format=='vast_pod_wrapper' || $format == 'vast4_pod' || $format=='vast4_pod_wrapper' || $format == 'vast4.1_pod' || $format=='vast4.1_pod_wrapper')
	{

		   	$conf = $GLOBALS['_MAX']['CONF'];
		   	unset($output['aRow']['bannerContent']);
		    unset($output['aRow']['clickUrl']);
		    unset($output['aRow']['logUrl']);
		    unset($output['aRow']['aSearch']);
		       unset($output['aRow']['aReplace']);
		    
			$djax_ad=$output['aRow']; 
			$player = "";
			$i=1;	
			$max=15;
			if(empty($_REQUEST['limit']))
			{
			$limit=$max;
			}
			else
			{
			$limit=$_REQUEST['limit'];
			}
		
			foreach($djax_ad as $key => $aBanner)
			{
				if($i<=$limit && $i<$max)
				{
					if(!empty($aBanner['ad_id']))
					{
						$aOutputParams=array();

						$aOutputParams['format'] = $format;
						
						djaxextractVastParameters( $aBanner );

						if(!empty($aBanner['vast_thirdparty_impression'])) 
						{
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
						if($aBanner['revenue']==1)
						{
							$pricingtype='CPM';
						}
						if($aBanner['revenue']==2)
						{
							$pricingtype='CPC';
						}
						if($aBanner['revenue']==3)
						{
							$pricingtype='CPA';
						}
						$aOutputParams['pricingtype']=$pricingtype;

						djaxprepareCompanionBanner($aOutputParams,$aBanner, 0, $source, $ct0, $withText, $logClick, $logView, $useAlt, $loc, $referer);
						
						djaxprepareVideoParams($aOutputParams,$aBanner);
						
						djaxprepareTrackingParams($aOutputParams,$aBanner, $thisZoneid, $source, $loc, $ct0, $logClick, $referer);

					    if($format=='vast_pod_wrapper')
						{
						$player .= renderOutput_inlinewrapperVast3( $aOutputParams,'vastInline', "Inline Wrapper Video Ad Pod",$format,$aBanner,$i);
						}
						else if($format=='vast4_pod_wrapper')
						{
						$player .= renderOutput_inlinewrapperVast4($aOutputParams,'vastInline', "Inline Wrapper Video Ad Pod",$format,$aBanner,$i);
						}
						else if($format=='vast4_pod')
						{ 
						$player .= renderOutput_inlineVast4( $aOutputParams,'vastInline', "Inline Video Ad Pod",$format,$aBanner,$i);
						}
						else if($format=='vast4.1_pod')
						{ 
						$player .= renderOutput_inlineVast41( $aOutputParams,'vastInline', "Inline Video Ad Pod",$format,$aBanner,$i);
						}
						else if($format=='vast4.1_pod_wrapper')
						{ 
						$player .= renderOutput_inlinewrapperVast41($aOutputParams,'vastInline', "Inline Wrapper Video Ad Pod",$format,$aBanner,$i);
						}
						else
						{ 
						$player .= renderOutput_inlineVast3( $aOutputParams,'vastInline', "Inline Video Ad Pod",$format,$i);
						}
					    	$i++;
				   	}
				}
			}
  	$spc_output .=  $player;
	}

        ###START_STRIP_DELIVERY
        OX_Delivery_logMessage('output bannerid='.(empty($output['bannerid']) ? ' NO BANNERID' : $output['bannerid']), 7);
        ###END_STRIP_DELIVERY

        // BM - output format is vast xml
       if ( $format == 'vmap' || $format == 'vast1' || $format=='vast_pod'  || $format=='vast4_pod_wrapper' || $format=='vast_pod_wrapper' || $format == 'vast2' || $format == 'vast3' || $format == 'vast1_wrapper'  || $format == 'vast2_wrapper'  || $format == 'vast3_wrapper' || $format == 'vast4' || $format == 'vast4_wrapper' || $format == 'vast4_pod' || $format=='vast4.1' || $format=='vast4.1_wrapper' || $format=='vast4.1_pod' || $format=='vast4.1_pod_wrapper'){
            if (  $output['html']  &&
                 (
                     ($output['width'] != VAST_OVERLAY_DIMENSIONS) &&
                     ($output['width'] != VAST_INLINE_DIMENSIONS)
                 )
               ){
                $badZoneId = $output['aRow']['zoneid'];
                $badBannerId = $output['bannerid'];
                // Store the html2js'd output for this ad
                
		if($format=='vast_pod' && $format=='vast_pod_wrapper' && $format == 'vast4_pod' && $format == 'vast4.1_pod' && $format=='vast4.1_pod_wrapper' && $format=='vast4_pod_wrapper')
		{
                $spc_output .= "<!-- You are requesting vast xml for zone $badZoneId which does not apear to be a video overlay banner nor a vast inline banner. -->\n";
		}
            } else { 
                // Store the html2js'd output for this ad
                $spc_output .= $output['html'] . "\n";
            }

            // Help the player (requestor of VAST) to match the ads in the response with his request by using his id in the Ad xml node
            $spc_output = str_replace( '{player_allocated_ad_id}', $varname, $spc_output );
        }
        else {
            // Store the html2js'd output for this ad
            $spc_output .= MAX_javascriptToHTML($output['html'], $conf['var']['prefix'] . "output['{$varname}']", false, false) . "\n";
        }

    }
    MAX_cookieFlush(); 
    // -------------- MARK end cut-and-paste from spc.php --------------------

    if ( $format == 'vast1' || $format=='vast_pod' || $format=='vast4_pod_wrapper' || $format=='vast_pod_wrapper' || $format == 'vast2' || $format == 'vast3' || $format == 'vast1_wrapper'  || $format == 'vast2_wrapper'  || $format == 'vast3_wrapper' || $format == 'vast4' || $format == 'vast4_wrapper' || $format == 'vast4_pod' || $format == 'vast4.1' || $format == 'vast4.1_wrapper' || $format == 'vast4.1_pod' || $format == 'vast4.1_pod_wrapper')
    {
        $spc_output .=  djaxgetVastXMLFooter($format);
        // Setup the banners for this page
        MAX_commonSendContentTypeHeader("application/xml", $charset);
    }
     else if( $format =='vmap')//vmap footer 
    {
		
		 $spc_output .=  getVmapXMLFooter($format,'','');
		 MAX_commonSendContentTypeHeader("application/xml", $charset);
		 header("Content-Length: ".strlen($spc_output));
	}
    else {
        // Setup the banners for this page
        MAX_commonSendContentTypeHeader("application/x-javascript", $charset);
    }
    $spc_output .= djaxgetClientMessages();
    echo $spc_output;
}
else {
   //echo "<!-- vast delivery include called -->";
}

