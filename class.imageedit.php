<?php
// A simple class for image manipulation
// Uses the GD library, since it seems to be the
// most readily available on most hosting providers
class ImageEdit
{
    public $image;
    public $image_type;
    public $thumb;
    public $filename;
  
    public static function create($filename = '')
    {
      $tmp = new ImageEdit();
      if(!empty($filename))
        $tmp->load($filename);
      
      return $tmp;
    }
    
    public function load($filename)
    {
      if(!file_exists($filename))
        $filename = realpath($filename);

      $this->filename = $filename;
      $this->thumb = dirname($filename).'/thumb-'.basename($filename);
      $ext = strtolower(self::getFileExtension($filename));
      
      switch($ext) {
        case "jpg":
        case "jpeg":
          $this->image_type = IMAGETYPE_JPEG;
          if(isset($raw))
            $this->image = imagecreatefromstring($raw);
          else
            $this->image = imagecreatefromjpeg($filename);
          break;
        case "gif":
          $this->image_type = IMAGETYPE_GIF;
          if(isset($raw))
            $this->image = imagecreatefromstring($raw);
          else
            $this->image = imagecreatefromgif($filename);
          break;
        case "png":
          $this->image_type = IMAGETYPE_PNG;
          if(isset($raw))
            $this->image = imagecreatefromstring($raw);
          else
            $this->image = imagecreatefrompng($filename);
          break;
        default:
          $this->image_type = IMAGETYPE_JPEG;
          if(isset($raw))
            $this->image = imagecreatefromstring($raw);
          else
            $this->image = imagecreatefromjpeg($filename);
          break;
      }

      if ($this->image_type == IMAGETYPE_GIF || $this->image_type == IMAGETYPE_PNG)
      {
        imagealphablending($this->image, false);
        imagesavealpha($this->image, true);
      }
    }
    
    public function save($filename, $image_type='jpeg', $compression=75, $permissions=null)
    {
      if( $image_type == 'jpeg' || $image_type == 'jpg' )
      {
        header("Content-type: image/jpeg");
        imagejpeg($this->image,$filename,$compression);
      }
      elseif( $image_type == 'gif' )
      {
        header("Content-type: image/gif");
        imagegif($this->image,$filename);
      }
      elseif( $image_type == 'png' )
      {
        header("Content-type: image/png");
        imagepng($this->image,$filename);
      }

      if( $permissions != null)
        chmod($filename,$permissions);
      imagedestroy($this->image);
    }
    
    public function output()
    {
      if( $this->image_type == IMAGETYPE_JPEG )
      {
        header("Content-type: image/jpeg");
        imagejpeg($this->image);
      }
      elseif( $this->image_type == IMAGETYPE_GIF )
      {
        header("Content-type: image/gif");
        imagegif($this->image);
      }
      elseif( $this->image_type == IMAGETYPE_PNG )
      {
        header("Content-type: image/png");
        imagepng($this->image);
      }
    }
   
    public function getWidth() { return imagesx($this->image); }
    public function getHeight() { return imagesy($this->image); }
    public function getImageType() { return $this->image_type; }

    public function resizeToHeight($height)
    {
      $ratio = $height / $this->getHeight();
      $width = $this->getWidth() * $ratio;
      $this->resize($width,$height);
    }
    
    public function resizeToWidth($width)
    {
      $ratio = $width / $this->getWidth();
      $height = $this->getheight() * $ratio;
      $this->resize($width,$height);
    }
   
    public function resize($width,$height)
    {
      $new_image = imagecreatetruecolor($width, $height);
      $this->retainTransparency($new_image, $width, $height);
      imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
      $this->image = $new_image;   
    }
    
    public function retainTransparency(&$image=null, $width, $height)
    {
      if ($image == null) return;
      if( $this->image_type == IMAGETYPE_GIF || $this->image_type == IMAGETYPE_PNG )
      {
        imagealphablending($image, false);
        imagesavealpha($image, true);
      }
    }

    public function destroy()
    {
      imagedestroy($this->image);
    }

    // Threw these next few functions in here just to lessen files in the repository
    public function relToAbs($path = '')
    {
      if(empty($path)) return $path;
  
      if(strpos($path, $_SERVER['DOCUMENT_ROOT']) === 0) return $path;
      return $_SERVER['DOCUMENT_ROOT'] ."/". ltrim($path, "/");
    }

    public function getFileExtension($file)
    {
      if(empty($file)) return '';
  
      $pieces = explode('.', $file);
      if(sizeof($pieces) < 1) return '';
      return array_pop($pieces);

    }
}
?>
