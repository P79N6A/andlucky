<template>

<div class="page" id="pageMessageView">
    <header class="bar bar-nav">
        <a class="button button-link button-nav pull-left" @click="$router.go(-1)">
            <span class="icon icon-left"></span>
            返回
        </a>
        <h1 class="title">系统消息</h1>
    </header>
    <div class="content" >
        <h4 class="text-center">通知</h4>
        <div class="content-block font-size-14" style="margin-top:0rem;text-indent: 2em;" v-html="message.message">
        </div>
    </div>
</div>
</template>


<script>
import Axios from 'axios'
export default {
	data:function(){
		return {
			message : {}
		}
	} ,
	created:function(){
		var that = this ;
		Axios.get('/message/' + that.$route.params.id +  
			'?_token='+  window._Token + 
			'&api_token=' + window.apiToken 
		).then(function(response) {
			//console.log( that );
			let data = response.data ;
			if( data.errcode === 0 ) {
				that.message = data.data 
			}
		});
	}
}
</script>

<style>
</style>