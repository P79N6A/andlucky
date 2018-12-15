<template>
<div class="page" id="pageHelp">
<header class="bar bar-nav">
    <a class="button button-link button-nav pull-left" @click="$router.go(-1)">
        <span class="icon icon-left"></span>
        返回
    </a>
    <h1 class="title">帮助说明</h1>
</header>
<div class="content" >
    <div class="content-block font-size-14" style="margin-top:0rem;text-indent: 2em;" v-html="content">
    </div>
</div>
</div>

</template>

<script>
import Axios from 'axios'
export default {
	data:function(){
		return {
			content : '' 
		}
	} ,
	created:function(){
		var that = this ;
		Axios.get('/help' + 
			'?_token='+  window._Token + 
			'&api_token=' + window.apiToken 
		).then(function(response) {
			//console.log( that );
			let data = response.data ;
			if( data.errcode === 0 ) {
				that.content = data.data.content 
			}
		});
	}
}
</script>

<style>
</style>