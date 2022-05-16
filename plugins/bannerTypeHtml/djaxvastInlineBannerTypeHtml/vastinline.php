<?php
function renderOutput_inlineVast1( $aOut, $pluginType, $vastAdDescription,$format)
{
    $adSystem = $GLOBALS['_MAX']['CONF']['ui']['applicationName'] ? $GLOBALS['_MAX']['CONF']['ui']['applicationName'] : 'Reviveadservermod.com';

    $adName = $aOut['name'];
    $player = "";
    $player .= "    <Ad id=\"{player_allocated_ad_id}\" >";
    $player .= "        <InLine>";
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

    if ( $pluginType == 'vastOverlay') {
        $code = $resourceType = $creativeType = $elementName = '';
        switch($aOut['overlayFormat']) {
            case VAST_OVERLAY_FORMAT_HTML:
                $code = "<![CDATA[". $aOut['overlayMarkupTemplate'] . "]]>";
                $resourceType = 'HTML';
                $elementName = 'Code';
            break;

            case VAST_OVERLAY_FORMAT_IMAGE:
                $creativeType = strtoupper($aOut['overlayContentType']);
                // BC when the overlay_creative_type field is not set in the DB
                if(empty($creativeType)) {
                    $creativeType = strtoupper(substr($aOut['overlayFilename'], -3));
                    // case of .jpeg files OXPL-493
                    if($creativeType == 'PEG') {
                        $creativeType = 'JPEG';
                    }
                }
                if($creativeType == 'JPEG') {
                    $creativeType = 'JPG';
                }
                $creativeType = 'image/'.$creativeType;
                $code = getImageUrlFromFilename($aOut['overlayFilename']);
                $resourceType = 'static';
                $elementName = 'URL';
            break;

            case VAST_OVERLAY_FORMAT_SWF:
                $creativeType = 'application/x-shockwave-flash';
		$vpaid_swf='apiFramework="VPAID"';
                $code = getImageUrlFromFilename($aOut['overlayFilename']);
                $resourceType = 'static';
                $elementName = 'URL';
            break;

            case VAST_OVERLAY_FORMAT_TEXT:
                $resourceType = 'TEXT';
                $code = "<![CDATA[
                	<Title>".xmlspecialchars($aOut['overlayTextTitle'])."</Title>
               		<Description>".xmlspecialchars($aOut['overlayTextDescription'])."</Description>
               		<CallToAction>".xmlspecialchars($aOut['overlayTextCall'])."</CallToAction>
               		]]>
               ";
                $elementName = 'Code';
            break;
        }

        if(!empty($aOut['clickUrl'])) {
            $nonLinearClickThrough = "<NonLinearClickThrough>
                    <URL><![CDATA[${aOut['clickUrl']}]]></URL>
                </NonLinearClickThrough>\n";
        }

        $creativeTypeAttribute = '';
        if(!empty($creativeType)) {
            $creativeType = strtolower($creativeType);
            $creativeTypeAttribute = 'creativeType="'. $creativeType .'"';
        }

        $player .= "             <NonLinearAds>\n";
        $player .= "                <NonLinear id=\"overlay\" $vpaid_swf maintainAspectRatio=\"true\" scalable=\"true\" width=\"${aOut['overlayWidth']}\" height=\"${aOut['overlayHeight']}\" resourceType=\"$resourceType\" $creativeTypeAttribute>\n";
        $player .= "                    <$elementName><![CDATA[$code]]></$elementName>\n";
        $player .= "                    $nonLinearClickThrough";
        $player .= "                </NonLinear>\n";
        $player .= "            </NonLinearAds>\n";
    }


    if ( isset($aOut['fullPathToVideo']) ){
        $player .= djaxgetVastVideoAdOutput($aOut);
    }
    $player .= "        </InLine>\n";
    $player .= "    </Ad>\n";
    return $player;
}


function renderOutput_inlineVast2( $aOut, $pluginType, $vastAdDescription,$format)
{
    $adSystem = 'Reviveadservermod.com';// $GLOBALS['_MAX']['CONF']['ui']['applicationName'] ? $GLOBALS['_MAX']['CONF']['ui']['applicationName'] : 'Reviveadservermod.com';
    $adName = $aOut['name'];
    $player = "";
    $player .= "    <Ad id=\"{player_allocated_ad_id}\" >";
    $player .= "        <InLine>";
    $player .= "            <AdSystem><![CDATA[$adSystem]]></AdSystem>\n";
    $player .= "                <AdTitle><![CDATA[$adName]]></AdTitle>\n";
    $player .= "                    <Impression>\n";
    $player .= "                        <![CDATA[${aOut['impressionUrl']}]]>\n";
    $player .= "</Impression>\n";
	
    if(!empty($aOut['thirdPartyImpressionUrl'])) {
	    $player .= "                    <Impression>\n";
        $player .= "                       <![CDATA[${aOut['thirdPartyImpressionUrl']}]]>\n";
		$player .= "                    </Impression>\n";
    }
  

	$player.="<Creatives>";

	if ( isset($aOut['companionMarkup'])  )
	{
				
				if(!empty($aOut['companionClickUrl'])) 
				{
				    $CompanionClickThrough  = "                    <CompanionClickThrough>\n";
				    $CompanionClickThrough .= "                        <URL><![CDATA[${aOut['companionClickUrl']}]]></URL>\n";
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

					if ($GLOBALS['_MAX']['SSL_REQUEST']) {
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

    if ( isset($aOut['fullPathToVideo']) )
	{
        $player .= getVast2VideoAdOutput($aOut);
    }
    $player.="</Creatives>";
    $player .= "        </InLine>\n";
    $player .= "    </Ad>\n";
    return $player;
}

function renderOutput_inlineVast3( $aOut, $pluginType, $vastAdDescription,$format,$sequence)
{

    $adSystem = $GLOBALS['_MAX']['CONF']['ui']['applicationName'] ? $GLOBALS['_MAX']['CONF']['ui']['applicationName'] : 'Reviveadservermod.com';
    $adName = $aOut['name'];
    $player = "";

	if($format=='vast_pod')
	{
    	$player .= "<Ad id=\"{player_allocated_ad_id}\" sequence=\"$sequence\">";
	}
	else
	{
	$player .= "    <Ad id=\"{player_allocated_ad_id}\" >";
	}
    $player .= "        <InLine>";
    $player .= "            <AdSystem><![CDATA[$adSystem]]></AdSystem>\n";
    $player .= "                <AdTitle><![CDATA[$adName]]></AdTitle>\n";
	if(!empty($aOut['pricingtype']))
	{
    $player .= "            <Pricing><model><![CDATA[${aOut['pricingtype']}]]></model><currency><![CDATA[USD]]></currency></Pricing>\n";
	}
    $player .= "                    <Impression>\n";
    $player .= "                        <![CDATA[${aOut['impressionUrl']}]]>\n";
$player .= "                    </Impression>\n";
  if(!empty($aOut['thirdPartyImpressionUrl'])) {
	   $player .= "                    <Impression>\n";
        $player .= "                       <![CDATA[${aOut['thirdPartyImpressionUrl']}]]>\n";
  $player .= "                    </Impression>\n";
    }

	$player.="<Creatives>";

	if ( isset($aOut['companionMarkup'])  )
	{
				
				if(!empty($aOut['companionClickUrl'])) 
				{
				    $CompanionClickThrough  = "                    <CompanionClickThrough>\n";
				    $CompanionClickThrough .= "                       <![CDATA[${aOut['companionClickUrl']}]]>\n";
				    $CompanionClickThrough .= "                    </CompanionClickThrough>\n";
				       	if(!empty($aOut['vast_thirdparty_companion_clicktracking'])) 
					{
					 $CompanionClickThrough .= '<CompanionClickTracking>
							  <![CDATA['.$aOut['vast_thirdparty_companion_clicktracking'].']]>
							</CompanionClickTracking> ';
				    	}
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


					if ($GLOBALS['_MAX']['SSL_REQUEST']) {
					$djprotocol='https://';
					}
					else
					{
					$djprotocol='http://';
					}

				$createview=$djprotocol.$conf['webpath']['delivery'].'fc.php?script=videoAds:vastEvent&bannerid='.$aOut['companionbannerid'].'&zoneid=0&event=creativeView';
				$CompanionTrackingEvents  = "                    <TrackingEvents>\n";
				$CompanionTrackingEvents .= "                        <Tracking><![CDATA[${createview}]]></Tracking>\n";
				$CompanionTrackingEvents .= "                    </TrackingEvents>\n";

				$player.="<Creative sequence=\"1\"><CompanionAds><Companion id=\"companion\"  width=\"${aOut['companionWidth']}\" height=\"${aOut['companionHeight']}\" $expand_width $expand_height ><HTMLResource>
              <![CDATA[${aOut['companionMarkup']}]]>
             </HTMLResource>";
				
				$player .= "$CompanionTrackingEvents$CompanionClickThrough";
				$player.="</Companion></CompanionAds></Creative>";
				
	}

    	if ( isset($aOut['fullPathToVideo']) )
	{
        $player .= getVast3VideoAdOutput($aOut);
    	}
    $player.="</Creatives>";
    $player .= "        </InLine>\n";
    $player .= "    </Ad>\n";
    return $player;


}



function getVast3VideoAdOutput($aO)
{
			if($aO['vastVideoType']=='video/x-mp4')
			{
				$aO['vastVideoType']='video/mp4';
			}
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
		if(!empty($aO['vast_video_skip_duration']))
		{
			$skip='skipoffset='.'"'.$aO['vast_video_skip_duration'].'"';
		}
		else
		{
			$skip='';
		}

		if(!empty($aO['vast_video_skip_progress_duration']))
		{
			$progress='offset='.'"'.$aO['vast_video_skip_progress_duration'].'"';
		}
		else
		{
			$progress='';
		}

		$vastVideoMarkup =<<<VAST_VIDEO_AD_TEMPLATE
        		<Creative>
				 <Linear $skip>
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
				      <Tracking event="closeLinear"><![CDATA[${aO['trackUrlcloseLinear']}]]></Tracking>
				      <Tracking event="acceptInvitationLinear"><![CDATA[${aO['trackUrlacceptInvitationLinear']}]]></Tracking>
				      <Tracking event="skip"><![CDATA[${aO['trackUrlskip']}]]></Tracking>
				      <Tracking event="progress" $progress><![CDATA[${aO['trackUrlprogress']}]]></Tracking>
				      <Tracking event="exitFullscreen"><![CDATA[${aO['trackUrlexitFullscreen']}]]></Tracking>
				      <Tracking event="rewind"><![CDATA[${aO['trackUrlrewind']}]]></Tracking>
                   	 <Tracking event="resume"><![CDATA[${aO['trackUrlResume']}]]> </Tracking>	
				    </TrackingEvents>
				 	$videoClicksVast
				    <MediaFiles>
					<MediaFile delivery="${aO['vastVideoDelivery']}" bitrate="${aO['vastVideoBitrate']}" width="640" height="480" type="${aO['vastVideoType']}" scalable="true" maintainAspectRatio="true">
				      <![CDATA[${aO['fullPathToVideo']}]]>
				      </MediaFile>
				    </MediaFiles>
				</Linear>
			</Creative>

VAST_VIDEO_AD_TEMPLATE;

    return $vastVideoMarkup;
}

function getVast2VideoAdOutput($aO)
{
			if($aO['vastVideoType']=='video/x-mp4')
			{
				$aO['vastVideoType']='video/mp4';
			}
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
				    <MediaFiles>
					<MediaFile delivery="${aO['vastVideoDelivery']}" bitrate="${aO['vastVideoBitrate']}" width="640" height="480" type="${aO['vastVideoType']}" $minbitrate scalable="true" maintainAspectRatio="true">
				      <![CDATA[${aO['fullPathToVideo']}]]>
				      </MediaFile>
				    </MediaFiles>
				    
				    
				</Linear>
			</Creative>

VAST_VIDEO_AD_TEMPLATE;

    return $vastVideoMarkup;
}

function djaxgetVastVideoAdOutput($aO)
{
	
			if($aO['vastVideoType']=='video/x-mp4')
			{
				$aO['vastVideoType']='video/mp4';
			}
    	if(!empty($aO['vastVideoClickThroughUrl'])) 
	{
	if(!empty($aO['vast_thirdparty_clicktracking'])) 
	{
        $videoClickstrackVast = '<ClickTracking>
                            <URL id="destination"><![CDATA['.$aO['vast_thirdparty_clicktracking'].']]></URL>
                        </ClickTracking> ';
    	}
	if(!empty($aO['vast_thirdparty_clickcustom'])) {
        $videocustomClicksVast = '<CustomClick>
                            <URL id="destination"><![CDATA['.$aO['vast_thirdparty_clickcustom'].']]></URL>
                        </CustomClick >';
    	}

        $videoClicksVast = '<VideoClicks>
                        <ClickThrough>
                            <URL id="destination"><![CDATA['.$aO['vastVideoClickThroughUrl'].']]></URL>
                        </ClickThrough>'.$videoClickstrackVast.$videocustomClicksVast.'
                    </VideoClicks>';
    	}



    $vastVideoMarkup =<<<VAST_VIDEO_AD_TEMPLATE
			    <Video>
                    <Duration>${aO['vastVideoDuration']}</Duration>
                    <AdID><![CDATA[${aO['vastVideoId']}]]></AdID>
                    $videoClicksVast
                    <MediaFiles>
                        <MediaFile delivery="${aO['vastVideoDelivery']}" bitrate="${aO['vastVideoBitrate']}" width="640" height="480" type="${aO['vastVideoType']}">
                            <URL><![CDATA[${aO['fullPathToVideo']}]]></URL>
                        </MediaFile>
                    </MediaFiles>
                </Video>

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
                    <Tracking event="unmute">
                        <URL id="primaryAdServer"><![CDATA[${aO['trackUrlUnmute']}]]></URL>
                    </Tracking>
                   <Tracking event="resume">
                        <URL id="primaryAdServer"><![CDATA[${aO['trackUrlResume']}]]></URL>
                    </Tracking>
                </TrackingEvents>
VAST_VIDEO_AD_TEMPLATE;

    return $vastVideoMarkup;
}


?>
