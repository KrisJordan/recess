<?php

abstract class Preprocessor {
	/**
	 * Used to pre-process a request.
	 * This may involve extracting information and transforming values. 
	 * For example, Transforming the HTTP method from POST to PUT based on a POSTed field.
	 * 
	 * @abstract
	 * @param	Request The Request to process.
	 * @return	Request The processed Request.
	 */
	public abstract function process(Request $request);
}

?>