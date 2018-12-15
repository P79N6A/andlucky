<template id="Index">
	<div class="page">
		<header class="bar bar-nav">
            <router-link to="/">
                <a class="button button-link button-nav pull-left back" href="/" data-transition='slide-out'>
                    <span class="icon icon-left"></span>
                    返回
                </a>

            </router-link>
            <h1 class="title">好友列表</h1>
        </header>
        <div class="content">
			<menu-nav index="index"></menu-nav>
			<div class="content-block" style="margin:0rem;padding:0rem;">
				<friend :friends="friends" :user_id="user_id" >这里是好友列表</friend>
				<infinite-loading @infinite="loadMore" ref="infiniteLoading">
					<span slot="no-more"></span>
					<span slot="no-result"></span>
				</infinite-loading> 
			</div>
		</div>

	</div>
	
</template>



<script>
import VueLoop from 'vue-loop'
import history from "./history.vue" ;
import menu from './menuNav' ;
import InfiniteLoading from 'vue-infinite-loading' ;
import Axios from 'axios' ;
export default {
	data :function(){
		return {
			page : 1 ,
			user_id:0,
			'friends' : [
			] 
		}
	} ,
	created:function(){
		this.user_id = 1 ;
		console.log('load index');
	} ,
	methods:{
		loadMore : function( $state ){
			let that = this ;
			Axios.get('/im/recent' +
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
					that.friends = ret.data.data.data ;
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
		getName:function( item ){
			return item.alias_name ? item.alias_name : ( item.friend.nickname  || '狗运' ) ;
		} ,
		getAvatar:function( v ){
			if( !v ) {
				return '/images/logo.png' ;
			}
			return '/' + v ;
		}
	},
	components: {
		'friend':history ,
		'menu-nav' : menu ,
		InfiniteLoading
	}
}

</script>
