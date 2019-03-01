<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
function replace_special_characters($text){
	return str_ireplace("°","",$text);
}
function files_to_vector($dir){

if (is_dir($dir)) {
		if ($academys = opendir($dir)) {
			while (($academy = readdir($academys)) !== false) {
				if ($academy != "." && $academy != ".."){
					if ($courses = opendir($dir.'/'.$academy)) {
						while (($course = readdir($courses)) !== false) {
							if ($course != "." && $course != ".."){
								if ($gradebooks = opendir($dir.'/'.$academy.'/'.$course)) {
									while (($gradebook = readdir($gradebooks)) !== false) {
										if ($gradebook != "." && $gradebook != ".."){
												if (($handle = fopen($dir.'/'.$academy.'/'.$course.'/'.$gradebook, "r")) !== FALSE) {
													$row=1;
													while (($file = fgetcsv($handle, 0, ",")) !== FALSE) {										
														$ValorTotal = count($file);
														for ($c=0; $c < $ValorTotal; $c++) {
															if ($row==1){
																$col[$c]=trim(strtolower(utf8_encode($file[$c])));
																if (strpos($col[$c], "instructor use only")!==false){
																	$col[$c]="status";
																}elseif(strpos($col[$c], "final exam")!==false){
																	$col[$c]="final";															
																}
															}elseif(($row!=2) AND ($rowtotal>$init)){
																$result[$rowtotal-$init][utf8_decode($col[$c])]=trim($file[$c]);
																$result[$rowtotal-$init]['academy']=trim(utf8_encode($academy));
																$result[$rowtotal-$init]['course']=trim(utf8_encode($course));
																$result[$rowtotal-$init]['archivo']=trim(utf8_encode($dir.'/'.$academy.'/'.$course.'/'.$gradebook));
															}
														}
														$row++;
														$rowtotal++;	
													}
													fclose($handle);
												}
										}
									}
								}	
							}
						}
						closedir($courses);
					}				  
				}					  
			}
			closedir($academys);
		}
	}	
	return $result;
}