<template>
<div class="page" id="pageMember">
    <header class="bar bar-nav">
        <router-link class="icon icon-message pull-right" :to="{name:'messages'}"></router-link>
        <h1 class="title">会员中心</h1>
    </header>

    <foot-bar select="me"></foot-bar>
    <div class="content">
        <div class="card" style="margin:0rem;">
            <div class="card-content">
                <div class="list-block media-list">
                    <ul>
                    <router-link :to="{name:'avatar'}">
                        <li class="item-content">
                            <div class="item-media img-round" style="width: 25%;">
                                <img :src="getAvatar( user.avatar )" style="width:3rem;">
                            </div>
                            <div class="item-inner right-arrow">
                                <div class="item-title-row">
                                    <div class="item-title">{{ user.nickname ? user.nickname : '狗运'}}</div>
                                </div>
                                <!--
                                <div class="item-subtitle">骨头:{{user.cash_recharge}}</div>
                                -->
                                <div class="item-subtitle">信用:
{{calcCredit( user.not_pay_big_small ,user.lose_big_small , user.not_pay_big_small_cash , user.lose_big_small_cash ) }}
                                </div>
                                <div class="item-subtitle">狗粮:{{user.cash_reward }}</div>
                            </div>
                        </li>
                    </router-link>
                    </ul>
                </div>
            </div>
            <!--
            <div class="card-footer no-border">
                <router-link tag="div" class="link" :to="{name:'microblog-myspace'}">
                    <span class="num">{{microblog_count}}</span>
                    <span class="tip">动态</span>
                </router-link>
                <div class="link">
                    <span class="num">0</span>
                    <span class="tip">关注</span>
                </div>
                <div class="link">
                    <span class="num">0</span>
                    <span class="tip">粉丝</span>
                </div>
                

            </div>
            -->
        </div>

        <div class="card" style="margin:.5rem 0rem;">
            <div class="card-content">
              <div class="list-block">
                <ul>
                    <li>
                        <router-link :to="{name:'member-baseinfo'}" class="item-link item-content">
                            <div class="item-media"><i class="icon icon-f7"></i></div>
                            <div class="item-inner">
                                <div class="item-title">基本信息</div>
                            </div>
                        </router-link>
                    </li>
                    <li>
                        <router-link :to="{name:'member-modpwd'}" class="item-link item-content">
                            <div class="item-media"><i class="icon icon-f7"></i></div>
                            <div class="item-inner">
                                <div class="item-title">修改密码</div>
                            </div>
                        </router-link>
                    </li>
                    <li v-if="user.invite_code">
                        <router-link :to="{name:'member-invited'}" class="item-link item-content">
                            <div class="item-media"><i class="icon icon-f7"></i></div>
                            <div class="item-inner">
                                <div class="item-title">推广码</div>
                                <div class="item-right button button-round button-fill button-success">{{user.invite_code}}</div>
                            </div>
                        </router-link>
                    </li>
                    <li>
                        <router-link :to="{name:'adv-mine'}" class="item-link item-content">
                            <div class="item-media"><i class="icon icon-f7"></i></div>
                            <div class="item-inner">
                                <div class="item-title">我发布的广告</div>
                            </div>
                        </router-link>
                    </li>
                    <li>
                        <router-link :to="{name:'microblog-myspace'}" class="item-link item-content">
                            <div class="item-media"><i class="icon icon-f7"></i></div>
                            <div class="item-inner">
                                <div class="item-title">我发布的资讯</div>
                                <div class="item-right button button-round button-fill button-success">{{microblog_count}}</div>
                            </div>
                        </router-link>
                    </li>
                    <!--
                    <li>
                        <router-link :to="{name:'guess-mine'}" class="item-link item-content">
                            <div class="item-media"><i class="icon icon-f7"></i></div>
                            <div class="item-inner">
                                <div class="item-title">我的押大小</div>
                            </div>
                        </router-link>
                    </li>
                    -->
                </ul>
              </div>
            </div>
        </div>

        <div class="card" style="margin:.5rem 0rem;">
            <div class="card-content">
              <div class="list-block">
                <ul>
                    <li>
                        <router-link :to="{name:'member-chargelog'}" class="item-link item-content">
                            <div class="item-media"><i class="icon icon-f7"></i></div>
                            <div class="item-inner">
                                <div class="item-title">充值记录</div>
                            </div>
                        </router-link>
                    </li>
                    <li>
                        <router-link :to="{name:'member-cashlog'}" class="item-link item-content">
                            <div class="item-media"><i class="icon icon-f7"></i></div>
                            <div class="item-inner">
                                <div class="item-title">我的收入</div>
                            </div>
                        </router-link>
                    </li>

                    <li>
                        <router-link :to="{name:'member-withdraw'}" class="item-link item-content">
                            <div class="item-media"><i class="icon icon-f7"></i></div>
                            <div class="item-inner">
                                <div class="item-title">我的提现</div>
                            </div>
                        </router-link>
                    </li>
                </ul>
              </div>
            </div>
        </div>

        <div class="card" style="margin:.5rem 0rem;">
            <div class="card-content">
              <div class="list-block">
                <ul>
                    <li>
                        <router-link :to="{name:'help'}" class="item-link item-content">
                            <div class="item-media"><i class="icon icon-f7"></i></div>
                            <div class="item-inner">
                                <div class="item-title">帮助说明</div>
                            </div>
                        </router-link>
                    </li>

                    <li>
                        <router-link :to="{name:'feedback'}" class="item-link item-content">
                            <div class="item-media"><i class="icon icon-f7"></i></div>
                            <div class="item-inner">
                                <div class="item-title">意见反馈</div>
                            </div>
                        </router-link>
                    </li>

                    <li>
                        <router-link :to="{name:'aboutus'}" class="item-link item-content">
                            <div class="item-media"><i class="icon icon-f7"></i></div>
                            <div class="item-inner">
                                <div class="item-title">关于我们</div>
                            </div>
                        </router-link>
                    </li>
                </ul>
              </div>
            </div>
        </div>
        <div class="card">
            <div class="list-block" style="padding:.5rem;">
                <a href="/logout" class="button button-danger button-fill button-big">退出登录</a>
            </div>
        </div>
    </div>

</div>
</template>

<script>
import footBar from '../../components/footNav'
import Axios from 'axios'
export default {
	data:function(){
		return {
			user : {} ,
			unread : 0 ,
			microblog_count : 0 ,
		}
	} ,

	created:function(){
		var that = this ;
			Axios.get('/member' + 
				'?_token='+  window._Token + 
				'&api_token=' + window.apiToken 
			).then(function(response) {
				//console.log( that );
				let data = response.data 
				console.log( data )
				that.user = data.data.user 
				that.unread = data.data.msg_count
				that.microblog_count = data.data.microblog_count 
			});
	},
	methods:{
		getAvatar : function( v ){
			v = v ? v : '/images/logo.png'
            return v + '?t=' + ( new Date()).getTime()
		} ,
        calcCredit:function( a , b , c , d ) {
            b = b > 0 ? b : 1 ;
            d = d > 0 ? d : 1 ;
            a = a > 0 ? a : 0  ;
            c = c > 0 ? c : 0 ;
            
            return (5 - ( 2 * a / b + 3 * c / d ).toFixed( 2 ) ).toFixed( 2 );
            
        },
	},
	components :{
		'foot-bar' : footBar
	}
}	

</script>

<style>

</style>
