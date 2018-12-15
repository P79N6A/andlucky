<template>
<div class="page" id="pageMember">
    <header class="bar bar-nav">
        <a class="button button-link button-nav pull-left" @click="$router.go(-1)">
            <span class="icon icon-left"></span>
            返回
        </a>
        <h1 class="title">提现记录</h1>
        <a class="button button-link button-nav pull-right" @click="showWithdrawForm">
            <span class="icon icon-plus"></span>
            提现
        </a>
    </header>
    <div class="content">
        <div class="card" style="margin:.5rem 0rem;">
            <div class="card-content">
              <div class="list-block">
                <ul>
                    <li v-for="item in lists">
                        <div class="item-content">
                            <div class="item-media">
                                <em v-if="item.status == 0" class='wait'>
                                    待审核
                                </em>
                                <em v-if="item.status == 1" class="success">
                                    已完成
                                </em>
                                <em v-if="item.status == 2" class="reject">
                                    审核失败
                                </em>

                            {{item.cash}}</div>
                            <div class="item-inner">
                                <div class="item-title">{{item.target_desc}}</div>
                                <div class="item-after">{{item.created_at.substring(0,10)}}</div>
                            </div>
                        </div>
                    </li>
                    <infinite-loading @infinite="loadMore" ref="infiniteLoading">
                        <span slot="no-more">没有更多数据了</span>
                        <span slot="no-result">没有更多数据了</span>
                    </infinite-loading> 
                </ul>
              </div>
            </div>
        </div>
    </div>
    <div class="modal-overlay modal-overlay-visible" :style="{display: showModal }" @click="hideModal">
        <div class="modal modal-in" style="display:block;top:5rem;background:#fff;" @click.stop.self >
            <div class="content-block tip" >
                <p >
                    狗粮汇率为:1
                </p>
                <p >
                    您当前狗粮为<em>{{cash}}</em>点，最大可提现部分为<em>{{max}}</em>点
                </p>
            </div>
            <div class="content-block list-block">
                <ul>
                    <!-- Text inputs -->
                    <li>
                        <div class="item-content">
                            <div class="item-inner">
                                <div class="item-input">
                                    <input type="text" v-model.number="promise" style="text-align:center;" placeholder="请输入您想提取的狗粮">
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="content-block">
                <div class="button button-danger button-fill button-big" @click="withdraw()">申请提取</div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
import InfiniteLoading from 'vue-infinite-loading'
import {formatDate} from '../../utils'
import Axios from 'axios'
export default {
	data:function(){
        return {
            lists : [] ,
            page : 1 ,
            showModal:'none' ,
            promise : '' ,
            min: 0 ,
            max: 0 ,
            cash: 0 ,
            alipay : '' ,

        }
    } ,

	created:function(){
	},
	methods:{
        showWithdrawForm : function(){
            let that = this
            that.showModal = 'block'
            Axios.get('/member/withdrawconf' +
                '?_token=' + window._Token  + 
                '&api_token=' + window.apiToken 
            , {
                responseType : 'json'
            }).then(function( ret ){
                
                let data = ret.data 
                console.log( data )
                if( data.errcode === 0 ) {
                    //邀请成功 回到发送信息的页面
                    that.min = data.min 
                    that.max = Math.floor( data.user.cash_reward * data.rate / 100 )
                    that.cash = data.user.cash_reward
                    that.alipay = data.user.alipay 
                }
                
            } , function( ret ){
                console.log( ret );
            });
        } ,
        hideModal:function(){
            let that = this
            that.promise = ''
            that.showModal = 'none' 
        },
        withdraw:function(){
            let that = this
            that.promise = parseInt( that.promise ) 
            that.promise = isNaN( that.promise ) ? 0 : that.promise 
            if( that.promise == 0 ) {
                return false 
            }
            if( that.promise > that.max ) {
                that.$toast("您超过了最大提取额度")
                return false 
            }
            /**
            if( !that.alipay ) {
                that.$toast("请填写提取收款账号")
                return false 
            }
            **/
            Axios.post('/member/withdraw/apply' , {
                '_token' : window._Token  ,
                'api_token' : window.apiToken ,
                'cash' : that.promise
            }
            , {
                responseType : 'json'
            }).then(function( ret ){
                
                let data = ret.data 
                that.$toast( data.msg )
                if( data.errcode === 0 ) {
                    //邀请成功 回到发送信息的页面
                    that.hideModal()
                }
                
            } , function( ret ){
                console.log( ret );
            });
        },
        loadMore : function( $state ){
            let that = this 
            Axios.get('/member/withdraw' +
                '?_token=' + window._Token  + 
                '&type=' + that.type + 
                '&api_token=' + window.apiToken + 
                '&page=' + that.page 
            , {
                responseType : 'json'
            }).then(function( ret ){
                $state.loaded();
                let data = ret.data 
                if( data.errcode === 0 ) {
                    //邀请成功 回到发送信息的页面

                    for( var i in data.data.data ) {
                        that.lists.push( data.data.data[i] );   
                    }
                    
                    if( data.data.last_page <= that.page ) {
                        $state.complete();
                    } else {
                        that.page++ ;
                    }
                }
                
            } , function( ret ){
                console.log( ret );
            });
        } ,
		getAvatar : function( v ){
			return v ? v : '/images/logo.png'
		}
	},
	components :{
        InfiniteLoading
    }
}	

</script>

<style scoped>
    .item-media {
        width: 5rem;
        color:red;
    }
    .item-media em {
        height: 1rem;
        min-width: 3.8rem;
        padding: 0 .5rem;
        font-size: .6rem;
        line-height: 1rem;
        color: white;
        vertical-align: top;
        border-radius: .5rem;
        margin-right: .5rem;
    }
    .item-media em.wait {
        background-color: red ;
    }
    .item-media em.success {
        background-color: green;
    }
    .item-media em.reject {
        background-color: gray;
    }
</style>