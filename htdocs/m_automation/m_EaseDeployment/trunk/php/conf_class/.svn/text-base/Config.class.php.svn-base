<?php
header("Content-type: text/html; charset=utf-8");

require_once "Varible.class.php";
require_once "Step.class.php";
require_once "Check.class.php";
require_once "Collect.class.php";

class Config {
	var $varibles = array ();
	var $steps = array();
	var $checks = array();
	var $collects = array ();
	var $filePath = "";
	var $confPath = "";

	//constructor
	function Config($filepath) {
		$this->filePath = $filepath;
		$this->confPath = dirname($filepath);
		if (!file_exists($this->filePath))
			touch($this->filePath);
	}

	//delete a file
	function delFile($filePath) {
		if (file_exists($filePath)){
			unlink($filePath);
		}
	}

	//convert elements information to a formatting string and write into a deployment file
	function write() {
		$tmpfilePath = $this->filePath . ".tmp";
		touch($tmpfilePath);
		try{
			$fp = fopen($tmpfilePath, "w") or die("Couldnot open file");
			flock($fp, LOCK_EX);
			$elementList = array("check"=>$this->checks,"step"=>$this->steps,"collect"=>$this->collects);
			$fileStream = Varible :: writeList($this->varibles) ."\n";
			foreach ($elementList as $key=>$value)
				$fileStream .= Element :: writeList($value,$key)."\n";
			fwrite($fp, $fileStream);
			flock($fp, LOCK_UN);
			fclose($fp);
			rename($tmpfilePath, $this->filePath);
		}catch (Exception $e) {
			$this->delFile($tmpfilePath);
			throw new Exception($e->getMessage());
		}
	}

	//parse out elements information from a deployment file
	function parse() {
		$fp = fopen($this->filePath, "r") or die("Couldnot open file");
        $confObj = array(
            "var" => "",
            "check" => "",
            "step" => "",
            "collect" => "",
        );
        $type = "";
        $num = 0;
		while (!feof($fp)) {
			$line = trim(fgets($fp, 1024));
			$head = substr($line, 0, 1);
			$elementArray = explode('.',$line,2);
			$element = $elementArray[0];
			switch ($head) {
				case "$":
					$confObj["var"] .= $line . '\nline_split';
					break;
				case "{":
                    if ($type == ""){
                        $confObj["step"] .= '\nmodule_split';
                        $confObj["check"] .= '\nmodule_split';
                        $confObj["collect"] .= '\nmodule_split';
                    }else if (array_key_exists($type, $confObj)){
                        $confObj[$type] .= $line . '\nline_split';
                        $num = $num + 1;
                    }
					break;
				case "}":
                    if ($num == 0){
                        $confObj["step"] .= '\nline_split';
                        $confObj["check"] .= '\nline_split';
                        $confObj["collect"] .= '\nline_split';
                        $type = "";
                    }else if ($num > 0){
                        $num = $num - 1;
                        $confObj[$type] .= $line . '\nline_split';
                    }
					break;
				case "#":
                    if (array_key_exists($type, $confObj)){
                        $confObj[$type] .= $line . '\nline_split';
                    }
					break;
				default:
                    if (array_key_exists($type, $confObj)){
                        $confObj[$type] .= $line . '\nline_split';
                    }else if (array_key_exists($element, $confObj)){
                        $confObj[$element] .= $line . '\nline_split';
                        $type = $element;
                    }
                    break;
			}
		}
		fclose($fp);
		$this->varibles = Varible :: parseList($confObj["var"]);
		$this->checks = Check :: parseList($confObj["check"],"check");
		$this->steps = Step :: parseList($confObj["step"],"step");
        //echo $confObj["step"];
		$this->collects = Collect :: parseList($confObj["collect"],"collect");
	}

	// return the List of file name by user name
	public static function getFileListbyUser($userId) {
		$fileList = scandir($confPath);
		$returnList = array();
		foreach ($fileList as $file)
			if ($file != "." and $file != "..")
				array_push($returnList,$file);
		return $returnList;
	}
}
?>
