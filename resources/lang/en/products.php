<?php 
$langs = DB::table('products')->select('id','name')->get();
$output = array();
foreach ($langs as $lang) {
	$output['name'.$lang->id] = $lang->name;
}
return $output;
?>