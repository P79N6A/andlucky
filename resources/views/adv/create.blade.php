@extends('layout')

@section('style')
<style>
    .aui-tags {
        margin-bottom: .5rem;
    }
    .aui-picker-header {
        background:#734d41;
        color: #ffffff;
    }
    #btn-tags .aui-btn {
        margin:.3rem;
    }
</style>
@endsection

@section('content')
    <header class="aui-bar aui-bar-nav">
        <div class="aui-title">广告发布</div>

    </header>
    <section class="aui-content" style="margin-bottom:3rem;">
        <ul class="aui-list aui-form-list">
            <li class="aui-list-item">
                <div class="aui-list-item-inner">
                    <div class="aui-list-item-label">
                        标题
                    </div>
                    <div class="aui-list-item-input">
                        <input type="text" id="title" placeholder="请输入广告标题" value="{{data_get( $adv , 'title')}}">
                    </div>
                </div>
            </li>
            <li class="aui-list-item" id="btn-tags">
                <div class="aui-list-item-inner">
                    <div class="aui-list-item-label">
                        标签
                    </div>
                    <div class="aui-list-item-input" placeholder="请选择标签">
                        @if( $adv->tags )
                        <?php
                            $tags = explode(',' , $adv->tags );
                            foreach( $tags as $tag ) :
                        ?>
                            <span class="aui-btn aui-btn-dander" data-id='{{$tag}}'>{{$tag}}</span>
                        <?php
                            endforeach ;
                        ?>
                        @endif
                    </div>
                </div>
            </li>
            <li class="aui-list-item" id="btn-price">
                <div class="aui-list-item-inner">
                    <div class="aui-list-item-label">
                        价格
                    </div>
                    <div class="aui-list-item-input">
                        {{ data_get( $adv , 'click_price') }}
                    </div>
                </div>
            </li>
            <li class="aui-list-item">
                <div class="aui-list-item-inner">
                    <div class="aui-list-item-label">
                        次数
                    </div>
                    <div class="aui-list-item-input">
                        <input type="text" id="click-times" placeholder="请输入总点击次数" value="{{ $adv->click_times or 1000 }}">
                    </div>
                </div>
            </li>
            <li class="aui-list-item">
                <div class="aui-list-item-inner">
                    <div class="aui-list-item-label">
                        天数
                    </div>
                    <div class="aui-list-item-input">
                        <input type="text" id="day" placeholder="请输入天数" value="{{$adv->last_days or 15}}">
                    </div>
                </div>
            </li>
            <li class="aui-list-item">
                <div class="aui-list-item-inner">
                    <div class="aui-list-item-label">
                        网扯
                    </div>
                    <div class="aui-list-item-input">
                        <input type="url" id="url" placeholder="请输入网址http://" value="{{data_get( $adv , 'url' , '')}}" >
                    </div>
                </div>
            </li>
            <li class="aui-list-item">
                <div class="aui-list-item-inner">
                    <div class="aui-list-item-label">
                        总费用
                    </div>
                    <div class="aui-list-item-input total-price">
                        {{ data_get( $adv , 'total_price') }}
                    </div>
                </div>
            </li>
            <li class="aui-list-header">广告图片</li>
            <li class="aui-list-item" style="padding-left:0;">
                <div class="aui-list-item-inner">
                    <div class="aui-list-item-input" style="height:8rem;">
                        <input type="file" class="btn-file" style="filter:alpha(opacity=0);opacity:0;width:100%;height:100%;">
                        <div id="btn-image" style="position: absolute;top:0;left:0;width:100%;height:8rem;right:0;">
                            <img data-src="{{$adv->cover}}" src="{{ $adv->cover ? asset( $adv->cover ) : asset('images/logo.png')}}" style="margin:0 auto;max-height:8rem;" />
                        </div>
                    </div>
                </div>
            </li>
            <li class="aui-list-header">有关介绍</li>
            <li class="aui-list-item">
                <div class="aui-list-item-inner">
                    <div class="aui-list-item-input">
                        <textarea id="desc" placeholder="请输入有关介绍">{{data_get( $adv , 'posts_content')}}</textarea>
                    </div>
                </div>
            </li>
            <li class="aui-list-item">
                <div class="aui-list-item-inner aui-list-item-center aui-list-item-btn">
                    <div class="aui-btn aui-btn-block aui-btn-sm aui-btn-success btn-popadv">发布广告</div>
                </div>
            </li>
        </ul>
    </section>

    @include('foot')
    <div class="aui-popup aui-popup-bottom" id="tags-bottom" style="display: none;left:0rem;right:0rem;bottom:0rem;margin-left:0rem;">
        <div class="aui-popup-content" style="border-radius: 0;padding:.0rem;">
                <div class="aui-content-padded tags-picker">
                    @foreach( $tags as $k => $tag )
                        <span data-id="{{ $k  }}" class="aui-btn aui-tags">{{ $tag  }}</span>
                    @endforeach
                </div>
        </div>
    </div>

    <div class="aui-popup aui-popup-bottom" id="price-bottom" style="display: none;left:0rem;right:0rem;bottom:0rem;margin-left:0rem;">
        <div class="aui-popup-content" style="border-radius: 0;padding:.0rem;">
            <div class="aui-content-padded price-picker">
                @foreach( $price as $k => $tag )
                    <span data-id="{{ $tag  }}" class="aui-btn aui-tags">{{ $tag  }}</span>
                @endforeach
            </div>
        </div>
    </div>
@endsection


@section('script')
    @include('footscript')
<script>
    var minCash = {{$min_pup_cash}} ;

    var tags = {} ;
    @if( $adv->tags )
        <?php
            $tags = explode(',' , $adv->tags );
            $tagJson = [] ;
            foreach( $tags as $tag ) {
                $tagJson[ $tag ] = $tag ;
            }
        ?>
        var tags = {!! json_encode( $tagJson ) !!}
    @endif
    var price = {{ $adv->click_price or 0 }} ;
    $('.btn-popadv').unbind('click').bind('click' , throttle( submitAdv , 1500 )) ;
    $('#btn-tags').click(function(){
        popup.show( document.getElementById("tags-bottom") )
    });
   
    //点击事件 
    $(document).on('click' , '.tags-picker span' , function(){
        var value = $(this).text();
        if( tags[value] ) {
            delete tags[value] ;
            $(this).removeClass('aui-btn-danger')
        } else {
            index = 0 ;
            for( var i in tags ) {
                index++ ;
            }
            if( index >= 3 ) {
                toast.fail({
                    title:'标签最多可以选择3个'
                });
                return false ;
            }
            tags[value] = value
            $(this).addClass('aui-btn-danger')    
        }
        var html = '' ;
        for( var i in tags ) {
            html += '<span class="aui-btn aui-btn-dander" data-id='+ i +'>'+ tags[i] +'</span>';
        }
        $('#btn-tags .aui-list-item-input').html( html );
        
    }) ;


    $(document).on('click' , '.price-picker span' , function(){
        var key = $(this).data('id');
        var value = $(this).text();
        price = parseFloat( value , 2 ).toFixed( 1 )
        $('#btn-price .aui-list-item-input').text(price)
        var click_times = $('#click-times').val().trim();
        click_times = parseInt( click_times );
        click_times = isNaN( click_times ) ? 0 : click_times ;
        var total = ( click_times * price ).toFixed( 1 )
        $('.total-price').text( total )
        $('.price-picker span').removeClass('aui-btn-danger');
        $(this).addClass('aui-btn-danger')

    }) ;

    $(document).on('click' , '.btn-price-ok' , function(){ 
        popup.hide();
    }) ;


    $('#btn-price').click(function(){
        popup.show( document.getElementById("price-bottom") )
    });

    $('#btn-image').click(function(){
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

        var URL = window.URL || window.webkitURL ;
        var originPhotoURL;
        originPhotoURL = URL.createObjectURL(originPhoto);

        $('#btn-image img').attr('src' , originPhotoURL )
        
        var reader = new FileReader();
        reader.onload = function (file) {
           //console.log( this.result )
           $('#btn-image img').data('src' ,  this.result );
        };
        reader.readAsDataURL( originPhoto );
    }) ;


    function submitAdv() {
        var title = $("#title").val().trim();
        var category_id = 0 ;
        var click_price = $('#btn-price .aui-list-item-input').text().trim();
        var file = $('#btn-image img').data('src');
        var content = $('#desc').val().trim();
        var career = [] ;
        $('#btn-tags .aui-list-item-input span').each( function(){
            career.push( $(this).text() );
        }) ;
        var advUrl = $('#url').val().trim();
        var click_times = $('#click-times').val().trim();
        var last_days = $('#day').val().trim();

        //验证规则
        if( !title ) {
            toast.fail({
                title : '好的广告标题是成功的一半哦!'
            });
            return false ;
        }
        if( !category_id ) {
            //that.$toast("为您的广告选一个分类吧！");
            //return false ;
        }
        if( !click_price ) {
            toast.fail({
                title : '打赏点小费给用户吧!'
            });
            return false ;
        }

        click_times = parseInt( click_times , 10 );
        click_times = isNaN( click_times ) ? 0 : click_times ;
        if( !click_times ) {
            toast.fail({
                title : '请设置最大点击次数!'
            });
            return false ;
        }

        last_days = parseInt( last_days , 10 );
        last_days = isNaN( last_days ) ? 0 : last_days ;
        if( !last_days ) {
            toast.fail({
                title : '再好的广告也有看腻的时候，给您的广告设置展示天数吧！!'
            });
            return false ;
        }
        if( last_days < 15 ) {
            toast.fail({
                title : '系统要求最小设置15天的展示期!'
            });
            return false ;
        }

        if( !file ) {
            toast.fail({
                title : '他们都说加个形象图更能给用户留下深刻印象哦!'
            });
            return false ;
        }

        total_price = click_price * click_times ;
        if( minCash ) {
            if( total_price < minCash ) {
                toast.fail({
                    title : "根据您的评分级别，您本次发布最小铜板为" + minCash
                });
                return false ;
            }
        }



        var url = '/gg/store'
        var id = "{{$adv->id or ''}}"
        if( id ) {
            url = '/gg/' + id + '/update'
        }
        $.ajax({
            url : url ,
            type : 'post' ,
            dataType:'json' ,
            data : {
                'title': title ,
                'topic' : category_id ,
                'price' : click_price ,
                'cover' : file ,
                'day' : last_days ,
                'desc' : content ,
                'tags' : career.join(',') ,
                'url' : advUrl ,
                'click_times' : click_times ,
                '_token': "{{csrf_token()}}"
            } ,
            success : function( data , status ,xhr ){
                if( data.errcode === 0 ) {
                    toast.success({
                        title:data.msg
                    });
                    setTimeout( function(){
                        location.href = data.url
                    } , 1500 )

                } else if( data.errcode === 30000 ) {
                    //余额不足
                    toast.fail({
                        title:data.msg
                    })
                    setTimeout( function(){
                        location.href = "{{route('member.chargeform')}}" ;
                    } , 1500 )
                } else {
                    toast.fail({
                        title:data.msg
                    })
                }
            } ,
            error : function(xhr, errorType, error){
                alert( 'error' )
            } ,
            complete : function( xhr , status ){
            }
        }) ;
    }
</script>
@endsection
