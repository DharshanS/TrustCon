<?php
class Cache { 
	public $expire = 5; // 5 sec 
	
	private $dir= "cache";

	public function get($key) {
		$this->setdir();
		$files = glob($this->dir . '/cache.' . preg_replace('/[^A-Z0-9\._-]/i', '', $key) . '.*');

		if ($files) {
			$cache = file_get_contents($files[0]);
			
			$data = unserialize($cache);
			
			foreach ($files as $file) {
				$time = substr(strrchr($file, '.'), 1);
                $nw=time();
				
				/*echo $time;
				echo "<br>";
				echo $nw;
				die();*/
				
      			if ($time < $nw) {
					if (file_exists($file)) {
						unlink($file);
					}
      			}
    		}
			
			return $data;			
		}
	}

  	public function set($key, $value) { 
		$this->setdir();
    	$this->delete($key);
		
		$file = $this->dir . '/cache.' . preg_replace('/[^A-Z0-9\._-]/i', '', $key) . '.' . (time() + $this->expire);
    	
		$handle = fopen($file, 'w');

    	fwrite($handle, serialize($value));
		
    	fclose($handle);
  	}
	
  	public function delete($key) {
		$this->setdir();
		$files = glob($this->dir . '/cache.' . preg_replace('/[^A-Z0-9\._-]/i', '', $key) . '.*');
		
		if ($files) {
    		foreach ($files as $file) {
      			if (file_exists($file)) {
					unlink($file);
				}
    		}
		}
  	}
	
	private function setdir(){
		 $this->dir=getcwd()."/cache";
	}
}
?>