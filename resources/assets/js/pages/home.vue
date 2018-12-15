<template>
	<v-ons-page>
		<v-ons-carousel style="width: 100%; height: 160px" swipeable overscrollable auto-scroll auto-scroll-ratio="0.2">
	    	<v-ons-carousel-item v-for="(value, key) in items" :style="{backgroundColor: value}">
	        	<div style="text-align: center; font-size: 30px; margin-top: 20px; color: #fff;">{{key}}</div>
	      	</v-ons-carousel-item>
	    </v-ons-carousel>
    </v-ons-page>

</template>

<script>
	import Member from './member.vue' 
    export default {
    	data : function(){
			return {
				autoScroll:true ,
				'title' : '班点狗' ,
				carouselIndex: 0,
				items: {
					BLUE: '#085078',
					DARK: '#373B44',
					ORANGE: '#D38312'
				}
			}
		} ,
    	//props : [ 'title' ] 
    	methods: {
		    push() {
		    	console.log('push')
				this.$emit('push-page', Member );
		    } ,
		    md() {
		      return this.$ons.platform.isAndroid();
		    }
		}
    }
</script>

<style>
  .transition-box {
    margin-bottom: 10px;
    width: 200px;
    height: 100px;
    border-radius: 4px;
    background-color: #20A0FF;
    text-align: center;
    color: #fff;
    padding: 40px 20px;
    box-sizing: border-box;
    margin-right: 20px;
  }
</style>