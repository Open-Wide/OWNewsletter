<?php

class OWNewsletterTrackingGoogle extends OWNewsletterTracking
{
	
	protected $currentTimestamp;
	
	
	public function __construct( ) {
		
		parent::__construct( );
		$this->currentTimestamp = time();

	}
	
	/**
     * Return general placeholders which will be replaced in tracking markers
     * You can add custom general placeholders in this function, see below :
     *
     * @return array
     */
	/*protected function getPlaceholders() {

		$result = parent::getPlaceholders();
		$result['{{CUSTOM_PLACEHOLDER}}'] = 'Custom_Value';
		return $result;
	}*/
	
	
	/**
     * Build GoogleAnalytics read marker, and replace placeholders in this marker.
     * 
     * @see newsletter.ini
     *
     * @return string
     */	
	protected function getReadMarker () {
		
		$ini = eZINI::instance( "newsletter.ini" );
		$var_utmac = '{{SETTINGS=GoogleId}}';
		$var_utmhn = '{{SITE_URL}}'; // domain
		$var_utmn = rand(1000000000,9999999999); // random number
		$var_cookie = rand(10000000,99999999); //random cookie number
		$var_random = rand(1000000000,2147483647); //number under 2147483647
		$var_today = $this->currentTimestamp;
		$var_referer = $_SERVER['HTTP_REFERER']; //referer url
		$var_utmt = $ini->hasVariable( 'CustomTrackingSettings', 'utmt' ) ? $ini->variable( 'CustomTrackingSettings', 'utmt' ) : 'page'; 
		if ($var_referer == '') { $var_referer = '-'; }
		$var_uservar='-'; // no user-defined
		$var_utmp='{{SETTINGS=Campaign}}'; // Name of campaign
		$urchinUrl=	'http://www.google-analytics.com/__utm.gif'
					.'?utmwv=3'
					.'&utmn='.$var_utmn
					.'&utme='
					.'&utmcs=-'
					.'&utmsr=-'
					.'&utmsc=-'
					.'&utmul=-'
					.'&utmje=0'
					.'&utmfl=-'
					.'&utmdt=-'
					.'&utmhn='.$var_utmhn 
					.'&utmhid='.$var_utmn
					.'&utmr='.$var_referer
					.'&utmp='.$var_utmp
					.'&utmac='.$var_utmac
					.'&utmt='.$var_utmt
					.'&utmcc=__utma%3D' . $var_cookie . '.' . $var_random . '.' . $var_today . '.' . $var_today . '.' . $var_today . '.2%3B%2B__utmz%3D' . $var_cookie . '.' . $var_today . '.2.2.'.
					'utmcsr%3D{{SETTINGS=Source}}'.
					'%7Cutmccn%3D'.$var_utmp.
					'%7Cutmcmd%3D{{SETTINGS=Medium}}'.
					'%7Cutmctr%3D{{SETTINGS=Keyword}}'.
					'%7Cutmcct%3D{{SETTINGS=Content}}%3B%2B__utmv%3D' . $var_cookie . '.' . $var_uservar . '%3B';
		$marker = ' <img src="' . $urchinUrl . '" border="0" />';
		
		$marker = $this->replacePlaceholders( $marker );
		
		return $marker;
	}


}

?>
