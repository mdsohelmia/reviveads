<?php
function renderOutput_nonlinearVast2($aOut, $pluginType, $vastAdDescription,$format,$aBanner)
{
    $adSystem = $GLOBALS['_MAX']['CONF']['ui']['applicationName'] ? $GLOBALS['_MAX']['CONF']['ui']['applicationName'] : 'Revive Adserver';
    $adName = $aOut['name'];
    $player = "";
    $player .= "    <Ad id=\"{player_allocated_ad_id}\" >";
    $player .= "        <InLine>";
    $player .= "            <AdSystem><![CDATA[$adSystem]]></AdSystem>\n";
    $player .= "                <AdTitle><![CDATA[$adName]]></AdTitle>\n";
     		    $player .= "                    <Impression>\n";
		    $player .= "                       <![CDATA[${aOut['impressionUrl']}]]>\n";
    $player .= "                    </Impression>\n";
		    if(!empty($aOut['thirdPartyImpressionUrl'])) {

     		    $player .= "                    <Impression>\n";
			$player .= "                       <![CDATA[${aOut['thirdPartyImpressionUrl']}]]>\n";
    $player .= "                    </Impression>\n";
		    }
		

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
		        $code = djaxgetImageUrlFromFilename($aOut['overlayFilename']);
		        $resourceType = 'static';
		        $elementName = 'StaticResource';
		    break;

		    case VAST_OVERLAY_FORMAT_SWF:
		        $creativeType = 'application/x-shockwave-flash';
			$vpaid_swf='';
		        $code = djaxgetImageUrlFromFilename($aOut['overlayFilename']);
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
				$djax_vast_start="<Creative sequence=\"1\">";

					$djax_trackingevent="<TrackingEvents>
							<Tracking event=\"creativeView\"><![CDATA[${aOut['trackUrlcreativeView']}]]></Tracking>
							<Tracking event=\"expand\"><![CDATA[${aOut['trackUrlexpand']}]]></Tracking>
							<Tracking event=\"collapse\"><![CDATA[${aOut['trackUrlcollapse']}]]></Tracking>
							<Tracking event=\"acceptInvitation\"><![CDATA[${aOut['trackUrlacceptInvitation']}]]></Tracking>
							<Tracking event=\"close\"><![CDATA[${aOut['trackUrlclose']}]]></Tracking>		 
							</TrackingEvents>";

					if(!empty($aOut['vast_overlay_expanded_width']))
					{
						$expand_width="expandedWidth=\"${aOut['vast_overlay_expanded_width']}\"";
					}
				
					if(!empty($aOut['vast_overlay_expanded_height']))
					{
						$expand_height="expandedHeight=\"${aOut['vast_overlay_expanded_height']}\"";
					}

					if(!empty($aOut['vast_overlay_expandedminduration']))
					{
						$expand_duration="minSuggestedDuration=\"${aOut['vast_overlay_expandedminduration']}\"";
					}
					if($resourceType=='static')
					{
						$player.=" $djax_vast_start<NonLinearAds>\n";

						$player.="$djax_trackingevent\n";

						$player .= "                <NonLinear id=\"overlay\" width=\"${aBanner['vast_overlay_width']}\" height=\"${aBanner['vast_overlay_height']}\"    maintainAspectRatio=\"true\" $vpaid_swf scalable=\"true\" $expand_duration $expand_width $expand_height>\n";
						$player .= "                    <$elementName $creativeTypeAttribute>
								
															<![CDATA[$code]]>
														</$elementName>\n";
						$player .= "                    $nonLinearClickThrough";
						$player .= "                </NonLinear>\n";

					}
					else
					{
						$player.=" $djax_vast_start<NonLinearAds>\n";
						$player.="$djax_trackingevent\n";
						$player .= "                <NonLinear id=\"overlay\" width=\"${aBanner['vast_overlay_width']}\" height=\"${aBanner['vast_overlay_height']}\"   maintainAspectRatio=\"true\" $vpaid_swf scalable=\"true\" $creativeTypeAttribute $expand_duration $expand_width $expand_height>\n";
						$player .= "                    <$elementName>
								
															$code
														</$elementName>\n";
						$player .= "                    $nonLinearClickThrough";
						$player .= "                </NonLinear>\n";
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
				$createview=$djprotocol.$conf['webpath']['delivery'].'/fc.php?script=djaxvideoAds:djaxvastEvent&bannerid='.$aOut['companionbannerid'].'&zoneid=0&event=creativeView';
				$CompanionTrackingEvents  = "                    <TrackingEvents>\n";
				$CompanionTrackingEvents .= "                        <Tracking><![CDATA[${createview}]]></Tracking>\n";
				$CompanionTrackingEvents .= "                    </TrackingEvents>\n";

				$player.="<Creative sequence=\"1\"><CompanionAds><Companion id=\"companion\" width=\"${aOut['companionWidth']}\" height=\"${aOut['companionHeight']}\" scalable=\"true\"  maintainAspectRatio=\"true\" $expand_width $expand_height ><HTMLResource>
              <![CDATA[${aOut['companionMarkup']}]]>
             </HTMLResource>";
				$player .= "$CompanionTrackingEvents$CompanionClickThrough";
				$player.="</Companion></CompanionAds></Creative>";
	}

    $player.="</Creatives>";
    $player .= "        </InLine>\n";
    $player .= "    </Ad>\n";
    return $player;
}
function renderOutput_nonlinearVast3($aOut, $pluginType, $vastAdDescription,$format,$aBanner)
{
	
	$adSystem = $GLOBALS['_MAX']['CONF']['ui']['applicationName'] ? $GLOBALS['_MAX']['CONF']['ui']['applicationName'] : 'Revive Adserver';
    $adName = $aOut['name'];
    $player = "";
    $player .= "    <Ad id=\"{player_allocated_ad_id}\" >";
    $player .= "        <InLine>";
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
			$player .= "                       <![CDATA[${aOut['thirdPartyImpressionUrl']}]]>\n";
			$player .= "                    </Impression>\n";

		    }

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
		        $code = djaxgetImageUrlFromFilename($aOut['overlayFilename']);
		        $resourceType = 'static';
		        $elementName = 'StaticResource';
		    break;

		    case VAST_OVERLAY_FORMAT_SWF:
		        $creativeType = 'application/x-shockwave-flash';
			$vpaid_swf='';
		        $code = djaxgetImageUrlFromFilename($aOut['overlayFilename']);
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
			if(!empty($aOut['vast_thirdparty_clicktracking'])) {
		    $nonLinearClickThrough .= "<NonLinearClickTracking>
		            <![CDATA[${aOut['vast_thirdparty_clicktracking']}]]>
		        </NonLinearClickTracking>\n";
				}
		}

		$creativeTypeAttribute = '';
		if(!empty($creativeType)) {
		    $creativeType = strtolower($creativeType);
		    $creativeTypeAttribute = 'creativeType="'. $creativeType .'"';
		}
			if($vastAdDescription=='Overlay Video Ad')
			{
				$djax_vast_start="<Creative sequence=\"1\">";

					$djax_trackingevent="<TrackingEvents>
							<Tracking event=\"creativeView\"><![CDATA[${aOut['trackUrlcreativeView']}]]></Tracking>
							<Tracking event=\"expand\"><![CDATA[${aOut['trackUrlexpand']}]]></Tracking>
							<Tracking event=\"collapse\"><![CDATA[${aOut['trackUrlcollapse']}]]></Tracking>
							</TrackingEvents>";
					$overlayadv=unserialize($aBanner['parameters']);
					if(!empty($overlayadv['vast_overlay_expanded_width']))
					{
						$expand_width="expandedWidth=\"${overlayadv['vast_overlay_expanded_width']}\"";
					}
				
					if(!empty($overlayadv['vast_overlay_expanded_height']))
					{
						$expand_height="expandedHeight=\"${overlayadv['vast_overlay_expanded_height']}\"";
					}

					if(!empty($overlayadv['vast_overlay_expandedminduration']))
					{
						$expand_duration="minSuggestedDuration=\"${overlayadv['vast_overlay_expandedminduration']}\"";
					}

					if($resourceType=='static')
					{
						$player.=" $djax_vast_start<NonLinearAds>\n";

						$player.="$djax_trackingevent\n";

						$player .= "                <NonLinear id=\"overlay\" width=\"${aBanner['vast_overlay_width']}\" height=\"${aBanner['vast_overlay_height']}\"   maintainAspectRatio=\"true\" $vpaid_swf $expand_duration $expand_width $expand_height scalable=\"true\" >\n";
						$player .= "                    <$elementName $creativeTypeAttribute>
								
															<![CDATA[$code]]>
														</$elementName>\n";
						$player .= "                    $nonLinearClickThrough";
						$player .= "                </NonLinear>\n";

					}
					else
					{
						$player.=" $djax_vast_start<NonLinearAds>\n";
						$player.="$djax_trackingevent\n";
						$player .= "                <NonLinear id=\"overlay\" width=\"${aBanner['vast_overlay_width']}\" height=\"${aBanner['vast_overlay_height']}\"   height=\"${aOut['overlayHeight']}\" maintainAspectRatio=\"true\" $vpaid_swf  $expand_duration $expand_width $expand_height scalable=\"true\" $creativeTypeAttribute>\n";
						$player .= "                    <$elementName>
								
															$code
														</$elementName>\n";
						$player .= "                    $nonLinearClickThrough";
						$player .= "                </NonLinear>\n";
					}

				$player .="</NonLinearAds></Creative>";
		
			}

    	}

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

					if ($GLOBALS['_MAX']['SSL_REQUEST']) {
					$djprotocol='https://';
					}
					else
					{
					$djprotocol='http://';
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
				$createview=$djprotocol.$conf['webpath']['delivery'].'/fc.php?script=djaxvideoAds:djaxvastEvent&bannerid='.$aOut['companionbannerid'].'&zoneid=0&event=creativeView';
				$CompanionTrackingEvents  = "                    <TrackingEvents>\n";
				$CompanionTrackingEvents .= "                        <Tracking><![CDATA[${createview}]]></Tracking>\n";
				$CompanionTrackingEvents .= "                    </TrackingEvents>\n";
				$player.="<Creative sequence=\"1\"><CompanionAds><Companion id=\"companion\"  width=\"${aOut['companionWidth']}\" height=\"${aOut['companionHeight']}\" $expand_width $expand_height ><HTMLResource>
              <![CDATA[${aOut['companionMarkup']}]]>
             </HTMLResource>";
				
				$player .= "$CompanionTrackingEvents$CompanionClickThrough";
				$player.="</Companion></CompanionAds></Creative>";
				
	}
    $player.="</Creatives>";
    $player .= "        </InLine>\n";
    $player .= "    </Ad>\n";
    return $player;
}





?>
