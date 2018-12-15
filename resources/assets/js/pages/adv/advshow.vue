<template>
<div class="page" id="pageAdvShow">
    <header class="bar bar-nav">
        <a class="button button-link button-nav pull-left" @click="back" >
            <span class="icon icon-left"></span>
            返回
        </a>
        <h1 class="title">广告详情</h1>
    </header>
    <nav class="bar bar-tab" v-if="adv.url">
        <a class="tab-item" :href="adv.url">
            查看活动
        </a>
    </nav>
    <div class="content" style="background: #fff;">
        <div class="content-block-title font-size-20" style="margin:.5rem;font-size:1rem;">{{adv.title}}</div>
        
        <div class="content-block-title font-size-14" style="margin:.5rem;">
            <div class="row">
                <div class="col-100">日期:{{adv.created_at}}</div>
            </div>
            <div class="row" style="margin-top:.5rem;">
                <div class="col-100">点击次数({{adv.has_click_times}}/{{adv.click_times}})</div>
            </div>
            <div class="row" style="margin-top:.5rem;">
                <div class="col-100">标签
                  <span v-for="( it , idx ) in adv.tags">{{it}}</span>
                </div>
            </div>
        </div>
        

        <div class="content-block font-size-14" style="margin-top:0rem;text-indent: 2em;line-height: 1.25rem;" v-html="adv.posts_content">
        </div>
        <div v-if="adv.cover" class="content-block font-size-14" style="margin-top:0rem;" >
            
            <img :src="cover( adv.cover )" width="100%">
        </div>
        <div class="content-block">
            <div class="row">
                <div class="col-50">
                    <a class="button button-fill button-success" @click="up">赞({{adv.up_times}})</a>
                </div>
                <div class="col-50">
                    <a class="button button-fill button-warning" @click="down">踩({{adv.down_times}})</a>
                </div>
            </div>
        </div>
    </div>
</div>

</template>

<script>
import Axios from 'axios'
export default {
	data:function(){
		return {
			adv : {} ,
			hasLogin : false ,
			countdown : 3 ,
			hasPraise: true ,
      timer : null 
		}
	},
	created:function(){
		let that = this 
		that.hasLogin = !!window.apiToken  
		Axios.get('/adv/' + that.$route.params.id + '?api_token=' + window.apiToken )
		.then(function( ret ){
			console.log( ret )
			let data =ret.data
			that.adv = data.data.adv 
      if( that.adv.tags ) {
        that.adv.tags = that.adv.tags.split(',')
      } else {
        that.adv.tags = [] 
      }
			if( that.hasLogin && data.data.has_click == 0 ) {
				that.timer = setInterval( function(){
					that.countdown-- ;
					if( that.countdown === 0 ) {
						clearInterval( that.timer )
						that.gain()
					}
				} , 1000 )	
			}
			
		})
	} ,
  beforeDestroy:function(){
    if( this.timer ) {
      clearInterval( this.timer )
    }
  },
	methods:{
    back:function(){
      if( this.$route.query.from == 'form' ) {
        this.$router.push({
          'name' : 'adv' ,
        })
      } else {
        this.$router.go(-1)
      }
    },
		cover : function( v ) {
			return v ? v : '/images/logo.png' 
		} ,
		up:function(){
			let that = this
			if( !that.hasLogin ) {
				that.$toast('请先登录');
				return false 
			}
			Axios.post('/adv/up' , {
				'id' :  that.$route.params.id ,
				'_token' : window._Token ,
				'api_token' : window.apiToken
			}).then(function( ret ){
				console.log( ret )
				let data =ret.data
				if( data.errcode === 0 ) {
					that.adv.up_times = data.num 
				} else {
					that.$toast( data.msg );
				}
				
			})
		} ,
		down:function(){
			let that = this
			if( !that.hasLogin ) {
				that.$toast('请先登录');
				return false 
			}
			Axios.post('/adv/down' , {
				'id' :  that.$route.params.id ,
				'_token' : window._Token ,
				'api_token' : window.apiToken
			}).then(function( ret ){
				console.log( ret )
				let data =ret.data
				if( data.errcode === 0 ) {
					that.adv.down_times = data.num
				} else {
					that.$toast( data.msg );
				}
				
			})

		} ,
		gain:function(){
			let that = this
			Axios.post('/adv/gain/' + that.$route.params.id , {
				'id' :  that.$route.params.id ,
				'_token' : window._Token ,
				'api_token' : window.apiToken
			}).then(function( ret ){
				console.log( ret )
				let data =ret.data
				that.$toast( data.msg )
				if( data.errcode === 0 ) {
					that.has_click = true 
				}
				
			})
		}
	}
};
</script>

<style scoped>
.tag span {
  padding:.2rem .2rem;
  background: #dfdfdf;
  margin:.2rem .2rem;
  display: inline-block;
}
.tag span:first-child {
  margin-left: 0;
}
</style>