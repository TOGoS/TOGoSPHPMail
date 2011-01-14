<?php

class TOGoS_Mail_SanityCheckingTransport implements TOGoS_Mail_Transport
{
	// Different ways to deal with bad messages:
	const MODE_EXCEPTION = 'exception';
	// Ignore them
	const MODE_NULL     = 'null';
	// Forward to another transport...
	const MODE_FORWARD = 'forward';
	
	protected $next;
	protected $mode;
	protected $errorNext = null;
	
	public function __construct( $next, $mode=self::MODE_EXCEPTION ) {
		$this->next = $next;
		if( is_object($mode) and $mode instanceof TOGoS_Mail_Transport ) {
			$this->mode = self::MODE_FORWARD;
			$this->errorNext = $mode;
		} else {
			$this->mode = $mode;
		}
	}
	
	public function validateAddress( $address, $usage, &$errorDest ) {
		if( is_object($address) ) $address = $address->getEmailAddress();
		
		// Borrowed from http://www.regular-expressions.info/email.html :
		if( preg_match( '/^[A-Z0-9\._%+\-]+@[A-Z0-9\.\-]+\.[A-Z]{2,4}$/i', $address ) ) {
			return true;
		} else {
			$errorDest[] = "$usage e-mail address '$address' appears invalid.";
			return false;
		}
	}
	
	public function validateAddresses( $addresses, $usage, &$errorDest ) {
		$ok = true;
		foreach( $addresses as $addy ) $ok = $ok && $this->validateAddress($addy, $usage, $errorDest);
		return $ok;
	}
	
	/**
	 * @return true if message is okay, false otherwise
	 */
	public function validate( $message, &$errorDest ) {
		$ok = true;
		if( $message->getSender() === null ) {
			$ok = false;
			$errorDest[] = "Sender is missing";
		} else {
			$ok = $ok && $this->validateAddress( $message->getSender(), 'Sender', $errorDest );
		}
		if( count($message->getPrimaryRecipients()) == 0 ) {
			$ok = false;
			$errorDest[] = "No primary recipients";
		} else {
			$ok = $ok && $this->validateAddresses( $message->getPrimaryRecipients(), 'Primary recipient', $errorDest );
		}
		$ok = $ok && $this->validateAddresses( $message->getCcRecipients(),    'CC recipient', $errorDest );
		$ok = $ok && $this->validateAddresses( $message->getBccRecipients(), 'BCC recipient', $errorDest );
		return $ok;
	}
	
	protected function handleError( $message, $errors ) {
		switch( $this->mode ) {
		case( self::MODE_EXCEPTION ):
			throw new Exception( "Invalid e-mail message:\n  ".implode("\n  ",$errors)."\n\n".TOGoS_Mail_Util::formatMessage($message) );
		case( self::MODE_NULL ):
			return;
		case( self::FORWARD ):
			// TODO: attach errors somehow?
			$this->errorNext->enqueue( $message );
		}
	}

	public function enqueue( $message ) {
		$errors = array();
		if( $this->validate( $message, $errors ) ) {
			$this->next->enqueue( $message );
		} else {
			$this->handleError( $message, $errors );
		}
	}
	
	public function flush() {
		$this->next->flush();
		if( $this->errorNext !== null ) $this->errorNext->flush();
	}
}
