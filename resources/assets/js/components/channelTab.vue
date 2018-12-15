<template>
<div class="bar bar-header-secondary buttons-tab">
	<router-link v-for="(item , sindex ) in blogCate" :to="{ name : 'microblog' , query:{'cate_id' : sindex } }" class="tab-link button" :class="{active : index == sindex }">
    	{{item}}
	</router-link>
	<router-link :to="{'name':'adv'}" class="tab-link button" :class="{active : index == 'adv'}">
    	点广告赚钱
	</router-link>
</div>	
</template>

<script>
export default {
	name : 'channelTab' ,
	props:[ 'index' ] ,
	data:function(){
		return {
			blogCate : window.blogCate
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