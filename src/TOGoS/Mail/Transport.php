<?php

interface TOGoS_Mail_Transport extends TOGoS_Flushable
{
	/**
	 * Eneuque a TOGoS_Mail_Message to be sent.
	 * It is not specified whether the message is sent immediately or queued.
	 * Call flush() after enqueueing to ensure messages are sent.
	 * 
	 * @param TOGoS_Mail_Message $message
	 */
	public function enqueue( $message );
}
