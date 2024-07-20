<?php
function returnIcon($v, $path = '')
{
	$ext = getFileFormat($v);
	if (checkPictureFormat($v)) return $path . $v;
	else if ($ext == "pdf") return "images/pdf.png";
	else if ($ext == "pptx") return "images/ppt.jpg";
	else if ($ext == "mp3") return "images/mp3.jpg";
	else if ($ext == "mp4") return "images/mp4.jpg";
	else return 0;
}

function getFileFormat($filepath)
{
	return strtolower(pathinfo($filepath, PATHINFO_EXTENSION));
}

function renameIfExists($filepath, $maintain_filename = false)
{
	$ext 			= strtolower(pathinfo($filepath, PATHINFO_EXTENSION));
	$filepath = strtolower($filepath);
	$folder		= dirname($filepath);
	$filename	= basename($filepath);
	if (is_file($filepath)) {
		if ($maintain_filename) {
			$basename = str_replace(".{$ext}", "", $filename);
			$filename = "{$basename}2.{$ext}";
		} else {
			$random 	= random(8);
			$filename = "{$random}.{$ext}";
		}
		return renameIfExists("{$folder}/{$filename}", $maintain_filename);
	} else {
		return $filepath;
	};
}

function checkPictureFormat($pic)
{
	$ft = array("jpg", "jpeg", "png", "gif");
	$tx = strtolower(getFileFormat($pic));
	if (array_search($tx, $ft) !== false) return 1;
	else return 0;
}

// for thumbnails or reduing image quality
function resizePicture($upload_picture, $thumbnail, $width = 200, $height = 200)
{
	// get image
	$image = imagecreatefrompicture($upload_picture);
	if (!$image) return 0; // stops execution if image not found
	//dimension for new height
	list($width_orig, $height_orig) = getimagesize($upload_picture);

	$ratio_orig = $width_orig / $height_orig;

	if ($width / $height > $ratio_orig) {
		$width = $height * $ratio_orig;
	} else {
		$height = $width / $ratio_orig;
	}

	$image_p = imagecreatetruecolor($width, $height);

	$white = imagecolorallocate($image_p, 255, 255, 255);

	// Draw a white rectangle
	imagefilledrectangle($image_p, 0, 0, $width, $height, $white);


	//resample
	imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
	//output
	if (!is_dir(dirname($thumbnail))) mkdir(dirname($thumbnail), 0755, true);
	imagejpeg($image_p, $thumbnail, 80);

	return $thumbnail;
}




function imagecreatefrompicture($pic)
{
	$ext = getFileFormat($pic);
	if (strtolower($ext) == "jpg" || strtolower($ext) == "jpeg") {

		return imagecreatefromjpeg($pic);
	} else if (strtolower($ext) == "gif") {

		return imagecreatefromgif($pic);
	} else if (strtolower($ext) == "png") {
		//Check if it's a valid png file
		$format = explode("/", mime_content_type($pic));
		$new_ext = end($format);
		if (strtolower($new_ext) !== "png") {
			$newpic = str_replace($ext, $new_ext, $pic);
			$pic = rename($pic, $newpic);
			return imagecreatefrompicture($newpic);
		} else {
			return imagecreatefrompng($pic);
		}
	} else {
		return imagecreatefromgd($pic);
	}
}

// Crop out a rectangular/square section of the image
function image_crop($image_resource, $param)
{
	// Get new sizes
	$width			=	imagesx($image_resource);
	$height			=	imagesy($image_resource);
	$newwidth 	= $param['width'];
	$newheight 	= $param['height'];
	$thumb 			= imagecreatetruecolor($newwidth, $newheight);
	// Resize
	imagecopy($thumb, $image_resource, 0, 0, $param['x'], $param['y'], $width, $height);
	// Output
	return $thumb;
}

function get_base64_extension($base64_string)
{
	foreach (["image", "video"] as $key => $value) {
		$base64_string = preg_replace("#data:{$value}/[^;]+;base64,#", '', $base64_string);
	}
	$file_object = finfo_open();
	$mime_type = finfo_buffer($file_object, base64_decode($base64_string), FILEINFO_MIME_TYPE);
	$data =  explode("/", $mime_type);
	array_push($data, $base64_string);
	return $data;
}

function save_base64_file($base64_string, $output_file)
{
	list($base64_string, $filename) = explode("--name=", $base64_string);
	// Check the $base64_string to the ascertain the file type
	if (substr($base64_string, 0, 5) == "data:") {
		// Strip the mime headers
		$mime_type = get_base64_extension($base64_string);
		$output_file = "{$output_file}.{$mime_type[1]}";
		$base64_string = $mime_type[2];
	} else {
		$output_file = $filename ?? "{$output_file}.png";
	}
	// open the output file for writing
	$folder = dirname($output_file);
	if (!is_dir($folder)) {
		if (!mkdir($folder, 0755, true)) {
			// Can't create folder
		}
	}

	// Save file and return filenmae
	file_put_contents($output_file, base64_decode($base64_string));
	$icon = create_thumbnail($output_file);
	return $icon;
}

function get_mime_type($base64_string)
{
	if (substr($base64_string, 0, 5) == "data:") {
		// Strip the mime headers
		$mime_type = get_base64_extension($base64_string);
		return	$mime_type[1];
	} else {
		return "png";
	}
}




function is_base64_string($string)
{
	$string = explode("--name=", $string)[0];
	if (substr($string, 0, 5) == "data:") {
		// Strip the mime headers
		$string = preg_replace('#data:image/[^;]+;base64,#', '', $string);
	}
	$decoded = base64_decode($string, true);

	// Check if there is no invalid character in string
	if (!preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $string)) return false;

	// Decode the string in strict mode and send the response
	if (!$decoded) return false;

	// Encode and compare it to original one
	if (base64_encode($decoded) != $string) return false;

	return true;
}

// removes /tbn from image path
function remove_thumbnail(string $filepath)
{
	return str_replace("/tbn", "", $filepath);
}

// create a thumbnail for images
function create_thumbnail(string $image_file)
{
	$icon = dirname($image_file) . "/tbn/" . basename($image_file);
	if (!is_file($icon) && in_array(getFileFormat($image_file), ['gif', 'jpg', 'bmp', 'jpeg', 'png'])) {
		resizePicture($image_file, $icon, 200, 200);
	}
	return $icon;
}

// converts laravel absolute_path to storage_path
function absolute_to_storage_path(string $full_path)
{
	return str_replace(storage_path() . "/app/public", "storage", $full_path);
}
