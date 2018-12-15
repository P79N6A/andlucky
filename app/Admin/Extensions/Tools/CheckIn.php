<?php
/**
 * 2017年5月6日
 * author email 349017188@qq.com
 * author qq 349017188
 * edit by sherry
 */
namespace App\Admin\Extensions\Tools;

use Encore\Admin\Admin;
use Encore\Admin\Grid\Tools\AbstractTool;

class CheckIn extends AbstractTool
{

    protected function script()
    {
        $script = <<<EOT
$('.reg-checkout').on('click' , function(){
	var that = $(this);
	layer.prompt({
		formType: 2,
		title: '请输入取号编码',
		area: ['300px', '30px']
	} ,function(value, index, elem){
		$.ajax({
            method: 'post',
            url: that.data('href') ,
            data: {
				code:value ,
                _method:'put',
                _token:LA.token,
            },
			dataType:'json' ,
            success: function (data) {
                $.pjax.reload('#pjax-container');

                if (typeof data === 'object') {
                    if (data.status) {
                        toastr.success(data.message);
                    } else {
                        toastr.error(data.message);
                    }
                }
            }
        });
				
		layer.close(index);
	});
});		
EOT;
        Admin::script($script);
    }

    /**
     * Render CreateButton.
     *
     * @return string
     */
    public function render()
    {
        $this->script();
        return <<<EOT
	
<div class="btn-group" style="margin-left: 10px">
    <a data-href="{$this->grid->resource()}/checkout" class="btn btn-sm btn-success reg-checkout">
        <i class="fa fa-save"></i>&nbsp;&nbsp;取号
    </a>
</div>
	
EOT;
    }
}