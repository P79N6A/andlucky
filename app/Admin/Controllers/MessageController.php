<?php
namespace App\Admin\Controllers;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use App\Models\Message;
use Encore\Admin\Grid\Filter;
use Illuminate\Support\Facades\Request;
use App\User;
use Carbon;
use Encore\Admin\Grid\Displayers\Actions;

class MessageController extends Controller
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
            
            $content->header('消息管理');
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
            
            $content->header('职业管理');
            $content->description('编辑职业');
            
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
            
            $content->header('消息管理');
            $content->description('发送消息');
            
            $content->body($this->form());
        });
    }

    /**
     *
     * @param Request $request
     */
    public function store(Request $request)
    {
        $form = $this->form();
        // $form->ignore(['mobile']) ;
        $form->saving(function (Form $form) {
            $msg = $form->input('message');
            $mobile = $form->input('mobile');
            $mobile = str_replace('，', ',', $mobile);
            $user = User::whereIn('mobile', explode(',', $mobile))->pluck('id', 'id');
            $timestamp = \Carbon\Carbon::now();
            if ($user) {
                // TODO
                $inserts = [];
                foreach ($user as $id) {
                    $inserts[] = [
                        'user_id' => $id,
                        'message' => $msg,
                        'created_at' => $timestamp,
                        'updated_at' => $timestamp
                    ];
                }
                $result = \DB::table('messages')->insert($inserts);
                if ($result) {
                    admin_toastr('发送完成');
                    return redirect()->to(admin_url('message'));
                } else {
                    admin_toastr('发送失败');
                    return back();
                }
            } else {
                admin_toastr('没有对应的用户');
                return back();
            }
            dd($mobile);
        });
        return $form->store();
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(Message::class, function (Grid $grid) {
            
            $grid->model()->orderBy('id', 'desc');
            
            $grid->id('ID')->sortable();
            $grid->column('user.nickname', '用户名称');
            $grid->message('消息内容')->display(function ($v) {
                return str_limit($v, 100);
            });
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
            $grid->read_at('阅读时间');
            $grid->created_at('创建时间');
            $grid->actions(function (Actions $action) {
                $action->disableEdit();
            });
            $grid->disableExport();
            $grid->disableRowSelector();
            $grid->filter(function (Filter $filter) {
                // $filter->like('title' , '用户昵称');
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
        return Admin::form(Message::class, function (Form $form) use ($id) {
            $form->textarea('mobile', '手机号码')
                ->rules('required', [
                'required' => '请填写用户手机号码'
            ])
                ->help('多个用户手机号用逗号","隔开');
            $form->textarea('message', '信息内容')->rules('required', [
                'required' => '请填写消息内容'
            ]);
            // $form->display('id', 'ID');
            // $form->display('created_at', 'Created At');
            // $form->display('updated_at', 'Updated At');
        });
    }
}
