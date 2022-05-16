<?php

require_once RV_PATH . '/lib/RV.php';

require_once MAX_PATH . '/plugins/bannerTypeHtml/djaxvastInlineBannerTypeHtml/common.php';
require_once MAX_PATH . '/plugins/bannerTypeHtml/djaxvastInlineBannerTypeHtml/commonAdmin.php';
require_once MAX_PATH . '/lib/OA.php';
require_once LIB_PATH . '/Extension/bannerTypeHtml/bannerTypeHtml.php';
require_once MAX_PATH . '/lib/max/Plugin/Common.php';

/**
 * @package    OpenXPlugin 
 * @subpackage Plugins_BannerTypes
 */
class Plugins_BannerTypeHTML_djaxvastInlineBannerTypeHtml_vastInlineHtml extends Plugins_BannerTypeHTML_djaxvastInlineBannerTypeHtml_vastBase
{
    function getBannerShortName()
    {
        return 'Inline Video Ad';
    }

    function getZoneToLinkShortName()
    {
        return $this->getBannerShortName();
    }

    /**
     * Return description of banner type
     * for the dropdown selection on the banner-edit screen
     *
     * @return string A string describing the type of plugin.
     */
    function getOptionDescription()
    {
        return 'Inline Video Ad(VAST 2.0,VAST 3.0)';
    }

    function getHelpAdTypeDescription()
    {
        return 'An '.$this->getBannerShortName().' is a video ad that can be presented before, in the middle of, or after the video content and takes over the full view of the video. ';
    }

    /**
     * Append type-specific form elements to the base form
     *
     * @param object form
     * @param integer bannerId
     */
    function buildForm(&$form, &$bannerRow)
    {
		
        parent::buildForm($form, $bannerRow);
        
    	$selectableCompanions = $this->getPossibleCompanions($bannerRow);
    	// for some bizarre reason $bannerid is all the fields
    	$bannerRow = $this->getExtendedBannerInfo($bannerRow);
        $isNewBanner = false;
        if ( !isset( $bannerRow['banner_vast_element_id']) ){
            $isNewBanner = true;
        }
		
        $header = $form->createElement('header', 'header_txt', "Create an Inline Video Ad (pre/mid/post-roll)");
        $header->setAttribute('icon', 'icon-banner-text.gif');
        $form->addElement($header);
        $form->addElement('hidden', 'ext_bannertype', $this->getComponentIdentifier());

        $this->addIntroductionInlineHelp($form);
        $this->addVastHardcodedDimensionsToForm($form, $bannerRow, VAST_INLINE_DIMENSIONS);
		
        $isVideoUploadSupported = false;
        if ($isVideoUploadSupported) {
            addUploadGroup($form, $row,
                array(
                    'uploadName' => 'uploadalt',
                    'radioName' => 'replacealtimage',
                    'imageName'  => $altImageName,
                    'fileSize'  => $altSize,
                    'fileName'  => $row['alt_filename'],
                    'newLabel'  => "select incomming video file",
                    'updateLabel'  => "select replacement video file",
                    'handleSWF' => false
                  )
            );
        }
        
        $this->addVastParametersToForm($form, $bannerRow, $isNewBanner);
        //~ $this->setElementIsRequired('vast_video_delivery', 'ext_bannertype', $this->getComponentIdentifier());
        //~ $this->setElementIsRequired('vast_video_filename', 'ext_bannertype', $this->getComponentIdentifier());
        //~ $this->setElementIsRequired('vast_video_type', 'ext_bannertype', $this->getComponentIdentifier());
        //~ $this->setElementIsRequired('vast_video_duration', 'ext_bannertype', $this->getComponentIdentifier());

        $this->addThirdPartyImpressionTracking($form);
	$this->addThirdPartyClickTracking($form);//DAC015
        $this->addVastCompanionsToForm($form, $selectableCompanions);
    }

    function onEnable()
    {
        $oSettings  = new OA_Admin_Settings();
        $oSettings->settingChange('allowedBanners','video','1');
        $oSettings->writeConfigChange();
        return true;
    }
}
