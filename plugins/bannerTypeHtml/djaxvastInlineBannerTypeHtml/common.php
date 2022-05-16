<?php
/*
 *    Copyright (c) 2009 Bouncing Minds - Option 3 Ventures Limited
 *
 *    This file is part of the Regions plug-in for Flowplayer.
 *
 *    The Regions plug-in is free software: you can redistribute it
 *    and/or modify it under the terms of the GNU General Public License
 *    as published by the Free Software Foundation, either version 3 of
 *    the License, or (at your option) any later version.
 *
 *    The Regions plug-in is distributed in the hope that it will be
 *    useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU General Public License for more details.
 *
 *    You should have received a copy of the GNU General Public License
 *    along with the plug-in.  If not, see <http://www.gnu.org/licenses/>.
 */

/*
 * We define these hardcoded width and height parameters for the banner
 * so that other types of zones/banners cannot be linked to these incompatable items
 * In the future, a banner-zone compatability hook will exist
 */
define( 'VAST_OVERLAY_DIMENSIONS', -2 );
define( 'VAST_INLINE_DIMENSIONS', -3 );

define( 'VAST_RTMP_MP4_DELIMITER', 'mp4:' );
define( 'VAST_RTMP_FLV_DELIMITER', 'flv:' );

// Definition of different overlay formats supported
define( 'VAST_OVERLAY_FORMAT_TEXT', 'text_overlay' );
define( 'VAST_OVERLAY_FORMAT_SWF', 'swf_overlay' );
define( 'VAST_OVERLAY_FORMAT_IMAGE', 'image_overlay' );
define( 'VAST_OVERLAY_FORMAT_HTML', 'html_overlay' );

// Definition of different actions supported as a result of a click
define( 'VAST_OVERLAY_CLICK_TO_PAGE', 'click_to_page' );
define( 'VAST_OVERLAY_CLICK_TO_VIDEO', 'click_to_video' );

define( 'VAST_VIDEO_URL_STREAMING_FORMAT', 'streaming' );
define( 'VAST_VIDEO_URL_PROGRESSIVE_FORMAT', 'progressive' );

define('VAST_OVERLAY_DEFAULT_WIDTH', 600);
define('VAST_OVERLAY_DEFAULT_HEIGHT', 40);

if (!function_exists('djaxgetVastVideoTypes')) 
{
	 
	function djaxgetVastVideoTypes()
	{
	   static $videoEncodingTypes = array( 'video/mp4' =>  'MP4',
										   'video/x-flv' => 'FLV',
										   'video/webm' => 'WEBM',
										    'application/x-mpegURL'=>'HLS',
										    
										   // not supported by flowplayer -  'video/x-ms-wmv' => 'WMV',
										   // not supported by flowplayer -  'video/x-ra' => 'video/x-ra',
	   );
	   return $videoEncodingTypes;
	}
}

if (!function_exists('djaxgetVastAudioTypes')) 
{
	 
	function djaxgetVastAudioTypes()
	{
	   static $audioEncodingTypes = array( 'audio/mpeg'=>'Audio/MPEG',	
										    'audio/aac'=>'Audio/AAC'	
										   // '1'=>'Audio/MPEG',	
										    //'2'=>'Audio/AAC'	
										   // not supported by flowplayer -  'video/x-ms-wmv' => 'WMV',
										   // not supported by flowplayer -  'video/x-ra' => 'video/x-ra',
	   );
	   return $audioEncodingTypes;
	}
}
if (!function_exists('djaxencodeUserSuppliedData')) 
{
	function djaxencodeUserSuppliedData($text)
	{
	   return htmlspecialchars($text, ENT_QUOTES);
	}
}

if (!function_exists('djaxxmlspecialchars')) 
{
function djaxxmlspecialchars($text)
{
   return htmlspecialchars($text, ENT_QUOTES);
}
}
if (!function_exists('djaxcombineVideoUrl')) 
{
function djaxcombineVideoUrl( &$aAdminFields )
{
    // If either of these fields are set we know that its a form submit (as these fields do not exist in db)
    if ( $aAdminFields['vast_net_connection_url'] || $aAdminFields['vast_video_filename'] ){

        // In the case of streaming - there are 2 seperate fields stored in the db field vast_video_outgoing_filename
        if ( $aAdminFields['vast_video_delivery'] == 'streaming'  ) {
            $aSeek = array( VAST_RTMP_FLV_DELIMITER, VAST_RTMP_MP4_DELIMITER );
            str_replace( $aSeek, '', $aAdminFields['vast_net_connection_url'] );
            str_replace( $aSeek, '', $aAdminFields['vast_video_filename'] );

            if ( $aAdminFields['vast_video_type'] == 'video/x-flv' ){
                $aAdminFields['vast_video_outgoing_filename'] = $aAdminFields['vast_net_connection_url']  . VAST_RTMP_FLV_DELIMITER  . $aAdminFields['vast_video_filename'];
            }
            elseif ( $aAdminFields['vast_video_type'] == 'video/x-mp4'){
                $aAdminFields['vast_video_outgoing_filename'] = $aAdminFields['vast_net_connection_url'] . VAST_RTMP_MP4_DELIMITER  . $aAdminFields['vast_video_filename'];
            }
        }
        // In the case of progressive - we just store vast_video_filename in the db field vast_video_outgoing_filename
        else {
            $aAdminFields['vast_video_outgoing_filename'] = $aAdminFields['vast_video_filename'];
        }
    }
}
}
if (!function_exists('djaxparseVideoUrl')) 
{
function djaxparseVideoUrl( $inFields, &$aDeliveryFields, &$aAdminFields )
{
	$conf = $GLOBALS['_MAX']['CONF'];
	if($inFields['is_mezzininefile'] == 1){
		if($inFields['internal_file'] != ""){
			$fullPathToVideo = 'http://'.$conf['webpath']['admin'].'/plugins/rmvideoReport/transcoded_video/'.$inFields['internal_file'];
			$aDeliveryFields['fullPathToVideo'] = $fullPathToVideo;
		}
	}
	else{
		$fullPathToVideo = $inFields['vast_video_outgoing_filename'];
		$aDeliveryFields['fullPathToVideo'] = $fullPathToVideo;
	}
    if(($fileDelimPosn = strpos($fullPathToVideo, VAST_RTMP_MP4_DELIMITER)) !== false )
    {
      $netConnectionUrl = substr( $fullPathToVideo, 0, $fileDelimPosn );
      $filename = substr( $fullPathToVideo, $fileDelimPosn + strlen( VAST_RTMP_MP4_DELIMITER ), strlen($fullPathToVideo) );

      $aDeliveryFields['videoNetConnectionUrl'] = $netConnectionUrl;

      // for some unknown reason - I need to have mp4: at the start of the filename to play in the in Admin tool player..
      $aDeliveryFields['videoFileName'] = 'mp4:' . $filename;
      $aDeliveryFields['videoDelivery'] =  'player_in_rtmp_mode';

      // parameters used at admin time
      $aAdminFields['vast_net_connection_url'] =  $netConnectionUrl;
      $aAdminFields['vast_video_filename'] = $filename;
    }
    elseif ( ($fileDelimPosn = strpos($fullPathToVideo, VAST_RTMP_FLV_DELIMITER)) !== false )
    {
      $netConnectionUrl = substr( $fullPathToVideo, 0, $fileDelimPosn );
      $filename = substr( $fullPathToVideo, $fileDelimPosn + strlen( VAST_RTMP_FLV_DELIMITER ), strlen($fullPathToVideo) );

      $aDeliveryFields['videoNetConnectionUrl'] = $netConnectionUrl;
      $aDeliveryFields['videoFileName'] =  $filename;
      $aDeliveryFields['videoDelivery'] = 'player_in_rtmp_mode';

      // parameters used at admin time
      $aAdminFields['vast_net_connection_url'] = $netConnectionUrl;
      $aAdminFields['vast_video_filename'] = $filename;
    }
    else
    {
		if($inFields['is_mezzininefile'] == 1){
			if($inFields['internal_file'] != ""){
				$aDeliveryFields['videoDelivery'] = 'mezzanine_mode';
				$aDeliveryFields['videoFileName'] = $inFields['internal_file'];
				$aAdminFields['vast_video_filename'] = $inFields['internal_file'];
			}				
		}else{
			$aDeliveryFields['videoDelivery'] = 'player_in_http_mode';
			$aDeliveryFields['videoFileName'] = $inFields['vast_video_outgoing_filename'];
			$aAdminFields['vast_video_filename'] = $inFields['vast_video_outgoing_filename'];
		}
    }
}
}
// This will be used to send debug messages to the requesting client
$aClientMessages = array();
if (!function_exists('djaxappendClientMessage')) 
{
function djaxappendClientMessage( $message, $variableToDump = null )
{
    global $aClientMessages;
    if ( $variableToDump ){
        $message .= '<pre>' . print_r( $variableToDump, true ) . '</pre>';
    }
    $aClientMessages[] = $message;
}
}
if (!function_exists('djaxgetClientMessages')) 
{
function djaxgetClientMessages()
{
    global $aClientMessages;
    global $clientdebug;
    $str = "";
    if ( $clientdebug ){
        $str = "<!-- \n";
        foreach( $aClientMessages as $currentMessage ){
            $str .= "$currentMessage\n";
        }
        $str .= " -->\n";
    }
    return $str;
}
}
if (!function_exists('djaxgetVideoPlayerSetting')) 
{
function djaxgetVideoPlayerSetting($parameterId)
{
    $conf = $GLOBALS['_MAX']['CONF'];
    $value = $conf['vastServeVideoPlayer'][$parameterId];

    return $value;
}
}
if (!function_exists('djaxgetVideoOverlaySetting')) 
{
function djaxgetVideoOverlaySetting($parameterId)
{
    $conf = $GLOBALS['_MAX']['CONF'];
    $value = $conf['vastOverlayBannerTypeHtml'][$parameterId];

    return $value;
}
}

if (!function_exists('getvastversion')) 
{
function getvastversion()
{

   static $videoEncodingTypes = array( '1' => 'Vast 1.0',
                                       '2' => 'Vast 2.0',
                                       '3' => 'Vast 3.0',
                                       '4' => 'Vast 4.0',
                                       // not supported by flowplayer -  'video/x-ms-wmv' => 'WMV',
                                       // not supported by flowplayer -  'video/x-ra' => 'video/x-ra',
   );
   return $videoEncodingTypes;


}
}
if (!function_exists('getconditionaltype')) 
{
function getconditionaltype()
{

   static $videoEncodingTypes = array( '1' => 'true',
                                       '2' => 'false',
                                    

   );
   return $videoEncodingTypes;


}
}
if (!function_exists('getadverificationtype')) 
{
function getadverificationtype()
{

   static $videoEncodingTypes = array( '1' => 'Javascript resource',
                                       '2' => 'FlashResource',
   );
   return $videoEncodingTypes;
}
}
if (!function_exists('getvasttype')) 
{
function getvasttype()
{

   static $vasttype = array( '1' => 'VAST 2.0',
                                       '2' => 'VAST 3.0'
   );
   return $vasttype;
}
}
if (!function_exists('get_third_internal_type')) 
{
function get_third_internal_type()
{

   static $get_third_internal_type = array( 			'1' => 'Internal Inline Media Ads',
														'2' => 'Third Party Wrapper Ads',
   );
   return $get_third_internal_type;
}
}
if (!function_exists('getadtype')) 
{
function getadtype()
{

   static $getadtype = array( 			'2' => 'Video',
										'1' => 'Audio',
										//'3' => 'Hybrid'
   );
   return $getadtype;
}
}
//~ if (!function_exists('getaudiotype')) 
//~ {
//~ function getaudiotype()
//~ {

   //~ static $getaudiotype = array( 		'1' => 'Audio/MPEG',
										//~ '2' => 'Audio/AAC'
   //~ );
   //~ return $getaudiotype;
//~ }
//~ }
if (!function_exists('vast_4_1_type_file')) 
{
function vast_4_1_type_file()
{

   static $vast_4_1_type_file = array(			'1' => 'text/srt',
												'2' => 'text/vtt',
												'3' => 'application/ttml+xml'
   );
   return $vast_4_1_type_file;
}
}
if (!function_exists('vast_4_1_language')) 
{
function vast_4_1_language()
{

   static $vast_4_1_language = array(    		'1' => 'en',
												'2' => 'zh-TW',
												'3' => 'zh-CH'
   );
   return $vast_4_1_language;
}
}
class VideoAdsHelperDjax
{
    static function djaxgetWarningMessage($message)
    {
        return "<div class='errormessage' style='width:750px;'><img class='errormessage' src='" . OX::assetPath() . "/images/info.gif' align='absmiddle'>
              <span class='tab-r' style='font-weight:normal;'>&nbsp;". $message ."</span>
              </div>";
    }

    static function djaxdisplayWarningMessage( $message )
    {
        echo self::djaxgetWarningMessage($message);
    }

    static function djaxgetErrorMessage($message)
    {
        return '<div style="" id="errors" class="form-message form-message-error">'. $message .'</div>';
    }

    static function djaxgetHelpLinkVideoPlayerConfig()
    {
        return 'https://documentation.revive-adserver.com/display/DOCS/Invocation+code:+Zone+level#InvocationCode:ZoneLevel-VideoInvocationcodeforInlineVideoadzoneorOverlayVideoadzone';
    }

    static function djaxgetHelpLinkOpenXPlugin()
    {
        return 'http://documentation.revive-adserver.com/display/DOCS/Inline+Video+banners';
    }

    static function djaxgetLinkCrossdomainExample()
    {
        return 'http://documentation.revive-adserver.com/display/DOCS/Server+Cross+Domain+Policy';
    }

    
}
