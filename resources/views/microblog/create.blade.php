@extends('layout')
@section('style')
<style>
#tags span.aui-btn {
  margin:0.3rem;
}
</style>
@endsection

@section('content')
<header class="aui-bar aui-bar-nav" id="aui-header">
    <div class="aui-title">发表资讯</div>
</header>
<section class="aui-content" id="microblog-content" style="margin-bottom: 3rem;">
<ul class="aui-list aui-form-list">
    <li class="aui-list-item">
        <div class="aui-list-item-inner">
            <div class="aui-list-item-input">
                <input type="text" id="title" placeholder="请填写资讯标题" value="{{ data_get( $blog , 'title' , '') }}">
            </div>
        </div>
    </li>
    <li class="aui-list-item">
        <div class="aui-list-item-inner">
            <div class="aui-list-item-input">
                <textarea id="content" placeholder="请填写资讯内容" style="height: 10rem;">{{ data_get( $blog , 'content' ) }}</textarea>
            </div>
        </div>
    </li>
    <li class="aui-list-item" id="tags" style="padding-left:0rem;">
        <div class="aui-list-item-inner">
            <div class="aui-list-item-input">
                @foreach( $cates as $k => $v )
                <span class="aui-btn aui-btn-outlined {{ data_get( $blog , 'cate_id' ) == $k ? 'aui-btn-danger' : ''  }}"  data-id="{{ $k }}">{{ $v }}</span>
                @endforeach
            </div>
        </div>
    </li>
    <li class="aui-list-item">
        <div class="aui-list-item-inner">
            <label>
            <input class="aui-checkbox" type="checkbox" name="radio" {{ data_get( $blog , 'allow_auth' ) ? "checked" : '' }} id="allow-auth">
                报审&nbsp;<span class="aui-font-size-12 aui-text-indigo">审核通过后可获得收益</span>
            </label>
        </div>
    </li>
    <li class="aui-list-item" style="padding-left:0;">
      <div class="aui-list-item-inner aui-content">
        <div class="aui-row covers" style="width:100%;">
            @if( data_get( $blog->extra , 'type') == 'image' )
                <div class="aui-row aui-row-padded">
                    @foreach( data_get( $blog->extra , 'data' , [] ) as $img )
                        <div class="aui-col-xs-4"
                             style="height:6rem;background-size:cover;background-repeat:no-repeat;background-image:url( {{asset( $img )}})"
                             data-src="{{ $img }}"
                        >

                        </div>
                    @endforeach
                </div>
            @endif


            <div class="aui-col-xs-4" id="btn-covers" style="height:6rem;position: relative;text-align: center;">
                <input type="file" class="btn-file" style="filter:alpha(opacity=0);opacity:0;width:100%;height:100%;">
                <span class="aui-iconfont aui-icon-plus btn-addimage" style="position:absolute;left:0;top:0;width:100%;font-size:4em;line-height:6rem;"></span>
            </div>
        </div>
      </div>
    </li>
    <li class="aui-list-item">
        <div class="aui-list-item-inner aui-list-item-center aui-list-item-btn">
            <div class="aui-btn aui-btn-block aui-btn-sm aui-btn-success btn-post">发布</div>
        </div>
    </li>
</ul>
</section>
    @include('foot')
@endsection


@section('script')
<script src="{{asset('packages/aui/script/aui-scroll.js')}}"></script>
@include('footscript')
<script>
    $('.btn-post').unbind('click').bind('click' , throttle( postForm , 1500 )) ;
    $('#tags .aui-btn').click( function(){
        $('#tags .aui-btn').removeClass('aui-btn-danger') ;
        $(this).addClass('aui-btn-danger');
    }) ;
    $('.covers .aui-col-xs-4').not('#btn-covers').bind('click' , remDom );

    var index = {{ count( data_get( $blog->extra , 'data' , [] ) ) }} ;
    $('.btn-addimage').click(function(){

        if( index >= 9 ) {
          toast.fail({
            title : '一次最多能上传9张图'
          })
          return false ;
        }
        $('.btn-file').trigger('click')
    })

    $('.btn-file').on('change' , function( e ){
        var originPhoto = e.target.files[0]; // IE10+ 单文件上传取第一个
        if( originPhoto.size >= 2*1024*1024 ) {
            toast.fail({
                'title' : '文件大小超过限制'
            })
            return false
        }

        // 过滤不是图片后缀的文件
        if (!/\.(jpeg|jpe|jpg|gif|png|webp)$/i.test(originPhoto.name)) {
            toast.fail({
                'title' : '文件类型不正确'
            })
            return false
        }

        console.log( originPhoto )
        var URL = window.URL || window.webkitURL ;
        var originPhotoURL;
        originPhotoURL = URL.createObjectURL(originPhoto);


        var reader = new FileReader();
        reader.onload = function (file) {
            index++ ;
            //console.log( this.result )
            var html = $('<div class="aui-col-xs-4" style="height:6rem;"></div>');
            $(html).css({
                'background-image' : 'url('+ this.result +')' ,
                'background-size' : 'cover' ,
                'background-repeat' : 'no-repeat'
            });
            $( html ).data('src' ,  this.result );
            $( html ).bind('click' , remDom );
            $( '#btn-covers').before( html );
        };
        reader.readAsDataURL( originPhoto );
        $('.btn-file').val('')
    }) ;

    function remDom( e , o ) {
        console.log( e )
        console.log( o )
        $(e.target ).remove();
        index-- ;
    }



    function postForm() {
        var title = $('#title').val().trim();
        var content = $('#content').val().trim();
        var tag = $('#tags .aui-btn-danger').data('id') ;
        var cover = [] ;
        var auth = $('#allow-auth').prop('checked') ? 1 : 0 ;

        $('.covers .aui-col-xs-4').not('#btn-covers').each( function(){
            cover.push( $(this).data('src') );
        });

        if( title == '' ) {
            toast.fail({
                title : '请填写标题'
            });
            return false
        }
        if( title.length > 30 ) {
            toast.fail({
                title : '标题长度不能超过30个字符'
            });
            return false
        }
        if( !tag ) {
            toast.fail({
                title : '请选择分类'
            });
            return false
        }
        if( content == '' && cover.length == 0 ) {
            toast.fail({
                title : '先写点什么再提交吧'
            });
            return false
        }
        console.log( cover );

        var id = {{$blog->id or 0}} ;
        let url = id > 0 ? '/microblog/updateblog' : '/microblog/createblog' ;
        $.post( url  , {
            id : id ,
            title : title ,
            info : content ,
            cate : tag ,
            uploaded : cover ,
            allow_auth : auth ,
            '_token': "{{csrf_token()}}"
        } , function( data ){
            if( data.errcode === 0 ) {
                toast.success({
                    title:data.msg
                });
                setTimeout( function(){
                    location.href = data.url
                } , 1500 )

            } else {
                toast.fail({
                    title : data.msg
                });
            }
        })
    }
</script>


@endsection

