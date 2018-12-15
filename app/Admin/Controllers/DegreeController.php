<?php
namespace App\Admin\Controllers;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use App\Models\Degree;
use Encore\Admin\Grid\Filter;

class DegreeController extends Controller
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
            
            $content->header('学历管理');
            $content->description('列表');
            
            $content->body($this->grid());
        });
    }

    /**
     * Edit interface.
     *
     * @param
     *            $id
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {
            
            $content->header('学历管理');
            $content->description('编辑学历');
            
            $content->body($this->form()
                ->edit($id));
        });
    }

    public function update($id)
    {
        return $this->form($id)->update($id);
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {
            
            $content->header('学历管理');
            $content->description('新增学历');
            
            $content->body($this->form());
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(Degree::class, function (Grid $grid) {
            
            $grid->model()->orderBy('id', 'desc');
            
            $grid->id('ID')->sortable();
            $grid->title('学历名称');
            $grid->sort('排序')
                ->editable()
                ->sortable();
            $states = [
                'on' => [
                    'value' => 1,
                    'text' => '打开',
                    'color' => 'primary'
                ],
                'off' => [
                    'value' => 0,
                    'text' => '关闭',
                    'color' => 'default'
                ]
            ];
            $grid->display('是否启用')->switch($states);
            $grid->created_at('创建时间');
            $grid->updated_at('修改时间');
            
            $grid->disableExport();
            $grid->disableRowSelector();
            $grid->filter(function (Filter $filter) {
                $filter->like('title', '学历名称');
            });
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form($id = null)
    {
        return Admin::form(Degree::class, function (Form $form) use ($id) {
            
            $form->text('title', '学历名称')->rules('required|unique:sys_degrees,title,' . $id, [
                'required' => '请填写学历名称',
                'unique' => '学历名称已经存在'
            ]);
            
            $form->number('sort', '排序')->default(10);
            $states = [
                'on' => [
                    'value' => 1,
                    'text' => '打开',
                    'color' => 'primary'
                ],
                'off' => [
                    'value' => 0,
                    'text' => '关闭',
                    'color' => 'default'
                ]
            ];
            $form->switch('display', '是否显示')
                ->states($states)
                ->default(1);
            
            // $form->display('id', 'ID');
            
            // $form->display('created_at', 'Created At');
            // $form->display('updated_at', 'Updated At');
        });
    }
}
