<?php namespace koenbetsens\jobserver;

use GearmanClient;
use Illuminate\Support\Facades\Config;

class JobServer
{
	static $default_server = ['localhost'=> 4730];
	
	/**
	 *	Get Server Client
	 *	In this case, the GearmanClient
	 *
	 *	@return GearmanClient
	 */
	public static function getServerClient()
	{
		$client = new GearmanClient ();
		$servers = Config::get ('gearman.servers')? : self::$default_server;
		
		foreach ($servers as $server => $port)
		{
			$client->addServer ($server, $port);
		}
		
		return $client;
	}
	
	/**
	 *	Foreground job
	 *	A low (default) or high priority foreground job.
	 *	Waits for process and returns response.
	 *
	 *	@return object
	 */
	public static function request($job, $jobload, $priority = false)
	{
		$client = self::getServerClient();
		
		return $priority?
		
			$client->doHigh ($job, json_encode($jobload)):
			$client->doLow ($job, json_encode($jobload));
	}
	
	/**
	 *	Background job
	 *	A low (default) or high priority background job.
	 *	Only returns the job handle. Doesn't wait for actual process.
	 *
	 *	@return string
	 */
	public static function queue($job, $jobload, $priority = false)
	{
		$client = self::getServerClient();

		return $priority?
		
			$client->doHighBackground ($job, json_encode($jobload)):
			$client->doLowBackground ($job, json_encode($jobload));
	}
}