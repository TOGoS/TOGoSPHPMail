<?php

/**
 * TOGoS_Mail_Transport that 'enqueues' messages by adding them to an
 * array.  May be helpful for testing/debugging.
 */
class TOGoS_Mail_ArrayTransport implements TOGoS_Mail_Transport
{
	/**
	 * List of messages that have been enqueued.
	 */
	public $messages;
	
	public function enqueue( $message ) {
		$this->messages[] = $message;
	}
	
	public function flush() {}
}
