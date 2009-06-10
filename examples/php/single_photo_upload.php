<?php
/*
 * The MIT License
 * 
 * Copyright (c) 2007-2009 Expono AS
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */


/**
 * Expono (http://www.expono.com) API Upload Example
 * 
 * This code requires following libraries to function properly
 * OAuth basic PHP library - http://oauth.googlecode.com/svn/code/php/
 * PEAR HTTP::Request http://pear.php.net/package/http_request/redirected
 */
require_once('HTTP/Request.php');
require_once('OAuth.php');

$consumer_key 		= 'INSERT YOUR CONSUMER KEY';
$consumer_secret 	= 'INSERT YOUR CONSUMER SECRET';
$token_key 			= 'INSERT YOUR TOKEN KEY';
$token_secret		= 'INSERT YOUR TOKEN SECRET'; 

$request_url		= 'http://www.expono.com/go/api/upload';

//Fill parameters
$request_params		= array(
	//'photo'			=> '', excluded from signature
	'title'			=> '',
	'description'	=> '',
	'tags'			=> '', //comma separated list for multiple tags
	'is_public'		=> '', //1 for on 0 for off
	'is_friend'		=> '',
	'is_family'		=> '',
	'safety_level'	=> '',
);

//Create and sign the request
$consumer 	= new OAuthConsumer($consumer_key, $consumer_secret);
$token		= new OAuthToken($token_key, $token_secret);

$req = OAuthRequest::from_consumer_and_token($consumer, $token, 'POST', $request_url, $request_params);
$req->sign_request(new OAuthSignatureMethod_HMAC_SHA1(), $consumer, $token);

//Upload file
uploadFile($req->to_url(), 'Enter Full Path to image to upload e.g /home/martin/image.jpg');

function uploadFile($url, $file) {
	$req =& new HTTP_Request($url);
	$req->setMethod(HTTP_REQUEST_METHOD_POST);
	$req->addHeader('Content-Type', 'multipart/form-data;charset=UTF-8');
	$result = $req->addFile('photo', $file);
	if (PEAR::isError($result)) {
	    echo $result->getMessage();
	} else {
	    $response = $req->sendRequest();	
	    if (PEAR::isError($response)) {
	        echo $response->getMessage();
	    } else {
	        echo $req->getResponseBody();
	    }
	}
}
?>