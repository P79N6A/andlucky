<?php
namespace App\Admin\Extensions\Tools;

use Encore\Admin\Admin;
use Encore\Admin\Grid\Tools\AbstractTool;
use Illuminate\Support\Facades\Request;

class WithdrawTool extends AbstractTool
{

    public function script()
    {
        $url = Request::fullUrlWithQuery([
            'status' => '_status_'
        ]);
        
        return <<<EOT

$('input:radio.user-gender').change(function () {

    var url = "$url".replace('_status_', $(this).val());

    $.pjax({container:'#pjax-container', url: url });

});

EOT;
    }

    public function render()
    {
        Admin::script($this->script());
        
        $options = [
            '-1' => '全部',
            '0' => '未付款',
            '1' => '已付款',
            '2' => '审核不通过'
        ];
        
        return view('admin.tools.withdraw', compact('options'));
    }
}