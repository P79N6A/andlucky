<template>
<div>
	<div class="card" v-for="( item , index ) in users" @click="$emit( 'userclick' , item.id )" >
	    <div class="card-content">
	      <div class="list-block media-list">
	        <ul>
				<li class="item-content">
					<div class="item-media img-round">
						<img :src="getAvatar(item.avatar)" width="44">
					</div>
					<div class="item-inner">
						<div class="item-title-row">
							<div class="item-title">{{item.nickname || '狗运'}}</div>
							<div class="item-right item-online-status item-online" v-if="item.online == 1">在线</div>
							<div class="item-right item-online-status" v-else>离线</div>
						</div>
						<div class="item-title-row">
						信用：
						{{calcCredit( item.not_pay_big_small ,item.lose_big_small , item.not_pay_big_small_cash , item.lose_big_small_cash ) }}
										&nbsp;,&nbsp;
						{{item.not_pay_big_small}}/{{item.lose_big_small}} 
						&nbsp; , &nbsp;{{item.not_pay_big_small_cash}}/{{item.lose_big_small_cash}}
						</div>
					</div>
				</li>
	        </ul>
	      </div>
	    </div>
	</div>
</div>
</template>

<script>
export default {
	props:[ 'users' ] ,
	methods:{
		getAvatar:function( v ){
			return v ? v : '/images/logo.png' ;
		} ,
		calcCredit:function( a , b , c , d ) {
			b = b > 0 ? b : 1 ;
      d = d > 0 ? d : 1 ;
      a = a > 0 ? a : 0  ;
      c = c > 0 ? c : 0 ;
      
      return (5 - ( 2 * a / b + 3 * c / d ).toFixed( 2 ) ).toFixed( 2 );
		},
	}
}
</script>

<style>
.item-online-status {
	font-size: 0.45rem;
    background: #e8e8e8;
    border-radius: .3rem;
    padding: 0.2rem .5rem;
}
.item-online {
	color:white;
	background-color: green;
}
.item-offline {
	color:gray;
}
</style>
