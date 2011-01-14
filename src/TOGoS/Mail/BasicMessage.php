<?php

class TOGoS_Mail_BasicMessage implements TOGoS_Mail_Message
{
	/** Sender Address */
	protected $sender;
	/** Array of primary recipient Addresses, keyed by e-mail address */
	protected $primaryRecipients = array();
	/** Array of CC recipient addresses, keyed by e-mail address */
	protected $ccRecipients = array();
	/** Array of BCC recipient addresses, keyed by e-mail address */
	protected $bccRecipients = array();
	/** Subject text */
	protected $subject;
	/** Plain text version of content */
	protected $bodyText;
	/** HTML version of content */
	protected $bodyHtml;
	
	//// Initialization ////

	protected function addAddress( &$toList, $addy, $name ) {
		$addy = TOGoS_Mail_Util::toAddress($addy,$name);
		$toList[$addy->getEmailAddress()] = $addy;
	}
	
	public function addPrimaryRecipient($z, $name=null) {
		$this->addAddress( $this->primaryRecipients, $z, $name );
	}
	public function addCcRecipient($z, $name=null) {
		$this->addAddress( $this->ccRecipients, $z, $name );
	}
	public function addBccRecipient($z, $name=null) {
		$this->addAddress( $this->bccRecipients, $z, $name );
	}
	public function setSender( $addy, $name=null ) {
		$this->sender = TOGoS_Mail_Util::toAddress($addy,$name);
	}
	
	public function setSubject($z)  {  $this->subject   = $z;  }
	public function setBodyText($z) {  $this->bodyText  = $z;  }
	public function setBodyHtml($z) {  $this->bodyHtml  = $z;  }
	
	//// Get stuff ////
	
	public function getSender()            {  return $this->sender;             }
	public function getPrimaryRecipients() {  return $this->primaryRecipients;  }
	public function getCcRecipients()      {  return $this->ccRecipients;       }
	public function getBccRecipients()     {  return $this->bccRecipients;      }
	public function getSubject()           {  return $this->subject;            }
	public function getBodyText()          {  return $this->bodyText;           }
	public function getBodyHtml()          {  return $this->bodyHtml;           }
}
