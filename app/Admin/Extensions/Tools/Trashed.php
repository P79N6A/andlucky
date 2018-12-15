<?php
namespace App\Admin\Extensions\Tools;

use Encore\Admin\Admin;
use Encore\Admin\Grid\Tools\AbstractTool;
use Illuminate\Support\Facades\Request;

class Trashed extends AbstractTool
{

    public function script()
    {
        $url = Request::fullUrlWithQuery([
            'trash' => '_trash_'
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
            '0' => '全部',
            '1' => '回收站'
        ];
        
        return view('admin.tools.trashed', compact('options'));
    }
}