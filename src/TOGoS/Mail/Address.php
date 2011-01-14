<?php

interface TOGoS_Mail_Address
{
	/**
	 * @return string string of the form person@host
	 *   See RFC 5322
	 */
	public function getEmailAddress();
	
	/**
	 * @return string|null the plain-text full name of the recipient.
	 */
	public function getFullName();
}
