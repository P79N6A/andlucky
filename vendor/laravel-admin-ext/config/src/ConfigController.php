<?php

namespace Encore\Admin\Config;

use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;

class ConfigController
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header('配置管理');
            $content->description('配置列表');

            $content->body($this->grid());
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     *
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {
            $content->header('配置编辑');
            $content->description('编辑');

            $content->body($this->form()->edit($id));
        });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {
            $content->header('配置');
            $content->description('新增');

            $content->body($this->form());
        });
    }

    public function grid()
    {
        return Admin::grid(ConfigModel::class, function (Grid $grid) {
            $grid->id('ID')->sortable();
            $grid->name('配置key')->display(function ($name) {
                return "<a tabindex=\"0\" class=\"btn btn-xs btn-twitter\" role=\"button\" data-toggle=\"popover\" data-html=true title=\"Usage\" data-content=\"<code>config('$name');</code>\">$name</a>";
            });
            $grid->value('配置值');
            $grid->description('描述');

            $grid->created_at('创建时间');
            $grid->updated_at('最近更新');

            $grid->filter(function ($filter) {
                $filter->disableIdFilter();
                $filter->like('name' , '配置key');
                $filter->like('value' , '配置值');
            });
        });
    }

    public function form()
    {
        return Admin::form(ConfigModel::class, function (Form $form) {
            $form->display('id', 'ID');
            $form->text('name'  , '配置Key')->rules('required');
            $form->textarea('value' , '配置值')->rules('required');
            $form->textarea('description' , '配置描述');

            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
