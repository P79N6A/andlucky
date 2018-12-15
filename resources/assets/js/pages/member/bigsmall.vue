<template>
<div class="page page-current" id="pageMyBigSmall">
    <header class="bar bar-nav buttons-tab">
	    <router-link :to="{name:'home' , query :{ cate : 'guess' }}" class="tab-link button" >押大小</router-link>
	    <router-link :to="{name:'home' , query :{ cate : 'bigsmall' }}" class="tab-link button">比大小</router-link>
	    <a class="tab-link button active">手气</a>
    </header>
    <!--<menu-nav index="bigsmall"></menu-nav>-->
    <div class="buttons-tab second-tab">
        <router-link :to="{name:'guess-mine'}" class="tab-link button">押大小</router-link>
        <router-link :to="{name:'member-bigsmall'}" class="tab-link button active">比大小</router-link>
    </div>
    <foot-bar select="home"></foot-bar>
    <div class="content" style="top:3.8rem;background:rgb(249, 249, 249);" >
        <!-- 这里是页面内容区 -->
        
            <div class="card facebook-card" v-for="item in lists" style="margin-top:.2rem;" >
            	<router-link :to="{name:'bigsmall-detail' , params :{id:item.id}}">
				    <div class="card-header">
				        <div class="">
				        		<span class="font-bold">狗粮&nbsp;</span>
				                <span class="color-brown font-size-20 font-bold"> {{item.cash_deposit}}&nbsp;</span>
				                <span class="pay_status haspay" v-if="item.status == 0 && user_id == item.user_id " >
				                	等待对方应战
				                </span>
				                <span class="pay_status haspay" v-if="item.status == 0 && user_id != item.user_id " >
				                	等待应战
				                </span>
				                <span class="pay_status notpay" v-if="item.status == 1" v-html="result( user_id == item.user_id , item)">
				                	
				                </span>
				                <span class="pay_status haspay" v-if="item.status == 2">
				                	赢家退狗粮
				                </span>
				                <span class="pay_status haspay" v-if="item.status == 3">
				                	拒绝应战
				                </span>
				                <span class="pay_status haspay" v-if="item.status == 4">
				                	用户撤销战斗
				                </span>
				                <span class="pay_status haspay" v-if="item.status == 6">
				                	系统撤销战斗
				                </span>
				                <span class="pay_status haspay" v-if="item.status == 5 && user_id == item.user_id && item.user_num < item.inviter_num">
				                	系统退狗粮
				                </span>
				                <span class="pay_status haspay" v-if="item.status == 5 && user_id == item.user_id && item.user_num > item.inviter_num">
				                	系统退狗粮
				                </span>
				                <span class="pay_status haspay" v-if="item.status == 5 && user_id != item.user_id && item.user_num < item.inviter_num">
				                	系统退狗粮
				                </span>
				                <span class="pay_status haspay" v-if="item.status == 5 && user_id != item.user_id && item.user_num > item.inviter_num">
				                	系统退狗粮
				                </span>
				        </div>
				    </div>
				    <div class="card-content">
				    	<div class="list-block media-list" v-if="user_id == item.user_id ">
							<div class="game-info">
								<div class="col-4">
									<img class="avatar" :src="avatar( item.user.avatar )" />
									<div class="nickname">{{item.user.nickname || '狗运'}}</div>
									<div class="credit color-red">
										{{calcCredit( item.user.not_pay_big_small ,item.user.lose_big_small , item.user.not_pay_big_small_cash , item.user.lose_big_small_cash ) }}
										
									</div>
								</div>
								<div class="col-2">
									<div class="vs">VS</div>
									<div class="result" v-if="item.status ==1 || item.status == 2 || item.status == 5">
										{{item.user_num}}&nbsp;:&nbsp;{{item.inviter_num}}
									</div>
								</div>
								<div class="col-4">
									<img class="avatar" :src="avatar( item.inviter.avatar )" />
									<div class="nickname">{{item.inviter.nickname || '狗运'}}</div>
									<div class="credit color-red">
										{{calcCredit( item.inviter.not_pay_big_small ,item.inviter.lose_big_small , item.inviter.not_pay_big_small_cash , item.inviter.lose_big_small_cash ) }}
									</div>
								</div>
							</div>
						</div>
						<div class="list-block media-list" v-else>
							<div class="game-info">
								<div class="col-4">
									<img class="avatar" :src="avatar( item.inviter.avatar )" />
									<div class="nickname">{{item.inviter.nickname || '狗运'}}</div>
									<div class="credit color-red">
										{{calcCredit( item.inviter.not_pay_big_small ,item.inviter.lose_big_small , item.inviter.not_pay_big_small_cash , item.inviter.lose_big_small_cash ) }}
									</div>
								</div>
								<div class="col-2">
									<div class="vs">VS</div>
									<div class="result" v-if="item.status ==1 || item.status == 2 || item.status == 5">
										{{item.inviter_num}}&nbsp;:&nbsp;{{item.user_num}}
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
import menu from '../../components//menuNav' 
import footBar from '../../components/footNav'
export default {
	data:function(){
		return {
			title:'' ,
			lists : [] ,
			page : 1 ,
			type : 'mine' ,
			user_id : 0 ,
			visitor : {}
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
			Axios.get('/bigsmall/mine' +
				'?_token=' + window._Token  + 
				'&api_token=' + window.apiToken + 
				'&page=' + that.page 
			, {
				responseType : 'json'
			}).then(function( ret ){
				$state.loaded();
				let data = ret.data 
				if( data.errcode === 0 ) {
					//邀请成功 回到发送信息的页面
					that.visitor = data.visitor 
					that.user_id = data.visitor.id 
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
		},
		changeRange:function(  v ){
			this.$router.push({
				name : 'member-bigsmall' ,
				query:{
					type : v 
				}
			});
			/**
			this.type = v 
			this.page = 1 
			this.lists = [] 
			this.$nextTick(() => {
		        this.$refs.infiniteLoading.$emit('$InfiniteLoading:reset');
		    });
		    **/
		} ,
		result:function( mine , item ){
			if( mine ) {
				return item.user_num > item.inviter_num ? '我方获胜' : '对手获胜'
			} else {
				return item.user_num < item.inviter_num ? '我方获胜' : '对手获胜'
			}
		},
		pay:function( id ) {
			let that = this
			Axios.get('/adv/' + id + '/pay' + 
				'?_token=' + window._Token  + 
				'&api_token=' + window.apiToken
			, {
				responseType : 'json'
			}).then(function( ret ){
				let data = ret.data 
				that.$toast( data.msg )
				if( data.errcode === 0 ) {
					//邀请成功 回到发送信息的页面 支付完成 更改这个的支付信息
					for( var i in that.advs ) {
						if( that.advs[i].id == id ) {
							that.advs[i].pay_status = 1 
							break 
						}
					}
				} else if( data.errcode === 30000 ) {
					setTimeout( function(){
                        that.$router.push({
                            name : 'charge' ,
                            params:{

                            }
                        })    
                    } , 1500 )	
				}
				
			} , function( ret ){
				console.log( ret );
			});
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
};

</script>
<style scoped>
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
	.buttons-tab {
		background-color: #734d41;
	}
	.buttons-tab .button {
		height: 2.2rem;
		line-height: 2.2rem;
		font-size: 0.75rem;
	}
	.bar .button {
		top:0rem;
	}
	.buttons-tab .button.active {
		border-color:#fff57e;
	}
	a.btn-create {
		color:#734d41;
	}
	.second-tab {
		background: #fff ;
		height: 1.7rem;
		line-height: 1.7rem;
	}
	.second-tab .button {
		height: 1.7rem;
		line-height: 1.7rem;
		font-size: 0.65rem;
	}
	.second-tab .button.active {
		border-color: #734d41;
	}
</style>
