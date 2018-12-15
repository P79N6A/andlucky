<template id="big_small">
	<div class="modal-overlay modal-overlay-visible" :style="{display: showModal }" @click="hideModal">
        <div class="modal modal-in" style="display:block;top:5rem;background:#fff;" @click.stop.self >
	        <div class="content-block tip" v-if="!onLine">
	            <p >
					当前用户不在线<br/>请过5秒再刷新
			    </p>
	        </div>
	        <div class="content-block list-block">
			    <ul>
					<!-- Text inputs -->
					<li>
			        	<div class="item-content">
			        		<div class="item-inner">
			            		<div class="item-input">
			            			<input type="text" v-model.number="promise" style="text-align:center;" :placeholder="'狗粮数需在' + min +'到'+ max +'之间'">
			            		</div>
			          		</div>
			        	</div>
			    	</li>
			    </ul>
			</div>
			<div class="content-block">
				<div class="button button-danger button-fill button-big" v-on:click="next()">发起战斗</div>
			</div>
    	</div>
    </div>
</template>

<script>
import Axios from "axios" ;

export default {
	props:[ 'to' ] ,
	data : function(){
		return {
			desc : '' ,
			min : 0 ,
			max : 0 ,
			promise: '' ,
			onLine : true ,
			showModal : 'none'
		}
	} ,
	created : function(){
		var that = this ;
		Axios.get('/bigsmall/desc' , {
			responseType : 'json'
		}).then(function(response) {
			console.log( that );
			that.min = response.data.min ;
			that.max = response.data.max ;
		});
	 },
	methods : {
		back : function(){
			this.$router.back();
		} ,
		next : function(){
			var that = this ;
			console.log( that );
			if( !that.promise ) {
				that.$toast("请填写狗粮" , 1500 , {
					'top' : '50%'
				}) ;
				return false ;
			}
			if( that.min > that.promise ) {
				that.$toast("狗粮最小为" + that.min , 1500 , {
					'top' : '50%'
				}) ;
				return false ;	
			}
			if( that.max < that.promise ) {
				that.$toast("狗粮最大为" + that.max , 1500 , {
					'top' : '50%'
				}) ;
				return false ;	
			}
			if( false === that.onLine ) {
				that.$toast("当前用户不在线，请换个好友试试" , 1500 , {
					'top' : '50%'
				}) ;
				return false ;	
			}
			Axios.put('/bigsmall/invite' , {
				'promise' : that.promise ,
				'_token' : window._Token ,
				'api_token' : window.apiToken ,
				'invite_user' : that.to ,
			} , {
				responseType : 'json'
			}).then(function( ret ){
				console.log( ret )
				that.$toast( ret.data.msg , 5500 );
				if( ret.data.errcode === 0 ) {
					//邀请成功 回到发送信息的页面
					that.hideModal()
				} else if( ret.data.errcode == 10006 ) {
					setTimeout( function(){
						that.$router.push({
							name : 'member-baseinfo'
						})
					} , 2500 );
				}
				
			} , function( ret ){
				that.$router.push({
					name : 'login'
				})
				console.log( ret );
			});


			
		} ,
		hideModal : function(){
			this.showModal = 'none'
			this.$emit('closeModal')
		}
	} ,
	mounted : function(){
		console.log( this.$route )
	} ,
	watch:{
		to : function(  ) {
			
			let that = this
			if( that.to > 0 ) {
				that.showModal = 'block'
				Axios.get('/im/isonline?user_id=' +that.to + '&api_token=' + window.apiToken  , {
					responseType : 'json'
				}).then(function(response) {
					let data = response.data 
					if( data.errcode === 0 ) {
						if( 'online' == response.data.online ) {
							that.onLine = true ;
						}
					}
				}).catch( function( ret ){
					that.$router.push({
						name : 'login'
					})
				} )

			}
			
		}
	}
}

</script>

<style scoped >
.bottom-fixed {
	position: absolute;
    right: 0;
    left: 0;
    z-index: 10;
    height: 2.2rem;
    padding-right: 0.5rem;
    padding-left: 0.5rem;
    -webkit-backface-visibility: hidden;
    backface-visibility: hidden;
    bottom: 0;
    width: 100%;
    padding: 0;
    table-layout: fixed;
    background: #fff;
}
.next-button {
	border-radius: 1.25rem;
	text-decoration: none;
    text-align: center;
    display: block;
    border-radius: 0.25rem;
    line-height: 2.25rem;
    box-sizing: border-box;
    -webkit-appearance: none;
    -moz-appearance: none;
    -ms-appearance: none;
    appearance: none;
    background: none;
    padding: 0 0.5rem;
    margin: 0;
    height: 2.2rem;
    white-space: nowrap;
    position: relative;
    text-overflow: ellipsis;
    font-size: 0.8rem;
    font-family: inherit;
    cursor: pointer;
    color: #fff;
	background: #734d41;
	border: none;
}
.tip {
	background: #ffdeab;
    margin: .5rem;
    border-radius: .2rem;
    padding: .2rem 1rem;
    color: red;
    font-size: .9rem;
    text-align: center;
    font-weight: 400;
    line-height: 1.8rem;
}
.tip p {
	margin:0;
}
</style>