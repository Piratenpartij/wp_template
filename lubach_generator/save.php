<?php

header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

if (isset($_POST['image']) && !empty($_POST['image']) && strlen($_POST['image']) < 1024 * 1024) {
  $image = explode(',', $_POST['image']);
  if ($image[0] == 'data:image/png;base64') {
    $image = imagecreatefromstring(base64_decode($image[1]));
    $image_file = 'memes/Zondag_met_PPNL_' . uniqid() . '.png';
    if (imagepng($image,$image_file)) {
      echo "/wp-content/themes/ppnl/lubach_generator/" . $image_file;
    }
  }
}
