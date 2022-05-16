<?php
/*
+---------------------------------------------------------------------------+
| Revive Adserver                                                           |
| http://www.revive-adserver.com                                            |
|                                                                           |
| Copyright: See the COPYRIGHT.txt file.                                    |
| License: GPLv2 or later, see the LICENSE.txt file.                        |
+---------------------------------------------------------------------------+
*/

/*
 * NOTE: If this list of event ever changes (IDs or names), the Video Reports must be updated as well
 */
$aVastEventStrToIdMap = array(
     'start' => 1,
     'midpoint' => 2,
     'firstquartile' => 3,
     'thirdquartile' => 4,
     'complete' => 5,
     'mute' => 6,
     'fullscreen' => 7,
     'stop' => 8,
     'unmute' => 9,
     'resume' => 10,
     'pause' => 11,
/*DAC015*/
     'creativeView'=>12,
     'expand'=>13,
     'collapse'=>14,
     'close'=>15,
     'progress'=>16,
     'skip'=>17,
     'closeLinear'=>18,
     'exitfullscreen'=>19,
     'acceptInvitationLinear'=>20,
     'acceptInvitation'=>21,
     'close'=>22,
     'breakStart'=>23,
     'breakEnd'=>24,
     'error'=>25
/*DAC015*/
);

MAX_commonRegisterGlobalsArray(array('event', 'video_time_posn'));

// Prevent the logging beacon from being cached by browsers
MAX_commonSetNoCacheHeaders();

// if its a vast tracking event
if (!empty($bannerid) && isset($aVastEventStrToIdMap[$event])) {
    // Remove any special characters from the request variables
    MAX_commonRemoveSpecialChars($_REQUEST);

    $time = MAX_commonGetTimeNow();
    $oi = $GLOBALS['_MAX']['CONF']['maintenance']['operationInterval'];

    $GLOBALS['_MAX']['deliveryData'] = array(
        'interval_start'    => gmdate('Y-m-d H:i:s', $time - $time % ($oi * 60)),
        'creative_id'       => (int)$bannerid,
        'zone_id'           => (int)$zoneid,
        'vast_event_id'     => $aVastEventStrToIdMap[$event],
    );
    OX_Delivery_Common_hook('logImpressiondjaxVast', array($bannerid, $zoneid, _viewersHostOkayToLog()));
}

MAX_cookieFlush();

MAX_commonDisplay1x1();
