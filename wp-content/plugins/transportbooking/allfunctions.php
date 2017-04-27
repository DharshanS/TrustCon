<?php
## Image Resize
function imageResizing ( $uploadDir, $file, $maxWidth = 500, $maxHeight = 500, $prefix = 'med'  ){


	$ext = substr($file,-3,3);

	switch ($ext) {
		case "jpg":
		case "jpeg":
			$src_img = imagecreatefromjpeg($uploadDir.$file);
			break;
		case "gif":
			$src_img = imagecreatefromgif($uploadDir.$file);
			break;
	
	}

	$maxSize = $maxWidth;
	
	// This will resize either width or height depending on which is wrong
	// If the image is smaller it won't resize at all.
	
	  $src_size = getimagesize($uploadDir.$file);
	   $width = $src_size[0];
	   $height = $src_size[1];
	
	   if($width > $maxSize || $height > $maxHeight) {
	
		  if($width > $height) {
			$z = $width;
			$i = 0;
			while($z > $maxSize) {
			  --$z; ++$i;
			}
			$dest_width = $z;
			$dest_height = $height - ($height * ($i / $width));
			
			
	
		  }
		  
		  else {
	
			$z = $height;
			$i = 0;
			while($z > $maxHeight) {
			  --$z; ++$i;
			}
			$dest_width = $width - ($width * ($i / $height));
			$dest_height = $z;
		  }
	
	  }
	  
	  else {
	
		 $dest_width = $width;
		 $dest_height = $height;
	  }
	
	switch ($ext) {
	case "jpg":
	case "jpeg":

		$dest_img = imagecreatetruecolor($dest_width, $dest_height);
		imagecopyresampled($dest_img, $src_img, 0, 0, 0, 0, $dest_width, $dest_height,$src_size[0],$src_size[1]);
		$medImg = imagejpeg($dest_img, $uploadDir . $prefix . "_" . $file,100);			
	break;
		
	case "gif":

		$dest_img = imagecreatetruecolor($dest_width, $dest_height);
		imagecopyresampled($dest_img, $src_img, 0, 0, 0, 0, $dest_width, $dest_height,$src_size[0],$src_size[1]);
		$medImg = imagegif($dest_img, $uploadDir . $prefix . "_". $file);
	break;
	
	}
	
	
	imagedestroy($src_img);

}


?>