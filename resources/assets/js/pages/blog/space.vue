<template>
<div class="page" id="pageMicroblogUser">
    <header class="bar bar-nav">
        <a class="button button-link button-nav pull-left" @click="$router.go(-1)">
            <span class="icon icon-left"></span>
            返回
        </a>
        <h1 class="title">{{user.nickname}}的主页</h1>
    </header>

    <div class="content" >
    	<div class="user-banner">
    		<span class="avatar img-round">
				<img :src="avatar( user.avatar )" />
			</span>
			<div class="user-name">
				{{user.nickname ? user.nickname : '狗运'}}
			</div>
			<div class="user-statics">
				动态&nbsp;<i>{{blog_count}}</i>&nbsp;&nbsp;&nbsp; 
				粉丝&nbsp;<i>{{fans_count}}</i>&nbsp;&nbsp;&nbsp; 
				关注&nbsp;<i>{{care_count}}</i>
			</div>
			
			<div class="user-focus">
			<!-- 如果是登录了则要检查关注 -->
				<a v-if="!has_care" :class="{active:!!has_care}" href="javascript:void(0);" @click="focus">
					<span class="icon icon-star"></span>关注
				</a>
				<a v-else :class="{active:!has_care}" href="javascript:void(0);" @click="unfocus">
					<span class="icon icon-star"></span>取消关注
				</a>
				<a href="javascript:void(0);" @click="steal">
					调戏一下
				</a>
			</div>
		</div>
        <div id="microblog-container" >
	        <div class="card facebook-card" style="margin:0px;" v-for="(item , index ) in blogs">
	        	<router-link :to="{name:'microblog-show' , params:{ id : item.id }}" >
		            <div class="card-header no-border">
		                <div class="facebook-avatar img-round">
		                    <img :src="avatar( item.user.avatar )"  width="34" height="34" />
		                </div>
		                <div class="facebook-name">{{ item.user.nickname ? item.user.nickname : '狗运'}}</div>
		                <div class="facebook-date">{{ item.created_at}}</div>
		            </div>
		            <div class="card-content link">
		                <div class="content-body content-padded" >
		                    {{item.content}}
		                </div>
		                <div v-if="item.extra.type =='image' && item.extra.data instanceof Array " class="content-body" >
				                <img :src="img" v-for="( img , i ) in item.extra.data" />
				        </div>

		            </div>
		        </router-link>
	            <div class="card-footer">
			        <a >评论&nbsp;{{item.comment_count}}</a>
			        <a 
			            class="praise-btn"
			            :class="{'has_praise' : item.praises_count }"
			            data-type="microblog" 
			        >踩&nbsp;<i class="blog-praise">{{item.prase}}</i></a>
			        <router-link 
			        	:to="{name:'microblog-show' , params:{ id : item.id }}" 
			        	>查看&nbsp;{{item.views}}
			        </router-link>
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
import Axios from "axios" ;
export default {
	data:function(){
		return {
			user_id : 0 ,
			blog_count : 0 ,
			fans_count : 0 ,
			care_count : 0 ,
			blogs : [] ,
			page : 1 ,
			has_care: false,
			user : {
				avatar:''
			} ,
		}
	} ,
	created:function(){
		let that = this
		that.user_id = that.$route.params.id 
		Axios.get('/microblog/userinfo' + 
			'?_token='+  window._Token + 
			'&user_id=' + that.$route.params.id + 
			'&api_token=' + window.apiToken 
		).then(function(response) {
			console.log( response )
			let data = response.data ;
			if( data.errcode === 0 ) {
				that.user = data.user 
				that.blog_count = that.user.blog_count 
				that.care_count = that.user.cares_count 
				that.fans_count = that.user.fans_count
				that.has_care = data.has_care	
			} else {
				that.$router.go(-1)
			}
			
			
		} , function( ret ){
			that.$router.go(-1)
		});
	},
	methods :{
		loadMore:function( $state ){
			var that = this ;
			Axios.get('/microblog/list' + 
				'?_token='+  window._Token + 
				'&keyword=' + that.keyword + 
				'&user_id=' + that.$route.params.id + 
				'&api_token=' + window.apiToken + 
				'&page=' + that.page
			).then(function(response) {
				//console.log( that );
				$state.loaded();
				let data = response.data ;
				//如果页码为1 则重置数据
				if( that.page == 1 ) {
					that.blogs = [] 
				}
				if( data.errcode === 0 ) {
					let list = data.data 
					if( list.total < that.page * 10 ) {
						$state.complete();
					}
					for( var i = 0 ; i < list.data.length ; i ++ ) {
						that.blogs.push( list.data[i] ) ;
					}
					that.page++ ;
				} else {
					$state.complete();
				}
			});
		} ,
		avatar:function( v ) {
			return v ? v : '/images/logo.png'
		} ,
		focus:function(){
			let that = this
			that.user_id = that.$route.params.id 
			Axios.post('/user/focus' ,{
				'_token' :  window._Token ,
				'user_id' : that.$route.params.id ,
				'api_token' : window.apiToken 
			}).then(function(response) {
				//console.log( that );
				
				let data = response.data ;
				that.$toast( data.msg )
				if( data.errcode === 0 ) {
					that.has_care = 1 
				}
			});
		},
		unfocus:function(){
			let that = this
			that.user_id = that.$route.params.id 
			Axios.post('/api/user/unfocus' , {
				'_token' :  window._Token ,
				'user_id' : that.$route.params.id ,
				'api_token' : window.apiToken 
			} ).then(function(response) {
				//console.log( that );
				let data = response.data ;
				that.$toast( data.msg )
				if( data.errcode === 0 ) {
					that.has_care = 0 
				}
			});
		} ,
		steal:function(){
			let userId = this.$route.params.id 
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
		}
	} ,
	components:{
		InfiniteLoading
	}
}

</script>


<style>


</style>
