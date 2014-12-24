<?php
/**
 * HTML Compressor
 * Author: Razorphyn
 * Suport Email: est.garndi@gmail.com
 * Copyright: WTFPL(http://www.wtfpl.net/)
**/
?>
<?php

	function html_compress($string){

		global $idarray;
		$idarray=array();
		
		//Replace PRE and TEXTAREA tags
		$search=array(
						'@(<)\s*?(pre\b[^>]*?)(>)([\s\S]*?)(<)\s*(/\s*?pre\s*?)(>)@',	//Find PRE Tag
						'@(<)\s*?(textarea\b[^>]*?)(>)([\s\S]*?)(<)\s*?(/\s*?textarea\s*?)(>)@'	//Find TEXTAREA
					);
		$string=preg_replace_callback($search,
										function($m){
											$id='<!['.uniqid().']!>';
											global $idarray;
											$idarray[]=array($id,$m[0]);
											return $id;
										},
										$string
		);
		
		//Remove blank useless space
		$search = array(
						'@( |\t|\f)+@',	// Shorten multiple whitespace sequences
						'@(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+@',	//Remove blank lines
						'@^(\s)+|( |\t|\0|\r\n)+$@' //Trim Lines
						);
		$replace = array(' ',"\\1",'');
		$string = preg_replace($search, $replace, $string);

		//Replace IE COMMENTS, SCRIPT, STYLE and CDATA tags
		$search=array(
						'@<!--\[if\s(?:[^<]+|<(?!!\[endif\]-->))*<!\[endif\]-->@',	//Find IE Comments
						'@(<)\s*?(script\b[^>]*?)(>)([\s\S]*?)(<)\s*?(/\s*?script\s*?)(>)@',	//Find SCRIPT Tag
						'@(<)\s*?(style\b[^>]*?)(>)([\s\S]*?)(<)\s*?(/\s*?style\s*?)(>)@',	//Find STYLE Tag
						'@(//<!\[CDATA\[([\s\S]*?)//]]>)@',	//Find commented CDATA
						'@(<!\[CDATA\[([\s\S]*?)]]>)@'	//Find CDATA
					);
		$string=preg_replace_callback($search,
										function($m){
											$id='<!['.uniqid().']!>';
											global $idarray;
											$idarray[]=array($id,$m[0]);
											return $id;
										},
										$string
		);
		
		//Remove blank useless space
		$search = array(
						'@(class|id|value|alt|href|src|style|title)=(\'\s*?\'|"\s*?")@',	//Remove empty attribute
						'@<!--([\s\S]*?)-->@',	// Strip comments except IE
						'@[\r\n|\n|\r]@', // Strip break line
						'@[ |\t|\f]+@',	// Shorten multiple whitespace sequences
						'@(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+@', //Remove blank lines
						'@^(\s)+|( |\t|\0|\r\n)+$@' //Trim Lines
						);
		$replace = array(' ','',' ',' ',"\\1",'');
		$string = preg_replace($search, $replace, $string);

		//Replace unique id with original tag
		$c=count($idarray);
		for($i=0;$i<$c;$i++){
			$string = str_replace($idarray[$i][0], "\n".$idarray[$i][1]."\n", $string);
		}
		
		return $string;
	}
?>