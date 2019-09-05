<?php
ini_set('memory_limit', '2048M');
ini_set("gd.jpeg_ignore_warning", 1);

//header('location:'.$_GET['img']);
//exit;

header('Content-Type: image/jpeg');

$_GET['img'] = str_replace(' ', '%20', urldecode($_GET['img']));

$images_name = 'imgs/'.md5($_GET['img']) . '_' . $_GET['w'].'.jpg';
if (!file_exists($images_name)) {
        $width = $_GET['w']; //*** Fix Width & Heigh (Autu caculate) ***//
        $size = GetimageSize($_GET['img']);
        $height = round($width * $size[1] / $size[0]);

        if(preg_match('/\.(png)$/i', $_GET['img']) || isPNG($_GET['img'])){
            $images_orig = @imagecreatefrompng($_GET['img']);
        }else{
            $images_orig = @imagecreatefromjpeg($_GET['img']);
        }
        if(!$images_orig)
            $images_orig = @ImageCreateFromJPEG($_GET['img']);

        if(!$images_orig){
                header('location: '.$_GET['img']);
                exit;
        }

        $photoX = ImagesX($images_orig);
        $photoY = ImagesY($images_orig);
        $images_fin = ImageCreateTrueColor($width, $height);
        ImageCopyResampled($images_fin, $images_orig, 0, 0, 0, 0, $width + 1, $height + 1, $photoX, $photoY);
        imagejpeg($images_fin, $images_name);
        imagejpeg($images_fin);
        ImageDestroy($images_orig);
        ImageDestroy($images_fin);
} else {
        $images_fin=  ImageCreateFromJPEG($images_name);
        imagejpeg($images_fin);
}

function isPNG($file){
    return preg_match('/'.quotemeta('PNG').'/i', file_get_contents($file));
}