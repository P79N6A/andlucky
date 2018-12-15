<template>
<div class="page page-current" id="pageAdvs">
    <header class="bar bar-nav">
        <h1 class="title">广告赚钱</h1>
    </header>
    <div class="bar bar-header-secondary" style="padding:0rem;height:auto;">
    <swiper :options="swiperOption" class="buttons-tab" @tap="changeCate" ref="mySwiper">
    	<swiper-slide v-for="(item , sindex ) in blogCate" :id="item.id" class="tab-link button" :class="{active : cate_id == item.id }">
	    	{{item.title}}
		</swiper-slide>
		<swiper-slide id="adv" class="tab-link button" :class="{active : cate_id == 'adv'}">
	    	赚钱
		</swiper-slide>
    </swiper>
	</div>
    <div class="buttons-tab" style="position:absolute;top:4rem;width:100%;padding:0rem;">
        <span class=" button " :class="{active:priceA}" @click="setPrice( 2 , 0 )">大于2狗粮</span>
        <span class=" button " :class="{active:priceB}" @click="setPrice( 1, 2)" >1-2狗粮</span>
        <span class=" button " :class="{active:priceC}" @click="setPrice( 0 , 1 )">小于1狗粮</span>
    </div>
    <foot-bar select="channel"></foot-bar>
    <div class="content" style="top:6rem;background:#ecececbf;">
        <div class="content-block" style="padding:0rem 0.3rem;margin:.2rem 0rem;">
            <div
            	class="card facebook-card" 
            	v-for="( item , index ) in advs"
            	style="margin-bottom:.2rem;"
            >
            <router-link
            	:to="{name:'advshow' , params:{ 'id' : item.id } }" 
            >
			    <div class="card-header no-border" >
			        <div class="facebook-avatar" style="margin-right:.5rem;">
			            <img :src="getAvatar( item.user.avatar )" width="44" height="44">
			        </div>
			        <div class="facebook-name">
			            {{item.title}}
			            <span class="color-red font-size-20 font-bold" style="float:right;"> {{item.click_price}}&nbsp;</span>
			        </div>
			        <div class="facebook-date" >{{item.user.nickname ? item.user.nickname : '狗运'}} {{item.created_at}}</div>
			        <div class="facebook-date tag">
			        	
			        	<span v-for="( it , idx ) in item.tags">{{it}}</span>
			        </div>
			    </div>
			    <div class="card-content" style="clear:both;" >
			    </div>
			    <div class="card-footer" style="clear:both;">
			        <a href="#" class="link"><i class="iconfont icon-zan font-bold color-success"></i>&nbsp;{{item.up_times}}</a>
			        <a href="#" class="link"><i class="iconfont icon-cai font-bold color-danger"></i>&nbsp;{{item.down_times}}</a>
			        <a href="#" class="link">点击({{item.has_click_times}}/{{item.click_times}})</a>
			    </div>
			</router-link>
			</div>
        </div>
        <infinite-loading @infinite="loadMore" ref="infiniteLoading">
			<span slot="no-more">没有更多数据了</span>
			<span slot="no-result">没有更多数据了</span>
		</infinite-loading> 
    </div>
</div>
</template>

<script>
import footBar from '../../components/footNav'
import channelTab from '../../components/channelTab'
import InfiniteLoading from 'vue-infinite-loading'
import Axios from "axios" ;
import 'swiper/dist/css/swiper.css'
import { swiper, swiperSlide } from 'vue-awesome-swiper'
export default {
	data:function(){
		return {
			page:1 ,
			advs : [] ,
			blogCate : window.blogCate , 
			priceA : false ,
			priceB : false ,
			priceC : false ,
			price:'',
			cate_id : 'adv' ,
			swiperOption: {
	          slidesPerView: 7 ,
	          spaceBetween: 0 ,
	        } ,
	        clickTime : 0 
		}
	} ,
	mounted:function(){
		let i = 0 ;
		for( var m in this.blogCate ) {
			i++ 
		}
		let that = this
		
		this.$refs.mySwiper.$el.children[0].style.width = this.blogCate.length * 53.57 + 'px'
		this.$refs.mySwiper.swiper.slideTo( i , 300 , false );
	},
	methods:{
		changeCate:function( e ){
			let that = this
			let second = ( new Date() ).getTime() 
			if( second - that.clickTime > 1 ) {
				that.clickTime = second
				that.cate_id = e.target.id 
				if( e.target.id == 'adv' ) {
					that.$router.push({
						'name' : 'adv'
					})
				} else {
					that.page = 1 
					that.blogs = [] 
					that.title = that.blogCate[ e.target.id ]
					//有变动
					that.$router.push({
						'name' : 'microblog' , 
						'query' :{
							cate_id : that.cate_id 
						}
					})
				}
			}
		},
		tags : function( tags ){
			let html = ''
			if( tags ) {
				tags = tags.split(',')
				for( var i in tags ) {
					html += '<span>' + tags[i] + '</span>'
				}
			}
			return html 
		},
		c:function( e ){
			console.log( e )
		},
		loadMore:function( $state ){
			var that = this ;
			Axios.get('/advs' + 
				'?_token='+  window._Token + 
				'&api_token=' + window.apiToken + 
				'&price=' + that.price + 
				'&page=' + that.page + 
				'&timestamp=' + ( new Date() ).getTime()
			).then(function(response) {
				//console.log( that );
				$state.loaded();
				let data = response.data ;
				if( data.errcode === 0 ) {
					let list = data.data 
					if( list.total < that.page * 10 ) {
						$state.complete();
					}
					for( var i = 0 ; i < list.data.length ; i ++ ) {
						if( list.data[i].tags ) {
							list.data[i].tags = list.data[i].tags.split(',')
						} else {
							list.data[i].tags = [] 
						}
						that.advs.push( list.data[i] ) ;
					}
					that.page++ ;
				} else {
					$state.complete();
				}
			});
		} ,
		getAvatar : function( v ){
			return v ? v : '/images/logo.png' ;
		} ,
		setPrice( from , end ) {
			if( from && end == 0 ) {
				this.priceA = true ;
				this.priceB = false ;
				this.priceC = false ;
			}
			if( from && end ) {
				this.priceB = true ;
				this.priceA = false ;
				this.priceC = false ;
			}
			if( 0 == from && end ) {
				this.priceC = true ;
				this.priceB = false ;
				this.priceA = false ;
			}
			this.price = from + ','+ end ;
			this.page = 1 ;
			this.advs = [];
		    this.$nextTick(() => {
		        this.$refs.infiniteLoading.$emit('$InfiniteLoading:reset');
		    });
		}
	} ,
	components:{
		'foot-bar' : footBar ,
		'channel-tab' : channelTab ,
		swiper ,
		'swiper-slide' : swiperSlide ,
		InfiniteLoading 
	}
};
</script>

<style scoped>
.bar-header-secondary {
	background-color: #fff;
	padding:0rem;
}
.bar-header-secondary .button {
	top:0.15rem;
}
.bar-header-secondary .button.active {
	color:#734d41;
}
.bar:after {
	display: none;
}
.buttons-tab:after {
	display: none;
}
.bar .button {
	top:0rem;
}
.theme-coffee .bar .active, .theme-coffee .bar-tab .active {
	color:#3d3f40;
}
.buttons-tab .button {
	font-size:.85rem;
}
.card {
	font-size:.8rem;
}
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
