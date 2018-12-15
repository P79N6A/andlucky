<?php
namespace App\Admin\Extensions\Tools;

use Encore\Admin\Admin;
use Encore\Admin\Grid\Tools\AbstractTool;
use Illuminate\Support\Facades\Request;

class FinanceOrderTool extends AbstractTool
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
            'all' => '全部',
            'wait' => '未支付',
            'onlinepay' => '线上已支付',
            'offlinepay' => '线下已支付',
            'afterpay' => '货到付款已支付'
        ];
        
        return view('admin.tools.orders', compact('options'));
    }
}