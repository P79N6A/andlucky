<template id="Index">
	<div class="page">
		<header class="bar bar-nav">
            <h1 class="title">好友列表</h1>
            <router-link :to="{name:'chat-search-friend'}" class="button button-link button-nav pull-right back" data-transition='slide-out'>
                <span class="icon icon-search"></span>
                加好友
            </router-link>
        </header>
        <menu-nav index="friend"></menu-nav>
        <foot-bar select="chat"></foot-bar>
        <div class="content">
			<div class="content-block" style="margin:0rem;padding:0rem;">
				<div class="card" v-for="item in friends" >
				    <div class="card-content">
				      <div class="list-block media-list">
				        <ul>

				        	<li class="item-content">
					            <router-link :to="{ name: 'im-chat' , params:{ user_id : item.friend_user_id } }"
					            	tag="div"
					            	class="item-media img-round">
					            	<img :src="getAvatar( item.friend.avatar )" width="44">
					            </router-link>
					            <router-link :to="{ name: 'im-chat' , params:{ user_id : item.friend_user_id } }"
					            	tag="div"
					            	class="item-inner">
									<div class="item-title-row">
										<div class="item-title" style="line-height:1.8rem;font-size:.8rem;">
											<span v-html="getName( item )"></span>
											<span class="item-online-status item-online" v-if="item.friend.online == 1">在线</span>
											<span class="item-right item-online-status" v-else>离线</span>
										</div>
									</div>
									<div class="item-title-row">
									<div class="item-title" v-html="item.friend.mobile"></div>
									</div>
					        	</router-link>
					            <div class="item-inner " style="width:30%;text-align:right;">
					            	<div class="item-title-row" style="height:1.4rem;">
										<!--<router-link :to="{name:'bigsmall-invite' , params : { id : item.friend.id } }" class="button button-fill button-danger">比大小</router-link>-->
										<a @click="showBigSmallModal( item.friend.id )" class="button button-fill button-danger">比大小</a>
									</div>
									<!--
									<div class="item-title-row" style="height:1.8rem;">
										<a class="button" @click="stolenback( item.friend.id )">摸一把</a>
									</div>
									-->
					            </div>
				          	</li>

				          	
				        </ul>
				      </div>
				    </div>
				</div>
				<!--
				<infinite-loading @infinite="loadMore" ref="infiniteLoading">
					<span slot="no-more"></span>
					<span slot="no-result"></span>
				</infinite-loading> 
				-->
			</div>
		</div>
		<bigsmall-modal v-on:closeModal="closeModal" :to="to"></bigsmall-modal>
	</div>
	
</template>



<script>
import friend from "../../components/friend.vue" 
import menu from '../../components//menuNav' 
import footBar from '../../components/footNav'
import bigSmallModal from '../../components/bigsmall'
import InfiniteLoading from 'vue-infinite-loading' 
import Axios from 'axios' ;
export default {
	data :function(){
		return {
			'page' : 1 ,
			'friends' : [] ,
			to : 0 ,
		}
	} ,
	created : function(){
		let that = this
		console.log('load friends');
		that.loadMore();
		setInterval( function(){
			that.loadMore()
		} , 5000 );
	} ,

	methods : {

		loadMore : function( ){
			let that = this ;
			Axios.get('/im/friends' +
				'?_token=' + window._Token  + 
				'&api_token=' + window.apiToken + 
				'&page=' + that.page 
			, {
				responseType : 'json'
			}).then(function( ret ){
				//$state.loaded();
				if( ret.data.errcode === 0 ) {
					//邀请成功 回到发送信息的页面
					console.log( ret.data );
					that.friends = ret.data.data.data ;
					if( ret.data.data.last_page >= that.page ) {
						//$state.complete();
					} else {
						//that.page++ ;
					}
				}
				
			} , function( ret ){
				console.log( ret );
			});
		} ,
		getName:function( item ){
			if( item.alias_name ) {
				return item.alias_name 
			}
			if( item.friend ) {
				return item.friend.nickname || '狗运'
			}
			return '狗运'
		} ,
		getAvatar:function( v ){
			if( !v ) {
				return '/images/logo.png' ;
			}
			return '/' + v ;
		},
		showBigSmallModal:function( id ) {
			let that = this 
			that.to = id 
			that.showBigSmall = 'block' 

		} ,
		closeModal:function(){
			this.to = 0 
		},
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
	} ,

	components: {
		'friend':friend ,
		'menu-nav' : menu ,
		'foot-bar' : footBar ,
		'bigsmall-modal' : bigSmallModal ,
		InfiniteLoading
	}
}

</script>
