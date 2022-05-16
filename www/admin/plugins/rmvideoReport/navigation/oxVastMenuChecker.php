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

require_once(MAX_PATH . '/lib/OA/Admin/Menu/IChecker.php');

class Plugins_admin_VideoAds_vastMenuChecker implements OA_Admin_Menu_IChecker
{
    /**
     * @param OA_Admin_Menu_Section $oSection
     */
    public function check($oSection) 
    {
        // this checker is called 6 times, not sure why, but we cache the lookup in a static variable
        static $cache = array();
        if(isset($cache[$oSection->getId()])) {
            return $cache[$oSection->getId()];
        }
        $enabled = false;
        require_once MAX_PATH . '/www/admin/plugins/rmvideoReport/stats-api.php';
        $vast = new OX_rmVideo_Report;
        phpAds_registerGlobal( 'clientid', 'campaignid', 'bannerid', 'zoneid' );
        global $clientid, $campaignid, $bannerid, $zoneid, $affiliateid;
//        echo "<pre>";debug_print_backtrace();
        switch($oSection->getId()) {
            case 'stats-djaxvast-advertiser':
                    $enabled = $vast->doesAdvertiserHaveVast((int)$clientid);
                break;
            case 'stats-djaxvast-campaign':
                    $enabled = $vast->doesCampaignHaveVast((int)$campaignid);
                break;
            case 'stats-djaxvast-banner': 
                    $enabled =$vast->doesBannerHaveVast((int)$bannerid);
                break;
            case 'stats-djaxvast-zone':
                    $enabled = $vast->isZoneVast((int)$zoneid);
                break;
            case 'stats-djaxvast-website':
                    $enabled = $vast->doesWebsiteHaveVast((int)$affiliateid);
                break;
            case 'players-vast':
                return true;
                break;
            case 'zone-invocation':
                if(!empty($zoneid) && $vast->isZoneVast((int)$zoneid)) {
                    $oSection->setNameKey('VAST Invocation Code');
                    $oSection->setLink('plugins/rmvideoReport/zone-invocation-code.php?zoneid='.(int)$zoneid.'&affiliateid='.(int)$affiliateid);
                }
                $enabled = true;
                break;
        }
        $cache[$oSection->getId()] = $enabled;
        return $enabled;
    }
}
