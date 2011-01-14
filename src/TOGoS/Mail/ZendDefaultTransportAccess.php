<?php

/** 
 * Provides a public accessor for Zend_Mail::$_defaultTransport, since
 * Zend_Mail doesn't have one (possibly an oversight, since it has a
 * function to *set* the default transport).
 */
class TOGoS_Mail_ZendDefaultTransportAccess extends Zend_Mail
{
	/**
	 * @return Zend_Mail_Transport_Abstract as registered by
	 *   Zend_Mail::setDefaultTransport(...) OR
	 *   a new Sendmail one (mimicking the behavior in Zend_Mail#send)
	 */
	public static function getDefaultTransport() {
		if( self::$_defaultTransport !== null ) {
			return self::$_defaultTransport;
		} else {
			return new Zend_Mail_Transport_Sendmail();
		}
	}
}
