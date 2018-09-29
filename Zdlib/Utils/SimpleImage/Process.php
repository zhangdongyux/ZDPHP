<?php
/*
 * File: SimpleImage.php Author: Simon Jarvis Copyright: 2006 Simon Jarvis Date:
 * 08/11/06 Link:
 * http://www.white-hat-web-design.co.uk/articles/php-image-resizing.php This
 * program is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version. This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
 * details: http://www.gnu.org/licenses/gpl.html
 * http://www.white-hat-web-design.co.uk/articles/php-image-resizing.php
 */
class Utils_SimpleImage_Process {
	var $image;
	var $image_type;
	function load($filename) {
		$image_info = getimagesize ( $filename );
		$this->image_type = $image_info [2];
		if ($this->image_type == IMAGETYPE_JPEG) {
			$this->image = imagecreatefromjpeg ( $filename );
		} elseif ($this->image_type == IMAGETYPE_GIF) {
			$this->image = imagecreatefromgif ( $filename );
		} elseif ($this->image_type == IMAGETYPE_PNG) {
			$this->image = imagecreatefrompng ( $filename );
		}
	}
	function save($filename, $image_type = IMAGETYPE_JPEG, $compression = 100, $permissions = null) {
		if ($image_type == IMAGETYPE_JPEG) {
			imagejpeg ( $this->image, $filename, $compression );
		} elseif ($image_type == IMAGETYPE_GIF) {
			imagegif ( $this->image, $filename );
		} elseif ($image_type == IMAGETYPE_PNG) {
			imagepng ( $this->image, $filename );
		}
		if ($permissions != null) {
			chmod ( $filename, $permissions );
		}
	}
	function output($image_type = IMAGETYPE_JPEG) {
		if ($image_type == IMAGETYPE_JPEG) {
			imagejpeg ( $this->image );
		} elseif ($image_type == IMAGETYPE_GIF) {
			imagegif ( $this->image );
		} elseif ($image_type == IMAGETYPE_PNG) {
			imagepng ( $this->image );
		}
	}
	function getWidth() {
		return imagesx ( $this->image );
	}
	function getHeight() {
		return imagesy ( $this->image );
	}
	function resizeToHeight($height) {
		$ratio = $height / $this->getHeight ();
		$width = $this->getWidth () * $ratio;
		return $this->resize ( $width, $height ); //新增返回值为了cut方法
	}
	function resizeToWidth($width) {
		$ratio = $width / $this->getWidth ();
		$height = $this->getheight () * $ratio;
		return $this->resize ( $width, $height );//新增返回值为了cut方法
	}
	function scale($scale) {
		$width = $this->getWidth () * $scale / 100;
		$height = $this->getheight () * $scale / 100;
		$this->resize ( $width, $height );
	}
	function resize($width, $height) {
		$new_image = imagecreatetruecolor ( $width, $height );
		if ($this->image_type == IMAGETYPE_GIF || $this->image_type == IMAGETYPE_PNG) {
			$current_transparent = imagecolortransparent ( $this->image );
			if ($current_transparent != - 1) {
				$transparent_color = imagecolorsforindex ( $this->image, $current_transparent );
				$current_transparent = imagecolorallocate ( $new_image, $transparent_color ['red'], $transparent_color ['green'], $transparent_color ['blue'] );
				imagefill ( $new_image, 0, 0, $current_transparent );
				imagecolortransparent ( $new_image, $current_transparent );
			} elseif ($this->image_type == IMAGETYPE_PNG) {
				imagealphablending ( $new_image, false );
				$color = imagecolorallocatealpha ( $new_image, 0, 0, 0, 127 );
				imagefill ( $new_image, 0, 0, $color );
				imagesavealpha ( $new_image, true );
				imagepng ( $new_image, "picture2.png" );
			}
		}
		imagecopyresampled ( $new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth (), $this->getHeight () );
		$this->image = $new_image;
		return$this->image;//新增返回值，为了cut方法
	}
	/**
	 * 此方法会将image2放入image1的正中位置,只支持jpg格式
	 * @param unknown $image1 背景图片
	 * @param unknown $image2 贴图图片
	 * @param unknown $areaImage 
	 */
	function copymerge($image1,$image2)
	{
	    $im1 = '';
	    $im2 = '';
	    $image_info = getimagesize ( $image1 );
	    $image_type = $image_info [2];
	    if ($image_type == IMAGETYPE_JPEG) {
	        $im1 = imagecreatefromjpeg ( $image1 );
	    } elseif ($this->image_type == IMAGETYPE_GIF) {
	        $im1 = imagecreatefromgif ( $image1 );
	    } elseif ($this->image_type == IMAGETYPE_PNG) {
	        $im1 = imagecreatefrompng ( $image1 );
	    }
	    $image_info = getimagesize ( $image1 );
	    $image_type = $image_info [2];
	    if ($image_type == IMAGETYPE_JPEG) {
	        $im2 = imagecreatefromjpeg ( $image2 );
	    } elseif ($this->image_type == IMAGETYPE_GIF) {
	        $im2 = imagecreatefromgif ( $image2 );
	    } elseif ($this->image_type == IMAGETYPE_PNG) {
	        $im2 = imagecreatefrompng ( $image2 );
	    }
	    
	    $im1Width = imagesx ( $im1 );
	    $im2Width = imagesx ( $im2 );
	    
	    $pos = ($im1Width-$im2Width)/2;
	    imagecopymerge($im1, $im2, $pos, 0, 0, 0, imagesx($im2), imagesy($im2), 100);
	    $this->image = $im1;
// 	    imagejpeg($im1);
	}
	//按中心线裁剪图片
	/**
	 * @param string $image
	 * @param int $width
	 * @param int $height
	 */
    public function cut($srcImage,$width,$height)
    {
        
        $this->load($srcImage);
        $srcImage = imagecreatefromjpeg ( $srcImage );
        $srcWdith = imagesx ( $srcImage );
        $srcHeight = imagesy ( $srcImage );
        if($width>$srcWdith)
        {
            $this->image = $this->resizeToWidth($width);
        }
        if($height>$this->getHeight())
        {
            $this->image = $this->resizeToHeight($height);
        }
        
        $new_image = imagecreatetruecolor ( $width, $height );//创建画布
       
        $iWidth = $this->getWidth()/2; //获取图片宽度/2
        
        $iHeight = $this->getHeight()/2; //获取图片高度/2
        
        $x1 = $iWidth-$width/2;
        $y1 = $iHeight-$height/2;
        
//         echo $y1;exit;
        imagecopyresampled ( $new_image, $this->image, 0, 0, $x1, $y1, $width, $height, $width, $height );
        $this->image = $new_image;
    }
}
?>