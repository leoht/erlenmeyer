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

## Usage

All configuration can be done by code using the main `Erlenmeyer` singleton.

### Registering features

A feature is a simple way of representing a new feature, improvement, or user experience test that is part of your application and that you want to manage (disable, enable, or test multiple variants of this feature). 

    use LeoHt\Erlenmeyer\Erlenmeyer;
    
    $erlenmeyer = Erlenmeyer::build();
    $erlenmeyer->feature('new_call_to_action');

### Defining strategies

By default, a feature registered without any strategy is always "enabled" in a single version. To make your feature vary depending on the user context or of other parameters, you can defined strategies and create features with a specific strategy, defining how the feature and its variants should behave.

For example, the built-in **percent** strategy enables a feature to only a defined percent of your users. This percent threshold is configured using an options array when you create your feature. To enable the previous feature to only 30% of your users, the code would look more like this:

    $erlenmeyer->feature('new_call_to_action', 'percent')
	    ->setOptions(array('threshold' => 30));

Another similar built-in strategy is **distribute**, which is behaving like the **percent** one, but instead of having just two possible states you can define multiple segments of users.

	$erlenmeyer->feature('header_color', 'distribute')
		->setVariants(['blue','green','black'])
		->setOptions(array(
			'distribution' => array(
				'blue' => 30,
				'green' => 30,
				'black' => 40
			)
		));

For this feature, the variant "blue" will be used for around 30% of your users, the "green" one for also 30%, and the "black" one 40%.

###Creating custom strategies

It is also possible to create other strategies that best fit your application or marketing philosophy.
For example, taking our previous example of the IP address whitelist, such a strategy could be defined like this:

		$strategy = $erlenmeyer->strategy('ip_whitelist', function ($context, $options) { 
		return in_array($context['current_ip'], $options['whitelist']);
		});

This creates our strategy. At runtime, to decide which feature variant should be used, the provided callback, which we call the **voter**, will execute and return the chosen variant value. 
The argument `$context` is an array of context values provided by you when you want to evaluate the state of the feature. The argument `$options` is an arbitrary array of options specific to your strategy. 

In this example, we define the `whitelist` key that holds an array of valid IP addresses, as part of the strategy options:

	$strategy->setOptions(array(
		'whitelist' => ['127.0.0.1', '82.192.46.20']
	));

You can be even more specific, and define these options feature-wise instead of strategy-wise. At runtime, the tested feature options will merge with the strategy ones, keys from the feature options overriding the strategy ones.

	$strategy->setOptions(array(
		'whitelist' => ['127.0.0.1', '82.192.46.20']
	));
	
	$feature = $erlenmeyer->feature('top_secret', 'ip_whitelist', ['enabled', 'disabled'])
		->setOptions(array(
			'whitelist' => ['127.0.0.1'] // will override strategy option
		));

	// At runtime

	$variant = $erlenmeyer->vary('top_secret', array('current_ip' => $_SERVER['REMOTE_ADDR'])); // will return true or false

###Using expressions for voters

If you don't want to use PHP callbacks for voters, Erlenmeyer is shipped with the [Symfony ExpressionLanguage component](http://symfony.com/doc/current/components/expression_language/index.html). You can use expressions to define boolean conditions that the voter should return. Inside an expression scope, the two arrays `context` and `options` are provided.

The voter for our **ip_whitelist** strategy could have been defined like this:

	$strategy = $erlenmeyer->strategy('ip_whitelist', 'context.current_ip in options.whitelist');

Way simpler, right?

	 
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


Using an expression for a strategy voter can become very convenient using a configuration file:

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


