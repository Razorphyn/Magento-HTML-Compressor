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

		//Substitute style, script, pre and cdata tag with unique id
		global $idarray;
		$idarray=array();
		
		$search=array(	
						'@<(\s)*pre[^>]*>(?:[^<]+|)</pre>@',	//Find PRE Tag
						'@<(\s)*textarea(\b[^>]*?>[\\s\\S]*?</textarea>)\s*@'	//Find TEXTAREA
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
		
		
		$search = array(
						'@( |\t|\f)+@',	// Shorten multiple whitespace sequences
						'@^\s+|\s+$@',	// Trim lines
						"/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/" //Remove empty lines
						);
		$replace = array(' ','',"\n");

		$string = preg_replace($search, $replace, $string);
		
		$search=array(	
						'@<!--\[if\s(?:[^<]+|<(?!!\[endif\]-->))*<!\[endif\]-->@',	//Find IE Comments
						'@<(\s*?)script(\b[^>]*?)>([\s\S]*?)</script>(\s*)@',	//Find SCRIPT Tag
						'@//<!\[CDATA\[(?:[^<]+|)//]]>@',	//Find CDATA
						'@<(\s)*style(\b[^>]*>)([\s\S]*?)</style>\s*@'	//Find STYLE Tag
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

		$search = array(
						'@(class|id|value|alt|href|src|style|title)=(\'\s*?\'|"\s*?")@',	//Remove empty attribute
						'@<!--([\s\S]*?)-->@',	// Strip comments excluded IE
						'@(\r\n|\n|\r)@', // Strip break line
						'@( |\t|\f)+@',	// Shorten multiple whitespace sequences
						'@^\s+|\s+$@'	// Trim lines
						);
		$replace = array(' ','',' ',' ','');
		$string = preg_replace($search, $replace, $string);

		//Remove blank lines
		$string=preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/"," ", $string);
		
		
		//Replace unique id with script, style, pre original tag
		$c=count($idarray);
		for($i=0;$i<$c;$i++){
			$string = str_replace($idarray[$i][0], $idarray[$i][1], $string);
		}

		return $string;
	}
?>