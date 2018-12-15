<template>
	<div>
		<li class="item-content comments" v-for="(item , index ) in comments">
		    <router-link tag="div" :to="{name:'microblog-space' , param :{ id : item.user.id }}" class="item-media img-round">
		        <img :src="avatar( item.user.avatar )" width="45" height="45" />
		    </router-link>
		    <div class="item-inner">
		        <div class="item-subtitle">
		            <a class="nickname" >{{item.user.nickname}}</a>
		            <a class="pull-right"
		            	v-if="del || user_id == item.user_id"
		            	@click="drop( item.id , index )"
		            >
		                <i class="iconfont icon-shanchu1" ></i>
		            </a>
		        </div>
		        <router-link :to="{name:'comment-show' , params:{ id : item.id }}" >
			        <div class="item-inner comment-content" >{{item.content}}</div>
			        <div class="item-create-time">
			            {{item.created_at}} <span class="com-reply-nums">{{item.comment_count}}</span>
			        </div>
		    	</router-link>
		        
		        <div class="item-inner comment-reply comment-content" 
		        	v-for="(sitem , i ) in item.comments"
		        >
		        	<a href="#">{{sitem.user.nickname}}</a> : {{sitem.content}}

		    	</div>
		    </div>
		</li>
	</div>
</template>

<script>
import Axios from 'axios'
export default {
	name : 'comments-item' ,
	props:[ 'comments' , 'del' , 'user_id' ] ,
	data:function(){
		return {

		}
	} ,
	methods :{
		avatar : function( v ) {
			return v ? v : '/images/logo.png'
		} , 
		praise:function( id , index ){
			let that = this

			Axios.post('/microblog/praise' , {
				'id': id , 
				'type' : 'comment' ,
				'_token' :  window._Token ,
				'api_token' : window.apiToken 
			}).then( function( ret ){
				let data = ret.data
				that.$toast( data.msg )
				if( data.errcode === 0 ) {
					if( data.act == 'dec' ) {
						that.comments[index].praise_count = 0 
					} else {
						that.comments[index].praise_count = 1 
					}
				}
			})
		} ,
		//删除评论
		drop : function( id , index ) {
			let that = this

			Axios.post('/comment/' + id , {
				'id': id , 
				'_method' : 'delete' ,
				'_token' :  window._Token ,
				'api_token' : window.apiToken 
			}).then( function( ret ){
				let data = ret.data
				that.$toast( data.msg )
				if( data.errcode === 0 ) {
					that.$emit('drop' , id  , index )
				}
			})
		}
	}
}
</script>

<style>

</style>