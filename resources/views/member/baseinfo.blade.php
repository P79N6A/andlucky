@extends('layout')

@section('style')
    <style>
        .aui-tags {
            margin-bottom: .5rem;
        }
        #btn-tags .aui-btn {
            margin:.3rem;
        }
    </style>
@endsection

@section('content')
    <header class="aui-bar aui-bar-nav">
        <a class="aui-pull-left aui-btn" onclick="history.back()">
            <span class="aui-iconfont aui-icon-left"></span>
        </a>
        <div class="aui-title">基本信息</div>

    </header>
    <section class="aui-content">
        <ul class="aui-list aui-form-list">
            <li class="aui-list-item">
                <div class="aui-list-item-inner">
                    <div class="aui-list-item-label">
                        昵称
                    </div>
                    <div class="aui-list-item-input">
                        <input type="text" id="nickname" placeholder="请填写昵称" value="{{ data_get( $user , 'nickname' ) }}" maxlength="8">
                    </div>
                </div>
            </li>
            <li class="aui-list-item">
                <div class="aui-list-item-inner">
                    <div class="aui-list-item-label">
                        性别
                    </div>
                    <div class="aui-list-item-input">
                        <label><input class="aui-radio" type="radio" name="gender" value="1" checked> 男</label>
                        <label><input class="aui-radio" type="radio" name="gender" value="2"> 女</label>
                    </div>
                </div>
            </li>
            <li class="aui-list-item">
                <div class="aui-list-item-inner">
                    <div class="aui-list-item-label">
                        出生日期
                    </div>
                    <div class="aui-list-item-input">
                        <input type="date" id="birthday" value="{{data_get( $user , 'birth_day' ) ? date('Y-m-d' , $user->birth_day ) : '' }}" placeholder="Password">
                    </div>
                </div>
            </li>
            <li class="aui-list-item" id="btn-city">
                <div class="aui-list-item-inner">
                    <div class="aui-list-item-label">
                        城市
                    </div>
                    <div class="aui-list-item-input" id="show-city">
                        {{ data_get( $user , 'city') }}
                    </div>
                    <input type="hidden" id="show-province">
                    <input type="hidden" id="show-city">

                </div>
            </li>
            <li class="aui-list-item">
                <div class="aui-list-item-inner">
                    <div class="aui-list-item-label">
                        学历
                    </div>
                    <div class="aui-list-item-input">
                        <select id="degree">
                            @foreach( $degree as $k => $val )
                            <option {{data_get( $user , 'degree') == $k ? 'selected' : '' }}>{{ $val }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </li>
            <li class="aui-list-item" id="btn-tags">
                <div class="aui-list-item-inner">
                    <div class="aui-list-item-label">
                        喜好
                    </div>
                    <div class="aui-list-item-input">
                        @if( $user->tags )
                            <?php
                            $tags = explode(',' , $user->tags );
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
            
            <li class="aui-list-item">
                <div class="aui-list-item-inner">
                    <div class="aui-list-item-label">
                        支付宝
                    </div>
                    <div class="aui-list-item-input">
                        <input type="text" id="alipay-account" placeholder="请填写支付宝账号" value="{{data_get( $user , 'alipay_account')}}" @if( data_get( $user , 'alipay_account') ) readonly @endif >
                    </div>
                </div>
            </li>
            @if( !data_get( $user , 'alipay_account') )
            <li class="aui-list-item">
                <div class="aui-list-item-inner" style="text-align:center;color:red;justify-content: center;">
                    支付宝账号一经填写不可修改
                </div>
            </li>
            @endif
            <li class="aui-list-item">
                <div class="aui-list-item-inner aui-list-item-center aui-list-item-btn">
                    <div class="aui-btn btn-block aui-btn-info aui-margin-r-5 btn-save">保存</div>
                </div>
            </li>
        </ul>
    </section>
    <div class="aui-popup aui-popup-bottom" id="tags-bottom" style="display: none;left:0rem;right:0rem;bottom:0rem;margin-left:0rem;">
        <div class="aui-popup-content" style="border-radius: 0;padding:.0rem;">
            <div class="aui-content-padded tags-picker">
                @foreach( $tags as $k => $tag )
                    <span data-id="{{ $k  }}" class="aui-btn aui-tags">{{ $tag  }}</span>
                @endforeach
            </div>
        </div>
    </div>
@endsection


@section('script')
<script src="{{asset('packages/aui/script/aui-popup.js')}}"></script>
<script src="{{asset('vendor/iosselect/areaData_v2.js')}}"></script>
<script src="{{asset('vendor/iosselect/ios.select.js')}}"></script>
<link rel="stylesheet" type="text/css" href="{{asset('vendor/iosselect/ios.select.css')}}" />
<script>
    var popup = new auiPopup();
    var tags = {} ;
    @if( $user->tags )
    <?php
    $tags = explode(',' , $user->tags );
    $tagJson = [] ;
    foreach( $tags as $tag ) {
        $tagJson[ $tag ] = $tag ;
    }
    ?>
    var tags = {!! json_encode( $tagJson ) !!}
    @endif

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
            if( index >= 6 ) {
                toast.fail({
                    title:'标签最多可以选择6个'
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


    var selectContactDom = $('#btn-city');
    var showContactDom = $('#show-city');
    var contactProvinceCodeDom = $('#province-code');
    var contactCityCodeDom = $('#city-code');
    selectContactDom.bind('click', function () {
        var sccode = showContactDom.attr('data-city-code');
        var scname = showContactDom.attr('data-city-name');

        var oneLevelId = showContactDom.attr('data-province-code');
        var twoLevelId = showContactDom.attr('data-city-code');
        var threeLevelId = showContactDom.attr('data-district-code');
        var iosSelect = new IosSelect(3,
            [iosProvinces, iosCitys, iosCountys],
            {
                title: '地址选择',
                itemHeight: 35,
                relation: [1, 1],
                oneLevelId: oneLevelId,
                twoLevelId: twoLevelId,
                threeLevelId: threeLevelId,
                callback: function (selectOneObj, selectTwoObj, selectThreeObj) {
                    contactProvinceCodeDom.val(selectOneObj.id);
                    contactProvinceCodeDom.attr('data-province-name', selectOneObj.value);
                    contactCityCodeDom.val(selectTwoObj.id);
                    contactCityCodeDom.attr('data-city-name', selectTwoObj.value);

                    showContactDom.attr('data-province-code', selectOneObj.id);
                    showContactDom.attr('data-city-code', selectTwoObj.id);
                    showContactDom.attr('data-district-code', selectThreeObj.id);
                    showContactDom.html(selectOneObj.value + ' ' + selectTwoObj.value + ' ' + selectThreeObj.value);
                }
            });
    });


    function saveInfo() {
        var nickname = $('#nickname').val().trim();
        var birthday = $('#birthday').val().trim();
        var city = $('#show-city').text();
        var career = [] ;
        $('#btn-tags .aui-list-item-input span').each( function(){
            career.push( $(this).text() );
        }) ;
        var degree = $('#degree').val();
        var gender = $('input[name="gender"]:checked').val();
        var is_marry = 0 ;
        var alipay = $('#alipay-account').val().trim();

        if( !alipay ) {
            toast.fail({title:"请填写正确的收款账号"});
            return false ;
        }
        if( nickname.length > 8 ) {
            toast.fail({title : "昵称最长为8个字符" });
            return false ;
        }

        $.post('{{route('wap.member.baseinfo')}}' , {
            nickname : nickname ,
            birth_day : birthday ,
            city : city ,
            tags : career.join(',') ,
            degree : degree ,
            gender : gender ,
            is_marry : is_marry ,
            alipay_account: alipay ,
            _token : "{{csrf_token()}}"
        } , function( data ){

            if( data.errcode === 0 ) {
                toast.success({
                    title : data.msg
                })
                setTimeout( function(){
                    history.back();
                } , 1500 )
            } else {
                toast.fail({
                    title : data.msg
                })
            }
        });
    }

    $('.btn-save').unbind('click').bind('click' , throttle( saveInfo , 1500 ) ) ;

</script>
@endsection