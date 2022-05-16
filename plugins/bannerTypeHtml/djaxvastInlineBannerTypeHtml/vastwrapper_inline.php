<?php

function renderOutput_inlinewrapperVast1($aOut, $pluginType, $vastAdDescription,$format,$aBanner,$sequence)
{
    $adSystem = $GLOBALS['_MAX']['CONF']['ui']['applicationName'] ? $GLOBALS['_MAX']['CONF']['ui']['applicationName'] : 'Revive Adserver';
    $adName = $aOut['name'];
    $player = "";
    $player .= "    <Ad id=\"{player_allocated_ad_id}\" >";
    $player .= "        <Wrapper>";
    $player .= "            <AdSystem><![CDATA[$adSystem]]></AdSystem>\n";
    $player .= "                <AdTitle><![CDATA[$adName]]></AdTitle>\n";
    $player .= "                    <Description><![CDATA[$vastAdDescription]]></Description>\n";
		    $player .= "                    <Impression>\n";
		    $player .= "                        <URL id=\"primaryAdServer\"><![CDATA[${aOut['impressionUrl']}]]></URL>\n";
$player .= "                    </Impression>\n";
		    if(!empty($aOut['thirdPartyImpressionUrl'])) {
$player .= "                    <Impression>\n";
			$player .= "                        <URL id=\"secondaryAdServer\"><![CDATA[${aOut['thirdPartyImpressionUrl']}]]></URL>\n";
$player .= "                    </Impression>\n";
		    }
	 $wrapurl=dynamic_url($aOut, $pluginType, $vastAdDescription,$format,$aBanner);	    
    $player .= "<AdTagURL>";
    $player .= str_replace('&','&amp;',$wrapurl);
    $player .= "</AdTagURL>";

    if ( isset($aOut['companionMarkup'])  ){
        if(!empty($aOut['companionClickUrl'])) {
            $CompanionClickThrough  = "                    <CompanionClickThrough>\n";
            $CompanionClickThrough .= "                        <URL><![CDATA[${aOut['companionClickUrl']}]]></URL>\n";
            $CompanionClickThrough .= "                    </CompanionClickThrough>\n";
        }
        //debugdump( '$companionOutput', $companionOutput );
        $player .= "             <CompanionAds>\n";
        $player .= "                <Companion id=\"companion\" width=\"${aOut['companionWidth']}\" height=\"${aOut['companionHeight']}\" resourceType=\"HTML\">\n";
        $player .= "                    <Code><![CDATA[${aOut['companionMarkup']}]]></Code>\n";
        $player .= "					$CompanionClickThrough";
        $player .= "                </Companion>\n";
        $player .= "            </CompanionAds>\n";
    }

	
        $player .= getVastWrapperVideoAdOutput($aOut,$format,$vastAdDescription,$aBanner);
    	
    	$player .= "        </Wrapper>\n";
    	$player .= "    </Ad>\n";
    	return $player;

}


function getVastWrapperVideoAdOutput($aO,$format,$videotype,$aBanner)
{

	if($format=='vast1_wrapper')
	{
	    if(!empty($aO['vastVideoClickThroughUrl'])) 
	    {
		$videoClicksVast = '<VideoClicks>
		                 <ClickTracking>
		                    <URL id="destination"><![CDATA['.$aO['vastVideoClickThroughUrl'].']]></URL>
		                 </ClickTracking>
		            </VideoClicks>';
	    }

		if($videotype!='Overlay Video Ad')
		{
		
    		$vastVideoMarkup =<<<VAST_VIDEO_AD_TEMPLATE
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
		$videoClicksVast
VAST_VIDEO_AD_TEMPLATE;

    return $vastVideoMarkup;
		}
		else
		{
			return $videoClicksVast;
		}
	}
	else if($format=='vast2_wrapper')
	{
            if(!empty($aO['vastVideoClickThroughUrl'])) 
	    {
		$videoClicksVast = '<VideoClicks>
		                <ClickTracking>
		                    <![CDATA['.$aO['vastVideoClickThroughUrl'].']]>
		                </ClickTracking>
		            </VideoClicks>';
	    }

		if($videotype!='Overlay Video Ad')
		{
		$vastVideoMarkup =<<<VAST_VIDEO_AD_TEMPLATE
        		<Creative>				
				 <Linear>
				    <Duration>${aO['vastVideoDuration']}</Duration>
				    <TrackingEvents>
				      <Tracking event="start"><![CDATA[${aO['trackUrlStart']}]]></Tracking>
				      <Tracking event="firstQuartile"><![CDATA[${aO['trackUrlFirstQuartile']}]]></Tracking>
				      <Tracking event="midpoint"><![CDATA[${aO['trackUrlMidPoint']}]]></Tracking>
				      <Tracking event="thirdQuartile"><![CDATA[${aO['trackUrlThirdQuartile']}]]></Tracking>
				      <Tracking event="complete"><![CDATA[${aO['trackUrlComplete']}]]></Tracking>
				      <Tracking event="pause"><![CDATA[${aO['trackUrlPause']}]]></Tracking>
				      <Tracking event="mute"><![CDATA[${aO['trackUrlMute']}]]></Tracking>
				      <Tracking event="fullscreen"><![CDATA[${aO['trackUrlFullscreen']}]]></Tracking>
				      <Tracking event="unmute"><![CDATA[${aO['trackUrlUnmute']}]]></Tracking>
				      <Tracking event="rewind"><![CDATA[${aO['trackUrlrewind']}]]></Tracking>
                   	  <Tracking event="resume"><![CDATA[${aO['trackUrlResume']}]]> </Tracking>	
				    </TrackingEvents>
				 	$videoClicksVast
				</Linear>
			</Creative>
VAST_VIDEO_AD_TEMPLATE;
	
    return $vastVideoMarkup;
		}
		else
		{
			return $videoClicksVast;
		}
	}
	else if($format=='vast3_wrapper' || $format=='vast_pod_wrapper')
	{
		
			if(!empty($aO['vastVideoClickThroughUrl'])) 
			{
				if(!empty($aO['vast_thirdparty_clicktracking'])) 
				{
				$videoClickstrackVast = '<ClickTracking>
						  <![CDATA['.$aO['vast_thirdparty_clicktracking'].']]>
						</ClickTracking> ';
			    	}
				if(!empty($aO['vast_thirdparty_clickcustom'])) {
				$videocustomClicksVast = '<CustomClick>
						 <![CDATA['.$aO['vast_thirdparty_clickcustom'].']]>
						</CustomClick >';
			    	}
				$videoClicksVast = '<VideoClicks>
				        <ClickThrough>
				          <![CDATA['.$aO['vastVideoClickThroughUrl'].']]>
				        </ClickThrough> '.$videoClickstrackVast.$videocustomClicksVast.'
				    </VideoClicks>';
		    	}
		if($videotype!='Overlay Video Ad')
		{
		$vastVideoMarkup =<<<VAST_VIDEO_AD_TEMPLATE
        		<Creative>
				 <Linear>
				    <Duration>${aO['vastVideoDuration']}</Duration>
			    	    <TrackingEvents>
				      <Tracking event="start"><![CDATA[${aO['trackUrlStart']}]]></Tracking>
				      <Tracking event="firstQuartile"><![CDATA[${aO['trackUrlFirstQuartile']}]]></Tracking>
				      <Tracking event="midpoint"><![CDATA[${aO['trackUrlMidPoint']}]]></Tracking>
				      <Tracking event="thirdQuartile"><![CDATA[${aO['trackUrlThirdQuartile']}]]></Tracking>
				      <Tracking event="complete"><![CDATA[${aO['trackUrlComplete']}]]></Tracking>
				      <Tracking event="pause"><![CDATA[${aO['trackUrlPause']}]]></Tracking>
				      <Tracking event="mute"><![CDATA[${aO['trackUrlMute']}]]></Tracking>
				      <Tracking event="fullscreen"><![CDATA[${aO['trackUrlFullscreen']}]]></Tracking>
				      <Tracking event="unmute"><![CDATA[${aO['trackUrlUnmute']}]]></Tracking>
				      <Tracking event="acceptInvitationLinear"><![CDATA[${aO['trackUrlacceptInvitationLinear']}]]></Tracking>
				      <Tracking event="closeLinear"><![CDATA[${aO['trackUrlcloseLinear']}]]></Tracking>
				      <Tracking event="skip"><![CDATA[${aO['trackUrlskip']}]]></Tracking>
				      <Tracking event="progress" $progress><![CDATA[${aO['trackUrlprogress']}]]></Tracking>
				      <Tracking event="exitFullscreen"><![CDATA[${aO['trackUrlexitFullscreen']}]]></Tracking>
				      <Tracking event="rewind"><![CDATA[${aO['trackUrlrewind']}]]></Tracking>
                   	  <Tracking event="resume"><![CDATA[${aO['trackUrlResume']}]]> </Tracking>	      	
				    </TrackingEvents>
				 	$videoClicksVast
				</Linear>
			</Creative>
VAST_VIDEO_AD_TEMPLATE;

    return $vastVideoMarkup;
		}
		else
		{
		return $videoClicksVast;
		}

	}
	
}

function renderOutput_inlinewrapperVast2($aOut, $pluginType, $vastAdDescription,$format,$aBanner,$sequence)
{
    $adSystem = $GLOBALS['_MAX']['CONF']['ui']['applicationName'] ? $GLOBALS['_MAX']['CONF']['ui']['applicationName'] : 'Revive Adserver';
    $adName = $aOut['name'];
    $player = "";
    $player .= "    <Ad id=\"{player_allocated_ad_id}\" >";
    $player .= "        <Wrapper>";
    $player .= "            <AdSystem><![CDATA[$adSystem]]></AdSystem>\n";
    $player .= "                <AdTitle><![CDATA[$adName]]></AdTitle>\n";
    $player .= "                    <Impression>\n";
    $player .= "                       <![CDATA[${aOut['impressionUrl']}]]>\n";
	$player .= "                    </Impression>\n";
    if(!empty($aOut['thirdPartyImpressionUrl'])) 
    {
	$player .= "                    <Impression>\n";
	$player .= "<![CDATA[${aOut['thirdPartyImpressionUrl']}]]>\n";
	$player .= "                    </Impression>\n";
    }
     $wrapurl=dynamic_url($aOut, $pluginType, $vastAdDescription,$format,$aBanner);
    $player .= "<VASTAdTagURI><![CDATA[";
    $player .= str_replace('&','&amp;',$wrapurl);
    $player .= "]]></VASTAdTagURI>";
    $player.="<Creatives>";
	if ( isset($aOut['companionMarkup'])  )
	{
				
				if(!empty($aOut['companionClickUrl'])) 
				{
				    $CompanionClickThrough  = "                    <CompanionClickThrough>\n";
				    $CompanionClickThrough .= "                        <![CDATA[${aOut['companionClickUrl']}]]>\n";
				    $CompanionClickThrough .= "                    </CompanionClickThrough>\n";
				}

				if(!empty($aOut['vast_thirdparty_companion_expandedwidth']))
				{
					$expand_width="expandedWidth=\"${aOut['vast_thirdparty_companion_expandedwidth']}\"";
				}
				
				if(!empty($aOut['vast_thirdparty_companion_expandedheight']))
				{
					$expand_height="expandedHeight=\"${aOut['vast_thirdparty_companion_expandedheight']}\"";
				}

				$conf = $GLOBALS['_MAX']['CONF'];
				if ($GLOBALS['_MAX']['SSL_REQUEST']) 
				{
					$djprotocol='https://';
				}
				else
				{
					$djprotocol='http://';
				}
				$createview=$djprotocol.$conf['webpath']['delivery'].'fc.php?script=djaxvideoAds:djaxvastEvent&bannerid='.$aOut['companionbannerid'].'&zoneid=0&event=creativeView';
				$CompanionTrackingEvents  = "                    <TrackingEvents>\n";
				$CompanionTrackingEvents .= "                        <Tracking><![CDATA[${createview}]]></Tracking>\n";
				$CompanionTrackingEvents .= "                    </TrackingEvents>\n";

				$player.="<Creative sequence=\"1\"><CompanionAds><Companion id=\"companion\" width=\"${aOut['companionWidth']}\" height=\"${aOut['companionHeight']}\" $expand_width $expand_height><HTMLResource>
              <![CDATA[${aOut['companionMarkup']}]]>
             </HTMLResource>";
				$player .= "$CompanionTrackingEvents$CompanionClickThrough";
				$player.="</Companion></CompanionAds></Creative>";
				
	}
	
        $player .= getVastWrapperVideoAdOutput($aOut,$format,$vastAdDescription,$aBanner);
		$player.="</Creatives>";
    	$player .= "        </Wrapper>\n";
    	$player .= "    </Ad>\n";
    	return $player;

}
function renderOutput_inlinewrapperVast3($aOut, $pluginType, $vastAdDescription,$format,$aBanner,$sequence)
{ 
    $adSystem = 'Reviveadservermod.com';
    $adName = $aOut['name'];
    $player = "";
    if($format=='vast_pod_wrapper')
	{
	    $player .= "<Ad id=\"{player_allocated_ad_id}\" sequence=\"$sequence\">";
	}
	else
	{
	$player .= "    <Ad id=\"{player_allocated_ad_id}\" >";
	}
    $player .= "        <Wrapper>";
    $player .= "            <AdSystem><![CDATA[$adSystem]]></AdSystem>\n";
    $player .= "                <AdTitle><![CDATA[$adName]]></AdTitle>\n";
   	if(!empty($aOut['pricingtype']))
	{
    $player .= "            <Pricing><model><![CDATA[${aOut['pricingtype']}]]></model><currency><![CDATA[USD]]></currency></Pricing>\n";
	} 
    $player .= "                    <Impression>\n";
    $player .= "                       <![CDATA[${aOut['impressionUrl']}]]>\n";
	$player .= "                    </Impression>\n";
    if(!empty($aOut['thirdPartyImpressionUrl'])) 
    {
	$player .= "                    <Impression>\n";
	$player .= "<![CDATA[${aOut['thirdPartyImpressionUrl']}]]>\n";
	$player .= "                    </Impression>\n";
    }
    $wrapurl=dynamic_url($aOut, $pluginType, $vastAdDescription,$format,$aBanner); 
	$player .= "<VASTAdTagURI><![CDATA[";
	$player .= str_replace('&','&amp;', $wrapurl);
    $player .= "]]></VASTAdTagURI>";
    $player.="<Creatives>";

	if ( isset($aOut['companionMarkup'])  )
	{
				if(!empty($aOut['vast_thirdparty_companion_clicktracking'])) 
				{
					 $CompanionClickThrough .= '<CompanionClickTracking>
							  <![CDATA['.$aOut['vast_thirdparty_companion_clicktracking'].']]>
							</CompanionClickTracking> ';
				}
				if(!empty($aOut['vast_thirdparty_companion_expandedwidth']))
				{
					$expand_width="expandedWidth=\"${aOut['vast_thirdparty_companion_expandedwidth']}\"";
				}
				if(!empty($aOut['vast_thirdparty_companion_expandedheight']))
				{
					$expand_height="expandedHeight=\"${aOut['vast_thirdparty_companion_expandedheight']}\"";
				}
					if ($GLOBALS['_MAX']['SSL_REQUEST']) 
					{
					$djprotocol='https://';
					}
					else
					{
					$djprotocol='http://';
					}

				$conf = $GLOBALS['_MAX']['CONF'];
				$createview=$djprotocol.$conf['webpath']['delivery'].'fc.php?script=djaxvideoAds:djaxvastEvent&bannerid='.$aOut['companionbannerid'].'&zoneid=0&event=creativeView';
				$CompanionTrackingEvents  = "                    <TrackingEvents>\n";
				$CompanionTrackingEvents .= "                        <Tracking><![CDATA[${createview}]]></Tracking>\n";
				$CompanionTrackingEvents .= "                    </TrackingEvents>\n";
				$player.="<Creative sequence=\"1\"><CompanionAds><Companion id=\"companion\" width=\"${aOut['companionWidth']}\" height=\"${aOut['companionHeight']}\" $expand_width $expand_height><HTMLResource>
              <![CDATA[${aOut['companionMarkup']}]]>
             </HTMLResource>";
				$player .= "$CompanionTrackingEvents$CompanionClickThrough";
				$player.="</Companion></CompanionAds></Creative>";
				
	}
        $player .= getVastWrapperVideoAdOutput($aOut,$format,$vastAdDescription,$aBanner);
		$player.="</Creatives>";
    	$player .= "        </Wrapper>\n";
    	$player .= "    </Ad>\n";
    	return $player;
}

