<template>
<!--	
	<v-ons-navigator swipeable
            :page-stack="pageStack"
            @push-page="pageStack.push($event)"
          >

    </v-ons-navigator>
-->
      <v-ons-page>
	    <v-ons-toolbar>
	      <div class="center">{{ title }}</div>
	    </v-ons-toolbar>

	    <v-ons-tabbar position="auto"
	      :tabs="tabs"
	      :visible="true"
	      :index.sync="activeIndex"
	    >
	    </v-ons-tabbar>
	  </v-ons-page>
</template>

<script>
	import Home from './home.vue' 
	import Post from './post.vue' 
	import Member from './member.vue' 
	export default {
		data : function(){
			return {
				pageStack:[ Home ] ,
				activeIndex: 0,
				tabs: [
					{
						icon: this.md() ? null : 'ion-home',
						label: '首页',
						page: Home,
						props: {
							myProp: 'This is a page prop!'
						} ,
						title : '首页' 
					},
					{
						icon: this.md() ? null : 'ion-ios-bell',
						label: '发布',
						page: Post ,
						title : '发布广告'
					},
					{
						icon: this.md() ? null : 'ion-ios-settings',
						label: '我的',
						page: Member ,
						title : '会员中心'
					}
				]
			}
		} , 
		methods: {
			md() {
		      return this.$ons.platform.isAndroid();
		    }
		} , 
		computed: {
			title() {
				return this.tabs[this.activeIndex].title ;
			}
		}
	}

</script>