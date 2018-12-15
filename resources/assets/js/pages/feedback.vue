<template>
<div class="page" id="pageFeedback">
    <header class="bar bar-nav">
        <a class="button button-link button-nav pull-left back" @click="$router.go(-1)">
            <span class="icon icon-left"></span>
            返回
        </a>
        <h1 class="title">意见反馈</h1>
    </header>
    <nav class="bar bar-tab">
        <a class="tab-item cursor" @click="feedback">
            提交
        </a>
    </nav>
    <div class="content" >
        <div class="list-block" style="margin:.5rem 0rem;">
            <ul>
              <!-- Text inputs -->
                <li>
                    <div class="item-content">
                        <div class="item-inner">
                            <div class="item-title label">手机号码</div>
                            <div class="item-input">
                                <input type="text" v-model="mobile" placeholder="您的联系方式">
                            </div>
                        </div>
                    </div>
                </li>

                <li>
                    <div class="item-content">
                        <div class="item-inner">
                            <div class="item-title label">反馈内容</div>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="item-content">
                        <div class="item-inner">
                            <div class="item-input">
                                <textarea placeholder="请输入反馈内容" v-model="content"></textarea>
                            </div>
                        </div>
                    </div>
                </li>
                 <li>
                    <div class="item-content">
                        <div class="item-inner">
                            <div class="item-title label color-gray" id="character-tip">{{content.length}}/200</div>
                            <div class="item-input text-right color-gray" id="error-tip">
                                内容控制在200字左右
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
</template>

<script>
import Axios from 'axios'
export default {
	data:function(){
		return {
			user : {} ,
			mobile : '' ,
			content : '' ,
		}
	},
	created:function(){
		let that = this
		if( window.apiToken ) {
			Axios.get('/member' + 
				'?_token='+  window._Token + 
				'&api_token=' + window.apiToken 
			).then(function(response) {
				//console.log( that );
				let data = response.data 
				console.log( data )
				that.user = data.data.user 
				that.mobile = data.data.user.mobile 
			});
		}
	},
	methods:{
		feedback:function(){
			let that = this 

			if( !that.mobile ) {
				that.$toast('请填写您的联系方式!')
				return false ;
			}
			if( !that.content ) {
				that.$toast("请填写您的建议内容！")
				return false ;
			}
			//检查用户铜板是不是满足发布的最小要求
			var post = {
				'mobile': that.mobile ,
				'content' : that.content ,
				'_token': window._Token
			};
			Axios.post('/feedback' , post ).then(function( ret ){
				let data = ret.data 
				that.$toast( data.msg )
				if( data.errcode === 0) {
					setTimeout( function(){
						that.$router.go(-1)
					} , 1500 )
				}
			})
		}
	}
}
</script>
