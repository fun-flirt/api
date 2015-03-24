<?php
/**
 * Fun-flirt Register SDK
 *
 * @author  		Christian Ibarguen R,   <rodriguez@nextlevel-sl.es>
 * @version			1
 * @date 			19/03/2015
 * Docummentation 	http://fun-flirt.com/documentation
 */
class Fun_flir
{
	const API_URL 			= 'http://fun-flirt.com/api/signup';
	protected $apiPublicKey;

	public function __construct($apiPublicKey) {
		$this->apiPublicKey 	= $apiPublicKey;
	}

    /**
	 * Get Api Publick Key
	 *
	 * This function return the Api Publick Key
	 *
	 * @return	string
	 */
	public function getApiKey() { 
	    return $this->apiPublicKey;
    }

	/**
	 * Call url
	 *
	 * This function ask to the sever for
	 *
	 * @param array
	 * @param boolean
	 * @return string
	 */
	private function callUrl($params, $isPost) {
		# Initialize cURL
	    $curlHandle 			= curl_init();

	    # Configure cURL request
	    curl_setopt($curlHandle, CURLOPT_URL, self::API_URL);
	    # Configure POST
	    curl_setopt($curlHandle, CURLOPT_POST, 1);
	    curl_setopt($curlHandle, CURLOPT_POSTFIELDS, http_build_query($params));

		# Make sure we can access the response when we execute the call
		curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curlHandle, CURLOPT_REFERER, 'http' . (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on" ? 's' : '') . '://' . $_SERVER['HTTP_HOST']);

	    # Execute the API call
	    $jsonEncodedApiResponse  = curl_exec($curlHandle);

	    # Ensure HTTP call was successful
	    if($jsonEncodedApiResponse === FALSE) {
	    	error_log( 'Fun_flir Error: "' . curl_error($curlHandle) . '" - Code: ' . curl_errno($curlHandle) );
			return FALSE;
	    }
	    # Clean up the resource now that we're done with cURL
	    curl_close($curlHandle);

	    # Decode the response from a JSON string to a PHP associative array
	    $data = json_decode($jsonEncodedApiResponse, TRUE);

	    # Make sure we got back a well-formed JSON string and that there were no errors when decoding it
	    $jsonErrorCode = json_last_error();
	    if($jsonErrorCode !== JSON_ERROR_NONE) {
	        error_log( 'Fun_flir API response not well-formed (json error code: ' . $jsonErrorCode . ')' );
			return FALSE;
	    }

	    if(isset($data['errors'])) {
	        # An error occurred
	        error_log( 'Fun_flir API call failed (' . $jsonEncodedApiResponse . ')' );
	        $data['response']['status'] = FALSE;
	        return $data;
	    } else {
	        #  No errors encountered
	        $data['response']['status'] = TRUE;
	        return $data;
	    }

		return FALSE;
	}

	/**
	 * Create an user
	 *
	 * This function create an user in
	 * fun-flirt.com via API
	 *
	 * @param	string 	username
	 * @param	string 	email
	 * @param	integer gender
	 * @param	string 	password
	 * @param	string 	pcid
	 * @param	string 	req
	 * @return	string
	 */

	public function createUser( $username, $email, $gender, $password, $pcid = NULL, $req = NULL ) {
	    $params 	= array(
	        'public_key' 	=> $this->apiPublicKey,
	        'username' 		=> filter_var( $username, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_SANITIZE_STRING ),
	        'email' 		=> filter_var( $email, 	  FILTER_SANITIZE_EMAIL ),
	        'gender' 		=> filter_var( $gender,   FILTER_SANITIZE_NUMBER_INT ),
	        'password' 		=> filter_var( $password, FILTER_SANITIZE_STRING )
	    );

	    /* Optional tracking params */
	    if ($pcid) {
	    	$params['pcid'] 	= filter_var( $pcid, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_SANITIZE_STRING );
	    }
	    if ($req) {
	    	$params['req'] 		= filter_var( $req, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_SANITIZE_STRING );
	    }

		return $this->callUrl( $params, TRUE );
	}
}