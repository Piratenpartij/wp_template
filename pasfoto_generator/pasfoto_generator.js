/*
		Free Pirateparty photo generator v1.2
		Made by Joshua (TheYOSH) Rubingh for Piratenpartij Nederland - 10 Sept 2016

		This script uses the latest HTML5 techniques for offline file reading and manipulation.
		No data is uploaded to the server. All graphical calculation is done in the local browser.
*/

// Setup
var pasfoto_source = new Image(),
    pasfoto_overlay = new Image(),
    pp_canvas = null,
    pp_max_width = 400,
    pp_max_height = 500,
    defaultpadding = 5,
    rotation = 0,
    basepath = '/',
    overlay_images = new Array('purple_bar1.png','piratenpartij_nederland_logo_zwart2.png','piratenpartij_nederland_logo_zwart.png','piratenpartij_nederland_logo_wit.png');

basepath = '/wp-content/themes/ppnl/pasfoto_generator/';
//basepath = '';
// Load the canvas loader listen for new images
pasfoto_source.onload = function() {
  rotation = 0;
  generate_pasfoto();
};

jQuery(document).ready(function(){
  load_app();

  // Listener for new image
  document.getElementById('pasfoto').addEventListener('change', handleFileSelect, false);
  // Listener for image rotations
  document.getElementById("rotate").addEventListener('click', rotate_image, false);
  // Listener for download action
  document.getElementById("dl").addEventListener('click', download_pasfoto, false);

  // Load initial free image
  pasfoto_source.src = basepath + 'pasfoto.jpg';
});

function rotate_image() {
  // Rotate image wit 90 degrees per step.
  rotation = rotation + 90;
  rotation = rotation % 360;

  // Generate the new photo
  generate_pasfoto();
}

// Borrowed from: http://stackoverflow.com/questions/13073647/crop-canvas-export-html5-canvas-with-certain-width-and-height#13074780
function generate_pasfoto() {
  padding = defaultpadding;
  pp_canvas = document.getElementsByTagName("canvas")[0];
  pp_canvas.width = pp_max_width;
  pp_canvas.height = pp_max_height;
  // Initialize the image such as rotation and scaling..
  initialize_pasfoto();

  // Here we add the image overlay
  var pp_context = pp_canvas.getContext("2d");
  // Load overlay image
  if (jQuery('div.overlay.checked').length == 1) {
    pasfoto_overlay.src = jQuery('div.overlay.checked img').attr('src');
    if (pasfoto_overlay.src.indexOf('purple_bar') >= 0) {
      padding = 0;
    }
    var xpos = padding, ypos = padding;
    // Check for selected position
    if (jQuery('table.position td.checked').length == 1) {
      switch (jQuery('table.position td.checked').attr('id')) {
        case 'lt':
          // Default
          break;

        case 'rt':
          xpos = pp_canvas.width - (pasfoto_overlay.width + padding);
          break;

        case 'lb':
          ypos = pp_canvas.height - (pasfoto_overlay.height + padding);
          break;

        case 'rb':
          xpos = pp_canvas.width - (pasfoto_overlay.width + padding);
          ypos = pp_canvas.height - (pasfoto_overlay.height + padding);
          break;
      }
    }
    // Draw the overlay on the main image on selected position
    pp_context.drawImage(pasfoto_overlay, xpos, ypos, pasfoto_overlay.width, pasfoto_overlay.height);
  }
}

// Borrowed from: https://stackoverflow.com/questions/17411991/html5-canvas-rotate-image
function initialize_pasfoto() {
  // Rotation
  var tempCanvas = document.createElement("canvas");
  var pp_context = tempCanvas.getContext("2d");

  if(rotation == 90 || rotation == 270) {
      tempCanvas.width = pasfoto_source.height;
      tempCanvas.height = pasfoto_source.width;
  } else {
      tempCanvas.width = pasfoto_source.width;
      tempCanvas.height = pasfoto_source.height;
  }

  pp_context.clearRect(0,0,tempCanvas.width,tempCanvas.height);
  if(rotation == 90 || rotation == 270) {
      pp_context.translate(pasfoto_source.height/2,pasfoto_source.width/2);
  } else {
      pp_context.translate(pasfoto_source.width/2,pasfoto_source.height/2);
  }
  pp_context.rotate(rotation*Math.PI/180);
  pp_context.drawImage(pasfoto_source,-pasfoto_source.width/2,-pasfoto_source.height/2);

  // Scaling up / down....
  var scale_width = 0, scale_height = 0, scale = 0;
  scale_width = pp_max_width / tempCanvas.width;
  scale_height = pp_max_height / tempCanvas.height;
  scale = (scale_width < scale_height ? scale_width : scale_height);

  pp_canvas.width = scale * tempCanvas.width;
  pp_canvas.height = scale * tempCanvas.height;

  pp_context = pp_canvas.getContext("2d");
  pp_context.drawImage(tempCanvas,0,0,pp_canvas.width,pp_canvas.height);
}

// Borrowed from: http://stackoverflow.com/questions/12796513/html5-canvas-to-png-file/12796748
function download_pasfoto() {
  var dt = pp_canvas.toDataURL('image/png');
  /* Change MIME type to trick the browser to downlaod the file instead of displaying it */
  //dt = dt.replace(/^data:image\/[^;]*/, 'data:application/octet-stream');

  /* In addition to <a>'s "download" attribute, you can define HTTP-style headers */
  //dt = dt.replace(/^data:application\/octet-stream/, 'data:application/octet-stream;headers=Content-Disposition%3A%20attachment%3B%20filename=Canvas.png');
  this.href = dt;
}

// Borrowed from: http://www.html5rocks.com/en/tutorials/file/dndfiles/
function handleFileSelect(evt) {
  var files = evt.target.files; // FileList object

  // Loop through the FileList and render image files as thumbnails.
  for (var i = 0, f; f = files[i]; i++) {
    // Only process image files.
    if (!f.type.match('image.*')) {
      continue;
    }
    var reader = new FileReader();
    // Closure to capture the file information.
    reader.onload = (function(theFile) {
      return function(e) {
        // Load image
        pasfoto_source.src = e.target.result;
      };
    })(f);

    // Read in the image file as a data URL.
    reader.readAsDataURL(f);
    // Only support 1 image!
    jQuery('td.result a').hide();
    break;
  }
}

function load_app() {
  // Check for the various File API support.
  if (window.File && window.FileReader && window.FileList && window.Blob) {
    // Great success! All the File APIs are supported.
  } else {
    alert('The File APIs are not fully supported in this browser.');
  }

  var div1 = jQuery('<div>').addClass('generator_menu');
  div1.append(jQuery('<h2>').text('1. Selecteer pasfoto'));
  div1.append(jQuery('<input>').attr({type:'file', name:'pasfoto', id: 'pasfoto'}));
  div1.append(jQuery('<br>'));
  div1.append(jQuery('<input>').attr({type:'button', name:'rotate', id: 'rotate', value:'Draai'}));

  jQuery('div#pasfoto_generator').html('');
  jQuery('div#pasfoto_generator').append(div1);

  var div2 = jQuery('<div>').addClass('generator_preview');
  div2.append(jQuery('<h2>').text('Preview'));
  div2.append(jQuery('<canvas>').attr({width:pp_max_width, height:pp_max_height}));

  jQuery('div#pasfoto_generator').append(div2);

  var div3 = jQuery('<div>').addClass('generator_menu');
  div3.append(jQuery('<h2>').text('2. Selecteer logo'));

  jQuery.each(overlay_images,function(index,value){
    div3.append(jQuery('<div>').addClass('overlay' + (index === 0 ? ' checked' : '')).append(jQuery('<img>').attr({src: basepath + 'overlays/' + value})));
  });

  div3.append(jQuery('<h2>').text('3. Selecteer positie'));
  div3.append('<table class="position"><tr><td id="lt"></td><td id="rt"></td></tr><tr><td id="lb"></td><td class="checked" id="rb"></td></tr></table>');
  div3.append(jQuery('<h2>').text('4. Sla op!'));
  div3.append(jQuery('<a>').addClass('download_button').attr({id:'dl', href:'#', download:'Piratenpartij_pasfoto.png', target:'_blank'}).text('Piratenpartij pasfoto'));
  jQuery('div#pasfoto_generator').append(div3);

  jQuery('div.overlay').on('click',function(){
    jQuery('div.overlay').removeClass('checked');
    jQuery(this).addClass('checked');
    generate_pasfoto();
  });

  jQuery('table.position td').on('click',function(){
    jQuery('table.position td').removeClass('checked');
    jQuery(this).addClass('checked');
    generate_pasfoto();
  });
}


