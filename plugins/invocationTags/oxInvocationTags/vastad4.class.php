<?php


require_once LIB_PATH . '/Extension/invocationTags/InvocationTags.php';
require_once MAX_PATH . '/lib/max/Plugin/Translation.php';

/**
 *
 * Invocation tag plugin.
 *
 */
class Plugins_InvocationTags_OxInvocationTags_vastad4 extends Plugins_InvocationTags
{

    /**
     * Return name of plugin
     *
     * @return string
     */
    function getName()
    {
        return $this->translate("VAST Ad Pod Wrapper");
    }

    /**
     * Return the English name of the plugin. Used when
     * generating translation keys based on the plugin
     * name.
     *
     * @return string An English string describing the class.
     */
    function getNameEN()
    {
        return 'VAST Ad Pod Wrapper';
    }

    /**
     * Check if plugin is allowed
     *
     * @return boolean  True - allowed, false - not allowed
     */
    function isAllowed($extra)
    {
        $isAllowed = parent::isAllowed($extra);
        return true;
    }

    function getOrder()
    {
        parent::getOrder();
        return 31;
    }

    /**
     * Return list of options
     *
     * @return array    Group of options
     */
    function getOptionsList()
    {
        if (is_array($this->defaultOptions)) {
            if (in_array('cacheBuster', $this->defaultOptions)) {
                unset($this->defaultOptions['cacheBuster']);
            }
        }
          $options = array (
            'limit'         => MAX_PLUGINS_INVOCATION_TAGS_STANDARD,
	    
            'adposition'        => MAX_PLUGINS_INVOCATION_TAGS_STANDARD,'withoutplayer'	=> MAX_PLUGINS_INVOCATION_TAGS_STANDARD,
        );

        return $options;
    }

    /**
     * Return invocation code for this plugin (codetype)
     *
     * @return string
     */
    function generateInvocationCode()
    {
        $aComments = array(
            'SSL Delivery Comment' => '',
            'Comment'              => $this->translate("
  * This noscript section of this tag only shows image banners. There
  * is no width or height in these banners, so if you want these tags to
  * allocate space for the ad before it shows, you will need to add this
  * information to the <img> tag.
  *
  * If you do not want to deal with the intricities of the noscript
  * section, delete the tag (from <noscript>... to </noscript>). On
  * average, the noscript tag is called from less than 1% of internet
  * users."),
            );
        parent::prepareCommonInvocationData($aComments);

	/*VAST VERSION 2.0*/

        $conf = $GLOBALS['_MAX']['CONF'];
        $mi = &$this->maxInvocation;
	$zoneid=$mi->zoneid;
	
	if ($GLOBALS['_MAX']['SSL_REQUEST']) {
					$djprotocol='https://';
					}
					else
					{
					$djprotocol='http://';
					}
	$way=$djprotocol.$conf['webpath']['deliverVastUrl'];
	$limit=$mi->limit;
    	if($mi->dadposition==1)
	{
		$vtype='preroll';
	}
	else if($mi->dadposition==2)	
	{
		$vtype='midroll';
	}
	else if($mi->dadposition==3)	
	{
		$vtype='postroll';
	}
	else
	{
		$vtype='postroll';
	}
      
		$playerurl=$mi->withoutplayer;

     if(isset($mi->limit) && $mi->limit !== '') 
	    {
		  $limit = $mi->limit;
	    } 
	if(!empty($mi->withoutplayer))
	{
	$buffer .= $way."/fc.php?script=rmVideo&zoneid=$zoneid&limit=$limit&type=$vtype&format=vast_pod_wrapper&loc=$playerurl";
	}
	else
	{
        $buffer .= $way."/fc.php?script=rmVideo&zoneid=$zoneid&limit=$limit&type=$vtype&format=vast_pod_wrapper&loc=\"+window.location.hostname+\"";
	}		
	/*VAST VERSION 2.0*/

        return $buffer;
    }

    function setInvocation(&$invocation) {

        $this->maxInvocation = &$invocation;
        $this->maxInvocation->canDetectCharset = true;

    }

}

?>
