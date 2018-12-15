<template>
<div class="page" id="pageMicroblogUser">
    <header class="bar bar-nav">
        <router-link class="button button-link button-nav pull-left" :to="{name:'member'}">
            <span class="icon icon-left"></span>
            返回
        </router-link>
        <h1 class="title">我的主页</h1>
    </header>

    <div class="content" >
    	<!--
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
		</div>
		-->
        <div id="microblog-container" >
	        <div class="card facebook-card" style="margin:0px;" v-for="(item , index ) in blogs">
	        	<router-link :to="{name:'microblog-show' , params:{ id : item.id }}" >
	        		<div class="blog-title">
            			{{item.title}}
            		</div>
		            <div class="card-header no-border">
		                <div class="facebook-avatar img-round">
		                    <img :src="avatar( item.user.avatar )"  width="34" height="34" />
		                </div>
		                <div class="facebook-name">{{ item.user.nickname ? item.user.nickname : '狗运'}}</div>
		                <div class="facebook-date">{{ item.created_at}}</div>
		            </div>
		            <div class="card-content link">
		                <div class="content-body content-padded" v-html="more( item.content )" >
		                </div>
		                <div v-if="item.extra.type =='image' && item.extra.data instanceof Array " class="content-body row" >
				            <div class="col-33 thumbs" v-for="( img , i ) in item.extra.data" :style="'background-image:url(' + img + ')'"></div>
				        </div>

		            </div>
		        </router-link>
	            <div class="card-footer">
			        <a ><i class="iconfont icon-conversation_icon "></i>&nbsp;{{item.comment_count}}</a>
			        <a 
			            class="praise-btn"
			            :class="{'has_praise' : item.praises_count }"
			            data-type="microblog" 
			        ><i class="iconfont icon-dislike"></i>&nbsp;<i class="blog-praise">{{item.prase}}</i></a>
			        <router-link 
			        	:to="{name:'microblog-show' , params:{ id : item.id }}" 
			        	><i class="iconfont icon-chakan1"></i>&nbsp;{{item.views}}
			        </router-link>
			        <router-link
			        	:to="{name:'microblog-edit' , params:{ id : item.id }}" 
			        	><i class="iconfont icon-xinzeng1"></i>
			        </router-link>
			        <a @click="drop( item.id )">
			        	<i class="iconfont icon-chuyidong"></i>
			        </a>
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
			blog_count : 0 ,
			fans_count : 0 ,
			care_count : 0 ,
			blogs : [] ,
			page : 1 ,
			user : {
				avatar:''
			} ,
		}
	} ,
	created:function(){
		let that = this
		Axios.get('/microblog/userinfo' + 
			'?_token='+  window._Token + 
			'&api_token=' + window.apiToken 
		).then(function(response) {
			//console.log( that );
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
		more:function( content ){
			return content.length < 50 ? content : content.substr(0 , 50 ) + '...[查看详细]' ;
		},
		loadMore:function( $state ){
			var that = this ;
			Axios.get('/microblog/mine' + 
				'?_token='+  window._Token + 
				'&keyword=' + that.keyword + 
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
		drop : function( id ) {
			let that = this 
			that.$confirm("您确定要删除本条内容吗?").then( function(){
				Axios.post('/microblog/drop' , {
					'_token' :  window._Token  ,
					'id' : id 
				} ).then(function(response) {
					let data = response.data
					if( data.errcode === 0 ) {
						that.$toast('删除完成')
						let blogs = []
						for( var i in that.blogs ) {
							if( that.blogs[i].id != id ) {
								blogs.push( that.blogs[i] )
							}
						}
						that.blogs = blogs
					}
				}).catch()
			}).catch( function(){

			} )
		}
	} ,
	components:{
		InfiniteLoading
	}
}

</script>


<style scoped>
.blog-title {
	font-size: 0.8rem;
	padding: 0.2rem 0.5rem;
	color:#000;
}
.thumbs {
	height: 5.5rem;
	background-repeat: no-repeat;
	background-position: center;
	background-size: cover ;
	border:#f7f7f7 solid 1px;
}
</style>
