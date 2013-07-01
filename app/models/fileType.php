<?php 

class FileType {
	var $useTable = false;
	
	const JPG = 'jpg';
	const JPEG = 'jpeg';
	const GIF = 'gif';
	const PNG = 'png';

	public static function parseExtension($fileName) {
		$imageDetail = explode('.' ,$fileName);
		$imageTypeIndex = count($imageDetail);
//		$imageType = $imageDetail[$imageTypeIndex - 1];
//		return $imageType;
		return $imageDetail;
	}
	
	public static function generateThumbnail($picture, $thumbWidth, $thumbHeight, $old_x = null, $old_y = null) {
		$old_x = isset($old_x) ? $old_x : imagesx($picture);
		$old_y = isset($old_y) ? $old_y : imagesy($picture);
			
		if ($old_x > $old_y) {
			if($old_x > $thumbWidth) {
				$thumb_w = $thumbWidth;
				$thumb_h = $old_y * ($thumbHeight / $old_x);
			} else {
				$thumb_w = $old_x;
				$thumb_h = $old_y;
			}
		}
		if ($old_x < $old_y) {
			if($old_y > $thumbHeight) {
				$thumb_w = $old_x * ($thumbWidth / $old_y);
				$thumb_h = $thumbHeight;
			} else {
				$thumb_w = $old_x;
				$thumb_h = $old_y;
			}
		}
		if ($old_x == $old_y) {
			$thumb_w = $thumbWidth;
			$thumb_h = $thumbHeight;
		}

		$thumb = imagecreatetruecolor($thumb_w, $thumb_h);
		imagecopyresampled($thumb, $picture,
							0,0,0,0,
							$thumb_w,$thumb_h,
							$old_x, $old_y);
		return $thumb;
	}
	
	public static function generateSquareThumb($picture, $width, $height) {
		$biggestSide = ($width > $height) ? $width : $height; 
		$cropPercent = .5; // This will zoom in to 50% zoom (crop)
		$cropWidth   = $biggestSide * $cropPercent; 
		$cropHeight  = $biggestSide * $cropPercent; 
		
		// Get top left corner
		$x = ($width - $cropWidth) / 2;
   		$y = ($height - $cropHeight) / 2;
   		
   		$thumbSize = 75; // will create a 75 x 75 thumb
   		
		$thumb = imagecreatetruecolor($thumbSize, $thumbSize); 
		imagecopyresampled($thumb, $picture, 
							0, 0, $x, $y,
							$thumbSize, $thumbSize,
							$cropWidth, $cropHeight);
		return $thumb;
	}	
}


?>