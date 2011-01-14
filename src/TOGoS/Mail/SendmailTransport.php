<?php

class TOGoS_Mail_SendmailTransport implements TOGoS_Mail_Transport
{
	protected function formatAddress( $addy ) {
		// @todo: Note that this doesn't quite comply with the spec;
		// need to properly escape recipients, subject, etc.
		return TOGoS_Mail_Util::formatAddress($addy);
	}
	
	protected function formatAddressList( $addyList ) {
		$faddyList = array();
		foreach( $addyList as $addy ) {
			$faddyList[] = $this->formatAddress($addy);
		}
		return implode(', ',$faddyList);
	}
	
	public function enqueue( $message ) {
		$allRecipients = array_merge($message->getPrimaryRecipients(),
									 $message->getCcRecipients(),
									 $message->getBccRecipients());
		
		$headers = '';
		$headers .= "From: ".$this->formatAddress($message->getSender())."\r\n";
		if( count($message->getPrimaryRecipients()) > 0 ) {
			$headers .= "To: ".$this->formatAddressList($message->getPrimaryRecipients())."\r\n";
		}
		if( count($message->getCcRecipients()) > 0 ) {
			$headers .= "Cc: ".$this->formatAddressList($message->getCcRecipients())."\r\n";
		}
		if( count($message->getBccRecipients()) > 0 ) {
			$headers .= "Bcc: ".$this->formatAddressList($message->getBccRecipients())."\r\n";
		}
		
		// TODO: support multipart if both text and HTML
		if( $text = $message->getBodyHtml() ) {
			$headers .= "Content-Type: text/html; charset=utf-8\r\n";
		} else if( $text = $message->getBodyText() ) {
			$headers .= "Content-Type: text/plain; charset=utf-8\r\n";			
		} else {
			$text = '';
		}
		
		mail( $this->formatAddressList($allRecipients),
			  $message->getSubject(),
			  $text,
			  $headers );
			  
	}
	
	public function flush() { }
}
