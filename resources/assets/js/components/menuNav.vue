<template>
<div class="bar bar-header-secondary buttons-tab">
    
    <router-link :to="{name:'im-friends'}" class="tab-link button" :class="{active : index == 'friend'}">
    	我的好友
	</router-link>
	<router-link :to="{name:'im-history'}" class="tab-link button" :class="{active : index == 'index' }">
    	最近聊天
	</router-link>
	<!--
	<router-link :to="{name:'member-bigsmall'}" class="tab-link button" :class="{active : index == 'bigsmall'}">
    	我的手气
	</router-link>
	
	<router-link :to="{name:'im-thief'}" class="tab-link button" :class="{active : index == 'stolen-me'}">
    	谁摸过我
	</router-link>
	-->
</div>	
</template>

<script>
export default {
	name : 'menuNav' ,
	props:[ 'index' ] ,
	data:function(){
		return {

		}
	}
}	

</script>
<style scoped>
.bar.buttons-tab {
	background-color: #fff;
	height: 1.5rem;
	line-height: 1.5rem;
	padding:0rem;
}
.bar.buttons-tab .button {
	font-size:.65rem;
	height:1.5rem;
	line-height: 1.5rem;
	top:0rem;
	color:#000;
}
.bar.buttons-tab .button.active {
	color:#724c41;
	border-color:#724c41;
}
</style>