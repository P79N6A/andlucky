<template>
<div class="page" id="pageMember">
    <header class="bar bar-nav">
        <a class="button button-link button-nav pull-left" @click="$router.go(-1)">
            <span class="icon icon-left"></span>
            返回
        </a>
        <h1 class="title">收入记录</h1>
    </header>
    <div class="content">

        <div class="card" style="margin:.5rem 0rem;">
            <div class="card-content">
              <div class="list-block">
                <ul>
                    <li v-for="item in lists">
                        <div class="item-content">
                            <div class="item-media"><em :class="{'in' : item.act == 'in'}">{{item.act =='in' ? '收入' : '支出'}}</em>{{item.cash}}</div>
                            <div class="item-inner">
                                <div class="item-title">{{item.target_desc}}</div>
                                <div class="item-after">{{item.created_at.substring(0,10)}}</div>
                            </div>
                        </div>
                    </li>
                </ul>
              </div>
            </div>
            <infinite-loading @infinite="loadMore" ref="infiniteLoading">
                <span slot="no-more">没有更多数据了</span>
                <span slot="no-result">没有更多数据了</span>
            </infinite-loading> 
        </div>
    </div>

</div>
</template>

<script>
import InfiniteLoading from 'vue-infinite-loading'
import {formatDate} from '../../utils'
import Axios from 'axios'
export default {
	data:function(){
        return {
            lists : [] ,
            page : 1 ,
        }
    } ,

	created:function(){
	},
	methods:{
        loadMore : function( $state ){
            let that = this 
            Axios.get('/member/cashlog' +
                '?_token=' + window._Token  + 
                '&type=' + that.type + 
                '&api_token=' + window.apiToken + 
                '&page=' + that.page 
            , {
                responseType : 'json'
            }).then(function( ret ){
                $state.loaded();
                let data = ret.data 
                if( data.errcode === 0 ) {
                    //邀请成功 回到发送信息的页面

                    for( var i in data.data.data ) {
                        that.lists.push( data.data.data[i] );   
                    }
                    
                    if( data.data.last_page <= that.page ) {
                        $state.complete();
                    } else {
                        that.page++ ;
                    }
                }
                
            } , function( ret ){
                console.log( ret );
            });
        } ,
		getAvatar : function( v ){
			return v ? v : '/images/logo.png'
		}
	},
	components :{
        InfiniteLoading
    }
}	

</script>

<style scoped>
    .item-media {
        width: 6rem;
        color:red;
    }
    .item-media em {
        height: 1rem;
        min-width: 1.8rem;
        padding: 0 .5rem;
        font-size: .6rem;
        line-height: 1rem;
        color: white;
        vertical-align: top;
        background: red;
        border-radius: .5rem;
        margin-right: .5rem;
    }
    .item-media em.in {
        background-color: green;
    }
</style>