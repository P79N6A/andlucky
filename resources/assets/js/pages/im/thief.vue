<template>
<div id="stolen-thief-page" class="page">
	<header class="bar bar-nav">
	    <h1 class="title">谁摸过我</h1>
	    <router-link :to="{name:'chat-search-friend'}" class="button button-link button-nav pull-right">
            <span class="icon icon-search"></span>
            加好友
        </router-link>
	</header>
	<menu-nav index="stolen-me"></menu-nav>
	<foot-bar select="chat"></foot-bar>
	<div class="content">
		
		<div class="content-block" style="margin:0rem;padding:0rem;">
	   	
		    <ul class="list-block media-list" style="padding:0rem;">
		        <li class="item-content" v-for="item in thieves">
		            <div class="item-media img-round" @click="toHome( item.user_id )">
		            	<img :src="getAvatar( item.thief.avatar )" width="50">
		            </div>
		            <div class="item-inner">
		              <div class="item-title-row">
		                <div class="item-title" @click="toHome( item.user_id )" >{{item.thief.nickname ? item.thief.nickname : '狗运' }}</div>
		                <span class="button button-fill button-danger" @click="stolenback( item.user_id )">摸回来</span>
		              </div>
		              <div class="item-inner">
		              	最近摸了您{{item.cash}}点狗粮
		              </div>
		            </div>
		        </li>
		        <infinite-loading @infinite="loadMore" ref="infiniteLoading">
					<span slot="no-more">没有更多数据了</span>
					<span slot="no-result">没有更多数据了</span>
				</infinite-loading> 
		    </ul>
		
		</div>
	</div>
</div>	
</template>

<script>
import Axios from 'axios' ;

import menu from '../../components/menuNav' ;
import InfiniteLoading from 'vue-infinite-loading' ;
import footBar from '../../components/footNav'
export default {
	data:function(){
		return {
			'thieves' : [] ,
			page : 1 ,
		}
	} ,
	mounted:function(){
		
	} ,
	methods:{
		loadMore:function( $state ){
			let that = this ;
			Axios.get('/stolen/thieves' +
				'?_token=' + window._Token  + 
				'&api_token=' + window.apiToken + 
				'&page=' + that.page 
			, {
				responseType : 'json'
			}).then(function( ret ){
				$state.loaded();
				if( ret.data.errcode === 0 ) {
					//邀请成功 回到发送信息的页面
					console.log( ret.data );
					that.thieves = ret.data.data.data ;
					if( ret.data.data.last_page >= that.page ) {
						$state.complete();
					} else {
						that.page++ ;
					}
				}
				
			} , function( ret ){
				console.log( ret );
			});
		} ,
		getAvatar:function( v ){
			return v ? '/' + v : '/images/logo.png' ;
		} ,
		stolenback:function( userId ){
			let that = this ;
			Axios.put('/stolen/stolen/' + userId , {
				'_token' : window._Token ,
				'api_token' : window.apiToken 
			} , {
				responseType : 'json'
			}).then(function( ret ){
				console.log( ret );
				that.$toast( ret.data.msg );
				if( ret.data.errcode === 0 ) {
					//邀请成功 回到发送信息的页面
					console.log( ret.data );
				}
				
			} , function( ret ){
				console.log( ret );
			});
		} ,
		toHome:function( u ){
			location.href = '/microblog/' + u ;
		}
	},
	components :{
		'menu-nav' : menu ,
		'foot-bar' : footBar ,
		InfiniteLoading
	}
}
</script>

<style scoped>
.list-block {
	margin:0rem;
}

.item-inner:after {
	border:none;
	display: none;
}

</style>
