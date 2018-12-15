<template>
<div class="page page-current" id="pageSearchUser">
    <header class="bar bar-nav buttons-tab">
	    <router-link :to="{name:'home' , query :{ cate : 'guess' }}" class="tab-link button" >押大小</router-link>
	    <router-link :to="{name:'home' , query :{ cate : 'bigsmall' }}" class="tab-link button">比大小</router-link>
	    <a class="tab-link button active">手气</a>
    </header>
    <div class="buttons-tab second-tab">
        <router-link :to="{name:'guess-mine'}" class="tab-link button active">押大小</router-link>
        <router-link :to="{name:'member-bigsmall'}" class="tab-link button">比大小</router-link>
    </div>
    <foot-bar select="home"></foot-bar>
    <div class="content" style="top:3.8rem;background:rgb(249, 249, 249);">
		<div class="guess-item">
			<router-link 
				tag="a" 
				:to="{name:'guess-show' , params:{ id : item.id }}" 
				v-for="(item , index ) in guesses"
				class="card facebook-card" >
				<div class="card-header no-border">
					<div class="list-block media-list">
						<ul>
							<li class="item-content">
								<div class="item-media img-round">
									<img :src="avatar( item.user.avatar )" width="44">
								</div>
								<div class="item-inner">
									<div class="item-title-row">
										<div class="item-title">{{item.user.nickname || '狗运'}}</div>
										<div class="pull-right" v-if="item.status == 0">
											未开台
										</div>
										<div class="pull-right" v-if="item.status == 1">
											已开台
										</div>
										<div class="pull-right" v-if="item.status == 2">
											已失效
										</div>
									</div>
									<div class="item-title-row">
		           			<div class="item-title">奖池:{{item.cash}}</div>
		           			<span class="pull-right babel" v-html="rate( item.rate )"></span>
			            </div>
						      <!--
									<div class="item-title-row">
										<div class="item-title" v-if="item.status == 1" v-html="seed( item.seed )">
											
										</div>
									信用：
									{{credit( item.user.not_pay_big_small ,item.user.lose_big_small , item.user.not_pay_big_small_cash , item.user.lose_big_small_cash ) }}
												&nbsp;,&nbsp;
								{{item.user.not_pay_big_small}}/{{item.user.lose_big_small}} 
								&nbsp; , &nbsp;{{item.user.not_pay_big_small_cash}}/{{item.user.lose_big_small_cash}}
									
									</div>
								-->
								</div>
							</li>
				        </ul>
			    	</div>
				</div>
				
			    <div class="card-footer no-border">
			    	<a>编号:{{item.id}}</a>
			    	<a>{{item.created_at}}</a>
			    	<!--
			    	<div v-for="(sitem ,index) in item.join" class="join-block">
						<div class="join-user-avatar img-round">
							<img :src="sitem.user.avatar" />
						</div>
						<div class="">{{credit( sitem.user.not_pay_big_small ,sitem.user.lose_big_small , sitem.user.not_pay_big_small_cash , sitem.user.lose_big_small_cash ) }}</div>
						<div class="">{{sitem.user.nickname || '狗运'}}</div>
					</div>
					-->
			    </div>
				
			</router-link>
		</div>
		
        

        <infinite-loading @infinite="loadGuess" ref="infiniteLoading">
            <span slot="no-more" class="no-data">没有更多数据了</span>
            <span slot="no-result" class="no-data">没有更多数据了</span>
        </infinite-loading> 
    </div>
    <guess-modal v-on:closeModal="closeModal" :guess="guess" :show="showGuessModal" ></guess-modal>
</div>

</template>

<script >
import footBar from '../../components/footNav'
import InfiniteLoading from 'vue-infinite-loading'
import user from '../../components/user'
import Axios from "axios" ;
import guessModal from '../../components/guess'
import guessItem from '../../components/guessitem'
export default {
	data :function(){
		return {
			keyword:'' ,
			page : 1 ,
			maxpage : 0 ,
			users : [] ,
			guesses : [] ,
			to:0,
			timer : null ,
			guessId : 0 ,
			max : 0 ,
			min : 0 ,
			type : 'guess' ,
			showGuessModal : false ,
			guess :{}

		}
	},
	created:function(){
		console.log( 1 )
		//this.loadMore()
	},
	destroyed:function(){
		clearInterval( this.timer )
	},
	methods:{
		ale:function(){
			alert( 3 );
		},
		loadGuess:function( $state ){
			var that = this ;
			Axios.get('/guess/mine' + 
				'?_token='+  window._Token + 
				'&keyword=' + that.keyword + 
				'&api_token=' + window.apiToken 
			).then(function(ret) {
				$state.loaded();
				if( ret.data.errcode === 0 ) {
					//邀请成功 回到发送信息的页面
					let data = ret.data.data.data ;
					for( var i in data ) {
						that.guesses.push( data[i] )
					}
					if( ret.data.data.last_page >= that.page ) {
						$state.complete();
					} else {
						that.page++ ;
					}
				} else {
					$state.complete();
				}
			});
		},
		change: function( type ){
			let that = this
			that.page = 1
			that.type = type 
			that.users = [] 
			if( type == 'bigsmall') {
				that.loadMore()
			} else {
				that.page = 1 
				that.maxpage = 0 
				that.guesses = [] 
				that.$nextTick(() => {
			        this.$refs.infiniteLoading.$emit('$InfiniteLoading:reset');
			    });
			}
		} ,
		search:function(){
			let that = this 
			if( !that.keyword ) {
				return false 
			}
			that.page = 1 
			this.loadMore()
			
		} ,
		avatar : function( src ){
            return src ? src : '/images/logo.png'
        } ,
		showBigSmallModal:function( id ) {
			let that = this 
			that.to = id 
			that.showBigSmall = 'block' 

		} ,
		closeModal:function(){
			this.to = 0 
			this.showGuessModal = false 
		},
		detechOnline:function(){
			let that = this
			let username = [] ;
			for( var i in that.users ) {
				username.push( that.users[i].name)
			}
			Axios.post('/longsearch'  , {
				'_token' :  window._Token  ,
				'keyword' : that.keyword ,
				user_name : username.join(',')
			}).then(function(response) {
				//console.log( that );
				let data = response.data ;
				if( data.errcode == 0 && data.data && data.data.length > 0 ) {
					that.users = data.data 
				}
				console.log( data );
				//that.detechOnline()
			});
		} ,
		seed : function( v ) {
            let s = {'max' : '大' , 'mid' : '中' , 'min' : '小' } ;
            return s[ v ] ;
        } ,
		credit:function( a , b , c , d ){
			b = b > 0 ? b : 1 ;
      d = d > 0 ? d : 1 ;
      a = a > 0 ? a : 0  ;
      c = c > 0 ? c : 0 ;
      
      return (5 - ( 2 * a / b + 3 * c / d ).toFixed( 2 ) ).toFixed( 2 );
		} ,
		take:function( id ) {
			let that = this
			Axios.get( '/guess/take/' + id  ,
			{

			}).then(function( response ){
				let data = response.data 
				console.log( data )
				if( data.errcode === 0 ) {
					that.showGuessModal = true 
					that.guess = data.data 
				} else {
					that.$toast( data.msg );
				}
			})
			
		} ,
		rate:function( v ){
			return v ? '赔率:' + v.toFixed( 2 ) : '赔率:0'
		}
	},
	components:{
		'foot-bar' : footBar ,
		'user-list' : user ,
		'guess-modal' : guessModal ,
		'guess-item' : guessItem ,
		InfiniteLoading	
	}
};

</script>


<style scoped>
.infinite-scroll-preloader {
	margin-top:-20px;
}
.searchbar .searchbar-cancel {
	color:#fff;
}
.card {
    margin: 0rem;
    margin-bottom: .5rem;
    display: block;
}
.card-header {
	background: none;
}
.facebook-card .card-footer {
	background: none;
}
.index_title {
	background: url('/images/dog_header.png') no-repeat ;
	background-size:2.2rem 2.2rem;
	padding-left: 3rem;
	height:2.2rem;
	display: inline-block;
	line-height: 2.2rem;
	font-size: .8em;
	color:#e8cc40;
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
