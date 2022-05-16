<?php
require_once '../../init.php';




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
 $table_prefix = $GLOBALS['_MAX']['CONF']['table']['prefix'];

//echo "hi";
$error=$_GET['errorcode'];
$zone_id=$_GET['zoneid'];
$count='1';


function MAX_commonGetTimeNow()
{
if (!isset($GLOBALS['_MAX']['NOW'])) {
$GLOBALS['_MAX']['NOW'] = time();
}

return $GLOBALS['_MAX']['NOW'];
}
$time = MAX_commonGetTimeNow();

 $oi = $GLOBALS['_MAX']['CONF']['maintenance']['operationInterval'];
 
 $interval_time=gmdate('Y-m-d H:i:s', $time - $time % ($oi * 60));
 //~ print_r($interval_time);
 //~ exit;
if($error=='[ERRORCODE]')
{
	//print_r("HI");
	exit(1);
}
else{
//~ print_r($error);
//~ exit;
$errorMap = array(
     '100' => 1,
     '101' => 2,
     '102' => 3,
     '200' => 4,
     '201' => 5,
     '202' => 6,
     '203' => 7,
     '204' => 8,
     '300' => 9,
     '301' => 10,
     '302' => 11,

     '303'=>12,
     '304'=>13,
     '400'=>14,
     '401'=>15,
     '402'=>16,
     '403'=>17,
     '405'=>18,
     '406'=>19,
     '407'=>20,
     '408'=>21,
     '409'=>22,
     '410'=>23,
     '411'=>24,
     '500'=>25,
     '501'=>26,
     '502'=>27,
      '503'=>28,
     '600'=>29,
     '601'=>30,
     '602'=>31,
     '603'=>32,
     '604'=>33,
     '900'=>34,
     '901'=>35,
     

);
if(!empty($error))
		{
		
 $_check_query = OA_Dal_Delivery_query("select * from djax_error_log Where zone_id = '".$zone_id."' AND error_id='".$errorMap[$error]."' ");
 
 $affs	= OA_Dal_Delivery_fetchAssoc($_check_query);
// print_r($affs);
 
 if (in_array($zone_id, $affs) && in_array($errorMap[$error], $affs))
 {
 //print_r("exists");
 
 
 OA_Dal_Delivery_query("UPDATE djax_error_log SET count=count+$count WHERE zone_id='$zone_id'");
}
 
else{
	
	//echo "hi";
	$insert=OA_Dal_Delivery_query("INSERT INTO djax_error_log
(zone_id,error_id,count,interval_start)
								 VALUES(
								     '".$zone_id."',
								     '".$errorMap[$error]."',
								     
									'".$count."','".$interval_time."')");
									
									
									$r = OA_Dal_Delivery_fetchAssoc($insert)	;
}
 
 
 //~ $row= OA_Dal_Delivery_numRows($_check_query);
 //~ echo $row;
 //~ exit;

//~ if($row = 0 ) {
	//~ 
//~ $insert=OA_Dal_Delivery_query("INSERT INTO djax_error_log
//~ (zone_id,error_id,count)
								 //~ VALUES(
								     //~ '".$zone_id."',
								     //~ '".$errorMap[$error]."',
								     //~ 
									//~ '".$count."')");
									//~ 
									//~ echo $insert;
									//~ exit;
	//~ $r = OA_Dal_Delivery_fetchAssoc($insert)	;		
//~ 
//~ }
//~ else
//~ {
	//~ OA_Dal_Delivery_query("UPDATE djax_error_log SET vast_thirdparty_clickcustom='".$doBanners->vast_thirdparty_clickcustom."' WHERE banner_vast_element_id='$rowId'");
//~ }
}
}
?>
