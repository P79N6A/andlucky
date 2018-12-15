<template>
<div class="page page-current" id="pageMyPosts">
    <header class="bar bar-nav">
        <a class="button button-link button-nav pull-left" @click="$router.go(-1)">
            <span class="icon icon-left"></span>
            返回
        </a>
        <h1 class="title">我发布的</h1>
    </header>
    <div class="content" data-distance="50">
        <!-- 这里是页面内容区 -->
        <!--
        <div class="buttons-tab" style="margin-top:.5rem;">
            <a class="tab-link button" :class="{active:type == 'all'}" @click="changeRange('all')">全部</a>
            <a class="tab-link button" :class="{active:type == 'haspay'}" @click="changeRange('haspay')">已支付</a>
            <a class="tab-link button" :class="{active:type == 'notpay'}" @click="changeRange('notpay')">未支付</a>
        </div>
    	-->
        <div class="content-block"
         style="padding:0rem;margin-top:0rem;">
            <div class="card facebook-card cursor" v-for="item in advs" >
            	<router-link :to="{name:item.pay_status == 0 ? 'adv-edit' : 'advshow' , params :{id:item.id}}">
				    <div class="card-header">
				        <div class="">
				                <span class="color-red font-size-20 font-bold"> {{item.click_price}}&nbsp;</span>
				                &nbsp;{{item.title}}
				                <span class="pay_status"
				                	:class="{haspay : item.pay_status , notpay : !item.pay_status}"
				                	>
				                {{ item.pay_status ? "已支付" : "未支付"}}
				                </span>
				        </div>
				    </div>
				    <div class="card-content">
				        <div class="card-content-inner font-size-12">
				            价格:(
				            <span class="color-red font-size-12"> {{item.click_price}}&nbsp;</span>/<span class="color-red font-size-12"> {{item.total_price}}&nbsp;</span>)<br/>
				            点击:(<span class="color-red font-size-12"> {{item.has_click_times}}&nbsp;</span>/<span class="color-red font-size-12"> {{item.click_times}}&nbsp;</span>)<br/>
				            赞/踩:(<span class="color-red font-size-12"> {{item.up_times}}&nbsp;</span>/<span class="color-red font-size-12"> {{item.down_times}}&nbsp;</span>)
				        </div>
				    </div>
				</router-link>
			    <div class="card-footer">
			        <div class="card-operation">
			            <div class="created_at">
			                <a href="#" class="link cursor">{{ datef( item.created_at ) }}</a>
			            </div>
			            <div class="operation" v-if="item.pay_status == 0 ">
			                <router-link :to="{name:'adv-edit' , params :{ id : item.id }}" class="button button-fill">编辑</router-link>
			                <a class="button button-fill" @click="pay( item.id )">立即支付</a>
			            </div>
			            <div class="operation" v-else>
			                <router-link :to="{name:'advshow' , params :{ id : item.id }}" class="button button-fill">查看</router-link>
			            </div>
			        </div>
			    </div>
			</div>
			<infinite-loading @infinite="loadMore" ref="infiniteLoading">
				<span slot="no-more">没有更多数据了</span>
				<span slot="no-result">没有更多数据了</span>
			</infinite-loading> 
        </div>
        
    </div>
</div>	
</template>
<script>
import Axios from "axios"
import InfiniteLoading from 'vue-infinite-loading' 
import {formatDate} from '../../utils'
export default {
	data:function(){
		return {
			advs : [] ,
			page : 1 ,
			type : 'all' ,
		}
	} ,
	methods:{
		loadMore : function( $state ){
			let that = this 
			Axios.get('/adv/mine' +
				'?_token=' + window._Token  + 
				'&status=' + that.type + 
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
						that.advs.push( data.data.data[i] );	
					}
					
					if( ret.data.data.last_page >= that.page ) {
						$state.complete();
					} else {
						that.page++ ;
					}
				}
				
			} , function( ret ){
				console.log( ret );
			});
		} ,
		shortContent : function( v ){
			 v = v.replace(/<\/?.+?>/g,"")
			 v = v.replace(/ /g,"")
			 return v.length > 200 ? v.substring( 0 , 200 ) + '....' : v 
		},
		datef:function( v ){
			v = v.replace(/\-/g, "/")
			return formatDate( new Date( v ) , 'yyyy-MM-dd') 
		} ,
		changeRange:function(  v ){
			this.type = v 
			this.page = 1 
			this.advs = [] 
			this.$nextTick(() => {
		        this.$refs.infiniteLoading.$emit('$InfiniteLoading:reset');
		    });
		} ,
		pay:function( id ) {
			let that = this
			Axios.get('/adv/' + id + '/pay' + 
				'?_token=' + window._Token  + 
				'&api_token=' + window.apiToken
			, {
				responseType : 'json'
			}).then(function( ret ){
				let data = ret.data 
				that.$toast( data.msg )
				if( data.errcode === 0 ) {
					//邀请成功 回到发送信息的页面 支付完成 更改这个的支付信息
					for( var i in that.advs ) {
						if( that.advs[i].id == id ) {
							that.advs[i].pay_status = 1 
							break 
						}
					}
				} else if( data.errcode === 30000 ) {
					setTimeout( function(){
                        that.$router.push({
                            name : 'charge' ,
                            params:{

                            }
                        })    
                    } , 1500 )	
				}
				
			} , function( ret ){
				console.log( ret );
			});
		}
	} ,
	components :{
		InfiniteLoading
	}
}

</script>