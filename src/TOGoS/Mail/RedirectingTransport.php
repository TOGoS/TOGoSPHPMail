<?php

class TOGoS_Mail_RedirectingTransport implements TOGoS_Mail_Transport
{
	protected $next;
	protected $recipients;
	
	public function __construct( $next, $recipient ) {
		$this->next = $next;
		$this->recipient = $recipient;
	}
	
	protected function rewrite( $oMess ) {
		// 'o' stands for 'original'
		$mess = new TOGoS_Mail_BasicMessage();
		if( ($sender = $oMess->getSender()) !== null ) {
			$mess->setSender( $sender );
		}
		$mess->addPrimaryRecipient( $this->recipient );
		$mess->setSubject( $oMess->getSubject() );
		
		if( ($oText = $oMess->getBodyText()) !== null ) {
			$text = "This message has been redirected to you for testing.\n";
			if( count($oTo = $oMess->getPrimaryRecipients()) > 0 ) {
				$text .= "Original To  : ".TOGoS_Mail_Util::formatAddressList($oTo)."\n";
			}
			if( count($oCc = $oMess->getCcRecipients()) > 0 ) {
				$text .= "Original CC  : ".TOGoS_Mail_Util::formatAddressList($oCc)."\n";
			}
			if( count($oBcc = $oMess->getBccRecipients()) > 0 ) {
				$text .= "Original BCC : ".TOGoS_Mail_Util::formatAddressList($oBcc)."\n";
			}
			$text .= "\n";
			$text .= $oText;
			$mess->setBodyText( $text );
		}
		
		if( ($oHtml = $oMess->getBodyHtml()) !== null ) {
			if( preg_match( '{^(.*<body[^>]*>)(.*)(</body>.*)$}s', $oHtml, $bif ) ) {
				$oHead = $bif[1];
				$oBody = $bif[2];
				$oFoot = $bif[3];
			} else {
				$oHead = '';
				$oBody = $oHtml;
				$oFoot = '';
			}
			
			$rHeader = "<p>This message has been redirected to you for testing.";
			if( count($oTo = $oMess->getPrimaryRecipients()) > 0 ) {
				$rHeader .= "\n<br />Original To  : ".htmlspecialchars(TOGoS_Mail_Util::formatAddressList($oTo),ENT_NOQUOTES);
			}
			if( count($oCc = $oMess->getCcRecipients()) > 0 ) {
				$rHeader .= "\n<br />Original CC  : ".htmlspecialchars(TOGoS_Mail_Util::formatAddressList($oCc),ENT_NOQUOTES);
			}
			if( count($oBcc = $oMess->getBccRecipients()) > 0 ) {
				$rHeader .= "\n<br />Original BCC : ".htmlspecialchars(TOGoS_Mail_Util::formatAddressList($oBcc),ENT_NOQUOTES);
			}
			$rHeader .= "</p>\n\n<hr />\n\n";
			
			$mess->setBodyHtml( $oHead.$rHeader.$oBody.$oFoot );
		}
		
		return $mess;
	}
	
	public function enqueue( $mess ) {
		$this->next->enqueue( $this->rewrite($mess) );
	}
	
	public function flush() {
		$this->next->flush();
	}
}
