<?php
// +----------------------------------------------------------------------
// | BrightStarThink [ 亮星内容管理框架 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.gxbrightstar.com All rights reserved.
// +----------------------------------------------------------------------
// | @author CodeGenerator <codegenerator@gxbrightstar.com>
//   修改记录：
//    2016-01-29 -- CodeGenerator 自动创建文件
// +----------------------------------------------------------------------

namespace Admin\Controller;
use Think\Model;


/**
 * 控制器 - 代码示例 (说明文字-这个表是用于代码生成器演示)
 * @author CodeGenerator <codegenerator@gxbrightstar.com>
 * 修改记录：
 *     2016-01-29 00:00:00 -- CodeGenerator 创建文件
 */
class CodeSampleController extends AdminController {

    /**
     * 列表
     * @author CodeGenerator <codegenerator@gxbrightstar.com>
     */
    public function index(){
        /* 获取列表 */
        $map  = array();
        $list = $this->lists('CodeSample', $map,'id');

        $this->assign('list', $list);
        $this->meta_title = '代码示例管理';
        $this->display();
    }

    /**
     * 添加
     * @author CodeGenerator <codegenerator@gxbrightstar.com>
     */
    public function add(){
        if(IS_POST){
        	//转换提交数据
        	$_POST['specialty'] = isset($_POST['specialty']) ? implode(',', $_POST['specialty']) : '';
        	$_POST['test_time'] = strtotime($_POST['test_time']);

        	$model_add = D('CodeSample');
            $data = $model_add->create();
            if($data){
                $id = $model_add->add();
                if($id){
                    action_log('insert_code_sample', 'code_sample', $id, UID);	//记录行为
                    $this->success('新增成功', U('index'));
                } else {
                    $this->error('新增失败');
                }
            } else {
                $this->error($model_add->getError());
            }
        } else {
            //在这里获取获取其它数据，如父类、分类、下拉列表
            
            $this->assign('info', null);
        	$this->meta_title = '新增代码示例';
            $this->display();
        }
    }

    /**
     * 编辑
     * @author CodeGenerator <codegenerator@gxbrightstar.com>
     */
    public function edit($id = 0){
        if(IS_POST){
        	//转换提交数据
        	$_POST['specialty'] = isset($_POST['specialty']) ? implode(',', $_POST['specialty']) : '';
        	$_POST['test_time'] = strtotime($_POST['test_time']);
        	
            $model_edit = D('CodeSample');
            $data = $model_edit->create();
            if($data){
                if($model_edit->save()){
                    action_log('update_code_sample', 'code_sample', $data['id'], UID);	//记录行为
                    $this->success('编辑成功', U('index'));
                } else {
                    $this->error('编辑失败，还未更新数据！');
                }
            } else {
            	$this->error($model_edit->getError());
            }
        } else {
            //获取所编辑的记录
            $info = M('CodeSample')->find($id);
            if(false === $info){
                $this->error('获取编辑信息错误');
            }
            
            //在这里获取获取其它数据，如父类、分类、下拉列表
            
            $this->assign('info', $info);
            $this->meta_title = '编辑代码示例';
            $this->display();
        }
    }

    /**
     * 删除
     * @author CodeGenerator <codegenerator@gxbrightstar.com>
     */
    public function del($ids = 0){
    	empty($ids) && $this->error('参数错误！');
    	if(is_array($ids)){
    		$map['id'] = array('in', $ids);
    	}elseif (is_numeric($ids)){
    		$map['id'] = $ids;
    	}
    	$res = M('CodeSample')->where($map)->delete();
    	if($res !== false){
    		$this->success('删除成功！');
    	}else {
    		$this->error('删除失败！');
    	}
    }

}