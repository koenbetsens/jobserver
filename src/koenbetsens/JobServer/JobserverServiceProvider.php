<?php namespace koenbetsens\JobServer;

use Illuminate\Support\ServiceProvider;

class JobServerServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('koenbetsens/jobserver');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{

		$this->app['jobserver'] = $this->app->share(function($app)
        {
            return $app->config->get('app.synchronized')?
            	
            	new JobLocalServer():
            	new JobServer();
        });
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}
