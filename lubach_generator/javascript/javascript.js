/*
		Lubach meme generator v1.0
		Made by Joshua (TheYOSH) Rubingh for Piratenpartij Nederland - 25 Feb 2017

		This script uses the latest HTML5 techniques for offline file reading and manipulation.
		No data is uploaded to the server. All graphical calculation is done in the local browser.
		
		It depends on jQuery!
*/

// Setup
basepath = '/wp-content/themes/ppnl/lubach_generator/';
//basepath = '';

function hide_controls() {
//  jQuery('#generator_button').hide();
}

function show_controls() {
//  jQuery('#generator_button').show();
}

function save() {
  hide_controls();
  html2canvas(document.getElementById('lubachgenerator'), {
    onrendered: function(canvas) {
      jQuery.ajax({
        url: basepath + 'save.php',
        type : 'POST',
        data : { image : canvas.toDataURL() },
        success : function(data) {
           var url = location.protocol + '//' + location.host + data;
//           alert(data + ',' + url);
        }
      });
      show_controls();
    }
  });
}

function generate() {
  hide_controls();
  html2canvas(document.getElementById('lubachgenerator'), {
    onrendered: function(canvas) {
      var image = jQuery('#lubachgenerator');
      Canvas2Image.saveAsPNG(canvas, image.width(), image.height(), "Zondag_met_PPNL");
      show_controls();
    }
  });
}

function joke() {
  var ooglapje = jQuery('#ooglapje');
  if (jQuery('#lubach').text().indexOf('piraat') !== -1) {
    if (ooglapje.length === 0) {
        ooglapje = jQuery('<img>').attr({'id':'ooglapje', 'src':basepath + 'images/ooglapje.png', 'title':'Lubach wordt piraat'});
        jQuery('#lubachgenerator').append(ooglapje);
    } else {
      ooglapje.show();
    }
  } else {
    ooglapje.hide();
  }
}

function loadLubach() {
  var head = jQuery('head');
  head.append('<link rel="stylesheet" href="' + basepath + 'css/style.css" type="text/css" />');
  head.append('<script src="' + basepath + 'javascript/html2canvas.js" type="text/javascript" ></script>');
  head.append('<script src="' + basepath + 'javascript/canvas2image.js" type="text/javascript" ></script>');
  var generator = jQuery('<div>').attr({'id':'lubachgenerator'})
                    .append(jQuery('<img>').attr({'src':basepath + 'background.png','title':'Lubach meme generator','class':'background'}))
                    .append(jQuery('<div>').attr({'id':'lubach','class':'baloon'})
                        .append(jQuery('<div>').attr({'class':'meme','contenteditable':'true','spellcheck':'false'}).html('<br />Type hier je vraag<br /><br />#hoedan'))
                    )
                    .append(jQuery('<div>').attr({'id':'ancilla','class':'baloon'})
                        .append(jQuery('<div>').attr({'class':'meme','contenteditable':'true','spellcheck':'false'}).html('<br />Type hier je oplossing<br /><br />#ppnl'))
                    )
                    .append(jQuery('<img>').attr({'id':'generator_button','class':'noglow','title':'Klik om Lubach meme te genereren','src':basepath + 'images/PPNL_Egg.gif'}));

  jQuery('#memegenerator').html(generator);
  jQuery('.baloon').on('keyup',joke);
  jQuery('#generator_button').on('click',generate);
}
jQuery(document).ready(function(){
    loadLubach();
}); 
