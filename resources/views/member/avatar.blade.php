@extends('layout')

@section('style')
<style>
    img {
        max-width:none;
    }
    .head-box{
        width: 100%;
        height: 15rem;
        overflow: hidden;
        position: relative;
        background: #FFFFFF;
        text-align: center;
    }
    .head-box img{
        width: auto;
        margin: 0 auto;
        max-height:15rem;
    }
    .btn-box{
        width: 8rem;
        height: 2.5rem;
        margin: 0 auto;
        position: relative;
        background: #009A44;
        border-radius: .25rem;
        overflow: hidden;
        z-index: 10;
        font-size: 1rem;
        text-align: center;
        line-height: 2.5rem;
        color: #ffffff;
    }
    .upload-btn{
        position: absolute;
        width:100%;
        height: 100%;
        left: 0;
        top: 0;
        z-index: 100;
        opacity: 0;
    }
    .cropper-canvas, .cropper-crop-box, .cropper-drag-box, .cropper-modal, .cropper-wrap-box{
        z-index: 200;
    }
</style>
@endsection

@section('content')
    <header class="aui-bar aui-bar-nav">
        <a class="aui-pull-left aui-btn" onclick="history.back()">
            <span class="aui-iconfont aui-icon-left"></span>
        </a>
        <div class="aui-title">用户头像</div>

    </header>
    <section class="aui-content-padded">
        <div class="content" style="margin-top:5rem;">
            <div id="crop_result" style="text-align: center; position:relative; height: 11.5rem; line-height: 300px;">
                <img id="preview" src="{{ data_get( $user , 'avatar') ? asset( data_get( $user , 'avatar') ) : asset('/images/logo.png') }}" style="margin:0px auto;max-height:11.5rem;">
                <input class="upload-btn" data-url="{{route('wap.member.avatar')}}" type="file" name="demo" id="upload-btn" accept="image/*">
            </div>

        </div>
    </section>

@endsection


@section('script')
<script src="{{asset('vendor/alloy/transform.js')}}"></script>
<script src="{{asset('vendor/alloy/alloy-finger.js')}}"></script>
<script src="{{asset('vendor/alloy/alloy-crop.js')}}"></script>
<script>
    var crop_result = document.querySelector("#crop_result");

    function showToolPanel() {


        crop_result.style.display = "block";
    }

    function hideToolPanel() {
        crop_result.style.display = "none";
    }


    $(document).on('change' , '#upload-btn' , function( e ){
        var that = $(this);
        hideToolPanel();
        if( e.target.files.length == 0 ) {
            return false ;
        }
        var originPhoto = e.target.files[0]; // IE10+ 单文件上传取第一个
        window.originFileType = originPhoto.type; //暂存图片类型
        window.originFileName = originPhoto.name; //暂存图片名称
        var URL = window.URL || window.webkitURL ;
        var originPhotoURL;
        originPhotoURL = URL.createObjectURL(originPhoto);
        console.log( originPhotoURL );
        new AlloyCrop({
            image_src: originPhotoURL ,
            width: 320,
            height: 320,
            output: 1,
            className: 'm-clip-box',
            ok: function (base64, canvas) {
                $(crop_result).find('img').attr('src' , base64 );
                console.log( base64 );
                var post = {
                    'avatar': base64 ,
                    '_token': "{{csrf_token()}}"
                };
                $.post( that.data('url') , post , function( data ){
                    console.log( data );
                    //that.removeClass('submit');
                    if( data.errcode === 0 ) {
                        toast.success({title: data.msg });
                        setTimeout( function(){
                            history.go(-1);
                        } , 1500 );

                    } else {
                        toast.fail({title: data.msg });
                    }
                } , 'json' );
                showToolPanel();
            },
            cancel: function () {
                showToolPanel();
            } ,
            ok_text:'保存' ,
            cancel_text : '取消'
        });
    });
</script>
@endsection