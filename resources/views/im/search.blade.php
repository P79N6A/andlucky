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
        <a class="aui-pull-left aui-btn" onclick="history.back()">
            <span class="aui-iconfont aui-icon-left"></span>
        </a>
        <div class="aui-title">添加好友</div>
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
    <section class="aui-content" >
        <ul class="aui-list aui-media-list" id="online-content">
        </ul>
    </section>
@endsection


@section('script')
<script src="{{asset('packages/aui/script/aui-dialog.js')}}"></script>
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
        $.get('/im/searchfriend', {
            keyword :keyword
        } , function( data ) {
            //console.log( that );
            console.log( data );
            if( data.errcode === 0 ) {
                $('#online-content').html( data.html )
            }
            //that.detechOnline()
        });
    }
    var user_id = 0 ;
    $(document).on('click' , '.btn-addfriend' , function(){
        var dialog = new auiDialog({}) ;
        user_id = $(this).data('id')
        dialog.alert({
            title:"提示",
            msg:'您确定要添加他为好友吗？',
            buttons:['取消','确定']
        },function(ret){
            if(ret){
                if( ret.buttonIndex == 2 ) {
                    $.post('/im/addfriend/' + user_id, {
                        '_token': "{{csrf_token()}}"
                    }, function (data) {
                        if (data.errcode === 0) {
                            //邀请成功 回到发送信息的页面
                            toast.success({
                                title: data.msg
                            });
                            setTimeout(function () {
                                history.back()
                            }, 1500);
                        } else {
                            toast.fail({
                                title: data.msg
                            });
                        }

                    });
                }
            }
        })

    });

</script>
@endsection