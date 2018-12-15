<?php
use App\Admin\Extensions\Form\Field\Ueditor;
use App\Admin\Extensions\Displayer\SLabel;
use Encore\Admin\Grid\Column;
/**
 * Laravel-admin - admin builder based on Laravel.
 * 
 * @author z-song <https://github.com/z-song>
 *        
 *         Bootstraper for Admin.
 *        
 *         Here you can remove builtin form field:
 *         Encore\Admin\Form::forget(['map', 'editor']);
 *        
 *         Or extend custom form field:
 *         Encore\Admin\Form::extend('php', PHPEditor::class);
 *        
 *         Or require js and css assets:
 *         Admin::css('/packages/prettydocs/css/styles.css');
 *         Admin::js('/packages/prettydocs/js/main.js');
 *        
 */

Encore\Admin\Form::forget([
    'editor',
    'map'
]);
Encore\Admin\Form::extend('ueditor', Ueditor::class);

Admin::css([
    '/packages/fullcalendar/fullcalendar.min.css'
]);
Admin::js([
    '/packages/moment/moment.min.js',
    '/packages/fullcalendar/fullcalendar.js',
    '/packages/fullcalendar/locale/zh-cn.js',
    '/laravel-u-editor/ueditor.config.js',
    '/laravel-u-editor/ueditor.all.min.js'

]);

Column::extend('slabel', function ($value, \Closure $closure) {
    $style = call_user_func($closure, $value);
    
    return "<span class='label label-{$style}'>$value</span>";
});