<template>
<div class="page" id="pageMember">
    <header class="bar bar-nav">
        <a class="button button-link button-nav pull-left" @click="$router.go(-1)">
            <span class="icon icon-left"></span>
            返回
        </a>
        <h1 class="title">充值</h1>
        <router-link class="button button-link button-nav pull-right" :to="{name:'charge'}">
            充值
        </router-link>
    </header>
    <div class="content">
        <div class="card" v-for="item in lists" style="margin:0.5rem;">
            <div class="card-header">单号:{{item.trade_no}}</div>
            <div class="card-content">
                <div class="card-content-inner">
                    狗粮:{{item.charge}}
                </div>
            </div>
            <div class="card-footer">
                时间:{{item.created_at}}
            </div>
        </div>
        
        <infinite-loading @infinite="loadMore" ref="infiniteLoading">
            <span slot="no-more">没有更多数据了</span>
            <span slot="no-result">没有更多数据了</span>
        </infinite-loading> 
    
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
            Axios.get('/member/chargelog' +
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