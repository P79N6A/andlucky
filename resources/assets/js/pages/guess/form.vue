<template>
<div class="page" id="pageCreateMicroblog">
    <header class="bar bar-nav">
        <a class="button button-link button-nav pull-left" @click="$router.go(-1)" data-transition='slide-out'>
            <span class="icon icon-left"></span>
            返回
        </a>
        <h1 class="title">押大小</h1>
    </header>
    <foot-bar select="pub"></foot-bar>
    <div class="content">
        <div class="list-block" style="margin:0rem 0rem .5rem 0rem;padding:0rem .5rem;">
            <ul>
              <!-- Text inputs -->
                <li>
                    <div class="item-content">
                        <div class="item-inner">
                            <div class="item-title label">庄池</div>
                            <div class="item-input">
                                <input style="height:1.75rem;width:100%;" placeholder="请输入狗粮数目" v-model.number="cash" />
                            </div>
                        </div>
                    </div>
                </li>
              	<li class="topic">
                    <div class="item-content">
                        <div class="item-inner">
                            <div class="item-title label">赔率</div>
                            <div class="item-input">
                                <a v-for="(item , index) in rateCate" class="radio-item" :class="{active: type == index }" 
                                    @click="changeType( index )" >{{item}}</a>
                            </div>
                        </div>
                    </div>
                </li>

                <li class="topic">
                    <div class="item-content">
                        <div class="item-inner">
                            <div class="item-title label">大小</div>
                            <div class="item-input">
                                <a class="radio-item" :class="{active: seed == 'max' }" @click="changeSeed( 'max' )" >大</a>
                                <a class="radio-item" v-show="type == 2.97" :class="{active: seed == 'mid' }" @click="changeSeed( 'mid' )" >中</a>
                                <a class="radio-item" :class="{active: seed == 'min' }" @click="changeSeed( 'min' )" >小</a>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="topic">
                    <div class="item-content">
                        <div class="item-inner">
                            <div class="item-title label">限时</div>
                            <div class="item-input">
                                <a class="radio-item" :class="{active: last == 1 }" @click="changeTime( 1 )" >1小时</a>
                                <a class="radio-item" :class="{active: last == 2 }" @click="changeTime( 2 )" >2小时</a>
                                <a class="radio-item" :class="{active: last == 3 }" @click="changeTime( 3 )" >3小时</a>
                                <a class="radio-item" :class="{active: last == 4 }" @click="changeTime( 4 )" >4小时</a>
                            </div>
                        </div>
                    </div>
                </li>                
            </ul>

            

            <div class="content-block" style="margin:.5rem 0rem;">
                <div class="row">
                    <div class="col-100">
                        <span class="button button-big button-fill button-success" @click="publish" >发起</span>
                    </div>
                </div>
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
            title : '' ,
            cash:0 ,
            content : '' ,
            type : 1.97 ,
            seed : 'max' ,
            last : 1 ,
            rateCate :[] ,
        }
    },
    created:function(){
        this.rateCate = { "1.97" : "1.97" , "2.97" : "2.97"}
    } ,
    methods:{
        publish : function(){
            let that = this
            if( !that.cash ) {
                that.$toast("请填写提供的奖励狗粮")
                return false 
            }
            if( that.cash < 1 ) {
                that.$toast("狗粮太少了吧")
                return false 
            }
            if( !that.type ) {
                that.$toast("请选择一个赔率")
                return false 
            }
            if( !that.seed ) {
                that.$toast("请选择要放的大小")
                return false 
            }
            Axios.post('/guess/store' , {
                cash : that.cash ,
                type : that.type ,
                seed : that.seed ,
                last : that.last ,
                '_token': window._Token , 
                'api_token' : window.apiToken
            }).then(function( ret ){
                let data = ret.data 
                that.$toast( data.msg )
                if( data.errcode === 0 ) {
                    console.log( data.blog )
                    setTimeout( function(){
                        that.$router.push({
                            name : 'guess-show' ,
                            params:{
                                id : data.guess.id
                            } ,
                            query :{
                                from : 'form'
                            }
                        })    
                    } , 1500 )
                    
                }
            })
        },
        changeType:function( i ) {
            this.type = i 
        },
        changeTime : function( i ) {
            this.last = i 
        } ,
        changeSeed:function( i ) {
            this.seed = i 
        }
    },
    watch:{
        
    },
    components: {
        'foot-bar' : footBar     }
};
</script>

<style scoped>
.topic {
    padding:.3rem 0rem;
}
.topic .radio-item {
    border:#f3f3f3 solid 1px;
    border-radius: 3px;
    padding:.2rem .5rem;
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
</style>