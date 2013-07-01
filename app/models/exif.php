<?php
class Exif extends AppModel {
	var $name = 'Exif';
	
	const Id = 'Exif.id';
	
	const Model = 'model';
	const Make = 'make';
	const ImageId = 'image_id';
	const Aperture = 'aperture';
	const FNumber = 'f_number';
	const Iso = 'iso';
	const ExposureTime = 'exposure_time';
	const DateTimeOriginal = 'dateTimeOriginal';
	
	const T_Model = 'Exif.model';
	const T_Make = 'Exif.make';
	const T_ImageId = 'Exif.image_id';
	const T_Aperture = 'Exif.aperture';
	const T_FNumber = 'Exif.f_number';
	const T_Iso = 'Exif.iso';
	const T_ExposureTime = 'Exif.exposure_time';
	const T_DateTimeOriginal = 'Exif.dateTimeOriginal';
	
	var $belongsTo = array(IMAGE);
	
	function saveExifData($image, $imageId) {
		$exifToSave = array();
		
		$exifData = @exif_read_data($image, null, true);
		
        if (isset($exifData['IFD0']) && is_array($exifData['IFD0'])) {
	        if (isset($exifData['IFD0']['Make'])) {
	        	$exifToSave[Exif::Model] = $exifData['IFD0']['Make'];
	        }
	        if (isset($exifData['IFD0']['Model'])) {
	        	$exifToSave[Exif::Model] = $exifData['IFD0']['Model'];	
	        }
        }
        
        if ( isset($exifData['EXIF']) && is_array($exifData['EXIF'])) {
			$exifToSave[Exif::Iso] = $exifData['EXIF']['ISOSpeedRatings'];
			$efnumber = $exifData['EXIF']['FocalLength']; // 56/10 == 5.6mm
			$efnumberArray = explode ('/', $efnumber);
			$firstOperand = (int)$efnumberArray[0];
			$secondOperand = (int)$efnumberArray[1];
			$exifToSave[Exif::FNumber] = $firstOperand /$secondOperand;
			
			$eexposuretime = $exifData['EXIF']['ExposureTime'];// 190000/1000000 == 0.0019sec
			$eexposuretime = explode ('/', $eexposuretime);
			$firstOperand = (int)$eexposuretime[0];
			$secondOperand = (int)$eexposuretime[1];
			$eexposuretime = $firstOperand /$secondOperand;
			$exifToSave[Exif::ExposureTime] = round($eexposuretime, 2);
			$exifToSave[Exif::DateTimeOriginal] = $exifData['EXIF']['DateTimeOriginal'];
        }
        
		if (isset($exifData['COMPUTED']) && is_array($exifData['COMPUTED'])) {
			if (isset($exifData['COMPUTED']['ApertureFNumber'])) {
				$eapertureFNumber = $exifData['COMPUTED']['ApertureFNumber'];// Aperture : f/2.8
				$exifToSave[Exif::Aperture] = $eapertureFNumber;
			}
		}
		if (0 < count($exifToSave)) {
			$exifToSave[Exif::ImageId] = $imageId;
			$this->id = null;
			return $this->save($exifToSave);	
		} else {
			return false;
		}
	}
}

?>