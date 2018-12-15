<template>
	
<div class="page" id="pageMicroblogShow">
    <header class="bar bar-nav">
        <a class="button button-link button-nav pull-left" @click="back()" >
            <span class="icon icon-left"></span>
            返回
        </a>
        <h1 class="title">{{ more( title ) }}</h1>
    </header>
    <nav class="bar bar-tab">
        <div class="comment-bar">
            <a href="javascript:void(0);" 
            	class="pull-right" 
            	@click="comment"
            >发表</a>
            <div class="input-box">
                <input v-model="commentVal" v-on:keyup.13="comment" placeholder="请输入您的评论···" />
            </div>
        </div>
    </nav>

    <div class="content">
    	<div v-if="blog.adv_pos == 'top'" class="blog-adv">
    		<a :href="blog.adv_link" v-if="blog.adv_link" >
    			<img :src="blog.adv_cover" />
    		</a>
    		<img v-else :src="blog.adv_cover" />
    	</div>
        <div class="card facebook-card" style="margin:0px;box-shadow:none;">
        	<div class="content-padded" style="font-size:1rem;padding:0.5rem;margin:0;">
			    {{blog.title}}
			</div>
            <div class="card-header no-border">
                <div :to="{name:'microblog-space' , params:{ id : user.id }}" class="facebook-avatar img-round" style="margin-right:.5rem;">
                    <img :src="avatar ( user.avatar )"  width="45" height="45" />
                </div>
                <div class="facebook-name">{{ user.nickname ? user.nickname : '狗运'}}</div>
                <div class="facebook-date">{{ blog.created_at }} </div>
            </div>
            <div class="card-content">
            	
                <div class="content-body content-padded">
		            <p  v-html="blog.content"></p>
		        </div>
		        
		        <div v-if="blog.extra && typeof( blog.extra.type ) != 'undefined' && blog.extra.type =='image' && blog.extra.data instanceof Array " class="content-body" >
		                <img :src="img" v-for="( img , i ) in blog.extra.data" />
		        </div>

            </div>
            <div class="card-footer">
                <a><i class="iconfont icon-conversation_icon "></i>&nbsp;{{blog.comment_count}}</a>
                <a href="javascript:void(0);" 
                  :class="{has_praise : has_praise > 0 }"
                  @click="praise( $route.params.id , 'microblog')"
                >
                  <i class="iconfont icon-dislike"></i>&nbsp;{{blog.praises_count}}
                </a>
		            <a><i class="iconfont icon-chakan1"></i>&nbsp;{{blog.views}}</a>
            </div>
        </div>
        <div v-if="blog.adv_pos == 'bottom'" class="blog-adv">
    		<a :href="blog.adv_link" v-if="blog.adv_link" >
    			<img :src="blog.adv_cover" />
    		</a>
    		<img v-else :src="blog.adv_cover" />
    	</div>
        <div class="card" style="margin:0px;">
            <div class="card-content">
                <div class="list-block media-list" >
                    
                    <p class="no-data" v-if="blog.comments_count == 0 ">
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
			title:'' ,
			blog:{},
			user:{},
			visitor:{},
			comments:[],
			commentVal : '',
			comment_target:'blog' ,
			comment_target_id : 0 ,
			has_praise : 0 ,
			hasLogin : false ,
			countdown : 30 ,
			del : false ,
			user_id : 0 
		}
	},
	created:function(){
		let that = this
		Axios.get('/microblog/' + that.$route.params.id  + '?api_token=' + window.apiToken )
		.then(function( ret ){
			console.log( ret )
			let data =ret.data
			that.blog = data.data.blog 
			that.blog.content = that.blog.content.replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1</p><p>$2')
			that.title = that.blog.title 
			that.user = data.data.blog.user 
			that.visitor = data.data.user 
			that.comment_target_id = data.data.blog.id 
			that.countdown = data.data.countdown 
			that.hasLogin = !!window.apiToken  
			that.del = that.blog.user_id == that.visitor.id 
			that.user_id = that.visitor.id 
			//计时30S 开始计算收益
			if( that.hasLogin && data.data.has_view == 0  && that.blog.user_id != that.visitor.id ) {
				var timer = setInterval( function(){
					that.countdown-- ;
					console.log( that.countdown )
					if( that.countdown === 0 ) {
						clearInterval( timer )
						that.getReward()
					}
				} , 1000 )	
			}
			
		})
	},
	methods : {
		avatar :  function( v ){
			return v ? v : '/images/logo.png' 
		} ,
		more:function( content ){
			return content.length < 10 ? content : content.substr(0 , 10 ) + '...' ;
		},
		getReward:function(){
			let that = this
			if( !that.$route.params.id ) {
				return false ;
			}
			Axios.post('/microblog/gain/' + that.$route.params.id , {
				'id' :  that.$route.params.id ,
				'_token' : window._Token ,
				'api_token' : window.apiToken
			}).then(function( ret ){
				console.log( ret )
				let data =ret.data
				if( data.errcode === 0 ) {
					that.$toast( data.msg )	
				}
				
			})
		},
		loadMore:function( $state ){
			var that = this ;
			Axios.get('/comments' + 
				'?_token='+  window._Token + 
				'&id=' + that.$route.params.id + 
				'&type=blog' + 
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

		comment:function(){
			let that = this

			if( !that.commentVal ) {
				return false
			}
			Axios.post('/comment/store' , {
				'id': that.$route.params.id , 
				'type' : 'blog' ,
				'content' : that.commentVal ,
				'_token' :  window._Token ,
				'api_token' : window.apiToken 
			}).then( function( ret ){
				let data = ret.data
				that.$toast( data.msg )
				if( data.errcode === 0 ) {
					that.commentVal = '' 
					that.blog.comment_count++
					that.comments.unshift( data.data )
					that.comments = that.comments 
				}
			})
		} ,
		praise:function( id , type ){
			let that = this

			Axios.post('/microblog/praise' , {
				'id': that.$route.params.id , 
				'type' : 'microblog' ,
				'_token' :  window._Token ,
				'api_token' : window.apiToken 
			}).then( function( ret ){
				let data = ret.data
				that.$toast( data.msg )
				if( data.errcode === 0 ) {
					if( data.act == 'dec' ) {
						that.has_praise = 0 
						that.blog.praises_count-- ;
					} else {
						that.has_praise = 1 
						that.blog.praises_count++ ;
					}
				}
			})
		} ,
		back:function(){
			let that = this
			if( this.$route.query.from == 'form' ) {
				this.$router.push({
					'name' : 'microblog-myspace' ,
				})
			} else {
				this.$router.push({
					'name' : 'microblog' ,
					query :{
						cate_id : that.blog.cate_id 
					}
				})
			}
		} ,
		dropComment:function( id , index ) {
			console.log( id );
			console.log( index );
			this.comments.splice( index , 1 )
			this.blog.comment_count-- 
		}
	} ,
	components :{
		'comment-list' : comment ,
		InfiniteLoading
	}
};
</script>

<style>
.blog-adv img {
	max-width: 100%;
}
</style>
