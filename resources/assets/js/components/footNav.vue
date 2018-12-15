<template>
<div class="bar-footer">	
    <nav class="bar bar-tab">
        <router-link class="tab-item" :to="{ name : 'microblog'}" :class="{active: 'channel' == select}">
          <span class="icon icon icon-menu"></span>
          <span class="tab-label">资讯</span>
        </router-link>
        <router-link class="tab-item" :class="{active: 'home' == select}" :to="{name:'home'}">
          <span class="icon iconfont icon-4"></span>
          <span class="tab-label">小游戏</span>
        </router-link>
        <a class="tab-item" :class="{active: 'pub' == select}" @click="showPub">
          <span class="icon iconfont icon-xinzeng-"></span>
          <span class="tab-label">发布</span>
        </a>
        <router-link 
            class="tab-item" 
            :class="{active: 'chat' == select}"
            :to="{name:'im-friends'}"

        >
          <span class="icon iconfont icon-conversation_icon"></span>
          <span class="tab-label">好友</span>
        </router-link>

        <router-link class="tab-item" :class="{active: 'me' == select}" :to="{name:'member'}">
          <span class="icon iconfont icon-home1"></span>
          <span class="tab-label">我</span>
        </router-link>
        
    </nav>
    <div class="modal-overlay" :class="{'modal-overlay-visible': showOverlay }" v-if="pupShow"></div>
    <div class="actions-modal" :class="{'modal-in':pupShow , 'modal-out': !pupShow , }" v-if="pupShow">
        <div class="actions-modal-group">
            <span class="actions-modal-label">请选择</span>
            <span class="actions-modal-button" @click="hidePub();$router.push({ name : 'microblog-form' })">资讯</span>
            <span class="actions-modal-button actions-modal-button-bold" @click="hidePub();$router.push({ name : 'adv-form' })">广告</span>
            
        </div>
        <div class="actions-modal-group">
            <span class="actions-modal-button bg-danger" @click="hidePub">取消</span>
        </div>
    </div>
</div>
</template>

<script >
export default {
	props:[ 'select'] ,
    data:function(){
        return {
            channelShow:false ,
            showOverlay : false ,
            pupShow : false ,
        }
    },
    created:function(){
        
    },
    methods:{
        showPub : function(){
            this.pupShow = true ;
            this.showOverlay = true ; 
        } ,
        hidePub : function(){
            this.pupShow = false ;
            this.showOverlay = false ; 
        }
    }
}	

</script>


<style scoped>
	.bar-footer {
        position: absolute;
        right: 0;
        left: 0;
        z-index: 4000;
        height: 2.2rem;
        padding-right: 0.5rem;
        padding-left: 0.5rem;
        background-color: #734d41;
        -webkit-backface-visibility: hidden;
        backface-visibility: hidden;
    }
</style>