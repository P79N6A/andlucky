<!-- 查看详情 登录的用户查看  自己查看   参与者查看-->
<template>
<div class="page" id="pageCreateMicroblog">
    <header class="bar bar-nav">
    	<a @click="back()" class="button button-link button-nav pull-left back" data-transition='slide-out'>
            <span class="icon icon-left"></span>
            返回
        </a>
        <h1 class="title">押大小</h1>
    </header>
    <div class="content">
        <div class="card facebook-card">
        	<div class="card-header no-border" style="padding:0rem;">
        		<div class="list-block media-list">
        			<ul>
        				<li class="item-content">
        					<div class="item-media img-round">
        						<img :src="avatar( guess.user ? guess.user.avatar : '' )" width="44">
        					</div>
        					<div class="item-inner">
        						<div class="item-title-row">
        							<div class="item-title">{{banker.nickname || '狗运'}}</div>
        							<div class="pull-right">
                                        <span v-html="status( guess.status )"></span>
        							</div>
        						</div>
        						<div class="item-title-row" v-if="guess.user_id == user.id || win()">
        			           		<div class="item-title">{{banker.mobile}}</div>
        			            </div>
        						<div class="item-title-row">
        						信用：{{calcCredit( banker.not_pay_big_small ,banker.lose_big_small , banker.not_pay_big_small_cash , banker.lose_big_small_cash ) }}
        										&nbsp;,&nbsp;
        						{{banker.not_pay_big_small}}/{{banker.lose_big_small}} 
        						&nbsp; , &nbsp;{{banker.not_pay_big_small_cash}}/{{banker.lose_big_small_cash}}
        						
        						</div>
        					</div>
        				</li>
        	        </ul>
            	</div>
        			
        			
        	</div>

            <div class="card-content">
                <div class="list-block">
                    <ul>
                        <li class="item-content" v-if="guess.user_id != user.id && !win()">
                            <div class="item-inner">
                                <div class="item-title font-bold">支付宝</div>
                                <div class="item-after">{{banker.alipay_account}}</div>
                            </div>
                        </li>
                        <li class="item-content">
                            <div class="item-inner">
                                <div class="item-title">奖池</div>
                                <div class="item-after">{{guess.cash}}</div>
                            </div>
                        </li>
                        <li class="item-content" v-if="guess.user_id == user.id">
                            <div class="item-inner">
                                <div class="item-title">赢得</div>
                                <div class="item-after">{{ rate( guess.win_cash ) }}</div>
                            </div>
                        </li>
                        <li class="item-content" v-if="guess.user_id == user.id">
                            <div class="item-inner">
                                <div class="item-title">输掉</div>
                                <div class="item-after">{{ rate( guess.lose_cash ) }}</div>
                            </div>
                        </li>
                        <li class="item-content" v-if="guess.user_id == user.id || guess.status == 1">
                            <div class="item-inner">
                                <div class="item-title">赔率</div>
                                <div class="item-after" v-html="rate( guess.rate )"></div>
                            </div>
                        </li>
                        <li class="item-content" v-if="guess.user_id == user.id || guess.status == 1">
                            <div class="item-inner">
                                <div class="item-title">种子</div>
                                <div class="item-after" v-html="seed( guess.seed )"></div>
                            </div>
                        </li>
                        
                    </ul>
                </div>

            </div>
        </div>

<!-- 我是庄家 -->
<div v-if="guess.user_id == user.id " style="margin-top:.75rem;">
    <div class="card" v-for="(item , index ) in guess.join">
        <div class="card-content">
            <div class="list-block media-list">
                <ul>
                    <li class="item-content">
                        <div class="item-media">
                          <img :src="avatar( item.user.avatar )" class="img-round"  width="44">
                        </div>
                        <div class="item-inner">
                            <div class="item-title-row">
                                <div class="item-title">{{ item.user.nickname || '狗运'}}</div>
                            </div>
                            <div class="item-subtitle">
                                信用：{{calcCredit( item.user.not_pay_big_small ,item.user.lose_big_small , item.user.not_pay_big_small_cash , item.user.lose_big_small_cash ) }}
                                &nbsp;,&nbsp;
                                {{item.user.not_pay_big_small}}/{{item.user.lose_big_small}}&nbsp; , &nbsp;{{item.user.not_pay_big_small_cash}}/{{item.user.lose_big_small_cash}}
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="list-block">
                <ul>
                    <!-- 玩家胜 庄家要给钱玩家 -->
                    <li class="item-content" v-if="item.is_win == 1 ">
                        <div class="item-inner">
                            <div class="item-title font-bold">支付宝</div>
                            <div class="item-after">{{ item.user.alipay_account }}</div>
                        </div>
                    </li>
                    <li class="item-content" v-if="item.is_win == 2 ">
                        <div class="item-inner">
                            <div class="item-title">手机号码</div>
                            <div class="item-after">{{ item.user.mobile }}</div>
                        </div>
                    </li>
                    <li class="item-content">
                        <div class="item-inner">
                            <div class="item-title">投注</div>
                            <div class="item-after">{{item.cash}}</div>
                        </div>
                    </li>
                    <li class="item-content">
                        <div class="item-inner">
                            <div class="item-title">押注</div>
                            <div class="item-after" v-html="seed( item.seed )"></div>
                        </div>
                    </li>
                    <li class="item-content" v-if="item.is_win > 0">
                        <div class="item-inner">
                            <div class="item-title">结果</div>
                            <div class="item-after">{{ item.is_win == 1 ? '玩家胜' : '庄家胜' }}</div>
                        </div>
                    </li>
                    <li class="item-content" v-if="item.status > 0">
                        <div class="item-inner">
                            <div class="item-title">状态</div>
                            <div class="item-after" v-if="item.status ==1">未退款</div>
                            <div class="item-after" v-if="item.status ==2">赢家已退狗粮</div>
                            <div class="item-after" v-if="item.status ==3">系统已退狗粮</div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <div class="card-footer">
            <span>{{item.created_at}}</span>
            <span class="button button-fill button-primary" v-if="item.is_win == 2 && item.status == 1" @click="reback( item.id )">退款<em >{{ rate( item.cash * guess.rate ) }}</em></span>
        </div>
    </div> 

</div>

<!-- 我是玩家 -->
<div v-else style="margin-top:.75rem;">
	<div class="card" v-for="(item , index ) in guess.join" v-if="user.id == item.user_id">
        <div class="card-content">
            <div class="list-block media-list">
                <ul>
                    <li class="item-content">
                        <div class="item-media">
                          <img :src="avatar( item.user.avatar )" class="img-round" width="44">
                        </div>
                        <div class="item-inner">
                            <div class="item-title-row">
                                <div class="item-title">{{ item.user.nickname || '狗运'}}</div>
                            </div>
                            <!--
                            <div class="item-subtitle">{{ item.user.alipay_account }}</div>
                            -->
                            <div class="item-subtitle">
                                信用：{{calcCredit( item.user.not_pay_big_small ,item.user.lose_big_small , item.user.not_pay_big_small_cash , item.user.lose_big_small_cash ) }}
                                &nbsp;,&nbsp;
                                {{item.user.not_pay_big_small}}/{{item.user.lose_big_small}}&nbsp; , &nbsp;{{item.user.not_pay_big_small_cash}}/{{item.user.lose_big_small_cash}}
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="list-block">
                <ul>
                    <li class="item-content">
                        <div class="item-inner">
                            <div class="item-title">投注</div>
                            <div class="item-after">{{item.cash}}</div>
                        </div>
                    </li>
                    <li class="item-content">
                        <div class="item-inner">
                            <div class="item-title">押注</div>
                            <div class="item-after" v-html="seed( item.seed )"></div>
                        </div>
                    </li>
                    <li class="item-content" v-if="item.is_win > 0">
                        <div class="item-inner">
                            <div class="item-title">结果</div>
                            <div class="item-after">{{ item.is_win == 1 ? '玩家胜' : '庄家胜' }}</div>
                        </div>
                    </li>
                    <li class="item-content" v-if="item.status > 0">
                        <div class="item-inner">
                            <div class="item-title">状态</div>
                            <div class="item-after" v-if="item.status ==1">未退款</div>
                            <div class="item-after" v-if="item.status ==2">赢家已退狗粮</div>
                            <div class="item-after" v-if="item.status ==3">系统已退狗粮</div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <div class="card-footer">
          <span>{{item.created_at}}</span>
          <span class="button button-fill button-primary" v-if="item.is_win == 1 && item.status == 1" @click="reback( item.id )">退款<em >{{ rate (item.cash * guess.rate  ) }}</em></span>
        </div>
      </div> 

    </div>

</div>


    </div>
</div>

</template>


<script >
import Axios from 'axios'

export default {
    data:function(){
        return {
            id : 0 ,
            guess : {} , 
            banker : {} ,
            user : {} ,
            joiners : [] 
        }
    },
    created:function(){
        let that = this
        that.id = that.$route.params.id 
        Axios.get( '/guess/' + that.id ).then( function( res ){
        	let data = res.data 
        	if( data.errcode === 0 ) {
        		that.guess = data.guess 
        		that.banker = data.guess.user 
        		that.user = data.user
        	}
        });
    } ,
    methods:{
        avatar : function( src ){
            return src ? src : '/images/logo.png'
        } ,
        status : function( v ) {
            if ( v == 0 ) {
                return "未开台" ;
            }
            if( v == 1 ) {
                return "已开台" ;
            }
            if( 2 == v ) {
                return "已过期" ;
            }
        } ,
        seed : function( v ) {
            let s = {'max' : '大' , 'mid' : '中' , 'min' : '小' } ;
            return s[ v ] ;
        } ,
        rate : function( v ){
            v = parseFloat( v , 2 );
            if( isNaN( v ) ) {
                v = 0 ;
            }
            return v ? v.toFixed( 2 ) : 0 ;
        },
        calcCredit:function( a , b , c , d ) {
			b = b > 0 ? b : 1 ;
            d = d > 0 ? d : 1 ;
            a = a > 0 ? a : 0  ;
            c = c > 0 ? c : 0 ;
            
            return (5 - ( 2 * a / b + 3 * c / d ).toFixed( 2 ) ).toFixed( 2 );
		},
        reback:function( id ){
            let that = this
            Axios.post( '/guess/reback/' + id , {
                _token : window._Token 
            }).then( function( res ){
                let data = res.data 
                that.$toast( data.msg )
                if( data.errcode === 0 ) {
                    that.guess = data.guess 
                    that.banker = data.guess.user 
                }
            });
        } ,
        //玩家是否赢了
        win:function(){
            let that = this
            if( that.guess.user_id == that.user.id ) {
                return true ;
            }
            if( typeof that.guess.join != 'undefined' ) {
                for( var i in that.guess.join ) {
                    //找到玩家的信息
                    if( that.user.id == that.guess.join[i].user_id ) {
                        //如果玩家赢了  则显示输家的电话号码
                        if( that.guess.join[i].is_win == 1 ) {
                            return true ;
                        }
                    }
                }
            }
            return false ;
        } ,
        back:function(){
            if( this.$route.query.from == 'form' ) {
                this.$router.push({name:'home' , query :{ cate : 'guess' }})
            } else {
                this.$router.go(-1)    
            }
            
        }
    },
    watch:{
        
    }
};
</script>

<style scoped>
.page {
    background:none;
}
.topic {
    padding:.3rem 0rem;
}
.topic .radio-item {
    border:#f3f3f3 solid 1px;
    border-radius: 3px;
    padding:.5rem;
}
.topic .radio-item.active {
    color:#fff;
    background-color: #724c41;
}
ul.image {
    overflow: hidden;
}
ul.image li {
    box-sizing: border-box;
    float: left;
    position: relative;
    background: #f5f5f594;
    width: 29.333333333333332%;
    margin-left:0;
    margin-top:.2rem;
    display: block;
    height:5rem;
    text-align: center;
    background-repeat: no-repeat;
    background-size: cover ;
    background-position: center 
}
ul.image li i {
    font-size: 3rem;
    line-height: 5rem;
    text-align: center;
    color:#b9b9b9;
}
.icon-xinzeng:before {
    width: 100%;
    margin:0 auto;
    display: block;
}
ul.image li i.del {
    position: absolute;
    right: 0px;
    top: 0px;
    font-size: 1.25rem;
    line-height: 1.25rem;
    cursor:pointer;
}
.upload-hander {
    width: 100%;
    height:100%;
    font-size: 3rem;
    line-height: 5rem;
    text-align: center;
    color:#b9b9b9;
}
.font-bold {
    font-weight: bold ;
    color:red;
}
</style>
