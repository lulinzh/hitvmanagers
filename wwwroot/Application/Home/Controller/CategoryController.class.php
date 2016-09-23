<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Home\Controller;
use Home\Controller\Base\HomeController;

/**
 * 栏目模型控制器
 * 栏目模型列表和详情
 */
class CategoryController extends HomeController {

	/* 文档模型列表页 */
	public function index($p = 1){
		/* 分类信息 */
		$category = $this->category();
		/* 模板赋值并渲染模板 */
		$this->assign('category', $category);
		$this->assign('position', get_category_pos($category['id']));
				
		if($category['ispart'] == 0){	//最终列表栏目(允许在本栏目发布文档，并生成文档列表)  
			/* 获取当前分类列表 */
			$category_ids = $category['pid']==0 ? get_category_son($category['id'], 'id') : $category['id'];	//顶级频道栏目，则列出所有子栏目
			$list = D('Document')->page($p, $category['list_row'])->lists($category_ids);
			if(false === $list){
				$this->error('获取列表数据失败！');
			}
			$this->assign('category_ids', $category_ids);		//分页用的
			$this->assign('list', $list);
				
			$this->display(empty($category['template_lists']) ? 'lists' : $category['template_lists']);
		} else if($category['ispart'] == 1) {	//频道封面(栏目本身不允许发布文档)
			$this->display(empty($category['template_index']) ? 'index' : $category['template_index']);
		} else if($category['ispart'] == 2) {	//外部连接(在"内容"处填写网址)
			redirect($category['content']);
		} else {
			$this->error('栏目属性错误！');
		}
	}

}
