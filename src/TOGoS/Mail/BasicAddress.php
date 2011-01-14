<?php

/**
 * Implements TOGoS_Mail_Address in a very straightforward way.
 * E-mail address and full name must be given to constructor.
 */
class TOGoS_Mail_BasicAddress implements TOGoS_Mail_Address
{
	protected $emailAddress;
	protected $fullName;
	
	public function __construct( $emailAddress, $fullName=null ) {
		$this->emailAddress = $emailAddress;
		$this->fullName     = $fullName;
	}
	
	public function getEmailAddress() {  return $this->emailAddress;  }
	public function getFullName()     {  return $this->fullName;      }
}
