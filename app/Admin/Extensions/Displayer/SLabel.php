<?php
namespace App\Admin\Extensions\Displayer;

use Encore\Admin\Admin;
use Encore\Admin\Grid\Displayers\AbstractDisplayer;

class SLabel extends AbstractDisplayer
{

    public function display(\Closure $closure)
    {
        $style = call_user_func($closure, $this->value);
        
        return "<span class='label label-{$style}'>{$this->value}</span>";
    }
}
