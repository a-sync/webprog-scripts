<?php

function thumbnail($image_path,$thumb_path,$image_name,$thumb_width)
{
    $src_img = imagecreatefromjpeg("$image_path/$image_name");
    $origw=imagesx($src_img);
    $origh=imagesy($src_img);
    $new_w = $thumb_width;
    $diff=$origw/$new_w;
    $new_h=$new_w;
    $dst_img = imagecreate($new_w,$new_h);
    imagecopyresized($dst_img,$src_img,0,0,0,0,$new_w,$new_h,imagesx($src_img),imagesy($src_img));

    imagejpeg($dst_img, "$thumb_path/$image_name");
    return true;
}
