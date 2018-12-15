<?php
namespace App\Admin\Extensions\Tools;

use Encore\Admin\Admin;
use Encore\Admin\Grid\Tools\AbstractTool;
use Illuminate\Support\Facades\Request;

class OrderTool extends AbstractTool
{

    public function script()
    {
        $url = Request::fullUrlWithQuery([
            'which' => '_trash_'
        ]);
        
        return <<<EOT

$('input:radio.user-gender').change(function () {

    var url = "$url".replace('_trash_', $(this).val());

    $.pjax({container:'#pjax-container', url: url });

});

EOT;
    }

    public function render()
    {
        Admin::script($this->script());
        
        $options = [
            'hall' => '大厅',
            'waitcheck' => '待审核',
            'waitpay' => '待支付',
            'waitsend' => '已支付待发货',
            'hassend' => '已支付已发货',
            'notpaysend' => '货到付款已发货',
            'notpaynotsend' => '货到付款未发货',
            // 'aftersendnotpay' => '货到付款未收款' ,
            'aftersendhaspay' => '货到付款已收款'
        ];
        if (auth()->guard('admin')
            ->user()
            ->inRoles([
            'administrator'
        ])) {
            $options['trashed'] = '回收站';
        }
        
        return view('admin.tools.orders', compact('options'));
    }
}