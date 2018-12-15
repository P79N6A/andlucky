@extends('layout')

@section('style')

@endsection

@section('content')
    <header class="aui-bar aui-bar-nav">
        @if( request('from') == 'form')
        <a class="aui-pull-left aui-btn" href="{{route('adv.index')}}">
            <span class="aui-iconfont aui-icon-left"></span>
        </a>
        @else
            <a class="aui-pull-left aui-btn" onclick="history.back()">
                <span class="aui-iconfont aui-icon-left"></span>
            </a>
        @endif
        <div class="aui-title">{{ data_get( $adv , 'title') }}</div>

    </header>

    <section class="aui-content aui-margin-b-15" style="margin-bottom:3rem;">
        <ul class="aui-list aui-media-list">
            <li class="aui-list-header aui-font-size-18" style="background: #fff ;">
                {{ data_get( $adv , 'title') }}
            </li>
            <li class="aui-list-item aui-list-item-middle">
                <div class="aui-media-list-item-inner">
                    <div class="aui-list-item-media" style="width: 2.5rem;">
                        <img src="{{ asset( data_get( $adv->user , 'avatar' , 'images/logo.png') ) }}" class="aui-img-round aui-list-img-sm">
                    </div>
                    <div class="aui-list-item-inner">
                        <div class="aui-list-item-text">
                            <div class="aui-list-item-title aui-font-size-16">{{ data_get( $adv->user , 'nickname' , config('global.no_nickname')) }}</div>
                            <small>{{ data_get( $adv , 'created_at') }}</small>
                        </div>
                        <div class="aui-list-item-text aui-font-size-12">
                            @if( isset( $val->tags ) && is_array( $val->tags ) )
                                <div class="aui-card-list-user-info">
                                    @foreach( $val->tags as $v)
                                        <span >{{$v}}</span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </li>
        </ul>
    </section>

    <section class="aui-content-padded aui-margin-b-15">
        @if( $adv->cover )
            <img src="{{ asset( data_get( $adv , 'cover' ) ) }}" width="100%">
        @endif
        <p>
        {{ nl2br( data_get( $adv , 'posts_content') ) }}
        </p>
    </section>
    <div class="aui-content-padded" STYLE="margin-bottom: 2.5rem;">
        <div class="aui-row-padded">
            <div class="aui-col-xs-6">
                <a class="aui-btn  aui-btn-block aui-btn-danger btn-praise" >赞(<em id="up-times">{{$adv->up_times}}</em>)</a>
            </div>
            <div class="aui-col-xs-6">
                <a class="aui-btn aui-btn-block btn-diss">踩(<em id="down-times">{{$adv->down_times}}</em>)</a>
            </div>
        </div>
    </div>

    @if( $adv->url )
        <footer class="aui-bar aui-bar-tab menu" id="footer">
            <a class="aui-btn aui-btn-block aui-btn-success" href="{{ $adv->url }}" >查看活动</a>
        </footer>
    @endif
@endsection


@section('script')
<script>
var login = false ;
@if( auth()->guard('wap')->check() )
var login = true
//5S后获取利润
setTimeout( function(){
    $.post( "{{route('wap.adv.gain' , ['id' => $adv->id ])}}" , { _token : '{{ csrf_token() }}'} , function( data ){
        if( data.errcode === 0 ) {
            toast.success({
                title: data.msg ,
                duration:1500
            });
        }
    }) ;
} , {{config('ADV_MIN_SECONDS')}} );
@endif

$('.btn-praise').unbind('click').bind('click' , throttle( up , 1500 ))
$('.btn-diss').unbind('click').bind('click' , throttle( down , 1500 ))
function up(){
    if( !login ) {
        toast.fail({
            title:"您还没有登录，请选登录"
        });
        return false
    }
    $.post('/gg/up' , {
        'id' : "{{$adv->id}}" ,
        _token : '{{ csrf_token() }}'
    } , function( data ){
        if( data.errcode === 0 ) {
            //更改次数
            $('#up-times').text( data.num )
        } else {
            toast.fail({
                title: data.msg
            })
        }

    })
}

function down(){
    let that = this
    if( !login ) {
        toast.fail({
            title:"您还没有登录，请选登录"
        });
        return false
    }
    $.post('/gg/down' , {
        'id' : "{{$adv->id}}" ,
        _token : '{{ csrf_token() }}'
    } , function( data ){
        if( data.errcode === 0 ) {
            $('#down-times').text( data.num )
            //更改次数
        } else {
            toast.fail({
                title: data.msg
            })
        }

    })

}

</script>
@endsection