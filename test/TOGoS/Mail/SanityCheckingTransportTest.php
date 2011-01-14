<?php

class TOGoS_Mail_SanityCheckingTransportTest extends PHPUnit_Framework_TestCase
{
	public function testValidateAddress() {
		$MX = new TOGoS_Mail_SanityCheckingTransport(null);
		
		$err = array();
		$this->assertFalse( $MX->validateAddress('ASDAs l', 'Test', $err) );
		$this->assertFalse( $MX->validateAddress('hug a lug@doop.com', 'Test', $err) );
		$this->assertFalse( $MX->validateAddress('a@b@c', 'Test', $err) );
		$this->assertFalse( $MX->validateAddress('a@qqq', 'Test', $err) );
		$this->assertTrue(  $MX->validateAddress('a@qq.qq', 'Test', $err) );
	}
	
	public function testValidateMessage() {
		$MX = new TOGoS_Mail_SanityCheckingTransport(null);
		
		$err = array();
		
		$mess = new TOGoS_Mail_BasicMessage();
		$this->assertFalse( $MX->validate($mess,$err) );
		$mess->setSender( 'Jon Q <jonq@jon.net>' );
		$this->assertFalse( $MX->validate($mess,$err) );
		$mess->addPrimaryRecipient( 'Jon H <jonh@jon.net>' );
		$this->assertTrue( $MX->validate($mess,$err) );
		$mess->addPrimaryRecipient( 'Jon Bad Addy <jonh@thisaddressisinvalid>' );
		$this->assertFalse( $MX->validate($mess,$err) );
		
		$mess = new TOGoS_Mail_BasicMessage();
		$mess->setSender( 'Jon Q <jonq@jon.net>' );
		$mess->addPrimaryRecipient( 'Jon H <jonh@jon.net>' );
		$this->assertTrue( $MX->validate($mess,$err) );
		$mess->addCcRecipient( 'BBBXXX>' );
		$this->assertFalse( $MX->validate($mess,$err) );
	}
}
