<template id="history">
<div>
	<router-link tag="div" :to="{ name: 'im-chat' , params:{ user_id : user_id == item.receiver_id ? item.receiver_id : item.sender_id } }" class="card" v-for="item in friends" >
	    <div class="card-content">
	      <div class="list-block media-list">
	        <ul>
	          <li class="item-content">
	            <div class="item-media img-round">
	            	<img v-if="user_id == item.receiver_id" :src="getAvatar( item.receiver ? item.receiver.avatar :  '' )" width="44">
	            	<img v-else :src="getAvatar( item.sender ? item.sender.avatar : '' )" width="44">
	            </div>
	            <div class="item-inner">
	              <div class="item-title-row">
	                <div class="item-title" v-if="user_id == item.receiver_id">{{item.receiver ? item.receiver.nickname : '系统消息' }}</div>
	                <div class="item-title" v-else >{{item.sender ? item.sender.nickname : '系统消息'}}</div>
	              </div>
	              <div class="item-subtitle" v-if="'txt' == item.type">{{item.body.data}}</div>
	              <div class="item-subtitle" v-if="'addfriend' == item.type">{{item.body.data}}</div>
	              <div class="item-subtitle" v-if="'image' == item.type">[图片]</div>
	            </div>
	          </li>
	        </ul>
	      </div>
	    </div>
	</router-link>
</div>
</template>

<script>
export default {
	props:[
		'user_id' ,
		'friends'
	] ,
	methods:{
		getAvatar:function( v ) {
			return v ? '/' + v : '/images/logo.png' ;
		}
	}
}
</script>