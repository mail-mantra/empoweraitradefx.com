<?php
if(!function_exists('post_data')) {
    function post_data()
    {
        $post_data = $_POST;
        array_walk_recursive($post_data, function(&$v) {
            $v = addslashes(trim($v));
        });
        return $post_data;
    }
}

if(!function_exists('clean_file_name')) {
    function clean_file_name($file_name)
    {
        $file_name = strtolower(str_replace(" ","-",$file_name ));
        $file_name_arr=explode('.',$file_name);
        $file_name_extention = array_pop($file_name_arr);
        $file_name_name = implode('',$file_name_arr);
        // $file_name_name = preg_replace('/[^A-Za-z0-9\-]/', '', $file_name_name);
        $file_name_name = preg_replace('/[^A-Za-z0-9]/', '', $file_name_name);
        $file_name_name = (strlen($file_name_name)>100)?(uniqid().substr($file_name_name,0, 50)):$file_name_name;
        $file_name=$file_name_name.".".$file_name_extention;
        return $file_name;
    }
}

if(!function_exists('get_space_url')) {
    function get_space_url($file_path, $keyPrefix="" )
    {
        global  $space;

        if(filter_var($file_path, FILTER_VALIDATE_URL)) {
            try {
                if($space->DoesObjectExist($file_path)) {
                    $space->MakePublic($file_path);
                }
            }
            catch(Exception $exception) {
//        print_r($exception);
            }
        }
        else {
            $file_path_clean = $keyPrefix.clean_file_name($file_path);
            $file_path = $keyPrefix.$file_path;
            try {
                if($space->DoesObjectExist($file_path)) {
                    $space->MakePublic($file_path);
                    $file_path = 'https://'.SPACE_SPACENAME.'.'.SPACE_REGION.'.digitaloceanspaces.com/'.$file_path;
                }
                elseif($space->DoesObjectExist($file_path_clean)) {
                    $space->MakePublic($file_path_clean);
                    $file_path = 'https://'.SPACE_SPACENAME.'.'.SPACE_REGION.'.digitaloceanspaces.com/'.$file_path_clean;
                }
                else {
                    // $file_path = 'https://'.SPACE_SPACENAME.'.'.SPACE_REGION.'.digitaloceanspaces.com/'.$file_path_clean;
                    // $file_path = PROJECT_URL.'/profit-login/web-assets/images/'.$file_path;
                    $file_path = BASE_URL.'business-login/web-assets/images/'.$file_path;
                }
            }
            catch(Exception $exception) {
//        print_r($exception);
            }
        }
        return $file_path;
    }
}

$phpFileUploadErrors = array(
    0 => 'There is no error, the file uploaded with success',
    1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
    2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
    3 => 'The uploaded file was only partially uploaded',
    4 => 'No file was uploaded',
    6 => 'Missing a temporary folder',
    7 => 'Failed to write file to disk.',
    8 => 'A PHP extension stopped the file upload.',
);

if(!function_exists('compression_quality')) {
    function compression_quality($file)
    {
        $size = (int)$file['size']; // bytes
        $size_kb = $size / 1024; // KB

        $compression_quality = (1024 * 2) / $size_kb;

        if($compression_quality > 100) {
            $compression_quality = 100;
        }

        return ceil($compression_quality);
    }
}

if(!function_exists('webpConvert2_mm')) {
    function webpConvert2_mm($file, $compression_quality = 10)
    {
        // check if file exists
        if(!file_exists($file)) {
            return false;
        }

        $mime_type = mime_content_type($file);

        $output_file = $file.'.webp';
        if(file_exists($output_file)) {
            return $output_file;
        }
        if(function_exists('imagewebp')) {
            switch($mime_type) {
                case 'image/gif':
                    $image = imagecreatefromgif($file);
                    break;
                case 'image/pjpeg':
                case 'image/jpeg':
                    $image = imagecreatefromjpeg($file);
                    break;
                case 'image/png':
                case 'image/x-png':
                    $image = imagecreatefrompng($file);
                    imagepalettetotruecolor($image);
                    imagealphablending($image, true);
                    imagesavealpha($image, true);
                    break;
                case 'image/bmp': // IMAGETYPE_BMP
                    $image = imagecreatefrombmp($file);
                    break;
                case 'image/webp': //IMAGETYPE_Webp
                    return false;
                    break;
                case 'image/xbm': //IMAGETYPE_XBM
                    $image = imagecreatefromxbm($file);
                    break;
                default:
                    return false;
            }
            // Save the image
            $result = imagewebp($image, $output_file, $compression_quality);
            if(false === $result) {
                return false;
            }
            // Free up memory
            imagedestroy($image);
            return $output_file;
        }
        elseif(class_exists('Imagick')) {
            $image = new Imagick();
            $image->readImage($file);
            if($mime_type === "image/x-png") {
                $image->setImageFormat('webp');
                $image->setImageCompressionQuality($compression_quality);
                $image->setOption('webp:lossless', 'true');
            }
            $image->writeImage($output_file);
            return $output_file;
        }
        return false;
    }
}