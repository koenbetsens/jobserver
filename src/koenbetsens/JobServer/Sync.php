<?php namespace koenbetsens\JobServer;

use Illuminate\Support\Facades\Config;

class Sync {
	
	/**
	 *	Execute php job
	 *	The job is handled in sync mode
	 */

	public function doExec ($job, $jobload)
	{
		exec ("php -f " . Config::get ('api.worker.path') . "execute.php " . $job . " '" . $jobload . "'", $output);
		
		return implode ("\n", $output);
	}
	
	public function doHigh ($job, $jobload) { return $this->doExec ($job, $jobload); }
	
	public function doLow ($job, $jobload) { return $this->doExec ($job, $jobload); }
	
	public function doHighBackground ($job, $jobload) { $this->doExec ($job, $jobload); return 'sync high'; }
	
	public function doLowBackground ($job, $jobload){ $this->doExec ($job, $jobload); return 'sync low'; }
}