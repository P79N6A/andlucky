<template>
<div class="page page-current" id="pageSearchUser">
    <header class="bar bar-nav buttons-tab">
	    <a class="tab-link button" :class="{active : type == 'guess' }" @click="change( 'guess') ">押大小</a>
	    <a class="tab-link button" :class="{active : type == 'bigsmall' }" @click="change( 'bigsmall')">比大小</a>
	    <router-link class="tab-link button" :to="{name: route }">手气</router-link>
    </header>
    <div v-if="type=='bigsmall' " class="bar bar-header-secondary" style="height:1.7rem;">
		<div class="searchbar searchbar-active">
			<a class="searchbar-cancel" @click="search">搜索</a>
			<div class="search-input">
				<label class="icon icon-search" for="search"></label>
				<input type="search" autocomplete="off"  v-on:keyup.13="search" name="keyword" v-model="keyword" placeholder='输入用户号搜索用户'/>
			</div>
		</div>
	</div>
	<div v-if="type=='guess'" class="bar bar-header-secondary" style="height:1.7rem;text-align:center;line-height:1.7rem;background:none;">
		    <router-link class="pull-right btn-create" :to="{name:'guess-form'}" >点我开台</router-link>
	</div>
    <foot-bar select="home"></foot-bar>
    <div class="content">
		<guess-item v-if="type == 'guess'" :guesses="guesses" v-on:join="take">
			这里是押大小
		</guess-item>
        <div v-if="type == 'bigsmall'" class="content-block" style="padding:0rem;margin-top:0rem;">
        	<user-list v-if="users.length > 0" @userclick="showBigSmallModal" :users="users"></user-list>
        	<div v-else="users.length > 0" class="content-block no-data">
	        	暂无在线用户
	        </div>
        </div>
        

        <infinite-loading v-if="type == 'guess'" @infinite="loadGuess" ref="infiniteLoading">
            <span slot="no-more" class="no-data">没有更多数据了</span>
            <span slot="no-result" class="no-data">没有更多数据了</span>
        </infinite-loading> 
    </div>
    <bigsmall-modal v-on:closeModal="closeModal" :to="to"></bigsmall-modal>
    <guess-modal v-on:closeModal="closeModal" :guess="guess" :show="showGuessModal" ></guess-modal>
</div>

</template>

<script >
import footBar from '../components/footNav'
import InfiniteLoading from 'vue-infinite-loading'
import user from '../components/user'
import Axios from "axios" ;
import bigSmallModal from '../components/bigsmall'
import guessModal from '../components/guess'
import guessItem from '../components/guessitem'
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
			guess :{} ,
			route : 'guess-mine'

		}
	},
	created:function(){
		this.type = this.$route.query.cate || 'guess' 
		this.route = this.type == 'guess' ? 'guess-mine' : 'member-bigsmall'
		if( this.type == 'bigsmall') {
			this.loadMore()
		}
	},
	destroyed:function(){
		clearInterval( this.timer )
	},
	methods:{
		loadMore:function( ){
			var that = this ;
			Axios.get('/onlineuser' + 
				'?_token='+  window._Token + 
				'&keyword=' + that.keyword + 
				'&api_token=' + window.apiToken 
			).then(function(response) {
				//console.log( that );
				let data = response.data ;
				console.log( data );
				that.users = data.data 
				//that.detechOnline()
				that.timer = setInterval( function(){that.detechOnline()} , 5000 );
			});
		} ,
		loadGuess:function( $state ){
			var that = this ;
			Axios.get('/guess/all' + 
				'?_token='+  window._Token + 
				'&keyword=' + that.keyword + 
				'&page=' + that.page + 
				'&api_token=' + window.apiToken 
			).then(function(ret) {
				$state.loaded();
				if( ret.data.errcode === 0 ) {
					//邀请成功 回到发送信息的页面
					let data = ret.data.data.data ;
					if( that.page == 1 ) {
						that.guesses = [] 
					}
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
				clearInterval( this.timer )
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
			
		}
	},
	watch :{
		$route : function( v , v2 ){
			if( this.type != v.query.cate ) {
				this.type = v.query.cate || 'guess'
				this.route = this.type == 'guess' ? 'guess-mine' : 'member-bigsmall'
				this.change( this.type )
			}
		}
	} ,
	components:{
		'foot-bar' : footBar ,
		'user-list' : user ,
		'bigsmall-modal' : bigSmallModal ,
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
	font-size: .65rem;
}
</style>
