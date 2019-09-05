<?php

function GrabImage($url,$filename="") {
  if($url==""):return false;endif;

  if($filename=="") {
    $ext=strrchr($url,".");
    //if($ext!=".gif" && $ext!=".jpg" && $ext!=".png"):return false;endif;
    $filename=date("dMYHis").$ext;
  }

  ob_start();
  readfile($url);
  $img = ob_get_contents();
  ob_end_clean();
  $size = strlen($img);

  $fp2=@fopen($filename, "a");
  fwrite($fp2,$img);
  fclose($fp2);

  return $filename;
}

function get_small_img($original, $desc) {

    $maxsize = 1280;



    // create new Imagick object

    $image = new Imagick($original);



    if ($image -> getImageWidth() >= 1280) {

        $maxsize = 1280;

    } else {

        $maxsize = $image -> getImageWidth();

    }

    // Resizes to whichever is larger, width or height

    if ($image -> getImageHeight() <= $image -> getImageWidth()) {

        // Resize image using the lanczos resampling algorithm based on width

        $image -> resizeImage($maxsize, 0, Imagick::FILTER_LANCZOS, 1);

    } else {

        // Resize image using the lanczos resampling algorithm based on height

        $image -> resizeImage(0, $maxsize, Imagick::FILTER_LANCZOS, 1);

    }



    // Set to use jpeg compression

    $image -> setImageCompression(Imagick::COMPRESSION_JPEG);

    // Set compression level (1 lowest quality, 100 highest quality)

    $image -> setImageCompressionQuality(90);

    // Strip out unneeded meta data

    $image -> stripImage();

    // Writes resultant image to output directory

    $image -> writeImage($desc);

    // Destroys Imagick object, freeing allocated resources in the process

    $image -> destroy();

}

?>