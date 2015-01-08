<?php
//Read the command line argument
$search_key=$argv[1];

//Get the local file contents that is in serialised format
$recoveredData = file_get_contents('local_array.txt');

//Searching for the keyword after unserialzing the data stored as an array
foreach(unserialize($recoveredData) as $key=>$value){
	if(@$value[$search_key]){
		$result[$key]=$value[$search_key];
	}
}

//Showing the search results
if(@$result){
	asort($result);
	foreach($result as $each_url=>$key_count){
		echo "$key_count resutls found in $each_url \n";
	}
}else{
	echo "No results found. \n";
}


?>
