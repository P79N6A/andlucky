<template id="friend">
<div>	
	<router-link tag="div" :to="{ name: 'chat' , params:{ user_id : item.uid } }" class="card" v-for="item in friends" >
	    <div class="card-content">
	      <div class="list-block media-list">
	        <ul>
	          <li class="item-content">
	            <div class="item-media img-round">
	            	<img src="http://gqianniu.alicdn.com/bao/uploaded/i4//tfscom/i3/TB10LfcHFXXXXXKXpXXXXXXXXXX_!!0-item_pic.jpg_250x250q60.jpg" width="44">
	            </div>
	            <div class="item-inner">
	              <div class="item-title-row">
	                <div class="item-title">{{item.name}}</div>
	              </div>
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
		'friends'
	]
}
</script>