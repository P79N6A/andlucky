<template>
<div class="page page-current" id="pageMineBlog">
    <header class="bar bar-nav">
    	<a class="button button-link button-nav pull-left" @click="$router.go(-1)">
            <span class="icon icon-left"></span>
            返回
        </a>
        <h1 class="title">我发布的</h1>
    </header>
    <div class="content">
        <!-- 这里是页面内容区 -->
        <div class="content-block" style="padding:0rem;margin-top:0rem;">
            <div class="card facebook-card" style="margin:.5rem;" v-for="(item , index) in blogs" >
            	<router-link :tag="div" :to="{name:'microblog-show' , params:{ id : item.id }}" class="blog-title">
	            	{{item.title}}
	            </router-link>
			    <div class="card-header no-border">
			        <div class="facebook-avatar img-round">
			            <img :src="avatar( item.user.avatar )"  width="34" height="34" />
			        </div>
			        <div class="facebook-name">{{ item.user.nickname ? item.user.nickname : '狗运'}}</div>
			        <div class="facebook-date">{{item.created_at}}</div>
			    </div>
			    <router-link :to="{name:'microblog-show' , params:{ id : item.id }}" class="card-content">
			        <div class="content-body content-padded" v-html="more(item.content)">
			            
			        </div>
			        
			        <div v-if="item.extra.type =='image' && item.extra.data instanceof Array " class="content-body" >
			                <img :src="img" v-for="( img , i ) in item.extra.data" />
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
import user from '../../components/user'
import Axios from "axios" ;
export default {
	data:function(){
		return {
			blogs : [] ,
			page : 1 ,
		}
	} ,
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
		}
	} ,
	components:{
		InfiniteLoading 
	}
}
</script>

<style>
.blog-title {
	font-size: 0.8rem;
	padding: 0.2rem 0.5rem;
	color:#000;
}
</style>
