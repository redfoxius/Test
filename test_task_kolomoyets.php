<?php
/**
* File test_task_kolomoyets.php
* Test task - Kolomoyets Alexander
* 21 July 2016
**/

/**
* Test task function
*
* @param string $path       Directory path
* @param string $resultType Result type
*
* @return mixed
**/
function testDir($path = null, $resultType = 'json') {
	if (!$path) $path = getcwd();
	$arr = array();
	$dir = dir($path);
	while (false !== ($entry = $dir->read())) {
		if ($entry != '.' && $entry != '..') {
			if (is_dir($path.'/'.$entry)) {
		   		$dirInfo = GetDirInfo($path.'/'.$entry);
		   		$tmp['name'] = $entry;
		   		$tmp['fullsize'] = $dirInfo['size'];
		   		$tmp['filesCount'] = $dirInfo['filesCount'];
				$tmp['dupCount'] = $dirInfo['dupCount'];
		   		$arr[$tmp['fullsize']] = (object)$tmp;
			}
		}
	}
	ksort($arr);
	if ($resultType === 'json') {
		return json_encode($arr);
	} else {
		return $arr;
	}
}

/**
* GetDirInfo function
*
* @param string $path Directory path
*
* @return array
**/
function GetDirInfo($path) {
	$result = array(
			'filesCount' => 0,
			'size' => 0,
			'dupCount' => 0
		);
	$md5files = array();
	if ($path) {
		$d = dir($path);
		while (false !== ($ent = $d->read())) {
			if ($ent != '.' && $ent != '..') {
				if (is_dir($path.'/'.$ent)) {
					$tmp = GetDirInfo($path.'/'.$ent);
					$result['filesCount'] += $tmp['filesCount'];
					$result['size'] += $tmp['size'];
					$result['dupCount'] += $tmp['dupCount'];
				} else {
					$result['filesCount']++;
					$result['size'] += filesize($path.'/'.$ent);
					$tmp = md5_file($path.'/'.$ent);
					if (!in_array($tmp, $md5files)) {
						$md5files[] = $tmp;						
					} else {
						$result['dupCount']++;
					}
				}
			}
		}
	}
	return $result;
}


var_dump(testDir('/var/www/html'));