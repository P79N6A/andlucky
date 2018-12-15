<template>
<div class="page" id="pageCommentShow">
    <header class="bar bar-nav">
        <a class="button button-link button-nav pull-left" @click="$router.go(-1)">
            <span class="icon icon-left"></span>
            返回
        </a>
        <h1 class="title">评论</h1>
    </header>
    <nav class="bar bar-tab">
        <div class="comment-bar">
            <!--
            <a href="javascript:void(0);" class="pull-right praise-btn"  
            	:class="{'has_praise' : comment.praise_count > 0 }" 
            	@click="praise"
            ><i class="iconfont icon-zan2"></i></a>
            -->
            <a href="javascript:void(0);" class="pull-right" @click="commentSub">发表</a>
            <div class="input-box">
                <input v-model="commentVal" autocomplete="off" placeholder="请输入您的评论···" />
            </div>
        </div>
    </nav>

    <div class="content">
        <div class="card facebook-card" style="margin:0px;">
            <div class="card-header no-border">
                <div class="facebook-avatar img-round" style="margin-right:.5rem;">
                    <img :src="avatar( comment.user.avatar )"  width="45" height="45" />
                </div>
                <div class="facebook-name">{{ comment.user.nickname }}</div>
                <div class="facebook-date">{{ comment.created_at }}</div>
            </div>
            <div class="card-content">
                <div class="content-body content-padded" >
                    {{comment.content}}
                </div>
            </div>
            <div class="card-footer">
                <a >评论&nbsp;{{comment.comment_count}}</a>
                <!--
                <a >赞&nbsp;<i id="blog-praise">{{comment.prase}}</i></a>
            	-->
            </div>
        </div>
        <div class="card" style="margin:0px;">
            <div class="card-content">
                <div class="list-block media-list" >
                    
                    <p class="no-data" v-if="comment.comment_count == 0 ">
                        暂无评论，点击抢沙发
                    </p>
                    
                    <ul v-else>
                        <comment-list :comments="comments" :del="del" :user_id="user_id" @drop="dropComment" ></comment-list>
                    </ul>
                    <infinite-loading @infinite="loadMore" ref="infiniteLoading">
						<span slot="no-more">没有更多数据了</span>
						<span slot="no-result">没有更多数据了</span>
					</infinite-loading>
                </div>
            </div>
        </div>
        
    </div>
</div>
</template>

<script>
import Axios from 'axios'
import comment from '../../components/comment'
import InfiniteLoading from 'vue-infinite-loading'
export default {
	data:function(){
		return {
			page : 1 ,
			comment:{
				user:{}
			},
			comments:[],
			commentVal : '',
			comment_target:'comment' ,
			user_id : 0 ,
			del : false ,
			visitor : {}

		}
	},
	created:function(){
		console.log('load')
		let that = this
		Axios.get('/comment/' + that.$route.params.id  + '?api_token=' + window.apiToken )
		.then(function( ret ){
			console.log( ret )
			let data =ret.data
			if( data.errcode === 0 ) {
				that.comment = data.data 
				that.visitor = data.visitor 
				that.del = that.comment.user_id == that.visitor.id 
				that.user_id = that.visitor.id 
			} else {
				that.$router.go(-1)
			}
		} , function(){
			that.$router.go(-1)
		})
		console.log( window )
	},
	methods : {
		avatar :  function( v ){
			return v ? v : '/images/logo.png' 
		} ,
		loadMore:function( $state ){
			var that = this ;
			Axios.get('/comments' + 
				'?_token='+  window._Token + 
				'&id=' + that.$route.params.id + 
				'&type=comment' + 
				'&api_token=' + window.apiToken + 
				'&page=' + that.page
			).then(function(response) {
				//console.log( that );
				$state.loaded();
				let data = response.data ;
				//如果页码为1 则重置数据
				if( that.page == 1 ) {
					that.comments = [] 
				}
				if( data.errcode === 0 ) {
					let list = data.data 
					if( list.total < that.page * 10 ) {
						$state.complete();
					}
					for( var i = 0 ; i < list.data.length ; i ++ ) {
						that.comments.push( list.data[i] ) ;
					}
					that.page++ ;
				} else {
					$state.complete();
				}
			});
		} ,

		commentSub:function(){
			let that = this

			if( !that.commentVal ) {
				return false
			}
			Axios.post('/comment/store' , {
				'id': that.$route.params.id , 
				'type' : 'comment' ,
				'content' : that.commentVal ,
				'_token' :  window._Token ,
				'api_token' : window.apiToken 
			}).then( function( ret ){
				let data = ret.data
				that.$toast( data.msg )
				if( data.errcode === 0 ) {
					that.commentVal = '' 
					that.comment.comment_count++
					that.comments.unshift( data.data )
				}
			})
		} ,
		praise:function( id , type ){
			let that = this

			Axios.post('/microblog/praise' , {
				'id': that.$route.params.id , 
				'type' : 'comment' ,
				'_token' :  window._Token ,
				'api_token' : window.apiToken 
			}).then( function( ret ){
				let data = ret.data
				that.$toast( data.msg )
				if( data.errcode === 0 ) {
					if( data.act == 'dec' ) {
						that.comment.praise_count = 0 
					} else {
						that.comment.praise_count = 1 
					}
				}
			})
		} ,
		dropComment:function( id , index ) {
			console.log( id );
			console.log( index );
			this.comments.splice( index , 1 )
			this.comment.comment_count-- 
		}
	} ,
	components :{
		'comment-list' : comment ,
		InfiniteLoading
	}
}
</script>

<style>

</style>