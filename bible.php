<?php

// Extension credits that show up on Special:Version
$wgExtensionCredits['parserhook'][] = array(
        'name' => 'Bible Generator',
        'author' => '[http://rationalwiki.com/wiki/User:Tmtoulouse Trent Toulouse]',
        'url' => 'http://rationalwiki.com/wiki/RationalWiki:Annotated_Bible',
        'description' => 'Allows for easy quoting of bible verses'
);
 
/*
 * @todo Document 
 */
$wgExtensionFunctions[] = 'wfBible';
 
/**
 * @todo Document
 */
function wfBible() {
    global $wgParser;
 
    $wgParser->setHook('bible', 'renderBible');
}
 
/**
 * @todo Document
 */

function renderBible($input, $argv) {
global $wgUser;
$title_text = "RationalWiki:Annotated Bible/".$argv['book'];
$title    = Title::newFromText($title_text);
$article  = new Article($title);
$wikitext = $article->getContent();
if(!$argv['verse2']) {
	$argv['verse2']=$argv['verse1'];
}
$argv['verse2']=$argv['verse2']+1;

	$params = array(
        	"book", "chapter", "verse1", "verse2"
	);
	$pattern = "/(";
	$pattern .= $argv['book'];
	$pattern .=" ";
	$pattern .=$argv['chapter'];
	$pattern .=":";
	$pattern .=$argv['verse1'] . "<br>";
	$pattern .=".*?)";
	$pattern .=$argv['book']." ";
	$pattern .=$argv['chapter'].":";
	$pattern .=$argv['verse2'] . "<br>";
	$pattern .="/i";

	$wikitext = str_ireplace("==","<br>",$wikitext);
	$wikitext = str_ireplace("__notoc__","",$wikitext);
	$wikitext = str_ireplace("\n","",$wikitext);
	
	$verse2=$argv['verse2']-1;

	if($verse2 == $argv['verse1']){	
	$output2 = "<a href = \"http://rationalwiki.com/wiki/RationalWiki:Annotated_Bible/".$argv['book']."#".$argv['book']."_".$argv['chapter'].":".$argv['verse1']."\">";
	$output2 .= $argv['book']." ".$argv['chapter'].":".$argv['verse1']."</a>"."<br>";
	}
	if($verse2 != $argv['verse1']){	
	$output2 = "<a href = \"http://rationalwiki.com/wiki/RationalWiki:Annotated_Bible/".$argv['book']."#".$argv['book']."_".$argv['chapter'].":".$argv['verse1']."\">";
	$output2 .=$argv['book']." ".$argv['chapter'].":".$argv['verse1']."-".$verse2."</a>"."<br>";
	}	
#	preg_match("/(Genesis.*?)Genesis+/i",$wikitext,$match);
	preg_match($pattern,$wikitext,$match);
	$output = $match[1]; 	
#	$output = str_ireplace($argv['book'], "<br>".$argv['book'], $output);
	$pattern="/<\/td>.*?<tr><td valign=top>/";
	$replace="";
	$output=preg_replace($pattern,$replace,$output);
	$pattern="/".$argv['book'].".*?<br>/";
	$replace="";
	$output=preg_replace($pattern,$replace,$output);
	
	$output3=$output2.$output;
	$output3= str_ireplace("==","",$output3);
	$output3= str_ireplace("=","",$output3);
	return $output3;
}
