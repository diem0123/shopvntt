<?php

class function_coppy
{
		
		public function create_watermark($source_file_path, $output_file_path)
		{
			define('WATERMARK_OVERLAY_IMAGE', $_SERVER['DOCUMENT_ROOT'].'/demo/wysiwyg/dongdau/watermark.png');
			define('WATERMARK_OVERLAY_OPACITY', 50);
			define('WATERMARK_OUTPUT_QUALITY', 90);
			list($source_width, $source_height, $source_type) = getimagesize($source_file_path);
			if ($source_type === NULL) {
				return false;
			}
			switch ($source_type) {
				case IMAGETYPE_GIF:
					$source_gd_image = imagecreatefromgif($source_file_path);
					break;
				case IMAGETYPE_JPEG:
					$source_gd_image = imagecreatefromjpeg($source_file_path);
					break;
				case IMAGETYPE_PNG:
					$source_gd_image = imagecreatefrompng($source_file_path);
					break;
				default:
					return false;
			}
			$overlay_gd_image = imagecreatefrompng(WATERMARK_OVERLAY_IMAGE);
			$overlay_width = imagesx($overlay_gd_image);
			$overlay_height = imagesy($overlay_gd_image);
			
			/*
			 * ALIGN TOP, LEFT
			 */
			/*
			imagecopymerge(
				$source_gd_image,
				$overlay_gd_image,
				0,
				0,
				0,
				0,
				$overlay_width,
				$overlay_height,
				WATERMARK_OVERLAY_OPACITY
			);
			*/
			
			/*
			 * ALIGN TOP, RIGHT
			 */
			/*
			imagecopymerge(
				$source_gd_image,
				$overlay_gd_image,
				$source_width - $overlay_width,
				0,
				0,
				0,
				$overlay_width,
				$overlay_height,
				WATERMARK_OVERLAY_OPACITY
			);
			*/
			
			/*
			 * ALIGN BOTTOM, RIGHT
			 */
			 
			imagecopymerge(
				$source_gd_image,
				$overlay_gd_image,
				$source_width - $overlay_width,
				$source_height - $overlay_height,
				0,
				0,
				$overlay_width,
				$overlay_height,
				WATERMARK_OVERLAY_OPACITY
			);
			
			/*
			 * ALIGN BOTTOM, LEFT
			 */
			/*
			imagecopymerge(
				$source_gd_image,
				$overlay_gd_image,
				0,
				$source_height - $overlay_height,
				0,
				0,
				$overlay_width,
				$overlay_height,
				WATERMARK_OVERLAY_OPACITY
			);
		*/
			
			imagejpeg($source_gd_image, $output_file_path, WATERMARK_OUTPUT_QUALITY);
			imagedestroy($source_gd_image);
			imagedestroy($overlay_gd_image);
		}

		/*
		 * Uploaded file processing function
		 */

		
		public function process_image_upload($Field)
		{
			//echo $_SERVER['DOCUMENT_ROOT'].'/demo/images/bin/imagenews_root/';
			define('UPLOADED_IMAGE_DESTINATION', $_SERVER['DOCUMENT_ROOT'].'/demo/images/bin/imagenews_root/');//Images input
			define('PROCESSED_IMAGE_DESTINATION',$_SERVER['DOCUMENT_ROOT'].'/demo/images/bin/imagenews/');//Images output
			$temp_file_path = $_FILES[$Field]['tmp_name'];
			$temp_f_p = $temp_file_path[0];
			$temp_file_name = $_FILES[$Field]['name'];
			$temp_f_n= $temp_file_name[0];
			list(, , $temp_type) = getimagesize($temp_f_p);
			if ($temp_type === NULL) {
				return false;
			}
			switch ($temp_type) {
				case IMAGETYPE_GIF:
					break;
				case IMAGETYPE_JPEG:
					break;
				case IMAGETYPE_PNG:
					break;
				default:
					return false;
			}
			$uploaded_file_path = UPLOADED_IMAGE_DESTINATION . $temp_f_n;
			$processed_file_path = PROCESSED_IMAGE_DESTINATION . preg_replace('/\\.[^\\.]+$/', '.jpg', $temp_f_n);
			move_uploaded_file($temp_f_p, $uploaded_file_path);
			$result = $this->create_watermark($uploaded_file_path, $processed_file_path);
			if ($result === false) {
				return false;
			} else {
				return array($uploaded_file_path, $processed_file_path);
			}
		}
}
?>