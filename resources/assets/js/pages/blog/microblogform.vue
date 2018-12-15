<template>
<div class="page" id="pageCreateMicroblog">
    <header class="bar bar-nav">
        <h1 class="title">资讯</h1>
    </header>
    <foot-bar select="pub"></foot-bar>
    <div class="content">
        <div class="list-block" style="margin:.5rem 0rem .5rem 0rem;padding:0rem .5rem;">
            <ul class="no-before">
              <!-- Text inputs -->
                <li>
                    <div class="item-content">
                        <div class="item-inner">
                            <div class="item-input">
                                <input style="height:1.75rem;width:100%;" placeholder="请输入标题" v-model="title" />
                            </div>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="item-content">
                        <div class="item-inner">
                            <div class="item-input">
                                <textarea style="font-size:.75rem;" placeholder="请输入内容" v-model="content"></textarea>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="topic">
                    <div class="item-content">
                        <div class="item-inner">
                            <div class="item-input">
                                <a v-for="(item , index) in blogCate" class="radio-item" :class="{active: type == item.id }" 
                                    @click="changeType( item.id )" >{{item.title}}</a>
                            </div>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="item-content">
                        <div class="item-inner">
                            <div class="item-input allow_auth">
                                <input type="checkbox" v-model="allow_auth"  />&nbsp;&nbsp;报审 <span class="help">审核通过可享收益</span>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>

            <ul class='image row'>
                <li class="col-33" v-for="(item , index) in preViews" :style="{'background-image':'url( '+ bg( item ) +' )'}">
                    <i class="iconfont icon-chuyidong" @click="remove( item )"></i>
                </li>
                <li class="col-33 image-handler" >
                <file-upload
                    ref="upload"
                    v-model="files"
                    accept="image/*" 
                    class="upload-hander iconfont icon-xinzeng"
                    name="file" 
                    post-action="/uploadfile" 
                    :data="fileUploadData" 
                    :multiple="true"
                    :maximum="9" 
                    @input-filter="inputFilter"
                >
                    &nbsp;
                </file-upload>
                </li>
                
            </ul>

            <div class="content-block" style="margin:.5rem 0rem;">
                <div class="row">
                    <div class="col-100">
                        <span v-if="uploading === false" class="button button-big button-fill button-success" @click="publish" >发布</span>
                        <span v-else class="button button-big button-fill" >正在上传图片</span>
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
import VueUploadComponent from "vue-upload-component"
export default {
    data:function(){
        return {
            title : '' ,
            'content' : '' ,
            'type' : '' ,
            'files' : [] ,
            old_files : [] ,
            blogCate :[] ,
            'preViews' : [] ,
            allow_auth : false ,
            uploading : false ,
            fileUploadData : {'_token': window._Token , 'api_token' : window.apiToken} ,
            id : 0 ,
        }
    },
    created:function(){
        let that = this
        this.blogCate = window.blogCate 
        this.id = this.$route.params.id || 0 ;
        console.log( this.id )
        if( this.id ) {
            Axios.get('/microblog/' + this.id  + '/edit' , {
                '_token': window._Token , 
                'api_token' : window.apiToken
            }).then(function( ret ){
                let data = ret.data 
                if( data.errcode === 0 ) {
                    that.title = data.data.title
                    that.content = data.data.content
                    that.type = data.data.cate_id
                    if( data.data.extra.data ) {
                        for( var i in data.data.extra.data ) {
                            that.old_files.push( data.data.extra.data[i] )
                        }
                    }
                    that.preViews = data.data.extra.data 
                    console.log( data.data.extra.data  )
                    that.allow_auth = data.data.allow_auth == 1 ? true : false 
                }
            })
        }
    } ,
    methods:{
        bg : function( v ) {
            return typeof v == 'string' ? v : v.blob 
        },
        publish : function(){
            let that = this
            if( that.preViews.length > 0 ) {
                //先上传图片
                that.$refs.upload.active = true
                that.uploading = true
                //每隔300毫秒检查一次上传状态
                let timer = setInterval( function(){
                    console.log( that.$refs.upload.uploaded )
                    if( that.$refs.upload.uploaded ) {
                        that.uploading = false 
                        console.log('all file uploaded')
                        that.createBlog()
                        clearInterval( timer )
                    }
                }, 300 )
            } else {
                that.createBlog()
            }
        } ,
        createBlog:function(){
            let that = this
            if( that.title == '' ) {
                that.$toast("请填写标题")
                return false 
            }
            if( that.title.length > 30 ) {
                that.$toast("标题长度不能超过30个字符")
                return false 
            }
            if( that.content == '' && that.files.length == 0 ) {
                that.$toast("先写点什么再提交吧")
                return false 
            }
            let file = [] 
            
            for( var i = 0 ; i <that.old_files.length ; i++ ) {
                file.push( that.old_files[i] );
            }
            if( that.files.length > 0 ) {
                for( var i = 0 ; i < that.files.length ; i++ ) {
                    file.push( that.files[i].response.data )
                }
            }
            
            let url = that.id > 0 ? '/updateblog' : 'createblog' ;
            Axios.post( url  , {
                id : that.id ,
                title : that.title ,
                info : that.content ,
                cate : that.type ,
                uploaded : file ,
                allow_auth : that.allow_auth ? 1 : 0 ,
                '_token': window._Token , 
                'api_token' : window.apiToken
            }).then(function( ret ){
                let data = ret.data 
                that.$toast( data.msg )
                if( data.errcode === 0 ) {
                    console.log( data.blog )
                    setTimeout( function(){
                        that.$router.push({
                            name : 'microblog' ,
                            params:{
                                cate_id : that.type
                            } ,
                            query :{
                                cate_id : that.type ,
                                from : 'form' 
                            }
                        })    
                    } , 1500 )
                    
                }
            })
        },
        changeType:function( i ) {
            this.type = i 
        } ,
        remove:function( item ) {
            let that = this
            for( var i in that.preViews ) {
                if( item == that.preViews[i] ) {
                    that.preViews.splice( i , 1 )
                }
            }
            if( typeof( item ) == 'string' ) {
                for( var i in that.old_files ) {
                    if( item == that.old_files[i] ) {
                        that.old_files.splice( i , 1 )
                    }
                }   
            } else {
                that.$refs.upload.remove( item )    
            }
            

        } ,
        /**
         * Pretreatment
         * @param  Object|undefined   newFile   读写
         * @param  Object|undefined   oldFile   只读
         * @param  Function           prevent   阻止回调
         * @return undefined
         */
        inputFilter: function (newFile, oldFile, prevent) {
            let that = this
            if( newFile.size >= 2*1024*1024*1024 ) {
                this.$toast("文件大小超过限制")
                return prevent()
            }
            if (newFile && !oldFile) {
                // 过滤不是图片后缀的文件
                if (!/\.(jpeg|jpe|jpg|gif|png|webp)$/i.test(newFile.name)) {
                    return prevent()
                }
                newFile.blob = ''
                let URL = window.URL || window.webkitURL
                if (URL && URL.createObjectURL) {
                    newFile.blob = URL.createObjectURL(newFile.file)
                }
                that.preViews.push( newFile )
            }
        }
    },
    watch:{
        
    },
    components: {
        'foot-bar' : footBar ,
        FileUpload: VueUploadComponent ,
    }
};
</script>

<style scoped>
ul.no-before:before {
    display: none;
}
.allow_auth {
    font-size: .75rem;
}
.topic {
    padding:.3rem 0rem;
}
.topic .radio-item {
    border:#f3f3f3 solid 1px;
    border-radius: 3px;
    padding:.2rem .5rem;
    margin-bottom: .2rem;
    margin-right:.3rem;
    display: inline-block;
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
.help {
    color:gray;
    font-size: .6rem;
}
</style>