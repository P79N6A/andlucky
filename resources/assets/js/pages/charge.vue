<template>
<div class="page" id="pageCharge">
    <header class="bar bar-nav">
        <a class="button button-link button-nav pull-left" @click="$router.go(-1)">
            <span class="icon icon-left"></span>
            返回
        </a>
        <h1 class="title">充值</h1>
    </header>
    <nav class="bar bar-tab">
        <a class="tab-item cursor" @click="charge">
            立即充值
        </a>
    </nav>
    <div class="content infinite-scroll infinite-scroll-bottom" data-distance="50" >
        <div class="content-block" >
            <h4>充值说明</h4>
            <p v-html="charge_desc">

            </p>
        </div>
        <div class="list-block" style="margin:.5rem 0rem;">
            <form action="/charge" method="POST" id="charge-form">
            <ul>
              <!-- Text inputs -->
                <li>
                    <div class="item-content">
                        <div class="item-inner">
                            <div class="item-title label">充值铜板</div>
                            <div class="item-input">
                                <input type="text" v-model="cash" @change="fixDecimal" placeholder="请输入您要充值的铜板">
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
            </form>
        </div>
    </div>
</div>
</template>

<script >
import Axios from 'axios'
export default {
	data:function(){
		return {
			charge_desc : '' ,
			cash : 0 
		}
	} ,
	created:function(){
		let that = this
		Axios.get('/charge' +
			'?api_token=' + window.apiToken 
		 , {
			responseType : 'json'
		}).then(function(response) {
			console.log( response );
			let data = response.data 
			if( data.errcode === 0 ) {
				that.charge_desc = data.desc 
				
			} else {
				//接口数据获取错误
				that.$router.go(-1)
			}
			
		});
	} ,
	methods:{
		fixDecimal:function(){
			let that = this
			let cash = parseFloat( that.cash )
			cash = isNaN( cash ) ? 0 : cash 
			that.cash = cash.toFixed( 2 )
		} ,
		charge:function(){
			let that = this
			let cash = parseFloat( that.cash )
			cash = isNaN( cash ) ? 0 : cash 
			that.cash = cash.toFixed( 2 )
			if( that.cash == 0 ) {
				return 
			}
			Axios.post('/charge' , {
				'api_token' : window.apiToken  ,
				'_token' : window._Token ,
				'cash' : that.cash 
			} , {
				responseType : 'json'
			}).then(function(response) {
				console.log( response );
				let data = response.data 
				if( data.errcode === 0 ) {
					location.href = data.url
					
				} else {
					//接口数据获取错误
					that.$router.go(-1)
				}
				
			});
		}
	}
}
</script>
