<?php

/**
 * TOGoS_Mail_Transport that converts messages to Zend_Mail objects and
 * passes to a Zend_Mail_Transport
 */
class TOGoS_Mail_ZendTransport implements TOGoS_Mail_Transport
{
	protected $zendTransport;
	
	/**
	 * @param Zend_Mail_Transport_Abstract $zendTransport
	 */
	public function __construct( $zendTransport ) {
		$this->zendTransport = $zendTransport;
	}

	/**
	 * @param TOGoS_Mail_Message $message
	 * @return Zend_Mail object with all info (sender, recipients, headers, content) filled in from the given message
	 */
	protected function togToZendMessage( $message ) {
		$zm = new Zend_Mail();
		if( ($sender = $message->getSender()) !== null ) {
			$senderEmail = $sender->getEmailAddress();
			$senderFullName = $sender->getFullName();
			
			// Zend_Mail will make a blank sender name if one is not given;
			// Default it to pre-@ part of the e-mail address so that recipients
			// don't see blank sender names.
			if( !$senderFullName and preg_match('/^([^@]+)@/',$senderEmail,$bif) ) {
				$senderFullName = $bif[1];
			}
			$zm->setFrom( $senderEmail, $senderFullName );
		}
		foreach( $message->getPrimaryRecipients() as $rc ) {
			$zm->addTo( $rc->getEmailAddress(), $rc->getFullName() );
		}
		foreach( $message->getCcRecipients() as $rc ) {
			$zm->addCc( $rc->getEmailAddress(), $rc->getFullName() );
		}
		foreach( $message->getBccRecipients() as $rc ) {
			$zm->addBcc( $rc->getEmailAddress(), $rc->getFullName() );
		}
		if( ($s = $message->getSubject() ) !== null ) $zm->setSubject($s);
		if( ($t = $message->getBodyText()) !== null ) $zm->setBodyText($t);
		if( ($h = $message->getBodyHtml()) !== null ) $zm->setBodyHtml($h);
		return $zm;
	}
	
	public function enqueue( $message ) {
		$zm = $this->togToZendMessage( $message );
		$this->zendTransport->send( $zm );
	}
	
	public function flush() {
		// No-op; messages are flushed immediately
	}
}
