<?php
$gOverlayLocation = 'overlays';
$gValidImages = array('png' => 3,'jpg' => 2, 'gif' => 1);
$gMaxMegaPixel = 9000000;

function scanOverlayImages() {
	global $gOverlayLocation, $gValidImages;
	$lImageList = array();
	$directory = new DirectoryIterator($gOverlayLocation);
	foreach ($directory as $fileinfo) {
	    if ($fileinfo->isFile()) {
	        $extension = strtolower(pathinfo($fileinfo->getFilename(), PATHINFO_EXTENSION));
	        if (in_array($extension, array_keys($gValidImages))) {
	            $lImageList[] = $fileinfo->getFilename();
	        }
	    }
	}
	return $lImageList;
}

function imageCreateFromAny($filepath) {
	global $gValidImages;
    $type = exif_imagetype($filepath); // [] if you don't have exif you could use getImageSize()

    if (!in_array($type, $gValidImages)) {
        return false;
    }
    switch ($type) {
        case 1 :
            $im = imageCreateFromGif($filepath);
        break;
        case 2 :
            $im = imageCreateFromJpeg($filepath);
        break;
        case 3 :
            $im = imageCreateFromPng($filepath);
        break;
    }
    return $im;
}

function processImages($pPhoto, $pOverlay, $pOverlayPosition = 'rb', $pOverlaySize = 30) {
	global $gOverlayLocation, $gMaxMegaPixel, $gValidImages;

	$pOverlay = $gOverlayLocation . '/' . $pOverlay;

	if (   !file_exists($pPhoto)
		|| !file_exists($pOverlay)
		|| !in_array(exif_imagetype($pPhoto), $gValidImages)
		|| !in_array(exif_imagetype($pOverlay), $gValidImages)) return false; // Files do not exist on server or are not valid images

	$lPhotoSize = getimagesize($pPhoto);
	$lOverlaySize = getimagesize($pOverlay);

	if (   $lPhotoSize[0] * $lPhotoSize[1] > $gMaxMegaPixel
		|| $lOverlaySize[0] * $lOverlaySize[1] > $gMaxMegaPixel) return false; // Image(s) is/are to big


	$lVerhoudingen = array('width' => ($lOverlaySize[0] / $lPhotoSize[0]) * 100, 'height' => ($lOverlaySize[1] / $lPhotoSize[1]) * 100);
	$lCurrentOverlaySize = ($lVerhoudingen['width'] <= $lVerhoudingen['height'] ? $lVerhoudingen['width']  : $lVerhoudingen['height'] );

	$pPhoto = imageCreateFromAny($pPhoto);
	$pOverlay = imageCreateFromAny($pOverlay);
	if ($lCurrentOverlaySize > $pOverlaySize) {
		// Overlay image is to big, downscale it
		$pOverlay = imagescale ($pOverlay, $lOverlaySize[0] * ($pOverlaySize/$lCurrentOverlaySize), $lOverlaySize[1] * ($pOverlaySize/$lCurrentOverlaySize));
	} else {
		// Photo is to big, downscale it
		$pPhoto = imagescale ($pPhoto, $lPhotoSize[0] * ($lCurrentOverlaySize/$pOverlaySize), $lPhotoSize[1] * ($lCurrentOverlaySize/$pOverlaySize));
	}

	// Merge images
	$pOverlayTopPos = imagesx($pPhoto) - imagesx($pOverlay);
	$pOverlayLeftPos = imagesy($pPhoto) - imagesy($pOverlay);
	switch (strtolower($pOverlayPosition)) {
		case 'lt':
			$pOverlayTopPos = $pOverlayLeftPos = 0;
		break;
		case 'rt':
			$pOverlayLeftPos = 0;
		break;
		case 'lb':
			$pOverlayTopPos = 0;
		break;
	}

	imagecopy($pPhoto, $pOverlay, $pOverlayTopPos, $pOverlayLeftPos, 0, 0, imagesx($pOverlay), imagesy($pOverlay));

	// Get the raw image data and use it as a base64 image string
	ob_start();
	imagepng($pPhoto);
	$image_data = ob_get_contents();
	ob_end_clean();
	echo '<img alt="Embedded Image" src="data:image/png;base64,' . base64_encode($image_data) . '" />';
}
?>
<html>
<head>
	<title>Piratenpartij Pasfoto Generator</title>
	<script type="text/javascript" src="jquery-3.0.0.min.js"></script>
	<script type="text/javascript">
		function styleMe() {
			if(window.top && window.top.location.href != document.location.href) {
				var linkrels = window.top.document.getElementsByTagName('link');
				var small_head = document.getElementsByTagName('head').item(0);
    				for (var i = 0, max = linkrels.length; i < max; i++) {
      					if (linkrels[i].rel && linkrels[i].rel == 'stylesheet') {
        					var thestyle = document.createElement('link');
						var attrib = linkrels[i].attributes;
        					for (var j = 0, attribmax = attrib.length; j < attribmax; j++) {
          						thestyle.setAttribute(attrib[j].nodeName, attrib[j].nodeValue);
        					}
       						small_head.appendChild(thestyle);
      					}
	    			}
			}
		}

		$(document).ready(function(){
			styleMe();
			$('div.overlay').on('click',function(){
				$('div.overlay').removeClass('checked');
				$(this).children('input').attr('checked','checked');
				$(this).addClass('checked');
			});
			$('table.position td').on('click',function(){
				$('table.position td').removeClass('checked');
				$(this).children('input').attr('checked','checked');
				$(this).addClass('checked');
			});
		});
	</script>
	<style>
		body {
			background: #fff !important;
		}

		h2 {
			margin-top: 10px;
		}

		table.wrapper {
			width: 100%;
		}

		table.wrapper td {
			vertical-align: top;
			text-align: left;
			white-space: nowrap;
		}

		table.wrapper td.result {
			padding-left: 5px;
			border-left: solid 2px #876fa1;
		}

		div.overlay {
			float: left;
			width: 75px;
			height: 75px;
			margin-right: 5px;
			position: relative;
                        border: solid 1px #876fa1;
		}

		div.overlay:hover,
		div.overlay.checked,
		table.position td:hover,
		table.position td.checked {
			background-color: #876fa1;
		}

		div.overlay input {
			visibility: hidden;
		}

		div.overlay img {
			position: absolute;
			width: 100%;
			top: 0px;
			left: 0px;
		}

		table.position {
			width: 200px;
			height: 200px;
		}

		table.position td {
			vertical-align: middle;
			text-align: center;
			border: solid 1px #876fa1;
		}

		table.position input {
			visibility: hidden;
		}
	</style>
</head>
<body>
	<form action="" method="post" enctype="multipart/form-data">
		<table class="wrapper">
			<tr>
				<td style="width:30%">
					<h2>1. Kies logo</h2>
					<?php
						foreach (scanOverlayImages() as $lImage) {
							echo '<div class="overlay"><input type="radio" name="overlay" value="' . $lImage . '"> <img src="overlays/' . $lImage . '"></div>';
						}
					?>
				</td>
				<td rowspan="5" class="result">
				    <h2>6. Resultaat</h2>
				    <?php
				    	if (!empty($_POST['submit']) && $_POST['submit']) {
				    		$check = getimagesize($_FILES["pasfoto"]["tmp_name"]);
						    if($check !== false) {
						        processImages($_FILES["pasfoto"]["tmp_name"],$_POST['overlay'],$_POST['position'],$_POST['size']);
						        echo "<br /><br />Sla deze pasfoto op als plaatje. Je kunt niet linken naar dit plaatje!";
						    } else {
						        echo "File is not an image.";
						    }
				    	}
				    ?>
				</td>
			</tr>
			<tr>
				<td>
					<h2>2. Kies positie</h2>
					<table class="position">
						<tr>
							<td><input type="radio" name="position" value="lt"></td><td><input type="radio" name="position" value="rt"></td>
						</tr>
						<tr>
							<td><input type="radio" name="position" value="lb"></td><td><input type="radio" name="position" value="rb"></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td>
					<h2>3. Kies verhouding</h2>
					<select name="size">
						<?php
							for ($size = 15; $size <= 60; $size++) {
								echo '<option value="' . $size . '" ' . ($size == 35 ? 'selected' : '') . '>' . $size . '%</option>';
							}
						?>
					</select>
				</td>
			</tr>
			<tr>
				<td>
					<h2>4. Upload je (pas)foto</h2><p>Hoe groter de pasfoto, hoe beter.<br />Maximaal 3000 x 3000 pixels</p>
				    <input type="file" name="pasfoto" id="pasfoto">
				</td>
			</tr>
			<tr>
				<td>
				    <h2>5. Maak!</h2>
				    <input type="submit" value="Maak Piratenpartij Pasfoto" name="submit">
				</td>
			</tr>
		</table>
	</form>
</body>
</html>
