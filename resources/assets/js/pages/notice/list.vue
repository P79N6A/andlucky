<template>
<div class="page" id="pageNotices">
    <header class="bar bar-nav">
    	<a class="button button-link button-nav pull-left" @click="$router.go(-1)">
            <span class="icon icon-left"></span>
            返回
        </a>
        <h1 class="title">公告</h1>
    </header>
    <div class="content" >
        <div class="content-block" style="margin:0rem;padding: 0rem;" >
	        <div class="card" v-for="(item , index ) in notices" >
			    <div class="card-header">{{item.title}}</div>
			    <div class="card-content">
			        <div class="list-block media-list">
			            <ul>
			                <li class="item-content">
			                    <div class="item-inner">
			                        <p v-html="shortContent( item.content )">

			                        </p>
			                    </div>
			                </li>
			            </ul>
			        </div>
			    </div>
			    <div class="card-footer">
			        <span>{{item.created_at}}</span>
			        <!--<span>详情</span>-->
			    </div>
			</div>
			<infinite-loading @infinite="loadMore" ref="infiniteLoading">
				<span slot="no-more"></span>
				<span slot="no-result"></span>
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
			notices : [] ,
			page : 1 
		}
	} ,
	methods :{
		loadMore : function( $state ){
			let that = this 
			Axios.get('/notices' +
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
						that.notices.push( data.data.data[i] );	
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
			 return v ;
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