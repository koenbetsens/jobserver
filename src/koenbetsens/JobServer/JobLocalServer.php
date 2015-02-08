<?php namespace koenbetsens\JobServer;

use Illuminate\Support\Facades\Config;

class JobLocalServer extends JobServer
{

	/**
	 *	Get Server Client
	 *	In this case, a synchronous handler
	 *
	 *	@return object
	 */
	public static function getServerClient()
	{
		return new Sync;
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
