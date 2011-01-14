<?php

class TOGoS_Mail_MailTest extends PHPUnit_Framework_TestCase
{
	public function testGetDefaultTransport() {
		$MX = TOGoS_Mail_Config::getInstance()->getTransport();
	}
	
	public function testSendMessage() {
		$MX = new TOGoS_Mail_ArrayTransport();
		
		$this->assertEquals( 0, count($MX->messages) );		
		
		$mess = new TOGoS_Mail_BasicMessage();
		$mess->setSender( "joe.schmoe@example.com", "Joe Schmoe" );
		$mess->addPrimaryRecipient( "george.laforge@example.com" );
		$mess->addBccRecipient( "Superman@krypton.com" );
		$mess->setSubject( "Subj" );
		$mess->setBodyText( "Watappp" );
		$mess->setBodyHtml( "<html><body><p>wat ap</p></body></html>" );
		$MX->enqueue($mess);
		$MX->flush();
		
		$this->assertEquals( 1, count($MX->messages) );
		
		foreach( $MX->messages as $m ) {
			$this->assertEquals( "Joe Schmoe", $m->getSender()->getFullName() );
			$this->assertEquals( "joe.schmoe@example.com", $m->getSender()->getEmailAddress() );
			$this->assertEquals( array("george.laforge@example.com"=>new TOGoS_Mail_BasicAddress("george.laforge@example.com")),
								 $m->getPrimaryRecipients() );
			$this->assertEquals( array("Superman@krypton.com"=>new TOGoS_Mail_BasicAddress("Superman@krypton.com")),
								 $m->getBccRecipients() );
			$this->assertEquals( "Subj", $m->getSubject() );
			$this->assertEquals( "Watappp", $m->getBodyText() );
			$this->assertEquals( "<html><body><p>wat ap</p></body></html>", $m->getBodyHtml() );
		}
	}
	
	public function testToAddress() {
		$jsAddy1 = new TOGoS_Mail_BasicAddress("joe.schmoe@example.com");
		$this->assertEquals( $jsAddy1, TOGoS_Mail_Util::toAddress('joe.schmoe@example.com') );
		$this->assertEquals( $jsAddy1, $jsAddy1 );
		
		$jsAddy2 = new TOGoS_Mail_BasicAddress("joe.schmoe@example.com","Joe Schmoe");
		$this->assertEquals( $jsAddy2, $jsAddy2 );
		$this->assertEquals( $jsAddy2, TOGoS_Mail_Util::toAddress("joe.schmoe@example.com","Joe Schmoe") );
		$this->assertEquals( $jsAddy2, TOGoS_Mail_Util::toAddress('Joe Schmoe <joe.schmoe@example.com>') );
		$this->assertEquals( $jsAddy2, TOGoS_Mail_Util::toAddress('"Joe Schmoe" joe.schmoe@example.com') );
	}
}
