<?php
header("Content-type: text/html; charset=utf-8");

class Varible {
	var $name = '';
	var $value = '';

    // constructor
	function varible() {
	}

	// set name and value
	function set($name, $value) {
		$this->name = $name;
		$this->value = $value;
	}

	// convert varible information to a formatting string
	function write() {
		$varStr = '$' . $this->name . '$ => ' . $this->value;
		return $varStr;
	}

	// parse out varible information from a string
	function parse($varStr) {
		if (substr($varStr, 0, 1) == '$') {
			$varArray = explode('=>', $varStr);
			$this->name = trim(str_replace('$', '', $varArray[0]));
			$this->value = trim($varArray[1]);

		}
	}

	// convert multiple varibles information to a formatting string
	static public function writeList($varArray) {
		$varStr = "### variable definitions \n";
		foreach ($varArray as $var) {
			$varStr .= $var->write() . "\n";
		}
		return $varStr;
	}

	// parse out multiple varibles information from a string
	static public function parseList($varListStr) {
		$varList = array ();
		$varStrArray = explode('\nline_split', $varListStr);
		foreach ($varStrArray as $varStr) {
			if (substr($varStr, 0, 1) == '$') {
				$var = new Varible();
				$var->parse($varStr);
				array_push($varList, $var);
			}
		}
		return $varList;
	}

}

################## test code ##########################
//
//$Var = new Varible();
//$Var->parse('$start_sh_file$ => $anti_dir$/bin/start.sh');
//echo $Var->name . '</br>';
//echo $Var->value . '</br>';
//$Var->set('hello', 'world-pease');
//echo $Var->write();

################## test code ##########################
?>
