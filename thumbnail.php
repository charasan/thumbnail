<?php
  include_once "class.imageedit.php";
 
  define('THUMB_TIMEOUT', 600);
  $image = new ImageEdit();
  
  $file = $_GET['file'];
  $file = $image->relToAbs($file);
  
  $ext = $iamge->getFileExtension($file);
  
  $width = (int)$_GET['w'];
  $height = (int)$_GET['h'];
  
  $tmp = "/tmp/".md5(basename($file.$width.$height)).".".$ext; // change this to wherever you want to store cached images
  
// We've already created this thumbnail - use the cached one.
  if(file_exists($tmp) && filemtime($tmp) >= time())
  {
    $image->load($tmp);
    $image->output();
    $image->destroy();
    exit(0);
  }
  elseif(file_exists($tmp))
    @unlink($tmp);
    
  $image->load($file);
  if(empty($width)) $width = $image->getWidth();
  if(empty($height)) $height = $image->getHeight();

  if($image->getWidth() > $image->getHeight())
  {
    $image->resizeToWidth($width);
    $image->save($tmp);
    @touch($tmp, time() + THUMB_TIMEOUT);
  }
  else
  {
    $image->resizeToHeight($height);
    $image->save($tmp);
    @touch($tmp, time() + THUMB_TIMEOUT);
  }

  $image = new ImageEdit();
  if(file_exists($tmp))
    $image->load($tmp);
  else 
    $image->load($file);

  $image->output();
  $image->destroy();
?>
