<?php
// ------------------ IMAGE NAME FUNCTION ---------------
function imageName() {
$dir='images/profileface/';
$result=scandir($dir,0);
unset($result[0],$result[1]);
do{
$naimg=rand(1,300);
$string_lenth=strlen($naimg);
switch($string_lenth){
		case 1:
		$naimg="usimg"."00"."$naimg";
		break;
		case 2:
		$naimg="usimg"."0"."$naimg";
		break;
		case 3:
		$naimg="usimg".""."$naimg";
		break;	
}}while(in_array($naimg.".jpg",$result));
return $naimg;
}
// ------------------ Start Universal Image Resizing Function ---------------
function imageResize($target,$newcopy) {
        $im = imagecreatefromstring($target);
        $source_width = imagesx($im);
        $source_height = imagesy($im);
        $ratio =  $source_height / $source_width;
        $new_width = 250; // assign new width to new resized image
        $new_height = $ratio * 250;
        $thumb = imagecreatetruecolor($new_width, $new_height);
        $transparency = imagecolorallocatealpha($thumb, 255, 255, 255, 127);
        imagefilledrectangle($thumb, 0, 0, $new_width, $new_height, $transparency);
        $file_uploaded=imagecopyresampled($thumb, $im, 0, 0, 0, 0, $new_width, $new_height, $source_width, $source_height);
        imagepng($thumb, $newcopy, 9);
        imagedestroy($im);
    return $file_uploaded;
}
?>