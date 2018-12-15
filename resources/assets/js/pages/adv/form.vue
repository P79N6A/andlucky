<template>
<div class="page" id="pagePostEdit">
    <header class="bar bar-nav">
    	<a v-if="id > 0 " class="button button-link button-nav pull-left" @click="$router.go(-1)">
            <span class="icon icon-left"></span>
            返回
        </a>
        <h1 class="title">编辑广告</h1>
    </header>
    <foot-bar select="pub"></foot-bar>
    <div class="content" style="margin:.5rem 0rem 0rem 0rem;">
        <div class="list-block" style="margin:.5rem 0rem;">
            <ul>
              <!-- Text inputs -->
                <li>
                    <div class="item-content">
                        <div class="item-inner">
                            <div class="item-title label">广告标题</div>
                            <div class="item-input">
                                <input type="text" v-model="title" placeholder="请输入广告标题">
                            </div>
                        </div>
                    </div>
                </li>
                <!--
                <li>
                    <div class="item-content">
                        <div class="item-inner">
                            <div class="item-title label">广告分类</div>
                            <div class="item-input">
                                <input type="text" v-model="category_name" readonly @click="openCate" placeholder="请选择广告分类">
                            </div>
                        </div>
                    </div>
                </li>
                -->
                <li>
                    <div class="item-content" @click="openCareer">
                        <div class="item-inner">
                            <div class="item-title label">标签</div>
                            <div class="item-input" placeholder="请选择标签">
                                <span v-for="item in career" @click.stop.self class="tag" @click="selectIt( item )">{{item}}</span>
                            </div>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="item-content">
                        <div class="item-inner">
                            <div class="item-title label">点击价格</div>
                            <div class="item-input">
                                <input type="text" readonly @click="openPrice" v-model="click_price" placeholder="请选择单次点击价格">
                            </div>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="item-content">
                        <div class="item-inner">
                            <div class="item-title label">点击次数</div>
                            <div class="item-input">
                                <input type="text" v-model="click_times" placeholder="请输入总点击次数">
                            </div>
                        </div>
                    </div>
                </li>

                <li>
                    <div class="item-content">
                        <div class="item-inner">
                            <div class="item-title label">天数</div>
                            <div class="item-input">
                                <input type="text" v-model="last_days" placeholder="最小天数为15天">
                            </div>
                        </div>
                    </div>
                </li>

                <li>
                    <div class="item-content">
                        <div class="item-inner">
                            <div class="item-title label">网址</div>
                            <div class="item-input">
                                <input type="text" v-model="url" placeholder="请输入链接地址 http://">
                            </div>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="item-content">
                        <div class="item-inner">
                            <div class="item-title label">总费用</div>
                            <div class="item-inner color-red">
                                {{total_price}}
                            </div>
                        </div>
                    </div>
                </li>
            </ul>


        </div>
        <div class="content-block-title" style="margin-top:.75rem;font-size:.8rem;">广告图片</div>
        <div class="content-block" style="margin:0rem 0rem .5rem 0rem;background: #fff;padding:.75rem .75rem;">
            <p class="color-gray text-center" >请上传广告图片，大小不能超过2M</p>
            <p class="text-center" style="height:8rem;width:100%;">
            	<file-upload
                    ref="upload"
                    v-model="files"
                    class="upload-hander"
                    accept="image/*" 
                    name="file" 
                    id="uploadAdv" 
                    post-action="/uploadfile" 
                    :data="fileUploadData" 
                    :multiple="false"
                    :maximum="1" 
                    @input-filter="inputFilter"
                    :style="{'background-image':'url( '+ cover +' )'}"
                >
                </file-upload>
                
            </p>
        </div>

        <div class="content-block-title" style="margin-top:.75rem;font-size:.8rem;">有关介绍</div>
        <div class="list-block" style="margin:.5rem 0rem;">
            <ul>
              <!-- Text inputs -->
                <li>
                    <div class="item-content">
                        <div class="item-inner">
                            <div class="item-input">
                                <textarea placeholder="请输入有关介绍" v-model="content"></textarea>
                            </div>
                        </div>
                    </div>
                </li>

            </ul>

            <div class="content-block">
                <p>
                    <a v-if="uploading == false" class="button button-big button-fill button-success" @click="pupadv()">发布广告</a>
                    <a v-else class="button button-big button-fill">正在上传广告图</a>
                </p>
            </div>

        </div>


    </div>
  

    <div class="modal-overlay " :class="{'modal-overlay-visible' : showCate }"  @click="closeCate">
        <div class="modal" :class="{ 'modal-in' : showCate , 'modal-out' : !showCate }"  style="display:block;top:5rem;background:#fff;height:20rem;overflow-y:scroll;text-align:left;" @click.stop.self >
            <div class="content-block" style="margin:.5rem;">
                
                <span v-for="( item , index ) in cates" class="tag" @click="changeCate( index )" :class="{active: index == category_id }">{{item}}</span>
            </div>
        </div>
    </div>

    <div class="modal-overlay " :class="{'modal-overlay-visible' : showPrice }"  @click="closePrice">
        <div class="modal" :class="{ 'modal-in' : showPrice , 'modal-out' : !showPrice }"  style="display:block;top:5rem;background:#fff;height:20rem;overflow-y:scroll;text-align:left;" @click.stop.self >
            <div class="content-block" style="margin:.5rem;">
                <span v-for="( item , index ) in prices" class="tag" @click="changePrice( item )" :class="{active: item == click_price }">{{item}}</span>
            </div>
            <div class="content-block">
                
            </div>
        </div>
    </div>


    <div class="modal-overlay " :class="{'modal-overlay-visible' : showCareer }"  @click="closeCareer">
        <div class="modal" :class="{ 'modal-in' : showCareer , 'modal-out' : !showCareer }"  style="display:block;top:5rem;background:#fff;height:20rem;overflow-y:scroll;text-align:left;" @click.stop.self >
            <div class="content-block" style="margin:.5rem;">
                <span v-for="item in careerData" class="tag" @click="selectIt( item )" :class="{active: strAt( item )}">{{item}}</span>
            </div>
            <div class="content-block">
                
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
			id : 0 ,
			showCate : false ,
			showPrice : false ,
            showCareer:false ,
			cover : '/images/logo.png' ,
			files:[] ,
			title : '' ,
			category_id : 0 ,
			category_name : '' ,
			click_price : '' ,
			click_times : 10000 ,
			last_days : 15 ,
			url : '' ,
			total_price : 0 ,
			content : '' ,
			uploading:false ,
			minCash : 0 ,
            career:[] ,
            careerData:[] ,
			cates : [] ,
			prices : [] ,
			fileUploadData : {'_token': window._Token , 'api_token' : window.apiToken} ,
			cateData:{} ,
			priceData:[
				{
					currentIndex: 0 ,
					flex: 3,
					list: [] ,
					textAlign: 'center',
					className: 'row-group'
				},
			] ,

		}
	} ,
	created:function(){

		let that = this ;
		if( that.$route.params.id ) {
			that.id = that.$route.params.id 
			Axios.get('/adv/' + that.id +  '/edit' +
				'?api_token=' + window.apiToken 
			 , {
				//responseType : 'json'
			}).then(function(response) {
				console.log( response );
				let data = response.data 
				if( data.errcode === 0 ) {
					that.minCash = data.data.min_pup_cash 
                    that.cates = data.data.cates ;
					that.prices = data.data.price 
					let adv = data.data.post
					that.title = adv.title 
					that.category_name = data.data.cates[ adv.category_id ]
					that.cover = adv.cover
					that.total_price = adv.total_price
					that.click_price = adv.click_price
					that.click_times = adv.click_times
					that.last_days = adv.last_days
					that.content = adv.posts_content
					that.category_id = adv.category_id
					that.url = adv.url

					
				} else {
					//接口数据获取错误
					that.$router.go(-1)
				}
				
			});
		} else {
			Axios.get('/advconf' +
				'?api_token=' + window.apiToken 
			 , {
				//responseType : 'json'
			}).then(function(response) {
				console.log( response );
				let data = response.data 
				if( data.errcode === 0 ) {
					that.minCash = data.data.min_pup_cash 
                    that.cates = data.data.cates ;
                    that.prices = data.data.price 
                    that.careerData = data.data.tags ;
					console.log( that.prices )
				} else {
					//接口数据获取错误
					that.$router.go(-1)
				}
				
			}).catch( function( e ){
                alert( e )
            });
		}
	},
	methods:{
        strAt : function( item ){
            let that = this
            let find = false 
            for( var i = 0 ; i<that.career.length ; i++ ) {
                if( that.career[i] == item ) {
                    find = true
                    break ;
                }
            }
            return find 
        },
        selectIt: function( item ){
            let that = this
            let find = false 
            for( var i = 0 ; i<that.career.length ; i++ ) {
                if( that.career[i] == item ) {
                    that.career.splice( i , 1 )
                    find = true
                    break ;
                }
            }
            if( find === false && that.career.length < 3) {
                that.career.push( item )
            }
        },
        openCareer:function(){
            this.showCareer = true 
            this.showCate = this.showPrice = false 
        },
        closeCareer:function(){
            let that = this
            this.showCareer = false
            
        } ,
		pupadv : function(){
            let that = this
            that.category_id = 0 
            //检查必填信息
			if( !that.title ) {
				that.$toast('好的广告标题是成功的一半哦!');
				return false ;
			}
			if( !that.category_id ) {
				//that.$toast("为您的广告选一个分类吧！");
				//return false ;
			}
			if( !that.click_price ) {
				that.$toast("打赏点小费给用户吧！");
				return false ;
			}
			
			that.click_times = parseInt( that.click_times , 10 );
			that.click_times = isNaN( that.click_times ) ? 0 : that.click_times ;
			if( !that.click_times ) {
				that.$toast("请设置最大点击次数！");
				return false ;
			}

			that.last_days = parseInt( that.last_days , 10 );
			that.last_days = isNaN( that.last_days ) ? 0 : that.last_days ;
			if( !that.last_days ) {
				that.$toast("再好的广告也有看腻的时候，给您的广告设置展示天数吧！");
				return false ;
			}
			if( that.last_days < 15 ) {
				that.$toast("系统要求最小设置15天的展示期");
				return false ;
			}

			if( !that.files.length && that.cover == '/images/logo.png') {
				that.$toast("他们都说加个形象图更能给用户留下深刻印象哦！");
				return false ;
			}

			that.total_price = that.click_price * that.click_times ;
			if( that.minCash ) {
				if( that.total_price < that.minCash ) {
					that.$toast("根据您的评分级别，您本次发布最小铜板为" + that.minCash );
					return false ;
				}
			}

            if( that.files.length > 0 ) {
                //先上传图片
                that.$refs.upload.active = true
                that.uploading = true
                //每隔300毫秒检查一次上传状态
                let timer = setInterval( function(){
                    console.log( that.$refs.upload.uploaded )
                    if( that.$refs.upload.uploaded ) {
                        that.uploading = false 
                        console.log('all file uploaded')
                        that.createAdv()
                        clearInterval( timer )
                    }
                }, 300 )
            } else {
                that.createAdv()
            }
        } ,
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
                that.cover = newFile.blob 
            }
        } ,
        createAdv : function(){
        	let that = this
            
            let file = '' 
            let check = true 
            if( that.files.length > 0 ) {
                for( var i in that.files ) {
                    var res = that.files[i].response
                    if( res.errcode != 0 ) {
                        that.$toast( res.msg )
                        check = false 
                    } else {
                        file = res.data
                    }
                }
            } else if( that.cover != '/images/logo.png' ) {
            	file = that.cover 
            }
            if( check === false ) {
                return false 
            }
            let url = '/adv/store' 
            if( that.id ) {
            	url = '/adv/' + that.id + '/update'
            }
            Axios.post( url , {
                'title': that.title ,
				'topic' : that.category_name ,
				'price' : that.click_price ,
				'cover' : file ,
				'day' : that.last_days ,
				'desc' : that.content ,
                'tags' : that.career.join(',') ,
				'url' : that.url ,
				'click_times' : that.click_times ,
                '_token': window._Token , 
                'api_token' : window.apiToken
            }).then(function( ret ){
                let data = ret.data 
                that.$toast( data.msg )
                if( data.errcode === 0 ) {
                    setTimeout( function(){
                        that.$router.push({
                            name : 'advshow' ,
                            params:{
                                id : data.data.id 
                            } ,
                            query :{
                                from : 'form'
                            }
                        })    
                    } , 1500 )
                    
                } else if( data.errcode === 30000 ) {
                	//余额不足
                	setTimeout( function(){
                        that.$router.push({
                            name : 'charge' ,
                            params:{

                            }
                        })    
                    } , 1500 )	
                }
            } , function( e ){
            }).catch( function(e){
                
            })
        } ,
        openCate:function(){
        	let that = this
        	that.showPrice = false 
        	that.showCate = true 
        },
        closeCate:function(){
        	let that = this
        	that.showCate = false 
        },
        changeCate:function( g ){
        	let that = this
        	that.category_id = g
        	that.category_name = that.cates[g ]
            that.showCate = false
        	
        } ,
        openPrice:function(){
        	let that = this 
        	that.showPrice = true 
        	that.showCate = false 
        } ,
        closePrice:function(){
        	let that = this
        	that.showPrice = false 
        } ,
        changePrice : function( g ){
        	let that = this
            that.showPrice = false 
        	that.click_price = g 
        }
	} , 
	watch:{
		click_times : function(  v ){
			console.log( v )
			let that = this
			v = parseInt( v , 10 );
			v = isNaN( v ) ? 0 : v ;
			let price = parseFloat( that.click_price , 2 )
			price = isNaN( price ) ? 0 : price ;
			that.total_price = ( v * price ).toFixed( 2 )
		} ,
		click_price : function( v ){
			let that = this
			v = parseFloat( v , 2)
			v = isNaN( v ) ? 0 : v ;
			let times = that.click_times
			times = parseInt( times , 10 );
			times = isNaN( times ) ? 0 : times ;

			that.total_price =( v * times ).toFixed( 2 )
		}
	},
	components :{
		'foot-bar' : footBar ,
        FileUpload: VueUploadComponent ,
	}
};
</script>
<style scoped>
.tag {
    display: inline-block;
    padding:.3rem;
    margin:0.2rem .2rem;
    border-radius: .2rem;
    background:#cccccc61;
}
.active {
    background:#724c41;
    color:#fff;
}
.upload-hander {
    width: 100%;
    height: 8rem;
    background: url(/images/logo.png) center center no-repeat;
    background-size: contain;
    display: block;
}
</style>
