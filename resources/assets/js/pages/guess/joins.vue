<template>
<div class="page page-current" id="pageMyBigSmall">
    <header class="bar bar-nav">
        <h1 class="title">{{title}}</h1>
        <router-link class="button button-link button-nav pull-right" :to="{name:'bigsmall-help'}">
            <span class="icon icon-help"></span>
            帮助
        </router-link>
    </header>
    <div class="buttons-tab" style="position:absolute;top:2.25rem;width:100%;">
		<router-link :to="{name:'guess-mine'}" class="tab-link button " >我发起的</router-link>
		<router-link :to="{name:'guess-joins'}" class="tab-link button active" >我参与的</router-link>
    </div>
    <foot-bar select="chat"></foot-bar>
    <div class="content" style="top:4rem;background:rgb(249, 249, 249);" >
        <!-- 这里是页面内容区 -->
        
            <div class="card facebook-card" v-for="item in lists" style="margin-top:.2rem;" >
            	<router-link :to="{name:'guess-show' , params :{id:item.guess.id}}">
				    <div class="card-header">
				        <div class="">
				        		<span class="font-bold">狗粮&nbsp;</span>
				                <span class="color-brown font-size-20 font-bold"> {{item.cash}}&nbsp;</span>
				                <span class="pay_status" v-if="item.guess.status == 0" >
				                	待开台
				                </span>
				                <span class="pay_status haspay" v-if="item.guess.status == 1" >
				                	已开台
				                </span>
				        </div>
				    </div>
				    <div class="card-content">
				    	<div class="list-block media-list" v-if="'mine' == type ">
							<div class="game-info">
								<div class="col-4">
									<img class="avatar" :src="avatar( item.guess.user.avatar )" />
									<div class="nickname">{{item.guess.user.nickname || '狗运'}}</div>
									<div class="credit color-red">
										{{calcCredit( item.guess.user.not_pay_big_small ,item.guess.user.lose_big_small , item.guess.user.not_pay_big_small_cash , item.guess.user.lose_big_small_cash ) }}
										
									</div>
								</div>
								<div class="col-2">
									<div class="vs">VS</div>
									<div class="result" v-if="item.guess.status == 0 ">
										{{seeds[ item.seed ]}}
									</div>
									<div class="result" v-else >
										<span v-if="item.seed == item.guess.seed">胜利</span>
										<span v-else>失败</span>
									</div>
								</div>
								<div class="col-4">
									<img class="avatar" :src="avatar( item.user.avatar )" />
									<div class="nickname">{{item.user.nickname || '狗运'}}</div>
									<div class="credit color-red">
										{{calcCredit( item.user.not_pay_big_small ,item.user.lose_big_small , item.user.not_pay_big_small_cash , item.user.lose_big_small_cash ) }}
									</div>
								</div>
							</div>
						</div>
					</div>
				</router-link>
			    <div class="card-footer">
			        <div class="card-operation">
			            <div class="created_at">
			                <a href="#" class="link cursor">{{ item.created_at.substring(0,16)}}</a>
			            </div>
			            <div class="operation">
			                <router-link :to="{name:'bigsmall-detail' , params :{ id : item.id }}" class="button button-fill">查看</router-link>
			            </div>
			        </div>
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
import Axios from "axios"
import InfiniteLoading from 'vue-infinite-loading' 
import {formatDate} from '../../utils'
import menu from '../../components/menuNav' 
import footBar from '../../components/footNav'
export default {
	data:function(){
		return {
			title:'' ,
			lists : [] ,
			page : 1 ,
			seeds :{max : '大' , mid : '中' , small : '小'} ,
			type : 'mine' ,
		}
	} ,
	created:function(){
		this.type = this.$route.query['type'] || 'mine' 
		this.title = this.type == 'mine' ? '我发起的' : '邀请我的' 
	},
	methods:{
		loadMore : function( $state ){
			let that = this 
			console.log('doing')
			Axios.get('/guess/joins' +
				'?_token=' + window._Token  + 
				'&type=' + that.type + 
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
						that.lists.push( data.data.data[i] );	
					}
					if( data.data.last_page <= that.page ) {
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
			 return v.length > 200 ? v.substring( 0 , 200 ) + '....' : v 
		},
		datef:function( v ){
			return formatDate( new Date( v ) , 'yyyy-MM-dd') 
		} ,
		avatar:function( v ) {
			return v ? v : '/images/logo.png'
		} ,
		calcCredit:function( a , b , c , d ) {
			b = b > 0 ? b : 1 ;
      d = d > 0 ? d : 1 ;
      a = a > 0 ? a : 0  ;
      c = c > 0 ? c : 0 ;
      
      return (5 - ( 2 * a / b + 3 * c / d ).toFixed( 2 ) ).toFixed( 2 );
		}
	} ,
	watch:{
		type : function(){
			this.title = this.type == 'mine' ? '我发起的' : '邀请我的' 		
		} ,
		'$route' : function(){
			this.type = this.$route.query.type || 'mine'
			this.page = 1 
			this.lists = [] 
			this.$nextTick(() => {
		        this.$refs.infiniteLoading.$emit('$InfiniteLoading:reset');
		    });
		}
	},
	components :{
		'menu-nav' : menu ,
		'foot-bar' : footBar ,
		InfiniteLoading
	}
}

</script>
<style scope>
	.card-header {
		background: #fafafa ;
	}
	.card-header .pay_status.haspay {
	    background: #d8d8d8;
	    color:#5f646e;
	}

	.card-header .pay_status.notpay {
	  background: #c9ad69;
	}

	.color-brown {
		color:#734d41;
	}
	.game-info {
		display: flex;
		padding:.5rem;
		
	}
	.col-4 {
		width: 40%;
		text-align: center;
	}
	.col-4 .nickname{
		height:1.25rem;
		line-height: 1.25rem;
		color:#4a4a4a;
		font-size: .65rem;
	}
	.col-4 .credit{
		height:1.25rem;
		line-height: 1.25rem;
	}
	.col-4 img {
		margin:0 auto;
		width: 3rem;
	}
	.col-2 {
		width: 20%;
		text-align: center;
		display: flex;
		align-items: baseline;
		flex-wrap:wrap;
		padding:1rem 0rem;
	}
	.col-2 div {
		width: 100%;
	}
	.col-2 .vs {
		font-size: 1.2rem;
	}
	.col-2 .result {
		color:red;
	}
</style>
