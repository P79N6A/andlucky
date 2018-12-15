@extends('layout')

@section('style')
<style>
    .aui-tab {
        background: none;
    }
    .aui-tab-item {
        color:#fff ;
    }
    .aui-tab-item.aui-active {
        color:#fff57e ;
        border-bottom: 2px solid #fff57e;
    }

</style>
@endsection

@section('content')
    <header class="aui-bar aui-bar-nav">
        <div class="aui-tab">
            <a class="aui-tab-item"  href="{{route('wap.guess.index')}}">
                押大小
            </a>
            <div class="aui-tab-item aui-active" >
                比大小
            </div>
            <a class="aui-tab-item" href="{{route('wap.game.mine')}}">
                手气
            </a>

        </div>

    </header>
    <div class="aui-searchbar" id="search">
        <div class="aui-searchbar-input aui-border-radius">
            <i class="aui-iconfont aui-icon-search"></i>
            <input type="search" placeholder="请输入搜索内容" id="search-input">
            <div class="aui-searchbar-clear-btn">
                <i class="aui-iconfont aui-icon-close"></i>
            </div>
        </div>
        <div class="aui-searchbar-btn" tapmode>取消</div>
    </div>
    <section class="aui-content" style="padding-bottom:2.5rem;">
        <ul class="aui-list aui-media-list" id="online-content">
        </ul>
    </section>
    <div class="aui-popup aui-popup-bottom" id="invite-bottom" style="display: none;left:0rem;right:0rem;bottom:0rem;margin-left:0rem;">
        <div class="aui-popup-content" style="border-radius: 0;padding-top:.25rem;">
            <ul class="aui-list aui-form-list">
                <li class="aui-list-item">
                    <div class="aui-list-item-inner">
                        <div class="aui-list-item-input" style="padding-right: 0rem;">
                            <input type="number" id="promise-cash" class="aui-text-center" placeholder="铜板需要在{{$min}}到{{$max}}之间">
                        </div>
                    </div>
                </li>
                <li class="aui-list-item">
                    <div class="aui-list-item-inner aui-list-item-center aui-list-item-btn">
                        <div class="aui-btn aui-btn-block aui-btn-sm aui-btn-success btn-battle">发起战斗</div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    @include('foot')
@endsection


@section('script')
    @include('footscript')
<script>
    var keyword = '';
    var searchBar = document.querySelector(".aui-searchbar");
    var searchBarInput = document.querySelector(".aui-searchbar input");
    var searchBarBtn = document.querySelector(".aui-searchbar .aui-searchbar-btn");
    var searchBarClearBtn = document.querySelector(".aui-searchbar .aui-searchbar-clear-btn");
    if(searchBar){
        searchBarInput.onclick = function(){
            searchBarBtn.style.marginRight = 0;
        }
        searchBarInput.oninput = function(){
            if(this.value.length){
                searchBarClearBtn.style.display = 'block';
                searchBarBtn.classList.add("aui-text-info");
                searchBarBtn.textContent = "搜索";
            }else{
                searchBarClearBtn.style.display = 'none';
                searchBarBtn.classList.remove("aui-text-info");
                searchBarBtn.textContent = "取消";
            }
        }
    }
    searchBarClearBtn.onclick = function(){
        this.style.display = 'none';
        searchBarInput.value = '';
        searchBarBtn.classList.remove("aui-text-info");
        searchBarBtn.textContent = "取消";
    }
    searchBarBtn.onclick = function(){
        var keywords = searchBarInput.value;
        keyword = keywords
        if(keywords.length){
            searchBarInput.blur();
            search();
        }else{
            this.style.marginRight = "-"+this.offsetWidth+"px";
            searchBarInput.value = '';
            searchBarInput.blur();
        }
    }
    function search() {
        var that = this ;
        $.get('/onlineuser', {
            keyword :keyword
        } , function( data ) {
            //console.log( that );
            console.log( data );
            if( data.errcode === 0 ) {
                $('#online-content').html( data.html )
            }
            //that.detechOnline()
            setTimeout( search , 5000 );
        });
    }
    search();

    var invite_user = 0 ;
    var min = "{{$min}}" ;
    var max = "{{$max}}" ;
    $( document ).on('click' , '#online-content li' , function(){
        var that = $(this);
        invite_user = $(this).data('id');
        popup.show( document.getElementById("invite-bottom") )
    })
    $(document).on('click' , '.btn-battle' , function(){
        var cash = $('#promise-cash').val().trim();
        cash = parseInt( cash );
        cash = isNaN( cash ) ? 0 : cash ;
        if( cash <= 0 ) {
            toast.fail( {
                title : '请填写铜板' ,
                duration : 1500
            });
            return false ;
        }
        if( cash < min ) {
            toast.fail( {
                title : '单次邀请最少需要' + min + '铜板' ,
                duration : 1500
            });
            return false ;
        }
        if( cash > max ) {
            toast.fail( {
                title : '单次邀请最多只能' + max + '铜板' ,
                duration : 1500
            });
            return false ;
        }
        $.post('/bigsmall/invite' , {
            'promise' : cash ,
            '_token' : '{{csrf_token()}}' ,
            '_method' : 'put' ,
            'invite_user' : invite_user ,
        }  , function(  data  ){

            if( data.errcode === 0 ) {
                //战斗成功 回到发送信息的页面
                toast.success({
                    'title' : data.msg ,
                    duration : 1500
                }) ;
            } else {
                toast.fail( {
                    title : data.msg ,
                    duration : 1500
                })
            }

        });
        popup.hide();
    })
</script>
@endsection