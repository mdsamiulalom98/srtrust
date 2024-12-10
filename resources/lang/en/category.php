<?php 
$langs = DB::table('categories')->select('id','name')->get();
$output = array();
foreach ($langs as $lang) {
	$output['name'.$lang->id] = $lang->name;
}
return $output;
?>