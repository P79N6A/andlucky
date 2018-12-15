<template id="Index">
	<div class="page">
		<header class="bar bar-nav">
			<a class="button button-link button-nav pull-left" @click="$router.go(-1)">
                <span class="icon icon-left"></span>
                返回
            </a>
            <h1 class="title">邀请记录</h1>
            
        </header>
        <div class="content">
			<div class="content-block" style="margin:0rem;padding:0rem;">
				<router-link tag="div" :to="{ name: 'microblog-space' , params:{ id : item.id } }" class="card" v-for="item in users" >
				    <div class="card-content">
				      <div class="list-block media-list">
				        <ul>
				          <li class="item-content">
				            <div class="item-media img-round">
				            	<img :src="getAvatar( item.avatar )" width="44">
				            </div>
				            <div class="item-inner">
								<div class="item-title-row">
									<div class="item-title" v-html="getName( item.nickname )"></div>
								</div>
				            </div>
				          </li>
				        </ul>
				      </div>
				    </div>
				</router-link>
				<infinite-loading @infinite="loadMore" ref="infiniteLoading">
					<span slot="no-more">我也是有底线的</span>
					<span slot="no-result">没有更多数据</span>
				</infinite-loading> 
			</div>
		</div>

	</div>
	
</template>



<script>
import InfiniteLoading from 'vue-infinite-loading' 
import Axios from 'axios' ;
export default {
	data :function(){
		return {
			'page' : 1 ,
			'users' : [] 
		}
	} ,
	created : function(){

		console.log('load friends');
	} ,

	methods : {

		loadMore : function( $state ){
			let that = this ;
			Axios.get('/invited' +
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
					that.users = ret.data.data.data ;
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
		getName:function( v ){
			return v  || '狗运' ;
		} ,
		getAvatar:function( v ){
			if( !v ) {
				return '/images/logo.png' ;
			}
			return '/' + v ;
		}
	} ,

	components: {
		InfiniteLoading
	}
}

</script>
