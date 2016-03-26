<?php
$funcNum = $_GET ['CKEditorFuncNum'];
$url = 'http://localhost.ebuilders/content/images/uploads/' . $_FILES ["upload"] ["name"];
$message = UploadImageFile ();

echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($funcNum, '" . $url . "', '" . $message . "');</script>";
function UploadImageFile() {
	$warning = '';
	if (strncasecmp ( $_FILES ["upload"] ["type"], "image/", 6 ) == 0) {
		if ($_FILES ["upload"] ["error"] > 0) {
			$warning = "Return Code: " . $_FILES ["upload"] ["error"];
		} else {
			$file_name = $_FILES ["upload"] ["name"];
			$move_to_file = "../../../../images/uploads/" . $file_name;
			
			if (file_exists ( $move_to_file )) {
				$warning = $file_name . " already exists.";
			} else {
				if (! @move_uploaded_file ( $_FILES ["upload"] ["tmp_name"], $move_to_file )) {
					$warning = 'Upload Failed!';
				} else {
					list ( $width, $height, $type, $attr ) = getimagesize ( $move_to_file );
					$fileType = image_type_to_mime_type ( $type );
					$allowedImageTypes = array (
							"image/pjpeg",
							"image/jpeg",
							"image/jpg",
							"image/png",
							"image/x-png",
							"image/gif" 
					);
					if (! in_array ( $fileType, $allowedImageTypes )) {
						$warning = "Unsupported file type";
						unlink ( $move_to_file ); // Delete unsupported file
					}
				}
			}
		}
	} else {
		$warning = "Invalid file";
	}
	return $warning;
}
?>
