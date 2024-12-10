<?php 
$langs = DB::table('create_pages')->select('id','name','description')->get();
$output = array();
foreach ($langs as $lang) {
	$output['name'.$lang->id] = $lang->name;
	$output['description'.$lang->id] = $lang->description;
}
return $output;
?>