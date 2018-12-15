<?php
namespace App\Admin\Controllers;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use App\Models\AdvPosts;
use Encore\Admin\Grid\Filter;

class AdvPostsController extends Controller
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
            $content->header('用户广告管理');
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
            
            $content->header('用户广告管理');
            $content->description('编辑用户广告');
            
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
            
            $content->header('用户广告管理');
            $content->description('新增用户广告');
            
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
        return Admin::grid(AdvPosts::class, function (Grid $grid) {
            
            $grid->model()->orderBy('id', 'desc');
            
            $grid->id('ID')->sortable();
            $grid->title('标题');
            $grid->column('dd' , '666' )->display( function( $v ){
                dd( $this );
            } );
            $grid->category_id('分类');
            $grid->user_id('用户');
            $grid->total_price('总价格');
            $grid->click_price('点击价格');
            $grid->click_times('总次数');
            $grid->has_click_times('已点击');

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
            $grid->display('是否显示')->switch($states);
            $grid->created_at('创建时间');
            // $grid->updated_at('修改时间');
            $grid->disableExport();
            $grid->disableRowSelector();
            $grid->filter(function (Filter $filter) {
                $filter->like('title', '用户广告名称');
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
        return Admin::form(AdvPosts::class, function (Form $form) use ($id) {
            
            $form->text('title', '用户广告名称')->rules('required|unique:adm_category,title,' . $id, [
                'required' => '请填写用户广告名称',
                'unique' => '用户广告名称已经存在'
            ]);
            $form->text('title_en', '用户广告名称缩写');
            
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
