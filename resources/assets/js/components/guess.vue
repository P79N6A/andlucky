<template id="big_small">
	<div class="modal-overlay modal-overlay-visible" v-if="show" @click="hideModal">
        <div class="modal modal-in" style="display:block;top:5rem;background:#fff;" @click.stop.self >
	        <div class="content-block tip">
	            <p >
					当前赔率为{{rate( guess.rate ) }}
			    </p>
	        </div>
	        <div class="content-block list-block">
			    <ul>
					<!-- Text inputs -->
					<li>
			        	<div class="item-content">
			        		<div class="item-inner">
			            		<div class="item-input">
			            			<input type="text" min="1" v-model.number="money" style="text-align:center;" :placeholder="tipHolder()">
			            		</div>
			          		</div>
			        	</div>
			    	</li>
			    	<li class="topic">
	                    
                        <div class="buttons-tab">
						    <a class="tab-link button" :class="{active: seed == 'max' }" @click="changeSeed( 'max' )">大</a>
						    <a class="tab-link button" v-show="guess.rate == 2.97" :class="{active: seed == 'mid' }" @click="changeSeed( 'mid' )">中</a>
						    <a class="tab-link button" :class="{active: seed == 'min' }" @click="changeSeed( 'min' )">小</a>
						</div>
	                </li>
			    </ul>
			</div>
			<div class="content-block">
				<div class="button button-danger button-fill button-big" v-on:click="next()">参加游戏</div>
			</div>
    	</div>
    </div>
</template>

<script>
import Axios from "axios" ;

export default {
	props:[ 'show' , 'guess' ] ,
	data : function(){
		return {
			desc : '' ,
			promise: '' ,
			max : 0 ,
			money : "" ,
			seed : 'max' ,
			onLine : true ,
			showModal : 'none'
		}
	} ,
	mounted : function(){
		
	 },
	methods : {
		tipHolder : function(){
			return "您最多可以投入狗"+ Math.floor( this.guess.cash / this.guess.rate  - this.guess.occupy_cash ) +"粮"
		},
		back : function(){
			this.$router.back();
		} ,
		changeSeed : function( sed ){
			this.seed = sed 
		},
		next : function(){
			var that = this ;
			var left = that.guess.cash / that.guess.rate - that.guess.occupy_cash 
			left = Math.floor( left )
			console.log( that );
			if( !that.money ) {
				that.$toast("请填写狗粮" , 1500 , {
					'top' : '50%'
				}) ;
				return false ;
			}
			if( 1 > that.money ) {
				that.$toast("狗粮最小为1" , 1500 , {
					'top' : '50%'
				}) ;
				return false ;	
			}
			if( left < that.money ) {
				that.$toast("狗粮最大为" + left , 1500 , {
					'top' : '50%'
				}) ;
				return false ;	
			}
			Axios.post('/guess/take/' + that.guess.id , {
				'money' : that.money ,
				'seed' : that.seed ,
				'_token' : window._Token ,
				'api_token' : window.apiToken 
			} , {
				responseType : 'json'
			}).then(function( ret ){
				console.log( ret )
				that.$toast( ret.data.msg , 5500 );
				if( ret.data.errcode === 0 ) {
					//邀请成功 回到发送信息的页面
					that.hideModal()
					setTimeout( function(){
						that.$router.push({
							name : 'guess-show' , 
							params :{
								id : that.guess.id 
							}
						})
					} , 1500 );
				} else if( ret.data.errcode == 10006 ) {
					setTimeout( function(){
						that.$router.push({
                            name : 'charge' ,
                            params:{

                            }
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
			this.$emit('closeModal')
		} ,
		rate : function( v ){
			return v ? v.toFixed( 2 ) : 0 ;
		}
	} ,
	mounted : function(){
		console.log( this.$route )
	} ,
	watch:{
		id : function(  ) {
			
			let that = this
			if( that.id > 0 ) {
				that.showModal = 'block'
				
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