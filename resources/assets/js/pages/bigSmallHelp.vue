<template>
<div class="page" id="pageAboutus">
<header class="bar bar-nav">
    <a class="button button-link button-nav pull-left back" @click="$router.go(-1)">
        <span class="icon icon-left"></span>
        返回
    </a>
    <h1 class="title">比大小说明</h1>
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
		Axios.get('/bigsmall/desc' , {
			responseType : 'json'
		}).then(function(response) {
			console.log( that );
			that.content = response.data.desc.replace(/\r\n/ , '<br/>') ;
			
		});
	}
}
</script>

<style>
</style>