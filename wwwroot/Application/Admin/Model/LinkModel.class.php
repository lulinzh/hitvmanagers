<?php
// +----------------------------------------------------------------------
// | BrightStarThink [ 亮星内容管理框架 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.gxbrightstar.com All rights reserved.
// +----------------------------------------------------------------------
// | @author CodeGenerator <codegenerator@gxbrightstar.com>
//   修改记录：
//    2016-01-31 -- CodeGenerator 自动创建文件
// +----------------------------------------------------------------------

namespace Admin\Model;
use Think\Model;


/**
 * 模型 - 网站连接 (网站配置连接，如友情连接、横幅广告、各种广告)
 * @author CodeGenerator <codegenerator@gxbrightstar.com>
 * 修改记录：
 *     2016-01-31 00:00:00 -- CodeGenerator 创建文件
 */
class LinkModel extends Model {	
	
	//后端验证
    protected $_validate = array(
    		array('group', 'require', '分组不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    		array('url', 'require', '连接地址不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    		array('sort', '/^\d+$/', '排列序号必须是整数', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    );

    //自动填充 (会覆盖表单数据)
    protected $_auto = array(
    		array('update_time', NOW_TIME, self::MODEL_BOTH),
    		array('create_time', 'time', self::MODEL_INSERT, 'function'),
    );

}
