<?php namespace koenbetsens\jobserver;

class Ghostjob
{
	/**
	 *	Ghostjob
	 *	Emulates some functions of queue job objects
	 *	like the GearmanJob.
	 *
	 */
	 
	/**
	 *	Append workload
	 */
	function __construct ($func, $workload)
	{
		$this->func = $this->extractFunction($func);
		
		$this->load = $workload;
	}
	
	/**
	 *	Sync Check
	 *	Ghostjob will evaluate and call the job function if "Synced" and not in production (debug mode).
	 *	Config.app.debug has to be enabled for Sync to work.
	 *	
	 *	@param	string	$func
	 *	@param	array	$argvs
	 *	@return	string	response
	 */
	public static function evaluate ($func, $args = null)
	{
		if (
			/*config ('app.debug') &&*/
			isset ($args, $args[2]) &&
			$args[1] == 'sync'
		)
		
			return (new self ($func, $args[2]))->call ();
	}
	
	/**
	 *	Returns the job function. 
	 *	@return string
	 */
	function functionName ()
	{
		return $this->func;
	}
	
	/**
	 *	Returns the workload for the job. 
	 *	@return string
	 */
	function workload ()
	{
		return $this->load;
	}
	
	/**
	 *	Extract Function
	 *	Pop filename or use full string if $func no path
	 *
	 *	@return	string
	 */
	function extractFunction($path)
	{
		return is_int(strpos($path, '/'))?
		
			basename ($path, ".php"):
			$path;
	}
	
	/**
	 *	Execute Job
	 *	An optional file containing the job function can be loaded.
	 *
	 */
	function call($required = false)
	{
		if ($required)

			require_once $required;
			
		return call_user_func ($this->func, $this);
	}
}
