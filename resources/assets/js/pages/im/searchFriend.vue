<template id="im-search-friend">
	
<div class="page">
	<header class="bar bar-nav">
        <a class="button button-link button-nav pull-left" @click="$router.go(-1)" data-transition='slide-out'>
            <span class="icon icon-left"></span>
            返回
        </a>
        <h1 class="title">查找好友</h1>
    </header>
	<div class="bar bar-header-secondary" style="height:1.5rem;">
		<div class="searchbar searchbar-active">
			<a class="searchbar-cancel" @click="search">查找</a>
			<div class="search-input">
				<label class="icon icon-search" for="search"></label>
				<input type="search" v-model="searchKey" v-on:keyup.13="search" autocomplete="off"  placeholder='输入关键字...'/>
			</div>
		</div>
	</div>
    <div class="content">
		<div class="content-block" style="margin:0rem;padding:0rem;">
			<div v-if="hasSearch && friends.length == 0 ">
				<p class="no-result">暂未找到用户</p>
			</div>

			<div v-else :to="{ name: 'chat' , params:{ user_id : item.uid } }" class="card" v-for="item in friends" >
			    <div class="card-content">
			      <div class="list-block media-list">
			        <ul>
			          <li class="item-content">
			            <div class="item-media img-round">
			            	<img :src="getAvatar( item.avatar )" width="44">
			            </div>
			            <div class="item-inner">
			            	<div class="item-title-row">
			           			<div class="item-title">{{item.id}}</div>
			           			<div class="pull-right button button-fill button-danger add-friend" @click="addFriends( item.id )">加好友</div>
			            	</div>
			            	<div class="item-title-row">
			           			<div class="item-title">{{item.nickname}}</div>
			            	</div>
			            	<div class="item-title-row">
			           			<div class="item-title">{{item.mobile}}</div>
			            	</div>
			            </div>
			          </li>
			        </ul>
			      </div>
			    </div>
			</div>
		</div>
	</div>
</div>
</template>

<script>
import Axios from 'axios' ;
export default {
	data:function(){
		return {
			searchKey : '' ,
			friends:[] ,
			hasSearch : false ,
		}
	} ,
	methods:{
		search:function(){
			let that = this ;
			if( !that.searchKey ) {
				return false ;
			}
			Axios.get('/im/searchfriend/' + that.searchKey +
				'?_token=' + window._Token +
				'&api_token=' + window.apiToken 
			, {
				responseType : 'json'
			}).then(function( ret ){
				that.hasSearch = true 
				if( ret.data.errcode === 0 ) {
					//邀请成功 回到发送信息的页面
					that.friends = ret.data.list ;
				}
				
			} , function( ret ){
				console.log( ret );
			});
		} ,
		getAvatar:function( v ){
			return v ? v : '/images/logo.png' 
		} ,
		addFriends:function( userId ){
			let that = this ;
			Axios.post('/im/addfriend/' + userId , {
				'_token' : window._Token ,
				'api_token' : window.apiToken 
			} , {
				responseType : 'json'
			}).then(function( ret ){
				console.log( ret );
				that.$toast( ret.data.msg );
				if( ret.data.errcode === 0 ) {
					//邀请成功 回到发送信息的页面
					setTimeout( function(){
						that.$router.go(-1);
					} , 1500 );
				}
				
			} , function( ret ){
				console.log( ret );
			});
		}
	}
}
</script>

<style scoped >
	.searchbar {
		background-color: #f9f9f9;
	}
	.no-result {
	    text-align: center;
	    font-size: 1rem;
	    font-weight: 400;
	}
	.add-friend {
		position: absolute;
	    right: 10px;
	    height: auto;
	    padding: .3rem;
	}
</style>