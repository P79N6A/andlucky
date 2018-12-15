<template>
<div class="page" id="pageBaseinfo">
    <header class="bar bar-nav">
        <a class="button button-link button-nav pull-left" @click="$router.go(-1)">
            <span class="icon icon-left"></span>
            返回
        </a>
        <h1 class="title">完善信息</h1>
    </header>
    <div class="content" >
        <div class="list-block" style="margin:.5rem 0rem;">
            <!--
            <div class="content-block-title text-center" v-if="!edit_enable" v-html="limit_tip()">
            	
            </div>
        	-->
            <ul>
              <!-- Text inputs -->
              	<li>
                    <div class="item-content">
                        <div class="item-inner sex-option cursor">
                            <div class="item-title label">昵称</div>
                            <div class="item-input">
                                <input type="text" v-model="nickname" placeholder="请填写昵称" maxlength="8">
                            </div>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="item-content">
                        <div class="item-inner sex-option cursor">
                            <div class="item-title label">性别</div>
                            <div class="item-input">
                                <input type="text" v-model="gender" readonly="readonly" @click="openGender" placeholder="请选择性别">
                            </div>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="item-content">
                        <div class="item-inner">
                            <div class="item-title label">出生日期</div>
                            <div class="item-input">
                                <input type="text" readonly="readonly" :value="birthday" @click="openBirth" placeholder="请选择出生日期">
                            </div>
                        </div>
                    </div>
                </li>

                <li>
                    <div class="item-content">
                        <div class="item-inner">
                            <div class="item-title label">城市</div>
                            <div class="item-input">
                                <input type="text" readonly="readonly" v-model="city" @click="openCity" placeholder="请选择居住城市">
                            </div>
                        </div>
                    </div>
                </li>
                <!--
                <li>
                    <div class="item-content">
                        <div class="item-inner">
                            <div class="item-title label">婚否</div>
                            <div class="item-input">
                                <label class="label-switch">
                                    <input type="checkbox" v-model="is_marry" >
                                    <div class="checkbox"></div>
                                </label>
                            </div>
                        </div>
                    </div>
                </li>
				-->
                <li>
                    <div class="item-content">
                        <div class="item-inner">
                            <div class="item-title label">学历</div>
                            <div class="item-input">
                                <input type="text" readonly="readonly" v-model="degree" @click="openDegree" placeholder="请选择学历">
                            </div>
                        </div>
                    </div>
                </li>

                <li>
                    <div class="item-content" @click="openCareer">
                        <div class="item-inner">
                            <div class="item-title label">喜好</div>
                            <div class="item-input" >
                                <span v-for="item in career" @click.stop.self class="tag" @click="selectIt( item )">{{item}}</span>
                            </div>
                        </div>
                    </div>
                </li>
                <li v-if="user.alipay_account ==''">
                    <div class="item-content">
                        <div class="item-inner">
                            <div class="item-title label">支付宝</div>
                            <div class="item-input">
                                <input type="text" v-model="alipay" placeholder="请输入支付宝账号">
                            </div>
                        </div>
                    </div>
                </li>
                <li v-else>
                    <div class="item-content">
                        <div class="item-inner">
                            <div class="item-title label">支付宝</div>
                            <div class="item-input">
                                {{user.alipay_account}}
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
            <div class="content-block">
                
                <div class="row" v-if="edit_enable || true">
                    <div class="col-100">
                    	<a id="btn-modbaseinfo" class="button button-big button-fill button-success" @click="save">保存</a>
                    </div>
                </div>
                
            </div>
        </div>
    </div>

    <div class="actions-modal" :class="{ 'modal-in' : showGender , 'modal-out' : !showGender }">
    	<header class="bar bar-nav">
    		<button class="button button-link pull-right" @click="closeGender">确定</button>
    		<h1 class="title">请选择性别</h1>
    	</header>
    	<smooth-picker ref="genderPicker" :data="genderData" :change="changeGender" />
    </div>
    <div class="actions-modal" :class="{ 'modal-in' : showBirth , 'modal-out' : !showBirth }">
    	<v-calendar
    		:is-expanded='true'
    		@dayselect="changeBirth"
    		:attributes='calendar_attrs'

    	>
  		</v-calendar>
    </div>
    
    <div class="actions-modal" :class="{ 'modal-in' : showDegree , 'modal-out' : !showDegree }">
    	<header class="bar bar-nav">
    		<button class="button button-link pull-right" @click="closeDegree">确定</button>
    		<h1 class="title">请选择学历</h1>
    	</header>
    	<smooth-picker ref="degreePicker" :data="degreeData" :change="changeDegree" />
    </div>
    <div class="actions-modal" :class="{ 'modal-in' : showCity , 'modal-out' : !showCity }">
    	<header class="bar bar-nav">
    		<button class="button button-link pull-right" @click="closeCity">确定</button>
    		<h1 class="title">请选择居住地</h1>
    	</header>
    	<smooth-picker ref="cityPicker" :data="cityData" :change="changeCity" />
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
import Axios from 'axios'
import {getCookie , formatDate , setCookie } from '../../utils'
import cityData from '../../citydata.js'
export default {
	data:function(){
		let that = this
		return {
			limit_time : 0 ,
			nickname:'',
			showGender : false ,
			showBirth : false ,
			showCareer : false ,
			showDegree : false ,
			showCity : false ,
			birthday : null ,
			career : [] ,
			degree : '' ,
			is_marry : false ,
			alipay:'' ,
			degreeData:[
				{
					currentIndex: 0 ,
					flex: 3,
					list: [] ,
					onClick: this.clickOnCareer,
					textAlign: 'center',
					className: 'row-group'
				},
			] ,
			careerData:[
				{
					currentIndex: 0 ,
					flex: 3,
					list: [] ,
					onClick: this.clickOnCareer,
					textAlign: 'center',
					className: 'row-group'
				},
			] ,
			genderData:[
				{
					currentIndex: 0 ,
					flex: 3,
					list: [ '男', '女'],
					onClick: this.clickOnGender,
					textAlign: 'center',
					className: 'row-group'
				},
			] ,
			cityData :[
				{
					currentIndex: 0 ,
					flex: 3,
					list: [],
					textAlign: 'center',
					className: 'row-group'
				},
				{
					currentIndex: 0 ,
					flex: 3,
					list: [ '男', '女'],
					textAlign: 'center',
					className: 'row-group'
				},
				{
					currentIndex: 0 ,
					flex: 3,
					list: [ '男', '女'],
					textAlign: 'center',
					className: 'row-group'
				},
			],
			gender : '男' ,
			calendar_attrs:[{
				highlight: {
		            backgroundColor: 'red',
		            borderRadius: '5px'             // Only applied on highlighted end caps
		        },
		        contentStyle: {
		            color: 'white'                  // Contrasts well with the red background
		        },
		        dates: new Date()
			}] ,
			user:{}
		}
	},
	created:function(){
		let that = this
		

	},
	mounted:function(){
		let that = this
		Axios.get('/baseinfoconfig' + 
			'?_token='+  window._Token + 
			'&api_token=' + window.apiToken 
		).then(function(response) {
			//console.log( that );
			let data = response.data 
			that.user = data.data.user 
			that.career = that.user.tags.split(',')
			that.careerConf = data.data.career 
			that.degreeConf = data.data.degree
			that.edit_enable = data.data.edit_enable
			that.limit_time = data.data.limit_time
			if( that.user.birth_day ) {
				that.birthday = formatDate( new Date( that.user.birth_day * 1000 ) , 'yyyy-MM-dd' )	
			}
			that.nickname = that.user.nickname 
			that.city = that.user.city 
			//处理职业的初始化数据\
	        that.careerData = data.data.career 
	        that.alipay = that.user.alipay_account
	        //处理学历
	        var list = [] 
	        for( var i in data.data.degree  ) {
	        	list.push( data.data.degree[i] )
	        	//处理一下 编号
	        	if( that.user.degree == i ) {
	        		that.degree = data.data.degree[i]
	        	}
	        }

	        that.degreeData[0].list = list 
	        //that.$refs.careerPicker.setGroupData( 0 , Object.assign({}, that.careerData[0],  change ))
	        that.calendar_attrs[0].dates = new Date( that.birthday )
	       	var province = that.format( cityData )
	       	
	        that.cityData[0].list = province
	        var city = that.getCities( province[0] )
	        that.cityData[1].list = city 
	        var district = that.getDistricts( city[0] )
	        that.cityData[2].list = district 

		});
	
        //this.$refs.smoothPicker.setGroupData(2, Object.assign({}, this.data[2], { currentIndex, list }))


        
        
	},
	methods:{

		format : function(data) {
	        var result = [];
	        for(var i=0;i<data.length;i++) {
	            var d = data[i];
	            if(d.name === "请选择") continue;
	            result.push(d.name);
	        }
	        if(result.length) return result;
	        return [""];
	    } ,
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
	    	if( find === false && that.career.length <= 6 ) {
	    		that.career.push( item )
	    	}
	    },
    	sub : function(data) {
    		let that = this
	        if(!data.sub) return [""];
	        return that.format(data.sub);
	    } ,

    	getCities : function(d) {
    		let that = this
	        for(var i=0;i< cityData.length;i++) {
	            if(cityData[i].name === d) return that.sub(cityData[i]);
	        }
	        return [""];
    	} ,

    	getDistricts : function(p, c) {
    		let that = this
	        for(var i=0;i< cityData.length;i++) {
	            if(cityData[i].name === p) {
	                for(var j=0;j< cityData[i].sub.length;j++) {
	                    if(cityData[i].sub[j].name === c) {
	                        return that.sub(cityData[i].sub[j]);
	                    }
	                }
	            }
	        }
	        return [""];
	    } ,
		changeBirth:function( e , v ){
			console.log( e , v )
			let that = this
			that.birthday = formatDate( e.date , 'yyyy-MM-dd' )
			that.showBirth = false 
		},
		changeGender:function( g , v){
			let gender = [ '男', '女']
			var m = this.$refs.genderPicker.getCurrentIndexList()
			console.log( m )
			this.gender = gender[v]
		} ,
		openBirth:function(){
			this.showBirth = true 
			this.showCity = this.showCareer = this.showGender = this.showDegree = false 
		} ,
		closeBirth:function(){
			this.showBirth = false 
		} ,
		openGender : function(){
			let that = this 
			that.genderData[0].currentIndex = 0 
			this.showGender = true 
			this.showCity = this.showCareer = this.showBirth = this.showDegree = false 
		} ,
		closeGender : function(){
			let that = this
			let m = that.$refs.genderPicker.getCurrentIndexList()
			that.gender = that.genderData[ 0 ].list[ m[0] ]
			this.showGender = false 
		} ,
		clickOnGender : function( g , v ){
			console.log( g , v )
		} ,
		openCareer:function(){
			this.showCareer = true 
			this.showCity = this.showGender = this.showBirth = this.showDegree = false 
		},
		closeCareer:function(){
			let that = this
			this.showCareer = false
			
		} ,
		openDegree:function(){
			this.showDegree = true 
			this.showCity = this.showGender = this.showBirth = this.showCareer = false 
		} ,
		closeDegree:function(){
			let that = this
			this.showDegree = false 
			let m = that.$refs.degreePicker.getCurrentIndexList()
			that.degree = that.degreeData[ 0 ].list[ m[0] ]
		} ,
		clickOnDegree:function( g , v ){
			let that = this 
			that.degree = that.degreeData[ g ].list[v]
		} ,
		changeDegree:function( g , v ){
			let that = this 
			that.degree = that.degreeData[ g ].list[v]
		} ,
		openCity : function(){
			let that = this
			this.showGender = this.showBirth = this.showCareer = this.showDegree = false 
			that.showCity = true 
		} ,
		closeCity : function(){
			let that = this 
			that.showCity = false 
			let indexList = that.$refs.cityPicker.getCurrentIndexList()
			var province = ''
			var city = '' 
			var zone = ''
			province = that.cityData[0].list[ indexList[0] ]
			city = that.cityData[1].list[ indexList[1] ]
			zone = that.cityData[2].list[ indexList[2] ]
			that.city = province + ' ' + city + ' ' + zone 
		} ,
		clickOnCity : function( g , v ) {

		} ,
		changeCity : function( g , v ) {
			let that = this
			var province = ''
			var city = '' 
			var zone = ''
			console.log( g , v )
			switch( g ) {
				case 0 :
					province = that.cityData[0].list[v]
					that.cityData[1].list = that.getCities( province )
					city = that.cityData[1].list[0]
					that.cityData[2].list = that.getDistricts( province , city )
					zone = that.cityData[2].list[0]
					break ;
				case 1 :
					let indexList = that.$refs.cityPicker.getCurrentIndexList()
					province = that.cityData[0].list[ indexList[0] ]
					city = that.cityData[1].list[v]
					that.cityData[2].list = that.getDistricts( province , city )
					zone = that.cityData[2].list[0]
					break ;
				case 2 :
					zone = that.cityData[2].list[v]
					break ;

			}
			that.city = province + ' ' + city + ' ' + zone 
			console.log( province , city , zone )
		},
		save:function(){
			let that = this
			if( !that.alipay ) {
				that.$toast("请填写正确的收款账号");
				return false ;
			}
			if( that.nickname.length > 8 ) {
				that.$toast("昵称最长为8个字符");
				return false ;
			}

			Axios.post('/updatebaseinfo' , {
				nickname : that.nickname ,
				birth_day : that.birthday ,
				city : that.city ,
				tags : that.career.join(',') ,
				degree : that.degree ,
				gender : that.gender ,
				is_marry : that.is_marry ,
				alipay_account:that.alipay ,
				_token : window._Token ,
				api_token : window.apiToken ,
			}).then( function( ret ){
				let data = ret.data 
				that.$toast( data.msg )
				if( data.errcode === 0 ) {
					setTimeout( function(){
						that.$router.go(-1)
					} , 1500 )
				}
			});

			/**
			that.$confirm('您确定要提交保存吗？这次修改后下一次修改要等很长时间哦!!!' , {
				yes:{
					text :'确定' ,
				} ,
				no :{
					text : '再考虑下'
				}
			}).then( function( ){
				Axios.post('/updatebaseinfo' , {
					nickname : that.nickname ,
					birth_day : that.birthday ,
					city : that.city ,
					tags : that.career.join(',') ,
					degree : that.degree ,
					gender : that.gender ,
					is_marry : that.is_marry ,
					alipay_account:that.alipay ,
					_token : window._Token ,
					api_token : window.apiToken ,
				}).then( function( ret ){
					let data = ret.data 
					that.$toast( data.msg )
					if( data.errcode === 0 ) {
						setTimeout( function(){
							that.$router.go(-1)
						} , 1500 )
					}
				})
			}).catch( function(){
				console.log('no')
			})
			**/
		} ,
		limit_tip : function(){
			let that = this 
			let day = Math.floor( that.limit_time / 86400 )
			let hour = Math.floor ( ( that.limit_time % 86400 ) / 3600  )
			let minute = Math.floor( ( that.limit_time % 3600 ) / 60 )
			return "距离下一次可编辑还有" + day + "天"+ hour +"小时"+ minute +"分钟"
		}

	},
	components : {
		
	}
}

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
</style>