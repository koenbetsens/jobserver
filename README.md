# JobServer
Gearman JobServer implementation for Laravel

### Gearman
Gearman is a MQ (Message Queue) provider with foreground and background capabilities. This makes it one of the few PHP-suited synchronous MQ handlers.

[gearman.org](http://gearman.org/)

### Laravel
Laravel is on of the leanest and meanest PHP Frameworks around - perfectly suited for rest API projects.

[laravel.com](http://laravel.com/)

___

## The package
The **Jobserver package** is a Gearman dispatch implementation for Laravel. The package functions as an abstraction layer to send both foreground as background jobs to a MQ server (Gearman in this case).
The Jobserver package is taylored to use in the 3-layer structure (app <-> api <-> worker).

Read more about installing and using the jobserver package here:

[3-layer - JobServer package](http://www.betsens.be/blog/2015/02/jobserver-package/)

[3-Layer - Set up Message Queueing](http://www.betsens.be/blog/2014/06/3-layer-set-up-message-queueing/)

___

## Implementation
Add the package to your API dependencies. To use the local "synced" mode - skipping a local Gearman installation - add the models/Ghostjob.php file to your worker app/models directory.
To make the synced connection between the API and worker job function work, add "echo Ghostjob::evaluate ('controllerDispatch', $argv);" to the end of the required function files.

#### Installation
Add the Jobserver package to the composer requirements of your API project. In `project/composer.json`, add the `koenbetsens/jobserver` entry
<?php

	{
		"name": "project",
		"description": "Project",
		"require": {
			"laravel/framework": "4.2.*",
			"koenbetsens/jobserver": "dev-master"
		}
	}

The package has 2 modes: "Gearman mode" (default) and "Synchronized mode" to enable functional communication without an actual MQ install.

#### Gearman mode
If your Gearman server runs on the same machine as your API project, Jobserver will work out-of-the-box. To send your jobs to an external queue, create a config file named `app/config/gearman.php` to store your Gearman server location. You can add multiple servers, or single servers for multiple environments, by creating [config environment-folders](http://laravel.com/docs/4.2/configuration#environment-configuration)
	<?php
	
	return array
	(
		/**
		|--------------------------------------------------------------------------
		| Gearman Settings
		|--------------------------------------------------------------------------
		|
		| Gearman Servers must be configured in their environments.
		|
		**/
		
		'servers' => array
		(
			'gearman.project-url.ext' => '4730'
		)
	
	);

#### Synchronized (local) mode
To save yourself the hassle of installing Gearman locally for development, you can enabled the **sync mode** for **php-cli** based execution of the jobload. This mode will only work when debug mode is on, to prevent sync mode in production-level environments.

##### Configuration
The configuration can be added directly to your `app/config/<strong>local</strong>/app.php` file. Your worker path points to the **job functions directory** (usually named `/jobs`)
	<?php
	
	return array(
	
		/**
		|--------------------------------------------------------------------------
		| Application Debug Mode
		|--------------------------------------------------------------------------
		|
		| When your application is in debug mode, detailed error messages with
		| stack traces will be shown on every error that occurs within your
		| application. If disabled, a simple generic error page is shown.
		|
		**/
	
		'debug' => true,
		
		/**
		|--------------------------------------------------------------------------
		| Synchronized
		|--------------------------------------------------------------------------
		| If set, the API will call the worker directly, instead of using a jobserver.
		**/
		
		'synchronized' => true,
		
		/**
		|--------------------------------------------------------------------------
		| Worker path
		|--------------------------------------------------------------------------
		| This path is used by the local Jobserver to sync a queue request.
		**/
		'worker' => array
		(
			'path' => '/path/to/project/worker/jobs'
		)
	);

##### Ghostjob model
We need to emulate a job model for syncronized usage. Copy the `/models/Ghostjob.php` file from the Jobserver package to the `app/models` folder in your worker project. Now you can selectively add the Ghostjob evaluation in the job-function files like this:
	<?php
	
	/**
	 *  Some Job Function
	 *  Catch and execute jobs
	 *
	 *  @param  object  $job
	 *  @return string
	 */
	function someJobFunction ($job) 
	{
		return "fubar";
	}
	
	/**
	 * Sync Check
	 * Ghostjob will evaluate and call the job function if "Synced" and not in production.
	 * Only add this evaluation to functions you allow to be called.
	 **/
	echo Ghostjob::evaluate ('someJobFunction', $argv);


Make sure your job-function files have the right permissions, and you're ready to go.

___

This package is MIT licensed.