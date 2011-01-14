<?php

/**
 * Global configuration point for mail-related settings.
 *
 * It's generally better to use dependency-injection for these sorts
 * of things, but for system initialization integrating with legacy
 * code, you may use this as a simple way to grab a transport.
 *
 * Configuration:
 *   TOGoS_Mail_Config::getInstance()->transport = new My_Cool_MailTransport();
 *
 * Usage:
 *   $MX = TOGoS_Mail_Config::getInstance()->getTransport();
 *   $MX->enqueue( $myCoolMailMessage );
 *   $MX->flush();
 */
class TOGoS_Mail_Config
{
	public static $instance;
	
	public static function getInstance() {
		if( self::$instance === null ) self::$instance = new self();
		return self::$instance;
	}
	
	
	public $transport;
	
	public function getTransport() {
		if( $this->transport === null ) {
			// Default is to use the mail() function...
			$this->transport = new TOGoS_Mail_SendmailTransport();
		}
		return $this->transport;
	}
}
