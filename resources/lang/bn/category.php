<?php
$langs =  \Illuminate\Support\Facades\DB::table('categories')->select('id','name_bn')->get();
$output = [];
foreach ($langs as $lang) {
	$output['name'.$lang->id] = $lang->name_bn;
}
return $output;
?>
