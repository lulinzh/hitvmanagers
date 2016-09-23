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

namespace Admin\Model;
use Think\Model;


/**
 * 模型 - 代码示例 (说明文字-这个表是用于代码生成器演示)
 * @author CodeGenerator <codegenerator@gxbrightstar.com>
 * 修改记录：
 *     2016-01-29 00:00:00 -- CodeGenerator 创建文件
 */
class CodeSampleModel extends Model {	
	
	//后端验证
    protected $_validate = array(
    		array('code', 'require', '编码不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    		array('title', 'require', '标题不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    		array('phone', '/1[3458]{1}\d{9}$/', '手机号格式不正确', self::VALUE_VALIDATE, 'regex', self::MODEL_BOTH),
    		array('email', '/^\\w+((-\\w+)|(\\.\\w+))*\\@[A-Za-z0-9]+((\\.|-)[A-Za-z0-9]+)*\\.[A-Za-z0-9]+$/', '电子邮箱格式不正确', self::VALUE_VALIDATE, 'regex', self::MODEL_BOTH),
    		array('sort', '/^\d+$/', '排列序号必须是整数', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    		array('from', 'require', '来源必须选择', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    		array('specialty', 'require', '特长必须选择', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
    );

    //自动填充 (会覆盖表单数据)
    protected $_auto = array(
    		array('update_time', NOW_TIME, self::MODEL_BOTH),
    		array('create_time', 'time', self::MODEL_INSERT, 'function'),
    );

}
