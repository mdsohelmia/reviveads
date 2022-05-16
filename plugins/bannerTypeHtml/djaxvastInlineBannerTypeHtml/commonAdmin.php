<?php
ob_start();
require_once LIB_PATH . '/Extension/bannerTypeHtml/bannerTypeHtml.php';
require_once MAX_PATH . '/plugins/bannerTypeHtml/djaxvastInlineBannerTypeHtml/common.php';

if($GLOBALS['_MAX']['CONF']['database']['type']=='mysql'){
	require_once MAX_PATH . '/lib/OA/Dal/Delivery/mysql.php';
}else if($GLOBALS['_MAX']['CONF']['database']['type']=='pgsql'){
	require_once MAX_PATH . '/lib/OA/Dal/Delivery/pgsql.php';
}else if($GLOBALS['_MAX']['CONF']['database']['type']=='mysqli'){
	require_once MAX_PATH . '/lib/OA/Dal/Delivery/mysqli.php';
}
abstract class Plugins_BannerTypeHTML_djaxvastInlineBannerTypeHtml_vastBase extends Plugins_BannerTypeHTML
{
    abstract function getBannerShortName();
    abstract function getZoneToLinkShortName();
    abstract function getHelpAdTypeDescription();
    private $requiredElement = array();

    function getContentType(){
        return 'html';
    }

    function getStorageType(){
        return 'html';
    }

    private $validationFailed = false;

    function buildForm(&$form, &$bannerRow){
        if($form->isSubmitted()) {
            $form->addElement('html', 'video_form_error', VideoAdsHelper::getWarningMessage('Validation failed!'));
        }
    }
    function preprocessForm($insert, $bannerid, &$aFields, &$aVariables){
        combineVideoUrl( $aFields ); 
        $aVastVariables = array();

        $aVastVariables['banner_vast_element_id'] = $aFields['banner_vast_element_id'];
        $aVastVariables['vast_element_type'] = 'singlerow'; //$aFields['vast_element_type'];
        $aVastVariables['vast_type'] = $aFields['vast_type'];
        $aVastVariables['vast_video_id'] = $aFields['vast_video_id'];
        $aVastVariables['vast_video_duration'] = $aFields['vast_video_duration'];
        $aVastVariables['vast_video_delivery'] = $aFields['vast_video_delivery'];
        $aVastVariables['get_third_internal_type'] = $aFields['get_third_internal_type'];
        $aVastVariables['vast_video_type'] = $aFields['vast_video_type'];
        $aVastVariables['vast_video_bitrate'] = $aFields['vast_video_bitrate'];
        $aVastVariables['vast_video_height'] = $aFields['vast_video_height'];
        $aVastVariables['vast_video_width'] = $aFields['vast_video_width'];
        $aVastVariables['vast_video_outgoing_filename'] = $aFields['vast_video_outgoing_filename'];
        $aVastVariables['vast_video_clickthrough_url'] = $aFields['vast_video_clickthrough_url'];
        $aVastVariables['vast_overlay_height'] = $aFields['vast_overlay_height'];
        $aVastVariables['vast_overlay_width'] = $aFields['vast_overlay_width'];
        $aVastVariables['vast_overlay_text_title'] = $aFields['vast_overlay_text_title'];
        $aVastVariables['vast_overlay_text_description'] = $aFields['vast_overlay_text_description'];
        $aVastVariables['vast_overlay_text_call'] = $aFields['vast_overlay_text_call'];
        $aVastVariables['vast_overlay_format'] = $aFields['vast_overlay_format'];
        $aVastVariables['vast_overlay_action'] = $aFields['vast_overlay_action'];
        $aVastVariables['vast_companion_banner_id'] = $aFields['vast_companion_banner_id'];
        $aVastVariables['vast_creative_type'] = $aFields['vast_creative_type'];
        $aVastVariables['vast_thirdparty_impression'] = $aFields['vast_thirdparty_impression'];
		$aVastVariables['vast_thirdparty_companion_clicktracking']= $aFields['vast_thirdparty_companion_clicktracking'];
		$aVastVariables['vast_thirdparty_clickcustom']= $aFields['vast_thirdparty_clickcustom'];
		$aVastVariables['vast_thirdparty_clicktracking']= $aFields['vast_thirdparty_clicktracking'];
		$aVastVariables['vast_overlay_expanded_width'] = $aFields['vast_overlay_expanded_width'];
		$aVastVariables['vast_overlay_expanded_height']= $aFields['vast_overlay_expanded_height'];
		$aVastVariables['vast_thirdparty_companion_expandedwidth']= $aFields['vast_thirdparty_companion_expandedwidth'];
		$aVastVariables['vast_thirdparty_companion_expandedheight']= $aFields['vast_thirdparty_companion_expandedheight'];
		$aVastVariables['vast_overlay_expandedminduration']= $aFields['vast_overlay_expandedminduration'];
		$aVastVariables['vast_video_skip_duration'] = $aFields['vast_video_skip_duration'];
		$aVastVariables['vast_video_skip_progress_duration'] = $aFields['vast_video_skip_progress_duration'];

		if($aFields['vast_overlay_format']=='VAST_OVERLAY_FORMAT_WRAPPER'){
			$aVastVariables['vast_overlay_wrapper'] = $aFields['vast_overlay_wrapper'];
			$aVastVariables['vast_overlay_version'] = $aFields['vast_overlay_version'];
		}else{
			$aVastVariables['vast_wrapper_url'] = $aFields['vast_wrapper_url'];
			$aVastVariables['vast_version'] = $aFields['vast_version'];
		}

		
		$aVastVariables['ad_type'] 							= $aFields['ad_type'];
		$aVastVariables['url_file'] 						= $aFields['url_file'];
		$aVastVariables['internal_file'] 					= $aFields['internal_file'];                

        $aVariables['parameters'] = serialize($aVastVariables);

        // attach the parameters to the nomal array to be stored as per normal DataObject technique
        $aVariables = array_merge($aVariables, $aVastVariables);
        return true;
    }
    
    function processForm($insert, $bannerid, $aFields){
        $doBanners = OA_Dal::factoryDO('djaxbanner_vast_element');
        $rowId = $aFields['banner_vast_element_id'];
        $doBanners->vast_element_type               = $aFields['vast_element_type'];
        $doBanners->vast_video_id                   = $aFields['vast_video_id'];
        $doBanners->vast_type                   	= $aFields['vast_type'];
        $doBanners->vast_video_duration             = $aFields['vast_video_duration'];
        $doBanners->vast_video_delivery             = $aFields['vast_video_delivery'];
        $doBanners->get_third_internal_type         = $aFields['get_third_internal_type'];
        $doBanners->vast_video_type                 = $aFields['vast_video_type'];
        $doBanners->vast_video_bitrate              = $aFields['vast_video_bitrate'];
        $doBanners->vast_video_height               = $aFields['vast_video_height'];
        $doBanners->vast_video_width                = $aFields['vast_video_width'];
        $doBanners->vast_video_outgoing_filename    = $aFields['vast_video_outgoing_filename'];
        $doBanners->vast_video_clickthrough_url     = $aFields['vast_video_clickthrough_url'];
        $doBanners->vast_overlay_height             = $aFields['vast_overlay_height'];
        $doBanners->vast_overlay_width              = $aFields['vast_overlay_width'];
        $doBanners->vast_overlay_action             = $aFields['vast_overlay_action'];
        $doBanners->vast_overlay_format             = $aFields['vast_overlay_format'];
        $doBanners->vast_overlay_text_title         = $aFields['vast_overlay_text_title'];
        $doBanners->vast_overlay_text_description   = $aFields['vast_overlay_text_description'];
        $doBanners->vast_overlay_text_call          = $aFields['vast_overlay_text_call'];
        $doBanners->vast_companion_banner_id        = $aFields['vast_companion_banner_id'];
        $doBanners->vast_creative_type              = $aFields['vast_creative_type'];
        $doBanners->vast_thirdparty_impression      = $aFields['vast_thirdparty_impression'];	
        $doBanners->vast_video_skip_duration = $aFields['vast_video_skip_duration'];
		$doBanners->vast_video_skip_progress_duration = $aFields['vast_video_skip_progress_duration'];
		$doBanners->vast_thirdparty_companion_clicktracking= $aFields['vast_thirdparty_companion_clicktracking'];
		$doBanners->vast_thirdparty_clickcustom= $aFields['vast_thirdparty_clickcustom'];
		$doBanners->vast_thirdparty_clicktracking= $aFields['vast_thirdparty_clicktracking'];
		$doBanners->vast_overlay_expanded_width = $aFields['vast_overlay_expanded_width'];
		$doBanners->vast_overlay_expanded_height= $aFields['vast_overlay_expanded_height'];
		$doBanners->vast_thirdparty_companion_expandedwidth= $aFields['vast_thirdparty_companion_expandedwidth'];
		$doBanners->vast_thirdparty_companion_expandedheight= $aFields['vast_thirdparty_companion_expandedheight'];
		$doBanners->vast_overlay_expandedminduration= $aFields['vast_overlay_expandedminduration'];

		
          //new
        
        $doBanners->change	     						= $_POST['change'];        
		if($aFields['vast_overlay_format']=='VAST_OVERLAY_FORMAT_WRAPPER'){	
			$doBanners->vast_overlay_wrapper= $aFields['vast_overlay_wrapper'];
			$doBanners->vast_overlay_version= $aFields['vast_overlay_version'];
		}else{
			$doBanners->vast_wrapper_url= $aFields['vast_wrapper_url'];
			$doBanners->vast_version= $aFields['vast_version'];
		}
        if ( !$insert && ($rowId == 'banner_vast_element_id') ){
            $insert = true;
        }
		$table_prefix = $GLOBALS['_MAX']['CONF']['table']['prefix'];	
        if ($insert){	
            $doBanners->banner_vast_element_id = $bannerid;
            $doBanners->banner_id            = $bannerid;
            $rowId=$doBanners->insert();
			if(!empty($doBanners->get_third_internal_type)){
				OA_Dal_Delivery_query("UPDATE {$table_prefix}djaxbanner_vast_element SET get_third_internal_type='".$doBanners->get_third_internal_type."' WHERE banner_vast_element_id='$rowId'");
			}
			if(!empty($doBanners->vast_type)){
				OA_Dal_Delivery_query("UPDATE {$table_prefix}djaxbanner_vast_element SET vast_type='".$doBanners->vast_type."' WHERE banner_vast_element_id='$rowId'");
			}
			if(!empty($doBanners->vast_companion_banner_id ) || ($doBanners->vast_companion_banner_id == 0)){
				OA_Dal_Delivery_query("UPDATE {$table_prefix}djaxbanner_vast_element SET vast_companion_banner_id='".$doBanners->vast_companion_banner_id."' WHERE banner_vast_element_id='$rowId'");
			}
			if(!empty($doBanners->vast_video_skip_duration)){
				OA_Dal_Delivery_query("UPDATE {$table_prefix}djaxbanner_vast_element SET vast_video_skip_duration='".$doBanners->vast_video_skip_duration."' WHERE banner_vast_element_id='$rowId'");
			}
			if(!empty($doBanners->vast_video_skip_progress_duration)){
				OA_Dal_Delivery_query("UPDATE {$table_prefix}djaxbanner_vast_element SET vast_video_skip_progress_duration='".$doBanners->vast_video_skip_progress_duration."' WHERE banner_vast_element_id='$rowId'");
			}
			if(!empty($doBanners->vast_wrapper_url)){
				OA_Dal_Delivery_query("UPDATE {$table_prefix}djaxbanner_vast_element SET vast_wrapper_url='".$doBanners->vast_wrapper_url."' WHERE banner_vast_element_id='$rowId'");
			}
			if(!empty($doBanners->vast_version)){
				OA_Dal_Delivery_query("UPDATE {$table_prefix}djaxbanner_vast_element SET vast_version='".$doBanners->vast_version."' WHERE banner_vast_element_id='$rowId'");
			}
			if(!empty($doBanners->vast_thirdparty_clicktracking)){
				OA_Dal_Delivery_query("UPDATE {$table_prefix}djaxbanner_vast_element SET vast_thirdparty_clicktracking='".$doBanners->vast_thirdparty_clicktracking."' WHERE banner_vast_element_id='$rowId'");
			}
			if(!empty($doBanners->vast_thirdparty_clickcustom)){
				OA_Dal_Delivery_query("UPDATE {$table_prefix}djaxbanner_vast_element SET vast_thirdparty_clickcustom='".$doBanners->vast_thirdparty_clickcustom."' WHERE banner_vast_element_id='$rowId'");
			}
			if(!empty($doBanners->vast_thirdparty_companion_clicktracking)){
				OA_Dal_Delivery_query("UPDATE {$table_prefix}djaxbanner_vast_element SET vast_thirdparty_companion_clicktracking='".$doBanners->vast_thirdparty_companion_clicktracking."' WHERE banner_vast_element_id='$rowId'");
			}
			if(!empty($doBanners->vast_thirdparty_companion_expandedheight)){
				OA_Dal_Delivery_query("UPDATE {$table_prefix}djaxbanner_vast_element SET vast_thirdparty_companion_expandedheight='".$doBanners->vast_thirdparty_companion_expandedheight."' WHERE banner_vast_element_id='$rowId'");
			}
			if(!empty($doBanners->vast_thirdparty_companion_expandedwidth)){
				OA_Dal_Delivery_query("UPDATE {$table_prefix}djaxbanner_vast_element SET vast_thirdparty_companion_expandedwidth='".$doBanners->vast_thirdparty_companion_expandedwidth."' WHERE banner_vast_element_id='$rowId'");
			}
			if(!empty($doBanners->vast_overlay_expanded_height)){
				OA_Dal_Delivery_query("UPDATE {$table_prefix}djaxbanner_vast_element SET vast_overlay_expanded_height='".$doBanners->vast_overlay_expanded_height."' WHERE banner_vast_element_id='$rowId'");
			}
			if(!empty($doBanners->vast_overlay_expanded_width)){
				OA_Dal_Delivery_query("UPDATE {$table_prefix}djaxbanner_vast_element SET vast_overlay_expanded_width='".$doBanners->vast_overlay_expanded_width."' WHERE banner_vast_element_id='$rowId'");
			}
			if(!empty($doBanners->vast_overlay_expandedminduration)){
				OA_Dal_Delivery_query("UPDATE {$table_prefix}djaxbanner_vast_element SET vast_overlay_expandedminduration='".$doBanners->vast_overlay_expandedminduration."' WHERE banner_vast_element_id='$rowId'");
			}
			if(!empty($doBanners->vast_overlay_version)){
				OA_Dal_Delivery_query("UPDATE {$table_prefix}djaxbanner_vast_element SET vast_overlay_version='".$doBanners->vast_overlay_version."' WHERE banner_vast_element_id='$rowId'");
			}
			if(!empty($doBanners->vast_overlay_wrapper)){
				OA_Dal_Delivery_query("UPDATE {$table_prefix}djaxbanner_vast_element SET vast_overlay_wrapper='".$doBanners->vast_overlay_wrapper."' WHERE banner_vast_element_id='$rowId'");
			}
					
			if($doBanners->vast_type == 1){
					OA_Dal_Delivery_query("UPDATE {$table_prefix}djaxbanner_vast_element SET ad_type='',vast_video_outgoing_filename1 = '',vast_video_bitrate_1 = '',vast_video_type_1='',vast_video_outgoing_filename2 = '',vast_video_bitrate_2 = '',vast_video_type_2 = '',is_mezzininefile = '',mezzanine_en = '',url_file = '',internal_file = '',vast_4_1_url = '',vast_4_1_type_file = '',vast_4_1_language = '',vast_4_1_url_1 = '',vast_4_1_type_file_1 = '',vast_4_1_language_1 = '',vast_4_1_url_2 = '',vast_4_1_type_file_2 = '',vast_4_1_language_2 = '',vast_video_skip_duration = '',vast_video_skip_progress_duration = '',vast_wrapper_fallbacknoads = '',vast_wrapper_allowmultipleads = '',vast_wrapper_followadditional = '',vast_version = '' WHERE banner_vast_element_id='$rowId'");	
					if($doBanners->get_third_internal_type == 2){
						OA_Dal_Delivery_query("UPDATE {$table_prefix}djaxbanner_vast_element SET vast_video_delivery = '',vast_net_connection_url = '',vast_video_filename = '',vast_video_type = '',vast_video_duration = '' WHERE banner_vast_element_id='$rowId'");	
					}
					else{
						OA_Dal_Delivery_query("UPDATE {$table_prefix}djaxbanner_vast_element SET vast_wrapper_url = '',vast_version = '' WHERE banner_vast_element_id='$rowId'");							
					}										
			}
			elseif($doBanners->vast_type == 2){
				OA_Dal_Delivery_query("UPDATE {$table_prefix}djaxbanner_vast_element SET ad_type='',vast_video_outgoing_filename1 = '',vast_video_bitrate_1 = '',vast_video_type_1='',vast_video_outgoing_filename2 = '',vast_video_bitrate_2 = '',vast_video_type_2 = '',is_mezzininefile = '',mezzanine_en = '',url_file = '',internal_file = '',vast_4_1_url = '',vast_4_1_type_file = '',vast_4_1_language = '',vast_4_1_url_1 = '',vast_4_1_type_file_1 = '',vast_4_1_language_1 = '',vast_4_1_url_2 = '',vast_4_1_type_file_2 = '',vast_4_1_language_2 = '',vast_wrapper_fallbacknoads = '',vast_wrapper_allowmultipleads = '',vast_wrapper_followadditional = '' ,vast_version = '' WHERE banner_vast_element_id='$rowId'");
				if($doBanners->get_third_internal_type == 2){
					OA_Dal_Delivery_query("UPDATE {$table_prefix}djaxbanner_vast_element SET vast_video_delivery = '',vast_net_connection_url = '',vast_video_filename = '',vast_video_type = '',vast_video_duration = '' WHERE banner_vast_element_id='$rowId'");	
				}
				else{
					OA_Dal_Delivery_query("UPDATE {$table_prefix}djaxbanner_vast_element SET vast_wrapper_url = '',vast_version = '' WHERE banner_vast_element_id='$rowId'");							
				}																	
			}	
			return 1;
        }
        else
        {
			$query_new=OA_Dal_Delivery_query("SELECT * from {$table_prefix}djaxbanner_vast_element  where banner_vast_element_id = ".$rowId);
			$row_new = OA_Dal_Delivery_fetchAssoc($query_new);				
			if(!empty($doBanners->vast_companion_banner_id) || ($doBanners->vast_companion_banner_id == 0)){
				OA_Dal_Delivery_query("UPDATE {$table_prefix}djaxbanner_vast_element SET vast_companion_banner_id='".$doBanners->vast_companion_banner_id."' WHERE banner_vast_element_id='$rowId'");
			}								
			if(!empty($doBanners->vast_video_delivery)){
				OA_Dal_Delivery_query("UPDATE {$table_prefix}djaxbanner_vast_element SET vast_video_delivery='".$doBanners->vast_video_delivery."' WHERE banner_vast_element_id='$rowId'");
			}
			if(!empty($doBanners->vast_type)){
				OA_Dal_Delivery_query("UPDATE {$table_prefix}djaxbanner_vast_element SET vast_type='".$doBanners->vast_type."' WHERE banner_vast_element_id='$rowId'");
			}
			if(!empty($doBanners->get_third_internal_type)){
				OA_Dal_Delivery_query("UPDATE {$table_prefix}djaxbanner_vast_element SET get_third_internal_type='".$doBanners->get_third_internal_type."' WHERE banner_vast_element_id='$rowId'");
			}
			
			if(!empty($doBanners->vast_video_skip_duration)){
				OA_Dal_Delivery_query("UPDATE {$table_prefix}djaxbanner_vast_element SET vast_video_skip_duration='".$doBanners->vast_video_skip_duration."' WHERE banner_vast_element_id='$rowId'");
			}
			if(!empty($doBanners->vast_video_skip_progress_duration)){
				OA_Dal_Delivery_query("UPDATE {$table_prefix}djaxbanner_vast_element SET vast_video_skip_progress_duration='".$doBanners->vast_video_skip_progress_duration."' WHERE banner_vast_element_id='$rowId'");
			}
			if(!empty($doBanners->vast_wrapper_url)){
				OA_Dal_Delivery_query("UPDATE {$table_prefix}djaxbanner_vast_element SET vast_wrapper_url='".$doBanners->vast_wrapper_url."' WHERE banner_vast_element_id='$rowId'");
			}
			if(!empty($doBanners->vast_version)){
				OA_Dal_Delivery_query("UPDATE {$table_prefix}djaxbanner_vast_element SET vast_version='".$doBanners->vast_version."' WHERE banner_vast_element_id='$rowId'");
			}
			if(!empty($doBanners->vast_thirdparty_clicktracking)){
				OA_Dal_Delivery_query("UPDATE {$table_prefix}djaxbanner_vast_element SET vast_thirdparty_clicktracking='".$doBanners->vast_thirdparty_clicktracking."' WHERE banner_vast_element_id='$rowId'");
			}
			if(!empty($doBanners->vast_thirdparty_clickcustom)){
				OA_Dal_Delivery_query("UPDATE {$table_prefix}djaxbanner_vast_element SET vast_thirdparty_clickcustom='".$doBanners->vast_thirdparty_clickcustom."' WHERE banner_vast_element_id='$rowId'");
			}
			if(!empty($doBanners->vast_thirdparty_companion_clicktracking)){
				OA_Dal_Delivery_query("UPDATE {$table_prefix}djaxbanner_vast_element SET vast_thirdparty_companion_clicktracking='".$doBanners->vast_thirdparty_companion_clicktracking."' WHERE banner_vast_element_id='$rowId'");
			}
			if(!empty($doBanners->vast_thirdparty_companion_expandedheight)){
				OA_Dal_Delivery_query("UPDATE {$table_prefix}djaxbanner_vast_element SET vast_thirdparty_companion_expandedheight='".$doBanners->vast_thirdparty_companion_expandedheight."' WHERE banner_vast_element_id='$rowId'");
			}
			if(!empty($doBanners->vast_thirdparty_companion_expandedwidth)){
				OA_Dal_Delivery_query("UPDATE {$table_prefix}djaxbanner_vast_element SET vast_thirdparty_companion_expandedwidth='".$doBanners->vast_thirdparty_companion_expandedwidth."' WHERE banner_vast_element_id='$rowId'");
			}
			if(!empty($doBanners->vast_overlay_expanded_height)){
				OA_Dal_Delivery_query("UPDATE {$table_prefix}djaxbanner_vast_element SET vast_overlay_expanded_height='".$doBanners->vast_overlay_expanded_height."' WHERE banner_vast_element_id='$rowId'");
			}
			if(!empty($doBanners->vast_overlay_expanded_width)){
				OA_Dal_Delivery_query("UPDATE {$table_prefix}djaxbanner_vast_element SET vast_overlay_expanded_width='".$doBanners->vast_overlay_expanded_width."' WHERE banner_vast_element_id='$rowId'");
			}
			if(!empty($doBanners->vast_overlay_expandedminduration)){
				OA_Dal_Delivery_query("UPDATE {$table_prefix}djaxbanner_vast_element SET vast_overlay_expandedminduration='".$doBanners->vast_overlay_expandedminduration."' WHERE banner_vast_element_id='$rowId'");
			}
			if(!empty($doBanners->vast_overlay_version)){
				OA_Dal_Delivery_query("UPDATE {$table_prefix}djaxbanner_vast_element SET vast_overlay_version='".$doBanners->vast_overlay_version."' WHERE banner_vast_element_id='$rowId'");
			}
		
			$query_url_file = OA_Dal_Delivery_query("select url_file from  {$table_prefix}djaxbanner_vast_element WHERE banner_vast_element_id='$rowId'");
			$row_url_file = OA_Dal_Delivery_fetchAssoc($query_url_file);			
			if($doBanners->vast_type == 1){
					OA_Dal_Delivery_query("UPDATE {$table_prefix}djaxbanner_vast_element SET ad_type='',vast_video_outgoing_filename1 = '',vast_video_bitrate_1 = '',vast_video_type_1='',vast_video_outgoing_filename2 = '',vast_video_bitrate_2 = '',vast_video_type_2 = '',is_mezzininefile = '',mezzanine_en = '',url_file = '',internal_file = '',vast_4_1_url = '',vast_4_1_type_file = '',vast_4_1_language = '',vast_4_1_url_1 = '',vast_4_1_type_file_1 = '',vast_4_1_language_1 = '',vast_4_1_url_2 = '',vast_4_1_type_file_2 = '',vast_4_1_language_2 = '',vast_video_skip_duration = '',vast_video_skip_progress_duration = '',vast_wrapper_fallbacknoads = '',vast_wrapper_allowmultipleads = '',vast_wrapper_followadditional = '' WHERE banner_vast_element_id='$rowId'");	
					if($doBanners->get_third_internal_type == 2){
						OA_Dal_Delivery_query("UPDATE {$table_prefix}djaxbanner_vast_element SET vast_video_delivery = '',vast_net_connection_url = '',vast_video_filename = '',vast_video_type = '',vast_video_duration = '' ,vast_video_outgoing_filename = '',vast_video_bitrate = '', vast_video_type = '' WHERE banner_vast_element_id='$rowId'");	
					} else {
						OA_Dal_Delivery_query("UPDATE {$table_prefix}djaxbanner_vast_element SET vast_wrapper_url = '',vast_version = '' WHERE banner_vast_element_id='$rowId'");							
					}										
			}
			elseif($doBanners->vast_type == 2){
					OA_Dal_Delivery_query("UPDATE {$table_prefix}djaxbanner_vast_element SET ad_type='',vast_video_outgoing_filename1 = '',vast_video_bitrate_1 = '',vast_video_type_1='',vast_video_outgoing_filename2 = '',vast_video_bitrate_2 = '',vast_video_type_2 = '',is_mezzininefile = '',mezzanine_en = '',url_file = '',internal_file = '',vast_4_1_url = '',vast_4_1_type_file = '',vast_4_1_language = '',vast_4_1_url_1 = '',vast_4_1_type_file_1 = '',vast_4_1_language_1 = '',vast_4_1_url_2 = '',vast_4_1_type_file_2 = '',vast_4_1_language_2 = '',vast_wrapper_fallbacknoads = '',vast_wrapper_allowmultipleads = '',vast_wrapper_followadditional = '' WHERE banner_vast_element_id='$rowId'");
					if($doBanners->get_third_internal_type == 2){
						OA_Dal_Delivery_query("UPDATE {$table_prefix}djaxbanner_vast_element SET vast_video_delivery = '',vast_net_connection_url = '',vast_video_filename = '',vast_video_type = '',vast_video_duration = '',vast_video_outgoing_filename = '',vast_video_bitrate = '', vast_video_type = '' WHERE banner_vast_element_id='$rowId'");	
					} else {
						OA_Dal_Delivery_query("UPDATE {$table_prefix}djaxbanner_vast_element SET vast_wrapper_url = '',vast_version = '' WHERE banner_vast_element_id='$rowId'");							
					}																	
			}
			$doBanners->whereAdd('banner_vast_element_id='. (int)$rowId, 'AND');
			return $doBanners->update(DB_DATAOBJECT_WHEREADD_ONLY);
        }
    }

    function getExtendedBannerInfo($banner){
        $actualBannerId = $banner['bannerid'];
        $vastElements = array();
        if ( $actualBannerId ){
            $vastElements = $this->fetchBannersJoined($actualBannerId);
            if ( isset($vastElements[0]) ){
		if($vastElements[0]['vast_overlay_expanded_width']==0){$vastElements[0]['vast_overlay_expanded_width']='';}
		if($vastElements[0]['vast_overlay_expanded_height']==0){$vastElements[0]['vast_overlay_expanded_height']='';}
		if($vastElements[0]['vast_thirdparty_companion_expandedwidth']==0){$vastElements[0]['vast_thirdparty_companion_expandedwidth']='';}
		if($vastElements[0]['vast_thirdparty_companion_expandedheight']==0){$vastElements[0]['vast_thirdparty_companion_expandedheight']='';}
                $elementRow = $vastElements[0];
                $banner = array_merge( $banner, $elementRow );
            }

            $aDeliveryFieldsNotUsed = array();
            parseVideoUrl( $banner, $aDeliveryFieldsNotUsed, $banner );
        }
        return $banner;
    }

    function fetchBannersJoined($bannerId, $fetchmode=MDB2_FETCHMODE_ORDERED){
        $aConf  = $GLOBALS['_MAX']['CONF']['table'];
        $oDbh   = OA_DB::singleton();
        $tblB   = $oDbh->quoteIdentifier($aConf['prefix'].'banners',true);
        $tblD   = $oDbh->quoteIdentifier($aConf['prefix'].'djaxbanner_vast_element');
        $query  = "SELECT d.* FROM ".$tblB." b"
                     ." LEFT JOIN ".$tblD." d ON b.bannerid = d.banner_id"
                     ." WHERE b.ext_bannertype = '".$this->getComponentIdentifier()."'"
                     ." AND b.bannerid = ".(int)$bannerId;
        $joinedResult = $oDbh->queryAll($query, null, MDB2_FETCHMODE_ASSOC, false, false, true );
        return $joinedResult;
    }

    function validateForm(&$form){
        if($form->isSubmitted()) {
            $errors = array();           
	   if($form->getSubmitValue('vast_video_delivery')!='vast')
	   {
            foreach($this->requiredElement as $requiredElement) {
                $fieldName = $requiredElement[0];
                $fieldNameWhenRequired = $requiredElement[1];
                $fieldValueWhenRequired = $requiredElement[2];
                $fieldValueWhenRequiredSubmittedValue = $form->getSubmitValue($fieldNameWhenRequired);
                if($fieldValueWhenRequiredSubmittedValue == $fieldValueWhenRequired) {
                    $submittedValue = $form->getSubmitValue($fieldName);
                    if(empty($submittedValue)) {
                        $errors[] = $this->getFieldLabel($fieldName);
                    }
                }
            }
			array_splice($errors, 0);
            if(count($errors) == 0) { 
                if ($form->getSubmitValue('vast_video_type') != 'video/webm' || $form->getSubmitValue('vast_video_delivery') == 'progressive') {
                    $form->removeElement('video_form_error');
                    return true;
                } else {
                    $errorString = 'WEBM video type is not compatible with streaming delivery';
                }
            } else {
                $errorString = 'Please provide values for all required fields: <ul><li>';
                $errorString .= implode('</li><li>', $errors);
                $errorString .= '</li></ul>';
            }

            $form->getElement('video_form_error')->setText(VideoAdsHelper::getErrorMessage($errorString));
            return false;

	}else{
		if (!($form->getSubmitValue('vast_wrapper_url')!='' && $form->getSubmitValue('vast_version')!='')){
			  $errorString = 'Please provide values for all required field of third party vast tag';

		}
	}
        }             
        return true;
    }

    function getPossibleCompanions($aBannerRow){
        $aParams = array( 'placement_id' => $aBannerRow['campaignid'] );
        $possibleCompanions = Admin_DA::_getEntities('ad', $aParams, true);
        $selectableCompanions = array( 0 => 'none' );
        foreach( $possibleCompanions as $currentCompanion ){
            // Only allow linking to banners that are not of type "vast"
            if ( strpos( $currentCompanion['ext_bannertype'], 'vast' ) === false ){
                $strNameToDisplay = $currentCompanion['name'] . " (" . $currentCompanion['width'] . "x" . $currentCompanion['height'] . " )";
                $selectableCompanions[$currentCompanion['ad_id'] ] = $strNameToDisplay;
            }
        }
        return $selectableCompanions;
    }

    function getAllFieldsLabels(){
        $labels = array(
            'vast_video_type' => "Media type",
            'vast_video_duration' => "Video duration in seconds",
            'vast_net_connection_url' => "RTMP server URL",
            'vast_video_filename' => 'Video filename',
            'vast_video_filename_http' => 'Media URL', // not submitted in the form itself, but string is displayed to
            'vast_video_delivery' => 'Video delivery method',
            'is_mezzininefile' => 'Mezzanine Format',
            'mezzanine_en' => 'Designate your creative file as the mezzanine file',
            'video_side' => 'Video Side',
        );
        return $labels;
    }

    function getFieldLabel($fieldName){
        $labels = $this->getAllFieldsLabels();
        return $labels[$fieldName];
    }

    function addFormRequiredElement(&$form, $element, $fieldNameWhenRequired = null, $fieldValueWhenRequired = null){
        $element[2] = $this->getLabelWithRequiredStar($element[2]);
        call_user_func_array(array($form, 'addElement'), $element);
        $fieldName = $element[1];
        $this->setElementIsRequired($fieldName, $fieldNameWhenRequired, $fieldValueWhenRequired);
    }

    function getLabelWithRequiredStar($label){
        return $label . ' <font color="red">*</font>';
    }
    function setElementIsRequired($fieldName, $fieldNameWhenRequired, $fieldValueWhenRequired){
        $this->requiredElement[] = array( $fieldName , $fieldNameWhenRequired, $fieldValueWhenRequired);
    }

    function addVastParametersToForm(&$form, &$bannerRow, $isNewBanner){
        $form->addElement('hidden', 'banner_vast_element_id', "banner_vast_element_id");
        $form->addElement('hidden', 'vast_element_type', "singlerow");

        $this->addVastVideoUrlFields($form, $bannerRow, $isNewBanner);		
		
        $sampleUrls = array(
            'RTMP - FLV' => array(
            	"rtmp://cp81850.edgefcs.net/ondemand/",
                "openx-ad",
                'FLV',
                '8',
            ),

            'RTMP - MP4' => array(
        		"rtmp://cp81850.edgefcs.net/ondemand/",
        		"openx-ad.mp4",
                'MP4',
                '10',
            ),

            'HTTP - FLV' => array(
            	"http://videoads.openx.org.edgesuite.net/openxvideos/openx-ad.flv",
                'FLV',
            	'8',
            ),

            'HTTP - MP4' => array(
            	"http://videoads.openx.org.edgesuite.net/openxvideos/openx-ad.mp4",
                'MP4',
                '10'
            ),

            'HTTP - WEBM' => array(
            	"http://video.webmfiles.org/big-buck-bunny_trailer.webm",
                'WEBM',
                '32'
            ),

        );

        $sampleAdsString = 'You can try using any of the following sample ads<br/><br/>';
        foreach($sampleUrls as $what => $urls) {
            $sampleAdsString .= "<b>$what sample ads</b><ul style='margin-top:5px'>";
            if(count($urls) == 3) {
               $sampleAdsString .= '<li>'.$this->getFieldLabel('vast_video_filename_http') . ': '. $urls[0];
               $sampleAdsString .= '<li>'.$this->getFieldLabel('vast_video_type') . ': '. $urls[1];
               $sampleAdsString .= '<li>'.$this->getFieldLabel('vast_video_duration') . ': '. $urls[2];
            } else {
               $sampleAdsString .= '<li>'.$this->getFieldLabel('vast_net_connection_url') . ': '. $urls[0];
               $sampleAdsString .= '<li>'.$this->getFieldLabel('vast_video_filename') . ': '. $urls[1];
               $sampleAdsString .= '<li>'.$this->getFieldLabel('vast_video_type') . ': '. $urls[2];
               $sampleAdsString .= '<li>'.$this->getFieldLabel('vast_video_duration') . ': '. $urls[3];
            }
            $sampleAdsString .= "</ul>";
        }
        $sampleAdsString .= '';
        //~ $form->addElement('html', 'video_status_info_rtmp_mp4', VideoAdsHelper::getWarningMessage($sampleAdsString) );
	
        $advancedUser = true;
        if ( $advancedUser ){	  
        }
        else {
            // hide these for now - the player ignores them anyway - atm
            $form->addElement('hidden', 'vast_video_bitrate', "vast_video_bitrate");
            $form->addElement('hidden', 'vast_video_width', "vast_video_width");
            $form->addElement('hidden', 'vast_video_height', "vast_video_height");
        }
        if ( $isNewBanner ){
            $bannerRow['vast_video_bitrate'] = '400';
            $bannerRow['vast_video_width'] = '640';
            $bannerRow['vast_video_height'] = '480';
        }
    }

    function addThirdPartyImpressionTracking( &$form ){
        $form->addElement('header', 'thirdpartyimp_title', 'Third party impression tracking');
        $form->addElement('html', 'thirdpartyimp_help', '
        	When a video ad is displayed, OpenX will record the ad impression.
        	You can also specify a URL to a third party 1x1 transparent pixel.
        	The URL can contain any of the supported <a href="http://documentation.revive-adserver.com/display/DOCS/Magic+Macros" target="_blank">magic macros</a>.
        					');

        $form->addElement(  'text',
        					'vast_thirdparty_impression',
        					'Impression tracking beacon URL <br>(incl. http://)');
    }

    function addThirdPartyClickTracking( &$form ){
        $form->addElement('header', 'thirdpartyimp_title', 'Third party Click tracking');
        $form->addElement('html', 'thirdpartyclick_help', 'Contains a URI to a location or file that the video player should request when the user clicks within the video frame while the Linear ad is played;the server can also use requests to this URI for tracking the “clickthrough” metric');
        $form->addElement(  'text',
        					'vast_thirdparty_clicktracking',
        					'Third Party Click tracking URL <br>(incl. http://)');
	    $form->addElement(  'text',
        					'vast_thirdparty_clickcustom',
        					'Third Party Click tracking Custom URL <br>(incl. http://)');
    }

    function addoverlayadvanced( &$form ){
        $form->addElement('header', 'advanced_feature', 'Overlay Advanced Settings');
		$form->addElement('text','vast_overlay_expanded_width','Expanded Width');//DAC015
        $form->addElement('text','vast_overlay_expanded_height','Expanded Height');//DAC015
		$form->addElement('text','vast_overlay_expandedminduration','Min Suggested Duration');//DAC015
    }

    function addVastCompanionsToForm( &$form, $selectableCompanions){
        $form->addElement('header', 'companion_status', "Companion banner");
        $doCampaigns = OA_Dal::factoryDO('campaigns');
        $doCampaigns->campaignid = $GLOBALS['campaignid'];
        $doCampaigns->find();
        $doCampaigns->fetch();
        if(OX_Util_Utils::getCampaignType($doCampaigns->priority) == OX_CAMPAIGN_TYPE_CONTRACT_NORMAL){
            $form->addElement('html', 'companion_help_contract',
                            '<br/><b>Note:</b> Revive Adserver currently doesn\'t support the display of a companion banner for "Contract" campaigns.
                             <br/>If you wish to display a companion banner, please select a "Remnant" or "Override" campaign.');

            return;
        }
        $helpLinkPlayer = VideoAdsHelper::getHelpLinkVideoPlayerConfig();
        $form->addElement('html', 'companion_help', 'To associate a companion banner to this video ad, select a banner from the companion banner dropdown. This banner will appear for the duration of the video ad. <br/>
        					You will need to specify where this companion banner appears on the page while setting up your video ad in the video player plugin configuration. <a href="'.$helpLinkPlayer.'" target="_blank">Learn more</a>
        					');

        $form->addElement(	'select','vast_companion_banner_id','Companion banner', $selectableCompanions);
		$form->addElement(  'text',
        					'vast_thirdparty_companion_clicktracking',
        					'Third Party Click tracking URL <br>(incl. http://)');//DAC015
		$form->addElement(  'text',
        					'vast_thirdparty_companion_expandedwidth',
        					'Expanded Width');//DAC015
		$form->addElement(  'text',
        					'vast_thirdparty_companion_expandedheight',
        					'Expanded Height');//DAC015
    }

    function addVastHardcodedDimensionsToForm(&$form, &$bannerRow, $dimension){
        $bannerRow['width'] = $dimension;
        $bannerRow['height'] = $dimension;
        $form->addElement('hidden', 'width' );
        $form->addElement('hidden', 'height');
    }

    function addIntroductionInlineHelp(&$form){
			
			$getvasttype=getvasttype();
			$form->addElement('select','vast_type','Select vast version', $getvasttype,
            array('onChange' => 'phpAds_vastversion(this);'));
				
        $helpString = $this->getHelpAdTypeDescription();
        $crossdomainUrl = MAX_commonConstructDeliveryUrl('crossdomain.xml');
        // because flash apps look at http://domain/crossdomain.xml, we need to construct this URL and keep only the hostname
        $crossdomainUrl = parse_url($crossdomainUrl);
        $crossdomainUrl = $crossdomainUrl['scheme'] . '://' . $crossdomainUrl['host'] . '/crossdomain.xml';
        $helpString .= "<br/><br/>To setup your ".$this->getBannerShortName().", you will need to:
        <ul style='list-style-type:decimal;padding-left:20px;padding-top:5px'>
        <li>Enter the information about your Ad in the form below.</li>
        <li>Link this ".$this->getBannerShortName()." to the desired zone. The zone must be of the type \"".$this->getZoneToLinkShortName()."\". <a href='".VideoAdsHelper::getHelpLinkOpenXPlugin() ."' target='_blank'>Learn more</a></li>
        <li>Include the zone in the Ad Schedule of the video player plugin configuration in your webpage. <a href='". VideoAdsHelper::getHelpLinkVideoPlayerConfig() ."' target='_blank'>Learn more</a></li>
        <li>Make sure that the flash player is allowed to request ads on this adserver. The <a href='$crossdomainUrl' target='_blank'>crossdomain.xml on your adserver</a> should look similar to the <a href='".VideoAdsHelper::getLinkCrossdomainExample()."' target='_blank'>recommended crossdomain.xml</a></li>
    	</ul>";
        //$form->addElement('html', 'video_status_info1', '<span style="font-size:100%;">'.$helpString.'</span>' );
    }

    function addVastVideoUrlFields(&$form, &$bannerRow, $isNewBanner){ 

        $vastVideoDelivery = $form->getSubmitValue('vast_video_delivery');
        if(empty($vastVideoDelivery)
            && !empty($bannerRow['vast_video_delivery'])) {
            $vastVideoDelivery = $bannerRow['vast_video_delivery'];
        }
       
	else{
	    $urlFormatMode  = VAST_VIDEO_URL_VAST_FORMAT;
         
    }
          
		$get_third_internal_type=get_third_internal_type();
		$form->addElement('select','get_third_internal_type','Select type', $get_third_internal_type);		
          $videoUrlFormats[] = $form->createElement(
        								'radio', 'vast_video_delivery', '',
                                        'progressive (HTTP)',
                                        'progressive',
                                        array('id' => 'video-url-format-progressive', 'onClick' => 'phpAds_formHttpProgressiveVideoUrlMode();' ));
		   $form->addGroup($videoUrlFormats, 'VideoFormatAction', $this->getLabelWithRequiredStar($this->getFieldLabel('vast_video_delivery')), "<br/>");
        $imageName = _getContentTypeIconImageName($aBanner['contenttype']);
        $size = _getBannerSizeText($type, $aBanner['filename']);                                                         
		$getadtype=getadtype();
		
		$form->addElement('text', 'vast_net_connection_url',$this->getFieldLabel('vast_net_connection_url'),'vast_net_connection_url');        
		$form->addElement('text', 'vast_video_filename','Media URL','vast_video_filename');        
           $form->addElement('text', 'vast_video_bitrate', "Media bitrate");
        $vastVideoType = djaxgetVastVideoTypes();
       $vastAudioType = djaxgetVastAudioTypes();
          
		$form->addElement('select', 'vast_video_type','Media type',array_merge($vastVideoType,$vastAudioType)); 
		$form->addElement('text', 'vast_video_duration','Video duration in seconds','vast_video_duration'); 
        $form->addElement('text', 'vast_video_clickthrough_url', "Destination URL (incl. http://) <br />when user clicks on the video");
	/*DAC015*/
	$form->addElement('text', 'vast_wrapper_url', "Third Party Vast Tag");
	$getvastversion=getvastversion();
	$form->addElement('select', 'vast_version','Vast Version',$getvastversion,array('id' => 'vast_version', 'onChange' => 'phpAds_skip();' )); 
	/*Wrapper vast 4 additional features*/
	  $selecfallback=array(1=>'True',2=>'False');
          $form->addElement('select','vast_wrapper_fallbacknoads','FallBack No Ad(Wrapper)', $selecfallback);
	  $allowmultiple=array(2=>'False',1=>'True');
          $form->addElement('select','vast_wrapper_allowmultipleads','Allow Multiple Ads',$allowmultiple);
	   $followadditional=array(1=>'True',2=>'False');
          $form->addElement('select','vast_wrapper_followadditional','FallBack No Ad(Wrapper)', $followadditional);
		/*Wrapper vast 3 additional features*/
		$form->addElement('header', 'VAST 3.0 Speacial Features', "VAST 3.0 Speacial Features");

		$form->addElement('text', 'vast_video_skip_duration','Skippable Inline Offset[Note:Format should be HH:MM:SS or HH:MM:SS.mmm or a percentage  n%]','vast_overlay_action');

		$form->addElement('text', 'vast_video_skip_progress_duration','Skippable Inline Progress Offset[Note:Format should be(HH:MM:SS or HH:MM:SS.mmm) or percentage n%]','vast_overlay_action');
	    $translation = new OX_Translation();
		$urlRequiredMsg = $translation->translate($GLOBALS['strXRequiredField'], array($GLOBALS['strWebsiteURL']));
		
	
		$azone = json_encode($azones);
}
} 

?>
<script src="../../www/admin/plugins/rmvideoReport/js/jquery331.min.js"></script>
<script>
$(function() {
phpAds_adtype($('#ad_type'),0);
});

function phpAds_mezzanine_enabled(){
	$("input[name=mezzanine_en]").attr("checked", false);
			
	// Show Designate your creative file as the mezzanine file 
	$("label[for=mezzanine_external]").parent().parent().prev().show();
	$("label[for=mezzanine_external]").parent().parent().show();
	
	// Show Interaction
	$("label[for=interactive_mediafile]").parent().parent().prev().show();
	$("label[for=interactive_mediafile]").parent().parent().show();			
	
	// Hide RTMP server URL
	$("label[for=vast_net_connection_url]").parent().parent().prev().hide();
	$("label[for=vast_net_connection_url]").parent().parent().hide(); 						
									
	// Hide Video duration in seconds 
	$("label[for=vast_video_duration]").parent().parent().prev().hide();
	$("label[for=vast_video_duration]").parent().parent().hide();				
									
	// Hide Destination URL (incl. http://) when user clicks on the video
	$("label[for=vast_video_clickthrough_url]").parent().parent().prev().hide();
	$("label[for=vast_video_clickthrough_url]").parent().parent().hide(); 
				 			
} 
function phpAds_adtype(type,changer=1){
	
	alert(runs);
	
	if(type.value == 1){							
		// Hide Interaction
		$("label[for=interactive_mediafile]").parent().parent().prev().hide();
		$("label[for=interactive_mediafile]").parent().parent().hide();
		
		// Hide Video duration in seconds 
		$("label[for=vast_video_duration]").parent().parent().prev().hide();
		$("label[for=vast_video_duration]").parent().parent().hide();
				
		// Hide options vast_video_type
		$("#vast_video_type option[value='video/mp4']").hide();						
		$("#vast_video_type option[value='video/x-flv']").hide();						
		$("#vast_video_type option[value='video/webm']").hide();						
		$("#vast_video_type option[value='application/x-mpegURL']").hide();	
		$("#vast_video_type_1 option[value='video/mp4']").hide();						
		$("#vast_video_type_1 option[value='video/x-flv']").hide();						
		$("#vast_video_type_1 option[value='video/webm']").hide();						
		$("#vast_video_type_1 option[value='application/x-mpegURL']").hide();	
		$("#vast_video_type_2 option[value='video/mp4']").hide();						
		$("#vast_video_type_2 option[value='video/x-flv']").hide();						
		$("#vast_video_type_2 option[value='video/webm']").hide();						
		$("#vast_video_type_2 option[value='application/x-mpegURL']").hide();	

		// Show options vast_video_type
		$("#vast_video_type option[value='audio/mpeg']").show();						
		$("#vast_video_type option[value='audio/aac']").show();
		$("#vast_video_type_1 option[value='audio/mpeg']").show();						
		$("#vast_video_type_1 option[value='audio/aac']").show();
		$("#vast_video_type_2 option[value='audio/mpeg']").show();						
		$("#vast_video_type_2 option[value='audio/aac']").show();
		
		if(changer)
		{
		// Select option vast_video_type				
		$("#vast_video_type option[value='audio/mpeg']").attr("selected", "selected");					
		$("#vast_video_type_1 option[value='audio/mpeg']").attr("selected", "selected");					
		$("#vast_video_type_2 option[value='audio/mpeg']").attr("selected", "selected");	
		}
						
	}else{		
		// Show Interaction
		$("label[for=interactive_mediafile]").parent().parent().prev().show();
		$("label[for=interactive_mediafile]").parent().parent().show();
		
		// Show Video duration in seconds 
		$("label[for=vast_video_duration]").parent().parent().prev().show();
		$("label[for=vast_video_duration]").parent().parent().show();
				
		// Hide options vast_video_type
		$("#vast_video_type option[value='audio/mpeg']").hide();						
		$("#vast_video_type option[value='audio/aac']").hide();	
		$("#vast_video_type_1 option[value='audio/mpeg']").hide();						
		$("#vast_video_type_1 option[value='audio/aac']").hide();	
		$("#vast_video_type_2 option[value='audio/mpeg']").hide();						
		$("#vast_video_type_2 option[value='audio/aac']").hide();	
			
		// Show options vast_video_type
		$("#vast_video_type option[value='video/mp4']").show();						
		$("#vast_video_type option[value='video/x-flv']").show();						
		$("#vast_video_type option[value='video/webm']").show();						
		$("#vast_video_type option[value='application/x-mpegURL']").show();
		$("#vast_video_type_1 option[value='video/mp4']").show();						
		$("#vast_video_type_1 option[value='video/x-flv']").show();						
		$("#vast_video_type_1 option[value='video/webm']").show();						
		$("#vast_video_type_1 option[value='application/x-mpegURL']").show();
		$("#vast_video_type_2 option[value='video/mp4']").show();						
		$("#vast_video_type_2 option[value='video/x-flv']").show();						
		$("#vast_video_type_2 option[value='video/webm']").show();						
		$("#vast_video_type_2 option[value='application/x-mpegURL']").show();
			
			if(changer)
		{
		// Select option vast_video_type	
		$("#vast_video_type option[value='video/mp4']").attr("selected", "selected");						
		$("#vast_video_type_1 option[value='video/mp4']").attr("selected", "selected");						
		$("#vast_video_type_2 option[value='video/mp4']").attr("selected", "selected");	
		 }
		
	}	
	
	
	
}










function phpAds_formRtmpStreamingVideoUrlMode(){       
	$("#vast_video_delivery").attr('value', 'streaming');
	
	// Hide Add New Video URL, Video type,Video bitrate
	$("#add_url_bitrate_type").hide();        
	
	// Show RTMP server URL
	$("label[for=vast_net_connection_url]").parent().parent().prev().show();
	$("label[for=vast_net_connection_url]").parent().parent().show();
	
	// Show Video URL/Filename
	$("label[for=vast_video_filename]").parent().parent().prev().show();
	$("label[for=vast_video_filename]").parent().parent().show(); 						
									
	// Hide Video URL/Filename 1
	$("label[for=vast_video_outgoing_filename1]").parent().parent().prev().hide();
	$("label[for=vast_video_outgoing_filename1]").parent().parent().hide(); 						
									
	// Hide Video URL/Filename 2
	$("label[for=vast_video_outgoing_filename2]").parent().parent().prev().hide();
	$("label[for=vast_video_outgoing_filename2]").parent().parent().hide(); 
											
	// Show Video type
	$("label[for=vast_video_type]").parent().parent().prev().show();
	$("label[for=vast_video_type]").parent().parent().show(); 						
									
	// Hide Video type 1
	$("label[for=vast_video_type_1]").parent().parent().prev().hide();
	$("label[for=vast_video_type_1]").parent().parent().hide(); 						
									
	// Hide Video type 2
	$("label[for=vast_video_type_2]").parent().parent().prev().hide();
	$("label[for=vast_video_type_2]").parent().parent().hide(); 
											
	// Show Video duration in seconds 
	$("label[for=vast_video_duration]").parent().parent().prev().show();
	$("label[for=vast_video_duration]").parent().parent().show();				
									
	// Show Destination URL (incl. http://) when user clicks on the video
	$("label[for=vast_video_clickthrough_url]").parent().parent().prev().show();
	$("label[for=vast_video_clickthrough_url]").parent().parent().show(); 
	
	// Hide Mezzanine Format 
	$("label[for=mezzanine_disabled]").parent().parent().prev().hide();
	$("label[for=mezzanine_disabled]").parent().parent().parent().hide();		
	
	// Hide Designate your creative file as the mezzanine file 
	$("label[for=mezzanine_external]").parent().parent().prev().hide();
	$("label[for=mezzanine_external]").parent().parent().hide();
	
	// Hide Ad Manager-hosted File					
	$("input[name=internal_file]").parent().parent().prev().hide();	
	$("input[name=internal_file]").parent().parent().hide();
	
	// Hide Externally-hosted URL 
	$("#url_file").parent().parent().prev().hide();	
	$("#url_file").parent().parent().hide();		
	
	// Hide Interaction
	$("label[for=interactive_mediafile]").parent().parent().prev().hide();
	$("label[for=interactive_mediafile]").parent().parent().hide();
				
	// Hide Video bitrate
	$("label[for=vast_video_bitrate]").parent().parent().prev().hide();
	$("label[for=vast_video_bitrate]").parent().parent().hide(); 											        
				
	// Hide Video bitrate 1
	$("label[for=vast_video_bitrate_1]").parent().parent().prev().hide();
	$("label[for=vast_video_bitrate_1]").parent().parent().hide(); 			
						
	// Hide Video bitrate 2
	$("label[for=vast_video_bitrate_2]").parent().parent().prev().hide();
	$("label[for=vast_video_bitrate_2]").parent().parent().hide(); 

	$("label[for=vast_video_filename]").html('Video filename<font color="red">*</font>');
}
function phpAds_formHttpProgressiveVideoUrlMode(){
	var vast_version = $("#vast_type").val();
	$("#vast_net_connection_url").attr('value', '');
	$("#vast_video_delivery").attr('value', 'progressive');
		   
	// Hide RTMP server URL
	$("label[for=vast_net_connection_url]").parent().parent().prev().hide();
	$("label[for=vast_net_connection_url]").parent().parent().hide(); 
	
	// Show Mezzanine Format
	$("label[for=mezzanine_disabled]").parent().parent().prev().show();
	$("label[for=mezzanine_disabled]").parent().parent().parent().show();
	
	// Check Disable as Default
	$("#mezzanine_disabled").attr("checked", true);
	
	// Show Video URL/Filename
	$("label[for=vast_video_filename]").parent().parent().prev().show();
	$("label[for=vast_video_filename]").parent().parent().show(); 						
									
	// Show Video URL/Filename 1
	$("label[for=vast_video_outgoing_filename1]").parent().parent().prev().show();
	$("label[for=vast_video_outgoing_filename1]").parent().parent().show(); 						
									
	// Show Video URL/Filename 2
	$("label[for=vast_video_outgoing_filename2]").parent().parent().prev().show();
	$("label[for=vast_video_outgoing_filename2]").parent().parent().show(); 
											
	// Show Video type
	$("label[for=vast_video_type]").parent().parent().prev().show();
	$("label[for=vast_video_type]").parent().parent().show(); 						
									
	// Show Video type 1
	$("label[for=vast_video_type_1]").parent().parent().prev().show();
	$("label[for=vast_video_type_1]").parent().parent().show(); 						
									
	// Show Video type 2
	$("label[for=vast_video_type_2]").parent().parent().prev().show();
	$("label[for=vast_video_type_2]").parent().parent().show(); 
											
	// Show Video duration in seconds 
	$("label[for=vast_video_duration]").parent().parent().prev().show();
	$("label[for=vast_video_duration]").parent().parent().show();				
									
	// Show Destination URL (incl. http://) when user clicks on the video
	$("label[for=vast_video_clickthrough_url]").parent().parent().prev().show();
	$("label[for=vast_video_clickthrough_url]").parent().parent().show(); 
				
	// Show Video bitrate
	$("label[for=vast_video_bitrate]").parent().parent().prev().show();
	$("label[for=vast_video_bitrate]").parent().parent().show(); 
				
	// Show Video bitrate 1
	$("label[for=vast_video_bitrate_1]").parent().parent().prev().show();
	$("label[for=vast_video_bitrate_1]").parent().parent().show(); 			
						
	// Show Video bitrate 2
	$("label[for=vast_video_bitrate_2]").parent().parent().prev().show();
	$("label[for=vast_video_bitrate_2]").parent().parent().show(); 
			
	// Show Add New Video URL, Video type,Video bitrate
	$("#add_url_bitrate_type").show(); 		
	
	if((vast_version == 1) || (vast_version == 2) ){
		// Hide Mezzanine Format 
		$("label[for=mezzanine_disabled]").parent().parent().prev().hide();
		$("label[for=mezzanine_disabled]").parent().parent().parent().hide();
		
		// Hide Interaction
		$("label[for=interactive_mediafile]").parent().parent().prev().hide();
		$("label[for=interactive_mediafile]").parent().parent().hide();	
		
		// Hide Add New Video URL, Video type,Video bitrate
		$("#add_url_bitrate_type").hide(); 										
	}else{						
		// Show Interaction
		$("label[for=interactive_mediafile]").parent().parent().prev().show();
		$("label[for=interactive_mediafile]").parent().parent().show();
	}
	if(vast_version == 4){						
		// Show Ad type 
		$("#ad_type").parent().parent().prev().show();
		$("#ad_type").parent().parent().show();		
	}						       
} 
function phpAds_mezzanine_disabled(){
	// Hide Do you wish to keep your existing video? Or do you want to upload another?				
	$("label[for=radio_internal_file_keep]").parent().parent().prev().hide();	
	$("label[for=radio_internal_file_keep]").parent().parent().hide();	
		
	// Hide Designate your creative file as the mezzanine file 
	$("label[for=mezzanine_external]").parent().parent().prev().hide();
	$("label[for=mezzanine_external]").parent().parent().hide();
	
	// Hide Externally-hosted URL 
	$("#url_file").parent().parent().prev().hide();	
	$("#url_file").parent().parent().hide();
	
	// Hide Ad Manager-hosted File					
	$("input[name=internal_file]").parent().parent().prev().hide();	
	$("input[name=internal_file]").parent().parent().hide();			
	
	// Hide RTMP server URL
	$("label[for=vast_net_connection_url]").parent().parent().prev().hide();
	$("label[for=vast_net_connection_url]").parent().parent().hide(); 
	
	// Show Video URL/Filename
	$("label[for=vast_video_filename]").parent().parent().prev().show();
	$("label[for=vast_video_filename]").parent().parent().show(); 						
	
	// Show Video URL/Filename 1
	$("label[for=vast_video_outgoing_filename1]").parent().parent().prev().show();
	$("label[for=vast_video_outgoing_filename1]").parent().parent().show(); 						
	
	// Show Video URL/Filename 2
	$("label[for=vast_video_outgoing_filename2]").parent().parent().prev().show();
	$("label[for=vast_video_outgoing_filename2]").parent().parent().show(); 
											
	// Show Video type
	$("label[for=vast_video_type]").parent().parent().prev().show();
	$("label[for=vast_video_type]").parent().parent().show(); 						
									
	// Show Video type 1
	$("label[for=vast_video_type_1]").parent().parent().prev().show();
	$("label[for=vast_video_type_1]").parent().parent().show(); 						
									
	// Show Video type 2
	$("label[for=vast_video_type_2]").parent().parent().prev().show();
	$("label[for=vast_video_type_2]").parent().parent().show(); 
											
	// Show Video duration in seconds 
	$("label[for=vast_video_duration]").parent().parent().prev().show();
	$("label[for=vast_video_duration]").parent().parent().show();				
									
	// Show Destination URL (incl. http://) when user clicks on the video
	$("label[for=vast_video_clickthrough_url]").parent().parent().prev().show();
	$("label[for=vast_video_clickthrough_url]").parent().parent().show();
				
	// Show Video bitrate
	$("label[for=vast_video_bitrate]").parent().parent().prev().show();
	$("label[for=vast_video_bitrate]").parent().parent().show();		
				
	// Show Video bitrate 1
	$("label[for=vast_video_bitrate_1]").parent().parent().prev().show();
	$("label[for=vast_video_bitrate_1]").parent().parent().show(); 			
				
	// Show Video bitrate 2
	$("label[for=vast_video_bitrate_2]").parent().parent().prev().show();
	$("label[for=vast_video_bitrate_2]").parent().parent().show(); 				 			
} 
function phpAds_server_side(){
	$("#get_third_internal_type option[value='audio/aac']").remove();
	
	// Hide Third Party Vast Tag 
	$("label[for=vast_wrapper_url]").parent().parent().prev().hide();
	$("label[for=vast_wrapper_url]").parent().parent().hide(); 
	
	// Hide Vast Version 
	$("label[for=vast_version]").parent().parent().prev().hide();
	$("label[for=vast_version]").parent().parent().hide();
	
	// Hide FallBack No Ad(Wrapper)
	$("label[for=vast_wrapper_fallbacknoads]").parent().parent().prev().hide();
	$("label[for=vast_wrapper_fallbacknoads]").parent().parent().hide();
	
	// Hide Allow Multiple Ads
	$("label[for=vast_wrapper_allowmultipleads]").parent().parent().prev().hide();
	$("label[for=vast_wrapper_allowmultipleads]").parent().parent().hide(); 
	
	// Hide FallBack No Ad(Wrapper) Additional
	$("label[for=vast_wrapper_followadditional]").parent().parent().prev().hide();
	$("label[for=vast_wrapper_followadditional]").parent().parent().hide();	
	
	// Show Mezzanine Format 
	$("label[for=mezzanine_disabled]").parent().parent().prev().show();
	$("label[for=mezzanine_disabled]").parent().parent().parent().show();
	
	// Show Video delivery method 
	$("label[for=video-url-format-progressive]").parent().parent().prev().show();
	$("label[for=video-url-format-progressive]").parent().parent().show();
	
	// Check progressive as default
	$("#video-url-format-progressive").attr("checked", true);
	
	// Show Video URL/Filename
	$("label[for=vast_video_filename]").parent().parent().prev().show();
	$("label[for=vast_video_filename]").parent().parent().show(); 
	
	// Show Video type
	$("label[for=vast_video_type]").parent().parent().prev().show();
	$("label[for=vast_video_type]").parent().parent().show(); 						
									
	// Show Video duration in seconds 
	$("label[for=vast_video_duration]").parent().parent().prev().show();
	$("label[for=vast_video_duration]").parent().parent().show();				
									
	// Show Destination URL (incl. http://) when user clicks on the video
	$("label[for=vast_video_clickthrough_url]").parent().parent().prev().show();
	$("label[for=vast_video_clickthrough_url]").parent().parent().show(); 
				
	// Show Video bitrate
	$("label[for=vast_video_bitrate]").parent().parent().prev().show();
	$("label[for=vast_video_bitrate]").parent().parent().show(); 
							
	// Show Video delivery method 
	$("#video-url-format-progressive").parent().prev().show();
	$("#video-url-format-progressive").parent().show();								
} 
function phpAds_client_side(){
	$("#get_third_internal_type").append(new Option("Third Party Wrapper Ads", "2"));
	
	// Show Video delivery method 
	$("label[for=video-url-format-progressive]").parent().parent().prev().show();
	$("label[for=video-url-format-progressive]").parent().parent().show();
	
	// Check progressive as default
	$("#video-url-format-progressive").attr("checked", true);
	
	// Show Video URL/Filename
	$("label[for=vast_video_filename]").parent().parent().prev().show();
	$("label[for=vast_video_filename]").parent().parent().show(); 
	
	// Show Video type
	$("label[for=vast_video_type]").parent().parent().prev().show();
	$("label[for=vast_video_type]").parent().parent().show(); 						
									
	// Show Video duration in seconds 
	$("label[for=vast_video_duration]").parent().parent().prev().show();
	$("label[for=vast_video_duration]").parent().parent().show();				
									
	// Show Destination URL (incl. http://) when user clicks on the video
	$("label[for=vast_video_clickthrough_url]").parent().parent().prev().show();
	$("label[for=vast_video_clickthrough_url]").parent().parent().show(); 
				
	// Show Video bitrate
	$("label[for=vast_video_bitrate]").parent().parent().prev().show();
	$("label[for=vast_video_bitrate]").parent().parent().show(); 								
}	
<?php 
if($_GET['bannerid'] == ""){ ?>	
	$(document).ready(function(){
		/* 
			* Hide these on page load "INLINE VIDEO"
		*/
		// Vast Version value
		var vast_version = $("#vast_type").val();
		
		// Hide VAST 4.1 Closed Caption
		$("#vast_4_1_type_file").parent().parent().parent().hide();
		
		// Hide Interaction
		$("label[for=interactive_mediafile]").parent().parent().prev().hide();
		$("label[for=interactive_mediafile]").parent().parent().hide();
		
		// Hide Video Side 
		$("label[for=server_side]").parent().parent().prev().hide();
		$("label[for=server_side]").parent().parent().hide();
		
		// Hide Ad type 
		$("#ad_type").parent().parent().prev().hide();
		$("#ad_type").parent().parent().hide();
		
		// Hide Video delivery method(Third party Vast Tag)
		$("label[for=video-url-format-vast]").hide();
		$("#video-url-format-vast").hide();
		
		// Hide Mezzanine Format 
		$("label[for=mezzanine_disabled]").parent().parent().prev().hide();
		$("label[for=mezzanine_disabled]").parent().parent().parent().hide();	
		
		// Hide Designate your creative file as the mezzanine file 
		$("label[for=mezzanine_external]").parent().parent().prev().hide();
		$("label[for=mezzanine_external]").parent().parent().hide();
		
		// Hide Ad Manager-hosted File
		$("input[name=internal_file]").parent().parent().prev().hide();	
		$("input[name=internal_file]").parent().parent().hide();
		
		// Hide Externally-hosted URL
		$("#url_file").parent().parent().prev().hide();	
		$("#url_file").parent().parent().hide();
		
		// Hide RTMP server URL 
		$("label[for=vast_net_connection_url]").parent().parent().prev().hide();
        $("label[for=vast_net_connection_url]").parent().parent().hide();
        
		// Hide Third Party Vast Tag 
		$("label[for=vast_wrapper_url]").parent().parent().prev().hide();
		$("label[for=vast_wrapper_url]").parent().parent().hide(); 
        
		// Hide Vast Version 
		$("label[for=vast_version]").parent().parent().prev().hide();
		$("label[for=vast_version]").parent().parent().hide();
        
		// Hide FallBack No Ad(Wrapper)
		$("label[for=vast_wrapper_fallbacknoads]").parent().parent().prev().hide();
		$("label[for=vast_wrapper_fallbacknoads]").parent().parent().hide();
		
		// Hide Allow Multiple Ads
		$("label[for=vast_wrapper_allowmultipleads]").parent().parent().prev().hide();
		$("label[for=vast_wrapper_allowmultipleads]").parent().parent().hide(); 
		
		// Hide FallBack No Ad(Wrapper) Additional
		$("label[for=vast_wrapper_followadditional]").parent().parent().prev().hide();
		$("label[for=vast_wrapper_followadditional]").parent().parent().hide();
		
		// Hide VAST 3.0 Speacial Features
		$("#vast_video_skip_progress_duration").parent().parent().parent().hide();
		
		// Hide Icon information
		$("#icon_track_url").parent().parent().parent().hide();
		
		// Hide VAST 4.0 Special Features
		$("#vast4_category_name").parent().parent().parent().parent().hide();
		
		// Hide Interaction
		$("label[for=interactive_mediafile]").parent().parent().prev().hide();
		$("label[for=interactive_mediafile]").parent().parent().hide();
		
		// Check Audio Type(Audio/MPEG) as Default
		$("#audio-type-mpeg").attr("checked", true);
		
		// Check Progressive as Default
		$("#video-url-format-progressive").attr("checked", true);
		
		// Check Client side  as Default
		$("#client_side").attr("checked", true);
		
		// Check Disable as Default
		$("#mezzanine_disabled").attr("checked", true);	
			
		//Change label name for video Filename/URL
		$("label[for=vast_video_filename]").html('Media URL<font color="red">*</font>');
						
		// When Third Party Wrapper Ads Selected	
		$("#get_third_internal_type").change(function (){
			var vast_version = $("#vast_type").val();
			if(this.value == 2){		
				// Hide Ad type 
				$("#ad_type").parent().parent().prev().hide();
				$("#ad_type").parent().parent().hide();	
																	
				// Hide Video bitrate
				$("label[for=vast_video_bitrate]").parent().parent().prev().hide();
				$("label[for=vast_video_bitrate]").parent().parent().hide();
					
				// Hide Video bitrate 1
				$("label[for=vast_video_bitrate_1]").parent().parent().prev().hide();
				$("label[for=vast_video_bitrate_1]").parent().parent().hide(); 			
							
				// Hide Video bitrate 2
				$("label[for=vast_video_bitrate_2]").parent().parent().prev().hide();
				$("label[for=vast_video_bitrate_2]").parent().parent().hide(); 
								
				// Hide Video Native width
				$("label[for=vast_video_width]").parent().parent().prev().hide();
				$("label[for=vast_video_width]").parent().parent().hide();
				
				// Hide Video Native height
				$("label[for=vast_video_height]").parent().parent().prev().hide();
				$("label[for=vast_video_height]").parent().parent().hide();
				
				// Hide Video Minimum bitrate(Vast4)
				$("label[for=vast4_min_bitrate]").parent().parent().prev().hide();
				$("label[for=vast4_min_bitrate]").parent().parent().hide();
				
				// Hide Video Maximum bitrate(Vast4)
				$("label[for=vast4_max_bitrate]").parent().parent().prev().hide();
				$("label[for=vast4_max_bitrate]").parent().parent().hide();
				
				// Hide Mezzanine Format 
				$("label[for=mezzanine_disabled]").parent().parent().prev().hide();
				$("label[for=mezzanine_disabled]").parent().parent().parent().hide();
								
				// Hide Video delivery method 
				$("#video-url-format-progressive").parent().prev().hide();
				$("#video-url-format-progressive").parent().hide();
				
				// Hide RTMP server URL
				$("label[for=vast_net_connection_url]").parent().parent().prev().hide();
				$("label[for=vast_net_connection_url]").parent().parent().hide(); 
										
				// Hide Video URL/Filename
				$("label[for=vast_video_filename]").parent().parent().prev().hide();
				$("label[for=vast_video_filename]").parent().parent().hide(); 						
										
				// Hide Video URL/Filename 1
				$("label[for=vast_video_outgoing_filename1]").parent().parent().prev().hide();
				$("label[for=vast_video_outgoing_filename1]").parent().parent().hide(); 						
										
				// Hide Video URL/Filename 2
				$("label[for=vast_video_outgoing_filename2]").parent().parent().prev().hide();
				$("label[for=vast_video_outgoing_filename2]").parent().parent().hide(); 						
										
				// Hide Video type
				$("label[for=vast_video_type]").parent().parent().prev().hide();
				$("label[for=vast_video_type]").parent().parent().hide(); 						
										
				// Hide Video type 1
				$("label[for=vast_video_type_1]").parent().parent().prev().hide();
				$("label[for=vast_video_type_1]").parent().parent().hide(); 						
										
				// Hide Video type 2
				$("label[for=vast_video_type_2]").parent().parent().prev().hide();
				$("label[for=vast_video_type_2]").parent().parent().hide(); 						
										
				// Hide Video duration in seconds 
				$("label[for=vast_video_duration]").parent().parent().prev().hide();
				$("label[for=vast_video_duration]").parent().parent().hide();
				
				// Hide VAST 4.1 Closed Caption
				$("#vast_4_1_type_file").parent().parent().parent().hide();
				
				// Hide VAST 4.0 Special Features
				$("#vast4_category_name").parent().parent().parent().parent().hide();				
									
				// Show Destination URL (incl. http://) when user clicks on the video
				$("label[for=vast_video_clickthrough_url]").parent().parent().prev().show();
				$("label[for=vast_video_clickthrough_url]").parent().parent().show(); 
										
				// Show Third Party Vast Tag 
				$("label[for=vast_wrapper_url]").parent().parent().prev().show();
				$("label[for=vast_wrapper_url]").parent().parent().show(); 
				
				// Show Vast Version 
				//~ $("label[for=vast_version]").parent().parent().prev().show();
				//~ $("label[for=vast_version]").parent().parent().show();						
			}
			if(this.value == 1){
				// Check Progressive as Default
				$("#video-url-format-progressive").attr("checked", true);
				
				// Hide Third Party Vast Tag 
				$("label[for=vast_wrapper_url]").parent().parent().prev().hide();
				$("label[for=vast_wrapper_url]").parent().parent().hide(); 
				
				// Hide Vast Version 
				$("label[for=vast_version]").parent().parent().prev().hide();
				$("label[for=vast_version]").parent().parent().hide();
				
				// Hide FallBack No Ad(Wrapper)
				$("label[for=vast_wrapper_fallbacknoads]").parent().parent().prev().hide();
				$("label[for=vast_wrapper_fallbacknoads]").parent().parent().hide();
				
				// Hide Allow Multiple Ads
				$("label[for=vast_wrapper_allowmultipleads]").parent().parent().prev().hide();
				$("label[for=vast_wrapper_allowmultipleads]").parent().parent().hide(); 
				
				// Hide FallBack No Ad(Wrapper) Additional
				$("label[for=vast_wrapper_followadditional]").parent().parent().prev().hide();
				$("label[for=vast_wrapper_followadditional]").parent().parent().hide();	
				
				// Hide RTMP server URL
				$("label[for=vast_net_connection_url]").parent().parent().prev().hide();
				$("label[for=vast_net_connection_url]").parent().parent().hide(); 
						
				// Show Video bitrate
				$("label[for=vast_video_bitrate]").parent().parent().prev().show();
				$("label[for=vast_video_bitrate]").parent().parent().show();
					
				// Show Video bitrate 1
				$("label[for=vast_video_bitrate_1]").parent().parent().prev().show();
				$("label[for=vast_video_bitrate_1]").parent().parent().show(); 			
							
				// Show Video bitrate 2
				$("label[for=vast_video_bitrate_2]").parent().parent().prev().show();
				$("label[for=vast_video_bitrate_2]").parent().parent().show(); 
								
				// Show Video Native width
				$("label[for=vast_video_width]").parent().parent().prev().show();
				$("label[for=vast_video_width]").parent().parent().show();
				
				// Show Video Native height
				$("label[for=vast_video_height]").parent().parent().prev().show();
				$("label[for=vast_video_height]").parent().parent().show();
				
				// Show Video Minimum bitrate(Vast4)
				$("label[for=vast4_min_bitrate]").parent().parent().prev().show();
				$("label[for=vast4_min_bitrate]").parent().parent().show();
				
				// Show Video Maximum bitrate(Vast4)
				$("label[for=vast4_max_bitrate]").parent().parent().prev().show();
				$("label[for=vast4_max_bitrate]").parent().parent().show();
								
				// Show Mezzanine Format 
				$("label[for=mezzanine_disabled]").parent().parent().prev().show();
				$("label[for=mezzanine_disabled]").parent().parent().parent().show();
								
				// Show Video delivery method 
				$("#video-url-format-progressive").parent().prev().show();
				$("#video-url-format-progressive").parent().show();
							
				// Show Video URL/Filename
				$("label[for=vast_video_filename]").parent().parent().prev().show();
				$("label[for=vast_video_filename]").parent().parent().show(); 						
										
				// Show Video URL/Filename 1
				$("label[for=vast_video_outgoing_filename1]").parent().parent().prev().show();
				$("label[for=vast_video_outgoing_filename1]").parent().parent().show(); 						
										
				// Show Video URL/Filename 2
				$("label[for=vast_video_outgoing_filename2]").parent().parent().prev().show();
				$("label[for=vast_video_outgoing_filename2]").parent().parent().show(); 
														
				// Show Video type
				$("label[for=vast_video_type]").parent().parent().prev().show();
				$("label[for=vast_video_type]").parent().parent().show(); 						
										
				// Show Video type 1
				$("label[for=vast_video_type_1]").parent().parent().prev().show();
				$("label[for=vast_video_type_1]").parent().parent().show(); 						
										
				// Show Video type 2
				$("label[for=vast_video_type_2]").parent().parent().prev().show();
				$("label[for=vast_video_type_2]").parent().parent().show();
				 										
				// Show Video duration in seconds 
				$("label[for=vast_video_duration]").parent().parent().prev().show();
				$("label[for=vast_video_duration]").parent().parent().show();
				if(vast_version == 4){							
					// Show Ad type 
					$("#ad_type").parent().parent().prev().show();
					$("#ad_type").parent().parent().show();
					
					// Show VAST 4.1 Closed Caption
					$("#vast_4_1_type_file").parent().parent().parent().show();
					
					// Show VAST 4.0 Special Features
					$("#vast4_category_name").parent().parent().parent().parent().show();					
				}	
				if(vast_version == 3){	
					// Show VAST 4.0 Special Features
					$("#vast4_category_name").parent().parent().parent().parent().show();						
				}	
			}
			if((vast_version == 1) || (vast_version == 2) ){
				// Hide Mezzanine Format 
				$("label[for=mezzanine_disabled]").parent().parent().prev().hide();
				$("label[for=mezzanine_disabled]").parent().parent().parent().hide();
				
				// Hide Interaction
				$("label[for=interactive_mediafile]").parent().parent().prev().hide();
				$("label[for=interactive_mediafile]").parent().parent().hide();	
											
				// Hide Video URL/Filename 1
				$("label[for=vast_video_outgoing_filename1]").parent().parent().prev().hide();
				$("label[for=vast_video_outgoing_filename1]").parent().parent().hide(); 						
											
				// Hide Video URL/Filename 2
				$("label[for=vast_video_outgoing_filename2]").parent().parent().prev().hide();
				$("label[for=vast_video_outgoing_filename2]").parent().parent().hide(); 											
											
				// Hide Video type 1
				$("label[for=vast_video_type_1]").parent().parent().prev().hide();
				$("label[for=vast_video_type_1]").parent().parent().hide(); 						
										
				// Hide Video type 2
				$("label[for=vast_video_type_2]").parent().parent().prev().hide();
				$("label[for=vast_video_type_2]").parent().parent().hide(); 
									
				// Hide Video bitrate 1
				$("label[for=vast_video_bitrate_1]").parent().parent().prev().hide();
				$("label[for=vast_video_bitrate_1]").parent().parent().hide();
						
				// Hide Video bitrate 2
				$("label[for=vast_video_bitrate_2]").parent().parent().prev().hide();
				$("label[for=vast_video_bitrate_2]").parent().parent().hide();							
			}	
			if((vast_version == 3) || (vast_version == 4) ){
				if(this.value == 1){					
					// Check Disable as Default
					$("#mezzanine_disabled").attr("checked", true);	
										
					// Show Mezzanine Format 
					$("label[for=mezzanine_disabled]").parent().parent().prev().show();
					$("label[for=mezzanine_disabled]").parent().parent().parent().show();
												
					// Show Interaction
					$("label[for=interactive_mediafile]").parent().parent().prev().show();
					$("label[for=interactive_mediafile]").parent().parent().show();						
				}
				else if(this.value == 2){
					// Hide Mezzanine Format 
					$("label[for=mezzanine_disabled]").parent().parent().prev().hide();
					$("label[for=mezzanine_disabled]").parent().parent().parent().hide();
												
					// Hide Interaction
					$("label[for=interactive_mediafile]").parent().parent().prev().hide();
					$("label[for=interactive_mediafile]").parent().parent().hide();
					
					// Hide Designate your creative file as the mezzanine file 
					$("label[for=mezzanine_external]").parent().parent().prev().hide();
					$("label[for=mezzanine_external]").parent().parent().hide();
					
					// Hide Externally-hosted URL 
					$("#url_file").parent().parent().prev().hide();	
					$("#url_file").parent().parent().hide();
				
					// Hide Ad Manager-hosted File					
					$("input[name=internal_file]").parent().parent().prev().hide();	
					$("input[name=internal_file]").parent().parent().hide();
				
					// Show FallBack No Ad(Wrapper)
					$("label[for=vast_wrapper_fallbacknoads]").parent().parent().prev().show();
					$("label[for=vast_wrapper_fallbacknoads]").parent().parent().show();
					
					// Show Allow Multiple Ads
					$("label[for=vast_wrapper_allowmultipleads]").parent().parent().prev().show();
					$("label[for=vast_wrapper_allowmultipleads]").parent().parent().show(); 
					
					// Show FallBack No Ad(Wrapper) Additional
					$("label[for=vast_wrapper_followadditional]").parent().parent().prev().show();
					$("label[for=vast_wrapper_followadditional]").parent().parent().show();									
				}
				else{																				
					// Hide Interaction
					$("label[for=interactive_mediafile]").parent().parent().prev().hide();
					$("label[for=interactive_mediafile]").parent().parent().hide();					
				}				
			}			
		});
		
		// Validation check for internal file
		$("input[name=internal_file]").change(function () {
			var val = $(this).val().toLowerCase(),
				regex = new RegExp("(.*?)\.(webm|mp4|flv|wmv|m4v|3gp|avi|ogg|mov|mpg|mkv|asf|vob|ogv)$");
			if (!(regex.test(val))) {
				$(this).val('');
				alert('Please select any one of this formats \n webm,mp4,flv,wmv,m4v,3gp,avi,ogg,mov,\n mpg,mkv,asf,vob,ogv');
			}       
		});
		
		// Validation check for external url link
		$("#url_file").change(function () {
			var val = $(this).val().toLowerCase(),
				regex = new RegExp("(.*?)\.(webm|mp4|flv|wmv|m4v|3gp|avi|ogg|mov|mpg|mkv|asf|vob|ogv)$");
			if (!(regex.test(val))) {
				$(this).val('');
				alert('Please select any one of this formats \n webm,mp4,flv,wmv,m4v,3gp,avi,ogg,mov,\n mpg,mkv,asf,vob,ogv');
			}       
		});
		
		// Adding new input box		
		//$("input[name=internal_file]").after('<button type="button" id="add_internal_file">+</button>');	
		var add_button = $("#add_internal_file");
		var count = 0;
		$(add_button).click(function (e) {
			count++;
			e.preventDefault();
			if(count < 3){
				$(add_button).after('<br/><br/><td width="100%"><input size="26" style="width: 250px" name="internal_file_'+count+'" id="internal_file_'+count+'" type="file"><a href="javascript:void(0)" class="remove_field">X</a>');
			}
			if(count == 3){count = 2;}
			// Validation check for internal file1
			$("#internal_file_1").change(function () {
				var val = $(this).val().toLowerCase(),
					regex = new RegExp("(.*?)\.(webm|mp4|flv|wmv|m4v|3gp|avi|ogg|mov|mpg|mkv|asf|vob|ogv)$");
				if (!(regex.test(val))) {
					$(this).val('');
					alert('Please select any one of this formats \n webm,mp4,flv,wmv,m4v,3gp,avi,ogg,mov,\n mpg,mkv,asf,vob,ogv');
				}       
			});

			// Validation check for internal file 2
			$("#internal_file_2").change(function () {
				var val = $(this).val().toLowerCase(),
					regex = new RegExp("(.*?)\.(webm|mp4|flv|wmv|m4v|3gp|avi|ogg|mov|mpg|mkv|asf|vob|ogv)$");
				if (!(regex.test(val))) {
					$(this).val('');
					alert('Please select any one of this formats \n webm,mp4,flv,wmv,m4v,3gp,avi,ogg,mov,\n mpg,mkv,asf,vob,ogv');
				}       
			});						
		});
		
		// Removing new input box	
		$(".remove_field").click(function(){
			$(this).prev().prev().remove();
			$(this).prev().remove();
			$(this).next().remove();
			$(this).remove();
			count--;
		});	
		
		/* 
			* Hide these on page load "OVERLAY VIDEO"
		*/
			
		// Hide Third party Click tracking
		$("label[for=vast_thirdparty_clickcustom]").parent().parent().parent().prev().hide();
		$("label[for=vast_thirdparty_clickcustom]").parent().parent().parent().hide();	
						
		// Hide Overlay Advanced Settings
		$("label[for=vast_overlay_expandedminduration]").parent().parent().parent().prev().hide();
		$("label[for=vast_overlay_expandedminduration]").parent().parent().parent().hide();
							
		// Hide Alt Text(Vast4)
		$("label[for=vast_thirdparty_companion_alttext]").parent().parent().prev().hide();
		$("label[for=vast_thirdparty_companion_alttext]").parent().parent().hide(); 
						
		// Hide pxratio(Vast4)
		$("label[for=vast_thirdparty_companion_pxratio]").parent().parent().prev().hide();
		$("label[for=vast_thirdparty_companion_pxratio]").parent().parent().hide();
		 				
		// Hide Asset Height(Vast4)
		$("label[for=vast_thirdparty_companion_assetheight]").parent().parent().prev().hide();
		$("label[for=vast_thirdparty_companion_assetheight]").parent().parent().hide();
		 				
		// Hide Asset Width(Vast4)
		$("label[for=vast_thirdparty_companion_assetwidth]").parent().parent().prev().hide();
		$("label[for=vast_thirdparty_companion_assetwidth]").parent().parent().hide(); 
		
		// Check Overlay click action as Default
		$("#overlay-action-open").attr("checked", true);						
	});
	
	function phpAds_vastversion(version){
		if(version.value == 1){
			/* 
				* Hide these on page load "INLINE VIDEO"
			*/
						
			// Check progressive as default
			$("#video-url-format-progressive").attr("checked", true);
			
			// Select Inline as default
			$("#get_third_internal_type [value=1]").attr("selected", true);
			
			// Hide Ad type 
			$("#ad_type").parent().parent().prev().hide();
			$("#ad_type").parent().parent().hide();	
									
			// Hide Third Party Vast Tag 
			$("label[for=vast_wrapper_url]").parent().parent().prev().hide();
			$("label[for=vast_wrapper_url]").parent().parent().hide(); 
				
			// Hide Vast Version 
			$("label[for=vast_version]").parent().parent().prev().hide();
			$("label[for=vast_version]").parent().parent().hide();
				
			// Hide FallBack No Ad(Wrapper)
			$("label[for=vast_wrapper_fallbacknoads]").parent().parent().prev().hide();
			$("label[for=vast_wrapper_fallbacknoads]").parent().parent().hide();
				
			// Hide Allow Multiple Ads
			$("label[for=vast_wrapper_allowmultipleads]").parent().parent().prev().hide();
			$("label[for=vast_wrapper_allowmultipleads]").parent().parent().hide(); 
				
			// Hide FallBack No Ad(Wrapper) Additional
			$("label[for=vast_wrapper_followadditional]").parent().parent().prev().hide();
			$("label[for=vast_wrapper_followadditional]").parent().parent().hide();						
			
			// Hide VAST 4.1 Closed Caption
			$("#vast_4_1_type_file").parent().parent().parent().hide();
						
			// Hide Video delivery method(Third party Vast Tag)
			$("label[for=video-url-format-vast]").hide();
			$("#video-url-format-vast").hide();
			
			// Hide VAST 4.0 Special Features
			$("#vast4_category_name").parent().parent().parent().parent().hide();
			
			// Hide VAST 3.0 Special Features
			$("#vast_video_skip_progress_duration").parent().parent().parent().hide();
			
			// Hide Video Side 
			$("label[for=server_side]").parent().parent().prev().hide();
			$("label[for=server_side]").parent().parent().hide();	
			
			// Hide Icon information
			$("#icon_track_url").parent().parent().parent().hide();	
			
			// Hide Mezzanine Format 
			$("label[for=mezzanine_disabled]").parent().parent().prev().hide();
			$("label[for=mezzanine_disabled]").parent().parent().parent().hide();
			
			// Hide Externally-hosted URL 
			$("#url_file").parent().parent().prev().hide();	
			$("#url_file").parent().parent().hide();
		
			// Hide Ad Manager-hosted File					
			$("input[name=internal_file]").parent().parent().prev().hide();	
			$("input[name=internal_file]").parent().parent().hide();
																	
			// Hide Designate your creative file as the mezzanine file					
			$("label[for=mezzanine_external]").parent().parent().prev().hide();
			$("label[for=mezzanine_external]").parent().parent().hide();

			// Hide Video URL/Filename 1
			$("label[for=vast_video_outgoing_filename1]").parent().parent().prev().hide();
			$("label[for=vast_video_outgoing_filename1]").parent().parent().hide(); 						
										
			// Hide Video URL/Filename 2
			$("label[for=vast_video_outgoing_filename2]").parent().parent().prev().hide();
			$("label[for=vast_video_outgoing_filename2]").parent().parent().hide(); 											
										
			// Hide Video type 1
			$("label[for=vast_video_type_1]").parent().parent().prev().hide();
			$("label[for=vast_video_type_1]").parent().parent().hide(); 						
										
			// Hide Video type 2
			$("label[for=vast_video_type_2]").parent().parent().prev().hide();
			$("label[for=vast_video_type_2]").parent().parent().hide(); 
								
			// Hide Video bitrate 1
			$("label[for=vast_video_bitrate_1]").parent().parent().prev().hide();
			$("label[for=vast_video_bitrate_1]").parent().parent().hide();
					
			// Hide Video bitrate 2
			$("label[for=vast_video_bitrate_2]").parent().parent().prev().hide();
			$("label[for=vast_video_bitrate_2]").parent().parent().hide();
						
			// Hide Add New Video URL, Video type,Video bitrate
			$("#add_url_bitrate_type").hide();	
			
			// Hide Interaction
			$("label[for=interactive_mediafile]").parent().parent().prev().hide();
			$("label[for=interactive_mediafile]").parent().parent().hide();
					       
			// Hide RTMP server URL
			$("label[for=vast_net_connection_url]").parent().parent().prev().hide();
			$("label[for=vast_net_connection_url]").parent().parent().hide();
									
			// Show Video URL/Filename
			$("label[for=vast_video_filename]").parent().parent().prev().show();
			$("label[for=vast_video_filename]").parent().parent().show(); 											
										 															
			// Show Video type
			$("label[for=vast_video_type]").parent().parent().prev().show();
			$("label[for=vast_video_type]").parent().parent().show(); 						
											
			// Show Video duration in seconds 
			$("label[for=vast_video_duration]").parent().parent().prev().show();
			$("label[for=vast_video_duration]").parent().parent().show();				
											
			// Show Destination URL (incl. http://) when user clicks on the video
			$("label[for=vast_video_clickthrough_url]").parent().parent().prev().show();
			$("label[for=vast_video_clickthrough_url]").parent().parent().show(); 
						
			// Show Video bitrate
			$("label[for=vast_video_bitrate]").parent().parent().prev().show();
			$("label[for=vast_video_bitrate]").parent().parent().show(); 
								
			// Show Video delivery method 
			$("#video-url-format-progressive").parent().prev().show();
			$("#video-url-format-progressive").parent().show();
			
			/* 
			* Hide these on page load "OVERLAY VIDEO"
			*/
				
			// Hide Third party Click tracking
			$("label[for=vast_thirdparty_clickcustom]").parent().parent().parent().prev().hide();
			$("label[for=vast_thirdparty_clickcustom]").parent().parent().parent().hide();	
							
			// Hide Overlay Advanced Settings
			$("label[for=vast_overlay_expandedminduration]").parent().parent().parent().prev().hide();
			$("label[for=vast_overlay_expandedminduration]").parent().parent().parent().hide();	
				
			// Hide Alt Text(Vast4)
			$("label[for=vast_thirdparty_companion_alttext]").parent().parent().prev().hide();
			$("label[for=vast_thirdparty_companion_alttext]").parent().parent().hide(); 
							
			// Hide pxratio(Vast4)
			$("label[for=vast_thirdparty_companion_pxratio]").parent().parent().prev().hide();
			$("label[for=vast_thirdparty_companion_pxratio]").parent().parent().hide();
							
			// Hide Asset Height(Vast4)
			$("label[for=vast_thirdparty_companion_assetheight]").parent().parent().prev().hide();
			$("label[for=vast_thirdparty_companion_assetheight]").parent().parent().hide();
							
			// Hide Asset Width(Vast4)
			$("label[for=vast_thirdparty_companion_assetwidth]").parent().parent().prev().hide();
			$("label[for=vast_thirdparty_companion_assetwidth]").parent().parent().hide(); 																							
		}
		if(version.value == 2){
			// Check progressive as default
			$("#video-url-format-progressive").attr("checked", true);
			
			// Select Inline as default
			$("#get_third_internal_type [value=1]").attr("selected", true);
			
			// Hide Ad type 
			$("#ad_type").parent().parent().prev().hide();
			$("#ad_type").parent().parent().hide();	
													
			// Hide Third Party Vast Tag 
			$("label[for=vast_wrapper_url]").parent().parent().prev().hide();
			$("label[for=vast_wrapper_url]").parent().parent().hide(); 
				
			// Hide Vast Version 
			$("label[for=vast_version]").parent().parent().prev().hide();
			$("label[for=vast_version]").parent().parent().hide();
				
			// Hide FallBack No Ad(Wrapper)
			$("label[for=vast_wrapper_fallbacknoads]").parent().parent().prev().hide();
			$("label[for=vast_wrapper_fallbacknoads]").parent().parent().hide();
				
			// Hide Allow Multiple Ads
			$("label[for=vast_wrapper_allowmultipleads]").parent().parent().prev().hide();
			$("label[for=vast_wrapper_allowmultipleads]").parent().parent().hide(); 
				
			// Hide FallBack No Ad(Wrapper) Additional
			$("label[for=vast_wrapper_followadditional]").parent().parent().prev().hide();
			$("label[for=vast_wrapper_followadditional]").parent().parent().hide();			
						
			// Hide VAST 4.1 Closed Caption
			$("#vast_4_1_type_file").parent().parent().parent().hide();
						
			// Show VAST 3.0 Special Features
			$("#vast_video_skip_progress_duration").parent().parent().parent().show();	
			
			// Hide VAST 4.0 Special Features
			$("#vast4_category_name").parent().parent().parent().parent().hide();	
			
			// Hide Video Side 
			$("label[for=server_side]").parent().parent().prev().hide();
			$("label[for=server_side]").parent().parent().hide();
			
			// Show Icon information
			$("#icon_track_url").parent().parent().parent().show();	
			
			// Hide Mezzanine Format 
			$("label[for=mezzanine_disabled]").parent().parent().prev().hide();
			$("label[for=mezzanine_disabled]").parent().parent().parent().hide();
			
			// Hide Externally-hosted URL 
			$("#url_file").parent().parent().prev().hide();	
			$("#url_file").parent().parent().hide();
		
			// Hide Ad Manager-hosted File					
			$("input[name=internal_file]").parent().parent().prev().hide();	
			$("input[name=internal_file]").parent().parent().hide();
																	
			// Hide Designate your creative file as the mezzanine file					
			$("label[for=mezzanine_external]").parent().parent().prev().hide();
			$("label[for=mezzanine_external]").parent().parent().hide();
			
			// Show Video URL/Filename
			$("label[for=vast_video_filename]").parent().parent().prev().show();
			$("label[for=vast_video_filename]").parent().parent().show(); 						
										
			// Hide Video URL/Filename 1
			$("label[for=vast_video_outgoing_filename1]").parent().parent().prev().hide();
			$("label[for=vast_video_outgoing_filename1]").parent().parent().hide(); 						
										
			// Hide Video URL/Filename 2
			$("label[for=vast_video_outgoing_filename2]").parent().parent().prev().hide();
			$("label[for=vast_video_outgoing_filename2]").parent().parent().hide(); 											
										
			// Hide Video type 1
			$("label[for=vast_video_type_1]").parent().parent().prev().hide();
			$("label[for=vast_video_type_1]").parent().parent().hide(); 						
										
			// Hide Video type 2
			$("label[for=vast_video_type_2]").parent().parent().prev().hide();
			$("label[for=vast_video_type_2]").parent().parent().hide(); 
								
			// Hide Video bitrate 1
			$("label[for=vast_video_bitrate_1]").parent().parent().prev().hide();
			$("label[for=vast_video_bitrate_1]").parent().parent().hide();
					
			// Hide Video bitrate 2
			$("label[for=vast_video_bitrate_2]").parent().parent().prev().hide();
			$("label[for=vast_video_bitrate_2]").parent().parent().hide();
														
			// Show Video type
			$("label[for=vast_video_type]").parent().parent().prev().show();
			$("label[for=vast_video_type]").parent().parent().show(); 						
											
			// Show Video duration in seconds 
			$("label[for=vast_video_duration]").parent().parent().prev().show();
			$("label[for=vast_video_duration]").parent().parent().show();				
											
			// Show Destination URL (incl. http://) when user clicks on the video
			$("label[for=vast_video_clickthrough_url]").parent().parent().prev().show();
			$("label[for=vast_video_clickthrough_url]").parent().parent().show(); 
						
			// Show Video bitrate
			$("label[for=vast_video_bitrate]").parent().parent().prev().show();
			$("label[for=vast_video_bitrate]").parent().parent().show(); 
								
			// Show Video delivery method 
			$("#video-url-format-progressive").parent().prev().show();
			$("#video-url-format-progressive").parent().show();						
			
			// Hide Add New Video URL, Video type,Video bitrate
			$("#add_url_bitrate_type").hide();
			
			// Hide Interaction
			$("label[for=interactive_mediafile]").parent().parent().prev().hide();
			$("label[for=interactive_mediafile]").parent().parent().hide();
		       
			// Hide RTMP server URL
			$("label[for=vast_net_connection_url]").parent().parent().prev().hide();
			$("label[for=vast_net_connection_url]").parent().parent().hide();
			
			/* 
				* Show these on page load "OVERLAY VIDEO"
			*/
				
			// Show Third party Click tracking
			$("label[for=vast_thirdparty_clickcustom]").parent().parent().parent().prev().show();
			$("label[for=vast_thirdparty_clickcustom]").parent().parent().parent().show();	
							
			// Show Overlay Advanced Settings
			$("label[for=vast_overlay_expandedminduration]").parent().parent().parent().prev().show();
			$("label[for=vast_overlay_expandedminduration]").parent().parent().parent().show();	
						
			// Hide Alt Text(Vast4)
			$("label[for=vast_thirdparty_companion_alttext]").parent().parent().prev().hide();
			$("label[for=vast_thirdparty_companion_alttext]").parent().parent().hide(); 
							
			// Hide pxratio(Vast4)
			$("label[for=vast_thirdparty_companion_pxratio]").parent().parent().prev().hide();
			$("label[for=vast_thirdparty_companion_pxratio]").parent().parent().hide();
							
			// Hide Asset Height(Vast4)
			$("label[for=vast_thirdparty_companion_assetheight]").parent().parent().prev().hide();
			$("label[for=vast_thirdparty_companion_assetheight]").parent().parent().hide();
							
			// Hide Asset Width(Vast4)
			$("label[for=vast_thirdparty_companion_assetwidth]").parent().parent().prev().hide();
			$("label[for=vast_thirdparty_companion_assetwidth]").parent().parent().hide();			 																										
		}
		if(version.value == 3){
			// Check progressive as default
			$("#video-url-format-progressive").attr("checked", true);
			
			// Show Add New Video URL, Video type,Video bitrate
			$("#add_url_bitrate_type").show();
			
			// Hide VAST 4.1 Closed Caption
			$("#vast_4_1_type_file").parent().parent().parent().hide();
						
			// Show VAST 4.0 Special Features
			$("#vast4_category_name").parent().parent().parent().parent().show();
			
			// Show VAST 3.0 Special Features
			$("#vast_video_skip_progress_duration").parent().parent().parent().show();	
			
			// Show Video Side 
			$("label[for=server_side]").parent().parent().prev().show();
			$("label[for=server_side]").parent().parent().show();
			
			// Show Icon information
			$("#icon_track_url").parent().parent().parent().show();	
			
			// Show Mezzanine Format 
			$("label[for=mezzanine_disabled]").parent().parent().prev().show();
			$("label[for=mezzanine_disabled]").parent().parent().parent().show();
			
			// Show Interaction
			$("label[for=interactive_mediafile]").parent().parent().prev().show();
			$("label[for=interactive_mediafile]").parent().parent().show();
			
			// Show Conditional Ad Type 
			$("label[for=is_conditionalad]").parent().parent().prev().show();
			$("label[for=is_conditionalad]").parent().parent().show();					
			
			// Select Inline as default
			$("#get_third_internal_type [value=1]").attr("selected", true);
				
			// Hide Third Party Vast Tag 
			$("label[for=vast_wrapper_url]").parent().parent().prev().hide();
			$("label[for=vast_wrapper_url]").parent().parent().hide(); 
				
			// Hide Vast Version 
			$("label[for=vast_version]").parent().parent().prev().hide();
			$("label[for=vast_version]").parent().parent().hide();
				
			// Hide FallBack No Ad(Wrapper)
			$("label[for=vast_wrapper_fallbacknoads]").parent().parent().prev().hide();
			$("label[for=vast_wrapper_fallbacknoads]").parent().parent().hide();
				
			// Hide Allow Multiple Ads
			$("label[for=vast_wrapper_allowmultipleads]").parent().parent().prev().hide();
			$("label[for=vast_wrapper_allowmultipleads]").parent().parent().hide(); 
				
			// Hide FallBack No Ad(Wrapper) Additional
			$("label[for=vast_wrapper_followadditional]").parent().parent().prev().hide();
			$("label[for=vast_wrapper_followadditional]").parent().parent().hide();	
								
			// Show Video delivery method 
			$("#video-url-format-progressive").parent().prev().show();
			$("#video-url-format-progressive").parent().show();
			
			// Hide Ad type 
			$("#ad_type").parent().parent().prev().hide();
			$("#ad_type").parent().parent().hide();
																			
			// Call Progressive function as default
			phpAds_formHttpProgressiveVideoUrlMode();		
																					
		}
		if(version.value == 4){
			// Check Audio Type(Audio/MPEG) as Default
			$("#audio-type-mpeg").attr("checked", true);
								
			// Check progressive as default
			$("#video-url-format-progressive").attr("checked", true);
						
			// Show VAST 4.1 Closed Caption
			$("#vast_4_1_type_file").parent().parent().parent().show();
			
			// Show Add New Video URL, Video type,Video bitrate
			$("#add_url_bitrate_type").show();
			
			// Show VAST 4.0 Special Features
			$("#vast4_category_name").parent().parent().parent().parent().show();
			
			// Show VAST 3.0 Special Features
			$("#vast_video_skip_progress_duration").parent().parent().parent().show();	
			
			// Show Ad type 
			$("#ad_type").parent().parent().prev().show();
			$("#ad_type").parent().parent().show();
													
			// Show Video Side 
			$("label[for=server_side]").parent().parent().prev().show();
			$("label[for=server_side]").parent().parent().show();
			
			// Show Icon information
			$("#icon_track_url").parent().parent().parent().show();	
			
			// Show Mezzanine Format 
			$("label[for=mezzanine_disabled]").parent().parent().prev().show();
			$("label[for=mezzanine_disabled]").parent().parent().parent().show();															
			// Hide Conditional Ad Type 
			$("label[for=is_conditionalad]").parent().parent().prev().hide();
			$("label[for=is_conditionalad]").parent().parent().hide();
			
			// Show Interaction
			$("label[for=interactive_mediafile]").parent().parent().prev().show();
			$("label[for=interactive_mediafile]").parent().parent().show();			
			
			// Select Inline as default
			$("#get_third_internal_type [value=1]").attr("selected", true);
				
			// Hide Third Party Vast Tag 
			$("label[for=vast_wrapper_url]").parent().parent().prev().hide();
			$("label[for=vast_wrapper_url]").parent().parent().hide(); 
				
			// Hide Vast Version 
			$("label[for=vast_version]").parent().parent().prev().hide();
			$("label[for=vast_version]").parent().parent().hide();
				
			// Hide FallBack No Ad(Wrapper)
			$("label[for=vast_wrapper_fallbacknoads]").parent().parent().prev().hide();
			$("label[for=vast_wrapper_fallbacknoads]").parent().parent().hide();
				
			// Hide Allow Multiple Ads
			$("label[for=vast_wrapper_allowmultipleads]").parent().parent().prev().hide();
			$("label[for=vast_wrapper_allowmultipleads]").parent().parent().hide(); 
				
			// Hide FallBack No Ad(Wrapper) Additional
			$("label[for=vast_wrapper_followadditional]").parent().parent().prev().hide();
			$("label[for=vast_wrapper_followadditional]").parent().parent().hide();	
								
			// Show Video delivery method 
			$("#video-url-format-progressive").parent().prev().show();
			$("#video-url-format-progressive").parent().show();
			
			// Hide options vast_video_type
			$("#vast_video_type option[value='audio/mpeg']").hide();						
			$("#vast_video_type option[value='audio/aac']").hide();	
									
			// Call Progressive function as default
			phpAds_formHttpProgressiveVideoUrlMode();
			
			// Adding new input box	in closed caption	
			if($("#add_url_type_language").length == 0){			
				$("#vast_4_1_language").after('&nbsp&nbsp&nbsp&nbsp<button type="button" id="add_url_type_language">Add New URL, File Type,Language</button>');	
				var add_button_utl = $("#add_url_type_language");
				var count_utl = 0;
				var count_utl_plus = count_utl + 1;
				$(add_button_utl).click(function (e) {
					count_utl++;
					count_utl_plus++;
					e.preventDefault();
					if(count_utl < 3){
						if(count_utl != 2){
							$(add_button_utl).parent().parent().after('<tr class="spacer"><td style="height:1px;" colspan="3">&nbsp;</td></tr><tr><td width="30">&nbsp;</td><td width="170"><label for="vast_4_1_url_'+count_utl+'">Closed Caption Media File '+count_utl_plus+'</label></td><td width="100%"><input name="vast_4_1_url_'+count_utl+'" type="text" id="vast_4_1_url_'+count_utl+'" class="large"></td></tr><tr class="spacer"><td style="height:1px;" colspan="3">&nbsp;</td></tr><tr><td width="30">&nbsp;</td><td width="170"><label for="vast_4_1_type_file_'+count_utl+'">File Type '+count_utl_plus+'<font color="red">*</font> </label></td><td width="100%"><select name="vast_4_1_type_file_'+count_utl+'" id="vast_4_1_type_file_'+count_utl+'" class="medium"><option value="1">text/srt</option><option value="2">text/vtt</option><option value="3">application/ttml+xml</option></select></td></tr><tr class="spacer"><td style="height:1px;" colspan="3">&nbsp;</td></tr><tr><td width="30">&nbsp;</td><td width="170"><label for="vast_4_1_language_'+count_utl+'">Language '+count_utl_plus+'<font color="red">*</font> </label></td><td width="100%"><select name="vast_4_1_language_'+count_utl+'" id="vast_4_1_language_'+count_utl+'" class="medium"><option value="1">en</option><option value="2">zh-TW</option><option value="3">zh-CH</option></select>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" class="remove_field_parent_utl">X</a></td></tr>');
							// Removing remove_field_parent_utl	
								$(".remove_field_parent_utl").click(function(){
									$(this).parent().parent().prev().prev().prev().prev().remove();
									$(this).parent().parent().prev().prev().prev().remove();
									$(this).parent().parent().prev().prev().remove();
									$(this).parent().parent().prev().remove();
									$(this).parent().parent().next().remove();
									$(this).parent().parent().remove();
									count_utl--;count_utl_plus--;
								});									
						}
						else{
							$("#vast_4_1_language_1").parent().parent().after('<tr class="spacer"><td style="height:1px;" colspan="3">&nbsp;</td></tr><tr><td width="30">&nbsp;</td><td width="170"><label for="vast_4_1_url_'+count_utl+'">Closed Caption Media File '+count_utl_plus+'</label></td><td width="100%"><input name="vast_4_1_url_'+count_utl+'" type="text" id="vast_4_1_url_'+count_utl+'" class="large"></td></tr><tr class="spacer"><td style="height:1px;" colspan="3">&nbsp;</td></tr><tr><td width="30">&nbsp;</td><td width="170"><label for="vast_4_1_type_file_'+count_utl+'">File Type '+count_utl_plus+'<font color="red">*</font> </label></td><td width="100%"><select name="vast_4_1_type_file_'+count_utl+'" id="vast_4_1_type_file_'+count_utl+'" class="medium"><option value="1">text/srt</option><option value="2">text/vtt</option><option value="3">application/ttml+xml</option></select></td></tr><tr class="spacer"><td style="height:1px;" colspan="3">&nbsp;</td></tr><tr><td width="30">&nbsp;</td><td width="170"><label for="vast_4_1_language_'+count_utl+'">Language '+count_utl_plus+'<font color="red">*</font> </label></td><td width="100%"><select name="vast_4_1_language_'+count_utl+'" id="vast_4_1_language_'+count_utl+'" class="medium"><option value="1">en</option><option value="2">zh-TW</option><option value="3">zh-CH</option></select>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" class="remove_field_parent_utl2">X</a></td></tr>');		
							// Removing remove_field_parent_utl	
							$(".remove_field_parent_utl2").click(function(){
								$(this).parent().parent().prev().prev().prev().prev().remove();
								$(this).parent().parent().prev().prev().prev().remove();
								$(this).parent().parent().prev().prev().remove();
								$(this).parent().parent().prev().remove();
								$(this).parent().parent().next().remove();
								$(this).parent().parent().remove();
								count_utl--;count_utl_plus--;
							});									
							}
					}
					if(count_utl == 3){count_utl = 2;count_utl_plus = 3;}		
				});	
			}																				
		}
			} 
	function phpAds_mezzanine_internal(){
		// Hide Externally-hosted URL 
		$("#url_file").parent().parent().prev().hide();	
		$("#url_file").parent().parent().hide();
		
		// Show Ad Manager-hosted File				
		$("input[name=internal_file]").parent().parent().prev().show();	
		$("input[name=internal_file]").parent().parent().show();
	}
	function phpAds_mezzanine_external(){
		// Show Externally-hosted URL 
		$("#url_file").parent().parent().prev().show();	
		$("#url_file").parent().parent().show();
		
		// Hide Ad Manager-hosted File					
		$("input[name=internal_file]").parent().parent().prev().hide();	
		$("input[name=internal_file]").parent().parent().hide();
	}
<?php } else{ 
			$table_prefix = $GLOBALS['_MAX']['CONF']['table']['prefix'];
			$query_ban=OA_Dal_Delivery_query("SELECT * from {$table_prefix}banners inner join {$table_prefix}djaxbanner_vast_element on {$table_prefix}banners.bannerid = {$table_prefix}djaxbanner_vast_element.banner_id where {$table_prefix}banners.bannerid = ".$_GET['bannerid']);
			$row_ban = OA_Dal_Delivery_fetchAssoc($query_ban);
			if($row_ban['vast_type'] == 1){
		?>	
				vasttype1();
			<?php } elseif($row_ban['vast_type'] == 2){?>
				vasttype2();
			<?php } else if($row_ban['vast_type'] == 3){?>
				vasttype3();
			<?php } else{?>		
				vasttype4();
			<?php }?>
			$(document).ready(function(){
				// Check radio_internal_file_keep as default
				$("#radio_internal_file_keep").attr("checked", true);
								
				// When Third Party Wrapper Ads Selected	
				$("#get_third_internal_type").change(function (){
					var vast_version = $("#vast_type").val();
					if(this.value == 2){		
						// Hide Ad type 
						$("#ad_type").parent().parent().prev().hide();
						$("#ad_type").parent().parent().hide();	
															
						// Hide Video bitrate
						$("label[for=vast_video_bitrate]").parent().parent().prev().hide();
						$("label[for=vast_video_bitrate]").parent().parent().hide();
						
						// Hide Video bitrate 1
						$("label[for=vast_video_bitrate_1]").parent().parent().prev().hide();
						$("label[for=vast_video_bitrate_1]").parent().parent().hide(); 			
								
						// Hide Video bitrate 2
						$("label[for=vast_video_bitrate_2]").parent().parent().prev().hide();
						$("label[for=vast_video_bitrate_2]").parent().parent().hide(); 
									
						// Hide Video Native width
						$("label[for=vast_video_width]").parent().parent().prev().hide();
						$("label[for=vast_video_width]").parent().parent().hide();
					
						// Hide Video Native height
						$("label[for=vast_video_height]").parent().parent().prev().hide();
						$("label[for=vast_video_height]").parent().parent().hide();
					
						// Hide Video Minimum bitrate(Vast4)
						$("label[for=vast4_min_bitrate]").parent().parent().prev().hide();
						$("label[for=vast4_min_bitrate]").parent().parent().hide();
						
						// Hide Video Maximum bitrate(Vast4)
						$("label[for=vast4_max_bitrate]").parent().parent().prev().hide();
						$("label[for=vast4_max_bitrate]").parent().parent().hide();
						
						// Hide Mezzanine Format 
						$("label[for=mezzanine_disabled]").parent().parent().prev().hide();
						$("label[for=mezzanine_disabled]").parent().parent().parent().hide();
										
						// Hide Video delivery method 
						$("#video-url-format-progressive").parent().prev().hide();
						$("#video-url-format-progressive").parent().hide();
					
						// Hide RTMP server URL
						$("label[for=vast_net_connection_url]").parent().parent().prev().hide();
						$("label[for=vast_net_connection_url]").parent().parent().hide(); 
												
						// Hide Video URL/Filename
						$("label[for=vast_video_filename]").parent().parent().prev().hide();
						$("label[for=vast_video_filename]").parent().parent().hide(); 						
												
						// Hide Video URL/Filename 1
						$("label[for=vast_video_outgoing_filename1]").parent().parent().prev().hide();
						$("label[for=vast_video_outgoing_filename1]").parent().parent().hide(); 						
												
						// Hide Video URL/Filename 2
						$("label[for=vast_video_outgoing_filename2]").parent().parent().prev().hide();
						$("label[for=vast_video_outgoing_filename2]").parent().parent().hide(); 						
												
						// Hide Video type
						$("label[for=vast_video_type]").parent().parent().prev().hide();
						$("label[for=vast_video_type]").parent().parent().hide(); 						
											
						// Hide Video type 1
						$("label[for=vast_video_type_1]").parent().parent().prev().hide();
						$("label[for=vast_video_type_1]").parent().parent().hide(); 						
												
						// Hide Video type 2
						$("label[for=vast_video_type_2]").parent().parent().prev().hide();
						$("label[for=vast_video_type_2]").parent().parent().hide(); 						
												
						// Hide Video duration in seconds 
						$("label[for=vast_video_duration]").parent().parent().prev().hide();
						$("label[for=vast_video_duration]").parent().parent().hide();
						
						// Hide VAST 4.1 Closed Caption
						$("#vast_4_1_type_file").parent().parent().parent().hide();
						
						// Hide VAST 4.0 Special Features
						$("#vast4_category_name").parent().parent().parent().parent().hide();
															
						// Show Destination URL (incl. http://) when user clicks on the video
						$("label[for=vast_video_clickthrough_url]").parent().parent().prev().show();
						$("label[for=vast_video_clickthrough_url]").parent().parent().show(); 
												
						// Show Third Party Vast Tag 
						$("label[for=vast_wrapper_url]").parent().parent().prev().show();
						$("label[for=vast_wrapper_url]").parent().parent().show(); 					
					}
					if(this.value == 1){
						// Check Progressive as Default
						$("#video-url-format-progressive").attr("checked", true);
						
						// Hide Third Party Vast Tag 
						$("label[for=vast_wrapper_url]").parent().parent().prev().hide();
						$("label[for=vast_wrapper_url]").parent().parent().hide(); 
						
						// Hide Vast Version 
						$("label[for=vast_version]").parent().parent().prev().hide();
						$("label[for=vast_version]").parent().parent().hide();
						
						// Hide FallBack No Ad(Wrapper)
						$("label[for=vast_wrapper_fallbacknoads]").parent().parent().prev().hide();
						$("label[for=vast_wrapper_fallbacknoads]").parent().parent().hide();
						
						// Hide Allow Multiple Ads
						$("label[for=vast_wrapper_allowmultipleads]").parent().parent().prev().hide();
						$("label[for=vast_wrapper_allowmultipleads]").parent().parent().hide(); 
						
						// Hide FallBack No Ad(Wrapper) Additional
						$("label[for=vast_wrapper_followadditional]").parent().parent().prev().hide();
						$("label[for=vast_wrapper_followadditional]").parent().parent().hide();	
						
						// Hide RTMP server URL
						$("label[for=vast_net_connection_url]").parent().parent().prev().hide();
						$("label[for=vast_net_connection_url]").parent().parent().hide(); 
								
						// Show Video bitrate
						$("label[for=vast_video_bitrate]").parent().parent().prev().show();
						$("label[for=vast_video_bitrate]").parent().parent().show();
							
						// Show Video bitrate 1
						$("label[for=vast_video_bitrate_1]").parent().parent().prev().show();
						$("label[for=vast_video_bitrate_1]").parent().parent().show(); 			
									
						// Show Video bitrate 2
						$("label[for=vast_video_bitrate_2]").parent().parent().prev().show();
						$("label[for=vast_video_bitrate_2]").parent().parent().show(); 
										
						// Show Video Native width
						$("label[for=vast_video_width]").parent().parent().prev().show();
						$("label[for=vast_video_width]").parent().parent().show();
						
						// Show Video Native height
						$("label[for=vast_video_height]").parent().parent().prev().show();
						$("label[for=vast_video_height]").parent().parent().show();
						
						// Show Video Minimum bitrate(Vast4)
						$("label[for=vast4_min_bitrate]").parent().parent().prev().show();
						$("label[for=vast4_min_bitrate]").parent().parent().show();
						
						// Show Video Maximum bitrate(Vast4)
						$("label[for=vast4_max_bitrate]").parent().parent().prev().show();
						$("label[for=vast4_max_bitrate]").parent().parent().show();
										
						// Show Mezzanine Format 
						$("label[for=mezzanine_disabled]").parent().parent().prev().show();
						$("label[for=mezzanine_disabled]").parent().parent().parent().show();
										
						// Show Video delivery method 
						$("#video-url-format-progressive").parent().prev().show();
						$("#video-url-format-progressive").parent().show();
									
						// Show Video URL/Filename
						$("label[for=vast_video_filename]").parent().parent().prev().show();
						$("label[for=vast_video_filename]").parent().parent().show(); 						
												
						// Show Video URL/Filename 1
						$("label[for=vast_video_outgoing_filename1]").parent().parent().prev().show();
						$("label[for=vast_video_outgoing_filename1]").parent().parent().show(); 						
												
						// Show Video URL/Filename 2
						$("label[for=vast_video_outgoing_filename2]").parent().parent().prev().show();
						$("label[for=vast_video_outgoing_filename2]").parent().parent().show(); 
																
						// Show Video type
						$("label[for=vast_video_type]").parent().parent().prev().show();
						$("label[for=vast_video_type]").parent().parent().show(); 						
												
						// Show Video type 1
						$("label[for=vast_video_type_1]").parent().parent().prev().show();
						$("label[for=vast_video_type_1]").parent().parent().show(); 						
												
						// Show Video type 2
						$("label[for=vast_video_type_2]").parent().parent().prev().show();
						$("label[for=vast_video_type_2]").parent().parent().show();
																
						// Show Video duration in seconds 
						$("label[for=vast_video_duration]").parent().parent().prev().show();
						$("label[for=vast_video_duration]").parent().parent().show();
						if(vast_version == 4){							
							// Show Ad type 
							$("#ad_type").parent().parent().prev().show();
							$("#ad_type").parent().parent().show();
							
							// Show VAST 4.1 Closed Caption
							$("#vast_4_1_type_file").parent().parent().parent().show();
							
							// Show VAST 4.0 Special Features
							$("#vast4_category_name").parent().parent().parent().parent().show();					
						}	
						if(vast_version == 3){	
							// Show VAST 4.0 Special Features
							$("#vast4_category_name").parent().parent().parent().parent().show();						
						}			
					}
					if((vast_version == 1) || (vast_version == 2) ){
						// Hide Mezzanine Format 
						$("label[for=mezzanine_disabled]").parent().parent().prev().hide();
						$("label[for=mezzanine_disabled]").parent().parent().parent().hide();
						
						// Hide Interaction
						$("label[for=interactive_mediafile]").parent().parent().prev().hide();
						$("label[for=interactive_mediafile]").parent().parent().hide();	
													
						// Hide Video URL/Filename 1
						$("label[for=vast_video_outgoing_filename1]").parent().parent().prev().hide();
						$("label[for=vast_video_outgoing_filename1]").parent().parent().hide(); 						
													
						// Hide Video URL/Filename 2
						$("label[for=vast_video_outgoing_filename2]").parent().parent().prev().hide();
						$("label[for=vast_video_outgoing_filename2]").parent().parent().hide(); 											
													
						// Hide Video type 1
						$("label[for=vast_video_type_1]").parent().parent().prev().hide();
						$("label[for=vast_video_type_1]").parent().parent().hide(); 						
													
						// Hide Video type 2
						$("label[for=vast_video_type_2]").parent().parent().prev().hide();
						$("label[for=vast_video_type_2]").parent().parent().hide(); 
											
						// Hide Video bitrate 1
						$("label[for=vast_video_bitrate_1]").parent().parent().prev().hide();
						$("label[for=vast_video_bitrate_1]").parent().parent().hide();
								
						// Hide Video bitrate 2
						$("label[for=vast_video_bitrate_2]").parent().parent().prev().hide();
						$("label[for=vast_video_bitrate_2]").parent().parent().hide();							
					}	
					if((vast_version == 3) || (vast_version == 4) ){
						if(this.value == 1){					
							// Check Disable as Default
							$("#mezzanine_disabled").attr("checked", true);	
												
							// Show Mezzanine Format 
							$("label[for=mezzanine_disabled]").parent().parent().prev().show();
							$("label[for=mezzanine_disabled]").parent().parent().parent().show();
														
							// Show Interaction
							$("label[for=interactive_mediafile]").parent().parent().prev().show();
							$("label[for=interactive_mediafile]").parent().parent().show();						
						}
						else if(this.value == 2){
							// Hide Mezzanine Format 
							$("label[for=mezzanine_disabled]").parent().parent().prev().hide();
							$("label[for=mezzanine_disabled]").parent().parent().parent().hide();
														
							// Hide Interaction
							$("label[for=interactive_mediafile]").parent().parent().prev().hide();
							$("label[for=interactive_mediafile]").parent().parent().hide();
							
							// Hide Designate your creative file as the mezzanine file 
							$("label[for=mezzanine_external]").parent().parent().prev().hide();
							$("label[for=mezzanine_external]").parent().parent().hide();
							
							// Hide Externally-hosted URL 
							$("#url_file").parent().parent().prev().hide();	
							$("#url_file").parent().parent().hide();
						
							// Hide Ad Manager-hosted File					
							$("input[name=internal_file]").parent().parent().prev().hide();	
							$("input[name=internal_file]").parent().parent().hide();
						
							// Show FallBack No Ad(Wrapper)
							$("label[for=vast_wrapper_fallbacknoads]").parent().parent().prev().show();
							$("label[for=vast_wrapper_fallbacknoads]").parent().parent().show();
							
							// Show Allow Multiple Ads
							$("label[for=vast_wrapper_allowmultipleads]").parent().parent().prev().show();
							$("label[for=vast_wrapper_allowmultipleads]").parent().parent().show(); 
							
							// Show FallBack No Ad(Wrapper) Additional
							$("label[for=vast_wrapper_followadditional]").parent().parent().prev().show();
							$("label[for=vast_wrapper_followadditional]").parent().parent().show();									
						}
						else{																				
							// Hide Interaction
							$("label[for=interactive_mediafile]").parent().parent().prev().hide();
							$("label[for=interactive_mediafile]").parent().parent().hide();					
						}				
					}			
				});
				// Validation check for internal file
				$("input[name=internal_file]").change(function () {
					var val = $(this).val().toLowerCase(),
						regex = new RegExp("(.*?)\.(webm|mp4|flv|wmv|m4v|3gp|avi|ogg|mov|mpg|mkv|asf|vob|ogv)$");
					if (!(regex.test(val))) {
						$(this).val('');
						alert('Please select any one of this formats \n webm,mp4,flv,wmv,m4v,3gp,avi,ogg,mov,\n mpg,mkv,asf,vob,ogv');
					}       
				});
			
				// Validation check for external url link
				$("#url_file").change(function () {
					var val = $(this).val().toLowerCase(),
						regex = new RegExp("(.*?)\.(webm|mp4|flv|wmv|m4v|3gp|avi|ogg|mov|mpg|mkv|asf|vob|ogv)$");
					if (!(regex.test(val))) {
						$(this).val('');
						alert('Please select any one of this formats \n webm,mp4,flv,wmv,m4v,3gp,avi,ogg,mov,\n mpg,mkv,asf,vob,ogv');
					}       
				});
							
				$("#radio_internal_file_change").click(function(){			
					// Show Ad Manager-hosted File				
					$("input[name=internal_file]").parent().parent().prev().show();	
					$("input[name=internal_file]").parent().parent().show();							
				});		
														
				$("#radio_internal_file_keep").click(function(){
					// Hide Ad Manager-hosted File				
					$("input[name=internal_file]").parent().parent().prev().hide();	
					$("input[name=internal_file]").parent().parent().hide();												
				});											
			});	
			function phpAds_vastversion(version){
				if(version.value == 1){
					vasttype1();																					
				}
				if(version.value == 2){
					vasttype2(); 																										
				}
				if(version.value == 3){	
					vasttype3();																							
				}
				if(version.value == 4){
					vasttype4();
				}					
			}
			function phpAds_mezzanine_internal(){
				// Hide Externally-hosted URL 
				$("#url_file").parent().parent().prev().hide();	
				$("#url_file").parent().parent().hide();
				
				// Hide Ad Manager-hosted File				
				$("input[name=internal_file]").parent().parent().prev().hide();	
				$("input[name=internal_file]").parent().parent().hide();
				<?php if($row_ban['internal_file'] == ""){ ?>			
					// Show Ad Manager-hosted File				
					$("input[name=internal_file]").parent().parent().prev().show();	
					$("input[name=internal_file]").parent().parent().show();			
				<?php } else{?>			
					// Show radio_internal_file_keep				
					$("label[for=radio_internal_file_keep]").parent().parent().prev().show();	
					$("label[for=radio_internal_file_keep]").parent().parent().show();	
				<?php } ?>	
			}
			function phpAds_mezzanine_external(){
				// Show Externally-hosted URL 
				$("#url_file").parent().parent().prev().show();	
				$("#url_file").parent().parent().show();
				
				// Hide Ad Manager-hosted File					
				$("input[name=internal_file]").parent().parent().prev().hide();	
				$("input[name=internal_file]").parent().parent().hide();
						
				// Hide radio_internal_file_keep				
				$("label[for=radio_internal_file_keep]").parent().parent().prev().hide();	
				$("label[for=radio_internal_file_keep]").parent().parent().hide();
			}
			function vasttype1(){
				$(document).ready(function(){													
					// Hide Ad type 
					$("#ad_type").parent().parent().prev().hide();
					$("#ad_type").parent().parent().hide();	
								
					// Hide VAST 4.1 Closed Caption
					$("#vast_4_1_type_file").parent().parent().parent().hide();
								
					// Hide Video delivery method(Third party Vast Tag)
					$("label[for=video-url-format-vast]").hide();
					$("#video-url-format-vast").hide();
					
					// Hide VAST 4.0 Special Features
					$("#vast4_category_name").parent().parent().parent().parent().hide();
					
					// Hide VAST 3.0 Special Features
					$("#vast_video_skip_progress_duration").parent().parent().parent().hide();
					
					// Hide Icon information
					$("#icon_track_url").parent().parent().parent().hide();	
					
					// Hide Mezzanine Format 
					$("label[for=mezzanine_disabled]").parent().parent().prev().hide();
					$("label[for=mezzanine_disabled]").parent().parent().parent().hide();
					
					// Hide Externally-hosted URL 
					$("#url_file").parent().parent().prev().hide();	
					$("#url_file").parent().parent().hide();
				
					// Hide Ad Manager-hosted File					
					$("input[name=internal_file]").parent().parent().prev().hide();	
					$("input[name=internal_file]").parent().parent().hide();
																			
					// Hide Designate your creative file as the mezzanine file					
					$("label[for=mezzanine_external]").parent().parent().prev().hide();
					$("label[for=mezzanine_external]").parent().parent().hide();

					// Hide Video URL/Filename 1
					$("label[for=vast_video_outgoing_filename1]").parent().parent().prev().hide();
					$("label[for=vast_video_outgoing_filename1]").parent().parent().hide(); 						
												
					// Hide Video URL/Filename 2
					$("label[for=vast_video_outgoing_filename2]").parent().parent().prev().hide();
					$("label[for=vast_video_outgoing_filename2]").parent().parent().hide();
														
					// Hide Video type 1
					$("label[for=vast_video_type_1]").parent().parent().prev().hide();
					$("label[for=vast_video_type_1]").parent().parent().hide(); 						
												
					// Hide Video type 2
					$("label[for=vast_video_type_2]").parent().parent().prev().hide();
					$("label[for=vast_video_type_2]").parent().parent().hide(); 
										
					// Hide Video bitrate 1
					$("label[for=vast_video_bitrate_1]").parent().parent().prev().hide();
					$("label[for=vast_video_bitrate_1]").parent().parent().hide();
							
					// Hide Video bitrate 2
					$("label[for=vast_video_bitrate_2]").parent().parent().prev().hide();
					$("label[for=vast_video_bitrate_2]").parent().parent().hide();
								
					// Hide Add New Video URL, Video type,Video bitrate
					$("#add_url_bitrate_type").hide();	
					
					// Hide Interaction
					$("label[for=interactive_mediafile]").parent().parent().prev().hide();
					$("label[for=interactive_mediafile]").parent().parent().hide();
					
					<?php if($row_ban['get_third_internal_type']==1){ ?>				
						// Hide Third Party Vast Tag 
						$("label[for=vast_wrapper_url]").parent().parent().prev().hide();
						$("label[for=vast_wrapper_url]").parent().parent().hide(); 
						
						// Hide Vast Version 
						$("label[for=vast_version]").parent().parent().prev().hide();
						$("label[for=vast_version]").parent().parent().hide();
						
						// Hide FallBack No Ad(Wrapper)
						$("label[for=vast_wrapper_fallbacknoads]").parent().parent().prev().hide();
						$("label[for=vast_wrapper_fallbacknoads]").parent().parent().hide();
						
						// Hide Allow Multiple Ads
						$("label[for=vast_wrapper_allowmultipleads]").parent().parent().prev().hide();
						$("label[for=vast_wrapper_allowmultipleads]").parent().parent().hide(); 
						
						// Hide FallBack No Ad(Wrapper) Additional
						$("label[for=vast_wrapper_followadditional]").parent().parent().prev().hide();
						$("label[for=vast_wrapper_followadditional]").parent().parent().hide();	
						<?php if($row_ban['vast_video_delivery']=='streaming'){ ?>
							
							// Hide Add New Video URL, Video type,Video bitrate
							$("#add_url_bitrate_type").hide();        
							
							// Show RTMP server URL
							$("label[for=vast_net_connection_url]").parent().parent().prev().show();
							$("label[for=vast_net_connection_url]").parent().parent().show();
							
							// Show Video URL/Filename
							$("label[for=vast_video_filename]").parent().parent().prev().show();
							$("label[for=vast_video_filename]").parent().parent().show(); 						
															
							// Hide Video URL/Filename 1
							$("label[for=vast_video_outgoing_filename1]").parent().parent().prev().hide();
							$("label[for=vast_video_outgoing_filename1]").parent().parent().hide(); 						
															
							// Hide Video URL/Filename 2
							$("label[for=vast_video_outgoing_filename2]").parent().parent().prev().hide();
							$("label[for=vast_video_outgoing_filename2]").parent().parent().hide(); 
																	
							// Show Video type
							$("label[for=vast_video_type]").parent().parent().prev().show();
							$("label[for=vast_video_type]").parent().parent().show(); 						
															
							// Hide Video type 1
							$("label[for=vast_video_type_1]").parent().parent().prev().hide();
							$("label[for=vast_video_type_1]").parent().parent().hide(); 						
															
							// Hide Video type 2
							$("label[for=vast_video_type_2]").parent().parent().prev().hide();
							$("label[for=vast_video_type_2]").parent().parent().hide(); 
																	
							// Show Video duration in seconds 
							$("label[for=vast_video_duration]").parent().parent().prev().show();
							$("label[for=vast_video_duration]").parent().parent().show();				
															
							// Show Destination URL (incl. http://) when user clicks on the video
							$("label[for=vast_video_clickthrough_url]").parent().parent().prev().show();
							$("label[for=vast_video_clickthrough_url]").parent().parent().show(); 
							
							// Hide Mezzanine Format 
							$("label[for=mezzanine_disabled]").parent().parent().prev().hide();
							$("label[for=mezzanine_disabled]").parent().parent().parent().hide();		
							
							// Hide Designate your creative file as the mezzanine file 
							$("label[for=mezzanine_external]").parent().parent().prev().hide();
							$("label[for=mezzanine_external]").parent().parent().hide();
							
							// Hide Ad Manager-hosted File					
							$("input[name=internal_file]").parent().parent().prev().hide();	
							$("input[name=internal_file]").parent().parent().hide();
							
							// Hide Externally-hosted URL 
							$("#url_file").parent().parent().prev().hide();	
							$("#url_file").parent().parent().hide();		
							
							// Hide Interaction
							$("label[for=interactive_mediafile]").parent().parent().prev().hide();
							$("label[for=interactive_mediafile]").parent().parent().hide();
										
							// Hide Video bitrate
							$("label[for=vast_video_bitrate]").parent().parent().prev().hide();
							$("label[for=vast_video_bitrate]").parent().parent().hide(); 											        
										
							// Hide Video bitrate 1
							$("label[for=vast_video_bitrate_1]").parent().parent().prev().hide();
							$("label[for=vast_video_bitrate_1]").parent().parent().hide(); 			
												
							// Hide Video bitrate 2
							$("label[for=vast_video_bitrate_2]").parent().parent().prev().hide();
							$("label[for=vast_video_bitrate_2]").parent().parent().hide(); 

							$("label[for=vast_video_filename]").html('Video filename<font color="red">*</font>');
							<?php } else {?>				
							// Hide RTMP server URL
							$("label[for=vast_net_connection_url]").parent().parent().prev().hide();
							$("label[for=vast_net_connection_url]").parent().parent().hide(); 
							
							// Show Video URL/Filename
							$("label[for=vast_video_filename]").parent().parent().prev().show();
							$("label[for=vast_video_filename]").parent().parent().show();
							
							// Show Video type
							$("label[for=vast_video_type]").parent().parent().prev().show();
							$("label[for=vast_video_type]").parent().parent().show();
							
							// Show Video duration in seconds 
							$("label[for=vast_video_duration]").parent().parent().prev().show();
							$("label[for=vast_video_duration]").parent().parent().show();				
															
							// Show Destination URL (incl. http://) when user clicks on the video
							$("label[for=vast_video_clickthrough_url]").parent().parent().prev().show();
							$("label[for=vast_video_clickthrough_url]").parent().parent().show(); 
										
							// Show Video bitrate
							$("label[for=vast_video_bitrate]").parent().parent().prev().show();
							$("label[for=vast_video_bitrate]").parent().parent().show();		
			
							// Hide Mezzanine Format 
							$("label[for=mezzanine_disabled]").parent().parent().prev().hide();
							$("label[for=mezzanine_disabled]").parent().parent().parent().hide();
							
							// Hide Interaction
							$("label[for=interactive_mediafile]").parent().parent().prev().hide();
							$("label[for=interactive_mediafile]").parent().parent().hide();	
							
							// Hide Add New Video URL, Video type,Video bitrate
							$("#add_url_bitrate_type").hide(); 														
							
					<?php } }  else{?>									
						// Hide Video delivery method 
						$("label[for=video-url-format-progressive]").parent().parent().prev().hide();
						$("label[for=video-url-format-progressive]").parent().parent().hide();
						
						// Hide RTMP server URL
						$("label[for=vast_net_connection_url]").parent().parent().prev().hide();
						$("label[for=vast_net_connection_url]").parent().parent().hide(); 
							
						// Hide Video URL/Filename
						$("label[for=vast_video_filename]").parent().parent().prev().hide();
						$("label[for=vast_video_filename]").parent().parent().hide(); 						
																	
						// Hide Video type
						$("label[for=vast_video_type]").parent().parent().prev().hide();
						$("label[for=vast_video_type]").parent().parent().hide(); 						
																	
						// Hide Video duration in seconds 
						$("label[for=vast_video_duration]").parent().parent().prev().hide();
						$("label[for=vast_video_duration]").parent().parent().hide();				
										
						// Hide Video bitrate
						$("label[for=vast_video_bitrate]").parent().parent().prev().hide();
						$("label[for=vast_video_bitrate]").parent().parent().hide();
																
						// Show Third Party Vast Tag 
						$("label[for=vast_wrapper_url]").parent().parent().prev().show();
						$("label[for=vast_wrapper_url]").parent().parent().show(); 
						
						// Hide Vast Version 
						$("label[for=vast_version]").parent().parent().prev().hide();
						$("label[for=vast_version]").parent().parent().hide();
						
						// Hide FallBack No Ad(Wrapper)
						$("label[for=vast_wrapper_fallbacknoads]").parent().parent().prev().hide();
						$("label[for=vast_wrapper_fallbacknoads]").parent().parent().hide();
						
						// Hide Allow Multiple Ads
						$("label[for=vast_wrapper_allowmultipleads]").parent().parent().prev().hide();
						$("label[for=vast_wrapper_allowmultipleads]").parent().parent().hide(); 
						
						// Hide FallBack No Ad(Wrapper) Additional
						$("label[for=vast_wrapper_followadditional]").parent().parent().prev().hide();
						$("label[for=vast_wrapper_followadditional]").parent().parent().hide();							
					<?php } ?>
			
					/* 
						* Hide these on page load "OVERLAY VIDEO"
					*/
						
					// Hide Third party Click tracking
					$("label[for=vast_thirdparty_clickcustom]").parent().parent().parent().prev().hide();
					$("label[for=vast_thirdparty_clickcustom]").parent().parent().parent().hide();	
									
					// Hide Overlay Advanced Settings
					$("label[for=vast_overlay_expandedminduration]").parent().parent().parent().prev().hide();
					$("label[for=vast_overlay_expandedminduration]").parent().parent().parent().hide();
										
					// Hide Alt Text(Vast4)
					$("label[for=vast_thirdparty_companion_alttext]").parent().parent().prev().hide();
					$("label[for=vast_thirdparty_companion_alttext]").parent().parent().hide(); 
									
					// Hide pxratio(Vast4)
					$("label[for=vast_thirdparty_companion_pxratio]").parent().parent().prev().hide();
					$("label[for=vast_thirdparty_companion_pxratio]").parent().parent().hide();
									
					// Hide Asset Height(Vast4)
					$("label[for=vast_thirdparty_companion_assetheight]").parent().parent().prev().hide();
					$("label[for=vast_thirdparty_companion_assetheight]").parent().parent().hide();
									
					// Hide Asset Width(Vast4)
					$("label[for=vast_thirdparty_companion_assetwidth]").parent().parent().prev().hide();
					$("label[for=vast_thirdparty_companion_assetwidth]").parent().parent().hide();
														
				});		
			}	
			function vasttype2(){
				$(document).ready(function(){					
					// Hide Ad type 
					$("#ad_type").parent().parent().prev().hide();
					$("#ad_type").parent().parent().hide();		
											
					// Hide VAST 4.1 Closed Caption
					$("#vast_4_1_type_file").parent().parent().parent().hide();
								
					// Show VAST 3.0 Special Features
					$("#vast_video_skip_progress_duration").parent().parent().parent().show();	
					
					// Hide VAST 4.0 Special Features
					$("#vast4_category_name").parent().parent().parent().parent().hide();	
					
					// Show Icon information
					$("#icon_track_url").parent().parent().parent().show();	
					
					// Hide Mezzanine Format 
					$("label[for=mezzanine_disabled]").parent().parent().prev().hide();
					$("label[for=mezzanine_disabled]").parent().parent().parent().hide();
					
					// Hide Externally-hosted URL 
					$("#url_file").parent().parent().prev().hide();	
					$("#url_file").parent().parent().hide();
				
					// Hide Ad Manager-hosted File					
					$("input[name=internal_file]").parent().parent().prev().hide();	
					$("input[name=internal_file]").parent().parent().hide();
																			
					// Hide Designate your creative file as the mezzanine file					
					$("label[for=mezzanine_external]").parent().parent().prev().hide();
					$("label[for=mezzanine_external]").parent().parent().hide();
					
					// Show Video URL/Filename
					$("label[for=vast_video_filename]").parent().parent().prev().show();
					$("label[for=vast_video_filename]").parent().parent().show(); 						
									
					// Hide Video type 1
					$("label[for=vast_video_type_1]").parent().parent().prev().hide();
					$("label[for=vast_video_type_1]").parent().parent().hide(); 						
												
					// Hide Video type 2
					$("label[for=vast_video_type_2]").parent().parent().prev().hide();
					$("label[for=vast_video_type_2]").parent().parent().hide(); 
										
					// Hide Video bitrate 1
					$("label[for=vast_video_bitrate_1]").parent().parent().prev().hide();
					$("label[for=vast_video_bitrate_1]").parent().parent().hide();
							
					// Hide Video bitrate 2
					$("label[for=vast_video_bitrate_2]").parent().parent().prev().hide();
					$("label[for=vast_video_bitrate_2]").parent().parent().hide();					
					
					// Hide Add New Video URL, Video type,Video bitrate
					$("#add_url_bitrate_type").hide();
					
					// Hide Interaction
					$("label[for=interactive_mediafile]").parent().parent().prev().hide();
					$("label[for=interactive_mediafile]").parent().parent().hide();			
						
					<?php if($row_ban['get_third_internal_type']==1){ ?>				
						// Hide Third Party Vast Tag 
						$("label[for=vast_wrapper_url]").parent().parent().prev().hide();
						$("label[for=vast_wrapper_url]").parent().parent().hide(); 
						
						// Hide Vast Version 
						$("label[for=vast_version]").parent().parent().prev().hide();
						$("label[for=vast_version]").parent().parent().hide();
						
						// Hide FallBack No Ad(Wrapper)
						$("label[for=vast_wrapper_fallbacknoads]").parent().parent().prev().hide();
						$("label[for=vast_wrapper_fallbacknoads]").parent().parent().hide();
						
						// Hide Allow Multiple Ads
						$("label[for=vast_wrapper_allowmultipleads]").parent().parent().prev().hide();
						$("label[for=vast_wrapper_allowmultipleads]").parent().parent().hide(); 
						
						// Hide FallBack No Ad(Wrapper) Additional
						$("label[for=vast_wrapper_followadditional]").parent().parent().prev().hide();
						$("label[for=vast_wrapper_followadditional]").parent().parent().hide();	
						<?php if($row_ban['vast_video_delivery']=='streaming'){ ?>
							
							// Hide Add New Video URL, Video type,Video bitrate
							$("#add_url_bitrate_type").hide();        
							
							// Show RTMP server URL
							$("label[for=vast_net_connection_url]").parent().parent().prev().show();
							$("label[for=vast_net_connection_url]").parent().parent().show();
							
							// Show Video URL/Filename
							$("label[for=vast_video_filename]").parent().parent().prev().show();
							$("label[for=vast_video_filename]").parent().parent().show(); 						
															
							// Hide Video URL/Filename 1
							$("label[for=vast_video_outgoing_filename1]").parent().parent().prev().hide();
							$("label[for=vast_video_outgoing_filename1]").parent().parent().hide(); 						
															
							// Hide Video URL/Filename 2
							$("label[for=vast_video_outgoing_filename2]").parent().parent().prev().hide();
							$("label[for=vast_video_outgoing_filename2]").parent().parent().hide(); 
																	
							// Show Video type
							$("label[for=vast_video_type]").parent().parent().prev().show();
							$("label[for=vast_video_type]").parent().parent().show(); 						
															
							// Hide Video type 1
							$("label[for=vast_video_type_1]").parent().parent().prev().hide();
							$("label[for=vast_video_type_1]").parent().parent().hide(); 						
															
							// Hide Video type 2
							$("label[for=vast_video_type_2]").parent().parent().prev().hide();
							$("label[for=vast_video_type_2]").parent().parent().hide(); 
																	
							// Show Video duration in seconds 
							$("label[for=vast_video_duration]").parent().parent().prev().show();
							$("label[for=vast_video_duration]").parent().parent().show();				
															
							// Show Destination URL (incl. http://) when user clicks on the video
							$("label[for=vast_video_clickthrough_url]").parent().parent().prev().show();
							$("label[for=vast_video_clickthrough_url]").parent().parent().show(); 
							
							// Hide Mezzanine Format 
							$("label[for=mezzanine_disabled]").parent().parent().prev().hide();
							$("label[for=mezzanine_disabled]").parent().parent().parent().hide();		
							
							// Hide Designate your creative file as the mezzanine file 
							$("label[for=mezzanine_external]").parent().parent().prev().hide();
							$("label[for=mezzanine_external]").parent().parent().hide();
							
							// Hide Ad Manager-hosted File					
							$("input[name=internal_file]").parent().parent().prev().hide();	
							$("input[name=internal_file]").parent().parent().hide();
							
							// Hide Externally-hosted URL 
							$("#url_file").parent().parent().prev().hide();	
							$("#url_file").parent().parent().hide();		
							
							// Hide Interaction
							$("label[for=interactive_mediafile]").parent().parent().prev().hide();
							$("label[for=interactive_mediafile]").parent().parent().hide();
										
							// Hide Video bitrate
							$("label[for=vast_video_bitrate]").parent().parent().prev().hide();
							$("label[for=vast_video_bitrate]").parent().parent().hide();
									
							// Hide Video bitrate 1
							$("label[for=vast_video_bitrate_1]").parent().parent().prev().hide();
							$("label[for=vast_video_bitrate_1]").parent().parent().hide(); 			
												
							// Hide Video bitrate 2
							$("label[for=vast_video_bitrate_2]").parent().parent().prev().hide();
							$("label[for=vast_video_bitrate_2]").parent().parent().hide(); 

							$("label[for=vast_video_filename]").html('Video filename<font color="red">*</font>');
							<?php } else {?>				
							// Hide RTMP server URL
							$("label[for=vast_net_connection_url]").parent().parent().prev().hide();
							$("label[for=vast_net_connection_url]").parent().parent().hide(); 
							
							// Show Video URL/Filename
							$("label[for=vast_video_filename]").parent().parent().prev().show();
							$("label[for=vast_video_filename]").parent().parent().show(); 						
																	
							// Show Video type
							$("label[for=vast_video_type]").parent().parent().prev().show();
							$("label[for=vast_video_type]").parent().parent().show(); 						
																	
							// Show Video duration in seconds 
							$("label[for=vast_video_duration]").parent().parent().prev().show();
							$("label[for=vast_video_duration]").parent().parent().show();				
															
							// Show Destination URL (incl. http://) when user clicks on the video
							$("label[for=vast_video_clickthrough_url]").parent().parent().prev().show();
							$("label[for=vast_video_clickthrough_url]").parent().parent().show(); 
										
							// Show Video bitrate
							$("label[for=vast_video_bitrate]").parent().parent().prev().show();
							$("label[for=vast_video_bitrate]").parent().parent().show();		
			
							// Hide Mezzanine Format 
							$("label[for=mezzanine_disabled]").parent().parent().prev().hide();
							$("label[for=mezzanine_disabled]").parent().parent().parent().hide();
							
							// Hide Interaction
							$("label[for=interactive_mediafile]").parent().parent().prev().hide();
							$("label[for=interactive_mediafile]").parent().parent().hide();	
							
							// Hide Add New Video URL, Video type,Video bitrate
							$("#add_url_bitrate_type").hide(); 														
							
					<?php } }  else{?>
						// Hide Video delivery method 
						$("label[for=video-url-format-progressive]").parent().parent().prev().hide();
						$("label[for=video-url-format-progressive]").parent().parent().hide();
						
						// Hide RTMP server URL
						$("label[for=vast_net_connection_url]").parent().parent().prev().hide();
						$("label[for=vast_net_connection_url]").parent().parent().hide(); 
							
						// Hide Video URL/Filename
						$("label[for=vast_video_filename]").parent().parent().prev().hide();
						$("label[for=vast_video_filename]").parent().parent().hide(); 						
																	
						// Hide Video type
						$("label[for=vast_video_type]").parent().parent().prev().hide();
						$("label[for=vast_video_type]").parent().parent().hide(); 						
																	
						// Hide Video duration in seconds 
						$("label[for=vast_video_duration]").parent().parent().prev().hide();
						$("label[for=vast_video_duration]").parent().parent().hide();				
										
						// Hide Video bitrate
						$("label[for=vast_video_bitrate]").parent().parent().prev().hide();
						$("label[for=vast_video_bitrate]").parent().parent().hide();
																
						// Show Third Party Vast Tag 
						$("label[for=vast_wrapper_url]").parent().parent().prev().show();
						$("label[for=vast_wrapper_url]").parent().parent().show(); 

						// Hide Vast Version 
						$("label[for=vast_version]").parent().parent().prev().hide();
						$("label[for=vast_version]").parent().parent().hide();
						
						// Hide FallBack No Ad(Wrapper)
						$("label[for=vast_wrapper_fallbacknoads]").parent().parent().prev().hide();
						$("label[for=vast_wrapper_fallbacknoads]").parent().parent().hide();
						
						// Hide Allow Multiple Ads
						$("label[for=vast_wrapper_allowmultipleads]").parent().parent().prev().hide();
						$("label[for=vast_wrapper_allowmultipleads]").parent().parent().hide(); 
						
						// Hide FallBack No Ad(Wrapper) Additional
						$("label[for=vast_wrapper_followadditional]").parent().parent().prev().hide();
						$("label[for=vast_wrapper_followadditional]").parent().parent().hide();	
					<?php } ?>
					/* 
						* Show these on page load "OVERLAY VIDEO"
					*/
						
					// Show Third party Click tracking
					$("label[for=vast_thirdparty_clickcustom]").parent().parent().parent().prev().show();
					$("label[for=vast_thirdparty_clickcustom]").parent().parent().parent().show();	
									
					// Show Overlay Advanced Settings
					$("label[for=vast_overlay_expandedminduration]").parent().parent().parent().prev().show();
					$("label[for=vast_overlay_expandedminduration]").parent().parent().parent().show();	
								
					// Hide Alt Text(Vast4)
					$("label[for=vast_thirdparty_companion_alttext]").parent().parent().prev().hide();
					$("label[for=vast_thirdparty_companion_alttext]").parent().parent().hide(); 
									
					// Hide pxratio(Vast4)
					$("label[for=vast_thirdparty_companion_pxratio]").parent().parent().prev().hide();
					$("label[for=vast_thirdparty_companion_pxratio]").parent().parent().hide();
									
					// Hide Asset Height(Vast4)
					$("label[for=vast_thirdparty_companion_assetheight]").parent().parent().prev().hide();
					$("label[for=vast_thirdparty_companion_assetheight]").parent().parent().hide();
									
					// Hide Asset Width(Vast4)
					$("label[for=vast_thirdparty_companion_assetwidth]").parent().parent().prev().hide();
					$("label[for=vast_thirdparty_companion_assetwidth]").parent().parent().hide();										
				});		
			}	
			function vasttype3(){
				$(document).ready(function(){				
					// Check radio_internal_file_keep as Default
					$("#radio_internal_file_keep").attr("checked", true);	
															
					// Show Mezzanine Format 
					$("label[for=mezzanine_disabled]").parent().parent().prev().show();
					$("label[for=mezzanine_disabled]").parent().parent().parent().show();	
					
					// Show Icon information
					$("#icon_track_url").parent().parent().parent().show();	
									
					// Hide VAST 4.1 Closed Caption
					$("#vast_4_1_type_file").parent().parent().parent().hide();
								
					// Show VAST 4.0 Special Features
					$("#vast4_category_name").parent().parent().parent().parent().show();
					
					// Show VAST 3.0 Special Features
					$("#vast_video_skip_progress_duration").parent().parent().parent().show();	
					
					// Hide Ad type 
					$("#ad_type").parent().parent().prev().hide();
					$("#ad_type").parent().parent().hide();
									
					<?php if($row_ban['get_third_internal_type']==1){ ?>				
						// Hide Third Party Vast Tag 
						$("label[for=vast_wrapper_url]").parent().parent().prev().hide();
						$("label[for=vast_wrapper_url]").parent().parent().hide(); 
						
						// Hide Vast Version 
						$("label[for=vast_version]").parent().parent().prev().hide();
						$("label[for=vast_version]").parent().parent().hide();
						
						// Hide FallBack No Ad(Wrapper)
						$("label[for=vast_wrapper_fallbacknoads]").parent().parent().prev().hide();
						$("label[for=vast_wrapper_fallbacknoads]").parent().parent().hide();
						
						// Hide Allow Multiple Ads
						$("label[for=vast_wrapper_allowmultipleads]").parent().parent().prev().hide();
						$("label[for=vast_wrapper_allowmultipleads]").parent().parent().hide(); 
						
						// Hide FallBack No Ad(Wrapper) Additional
						$("label[for=vast_wrapper_followadditional]").parent().parent().prev().hide();
						$("label[for=vast_wrapper_followadditional]").parent().parent().hide();	
						<?php if($row_ban['vast_video_delivery']=='streaming'){ ?>
							
							// Hide Add New Video URL, Video type,Video bitrate
							$("#add_url_bitrate_type").hide();        
							
							// Show RTMP server URL
							$("label[for=vast_net_connection_url]").parent().parent().prev().show();
							$("label[for=vast_net_connection_url]").parent().parent().show();
							
							// Show Video URL/Filename
							$("label[for=vast_video_filename]").parent().parent().prev().show();
							$("label[for=vast_video_filename]").parent().parent().show(); 						
															
							// Hide Video URL/Filename 1
							$("label[for=vast_video_outgoing_filename1]").parent().parent().prev().hide();
							$("label[for=vast_video_outgoing_filename1]").parent().parent().hide(); 						
															
							// Hide Video URL/Filename 2
							$("label[for=vast_video_outgoing_filename2]").parent().parent().prev().hide();
							$("label[for=vast_video_outgoing_filename2]").parent().parent().hide(); 
																	
							// Show Video type
							$("label[for=vast_video_type]").parent().parent().prev().show();
							$("label[for=vast_video_type]").parent().parent().show(); 						
															
							// Hide Video type 1
							$("label[for=vast_video_type_1]").parent().parent().prev().hide();
							$("label[for=vast_video_type_1]").parent().parent().hide(); 						
															
							// Hide Video type 2
							$("label[for=vast_video_type_2]").parent().parent().prev().hide();
							$("label[for=vast_video_type_2]").parent().parent().hide(); 
																	
							// Show Video duration in seconds 
							$("label[for=vast_video_duration]").parent().parent().prev().show();
							$("label[for=vast_video_duration]").parent().parent().show();				
															
							// Show Destination URL (incl. http://) when user clicks on the video
							$("label[for=vast_video_clickthrough_url]").parent().parent().prev().show();
							$("label[for=vast_video_clickthrough_url]").parent().parent().show(); 
										
							// Show Interaction
							$("label[for=interactive_mediafile]").parent().parent().prev().show();
							$("label[for=interactive_mediafile]").parent().parent().show();
										
							// Hide Video bitrate
							$("label[for=vast_video_bitrate]").parent().parent().prev().hide();
							$("label[for=vast_video_bitrate]").parent().parent().hide();
							
							// Hide Video bitrate 1
							$("label[for=vast_video_bitrate_1]").parent().parent().prev().hide();
							$("label[for=vast_video_bitrate_1]").parent().parent().hide(); 			
												
							// Hide Video bitrate 2
							$("label[for=vast_video_bitrate_2]").parent().parent().prev().hide();
							$("label[for=vast_video_bitrate_2]").parent().parent().hide(); 

							$("label[for=vast_video_filename]").html('Video filename<font color="red">*</font>');
							<?php } else {?>				
							// Hide RTMP server URL
							$("label[for=vast_net_connection_url]").parent().parent().prev().hide();
							$("label[for=vast_net_connection_url]").parent().parent().hide(); 
							
							// Show Video URL/Filename
							$("label[for=vast_video_filename]").parent().parent().prev().show();
							$("label[for=vast_video_filename]").parent().parent().show(); 						
															
							// Show Video URL/Filename 1
							$("label[for=vast_video_outgoing_filename1]").parent().parent().prev().show();
							$("label[for=vast_video_outgoing_filename1]").parent().parent().show(); 						
															
							// Show Video URL/Filename 2
							$("label[for=vast_video_outgoing_filename2]").parent().parent().prev().show();
							$("label[for=vast_video_outgoing_filename2]").parent().parent().show(); 																
							// Show Video type
							$("label[for=vast_video_type]").parent().parent().prev().show();
							$("label[for=vast_video_type]").parent().parent().show(); 						
															
							// Show Video type 1
							$("label[for=vast_video_type_1]").parent().parent().prev().show();
							$("label[for=vast_video_type_1]").parent().parent().show(); 						
															
							// Show Video type 2
							$("label[for=vast_video_type_2]").parent().parent().prev().show();
							$("label[for=vast_video_type_2]").parent().parent().show();	
																						
							// Show Video duration in seconds 
							$("label[for=vast_video_duration]").parent().parent().prev().show();
							$("label[for=vast_video_duration]").parent().parent().show();				
															
							// Show Destination URL (incl. http://) when user clicks on the video
							$("label[for=vast_video_clickthrough_url]").parent().parent().prev().show();
							$("label[for=vast_video_clickthrough_url]").parent().parent().show(); 
										
							// Show Video bitrate
							$("label[for=vast_video_bitrate]").parent().parent().prev().show();
							$("label[for=vast_video_bitrate]").parent().parent().show();
									
							// Show Video bitrate 1
							$("label[for=vast_video_bitrate_1]").parent().parent().prev().show();
							$("label[for=vast_video_bitrate_1]").parent().parent().show(); 			
												
							// Hide Video bitrate 2
							$("label[for=vast_video_bitrate_2]").parent().parent().prev().show();
							$("label[for=vast_video_bitrate_2]").parent().parent().show();	
							
							// Show Interaction
							$("label[for=interactive_mediafile]").parent().parent().prev().show();
							$("label[for=interactive_mediafile]").parent().parent().show();	
							
							// Show Add New Video URL, Video type,Video bitrate
							$("#add_url_bitrate_type").show(); 														
							
					<?php } }  else{?>
						// Hide Video delivery method 
						$("label[for=video-url-format-progressive]").parent().parent().prev().hide();
						$("label[for=video-url-format-progressive]").parent().parent().hide();
						
						// Hide Interactive Media file url(VPAID)
						$("label[for=interactive_mediafile]").parent().parent().prev().hide();
						$("label[for=interactive_mediafile]").parent().parent().hide();
						
						// Hide RTMP server URL
						$("label[for=vast_net_connection_url]").parent().parent().prev().hide();
						$("label[for=vast_net_connection_url]").parent().parent().hide(); 
							
						// Hide Video URL/Filename
						$("label[for=vast_video_filename]").parent().parent().prev().hide();
						$("label[for=vast_video_filename]").parent().parent().hide(); 						
																	
						// Hide Video type
						$("label[for=vast_video_type]").parent().parent().prev().hide();
						$("label[for=vast_video_type]").parent().parent().hide(); 						
																	
						// Hide Video duration in seconds 
						$("label[for=vast_video_duration]").parent().parent().prev().hide();
						$("label[for=vast_video_duration]").parent().parent().hide();				
										
						// Hide Video bitrate
						$("label[for=vast_video_bitrate]").parent().parent().prev().hide();
						$("label[for=vast_video_bitrate]").parent().parent().hide();
						
						// Hide VAST 4.0 Special Features
						$("#vast4_category_name").parent().parent().parent().parent().hide();					
																
						// Show Third Party Vast Tag 
						$("label[for=vast_wrapper_url]").parent().parent().prev().show();
						$("label[for=vast_wrapper_url]").parent().parent().show(); 
						
						// Hide Vast Version 
						$("label[for=vast_version]").parent().parent().prev().hide();
						$("label[for=vast_version]").parent().parent().hide();
						
						// Show FallBack No Ad(Wrapper)
						$("label[for=vast_wrapper_fallbacknoads]").parent().parent().prev().show();
						$("label[for=vast_wrapper_fallbacknoads]").parent().parent().show();
						
						// Show Allow Multiple Ads
						$("label[for=vast_wrapper_allowmultipleads]").parent().parent().prev().show();
						$("label[for=vast_wrapper_allowmultipleads]").parent().parent().show(); 
						
						// Show FallBack No Ad(Wrapper) Additional
						$("label[for=vast_wrapper_followadditional]").parent().parent().prev().show();
						$("label[for=vast_wrapper_followadditional]").parent().parent().show();	

					<?php } if($row_ban['is_mezzininefile']==1){?>				
						// Show Designate your creative file as the mezzanine file 
						$("label[for=mezzanine_external]").parent().parent().prev().show();
						$("label[for=mezzanine_external]").parent().parent().show();			
						
						// Hide RTMP server URL
						$("label[for=vast_net_connection_url]").parent().parent().prev().hide();
						$("label[for=vast_net_connection_url]").parent().parent().hide(); 												
														
						// Hide Video duration in seconds 
						$("label[for=vast_video_duration]").parent().parent().prev().hide();
						$("label[for=vast_video_duration]").parent().parent().hide();				
														
						// Hide Destination URL (incl. http://) when user clicks on the video
						$("label[for=vast_video_clickthrough_url]").parent().parent().prev().hide();
						$("label[for=vast_video_clickthrough_url]").parent().parent().hide(); 				
						<?php  if($row_ban['mezzanine_en']==1){?>	
							// Hide Externally-hosted URL 
							$("#url_file").parent().parent().prev().hide();	
							$("#url_file").parent().parent().hide();	
							
							// Hide Ad Manager-hosted File					
							$("input[name=internal_file]").parent().parent().prev().hide();	
							$("input[name=internal_file]").parent().parent().hide();
													
							// Show Do you wish to keep your existing video? Or do you want to upload another?				
							$("label[for=radio_internal_file_keep]").parent().parent().prev().show();	
							$("label[for=radio_internal_file_keep]").parent().parent().show();
							
							$("#radio_internal_file_change").click(function(){
								// Show Ad Manager-hosted File				
								$("input[name=internal_file]").parent().parent().prev().show();	
								$("input[name=internal_file]").parent().parent().show();							
							});		
												
							$("#radio_internal_file_keep").click(function(){
								// Hide Ad Manager-hosted File				
								$("input[name=internal_file]").parent().parent().prev().hide();	
								$("input[name=internal_file]").parent().parent().hide();
															
							});							
						<?php } else{?>	
							// Hide Do you wish to keep your existing video? Or do you want to upload another?				
							$("label[for=radio_internal_file_keep]").parent().parent().prev().hide();	
							$("label[for=radio_internal_file_keep]").parent().parent().hide();											
							// Show Externally-hosted URL 
							$("#url_file").parent().parent().prev().show();	
							$("#url_file").parent().parent().show();
							
							// Hide Ad Manager-hosted File					
							$("input[name=internal_file]").parent().parent().prev().hide();	
							$("input[name=internal_file]").parent().parent().hide();				
						<?php }} else{?>
							// Check Disable as Default
							$("#mezzanine_disabled").attr("checked", true);
															
							// Show Mezzanine Format 
							$("label[for=mezzanine_disabled]").parent().parent().prev().show();
							$("label[for=mezzanine_disabled]").parent().parent().parent().show();
							
							// Hide Do you wish to keep your existing video? Or do you want to upload another? 
							$("label[for=radio_internal_file_keep]").parent().parent().prev().hide();
							$("label[for=radio_internal_file_keep]").parent().parent().hide();
							
							// Hide Designate your creative file as the mezzanine file 
							$("label[for=mezzanine_external]").parent().parent().prev().hide();
							$("label[for=mezzanine_external]").parent().parent().hide();
							
							// Hide Externally-hosted URL 
							$("#url_file").parent().parent().prev().hide();	
							$("#url_file").parent().parent().hide(); 

							// Hide Ad Manager-hosted File					
							$("input[name=internal_file]").parent().parent().prev().hide();	
							$("input[name=internal_file]").parent().parent().hide();
									
						<?php if($row_ban['get_third_internal_type'] != 2){?>						
							// Hide RTMP server URL
							$("label[for=vast_net_connection_url]").parent().parent().prev().hide();
							$("label[for=vast_net_connection_url]").parent().parent().hide(); 
							
							// Show Video URL/Filename
							$("label[for=vast_video_filename]").parent().parent().prev().show();
							$("label[for=vast_video_filename]").parent().parent().show(); 						
							
							// Show Video URL/Filename 1
							$("label[for=vast_video_outgoing_filename1]").parent().parent().prev().show();
							$("label[for=vast_video_outgoing_filename1]").parent().parent().show(); 						
							
							// Show Video URL/Filename 2
							$("label[for=vast_video_outgoing_filename2]").parent().parent().prev().show();
							$("label[for=vast_video_outgoing_filename2]").parent().parent().show(); 
																	
							// Show Video type
							$("label[for=vast_video_type]").parent().parent().prev().show();
							$("label[for=vast_video_type]").parent().parent().show(); 						
															
							// Show Video type 1
							$("label[for=vast_video_type_1]").parent().parent().prev().show();
							$("label[for=vast_video_type_1]").parent().parent().show(); 						
															
							// Show Video type 2
							$("label[for=vast_video_type_2]").parent().parent().prev().show();
							$("label[for=vast_video_type_2]").parent().parent().show(); 
																	
							// Show Video duration in seconds 
							$("label[for=vast_video_duration]").parent().parent().prev().show();
							$("label[for=vast_video_duration]").parent().parent().show();				
															
							// Show Destination URL (incl. http://) when user clicks on the video
							$("label[for=vast_video_clickthrough_url]").parent().parent().prev().show();
							$("label[for=vast_video_clickthrough_url]").parent().parent().show();
										
							// Show Video bitrate
							$("label[for=vast_video_bitrate]").parent().parent().prev().show();
							$("label[for=vast_video_bitrate]").parent().parent().show();		
										
							// Show Video bitrate 1
							$("label[for=vast_video_bitrate_1]").parent().parent().prev().show();
							$("label[for=vast_video_bitrate_1]").parent().parent().show(); 			
										
							// Show Video bitrate 2
							$("label[for=vast_video_bitrate_2]").parent().parent().prev().show();
							$("label[for=vast_video_bitrate_2]").parent().parent().show();			
																					
						<?php }} if($row_ban['vast_video_outgoing_filename1'] != ""){ $options=$row_ban['vast_video_type_1'];?>
						// Adding new input box				
						$("#vast_video_type").after('&nbsp&nbsp&nbsp&nbsp<button type="button" id="add_url_bitrate_type">Add New Media URL, Media type,Media bitrate</button>');
						var count = 1;
						var count_plus = count +1;					
						var add_button = $("#add_url_bitrate_type");				
						$(add_button).parent().parent().after('<tr class="spacer"><td style="height:1px;" colspan="3">&nbsp;</td></tr><tr><td width="30">&nbsp;</td><td width="170"><label for="vast_video_outgoing_filename'+count+'">Media URL '+count_plus+'<font color="red">*</font></label></td><td width="100%"><input name="vast_video_outgoing_filename'+count+'" type="text" id="vast_video_outgoing_filename'+count+'" value="<?= $row_ban['vast_video_outgoing_filename1'] ?>" class="large"></td></tr><tr class="spacer"><td style="height:1px;" colspan="3">&nbsp;</td></tr><tr><td width="30">&nbsp;</td><td width="170"><label for="vast_video_bitrate_'+count+'">Media bitrate '+count_plus+'</label></td><td width="100%"><input name="vast_video_bitrate_'+count+'" type="text"  value="<?= $row_ban['vast_video_bitrate_1'] ?>"  id="vast_video_bitrate_'+count+'" class="large"></td></tr><tr class="spacer"><td style="height:1px;" colspan="3">&nbsp;</td></tr><tr><td width="30">&nbsp;</td><td width="170"><label for="vast_video_type_'+count+'">Media type '+count_plus+'<font color="red">*</font> </label></td><td width="100%"><select name="vast_video_type_'+count+'" id="vast_video_type_'+count+'" class="medium"><option value="video/mp4"<?php if($options=="video/mp4") echo 'selected="selected"'; ?>>MP4</option><option value="video/x-flv"<?php if($options=="video/x-flv") echo 'selected="selected"'; ?>>FLV</option><option value="video/webm"<?php if($options=="video/webm") echo 'selected="selected"'; ?>>WEBM</option></select>&nbsp&nbsp<a href="javascript:void(0)" class="remove_field_parent_1">X</a></td></tr>');										
						<?php } if($row_ban['vast_video_outgoing_filename2'] != ""){ $options=$row_ban['vast_video_type_2'];?>	
							// Adding new input box						
							var count = 2;
							var count_plus = count +1;
							var add_button = $("#add_url_bitrate_type");															
							$("#vast_video_type_1").parent().parent().after('<tr class="spacer"><td style="height:1px;" colspan="3">&nbsp;</td></tr><tr><td width="30">&nbsp;</td><td width="170"><label for="vast_video_outgoing_filename'+count+'">Media URL '+count_plus+'<font color="red">*</font></label></td><td width="100%"><input name="vast_video_outgoing_filename'+count+'" type="text" id="vast_video_outgoing_filename'+count+'" value="<?= $row_ban['vast_video_outgoing_filename2'] ?>" class="large"></td></tr><tr class="spacer"><td style="height:1px;" colspan="3">&nbsp;</td></tr><tr><td width="30">&nbsp;</td><td width="170"><label for="vast_video_bitrate_'+count+'">Media bitrate '+count_plus+'</label></td><td width="100%"><input name="vast_video_bitrate_'+count+'" type="text"  value="<?= $row_ban['vast_video_bitrate_2'] ?>"  id="vast_video_bitrate_'+count+'" class="large"></td></tr><tr class="spacer"><td style="height:1px;" colspan="3">&nbsp;</td></tr><tr><td width="30">&nbsp;</td><td width="170"><label for="vast_video_type_'+count+'">Media type '+count_plus+'<font color="red">*</font> </label></td><td width="100%"><select name="vast_video_type_'+count+'" id="vast_video_type_'+count+'" class="medium"><option value="video/mp4"<?php if($options=="video/mp4") echo 'selected="selected"'; ?>>MP4</option><option value="video/x-flv"<?php if($options=="video/x-flv") echo 'selected="selected"'; ?>>FLV</option><option value="video/webm"<?php if($options=="video/webm") echo 'selected="selected"'; ?>>WEBM</option></select>&nbsp&nbsp<a href="javascript:void(0)" class="remove_field_parent_2">X</a></td></tr>');																		
						<?php } if(($row_ban['vast_video_outgoing_filename1'] == "")&&($row_ban['vast_video_outgoing_filename2'] == "")){?>
						// Adding new input box		
						if($("#add_url_bitrate_type").length == 0){			
							$("#vast_video_type").after('&nbsp&nbsp&nbsp&nbsp<button type="button" id="add_url_bitrate_type">Add New Media URL, Media type,Media bitrate</button>');	
							var add_button = $("#add_url_bitrate_type");
							var count = 0;
							var count_plus = count +1;
							$(add_button).click(function (e) {
								count++;
								count_plus++;
								e.preventDefault();
								if(count < 3){
									if(count != 2){
										$(add_button).parent().parent().after('<tr class="spacer"><td style="height:1px;" colspan="3">&nbsp;</td></tr><tr><td width="30">&nbsp;</td><td width="170"><label for="vast_video_outgoing_filename'+count+'">Media URL '+count_plus+'<font color="red">*</font></label></td><td width="100%"><input name="vast_video_outgoing_filename'+count+'" type="text" id="vast_video_outgoing_filename'+count+'" class="large"></td></tr><tr class="spacer"><td style="height:1px;" colspan="3">&nbsp;</td></tr><tr><td width="30">&nbsp;</td><td width="170"><label for="vast_video_bitrate_'+count+'">Media bitrate '+count_plus+'</label></td><td width="100%"><input name="vast_video_bitrate_'+count+'" type="text" value="400" id="vast_video_bitrate_'+count+'" class="large"></td></tr><tr class="spacer"><td style="height:1px;" colspan="3">&nbsp;</td></tr><tr><td width="30">&nbsp;</td><td width="170"><label for="vast_video_type_'+count+'">Media type '+count_plus+'<font color="red">*</font> </label></td><td width="100%"><select name="vast_video_type_'+count+'" id="vast_video_type_'+count+'" class="medium"><option value="video/mp4">MP4</option><option value="video/x-flv">FLV</option><option value="video/webm">WEBM</option></select>&nbsp&nbsp<a href="javascript:void(0)" class="remove_field_parent_1">X</a></td></tr>');
										// Removing remove_field_parent	
										$(".remove_field_parent_1").click(function(){
											if($("#vast_video_type_2").length == 0){
												$(this).parent().parent().prev().prev().prev().prev().remove();
												$(this).parent().parent().prev().prev().prev().remove();
												$(this).parent().parent().prev().prev().remove();
												$(this).parent().parent().prev().remove();
												$(this).parent().parent().next().remove();
												$(this).parent().parent().remove();
												count--;count_plus--;
											}
										});											
									}
									else{
										$("#vast_video_type_1").parent().parent().after('<tr class="spacer"><td style="height:1px;" colspan="3">&nbsp;</td></tr><tr><td width="30">&nbsp;</td><td width="170"><label for="vast_video_outgoing_filename'+count+'">Media URL '+count_plus+'<font color="red">*</font></label></td><td width="100%"><input name="vast_video_outgoing_filename'+count+'" type="text" id="vast_video_outgoing_filename'+count+'" class="large"></td></tr><tr class="spacer"><td style="height:1px;" colspan="3">&nbsp;</td></tr><tr><td width="30">&nbsp;</td><td width="170"><label for="vast_video_bitrate_'+count+'">Media bitrate '+count_plus+'</label></td><td width="100%"><input name="vast_video_bitrate_'+count+'" type="text" value="400" id="vast_video_bitrate_'+count+'" class="large"></td></tr><tr class="spacer"><td style="height:1px;" colspan="3">&nbsp;</td></tr><tr><td width="30">&nbsp;</td><td width="170"><label for="vast_video_type_'+count+'">Media type '+count_plus+'<font color="red">*</font> </label></td><td width="100%"><select name="vast_video_type_'+count+'" id="vast_video_type_'+count+'" class="medium"><option value="video/mp4">MP4</option><option value="video/x-flv">FLV</option><option value="video/webm">WEBM</option></select>&nbsp&nbsp<a href="javascript:void(0)" class="remove_field_parent">X</a></td></tr>');	
										// Removing remove_field_parent	
										$(".remove_field_parent").click(function(){
											$(this).parent().parent().prev().prev().prev().prev().remove();
											$(this).parent().parent().prev().prev().prev().remove();
											$(this).parent().parent().prev().prev().remove();
											$(this).parent().parent().prev().remove();
											$(this).parent().parent().next().remove();
											$(this).parent().parent().remove();
											count--;count_plus--;
										});																	
										}
								}
								if(count == 3){count = 2;count_plus = 3;}		
							});	
						}							
					<?php } else { ?>
							$(add_button).click(function (e) {
								count++;
								count_plus++;
								e.preventDefault();
								if(count < 3){
									if(count != 2){
										$(add_button).parent().parent().after('<tr class="spacer"><td style="height:1px;" colspan="3">&nbsp;</td></tr><tr><td width="30">&nbsp;</td><td width="170"><label for="vast_video_outgoing_filename'+count+'">Media URL '+count_plus+'<font color="red">*</font></label></td><td width="100%"><input name="vast_video_outgoing_filename'+count+'" type="text" id="vast_video_outgoing_filename'+count+'" class="large"></td></tr><tr class="spacer"><td style="height:1px;" colspan="3">&nbsp;</td></tr><tr><td width="30">&nbsp;</td><td width="170"><label for="vast_video_bitrate_'+count+'">Media bitrate '+count_plus+'</label></td><td width="100%"><input name="vast_video_bitrate_'+count+'" type="text" value="400" id="vast_video_bitrate_'+count+'" class="large"></td></tr><tr class="spacer"><td style="height:1px;" colspan="3">&nbsp;</td></tr><tr><td width="30">&nbsp;</td><td width="170"><label for="vast_video_type_'+count+'">Media type '+count_plus+'<font color="red">*</font> </label></td><td width="100%"><select name="vast_video_type_'+count+'" id="vast_video_type_'+count+'" class="medium"><option value="video/mp4">MP4</option><option value="video/x-flv">FLV</option><option value="video/webm">WEBM</option></select>&nbsp&nbsp<a href="javascript:void(0)" class="remove_field_parent_1">X</a></td></tr>');											
										// Removing remove_field_parent	
										$(".remove_field_parent_1").click(function(){
											if($("#vast_video_type_2").length == 0){
												$(this).parent().parent().prev().prev().prev().prev().remove();
												$(this).parent().parent().prev().prev().prev().remove();
												$(this).parent().parent().prev().prev().remove();
												$(this).parent().parent().prev().remove();
												$(this).parent().parent().next().remove();
												$(this).parent().parent().remove();
												count--;count_plus--;
											}
										});											
									}
									else{
										$("#vast_video_type_1").parent().parent().after('<tr class="spacer"><td style="height:1px;" colspan="3">&nbsp;</td></tr><tr><td width="30">&nbsp;</td><td width="170"><label for="vast_video_outgoing_filename'+count+'">Media URL '+count_plus+'<font color="red">*</font></label></td><td width="100%"><input name="vast_video_outgoing_filename'+count+'" type="text" id="vast_video_outgoing_filename'+count+'" class="large"></td></tr><tr class="spacer"><td style="height:1px;" colspan="3">&nbsp;</td></tr><tr><td width="30">&nbsp;</td><td width="170"><label for="vast_video_bitrate_'+count+'">Media bitrate '+count_plus+'</label></td><td width="100%"><input name="vast_video_bitrate_'+count+'" type="text" value="400" id="vast_video_bitrate_'+count+'" class="large"></td></tr><tr class="spacer"><td style="height:1px;" colspan="3">&nbsp;</td></tr><tr><td width="30">&nbsp;</td><td width="170"><label for="vast_video_type_'+count+'">Media type '+count_plus+'<font color="red">*</font> </label></td><td width="100%"><select name="vast_video_type_'+count+'" id="vast_video_type_'+count+'" class="medium"><option value="video/mp4">MP4</option><option value="video/x-flv">FLV</option><option value="video/webm">WEBM</option></select>&nbsp&nbsp<a href="javascript:void(0)" class="remove_field_parent">X</a></td></tr>');											
										// Removing remove_field_parent	
										$(".remove_field_parent").click(function(){
											$(this).parent().parent().prev().prev().prev().prev().remove();
											$(this).parent().parent().prev().prev().prev().remove();
											$(this).parent().parent().prev().prev().remove();
											$(this).parent().parent().prev().remove();
											$(this).parent().parent().next().remove();
											$(this).parent().parent().remove();
											count--;count_plus--;
										});																	
									}
								}
								if(count == 3){count = 2;count_plus = 3;}		
							});						
					<?php }?>
					// Removing remove_field_parent_1
					$(".remove_field_parent_1").click(function(){
						if($("#vast_video_type_2").length == 0){
							if (!confirm("Are you sure to delete?")){
								return false;
							}
							else{					
								$(this).parent().parent().prev().prev().prev().prev().remove();
								$(this).parent().parent().prev().prev().prev().remove();
								$(this).parent().parent().prev().prev().remove();
								$(this).parent().parent().prev().remove();
								$(this).parent().parent().next().remove();
								$(this).parent().parent().remove();
								count--;count_plus--;
							}
						}
					});				
					// Removing remove_field_parent_2	
					$(".remove_field_parent_2").click(function(){
						if (!confirm("Are you sure to delete?")){
							return false;
						}
						else{						
							$(this).parent().parent().prev().prev().prev().prev().remove();
							$(this).parent().parent().prev().prev().prev().remove();
							$(this).parent().parent().prev().prev().remove();
							$(this).parent().parent().prev().remove();
							$(this).parent().parent().next().remove();
							$(this).parent().parent().remove();
							count--;count_plus--;
						}
					});
					/* 
						* Show these on page load "OVERLAY VIDEO"
					*/
						
					// Show Third party Click tracking
					$("label[for=vast_thirdparty_clickcustom]").parent().parent().parent().prev().show();
					$("label[for=vast_thirdparty_clickcustom]").parent().parent().parent().show();	
									
					// Show Overlay Advanced Settings
					$("label[for=vast_overlay_expandedminduration]").parent().parent().parent().prev().show();
					$("label[for=vast_overlay_expandedminduration]").parent().parent().parent().show();
					
					// Show Alt Text(Vast4)
					$("label[for=vast_thirdparty_companion_alttext]").parent().parent().prev().show();
					$("label[for=vast_thirdparty_companion_alttext]").parent().parent().show(); 
									
					// Show pxratio(Vast4)
					$("label[for=vast_thirdparty_companion_pxratio]").parent().parent().prev().show();
					$("label[for=vast_thirdparty_companion_pxratio]").parent().parent().show();
									
					// Show Asset Height(Vast4)
					$("label[for=vast_thirdparty_companion_assetheight]").parent().parent().prev().show();
					$("label[for=vast_thirdparty_companion_assetheight]").parent().parent().show();
									
					// Show Asset Width(Vast4)
					$("label[for=vast_thirdparty_companion_assetwidth]").parent().parent().prev().show();
					$("label[for=vast_thirdparty_companion_assetwidth]").parent().parent().show();																								
				});		
			}
			function vasttype4(){
				$(document).ready(function(){					
					// Check radio_internal_file_keep as Default
					$("#radio_internal_file_keep").attr("checked", true);
																				
					// Show VAST 4.1 Closed Caption
					$("#vast_4_1_type_file").parent().parent().parent().show();
						
					// Show Icon information
					$("#icon_track_url").parent().parent().parent().show();
									
					// Show Add New Video URL, Video type,Video bitrate
					$("#add_url_bitrate_type").show();
					
					// Show VAST 4.0 Special Features
					$("#vast4_category_name").parent().parent().parent().parent().show();
					
					// Show VAST 3.0 Special Features
					$("#vast_video_skip_progress_duration").parent().parent().parent().show();	
																
					// Hide Conditional Ad Type 
					$("label[for=is_conditionalad]").parent().parent().prev().hide();
					$("label[for=is_conditionalad]").parent().parent().hide();	
								
					// Show Ad type 
					$("#ad_type").parent().parent().prev().show();
					$("#ad_type").parent().parent().show();	
									
					<?php if($row_ban['get_third_internal_type'] == 1){ ?>			
						// Hide Third Party Vast Tag 
						$("label[for=vast_wrapper_url]").parent().parent().prev().hide();
						$("label[for=vast_wrapper_url]").parent().parent().hide(); 
						
						// Hide Vast Version 
						$("label[for=vast_version]").parent().parent().prev().hide();
						$("label[for=vast_version]").parent().parent().hide();
						
						// Hide FallBack No Ad(Wrapper)
						$("label[for=vast_wrapper_fallbacknoads]").parent().parent().prev().hide();
						$("label[for=vast_wrapper_fallbacknoads]").parent().parent().hide();
						
						// Hide Allow Multiple Ads
						$("label[for=vast_wrapper_allowmultipleads]").parent().parent().prev().hide();
						$("label[for=vast_wrapper_allowmultipleads]").parent().parent().hide(); 
						
						// Hide FallBack No Ad(Wrapper) Additional
						$("label[for=vast_wrapper_followadditional]").parent().parent().prev().hide();
						$("label[for=vast_wrapper_followadditional]").parent().parent().hide();	
						<?php if($row_ban['vast_video_delivery']=='streaming'){ ?>
							
							// Hide Add New Video URL, Video type,Video bitrate
							$("#add_url_bitrate_type").hide();        
							
							// Show RTMP server URL
							$("label[for=vast_net_connection_url]").parent().parent().prev().show();
							$("label[for=vast_net_connection_url]").parent().parent().show();
							
							// Show Video URL/Filename
							$("label[for=vast_video_filename]").parent().parent().prev().show();
							$("label[for=vast_video_filename]").parent().parent().show(); 						
															
							// Hide Video URL/Filename 1
							$("label[for=vast_video_outgoing_filename1]").parent().parent().prev().hide();
							$("label[for=vast_video_outgoing_filename1]").parent().parent().hide();
							
							// Hide Video URL/Filename 2
							$("label[for=vast_video_outgoing_filename2]").parent().parent().prev().hide();
							$("label[for=vast_video_outgoing_filename2]").parent().parent().hide(); 
																	
							// Show Video type
							$("label[for=vast_video_type]").parent().parent().prev().show();
							$("label[for=vast_video_type]").parent().parent().show(); 						
															
							// Hide Video type 1
							$("label[for=vast_video_type_1]").parent().parent().prev().hide();
							$("label[for=vast_video_type_1]").parent().parent().hide(); 						
															
							// Hide Video type 2
							$("label[for=vast_video_type_2]").parent().parent().prev().hide();
							$("label[for=vast_video_type_2]").parent().parent().hide(); 
																	
							// Show Video duration in seconds 
							$("label[for=vast_video_duration]").parent().parent().prev().show();
							$("label[for=vast_video_duration]").parent().parent().show();				
															
							// Show Destination URL (incl. http://) when user clicks on the video
							$("label[for=vast_video_clickthrough_url]").parent().parent().prev().show();
							$("label[for=vast_video_clickthrough_url]").parent().parent().show(); 
										
							// Show Interaction
							$("label[for=interactive_mediafile]").parent().parent().prev().show();
							$("label[for=interactive_mediafile]").parent().parent().show();
										
							// Hide Video bitrate
							$("label[for=vast_video_bitrate]").parent().parent().prev().hide();
							$("label[for=vast_video_bitrate]").parent().parent().hide();
							
							// Hide Video bitrate 1
							$("label[for=vast_video_bitrate_1]").parent().parent().prev().hide();
							$("label[for=vast_video_bitrate_1]").parent().parent().hide(); 			
												
							// Hide Video bitrate 2
							$("label[for=vast_video_bitrate_2]").parent().parent().prev().hide();
							$("label[for=vast_video_bitrate_2]").parent().parent().hide(); 

							$("label[for=vast_video_filename]").html('Video filename<font color="red">*</font>');
							<?php } else {?>				
							// Hide RTMP server URL
							$("label[for=vast_net_connection_url]").parent().parent().prev().hide();
							$("label[for=vast_net_connection_url]").parent().parent().hide(); 
							
							// Show Video URL/Filename
							$("label[for=vast_video_filename]").parent().parent().prev().show();
							$("label[for=vast_video_filename]").parent().parent().show(); 						
															
							// Show Video URL/Filename 1
							$("label[for=vast_video_outgoing_filename1]").parent().parent().prev().show();
							$("label[for=vast_video_outgoing_filename1]").parent().parent().show(); 						
															
							// Show Video URL/Filename 2
							$("label[for=vast_video_outgoing_filename2]").parent().parent().prev().show();
							$("label[for=vast_video_outgoing_filename2]").parent().parent().show(); 																
							// Show Video type
							$("label[for=vast_video_type]").parent().parent().prev().show();
							$("label[for=vast_video_type]").parent().parent().show(); 						
															
							// Show Video type 1
							$("label[for=vast_video_type_1]").parent().parent().prev().show();
							$("label[for=vast_video_type_1]").parent().parent().show(); 						
															
							// Show Video type 2
							$("label[for=vast_video_type_2]").parent().parent().prev().show();
							$("label[for=vast_video_type_2]").parent().parent().show();																
							// Show Video duration in seconds 
							$("label[for=vast_video_duration]").parent().parent().prev().show();
							$("label[for=vast_video_duration]").parent().parent().show();				
															
							// Show Destination URL (incl. http://) when user clicks on the video
							$("label[for=vast_video_clickthrough_url]").parent().parent().prev().show();
							$("label[for=vast_video_clickthrough_url]").parent().parent().show(); 
										
							// Show Video bitrate
							$("label[for=vast_video_bitrate]").parent().parent().prev().show();
							$("label[for=vast_video_bitrate]").parent().parent().show();
									
							// Show Video bitrate 1
							$("label[for=vast_video_bitrate_1]").parent().parent().prev().show();
							$("label[for=vast_video_bitrate_1]").parent().parent().show(); 			
												
							// Hide Video bitrate 2
							$("label[for=vast_video_bitrate_2]").parent().parent().prev().show();
							$("label[for=vast_video_bitrate_2]").parent().parent().show();	
							
							// Show Interaction
							$("label[for=interactive_mediafile]").parent().parent().prev().show();
							$("label[for=interactive_mediafile]").parent().parent().show();	
							
							// Show Add New Video URL, Video type,Video bitrate
							$("#add_url_bitrate_type").show(); 														
							
					<?php } }  else {?>
						// Hide Ad type 
						$("#ad_type").parent().parent().prev().hide();
						$("#ad_type").parent().parent().hide();
						
						// Hide Video delivery method 
						$("label[for=video-url-format-progressive]").parent().parent().prev().hide();
						$("label[for=video-url-format-progressive]").parent().parent().hide();
						
						// Hide Interactive Media file url(VPAID)
						$("label[for=interactive_mediafile]").parent().parent().prev().hide();
						$("label[for=interactive_mediafile]").parent().parent().hide();
											
						// Hide RTMP server URL
						$("label[for=vast_net_connection_url]").parent().parent().prev().hide();
						$("label[for=vast_net_connection_url]").parent().parent().hide(); 
							
						// Hide Video URL/Filename
						$("label[for=vast_video_filename]").parent().parent().prev().hide();
						$("label[for=vast_video_filename]").parent().parent().hide(); 
									
						// Hide Video type
						$("label[for=vast_video_type]").parent().parent().prev().hide();
						$("label[for=vast_video_type]").parent().parent().hide(); 						
																	
						// Hide Video duration in seconds 
						$("label[for=vast_video_duration]").parent().parent().prev().hide();
						$("label[for=vast_video_duration]").parent().parent().hide();				
										
						// Hide Video bitrate
						$("label[for=vast_video_bitrate]").parent().parent().prev().hide();
						$("label[for=vast_video_bitrate]").parent().parent().hide();
						
						// Hide VAST 4.1 Closed Caption
						$("#vast_4_1_type_file").parent().parent().parent().hide();					
					
						// Hide VAST 4.0 Special Features
						$("#vast4_category_name").parent().parent().parent().parent().hide();
																
						// Show Third Party Vast Tag 
						$("label[for=vast_wrapper_url]").parent().parent().prev().show();
						$("label[for=vast_wrapper_url]").parent().parent().show(); 
						
						// Hide Vast Version 
						$("label[for=vast_version]").parent().parent().prev().hide();
						$("label[for=vast_version]").parent().parent().hide();
						
						// Show FallBack No Ad(Wrapper)
						$("label[for=vast_wrapper_fallbacknoads]").parent().parent().prev().show();
						$("label[for=vast_wrapper_fallbacknoads]").parent().parent().show();
						
						// Show Allow Multiple Ads
						$("label[for=vast_wrapper_allowmultipleads]").parent().parent().prev().show();
						$("label[for=vast_wrapper_allowmultipleads]").parent().parent().show(); 
						
						// Show FallBack No Ad(Wrapper) Additional
						$("label[for=vast_wrapper_followadditional]").parent().parent().prev().show();
						$("label[for=vast_wrapper_followadditional]").parent().parent().show();	

					<?php } if($row_ban['is_mezzininefile']==1){?>				
						// Show Designate your creative file as the mezzanine file 
						$("label[for=mezzanine_external]").parent().parent().prev().show();
						$("label[for=mezzanine_external]").parent().parent().show();			
						
						// Hide RTMP server URL
						$("label[for=vast_net_connection_url]").parent().parent().prev().hide();
						$("label[for=vast_net_connection_url]").parent().parent().hide();  												
														
						// Hide Video duration in seconds 
						$("label[for=vast_video_duration]").parent().parent().prev().hide();
						$("label[for=vast_video_duration]").parent().parent().hide();				
														
						// Hide Destination URL (incl. http://) when user clicks on the video
						$("label[for=vast_video_clickthrough_url]").parent().parent().prev().hide();
						$("label[for=vast_video_clickthrough_url]").parent().parent().hide();  				
						<?php  if($row_ban['mezzanine_en']==1){?>	
							// Hide Externally-hosted URL 
							$("#url_file").parent().parent().prev().hide();	
							$("#url_file").parent().parent().hide();
							
							// Hide Ad Manager-hosted File					
							$("input[name=internal_file]").parent().parent().prev().hide();	
							$("input[name=internal_file]").parent().parent().hide();
													
							// Show Do you wish to keep your existing video? Or do you want to upload another?				
							$("label[for=radio_internal_file_keep]").parent().parent().prev().show();	
							$("label[for=radio_internal_file_keep]").parent().parent().show();
							
							$("#radio_internal_file_change").click(function(){
								// Show Ad Manager-hosted File				
								$("input[name=internal_file]").parent().parent().prev().show();	
								$("input[name=internal_file]").parent().parent().show();							
							});		
												
							$("#radio_internal_file_keep").click(function(){
								// Hide Ad Manager-hosted File				
								$("input[name=internal_file]").parent().parent().prev().hide();	
								$("input[name=internal_file]").parent().parent().hide();
															
							});							
						<?php } else{?>	
							// Hide Do you wish to keep your existing video? Or do you want to upload another?				
							$("label[for=radio_internal_file_keep]").parent().parent().prev().hide();	
							$("label[for=radio_internal_file_keep]").parent().parent().hide();	
										
							// Show Externally-hosted URL 
							$("#url_file").parent().parent().prev().show();	
							$("#url_file").parent().parent().show();
							
							// Hide Ad Manager-hosted File					
							$("input[name=internal_file]").parent().parent().prev().hide();	
							$("input[name=internal_file]").parent().parent().hide();				
						<?php }} else {?>
							// Check Disable as Default
							$("#mezzanine_disabled").attr("checked", true);
															
							// Show Mezzanine Format 
							$("label[for=mezzanine_disabled]").parent().parent().prev().show();
							$("label[for=mezzanine_disabled]").parent().parent().parent().show();
														
							// Hide Do you wish to keep your existing video? Or do you want to upload another?				
							$("label[for=radio_internal_file_keep]").parent().parent().prev().hide();	
							$("label[for=radio_internal_file_keep]").parent().parent().hide();	
													
							// Hide Designate your creative file as the mezzanine file 
							$("label[for=mezzanine_external]").parent().parent().prev().hide();
							$("label[for=mezzanine_external]").parent().parent().hide();
							
							// Hide Externally-hosted URL 
							$("#url_file").parent().parent().prev().hide();	
							$("#url_file").parent().parent().hide(); 
							
							// Hide Ad Manager-hosted File					
							$("input[name=internal_file]").parent().parent().prev().hide();	
							$("input[name=internal_file]").parent().parent().hide();
									
						<?php if($row_ban['get_third_internal_type'] != 2){?>	

							// Hide RTMP server URL
							$("label[for=vast_net_connection_url]").parent().parent().prev().hide();
							$("label[for=vast_net_connection_url]").parent().parent().hide(); 
							
							// Show Video URL/Filename
							$("label[for=vast_video_filename]").parent().parent().prev().show();
							$("label[for=vast_video_filename]").parent().parent().show(); 						
							
							// Show Video URL/Filename 1
							$("label[for=vast_video_outgoing_filename1]").parent().parent().prev().show();
							$("label[for=vast_video_outgoing_filename1]").parent().parent().show();
							
							// Show Video URL/Filename 2
							$("label[for=vast_video_outgoing_filename2]").parent().parent().prev().show();
							$("label[for=vast_video_outgoing_filename2]").parent().parent().show(); 
																	
							// Show Video type
							$("label[for=vast_video_type]").parent().parent().prev().show();
							$("label[for=vast_video_type]").parent().parent().show(); 						
															
							// Show Video type 1
							$("label[for=vast_video_type_1]").parent().parent().prev().show();
							$("label[for=vast_video_type_1]").parent().parent().show(); 						
															
							// Show Video type 2
							$("label[for=vast_video_type_2]").parent().parent().prev().show();
							$("label[for=vast_video_type_2]").parent().parent().show(); 
																	
							// Show Video duration in seconds 
							$("label[for=vast_video_duration]").parent().parent().prev().show();
							$("label[for=vast_video_duration]").parent().parent().show();				
															
							// Show Destination URL (incl. http://) when user clicks on the video
							$("label[for=vast_video_clickthrough_url]").parent().parent().prev().show();
							$("label[for=vast_video_clickthrough_url]").parent().parent().show();
										
							// Show Video bitrate
							$("label[for=vast_video_bitrate]").parent().parent().prev().show();
							$("label[for=vast_video_bitrate]").parent().parent().show();		
										
							// Show Video bitrate 1
							$("label[for=vast_video_bitrate_1]").parent().parent().prev().show();
							$("label[for=vast_video_bitrate_1]").parent().parent().show(); 			
										
							// Show Video bitrate 2
							$("label[for=vast_video_bitrate_2]").parent().parent().prev().show();
							$("label[for=vast_video_bitrate_2]").parent().parent().show();			
																					
						<?php }} if($row_ban['vast_video_outgoing_filename1'] != ""){ $options=$row_ban['vast_video_type_1'];?>
							var options;
						<?php if($row_ban['ad_type'] == 2){?>
								options = '<option value="video/mp4"<?php if($options=="video/mp4") echo 'selected="selected"'; ?>>MP4</option><option value="video/x-flv"<?php if($options=="video/x-flv") echo 'selected="selected"'; ?>>FLV</option><option value="video/webm"<?php if($options=="video/webm") echo 'selected="selected"'; ?>>WEBM</option><option value="application/x-mpegURL"<?php if($options=="application/x-mpegURL") echo 'selected="selected"'; ?>>HLS</option><option value="audio/mpeg" style="display: none;">Audio/MPEG</option><option value="audio/aac" style="display: none;">Audio/AAC</option>';
						<?php }else{ ?>
								options = '<option value="video/mp4" style="display: none;">MP4</option><option value="video/x-flv" style="display: none;">FLV</option><option value="video/webm" style="display: none;">WEBM</option><option value="application/x-mpegURL" style="display: none;">HLS</option><option value="audio/mpeg"<?php if($options=="audio/mpeg") echo 'selected="selected"'; ?>>Audio/MPEG</option><option value="audio/aac"<?php if($options=="audio/aac") echo 'selected="selected"'; ?>>Audio/AAC</option>';
								// Hide Video duration in seconds 
								$("label[for=vast_video_duration]").parent().parent().prev().hide();
								$("label[for=vast_video_duration]").parent().parent().hide();								
						<?php	}	?>					
							// Adding new input box				
							$("#vast_video_type").after('&nbsp&nbsp&nbsp&nbsp<button type="button" id="add_url_bitrate_type">Add New Media URL, Media type,Media bitrate</button>');
							var count = 1;
							var count_plus = count +1;					
							var add_button = $("#add_url_bitrate_type");				
							$(add_button).parent().parent().after('<tr class="spacer"><td style="height:1px;" colspan="3">&nbsp;</td></tr><tr><td width="30">&nbsp;</td><td width="170"><label for="vast_video_outgoing_filename'+count+'">Media URL '+count_plus+'<font color="red">*</font></label></td><td width="100%"><input name="vast_video_outgoing_filename'+count+'" type="text" id="vast_video_outgoing_filename'+count+'" value="<?= $row_ban['vast_video_outgoing_filename1'] ?>" class="large"></td></tr><tr class="spacer"><td style="height:1px;" colspan="3">&nbsp;</td></tr><tr><td width="30">&nbsp;</td><td width="170"><label for="vast_video_bitrate_'+count+'">Media bitrate '+count_plus+'</label></td><td width="100%"><input name="vast_video_bitrate_'+count+'" type="text"  value="<?= $row_ban['vast_video_bitrate_1'] ?>"  id="vast_video_bitrate_'+count+'" class="large"></td></tr><tr class="spacer"><td style="height:1px;" colspan="3">&nbsp;</td></tr><tr><td width="30">&nbsp;</td><td width="170"><label for="vast_video_type_'+count+'">Media type '+count_plus+'<font color="red">*</font> </label></td><td width="100%"><select name="vast_video_type_'+count+'" id="vast_video_type_'+count+'" class="medium">'+options+'</select>&nbsp&nbsp<a href="javascript:void(0)" class="remove_field_parent_1">X</a></td></tr>');
						<?php } if($row_ban['vast_video_outgoing_filename2'] != ""){$options=$row_ban['vast_video_type_2'];?>	
							var options;
						<?php if($row_ban['ad_type'] == 2){?>
								options = '<option value="video/mp4"<?php if($options=="video/mp4") echo 'selected="selected"'; ?>>MP4</option><option value="video/x-flv"<?php if($options=="video/x-flv") echo 'selected="selected"'; ?>>FLV</option><option value="video/webm"<?php if($options=="video/webm") echo 'selected="selected"'; ?>>WEBM</option><option value="application/x-mpegURL"<?php if($options=="application/x-mpegURL") echo 'selected="selected"'; ?>>HLS</option><option value="audio/aac" style="display: none;">Audio/AAC</option>';
						<?php }else{ ?>
								options = '<option value="video/mp4" style="display: none;">MP4</option><option value="video/x-flv" style="display: none;">FLV</option><option value="video/webm" style="display: none;">WEBM</option><option value="application/x-mpegURL" style="display: none;">HLS</option>';
								// Hide Video duration in seconds 
								$("label[for=vast_video_duration]").parent().parent().prev().hide();
								$("label[for=vast_video_duration]").parent().parent().hide();								
						<?php	}	?>							
							// Adding new input box						
							var count = 2;
							var count_plus = count +1;
							var add_button = $("#add_url_bitrate_type");											
							$("#vast_video_type_1").parent().parent().after('<tr class="spacer"><td style="height:1px;" colspan="3">&nbsp;</td></tr><tr><td width="30">&nbsp;</td><td width="170"><label for="vast_video_outgoing_filename'+count+'">Media URL '+count_plus+'<font color="red">*</font></label></td><td width="100%"><input name="vast_video_outgoing_filename'+count+'" type="text" id="vast_video_outgoing_filename'+count+'" value="<?= $row_ban['vast_video_outgoing_filename2'] ?>" class="large"></td></tr><tr class="spacer"><td style="height:1px;" colspan="3">&nbsp;</td></tr><tr><td width="30">&nbsp;</td><td width="170"><label for="vast_video_bitrate_'+count+'">Media bitrate '+count_plus+'</label></td><td width="100%"><input name="vast_video_bitrate_'+count+'" type="text"  value="<?= $row_ban['vast_video_bitrate_2'] ?>"  id="vast_video_bitrate_'+count+'" class="large"></td></tr><tr class="spacer"><td style="height:1px;" colspan="3">&nbsp;</td></tr><tr><td width="30">&nbsp;</td><td width="170"><label for="vast_video_type_'+count+'">Media type '+count_plus+'<font color="red">*</font> </label></td><td width="100%"><select name="vast_video_type_'+count+'" id="vast_video_type_'+count+'" class="medium">'+options+'</select>&nbsp&nbsp<a href="javascript:void(0)" class="remove_field_parent_2">X</a></td></tr>');	
						<?php } if(($row_ban['vast_video_outgoing_filename1'] == "")&&($row_ban['vast_video_outgoing_filename2'] == "")){?>
							// Adding new input box		
							if($("#add_url_bitrate_type").length == 0){			
								$("#vast_video_type").after('&nbsp&nbsp&nbsp&nbsp<button type="button" id="add_url_bitrate_type">Add New Media URL, Media type,Media bitrate</button>');	
								var add_button = $("#add_url_bitrate_type");
								var count = 0;
								var count_plus = count +1;
								$(add_button).click(function (e) {
									var mtype = $("#ad_type option:selected").val();
									if(mtype == 2){
										options = '<option value="video/mp4">MP4</option><option value="video/x-flv">FLV</option><option value="video/webm">WEBM</option><option value="application/x-mpegURL">HLS</option>';
									}else{
										options = '<option value="video/mp4" style="display: none;">MP4</option><option value="video/x-flv" style="display: none;">FLV</option><option value="video/webm" style="display: none;">WEBM</option><option value="application/x-mpegURL" style="display: none;">HLS</option>';
									}															
									count++;
									count_plus++;
									e.preventDefault();
									if(count < 3){
										if(count != 2){
											$(add_button).parent().parent().after('<tr class="spacer"><td style="height:1px;" colspan="3">&nbsp;</td></tr><tr><td width="30">&nbsp;</td><td width="170"><label for="vast_video_outgoing_filename'+count+'">Media URL '+count_plus+'<font color="red">*</font></label></td><td width="100%"><input name="vast_video_outgoing_filename'+count+'" type="text" id="vast_video_outgoing_filename'+count+'" class="large"></td></tr><tr class="spacer"><td style="height:1px;" colspan="3">&nbsp;</td></tr><tr><td width="30">&nbsp;</td><td width="170"><label for="vast_video_bitrate_'+count+'">Media bitrate '+count_plus+'</label></td><td width="100%"><input name="vast_video_bitrate_'+count+'" type="text" value="400" id="vast_video_bitrate_'+count+'" class="large"></td></tr><tr class="spacer"><td style="height:1px;" colspan="3">&nbsp;</td></tr><tr><td width="30">&nbsp;</td><td width="170"><label for="vast_video_type_'+count+'">Media type '+count_plus+'<font color="red">*</font> </label></td><td width="100%"><select name="vast_video_type_'+count+'" id="vast_video_type_'+count+'" class="medium">'+options+'</select>&nbsp&nbsp<a href="javascript:void(0)" class="remove_field_parent_1">X</a></td></tr>');
											// Removing remove_field_parent	
											$(".remove_field_parent_1").click(function(){
												if($("#vast_video_type_2").length == 0){
													$(this).parent().parent().prev().prev().prev().prev().remove();
													$(this).parent().parent().prev().prev().prev().remove();
													$(this).parent().parent().prev().prev().remove();
													$(this).parent().parent().prev().remove();
													$(this).parent().parent().next().remove();
													$(this).parent().parent().remove();
													count--;count_plus--;
												}
											});
											if(mtype == 2){
												$("#vast_video_type_1 option[value='video/mp4']").attr("selected", "selected");	
											} else {
												$("#vast_video_type_1 option[value='audio/mpeg']").attr("selected", "selected");
											}																						
										}
										else{
											$("#vast_video_type_1").parent().parent().after('<tr class="spacer"><td style="height:1px;" colspan="3">&nbsp;</td></tr><tr><td width="30">&nbsp;</td><td width="170"><label for="vast_video_outgoing_filename'+count+'">Media URL '+count_plus+'<font color="red">*</font></label></td><td width="100%"><input name="vast_video_outgoing_filename'+count+'" type="text" id="vast_video_outgoing_filename'+count+'" class="large"></td></tr><tr class="spacer"><td style="height:1px;" colspan="3">&nbsp;</td></tr><tr><td width="30">&nbsp;</td><td width="170"><label for="vast_video_bitrate_'+count+'">Media bitrate '+count_plus+'</label></td><td width="100%"><input name="vast_video_bitrate_'+count+'" type="text" value="400" id="vast_video_bitrate_'+count+'" class="large"></td></tr><tr class="spacer"><td style="height:1px;" colspan="3">&nbsp;</td></tr><tr><td width="30">&nbsp;</td><td width="170"><label for="vast_video_type_'+count+'">Media type '+count_plus+'<font color="red">*</font> </label></td><td width="100%"><select name="vast_video_type_'+count+'" id="vast_video_type_'+count+'" class="medium">'+options+'</select>&nbsp&nbsp<a href="javascript:void(0)" class="remove_field_parent">X</a></td></tr>');	
											// Removing remove_field_parent	
											$(".remove_field_parent").click(function(){
												$(this).parent().parent().prev().prev().prev().prev().remove();
												$(this).parent().parent().prev().prev().prev().remove();
												$(this).parent().parent().prev().prev().remove();
												$(this).parent().parent().prev().remove();
												$(this).parent().parent().next().remove();
												$(this).parent().parent().remove();
												count--;count_plus--;
											});	
											if(mtype == 2){
												$("#vast_video_type_2 option[value='video/mp4']").attr("selected", "selected");	
											} else {
												$("#vast_video_type_2 option[value='audio/mpeg']").attr("selected", "selected");
											}																										
										}
									}
									if(count == 3){count = 2;count_plus = 3;}		
								});	
							}							
						<?php } else { ?>
							$(add_button).click(function (e) {
								var mtype = $("#ad_type option:selected").val();
								if(mtype == 2){
									options = '<option value="video/mp4">MP4</option><option value="video/x-flv">FLV</option><option value="video/webm">WEBM</option><option value="application/x-mpegURL">HLS</option><option value="audio/mpeg" style="display: none;">Audio/MPEG</option><option value="audio/aac" style="display: none;">Audio/AAC</option>';
								}else{
									options = '<option value="video/mp4" style="display: none;">MP4</option><option value="video/x-flv" style="display: none;">FLV</option><option value="video/webm" style="display: none;">WEBM</option><option value="application/x-mpegURL" style="display: none;">HLS</option><option value="audio/mpeg">Audio/MPEG</option><option value="audio/aac">Audio/AAC</option>';
								}								
								count++;
								count_plus++;
								e.preventDefault();
								if(count < 3){
									if(count != 2){
										$(add_button).parent().parent().after('<tr class="spacer"><td style="height:1px;" colspan="3">&nbsp;</td></tr><tr><td width="30">&nbsp;</td><td width="170"><label for="vast_video_outgoing_filename'+count+'">Media URL '+count_plus+'<font color="red">*</font></label></td><td width="100%"><input name="vast_video_outgoing_filename'+count+'" type="text" id="vast_video_outgoing_filename'+count+'" class="large"></td></tr><tr class="spacer"><td style="height:1px;" colspan="3">&nbsp;</td></tr><tr><td width="30">&nbsp;</td><td width="170"><label for="vast_video_bitrate_'+count+'">Media bitrate '+count_plus+'</label></td><td width="100%"><input name="vast_video_bitrate_'+count+'" type="text" value="400" id="vast_video_bitrate_'+count+'" class="large"></td></tr><tr class="spacer"><td style="height:1px;" colspan="3">&nbsp;</td></tr><tr><td width="30">&nbsp;</td><td width="170"><label for="vast_video_type_'+count+'">Media type '+count_plus+'<font color="red">*</font> </label></td><td width="100%"><select name="vast_video_type_'+count+'" id="vast_video_type_'+count+'" class="medium">'+options+'</select>&nbsp&nbsp<a href="javascript:void(0)" class="remove_field_parent_1">X</a></td></tr>');
										// Removing remove_field_parent	
										$(".remove_field_parent_1").click(function(){
											if($("#vast_video_type_2").length == 0){
												$(this).parent().parent().prev().prev().prev().prev().remove();
												$(this).parent().parent().prev().prev().prev().remove();
												$(this).parent().parent().prev().prev().remove();
												$(this).parent().parent().prev().remove();
												$(this).parent().parent().next().remove();
												$(this).parent().parent().remove();
												count--;count_plus--;
											}
										});
										if(mtype == 2){
											$("#vast_video_type_1 option[value='video/mp4']").attr("selected", "selected");	
										} else {
											$("#vast_video_type_1 option[value='audio/mpeg']").attr("selected", "selected");
										}																					
									}
									else{
										$("#vast_video_type_1").parent().parent().after('<tr class="spacer"><td style="height:1px;" colspan="3">&nbsp;</td></tr><tr><td width="30">&nbsp;</td><td width="170"><label for="vast_video_outgoing_filename'+count+'">Media URL '+count_plus+'<font color="red">*</font></label></td><td width="100%"><input name="vast_video_outgoing_filename'+count+'" type="text" id="vast_video_outgoing_filename'+count+'" class="large"></td></tr><tr class="spacer"><td style="height:1px;" colspan="3">&nbsp;</td></tr><tr><td width="30">&nbsp;</td><td width="170"><label for="vast_video_bitrate_'+count+'">Media bitrate '+count_plus+'</label></td><td width="100%"><input name="vast_video_bitrate_'+count+'" type="text" value="400" id="vast_video_bitrate_'+count+'" class="large"></td></tr><tr class="spacer"><td style="height:1px;" colspan="3">&nbsp;</td></tr><tr><td width="30">&nbsp;</td><td width="170"><label for="vast_video_type_'+count+'">Media type '+count_plus+'<font color="red">*</font> </label></td><td width="100%"><select name="vast_video_type_'+count+'" id="vast_video_type_'+count+'" class="medium">'+options+'</select>&nbsp&nbsp<a href="javascript:void(0)" class="remove_field_parent">X</a></td></tr>');
										// Removing remove_field_parent	
										$(".remove_field_parent").click(function(){
											$(this).parent().parent().prev().prev().prev().prev().remove();
											$(this).parent().parent().prev().prev().prev().remove();
											$(this).parent().parent().prev().prev().remove();
											$(this).parent().parent().prev().remove();
											$(this).parent().parent().next().remove();
											$(this).parent().parent().remove();
											count--;count_plus--;
										});
										if(mtype == 2){
											$("#vast_video_type_2 option[value='video/mp4']").attr("selected", "selected");	
										} else {
											$("#vast_video_type_2 option[value='audio/mpeg']").attr("selected", "selected");
										}																											
									}
								}
								if(count == 3){count = 2;count_plus = 3;}		
							});						
					<?php }?>
					// Removing remove_field_parent_1
					$(".remove_field_parent_1").click(function(){
						if($("#vast_video_type_2").length == 0){
							if (!confirm("Are you sure to delete?")){
								return false;
							}
							else{											
								$(this).parent().parent().prev().prev().prev().prev().remove();
								$(this).parent().parent().prev().prev().prev().remove();
								$(this).parent().parent().prev().prev().remove();
								$(this).parent().parent().prev().remove();
								$(this).parent().parent().next().remove();
								$(this).parent().parent().remove();
								count--;count_plus--;
							}
						}
					});					
					// Removing remove_field_parent_2
					$(".remove_field_parent_2").click(function(){
						if (!confirm("Are you sure to delete?")){
							return false;
						}
						else{					
							$(this).parent().parent().prev().prev().prev().prev().remove();
							$(this).parent().parent().prev().prev().prev().remove();
							$(this).parent().parent().prev().prev().remove();
							$(this).parent().parent().prev().remove();
							$(this).parent().parent().next().remove();
							$(this).parent().parent().remove();
							count--;count_plus--;
						}
					});					
					<?php if($row_ban['vast_4_1_url_1'] != ""){$options=$row_ban['vast_4_1_type_file_1'];$options1=$row_ban['vast_4_1_language_1'];?>	
						$("#vast_4_1_language").after('&nbsp&nbsp&nbsp&nbsp<button type="button" id="add_url_type_language">Add New URL, File Type,Language</button>');	
						var add_button_utl = $("#add_url_type_language");
						var count_utl = 1;
						var count_utl_plus = count_utl + 1;	
						$(add_button_utl).parent().parent().after('<tr class="spacer"><td style="height:1px;" colspan="3">&nbsp;</td></tr><tr><td width="30">&nbsp;</td><td width="170"><label for="vast_4_1_url_'+count_utl+'">Closed Caption Media File '+count_utl_plus+'</label></td><td width="100%"><input name="vast_4_1_url_'+count_utl+'" type="text" id="vast_4_1_url_'+count_utl+'" value="<?= $row_ban['vast_4_1_url_1'] ?>" class="large"></td></tr><tr class="spacer"><td style="height:1px;" colspan="3">&nbsp;</td></tr><tr><td width="30">&nbsp;</td><td width="170"><label for="vast_4_1_type_file_'+count_utl+'">File Type '+count_utl_plus+'<font color="red">*</font> </label></td><td width="100%"><select name="vast_4_1_type_file_'+count_utl+'" id="vast_4_1_type_file_'+count_utl+'" class="medium"><option value="1"<?php if($options=="1") echo 'selected="selected"'; ?>>text/srt</option><option value="2"<?php if($options=="2") echo 'selected="selected"'; ?>>text/vtt</option><option value="3"<?php if($options=="3") echo 'selected="selected"'; ?>>application/ttml+xml</option></select></td></tr><tr class="spacer"><td style="height:1px;" colspan="3">&nbsp;</td></tr><tr><td width="30">&nbsp;</td><td width="170"><label for="vast_4_1_language_'+count_utl+'">Language '+count_utl_plus+'<font color="red">*</font> </label></td><td width="100%"><select name="vast_4_1_language_'+count_utl+'" id="vast_4_1_language_'+count_utl+'" class="medium"><option value="1"<?php if($options1=="1") echo 'selected="selected"'; ?>>en</option><option value="2"<?php if($options1=="2") echo 'selected="selected"'; ?>>zh-TW</option><option value="3"<?php if($options1=="3") echo 'selected="selected"'; ?>>zh-CH</option></select>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" class="remove_field_parent_utl_1">X</a></td></tr>');
					<?php } if($row_ban['vast_4_1_url_2'] != ""){$options=$row_ban['vast_4_1_type_file_2'];$options1=$row_ban['vast_4_1_language_2'];?>	
						var count_utl = 2;
						var count_utl_plus = count_utl + 1;	
						$("#vast_4_1_language_1").parent().parent().after('<tr class="spacer"><td style="height:1px;" colspan="3">&nbsp;</td></tr><tr><td width="30">&nbsp;</td><td width="170"><label for="vast_4_1_url_'+count_utl+'">Closed Caption Media File '+count_utl_plus+'</label></td><td width="100%"><input name="vast_4_1_url_'+count_utl+'" type="text" id="vast_4_1_url_'+count_utl+'" value="<?= $row_ban['vast_4_1_url_2'] ?>" class="large"></td></tr><tr class="spacer"><td style="height:1px;" colspan="3">&nbsp;</td></tr><tr><td width="30">&nbsp;</td><td width="170"><label for="vast_4_1_type_file_'+count_utl+'">File Type '+count_utl_plus+'<font color="red">*</font> </label></td><td width="100%"><select name="vast_4_1_type_file_'+count_utl+'" id="vast_4_1_type_file_'+count_utl+'" class="medium"><option value="1"<?php if($options=="1") echo 'selected="selected"'; ?>>text/srt</option><option value="2"<?php if($options=="2") echo 'selected="selected"'; ?>>text/vtt</option><option value="3"<?php if($options=="3") echo 'selected="selected"'; ?>>application/ttml+xml</option></select></td></tr><tr class="spacer"><td style="height:1px;" colspan="3">&nbsp;</td></tr><tr><td width="30">&nbsp;</td><td width="170"><label for="vast_4_1_language_'+count_utl+'">Language '+count_utl_plus+'<font color="red">*</font> </label></td><td width="100%"><select name="vast_4_1_language_'+count_utl+'" id="vast_4_1_language_'+count_utl+'" class="medium"><option value="1"<?php if($options1=="1") echo 'selected="selected"'; ?>>en</option><option value="2"<?php if($options1=="2") echo 'selected="selected"'; ?>>zh-TW</option><option value="3"<?php if($options1=="3") echo 'selected="selected"'; ?>>zh-CH</option></select>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" class="remove_field_parent_utl_2">X</a></td></tr>');									
					<?php } if(($row_ban['vast_4_1_url_1'] == "")&&($row_ban['vast_4_1_url_2'] == "")){?>	
					// Adding new input box	in closed caption	
					if($("#add_url_type_language").length == 0){		
						$("#vast_4_1_language").after('&nbsp&nbsp&nbsp&nbsp<button type="button" id="add_url_type_language">Add New URL, File Type,Language</button>');	
						var add_button_utl = $("#add_url_type_language");
						var count_utl = 0;
						var count_utl_plus = count_utl + 1;
						$(add_button_utl).click(function (e) {
							count_utl++;
							count_utl_plus++;
							e.preventDefault();
							if(count_utl < 3){
								if(count_utl != 2){	
									$(add_button_utl).parent().parent().after('<tr class="spacer"><td style="height:1px;" colspan="3">&nbsp;</td></tr><tr><td width="30">&nbsp;</td><td width="170"><label for="vast_4_1_url_'+count_utl+'">Closed Caption Media File '+count_utl_plus+'</label></td><td width="100%"><input name="vast_4_1_url_'+count_utl+'" type="text" id="vast_4_1_url_'+count_utl+'" class="large"></td></tr><tr class="spacer"><td style="height:1px;" colspan="3">&nbsp;</td></tr><tr><td width="30">&nbsp;</td><td width="170"><label for="vast_4_1_type_file_'+count_utl+'">File Type '+count_utl_plus+'<font color="red">*</font> </label></td><td width="100%"><select name="vast_4_1_type_file_'+count_utl+'" id="vast_4_1_type_file_'+count_utl+'" class="medium"><option value="1">text/srt</option><option value="2">text/vtt</option><option value="3">application/ttml+xml</option></select></td></tr><tr class="spacer"><td style="height:1px;" colspan="3">&nbsp;</td></tr><tr><td width="30">&nbsp;</td><td width="170"><label for="vast_4_1_language_'+count_utl+'">Language '+count_utl_plus+'<font color="red">*</font> </label></td><td width="100%"><select name="vast_4_1_language_'+count_utl+'" id="vast_4_1_language_'+count_utl+'" class="medium"><option value="1">en</option><option value="2">zh-TW</option><option value="3">zh-CH</option></select>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" class="remove_field_parent_utl_1">X</a></td></tr>');
									// Removing remove_field_parent_utl	
									$(".remove_field_parent_utl_1").click(function(){
										if($("#vast_4_1_language_2").length == 0){									
											$(this).parent().parent().prev().prev().prev().prev().remove();
											$(this).parent().parent().prev().prev().prev().remove();
											$(this).parent().parent().prev().prev().remove();
											$(this).parent().parent().prev().remove();
											$(this).parent().parent().next().remove();
											$(this).parent().parent().remove();
											count_utl--;count_utl_plus--;
										}
									});									
								}
								else{
									$("#vast_4_1_language_1").parent().parent().after('<tr class="spacer"><td style="height:1px;" colspan="3">&nbsp;</td></tr><tr><td width="30">&nbsp;</td><td width="170"><label for="vast_4_1_url_'+count_utl+'">Closed Caption Media File '+count_utl_plus+'</label></td><td width="100%"><input name="vast_4_1_url_'+count_utl+'" type="text" id="vast_4_1_url_'+count_utl+'" class="large"></td></tr><tr class="spacer"><td style="height:1px;" colspan="3">&nbsp;</td></tr><tr><td width="30">&nbsp;</td><td width="170"><label for="vast_4_1_type_file_'+count_utl+'">File Type '+count_utl_plus+'<font color="red">*</font> </label></td><td width="100%"><select name="vast_4_1_type_file_'+count_utl+'" id="vast_4_1_type_file_'+count_utl+'" class="medium"><option value="1">text/srt</option><option value="2">text/vtt</option><option value="3">application/ttml+xml</option></select></td></tr><tr class="spacer"><td style="height:1px;" colspan="3">&nbsp;</td></tr><tr><td width="30">&nbsp;</td><td width="170"><label for="vast_4_1_language_'+count_utl+'">Language '+count_utl_plus+'<font color="red">*</font> </label></td><td width="100%"><select name="vast_4_1_language_'+count_utl+'" id="vast_4_1_language_'+count_utl+'" class="medium"><option value="1">en</option><option value="2">zh-TW</option><option value="3">zh-CH</option></select>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" class="remove_field_parent_utl">X</a></td></tr>');
									// Removing remove_field_parent_utl	
									$(".remove_field_parent_utl").click(function(){
										$(this).parent().parent().prev().prev().prev().prev().remove();
										$(this).parent().parent().prev().prev().prev().remove();
										$(this).parent().parent().prev().prev().remove();
										$(this).parent().parent().prev().remove();
										$(this).parent().parent().next().remove();
										$(this).parent().parent().remove();
										count_utl--;count_utl_plus--;
									});																
									}
							}
							if(count_utl == 3){count_utl = 2;count_utl_plus = 3;}		
						});	
					}				
					<?php } else {?>	
						$(add_button_utl).click(function(e){
							count_utl++;
							count_utl_plus++;
							e.preventDefault();
							if(count_utl < 3){
								if(count_utl != 2){	
									$(add_button_utl).parent().parent().after('<tr class="spacer"><td style="height:1px;" colspan="3">&nbsp;</td></tr><tr><td width="30">&nbsp;</td><td width="170"><label for="vast_4_1_url_'+count_utl+'">Closed Caption Media File '+count_utl_plus+'</label></td><td width="100%"><input name="vast_4_1_url_'+count_utl+'" type="text" id="vast_4_1_url_'+count_utl+'" class="large"></td></tr><tr class="spacer"><td style="height:1px;" colspan="3">&nbsp;</td></tr><tr><td width="30">&nbsp;</td><td width="170"><label for="vast_4_1_type_file_'+count_utl+'">File Type '+count_utl_plus+'<font color="red">*</font> </label></td><td width="100%"><select name="vast_4_1_type_file_'+count_utl+'" id="vast_4_1_type_file_'+count_utl+'" class="medium"><option value="1">text/srt</option><option value="2">text/vtt</option><option value="3">application/ttml+xml</option></select></td></tr><tr class="spacer"><td style="height:1px;" colspan="3">&nbsp;</td></tr><tr><td width="30">&nbsp;</td><td width="170"><label for="vast_4_1_language_'+count_utl+'">Language '+count_utl_plus+'<font color="red">*</font> </label></td><td width="100%"><select name="vast_4_1_language_'+count_utl+'" id="vast_4_1_language_'+count_utl+'" class="medium"><option value="1">en</option><option value="2">zh-TW</option><option value="3">zh-CH</option></select>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" class="remove_field_parent_utl_1">X</a></td></tr>');					
									// Removing remove_field_parent_utl	
									$(".remove_field_parent_utl_1").click(function(){
										if($("#vast_4_1_language_2").length == 0){									
											$(this).parent().parent().prev().prev().prev().prev().remove();
											$(this).parent().parent().prev().prev().prev().remove();
											$(this).parent().parent().prev().prev().remove();
											$(this).parent().parent().prev().remove();
											$(this).parent().parent().next().remove();
											$(this).parent().parent().remove();
											count_utl--;count_utl_plus--;
										}
									});							
								}
								else{
									$("#vast_4_1_language_1").parent().parent().after('<tr class="spacer"><td style="height:1px;" colspan="3">&nbsp;</td></tr><tr><td width="30">&nbsp;</td><td width="170"><label for="vast_4_1_url_'+count_utl+'">Closed Caption Media File '+count_utl_plus+'</label></td><td width="100%"><input name="vast_4_1_url_'+count_utl+'" type="text" id="vast_4_1_url_'+count_utl+'" class="large"></td></tr><tr class="spacer"><td style="height:1px;" colspan="3">&nbsp;</td></tr><tr><td width="30">&nbsp;</td><td width="170"><label for="vast_4_1_type_file_'+count_utl+'">File Type '+count_utl_plus+'<font color="red">*</font> </label></td><td width="100%"><select name="vast_4_1_type_file_'+count_utl+'" id="vast_4_1_type_file_'+count_utl+'" class="medium"><option value="1">text/srt</option><option value="2">text/vtt</option><option value="3">application/ttml+xml</option></select></td></tr><tr class="spacer"><td style="height:1px;" colspan="3">&nbsp;</td></tr><tr><td width="30">&nbsp;</td><td width="170"><label for="vast_4_1_language_'+count_utl+'">Language '+count_utl_plus+'<font color="red">*</font> </label></td><td width="100%"><select name="vast_4_1_language_'+count_utl+'" id="vast_4_1_language_'+count_utl+'" class="medium"><option value="1">en</option><option value="2">zh-TW</option><option value="3">zh-CH</option></select>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" class="remove_field_parent_utl">X</a></td></tr>');				
									// Removing remove_field_parent_utl	
									$(".remove_field_parent_utl").click(function(){
										$(this).parent().parent().prev().prev().prev().prev().remove();
										$(this).parent().parent().prev().prev().prev().remove();
										$(this).parent().parent().prev().prev().remove();
										$(this).parent().parent().prev().remove();
										$(this).parent().parent().next().remove();
										$(this).parent().parent().remove();
										count_utl--;count_utl_plus--;
									});															
									}
							}
							if(count_utl == 3){count_utl = 2;count_utl_plus = 3;}		
						});	
					<?php } ?>																						
					// Removing remove_field_parent_utl_1
					$(".remove_field_parent_utl_1").click(function(){
						if($("#vast_4_1_language_2").length == 0){
						if (!confirm("Are you sure to delete?")){
							return false;
						}
						else{												
							$(this).parent().parent().prev().prev().prev().prev().remove();
							$(this).parent().parent().prev().prev().prev().remove();
							$(this).parent().parent().prev().prev().remove();
							$(this).parent().parent().prev().remove();
							$(this).parent().parent().next().remove();
							$(this).parent().parent().remove();
							count_utl--;count_utl_plus--;
						}
						}
					});															
					// Removing remove_field_parent_utl_2	
					$(".remove_field_parent_utl_2").click(function(){
						if (!confirm("Are you sure to delete?")){
							return false;
						}
						else{					
							$(this).parent().parent().prev().prev().prev().prev().remove();
							$(this).parent().parent().prev().prev().prev().remove();
							$(this).parent().parent().prev().prev().remove();
							$(this).parent().parent().prev().remove();
							$(this).parent().parent().next().remove();
							$(this).parent().parent().remove();
							count_utl--;count_utl_plus--;
						}
					});	
					/* 
						* Show these on page load "OVERLAY VIDEO"
					*/
						
					// Show Third party Click tracking
					$("label[for=vast_thirdparty_clickcustom]").parent().parent().parent().prev().show();
					$("label[for=vast_thirdparty_clickcustom]").parent().parent().parent().show();	
									
					// Show Overlay Advanced Settings
					$("label[for=vast_overlay_expandedminduration]").parent().parent().parent().prev().show();
					$("label[for=vast_overlay_expandedminduration]").parent().parent().parent().show();
					
					// Show Alt Text(Vast4)
					$("label[for=vast_thirdparty_companion_alttext]").parent().parent().prev().show();
					$("label[for=vast_thirdparty_companion_alttext]").parent().parent().show(); 
									
					// Show pxratio(Vast4)
					$("label[for=vast_thirdparty_companion_pxratio]").parent().parent().prev().show();
					$("label[for=vast_thirdparty_companion_pxratio]").parent().parent().show();
									
					// Show Asset Height(Vast4)
					$("label[for=vast_thirdparty_companion_assetheight]").parent().parent().prev().show();
					$("label[for=vast_thirdparty_companion_assetheight]").parent().parent().show();
									
					// Show Asset Width(Vast4)
					$("label[for=vast_thirdparty_companion_assetwidth]").parent().parent().prev().show();
					$("label[for=vast_thirdparty_companion_assetwidth]").parent().parent().show();
																												
				<?php if($row_ban['ad_type'] == 1){ ?>					
					// Hide Interaction
					$("label[for=interactive_mediafile]").parent().parent().prev().hide();
					$("label[for=interactive_mediafile]").parent().parent().hide();					
				<?php } else{ ?>					
					// Show Interaction
					$("label[for=interactive_mediafile]").parent().parent().prev().show();
					$("label[for=interactive_mediafile]").parent().parent().show();					
				<?php } ?>
				});		
			}						
<?php 	} 	?>		
</script>
