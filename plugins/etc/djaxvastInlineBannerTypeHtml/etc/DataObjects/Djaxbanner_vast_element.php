<?php
/**
 * Table Definition for banner_vast_element
 */
require_once MAX_PATH.'/lib/max/Dal/DataObjects/DB_DataObjectCommon.php';

class DataObjects_Djaxbanner_vast_element extends DB_DataObjectCommon
{
    public $__table = 'djaxbanner_vast_element';             // table name
    public $banner_vast_element_id;          // MEDIUMINT(9) => openads_mediumint => 129
    public $banner_id;                       // MEDIUMINT(9) => openads_mediumint => 129
    public $vast_element_type;               // VARCHAR(16) => openads_varchar => 130
    public $vast_video_id;                   // VARCHAR(100) => openads_varchar => 2
    public $vast_type;                   	 // int(1) => openads_varchar => 2
    public $vast_video_duration;             // MEDIUMINT(9) => openads_mediumint => 1
    public $vast_video_delivery;             // VARCHAR(20) => openads_varchar => 2
    public $get_third_internal_type;         // int(1) => openads_varchar => 2
    public $vast_video_type;                 // VARCHAR(20) => openads_varchar => 2
    public $vast_video_bitrate;              // VARCHAR(20) => openads_varchar => 2
    public $vast_net_connection_url;         // text => openads_varchar => 2
    public $vast_video_filename;         	 // VARCHAR(255) => openads_varchar => 2
    public $vast_video_height;               // MEDIUMINT(9) => openads_mediumint => 1
    public $vast_video_width;                // MEDIUMINT(9) => openads_mediumint => 1
    public $vast_video_outgoing_filename;    // TEXT() => openads_text => 34
    public $vast_companion_banner_id;        // MEDIUMINT(9) => openads_mediumint => 1
    public $vast_overlay_height;             // MEDIUMINT(9) => openads_mediumint => 1
    public $vast_overlay_width;              // MEDIUMINT(9) => openads_mediumint => 1
    public $vast_video_clickthrough_url;     // TEXT() => openads_text => 34
    public $vast_overlay_action;             // VARCHAR(20) => openads_varchar => 2
    public $vast_overlay_format;             // VARCHAR(20) => openads_varchar => 2
    public $vast_overlay_text_title;         // TEXT() => openads_text => 34
    public $vast_overlay_text_description;    // TEXT() => openads_text => 34
    public $vast_overlay_text_call;          // TEXT() => openads_text => 34
    public $vast_creative_type;              // VARCHAR(20) => openads_varchar => 2
    public $vast_thirdparty_impression;      // TEXT() => openads_text => 162
    public $vast_wrapper_url;      // TEXT() => openads_text => 162
    public $vast_version;
    public $vast_overlay_wrapper;
    public $vast_overlay_version;
    public $vast_video_skip_duration;
    public $vast_video_skip_progress_duration;
    public $vast_thirdparty_clicktracking;
    public $vast_thirdparty_clickcustom;
    public $vast_overlay_expanded_width;
    public $vast_overlay_expanded_height;
    public $vast_thirdparty_companion_expandedwidth;
    public $vast_thirdparty_companion_expandedheight;
    public $vast_thirdparty_companion_clicktracking;
    public $vast_overlay_expandedminduration;
	public $vast_wrapper_followadditional;
	public $vast_wrapper_allowmultipleads;
	public $vast_wrapper_fallbacknoads;
	public $vast_thirdparty_companion_assetwidth;
	public $vast_thirdparty_companion_assetheight;
	public $vast_thirdparty_companion_pxratio;
	public $vast_thirdparty_companion_alttext;
	public $vast4_min_bitrate;
	public $vast4_max_bitrate;
	public $vast4_verificationurl2;
	public $vast4_verificationurl1;
	public $vast4_adverificationtype2;
	public $vast4_adverificationtype1;
	public $is_conditionalad;
	public $vast_icon_filename;
	public $vast_icon_width;
	public $vast_icon_height;
	public $vast_icon_xposition;
	public $vast_icon_yposition;
	public $vast_icon_duration;
	public $vast_icon_offset;
	public $icon_click_url;
	public $icon_track_url;
	public $iabcategory;
	public $vast_video_outgoing_filename1;
	public $vast_video_type_1;
	public $vast_video_bitrate_1;
    public $vast_video_outgoing_filename2;
	public $vast_video_type_2;
	public $vast_video_bitrate_2;
	public $ad_type;
	public $audio_type;
	public $interactive_mediafile;
	public $is_mezzininefile;
	public $mezzanine_en;
	public $internal_file;
	public $url_file;
	public $vast_4_1_url;
	public $vast_4_1_type_file;
	public $vast_4_1_language;
	public $vast_4_1_url_1;
	public $vast_4_1_type_file_1;
	public $vast_4_1_language_1;
	public $vast_4_1_url_2;
	public $vast_4_1_type_file_2;
	public $vast_4_1_language_2;
	public $blocked_category;
    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGetFromClassName('DataObjects_Djaxbanner_vast_element',$k,$v); }

    var $defaultValues = array(
                'vast_element_type' => '',
                'vast_thirdparty_impression' => '',
                );

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
?>
