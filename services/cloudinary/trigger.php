<?php 
require 'setting.php';

function cloudinary_trigger($file_path, $type, $folder){

    $return = \Cloudinary\Uploader::upload_large($file_path, ["resource_type" => $type,'public_id' => $folder]);  
    return $return;
}
?>