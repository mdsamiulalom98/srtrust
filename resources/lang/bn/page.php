<?php 
$langs = DB::table('create_pages')->select('id','name_bn','description_bn')->get();
$output = array();
foreach ($langs as $lang) {
	$output['name'.$lang->id] = $lang->name_bn;
	$output['description'.$lang->id] = $lang->description_bn;
}
return $output;
?>