<?php

/**
 * Creates an OAuth request to Twitter and executes it
 * For licensing and examples: 
 *
 * @see https://github.com/avalanche-development/obcento
 *
 * @author jacobemerick (http://home.jacobemerick.com/)
 * @version 1.0 (2013-03-18)
 */

class ObcentoTwitterRequest
{

	/**
	 * $consumer_key from dev.twitter.com
	 */
	private $consumer_key;

	/**
	 * $consumer_secret from dev.twitter.com
	 */
	private $consumer_secret;

	/**
	 * $access_token from dev.twitter.com
	 */
	private $access_token;

	/**
	 * $access_token_secret from dev.twitter.com
	 */
	private $access_token_secret;

	/**
	 * $RESOURCE_URL_PATTERN url pattern for the request
	 */
	private static $RESOURCE_URL_PATTERN = 'https://api.twitter.com/1.1/%s.json';

	/**
	 * Construct with most of the parameters needed to execute the final CURL
	 * Sets up the full array for the OAuth request
	 *
	 * @param	string	$consumer_key			from dev.twitter.com
	 * @param	string	$consumer_secret		from dev.twitter.com
	 * @param	string	$access_token			from dev.twitter.com
	 * @param	string	$access_token_secret	from dev.twitter.com
	 */
	public function __construct($consumer_key, $consumer_secret, $access_token, $access_token_secret)
	{
		$this->consumer_key = $consumer_key;
		$this->consumer_secret = $consumer_secret;
		$this->access_token = $access_token;
		$this->access_token_secret = $access_token_secret;
	}

	/**
	 * Actual execution of the curl request
	 * Pulls all of the pieces from methods
	 * Final return is the basic json string, no frills
	 *
	 * @param	string	$resource	string path that defines the method
	 * @param	array	$parameters	key->value list of all parameters for the request
	 * @return	string	JSON data from Twitter API
	 */
	public function execute($resource, $parameters = array())
	{
		$resource_url = $this->get_resource_url($resource);
		
		$curl_request = curl_init();
		curl_setopt($curl_request, CURLOPT_HTTPHEADER, $this->get_header($resource_url, $parameters));
		curl_setopt($curl_request, CURLOPT_HEADER, false);
		curl_setopt($curl_request, CURLOPT_URL, $resource_url . $this->get_query_string($parameters));
		curl_setopt($curl_request, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl_request, CURLOPT_SSL_VERIFYPEER, false);
		$json = curl_exec($curl_request);
		curl_close($curl_request);
		
		return $json;
	}

	/**
	 * Format and return the resource_url based on the twitter method
	 *
	 * @param	string	$resource	path (twitter method) you want to use
	 * @return	string				full url for curl endpoint
	 */
	private function get_resource_url($resource)
	{
		return sprintf(self::$RESOURCE_URL_PATTERN, $resource);
	}

	/**
	 * Compiles everything for the OAuth header request
	 * Pulls signature and other parameters for OAuth part of the CURL request
	 *
	 * @param	string	$resource_url	url endpoint for curl request
	 * @param	array	$parameters		parameters passed in for specific api request
	 * @return	array	compiled header needed for the oauth request
	 */
	private function get_header($resource_url, $parameters)
	{
		$parameter_array = $this->get_parameter_array($parameters);
		$parameter_array['oauth_signature'] = $this->get_signature($resource_url, $parameter_array);
		ksort($parameter_array);
		
		$header = $this->join_array($parameter_array, '%s="%s"', ', ');
		
		return array("Authorization: Oauth {$header}", 'Expect:');
	}

	/**
	 * Form the full array of key->values for the signature and OAuth header
	 *
	 * @param	array	$parameters	key->value list of passed in parameters
	 * @return	array	full key->value list of parameters with authentication
	 */
	private function get_parameter_array($parameters)
	{
		$parameter_array = $parameters;
		$timestamp = time();
		
		// This really shouldn't overwrite any keys being passed in
		// If it is, then you're doing some advanced stuff and should be aware of it
		$parameter_array['oauth_consumer_key'] = $this->consumer_key;
		$parameter_array['oauth_nonce'] = $timestamp;
		$parameter_array['oauth_signature_method'] = 'HMAC-SHA1';
		$parameter_array['oauth_timestamp'] = $timestamp;
		$parameter_array['oauth_token'] = $this->access_token;
		$parameter_array['oauth_version'] = '1.0';
		
		ksort($parameter_array);
		
		return $parameter_array;
	}

	/**
	 * Pulls together the signature for the OAuth request
	 * Relies on several helper methods to fetch base and key before the hash
	 *
	 * @param	string	$resource_url		url endpoint for curl request
	 * @param	array	$parameter_array	full parameter list for request
	 * @return	string	signature for the oauth request
	 */
	private function get_signature($resource_url, $parameter_array)
	{
		$signature = hash_hmac(
			'sha1',
			$this->get_signature_base($resource_url, $parameter_array),
			$this->get_signature_key(),
			true);
		
		$signature = base64_encode($signature);
		$signature = rawurlencode($signature);
		
		return $signature;
	}

	/**
	 * Helper function for get_signature
	 * The base of the OAuth signature (pre-hash) is dependant on the parameters and path
	 *
	 * @param	string	$resource_url		url endpoint for curl request
	 * @param	array	$parameter_array	full parameter list for request
	 * @return	string	signature base for the encoded signature
	 */
	private function get_signature_base($resource_url, $parameter_array)
	{
		$parameter_string = $this->join_array($parameter_array, '%s=%s', '&');
		
		$base = '';
		$base .= 'GET';
		$base .= '&';
		$base .= rawurlencode($resource_url);
		$base .= '&';
		$base .= rawurlencode($parameter_string);
		
		return $base;
	}

	/**
	 * Helper function for get_signature
	 * Uses the secret parts of the handshake as part of the hashing algorithm
	 *
	 * @return	string	oauth key for the encoded signature
	 */
	private function get_signature_key()
	{
		$key = '';
		$key .= rawurlencode($this->consumer_secret);
		$key .= '&';
		$key .= rawurlencode($this->access_token_secret);
		
		return $key;
	}

	/**
	 * Simple function to create the query string on the curl endpoint
	 *
	 * @param	array	$parameters	key->value list passed in by the user
	 * @return	string	query string ready to be appended on the end of a url
	 */
	private function get_query_string($parameters)
	{
		if(count($parameters) < 1)
			return '';
		
		ksort($parameters);
		return '?' . $this->join_array($parameters, '%s=%s', '&');
	}

	/**
	 * Helper function to implode a array for the OAuth header processing
	 * Used by both get_signature_base and get_header
	 *
	 * @param	array	$array		parent array that needs to be imploded
	 * @param	string	$pattern	pattern for key->value association
	 * @param	string	$glue		glue to join each key->value association
	 *
	 * @return	string				imploded array
	 */
	private function join_array($array, $pattern, $glue)
	{
		$wrapped_array = array();
		foreach($array as $key => $value)
		{
			$wrapped_array[] = sprintf($pattern, $key, $value);
		}
		
		return implode($glue, $wrapped_array);
	}

}