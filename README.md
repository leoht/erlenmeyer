# Erlenmeyer

Erlenmeyer is a PHP library that allows to launch or restrict features of your application to a sample of users, based 
on different strategies. You can also launch different versions of the same feature, having a A/B testing approach.

## Installation

Install with composer:

	composer require leoht/erlenmeyer

## Introduction:

Erlenmeyer uses the concept of features and strategies:

 - features represent the different features or variants (in case of A/B testing approach) that you want to launch or test for your users.
 - strategies define a way to compute a result value depending of both configured options and the runtime context.

Strategies are defined independently from your features, and can also be used for different ones of them.

A very basic example of a strategy is a strategy that computes a random number between 0 and 100, and return a boolean value
depending on a configured option that represents a percentage thresold. Then, such a strategy would return true if the random
value is less than the thresold, and false if this value is more that the thresold.

A more complex strategy, for example, could be configured using a range of 'whitelisted' IP addresses, and then use the runtime context of your application to get the IP address of the user and check it against this range to return, again, a boolean value.

## Using a configuration file

Defining a lot of features and strategies can become cumbersome at some point. A nicer way is to use a configuration file.
Erlenmeyer can read YAML configuration file to build its inventory of features and strategies.

Here is an example of a configuration file:

	features:
	    home_button:
	       strategy: distribute
	       variants: [red, blue, green]
	       options:
	           distribution:
	                red: 33
	                blue: 33
	                green: 33


Using an expression for a strategy voter can become very conveniant using a configuration file:

	features:
	    top_secret:
	        strategy: ip_whitelist
	        variants: [enabled, disabled]
	        options:
	            whitelist: [127.0.0.1, 10.2.3.5]

	strategies:
	    ip_whitelist:
	         voter: "context.current_ip IN options.whitelist"


The voter expression will be evaluated, at runtime, with provided options and context values to return a boolean result.
The feature can then be tested inside your code:

	<?php

	$result = $erlenmeyer->vary('top_secret', array(
		'current_ip' => $_SERVER['REMOTE_ADDR']
	));


