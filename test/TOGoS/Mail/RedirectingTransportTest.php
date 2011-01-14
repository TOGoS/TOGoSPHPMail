<?php

class TOGoS_Mail_RedirectingTransportTest extends PHPUnit_Framework_TestCase
{
	public function testMails() {
		$AMX = new TOGoS_Mail_ArrayTransport();
		
		$MX = new TOGoS_Mail_RedirectingTransport( $AMX, 'Jack McTester <jack.mctest@testco.com>' );
		
		$mess = new TOGoS_Mail_BasicMessage();
		$mess->setSender( "joe.schmoe@example.com", "Joe Schmoe" );
		$mess->addPrimaryRecipient( "george.laforge@example.com" );
		$mess->addPrimaryRecipient( '"George M" george.lamorge@example.com' );
		$mess->addCcRecipient( "Henry <henry@henry.com>" );
		$mess->addBccRecipient( "Superman@krypton.com" );
		$mess->setSubject( "Subj" );
		$mess->setBodyText( "Watappp" );
		$MX->enqueue($mess);
		
		$mess = new TOGoS_Mail_BasicMessage();
		$mess->setSender( "joe.schmoe@example.com", "Joe Schmoe" );
		$mess->addPrimaryRecipient( "george.laforge@example.com" );
		$mess->addPrimaryRecipient( '"George M" george.lamorge@example.com' );
		$mess->addCcRecipient( "Henry <henry@henry.com>" );
		$mess->addBccRecipient( "Superman@krypton.com" );
		$mess->setSubject( "Subj" );
		$mess->setBodyHtml( "<html><body><p>wat ap</p></body></html>" );
		$MX->enqueue($mess);
		
		$mess = new TOGoS_Mail_BasicMessage();
		$mess->setSender( "joe.schmoe@example.com", "Joe Schmoe" );
		$mess->addPrimaryRecipient( "george.laforge@example.com" );
		$mess->setSubject( "Subj" );
		$mess->setBodyText( "Watappp" );
		$mess->setBodyHtml( "<html><body><p>wat ap</p></body></html>" );
		$MX->enqueue($mess);
		
		$MX->flush();
		
		$this->assertEquals( 3, count($AMX->messages) );
		
		$primaryRecipients = $AMX->messages[0]->getPrimaryRecipients();
		$sender = $AMX->messages[0]->getSender();
		$this->assertEquals( 1, count($primaryRecipients) );
		$this->assertNotNull( $sender );
		$this->assertEquals( 'joe.schmoe@example.com', $sender->getEmailAddress() );
		$this->assertEquals( 'Joe Schmoe', $sender->getFullName() );
		$this->assertEquals( 'Jack McTester', $primaryRecipients['jack.mctest@testco.com']->getFullName() );
		$this->assertEquals( 'jack.mctest@testco.com', $primaryRecipients['jack.mctest@testco.com']->getEmailAddress() );
		$this->assertSame( array(), $AMX->messages[0]->getCcRecipients() );
		$this->assertSame( array(), $AMX->messages[0]->getBccRecipients() );
		$this->assertSame( "This message has been redirected to you for testing.\n".
						   "Original To  : george.laforge@example.com, \"George M\" <george.lamorge@example.com>\n".
						   "Original CC  : \"Henry\" <henry@henry.com>\n".
						   "Original BCC : Superman@krypton.com\n".
						   "\n".
						   "Watappp",
						   $AMX->messages[0]->getBodyText() );
		$this->assertNull( $AMX->messages[0]->getBodyHtml() );
		
		$this->assertNull( $AMX->messages[1]->getBodyText() );
		$this->assertSame( "<html><body>".
						   "<p>This message has been redirected to you for testing.\n".
						   "<br />Original To  : george.laforge@example.com, \"George M\" &lt;george.lamorge@example.com&gt;\n".
						   "<br />Original CC  : \"Henry\" &lt;henry@henry.com&gt;\n".
						   "<br />Original BCC : Superman@krypton.com</p>\n".
						   "\n".
						   "<hr />\n".
						   "\n".
						   "<p>wat ap</p></body></html>",
						   $AMX->messages[1]->getBodyHtml() );

		$this->assertSame( "This message has been redirected to you for testing.\n".
						   "Original To  : george.laforge@example.com\n".
						   "\n".
						   "Watappp",
						   $AMX->messages[2]->getBodyText() );
		$this->assertSame( "<html><body>".
						   "<p>This message has been redirected to you for testing.\n".
						   "<br />Original To  : george.laforge@example.com</p>\n".
						   "\n".
						   "<hr />\n".
						   "\n".
						   "<p>wat ap</p></body></html>",
						   $AMX->messages[2]->getBodyHtml() );
	}
}
