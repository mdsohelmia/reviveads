<?php
function renderOutput_nonlinearwrapperVast1($aOut, $pluginType, $vastAdDescription,$format,$aBanner)
{

if ($GLOBALS['_MAX']['SSL_REQUEST']) {
					$djprotocol='https://';
					}
					else
					{
					$djprotocol='http://';
					}
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
		    }		$wrapurl=dynamic_url($aOut, $pluginType, $vastAdDescription,$format,$aBanner);	
			 $player .= "<AdTagURL>";
		 $player .= str_replace('&','&amp;',$wrapurl);
		 $player .= "</AdTagURL>";

				if ( isset($aOut['companionMarkup'])  )
		{
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

		if($pluginType == 'vastOverlay')
		{
			$code = $resourceType = $creativeType = $elementName = '';

			if(!empty($aOut['clickUrl'])) 
			{
				    $nonLinearClickThrough = "<NonLinearClickThrough>
					    <![CDATA[${aOut['clickUrl']}]]>
					</NonLinearClickThrough>\n";
			}

			$creativeTypeAttribute = '';
			if(!empty($creativeType)) {
			    $creativeType = strtolower($creativeType);
			    $creativeTypeAttribute = 'creativeType="'. $creativeType .'"';
			}

			$player .= "             <NonLinearAds>\n";
			$player .= "                <NonLinear id=\"overlay\" width=\"${aOut['overlayWidth']}\" height=\"${aOut['overlayHeight']}\" resourceType=\"$resourceType\" $creativeTypeAttribute>\n";
			$player .= "                    $nonLinearClickThrough";
			$player .= "                </NonLinear>\n";
			$player .= "            </NonLinearAds>\n";
		}
    $player .= "        </Wrapper>\n";
    $player .= "    </Ad>\n";
    return $player;

}
function renderOutput_nonlinearwrapperVast2($aOut, $pluginType, $vastAdDescription,$format,$aBanner)
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
		    if(!empty($aBanner['vast_thirdparty_impression'])) {
			$player .= "                    <Impression>\n";
			$player .= "                       <![CDATA[${aBanner['vast_thirdparty_impression']}]]>\n";
			$player .= "                    </Impression>\n";
		    }		$wrapurl=dynamic_url($aOut, $pluginType, $vastAdDescription,$format,$aBanner);	
	 	 $player .= "<VASTAdTagURI><![CDATA[";
		 $player .= str_replace('&','&amp;',$wrapurl);
		 $player .= "]]></VASTAdTagURI>";

$player.="<Creatives>";

	if ( $pluginType == 'djaxvastOverlay') 
	{
		$code = $resourceType = $creativeType = $elementName = '';
		switch($aOut['overlayFormat']) {
		    case VAST_OVERLAY_FORMAT_HTML:
		        $code = "<![CDATA[". $aOut['overlayMarkupTemplate'] . "]]>";
		        $resourceType = 'HTML';
		        $elementName = 'HTMLResource';
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
		        $elementName = 'StaticResource';
		    break;

		    case VAST_OVERLAY_FORMAT_SWF:
		        $creativeType = 'application/x-shockwave-flash';
		        $code = getImageUrlFromFilename($aOut['overlayFilename']);
		        $resourceType = 'static';
		        $elementName = 'StaticResource';
		    break;

		    case VAST_OVERLAY_FORMAT_TEXT:
		        $resourceType = 'TEXT';
		        $code = "<![CDATA[
		        	<Title>".xmlspecialchars($aOut['overlayTextTitle'])."</Title>
		       		<Description>".xmlspecialchars($aOut['overlayTextDescription'])."</Description>
		       		<CallToAction>".xmlspecialchars($aOut['overlayTextCall'])."</CallToAction>
		       		]]>
		       ";
		        $elementName = 'HTMLResource';
		    break;
		}

		if(!empty($aOut['clickUrl'])) {
		    $nonLinearClickThrough = "<NonLinearClickThrough>
		            <![CDATA[${aOut['clickUrl']}]]>
		        </NonLinearClickThrough>\n";
		}

		$creativeTypeAttribute = '';
		if(!empty($creativeType)) {
		    $creativeType = strtolower($creativeType);
		    $creativeTypeAttribute = 'creativeType="'. $creativeType .'"';
		}
			if($vastAdDescription=='Overlay Video Ad')
			{


					$djax_trackingevent="<TrackingEvents>
							<Tracking event=\"creativeView\"><![CDATA[${aOut['trackUrlcreativeView']}]]></Tracking>
							<Tracking event=\"expand\"><![CDATA[${aOut['trackUrlexpand']}]]></Tracking>
							<Tracking event=\"collapse\"><![CDATA[${aOut['trackUrlcollapse']}]]></Tracking>
							<Tracking event=\"acceptInvitation\"><![CDATA[${aOut['trackUrlacceptInvitation']}]]></Tracking>
							<Tracking event=\"close\"><![CDATA[${aOut['trackUrlclose']}]]></Tracking>
							</TrackingEvents>";

				
					$djax_vast_start="<Creative sequence=\"1\">";

					if($resourceType=='static')
					{
						$player.=" $djax_vast_start<NonLinearAds>\n";
						$player.=" $djax_trackingevent\n";
						$player .= "                <NonLinear id=\"overlay\" >\n";
						$player .= "                    $nonLinearClickThrough";
						$player .= "                </NonLinear>\n";

					}
					else
					{
						$player.=" $djax_vast_start<NonLinearAds>\n";
						$player.=" $djax_trackingevent\n";
						$player .= "<NonLinear id=\"overlay\">\n";
						$player .= "$nonLinearClickThrough";
						$player .= "</NonLinear>\n";
					}

				$player .="</NonLinearAds></Creative>";
		
			}

    	}

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


if ($GLOBALS['_MAX']['SSL_REQUEST']) {
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

    $player.="</Creatives>";
    $player .= "        </Wrapper>\n";
    $player .= "    </Ad>\n";
    return $player;
}
function renderOutput_nonlinearwrapperVast3($aOut, $pluginType, $vastAdDescription,$format,$aBanner)
{

    $adSystem = $GLOBALS['_MAX']['CONF']['ui']['applicationName'] ? $GLOBALS['_MAX']['CONF']['ui']['applicationName'] : 'Revive Adserver';
    $adName = $aOut['name'];
    $player = "";
    $player .= "    <Ad id=\"{player_allocated_ad_id}\" >";
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
		    if(!empty($aOut['thirdPartyImpressionUrl'])) {
		    $player .= "                    <Impression>\n";
			$player .= "                       <![CDATA[${aBanner['vast_thirdparty_impression']}]]>\n";
		    $player .= "                    </Impression>\n";
		    }
		$wrapurl=dynamic_url($aOut, $pluginType, $vastAdDescription,$format,$aBanner);	
	 	  $player .= "<VASTAdTagURI><![CDATA[";
		 $player .= str_replace('&','&amp;',$wrapurl);//$aBanner['vast_overlay_wrapper']);
		 $player .= "]]></VASTAdTagURI>";

$player.="<Creatives>";

	if ( $pluginType == 'djaxvastOverlay') 
	{
		$code = $resourceType = $creativeType = $elementName = '';
		switch($aOut['overlayFormat']) {
		    case VAST_OVERLAY_FORMAT_HTML:
		        $code = "<![CDATA[". $aOut['overlayMarkupTemplate'] . "]]>";
		        $resourceType = 'HTML';
		        $elementName = 'HTMLResource';
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
		        $elementName = 'StaticResource';
		    break;

		    case VAST_OVERLAY_FORMAT_SWF:
		        $creativeType = 'application/x-shockwave-flash';
		        $code = getImageUrlFromFilename($aOut['overlayFilename']);
		        $resourceType = 'static';
		        $elementName = 'StaticResource';
		    break;

		    case VAST_OVERLAY_FORMAT_TEXT:
		        $resourceType = 'TEXT';
		        $code = "<![CDATA[
		        	<Title>".xmlspecialchars($aOut['overlayTextTitle'])."</Title>
		       		<Description>".xmlspecialchars($aOut['overlayTextDescription'])."</Description>
		       		<CallToAction>".xmlspecialchars($aOut['overlayTextCall'])."</CallToAction>
		       		]]>
		       ";
		        $elementName = 'HTMLResource';
		    break;
		}

		if(!empty($aOut['clickUrl'])) 
		{
		    $nonLinearClickThrough .= "<NonLinearClickTracking>
		            <![CDATA[${aOut['clickUrl']}]]>
		        </NonLinearClickTracking>\n";	
		}

		$creativeTypeAttribute = '';
		if(!empty($creativeType)) {
		    $creativeType = strtolower($creativeType);
		    $creativeTypeAttribute = 'creativeType="'. $creativeType .'"';
		}
			if($vastAdDescription=='Overlay Video Ad')
			{
							$djax_trackingevent="<TrackingEvents>
							<Tracking event=\"creativeView\"><![CDATA[${aOut['trackUrlcreativeView']}]]></Tracking>
							<Tracking event=\"expand\"><![CDATA[${aOut['trackUrlexpand']}]]></Tracking>
							<Tracking event=\"collapse\"><![CDATA[${aOut['trackUrlcollapse']}]]></Tracking>
							</TrackingEvents>";
				
				$djax_vast_start="<Creative sequence=\"1\">";

					if($resourceType=='static')
					{
						$player.=" $djax_vast_start<NonLinearAds>\n";
						$player.=" $djax_trackingevent\n";
						$player .= "                <NonLinear id=\"overlay\" >\n";
						$player .= "                    $nonLinearClickThrough";
						$player .= "                </NonLinear>\n";

					}
					else
					{
						$player.=" $djax_vast_start<NonLinearAds>\n";
						$player.=" $djax_trackingevent\n";
						$player .= "<NonLinear id=\"overlay\">\n";
						$player .= "$nonLinearClickThrough";
						$player .= "</NonLinear>\n";
					}

				$player .="</NonLinearAds></Creative>";
		
			}

    	}

	if ( isset($aOut['companionMarkup'])  )
	{
				
				if(!empty($aOut['companionClickUrl'])) 
				{
					 $CompanionClickThrough .= '<CompanionClickTracking>
							  <![CDATA['.$aOut['companionClickUrl'].']]>
							</CompanionClickTracking>';
				}

				if(!empty($aOut['vast_thirdparty_companion_expandedwidth']))
				{
					$expand_width="expandedWidth=\"${aOut['vast_thirdparty_companion_expandedwidth']}\"";
				}
				
				if(!empty($aOut['vast_thirdparty_companion_expandedheight']))
				{
					$expand_height="expandedHeight=\"${aOut['vast_thirdparty_companion_expandedheight']}\"";
				}


if ($GLOBALS['_MAX']['SSL_REQUEST']) {
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
	
		$player.="</Creatives>";

    $player .= "        </Wrapper>\n";
    $player .= "    </Ad>\n";
    return $player;
}


?>
