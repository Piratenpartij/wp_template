"use strict";
/*global window: false */

var ymStation, ymBitrate, ymAutoPlay, ymMode, ymCodec, ymPlaying, ymMetaInterval, ymAudioElement, ymModeTextElement, ymCodecTextElement, ymStationNameTextElement, ymNowPlayingTextElement, ymStatusTextElement, ymBitrateSelectElement, ymChangeBitrate, ymPlay, ymChangeVolume, ymStop, ymFlashElement, ymMetaScriptElement;

function ymInterfaceBitrateChanged() {
        ymChangeBitrate(ymBitrateSelectElement.options[ymBitrateSelectElement.selectedIndex].value);
}

function ymUpdateBitrate(text) {
   var i;
   if (document.getElementById('ym_bitrate')) {

      if (!ymBitrateSelectElement) {
        ymBitrateSelectElement = document.createElement('select');
        ymBitrateSelectElement.setAttribute('name', 'ym_bitrate_select');

        var option = document.createElement('option');
        option.setAttribute('value', 'h');
        var optionText = document.createTextNode('h');
        option.appendChild(optionText);
        ymBitrateSelectElement.appendChild(option);

        if (ymCodec === 'aac') {
          option = document.createElement('option');
          option.setAttribute('value', 'm');
          optionText = document.createTextNode('m');
          option.appendChild(optionText);
          ymBitrateSelectElement.appendChild(option);

          option = document.createElement('option');
          option.setAttribute('value', 'l');
          optionText = document.createTextNode('l');
          option.appendChild(optionText);
          ymBitrateSelectElement.appendChild(option);
        }

        ymBitrateSelectElement.onchange = ymInterfaceBitrateChanged;

        document.getElementById('ym_bitrate').appendChild(ymBitrateSelectElement);

      }
      ymBitrateSelectElement.selectedIndex = 2;
      for (i=0; i< ymBitrateSelectElement.options.length; i++) {
        if (text == ymBitrateSelectElement.options[i].value) {
          ymBitrateSelectElement.selectedIndex = i;
        }
      }

      ymBitrateSelectElement.data = text;
   }
}

function ymFlashApp() {
    var position = navigator.appVersion.indexOf("MSIE");
    if (position > -1 && parseFloat(navigator.appVersion.split("MSIE")[1]) < 9) {
        return window.ymPlayer;
    } else if (position > -1 && parseFloat(navigator.appVersion.split("MSIE")[1]) > 9) {
        return document.getElementById('ymPlayer');
    } else {
        return document.ymPlayer;
    }
}

ymChangeBitrate = function(b) {
   if (ymCodec == 'vorbis' && (b == 'm' || b == 'l')) {
     b = 'h';
   }
   ymBitrate = b;
   if (ymMode == 'flash') {
      ymFlashApp().setBitrate(ymBitrate);
   }
   else {
      if (ymPlaying) {
         ymPlay();
      }
   }
   ymUpdateBitrate(ymBitrate);
};

function ymUpdateNowPlaying(text) {
   if (document.getElementById('ym_now_playing')) {
      if (!ymNowPlayingTextElement) {
        ymNowPlayingTextElement = document.createTextNode('');
        document.getElementById('ym_now_playing').appendChild(ymNowPlayingTextElement);
      }
      ymNowPlayingTextElement.data = text;
   }
}

function ymUpdateStationName(text) {
   if (document.getElementById('ym_station_name')) {
      if (!ymStationNameTextElement) {
        ymStationNameTextElement = document.createTextNode('');
        document.getElementById('ym_station_name').appendChild(ymStationNameTextElement);
      }
      ymStationNameTextElement.data = text;
   }
}

function ymUpdateMetaData() {
     if (ymMetaScriptElement) {
       document.body.removeChild(ymMetaScriptElement);
     }     

     ymMetaScriptElement = document.createElement('script');
     var rnd = Math.floor(Math.random()*10000000000000001);
     ymMetaScriptElement.setAttribute('src', 'http://www.yourmuze.com/perl/get_metadata.pl?station=' + ymStation + '&rnd=' + rnd);
     ymMetaScriptElement.setAttribute('type','text/javascript');
     
     document.body.appendChild(ymMetaScriptElement);
}


function ymReceiveMetaData(hash) {
    ymUpdateStationName(hash.station_title);                
    if (!ymPlaying) {
        hash.now_playing = '';
    }
    ymUpdateNowPlaying(hash.now_playing);
}

function ymUpdateStatus(text) {
   if (document.getElementById('ym_status')) {
      if (!ymStatusTextElement) {
        ymStatusTextElement = document.createTextNode('');
        document.getElementById('ym_status').appendChild(ymStatusTextElement);
      }
      ymStatusTextElement.data = text;
   }
}


ymPlay = function() {
   if (ymMode == 'flash') {
     ymFlashApp().doPlay();
   }
   else {
	
       var url = ymGetUrl();

       if (!ymAudioElement) {
           ymAudioElement = document.createElement('audio');
           ymAudioElement.volume = 0.5;
           if (ymAudioElement.volume != 0.5) {
	      if(document.getElementById('ym_volume_container')) {
                 document.getElementById('ym_volume_container').parentNode.replaceChild(document.createElement('div'), document.getElementById('ym_volume_container'));
	      }
           }
           else {
              ymAudioElement.volume = 1;
           }
           document.body.appendChild(ymAudioElement);
       }
       ymAudioElement.setAttribute('src', url);
       ymAudioElement.setAttribute('id', 'ymPlayer');
       ymAudioElement.setAttribute('autoplay', 'true');
       ymAudioElement.load();
/*       ymAudioElement.play();
       if (ymAudioElement.paused) {
          return;
       }
*/
   }
   ymPlaying = true;
   if (document.getElementById('ym_play')) {
     document.getElementById('ym_play').style.display='none';
   }
   if (document.getElementById('ym_stop') && !ymIsBlackBerry()) {
     document.getElementById('ym_stop').style.display='';
   }
   ymUpdateStatus('On');
//   ymUpdateMetaData();
//   ymMetaInterval = setInterval(ymUpdateMetaData, 5000);
};

function ymGetFlashVersion(){
  // ie
  try {
    try {
      // avoid fp6 minor version lookup issues
      // see: http://blog.deconcept.com/2006/01/11/getvariable-setvariable-crash-internet-explorer-flash-6/
      var axo = new ActiveXObject('ShockwaveFlash.ShockwaveFlash.6');
      try { axo.AllowScriptAccess = 'always'; }
      catch(e) { return '6,0,0'; }
    } catch(f) {}
    return new ActiveXObject('ShockwaveFlash.ShockwaveFlash').GetVariable('$version').replace(/\D+/g, ',').match(/^,?(.+),?$/)[1];
  // other browsers
  } catch(g) {
    try {
      if(navigator.mimeTypes["application/x-shockwave-flash"].enabledPlugin){
        return (navigator.plugins["Shockwave Flash 2.0"] || navigator.plugins["Shockwave Flash"]).description.replace(/\D+/g, ",").match(/^,?(.+),?$/)[1];
      }
    } catch(h) {}
  }
  return '0,0,0';
}


function ymGetFlashMajorVersion() {
        return(ymGetFlashVersion().split(',').shift());
}

function ymChangeStation(s) {
   ymStation = s;
   if (ymMode == 'flash') {
      ymFlashApp().setStation(ymStation);
   }
   else {
       if (ymPlaying) {
          ymPlay();
       }
   }
   //ymUpdateMetaData();
}


function ymInterfaceVolumeChanged() {
	ymChangeVolume(document.getElementById('ym_volume').value);
}

function ymInterfaceStationChanged() {
	ymChangeStation(document.getElementById('ym_username').value);
}


function ymInit(station, bitrate, autoplay) {
alert('11');
   if (ymIsBlackBerry()) {
	return;
   }
   ymStation = station;
   ymBitrate = bitrate;
   ymAutoPlay = autoplay;
   ymPlaying = false;
   var myAudio = document.createElement('audio');
   ymMode = 'flash';
   ymCodec = 'aac';

   if (document.getElementById('ym_volume')) {
     document.getElementById('ym_volume').onchange = ymInterfaceVolumeChanged;
   }
//   if (ymGetFlashMajorVersion() < 9 && !ymIsFixedFlashBrowser()) {
     if (myAudio.canPlayType) {
        if (!!myAudio.canPlayType && "" !== myAudio.canPlayType('audio/ogg; codecs="vorbis"')) {
            ymMode = 'html5';
            ymCodec = 'vorbis';
        }
        else if (!!myAudio.canPlayType && "" !== myAudio.canPlayType('audio/aac')) {
            ymMode = 'html5';
        }
     }
//   }
console.log(ymMode);
   if (ymMode == 'flash') {          
           ymFlashElement = document.createElement('div');
	   if (document.getElementById('ym_flash')) {
		   document.getElementById('ym_flash').appendChild(ymFlashElement);
	   }
	   else {
	           document.body.appendChild(ymFlashElement);
           }

           ymFlashElement.innerHTML='<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" id="ymPlayer" width="1" height="1" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab" wmode="transparent"> <param name="wmode" value="transparent" /> <param name="movie" value="/wp-content/themes/ppnl/ymInvisible.swf" /> <param name="quality" value="high" /> <param name="bgcolor" value="#869ca7" /> <param name="allowScriptAccess" value="always" /> <embed src="/wp-content/themes/ppnl/ymInvisible.swf" wmode="transparent" quality="high" bgcolor="#869ca7" width="1" height="1" name="ymPlayer" align="middle" play="true" loop="false" quality="high" allowScriptAccess="always" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer"> </embed> </object>';
   }
   else {
      if (document.getElementById('ym_play')) {
        document.getElementById('ym_play').onclick = ymPlay;
        document.getElementById('ym_play').style.display='';
      }


      if (document.getElementById('ym_stop')) {
        document.getElementById('ym_stop').onclick = ymStop;
        document.getElementById('ym_stop').style.display='none';
      }

      if (document.getElementById('ym_username')) {
        document.getElementById('ym_username').onchange = ymInterfaceStationChanged;
      }
      ymChangeBitrate(ymBitrate);
      if (ymAutoPlay && !ymIsIOS()) {
         ymPlay();
      }
      if (document.getElementById('ym_unsupported')) {
        document.getElementById('ym_unsupported').parentNode.replaceChild(document.createElement('div'), document.getElementById('ym_unsupported'));
      }
      if (document.getElementById('ym_mode')) {
         if (!ymModeTextElement) {
           ymModeTextElement = document.createTextNode('');
           document.getElementById('ym_mode').appendChild(ymModeTextElement);
         }
         ymModeTextElement.data = ymMode;
      }
      if (document.getElementById('ym_codec')) {
         if (!ymCodecTextElement) {
           ymCodecTextElement = document.createTextNode('');       
           document.getElementById('ym_codec').appendChild(ymCodecTextElement);
         }
         ymCodecTextElement.data = ymCodec;
      }
   }

}

function ymFlashIsReady() {
   if (document.getElementById('ym_play')) {
     document.getElementById('ym_play').onclick = ymPlay;
     document.getElementById('ym_play').style.display='';
   }

   if (document.getElementById('ym_stop')) {
     document.getElementById('ym_stop').onclick = ymStop;
     document.getElementById('ym_stop').style.display='none';
   }

   if (document.getElementById('ym_username')) {
     document.getElementById('ym_username').onchange = ymInterfaceStationChanged;
   }

   if (document.getElementById('ym_mode')) {
      if (!ymModeTextElement) {
        ymModeTextElement = document.createTextNode('');
        document.getElementById('ym_mode').appendChild(ymModeTextElement);
      }
      ymModeTextElement.data = ymMode;
   }
   if (document.getElementById('ym_codec')) {
      if (!ymCodecTextElement) {
        ymCodecTextElement = document.createTextNode('');
        document.getElementById('ym_codec').appendChild(ymCodecTextElement);
      }
      ymCodecTextElement.data = ymCodec;
   }

   if (document.getElementById('ym_unsupported')) {
     document.getElementById('ym_unsupported').parentNode.replaceChild(document.createElement('div'), document.getElementById('ym_unsupported'));
   }

   ymChangeStation(ymStation);
   ymChangeBitrate(ymBitrate);
   if (ymAutoPlay) {
           ymPlay();
   }
}


ymStop = function(){
   if (ymMode == 'flash') {
      ymFlashApp().doStop();
   }
   else {
      ymAudioElement.pause();
      ymAudioElement.setAttribute('src', '');

      ymAudioElement.load();

   }
   ymPlaying = false;
   ymUpdateStatus('Off');
   clearInterval(ymMetaInterval);
   ymUpdateNowPlaying('');
   if (document.getElementById('ym_play')) {
     document.getElementById('ym_play').style.display='';
   }
   if (document.getElementById('ym_stop')) {
     document.getElementById('ym_stop').style.display='none';
   }
};
ymChangeVolume = function(v) {
   if (ymMode == 'flash') {
      ymFlashApp().setVolume(v);
   }
   else {
      ymAudioElement.volume = v / 100;
   }
};

function ymIsIOS() {
        if (navigator.userAgent.indexOf("Safari") > 0 && (navigator.userAgent.indexOf("Mobile") > 0 || navigator.userAgent.indexOf("iPad") > 0 || navigator.userAgent.indexOf("iPod") > 0 || navigator.userAgent.indexOf("iPhone") > 0
                || navigator.userAgent.indexOf("AppleCoreMedia") > 0) && !ymIsAndroid() && !ymIsBlackBerry()) {
                return true;
        }
        return false;
}

function ymIsAndroid() {
        if (navigator.userAgent.indexOf("Android") > 0) {
                return true;
        }
        false;
}



function ymIsFixedFlashBrowser() {  // Android (mobile) that calls itself "Safari".
        var yes = false;
        if (ymIsAndroid()) {
                yes = true;
        }
        return yes;
}

function ymIsBlackBerry() {
    if (navigator.userAgent.indexOf("BlackBerry") > 0) {
        return true;
    }
    return false;
}

function ymGetUrl() {
       var rnd = Math.floor(Math.random()*10000000000000001);
       var url = 'http://www.yourmuze.com/desktop/'+ymStation+'/' + ymBitrate + '.pls?rnd=' + rnd;

       if (ymCodec == 'vorbis') {
           url = 'http://http.yourmuze.com/play/' + ymStation + '/' + ymBitrate + '-vorbis.ogg' + '?rnd=' + rnd;
       }
       if (ymIsBlackBerry()) {
           url = 'http://http.yourmuze.com/play/' + ymStation + '/' + ymBitrate + '.aac' + '?rnd=' + rnd;
       }
  
       return url;
}


