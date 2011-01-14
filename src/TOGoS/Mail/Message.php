<?php

/** 
 * Represents a standard e-mail message (text and/or html content, no
 * attachments) and provides all relevant information.
 *
 * Almost but not quite compatible with Zend_Mail (this interface is
 * less coupled with the transport; e.g. getSubject() must return the
 * subject in plain text, not some arbitrary encoding useful only to
 * MIME-formatted messages).
 */
interface TOGoS_Mail_Message
{
	/**
	 * @return TOGoS_Mail_Address identifying the sender
	 */
	public function getSender();
	
	/**
	 * @return iterable of TOGoS_Mail_Addresses that indicate primary (to: field) recpients
	 *   Must contain at least one address.
	 */
	public function getPrimaryRecipients();
	
	/**
	 * @return iterable of TOGoS_Mail_Addresses that this message should be CC'd to
	 *   May be empty, but may not be null.
	 */
	public function getCcRecipients();
	
	/**
	 * @return iterable of TOGoS_Mail_Addresses that this message should be BCC'd to.
	 *   May be empty, but may not be null.
	 */
	public function getBccRecipients();
	
	/**
	 * @return string|null plain-text e-mail subject; may be null
	 */
	public function getSubject();
	
	/**
	 * @return string|null plain text e-mail content; may be null for
	 *   HTML-only mail
	 */
	public function getBodyText();
	
	/**
	 * @return string|null HTML-formatted e-mail content; may be null for
	 *   plain text-only e-mail
	 */
	public function getBodyHtml();
}
