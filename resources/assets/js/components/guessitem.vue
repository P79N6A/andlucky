<template>
<div class="guess-item" style="background:rgb(249, 249, 249);">
	<div class="card facebook-card" v-for="(item , index ) in guesses">
		<div class="card-header no-border">
			<div class="list-block media-list">
				<ul>
					<li class="item-content">
						<div class="item-inner">
							<div class="item-title-row">
								<div class="item-title">编号:{{item.id}}</div>
								<div class="pull-right">
									<span class="icon icon-clock"></span>&nbsp;&nbsp;<span v-html="countDown( item.end_time )"></span>
								</div>
							</div>
						</div>
					</li>
					<li class="item-content">
						<div class="item-media img-round">
							<img :src="avatar ( item.user ? item.user.avatar : '' )" width="44">
						</div>
						<div class="item-inner">
							<div class="item-title-row">
								<div class="item-title">{{item.user.nickname || '狗运'}}</div>
								<span class="pull-right babel" v-html="rate( item.rate )"></span>
							</div>
							<!--
							<div class="item-title-row">
				           			<div class="item-title" v-html="mobile( item.user.mobile )"></div>
				           			
				            </div>
				            -->
							<div class="item-title-row">
							信用：
							{{credit( item.user.not_pay_big_small ,item.user.lose_big_small , item.user.not_pay_big_small_cash , item.user.lose_big_small_cash ) }}
										&nbsp;,&nbsp;
						{{item.user.not_pay_big_small}}/{{item.user.lose_big_small}} 
						&nbsp; , &nbsp;{{item.user.not_pay_big_small_cash}}/{{item.user.lose_big_small_cash}}
							</div>
						</div>
					</li>
		        </ul>
	    	</div>
				
				
		</div>
	    <div class="card-footer no-border">
	    	<div v-for="(sitem ,index) in item.join" class="join-block" @click="join">
				<div class="join-user-avatar img-round">
					<img :src="avatar( sitem.user.avatar )" />
				</div>
				<div class="">{{credit( sitem.user.not_pay_big_small ,sitem.user.lose_big_small , sitem.user.not_pay_big_small_cash , sitem.user.lose_big_small_cash ) }}</div>
				<div class="">{{sitem.user.nickname || '狗运'}}</div>
			</div>
			<div v-for="(sitem , index ) in range( item )" class="join-block" @click="$emit('join' , item.id )">
				<div class="join-user-avatar img-round join-grid">
					<img src="/images/jia.png" />
				</div>
				<div class="">0.0</div>
				<div class="">等待加入</div>
			</div>
	    </div>
	</div>
</div>
</template>
<script>
export default {
	props:[ 'guesses' ] ,
	data:function(){
		return {
		}
	},
	created:function(){

	},
	methods:{
		range:function( item ){
			let arr = []
			let l = item.join.length 
			for( var i = 0 ; i < 3-l ;i++ ) {
				arr.push({})
			}
			return arr 
		},
		join:function(){

		} ,
		avatar : function( src ){
            return src ? src : '/images/logo.png'
        } ,
		mobile :function(  v ) {
			return v.substr( 0 , 4 ) + '****' + v.substr( 8 ) ;
		} ,
		countDown : function( v ) {
			let n = ( new Date() ).getTime() / 1000 ;
			let l = v - n 
			let h = Math.floor( l /3600 ) 
			let m = Math.floor( ( l % 3600 ) / 60 )
			let s = Math.floor ( l % 60 )
			if( h > 0 ) {
				return h + '小时' + m + '分钟' + s + "秒"
			}
			if( m > 0 ) {
				return h + '小时' + m + '分钟' + s + "秒"	
			}
			return s + '秒' 
		} ,
		credit:function( a , b , c , d ){
			b = b > 0 ? b : 1 ;
      d = d > 0 ? d : 1 ;
      a = a > 0 ? a : 0  ;
      c = c > 0 ? c : 0 ;
      
      return (5 - ( 2 * a / b + 3 * c / d ).toFixed( 2 ) ).toFixed( 2 );
		} ,
		rate : function( v ){
			return v ? '赔率:' + v.toFixed( 2 ) : '赔率:0'
		}
	}
};
</script>

<style scoped>
.guess-header {
	height: 1.5rem;
	line-height: 1.5rem;
}
.card {
	margin-bottom: .5rem;
}
.facebook-card .card-header {
	padding:0.2rem 0rem;
}
.list-block .item-content {
	padding:0rem 0.5rem;
}
.facebook-card .card-footer {
	padding:0rem 0rem;
}
.guess-title {
	position: relative;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
    font-size: 0.8rem;
    text-transform: uppercase;
    line-height: 1;
    color: #6d6d72;
    margin: 1.75rem 0rem 0.5rem;
}
.timer {
	color:#734d41;
	font-size: .6rem;
}
.babel {
	background-color: red;
	color:#fff;
	border-radius: .5rem;
	line-height: 1rem;
	height: 1rem;
	padding: 0 .5rem;
}
.join-block {
	padding: .3rem .5rem;
	width: 33%;
	height: 5.5rem;
	text-align: center;
	background: #dfdfdf;
	position: relative;
}
.join-user-avatar {
	
}
.join-user-avatar img {
	width: 2.5rem;
	height: 2.5rem;
}
.join-id-type {
	position: absolute;
    color: #f7f7f7;
    right: -.3rem;
    top: -.3rem;
    background: #734d41;
    font-size: .6rem;
    border-radius: 50%;
    height: 1rem;
    width: 1rem;
    display: block;
    text-align: center;
    line-height: 1rem;
    font-style: normal;
}
</style>
