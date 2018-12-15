window.onload = function() {
    var g_audio = window.g_audio = document.getElementById('tipAudio'); //创建一个audio播放器
    function loadAudio (){
        g_audio.touchstart = true;
        g_audio.loop = false;
        g_audio.autoplay = true;
        g_audio.isLoadedmetadata = false;
        g_audio.touchstart = true;
        g_audio.audio = true;
        //g_audio.load();
        g_audio.play();
        g_audio.pause();
        
        return false;
    }

    g_audio.addEventListener('ended' , function(){
        g_audio.load()
        g_audio.pause()
    })

    g_audio.addEventListener('play' , function(){
        document.removeEventListener('click' , loadAudio ) ;
    } , true )

    

    if (/i(Phone|P(o|a)d)|Mac/.test(navigator.userAgent)) {
        console.log('load')
        document.addEventListener('click' , loadAudio  , false )
    }

    g_audio.addEventListener('play' , function(){
        document.removeEventListener('touchstart' , loadAudio ) ;
    } , true )


    if (/i(Phone|P(o|a)d)|Mac/.test(navigator.userAgent)) {
        document.addEventListener('touchstart' , loadAudio  , false )
    }

};