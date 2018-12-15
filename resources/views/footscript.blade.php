<script src="{{asset('packages/aui/script/aui-popup.js')}}"></script>
<script>
    var popup = new auiPopup();

    $('.menu-popup').unbind('click').bind('click' , function(){
        console.log('o')
        popup.show( document.getElementById("menu-popup-bottom") )
    })

    $(document).on('click' , '#foot .aui-bar-tab-item' , function( e ){

    }) ;
</script>