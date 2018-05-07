<?php

namespace App\Admin\Controllers;

use App\Question;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\MessageBag;
use Maatwebsite\Excel\Facades\Excel;

class QuestionController extends Controller
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
            $content->header('题目管理');
            $content->description('上传以及查看题目');
            $content->body($this->grid());
        });
    }

    public function delete(Request $request){
        $data = $request->all();
        return $data;
    }

    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {
            $content->header('题目管理');
            $content->description('上传以及查看题目');
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

            $content->header('上传题目');
            $content->description('新增题目');

            $content->body($this->import());
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(Question::class, function (Grid $grid) {
            $grid->actions(function ($actions) {
                $actions->disableDelete();
                // prepend一个操作
//                $actions->prepend('<a href=""><i class="fa fa-paper-plane"></i></a>');
            });

            $grid->id('ID')->sortable();
            $grid->column('title','问题')->sortable();
            $grid->column('answer','答案')->sortable();

            $grid->created_at();
            $grid->updated_at();

        });
    }


    protected function import(){

        return Admin::form(Question::class, function (Form $form) {

            $form->file('file','file');
            $form->setAction('import');
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(Question::class, function (Form $form) {

            $form->display('id', 'ID');
            $form->display('title', '问题');
            $form->display('answer', '答案');
            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }

    /**
     * 导入题目和答案
     */
    public function importChoose(Request $request){
//        return $request->all();
        if(!$request->hasFile('file')){
            exit('上传文件为空！');
        }
        $file = $_FILES;
        $params['excel_file_path']= $file['file']['tmp_name'];
        Excel::load($params['excel_file_path'],function($reader) use( &$res ) {
            $reader = $reader->getSheet(0);
            $res = $reader->toArray();
        });
//        dd(count($res));
//        $count = count($res);
        for($i = 1;$i<count($res);$i++){
//            var_dump($res[$i]);
                $data = [
                    'title' => $res[$i][1],//题目
                    'answer' => $res[$i][2],//答案
                    'question_type' =>1,//题目类型
                ];//登录信息新增
            $qres = Question::questionAdd($data);
//                $qres = DB::table('questions')->insert($data);
                if(!$qres){
                    return ['code'=>500,'msg'=>'添加失败'];
                }
        }
        $success = new MessageBag([
            'title'   => '上传成功',
            'message' => '<a href="./">点此处返回查看</a>',
        ]);
//        $success = new MessageBag([
//
//        ]);

        return back()->with(compact('success'));
    }

}
