<?php

interface TOGoS_Flushable
{
	// Commit any pending changes or actions so that they are not lost
	// when PHP exits or this object is destroyed:
	public function flush();
}
