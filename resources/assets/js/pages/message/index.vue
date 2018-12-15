<template>
<div class="page" id="pageMessages">
    <header class="bar bar-nav">
        <a class="button button-link button-nav pull-left" @click="$router.go(-1)">
            <span class="icon icon-left"></span>
            返回
        </a>
        <h1 class="title">系统消息</h1>
    </header>
    <div class="content" >
        <div class="content-block" style="margin:0rem;padding: 0rem;" >
        	<router-link class="card" 
        		tag="div" 
        		:to="{name:'message-show' , params:{'id' : item.id }}"
        		v-for="(item , index) in messages">
			    <div class="card-header color-danger">通知</div>
			    <div class="card-content content-block">
			        <p v-html="item.message">
			        </p>
			    </div>
			    <div class="card-footer">
			        <span>{{item.created_at}}</span>

			        <span>{{item.read_at ? '已读' : '未读' }}</span>
			    </div>
			</router-link>
			<infinite-loading @infinite="loadMore" ref="infiniteLoading">
				<span slot="no-more">我也是有底线的</span>
				<span slot="no-result">没有消息</span>
			</infinite-loading> 
        </div>
    </div>
</div>
</template>

<script>
import Axios from "axios"
import InfiniteLoading from 'vue-infinite-loading' 
export default {
	data:function(){
		return {
			messages:[] ,
			page : 1 ,
		}
	},
	methods:{
		loadMore : function( $state ){
			let that = this 
			Axios.get('/messages' +
				'?_token=' + window._Token  + 
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
						that.messages.push( data.data.data[i] );	
					}
					
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
		shortContent : function( v ){
			 v = v.replace(/<\/?.+?>/g,"")
			 v = v.replace(/ /g,"")
			 return v.length > 200 ? v.substring( 0 , 200 ) + '....' : v 
		},
	} ,
	components :{
		InfiniteLoading
	}
}
</script>

<style>

</style>