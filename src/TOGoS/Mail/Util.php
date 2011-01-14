<?php

class TOGoS_Mail_Util
{
	/**
	 * @param TOGoS_Mail_Address $addy
	 * @return string of the form '"Joe Blow" <joe.blow@example.com>' or 'joe.blow@example.com'
	 *  (not including the single quotes)
	 */
	public static function formatAddress( $addy ) {
		$em = $addy->getEmailAddress();
		if( ($name = $addy->getFullName()) ) {
			return "\"$name\" <$em>";
		} else {
			return $em;
		}
	}
	
	/**
	 * @param array of TOGoS_Mail_Addresses
	 * @return string of the form '"Joe Blow" <joe@example.com>, cindy@example.com'
	 *  (not including the single quotes)
	 */
	public static function formatAddressList( $addresses ) {
		$parts = array();
		foreach( $addresses as $addy ) {
			$parts[] = self::formatAddress( $addy );
		}
		return implode(', ',$parts);
	}
	
	/**
	 * Format the given message in a human-readable way for debugging
	 * Note that while the output may look similar to a MIME-formatted
	 * message, it is not intended to be machine-readable.
	 * 
	 * @param TOGoS_Mail_Message $message
	 * @return string a plain-text human-readable representation of
	 *   the message
	 */
	public static function formatMessage( $message ) {
		$headers = array();
		$headers['From'] = self::formatAddress( $message->getSender() );
		$headers['To']   = self::formatAddressList( $message->getPrimaryRecipients() );
		$headers['CC']   = self::formatAddressList( $message->getCcRecipients() );
		$headers['BCC']  = self::formatAddressList( $message->getBccRecipients() );
		$headers['Subject'] = $message->getSubject();
		
		$resultLines = array();
		
		foreach( $headers as $k=>$v ) if( $v ) $resultLines[] = "$k: $v";
		
		if( ($bodyText = $message->getBodyText()) !== null ) {
			$resultLines[] = "-- Body Text --";
			$resultLines[] = trim($bodyText);
		}
		if( ($bodyHtml = $message->getBodyText()) !== null ) {
			$resultLines[] = "-- Body HTML --";
			$resultLines[] = trim($bodyHtml);
		}
		
		return implode("\n", $resultLines);
	}
	
	public static function toAddress( $addy, $name=null ) {
		if( is_object($addy) ) {
			return $addy;
		} else if( is_scalar($addy) ) {
			if(
				preg_match('/ "([^"]+)" \s <([^>]+)> /x',$addy,$bif) or
				preg_match('/     (.+)  \s <([^>]+)> /x',$addy,$bif) or
				preg_match('/ "([^"]+)" \s  (   .+)  /x',$addy,$bif)
			) {
				if( $name === null ) $name = $bif[1];
				$addy = $bif[2];
			}
			return new TOGoS_Mail_BasicAddress( $addy, $name );
		} else {
			throw new Exception("Can't create address from ".gettype($addy));
		}		
	}
}
