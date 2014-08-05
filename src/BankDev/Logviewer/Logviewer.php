<?php


namespace BankDev\Logviewer;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Lang;
use Psr\Log\LogLevel;
use ReflectionClass;

class Logviewer {
	public $path;
	public $sapi;
	public $fileName;
	public $level;
	public $empty;
	
	/**
	 * Create a new Logviewer.
	 *
	 * @access public
	 * @param
	 *        	string
	 * @param
	 *        	string
	 * @param
	 *        	string
	 * @param
	 *        	string
	 */
	public function __construct($app, $sapi, $fileName, $level = 'all') {
		$log_dirs = Config::get ( 'logviewer::log_dirs' );
		$this->path = $log_dirs [$app];
		$this->sapi = $sapi;
		$this->fileName = $fileName;
		$this->level = $level;
	}
	
	/**
	 * Check if the log is empty.
	 *
	 * @access public
	 * @return boolean
	 */
	public function isEmpty() {
		return $this->empty;
	}
	
	/**
	 * Open and parse the log.
	 *
	 * @access public
	 * @return array
	 */
	public function log() {
		$this->empty = true;
		$log = array ();
		
		$pattern = "/\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\].*/";
		
		$log_levels = $this->getLevels ();
		
		//$log_file = glob ( $this->path . '/log-' . $this->sapi . '*-' . $this->fileName . '.txt' );
		$log_file = $this->path.'/'.$this->fileName;
		
		if (! empty ( $log_file ) && File::exists ( $log_file ) ) {
			$this->empty = false;
			$file = File::get ( $log_file );

			// There has GOT to be a better way of doing this...
			preg_match_all ( $pattern, $file, $headings );
			$log_data = preg_split ( $pattern, $file );

			if ($log_data [0] < 1) {
				$trash = array_shift ( $log_data );
				unset ( $trash );
			}
			
			
			foreach ( $headings as $h ) {
				for($i = 0, $j = count ( $h ); $i < $j; $i ++) {
					foreach ( $log_levels as $ll ) {
						if ($this->level == $ll or $this->level == 'all') {
							if (strpos ( strtolower ( $h [$i] ), strtolower ( 'production.' . $ll ) )) {
								$log [] = array (
										'level' => $ll,
										'header' => $h [$i],
										'stack' => $log_data [$i] 
								);
							}
						}
					}
				}
			}
		}
		
		unset ( $headings );
		unset ( $log_data );
		
		if (strtolower ( Config::get ( 'logviewer::log_order' ) ) == "desc") {
			$log = array_reverse ( $log );
		}
		
		return $log;
	}

	
	/**
	 * Delete the log.
	 *
	 * @access public
	 * @return boolean
	 */
	public function delete() {
		
		$log_file = glob ( $this->path . '/'.$this->fileName );

		if (! empty ( $log_file ) && File::exists ( $log_file[0] ) ) {
			return File::delete ( $log_file [0] );
		}
	}
	
	public function getFirstFile(){
		//Get First File
		$dirs = Config::get('logviewer::log_dirs');
		
		$files = array();
		
		foreach ($dirs as $app => $dir)
		{
			$files = glob($dir . '/' . '*', GLOB_BRACE);
		
			if (is_array($files) && count($files) > 0)
			{
				$fileName = preg_replace('/logs/', '$1', basename($files[0]));
			}
			else
			{
				$fileName = '-';
			}
		}
		return $fileName;
	}
	
	/**
	 * Get the log levels from psr/log.
	 *
	 * @access public
	 * @return array
	 */
	public function getLevels() {
		$class = new ReflectionClass ( new LogLevel () );
		return $constants = $class->getConstants ();
	}
}
