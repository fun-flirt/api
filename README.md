Fun-flirt Basic SDK for PHP & jQuery
====================

[![Latest Stable Version](http://img.shields.io/badge/Latest%20Stable-0.1-green.svg)]


This repository contains examples that allows you to access Fun-flirt
Platform from your PHP or JavaScript app.


Usage
-----

Minimal example:

```php
<?php

require 'fun-flirt.php';

# Instatiate the API and send the API Key
$funflirt 	= new Fun_flir('API_PUBLIC_KEY');

/**
 * string 			username
 * string 			email
 * integer 			gender
 * string 			password
 * optional string	pcid
 * optional string	req
 */
$reply 		= $funflirt->createUser('testuser', 'test@example.com', '1', '123456', '87654321', '87654321');

if($reply['response']['status'] === TRUE) {
	echo $reply['code'] . ', ' . $reply['message'];
} else {
	foreach ($reply['errors'] as $error) {
		printf('<strong>Code:</strong> %s, <strong>Message:</strong>%s<br>', $error['code'], $error['message']);
	}
}

```

Complete documentation is available at:
[http://fun-flirt.com/documentation](http://fun-flirt.com/documentation)