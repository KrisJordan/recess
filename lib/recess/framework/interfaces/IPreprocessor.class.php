<?php
/**
 * The IPreprocessor interface expects a raw request from the environment and 
 * transforms it into a more appropriate request before the request
 * is handed off to a Controller. Examples of the types of tasks a
 * preprocessor may handle:
 * 	- Extract HTTP method from POST to enable REST operations from web browsers
 *  - Extract the file format from the URI (i.e. /pages/1.json => URI becomes /pages/1, format becomes json)
 *  - Append routing metadata from URI
 * 
 * @author Kris Jordan
 */
interface IPreprocessor {
	/**
	 * Used to pre-process request data.
	 * This may involve extracting information and transforming values. 
	 * For example, Transforming the HTTP method from POST to PUT based on a POSTed field.
	 * 
	 * @abstract
	 * @param	Request The Request to preprocess.
	 * @return	Request The preprocessed Request.
	 */
	public function process(Request &$request);
}
?>