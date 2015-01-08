<?php
//simple html dom plugin has been used.
include_once('simple_html_dom.php');

//The required functions and classes for the index functionality can be found here. 
include_once('related_functions.php');

$urls=array_splice($argv, 1);

foreach($urls as $url){
	//Get the page text
	$text= file_get_html($url)->plaintext;
	
	//Decode HTML character references
	$text = html_entity_decode( $text, ENT_QUOTES, "utf-8" );

	//Strip punctuation characters
	$text = strip_punctuation( $text );

	//Strip symbol characters
	$text = strip_symbols( $text );

	//Strip number characters
	$text = strip_numbers( $text );

	//Convert to lower case
	$text = mb_strtolower( $text, "utf-8" );

	//Split the text into a word list
	mb_regex_encoding( "utf-8" );
	$words = mb_split( ' +', $text );

	//Stem the words
	foreach ( $words as $key => $word )
	    $words[$key] = $word;

	//Stop word file
	$stopWordsFilename='./stop-words_english_3_en.txt';
	$stopText  = file_get_contents( $stopWordsFilename,true );
	$stopWords = mb_split( '[ \n]+', mb_strtolower( $stopText, 'utf-8' ) );
	foreach ( $stopWords as $key => $word )
	    $stopWords[$key] = PorterStemmer::Stem( $word, true );

	//Remove stop words
	$words = array_diff( $words, $stopWords );

	//Remove unwanted words
	//$words = array_diff( $words, array() );

	//Count keyword usage
	$keywordCounts = array_count_values( $words );
	arsort( $keywordCounts, SORT_NUMERIC );


	$array[$url]=$keywordCounts;
	if ( filesize('local_array.txt') ) { 
	//If there is already data in the local file then get and put it back along with the new url processed
		$recoveredData = file_get_contents('local_array.txt');
		$array = array_merge($array,unserialize($recoveredData));
	} 
	//Serialize the data and store in the local file.
	$serializedData = serialize($array);
	file_put_contents('local_array.txt', $serializedData);
}
?>
