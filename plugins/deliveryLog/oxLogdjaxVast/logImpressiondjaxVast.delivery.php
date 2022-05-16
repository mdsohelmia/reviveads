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


MAX_Dal_Delivery_Include();

function Plugin_deliveryLog_oxLogdjaxVast_logImpressiondjaxVast_Delivery_logImpressiondjaxVast($adId = 0, $zoneId = 0, $okToLog = true)
{ 
    $aData = $GLOBALS['_MAX']['deliveryData'];
    $table_prefix = $GLOBALS['_MAX']['CONF']['table']['prefix'];
    	if (!$okToLog || empty($aData['interval_start']) || empty($aData['vast_event_id'])) {
        return false;
    	}
    	$djax_plots=array(1,2,3,4,5,7,8,6,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25);

	foreach($djax_plots as $eventid)
	{
		$chk_timespent=OA_Dal_Delivery_query("select * from {$table_prefix}data_bkt_djaxvast_e where creative_id='".$aData['creative_id']."' and zone_id='".$aData['zone_id']."' and interval_start='".$aData['interval_start']."' and vast_event_id='$eventid'");

	if(OA_Dal_Delivery_numRows($chk_timespent)==0)
	{
		OA_Dal_Delivery_query("INSERT INTO {$table_prefix}data_bkt_djaxvast_e(`interval_start`, `creative_id`, `zone_id`,count,vast_event_id) VALUES ('".$aData['interval_start']."','".$aData['creative_id']."','".$aData['zone_id']."','0','".$eventid."')");
	}
	}

    $aQuery = array(
        'interval_start' => $aData['interval_start'],
        'creative_id'    => $aData['creative_id'],
        'zone_id'        => $aData['zone_id'],
        'vast_event_id'  => $aData['vast_event_id'],
    );

    return OX_bucket_updateTable('data_bkt_djaxvast_e', $aQuery);
}
