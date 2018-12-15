$(document).on('click' , '.p-del' , function(){
	//删除事件
	var that = $(this);
	that.parents('.item').remove();
});


$('.p-new').unbind('click').bind('click' , function(){
	var that = $(this);
	layer.open({
		   'title':'中药饮片选择' ,
			type: 1,
			skin: 'layui-layer-rim', //加上边框
			area: ['560px', '240px'], //宽高
			id:'workTimeLayer' ,
			offset:'b' ,
			content: $('.prescription-tpl').html() ,
			btn:[ '确定' ] ,
			yes:function( index , lay ){
				var id = lay.find('select').val();
				var opt = lay.find('select option:selected') ;
				var name = opt.text();
				var is_poisonous = opt.data('is_poisonous');
				var toxic_criticality = opt.data('toxic_criticality');
				var unit = opt.data('unit');
				var num = lay.find('input').val();
				num = parseInt( num );
				num = isNaN( num ) ? 0 : num ;
				if( !num ) {
					toastr.error('请填写药片重量');
					return false ;
				}
				if( is_poisonous && num >= toxic_criticality ) {
					//这个超出了临界值
					layer.confirm( name + '超出了有毒临界值' + toxic_criticality + unit + '，是否询问医生添加签名备注', function( cindex ){
					  //do something
					  	var html = '<span class="item" data-id="'+ id +'" data-num="'+ num +'">' + name + '&nbsp;&nbsp;&nbsp;&nbsp;'+ num + unit + '<i class="fa fa-trash p-del"></i></span>';
						var find = false ;
						$('.has-many-prescription-forms .item').each(function(){
						
							if( $(this).data('id') == id ) {
								console.log( $(this).data('id') );		
								$(this).replaceWith( html );
								find = true ;
								return false ;
							}
						});
						if( false === find ) {
							$('.has-many-prescription-forms').append( html );
						}
						$('.has_fatal.la_checkbox').trigger('click');
						$('.is_sign.la_checkbox').trigger('click');
						//layer.close(index);
						layer.close(cindex);
					});
					return false ;	
				}
				var html = '<span class="item" data-id="'+ id +'" data-num="'+ num +'">' + name + '&nbsp;&nbsp;&nbsp;&nbsp;'+ num + unit + '<i class="fa fa-trash p-del"></i></span>';
				var find = false ;
				$('.has-many-prescription-forms .item').each(function(){
				
					if( $(this).data('id') == id ) {
						console.log( $(this).data('id') );		
						$(this).replaceWith( html );
						find = true ;
						return false ;
					}
				});
				if( false === find ) {
					$('.has-many-prescription-forms').append( html );
				}
				//layer.close( index );
			} ,
			success:function( layero, index ){
				layero.find('.prescription-select').select2({
					matcher: function(term, text) {
				       if ( typeof term.term == 'undefined' ) {
							return text ;
					   }

					   var name_en = $(text.element).data('name_en') ;
					   name_en = name_en ? name_en : '' ;
					   var name_en_short = $(text.element).data('name_en_short') ;
					   name_en_short = name_en_short ? name_en_short : '' ;
				       return ( 
				       		text.text.toUpperCase().indexOf( term.term.toUpperCase() ) >= 0 ||
							name_en.toUpperCase().indexOf( term.term.toUpperCase() ) >= 0  || 
							name_en_short.toUpperCase().indexOf( term.term.toUpperCase() ) >= 0 
						) ? text : null ;
				   }

				});
				
				layero.find('.prescription-select').on('change' , function(){
					layero.find('.p-num').focus();

				}).on('select2-blur' , function(){
					console.log('lose focus') ;
				});
			}
	});
		
});



$('.m-new').unbind('click').bind('click' , function(){
	var that = $(this);
	layer.open({
		   'title':'中成药选择' ,
			type: 1,
			skin: 'layui-layer-rim', //加上边框
			area: ['560px', '240px'], //宽高
			id:'workTimeLayer' ,
			offset:'b' ,
			content: $('.medicine-tpl').html() ,
			btn:[ '确定' ] ,
			yes:function( index , lay ){
				var id = lay.find('select').val();
				var opt = lay.find('select option:selected') ;
				var name = opt.text();
				var is_poisonous = opt.data('is_poisonous');
				var toxic_criticality = opt.data('toxic_criticality');
				var unit = opt.data('unit');
				var num = lay.find('input').val();
				num = parseInt( num );
				num = isNaN( num ) ? 0 : num ;
				if( !num ) {
					toastr.error('请填写药品数量');
					return false ;
				}
				var html = '<span class="item" data-id="'+ id +'" data-num="'+ num +'">' + name + '&nbsp;&nbsp;&nbsp;&nbsp;'+ num + unit + '<i class="fa fa-trash p-del"></i></span>';
				var find = false ;
				$('.has-many-medicine-forms .item').each(function(){
				
					if( $(this).data('id') == id ) {
						console.log( $(this).data('id') );		
						$(this).replaceWith( html );
						find = true ;
						return false ;
					}
				});
				if( false === find ) {
					$('.has-many-medicine-forms').append( html );
				}
				//layer.close( index );
			} ,
			success:function( layero, index ){
				$('.medicine-select').select2({
					matcher: function(term, text) {
				       if ( typeof term.term == 'undefined' ) {
							return text ;
					   }

					   var name_en = $(text.element).data('name_en') ;
					   name_en = name_en ? name_en : '' ;
					   var name_en_short = $(text.element).data('name_en_short') ;
					   name_en_short = name_en_short ? name_en_short : '' ;
				       return ( 
				       		text.text.toUpperCase().indexOf( term.term.toUpperCase() ) >= 0 ||
							name_en.toUpperCase().indexOf( term.term.toUpperCase() ) >= 0  || 
							name_en_short.toUpperCase().indexOf( term.term.toUpperCase() ) >= 0 
						) ? text : null ;
				   }

				}).on('select2-selecting' , function(){

					layero.find('input').focus();

				});

			}
	});
		
});




$('.s-new').unbind('click').bind('click' , function(){
	var that = $(this);
	layer.open({
		   'title':'养生方选择' ,
			type: 1,
			skin: 'layui-layer-rim', //加上边框
			area: ['560px', '240px'], //宽高
			offset:'b' ,
			id:'workTimeLayer' ,
			content: $('.secrettip-tpl').html() ,
			btn:[ '确定' ] ,
			yes:function( index , lay ){
				var id = lay.find('select').val();
				var opt = lay.find('select option:selected') ;
				var name = opt.text();
				var is_poisonous = opt.data('is_poisonous');
				var toxic_criticality = opt.data('toxic_criticality');
				var unit = opt.data('unit');
				var num = lay.find('input').val();
				num = parseInt( num );
				num = isNaN( num ) ? 0 : num ;
				if( !num ) {
					toastr.error('请填写养生方数量');
					return false ;
				}
				var html = '<span class="item" data-id="'+ id +'" data-num="'+ num +'">' + name + '&nbsp;&nbsp;&nbsp;&nbsp;'+ num + unit + '<i class="fa fa-trash p-del"></i></span>';
				var find = false ;
				$('.has-many-secrettip-forms .item').each(function(){
					if( $(this).data('id') == id ) {
						$(this).replaceWith( html );
						find = true ;
						return false ;
					}
				});
				if( false === find ) {
					$('.has-many-secrettip-forms').append( html );
				}
				//layer.close( index );
			} ,
			success:function( layero, index ){
				$('.secrettip-select').select2({
					matcher: function(term, text) {
				       if ( typeof term.term == 'undefined' ) {
							return text ;
					   }

					   var name_en = $(text.element).data('name_en') ;
					   name_en = name_en ? name_en : '' ;
					   var name_en_short = $(text.element).data('name_en_short') ;
					   name_en_short = name_en_short ? name_en_short : '' ;
				       return ( 
				       		text.text.toUpperCase().indexOf( term.term.toUpperCase() ) >= 0 ||
							name_en.toUpperCase().indexOf( term.term.toUpperCase() ) >= 0  || 
							name_en_short.toUpperCase().indexOf( term.term.toUpperCase() ) >= 0 
						) ? text : null ;
				   }

				}).on('select2-selecting' , function(){

					layero.find('input').focus();

				});
			}
	});
		
});

@if( isset( $album ) )
$('.show-image').unbind('click' ).bind('click' , function(){
	var html = "{!!implode( '' , $album ) !!}";
	var deg = 0 ;
	c = document.createElement('div');
	var c = $( c ) ;
	c.css('text-align' , 'center');
	c.append( html );
	var d = document.createElement('div');
	d = $( d );
	d.append( c );
	var html = d.html();
	layer.open({
		title: '处方单详情' ,
		type:1 ,
		offset: 'rt' ,
		area : ['600px' , '600px' ] ,
		'shade': 0 ,
		scrollbar:true ,
		btn:['顺时针' , '逆时针' , '关闭'] ,
		yes:function( index , layero ){
			console.log( index );
			deg+=90 ;
			deg = deg % 360 ;
			var width = '100%' ;
			var height = 'auto' ;
			var pw = layero.find('.prescriptionImage').parent().width();
			if( deg % 180 == 90 ) {
				width = 'auto' ;
				height = pw ;
			}
			layero.find('.prescriptionImage').css({
				"transform":"rotate("+deg+"deg)" ,
				'width': width , 
				'height': height 
			}) ;
		} ,
		btn2 : function( index , layero ){
			deg-=90 ;
			deg = deg % 360 ;
			var width = '100%' ;
			var height = 'auto' ;
			var pw = layero.find('.prescriptionImage').parent().width();
			if( deg % 180 == 90 ) {
				width = 'auto' ;
				height = pw ;
			}
			layero.find('.prescriptionImage').css({
				"transform":"rotate("+deg+"deg)" ,
				'width': width , 
				'height': height 
			}) ;
			return false ;
		} ,
		cancel:function( index , layero ){
			layer.close( index );
		} ,
		content: html
	});     
	  
});

@endif

$('form').on('submit' , function(){
	console.log( 'submit' );
	var prescription = [] ;
	$('.has-many-prescription-forms .item').each(function(){
		var item = {
			'id': $(this).data('id') ,
			'num' : $(this).data('num')
		};
		prescription.push( item );
	});
	$('#prescription').val( JSON.stringify( prescription ) ) ;
	
	var medicine = [] ;
	$('.has-many-medicine-forms .item').each(function(){
		var item = {
			'id': $(this).data('id') ,
			'num' : $(this).data('num')
		};
		medicine.push( item );
	});
	$('#medicine').val( JSON.stringify( medicine ) ) ;
	
	var secrettip = [] ;
	$('.has-many-secrettip-forms .item').each(function(){
		var item = {
			'id': $(this).data('id') ,
			'num' : $(this).data('num')
		};
		secrettip.push( item );
	});
	$('#secrettip').val( JSON.stringify( secrettip ) ) ;
	
});


$('.btn-check').unbind('click').bind('click' , function(){
	var that = $(this);
	if( that.data('href') ) {
		layer.confirm("所有信息确认无误才能审核，您确定审核吗？？" , function( index ){
			layer.close( index );
			$.post( that.data('href') , {'_method':'put' , '_token':LA.token } , function( data ){
				if( data.errcode === 0 ) {
					toastr.success(data.msg );
					$.pjax.reload("#pjax-container");
				} else {
					toastr.error( data.msg );
				}
			} , 'json');
		});
	}
});



$('.btn-offpay').unbind('click').bind('click' , function(){
	var that = $(this);
	if( that.data('href') ) {
		layer.confirm("在您点击确认线下收款前，请认真确认是否已经收到款项了？" , function( index ){
			layer.close( index );
			$.post( that.data('href') , {'_method':'put' , '_token':LA.token } , function( data ){
				if( data.errcode === 0 ) {
					toastr.success(data.msg );
					$.pjax.reload("#pjax-container");
				} else {
					toastr.error( data.msg );
				}
			} , 'json');
		});
	}
});



$('.btn-recivepay').unbind('click').bind('click' , function(){
	var that = $(this);
	if( that.data('href') ) {
		layer.confirm("您正将此订单改为货到付款，请确保有与用户确认付款方式？" , function( index ){
			layer.close( index );
			$.post( that.data('href') , {'_method':'put' , '_token':LA.token } , function( data ){
				if( data.errcode === 0 ) {
					toastr.success(data.msg );
					$.pjax.reload("#pjax-container");
				} else {
					toastr.error( data.msg );
				}
			} , 'json');
		});
	}
});


$('.btn-send').unbind('click').bind('click' , function(){
	var that = $(this);
	if( that.data('href') ) {
		var html = $('#_send_tpl').val();

		var index = layer.open({
			title:'请输入运单编号' ,
			content: html ,
			area: ['400px', '300px'] , //自定义文本域宽高	
			success:function( dom , index ){
				$(dom).find('select').select2();
			},
			yes:function( index , dom ){
				var ship_no = $(dom).find(".ship_no").val().trim();
				var ship_simple_name = $(dom).find('.ship_name').val();
				var ship_name = $(dom).find('.ship_name option:selected').text();
				if( !ship_simple_name ) {
					toastr.error("请选择快递公司");
			  		return false ;
				}
				if( !ship_no ) {
			  		toastr.error("请输入运单号码");
			  		return false ;
			  	}
			  	layer.close(index);
			  	var post = {
			  		'_method':'put' , 
			  		'_token':LA.token , 
			  		'ship_no' : ship_no ,
			  		'ship_simple_name':ship_simple_name ,
			  		'ship_name' : ship_name  
			  	};
			  	$.post( that.data('href') , post , function( data ){
				if( data.errcode === 0 ) {
					toastr.success(data.msg );
					$.pjax.reload("#pjax-container");
				} else {
					toastr.error( data.msg );
				}
			} , 'json');
			}
		});

	}
});




$('.btn-mark').unbind('click').bind('click' , function(){
	var that = $(this);
	if( that.data('href') ) {
		layer.prompt({
		  formType: 2,
		  value: '',
		  title: '请输入备注信息',
		  area: ['400px', '50px'] //自定义文本域宽高
		}, function(value, index, elem){
		  	layer.close(index);
		  	if( !value ) {
		  		toastr.error("请输入备注信息");
		  		return false ;
		  	}
		  	$.post( that.data('href') , {'_method':'put' , '_token':LA.token , 'mark' : value } , function( data ){
				if( data.errcode === 0 ) {
					toastr.success(data.msg );
					$.pjax.reload("#pjax-container");
				} else {
					toastr.error( data.msg );
				}
			} , 'json');
		});
	}
});

$( 'form' ).keypress(function(e) {
	if (e.which == 13) {
		return false;
	}
});

var deg = 0 ;
//旋转图片
$('.routateLeft').unbind('click').bind('click' , function(){
	deg+=90 ;
	deg = deg % 360 ;
	var width = '100%' ;
	var height = 'auto' ;
	var pw = $('.prescriptionImage').parent().width();
	if( deg % 180 == 90 ) {
		width = 'auto' ;
		height = pw ;
	}
	$('.prescriptionImage').css({
		"transform":"rotate("+deg+"deg)" ,
		'width': width , 
		'height': height 
	}) ;
});

$('.routateRight').unbind('click').bind('click' , function(){
	deg-=90 ;
	deg = deg % 360 ;
	var width = '100%' ;
	var height = 'auto' ;
	var pw = $('.prescriptionImage').parent().width();
	if( deg % 180 == 90 ) {
		width = 'auto' ;
		height = pw ;
	}
	$('.prescriptionImage').css({
		"transform":"rotate("+deg+"deg)" ,
		'width': width , 
		'height': height 
	}) ;
});