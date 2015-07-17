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
		$varListStr = "";
		$checkListStr = "";
		$stepListStr = "";
		$collectListStr = "";
		while (!feof($fp)) {
			$line = trim(fgets($fp, 1024));
			$flag = substr($line, 0, 1);
			$elementArray = explode('.',$line,2);
			$element = $elementArray[0];
			switch ($flag) {
				case "$":
					$varListStr .= $line . '\n';
					break;
				case "{":
					$stepListStr .= $line . '\nflag';
					$checkListStr .= $line . '\nflag';
					$collectListStr .= $line . '\nflag';
					break;
				case "}":
					$stepListStr .= $line . '\n';
					$checkListStr .= $line . '\n';
					$collectListStr .= $line . '\n';
					break;
				case "#":
					break;
				default:
					switch ($element) {
						case "step":
							$stepListStr .= $line . '\n';
							break;
						case "check":
							$checkListStr .= $line . '\n';
							break;
						case "collect":
							$collectListStr .= $line . '\n';
							break;
						default:
							break;

					}
					break;
			}
		}
		fclose($fp);
		//echo "</br></br>".$varListStr."</br></br>";
		//echo $checkListStr."</br></br>";
		//echo $stepListStr."</br></br>";
		//echo $collectListStr."</br></br>";
		$this->varibles = Varible :: parseList($varListStr);
		$this->checks = Check :: parseList($checkListStr,"check");
		$this->steps = Step :: parseList($stepListStr,"step");
		$this->collects = Collect :: parseList($collectListStr,"collect");
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
