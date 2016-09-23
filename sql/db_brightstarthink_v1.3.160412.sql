/*
Navicat MySQL Data Transfer

Source Server         : localhost:8694 root/123456
Source Server Version : 50610
Source Host           : localhost:8694
Source Database       : db_brightstarthink

Target Server Type    : MYSQL
Target Server Version : 50610
File Encoding         : 65001

Date: 2016-04-12 11:18:13
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `lx_action`
-- ----------------------------
DROP TABLE IF EXISTS `lx_action`;
CREATE TABLE `lx_action` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` char(30) NOT NULL DEFAULT '' COMMENT '行为唯一标识',
  `title` char(80) NOT NULL DEFAULT '' COMMENT '行为说明',
  `remark` char(140) NOT NULL DEFAULT '' COMMENT '行为描述',
  `rule` text COMMENT '行为规则',
  `log` text COMMENT '日志规则',
  `type` tinyint(2) unsigned NOT NULL DEFAULT '1' COMMENT '类型',
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '状态',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='系统行为表';

-- ----------------------------
-- Records of lx_action
-- ----------------------------
INSERT INTO `lx_action` VALUES ('1', 'user_login', '用户登录', '积分+10，每天一次', 'table:member|field:score|condition:uid={$self} AND status>-1|rule:score+10|cycle:24|max:1;', '[user|get_nickname]在[time|time_format]登录了后台', '1', '1', '1387181220');
INSERT INTO `lx_action` VALUES ('2', 'add_article', '发布文章', '积分+5，每天上限5次', 'table:member|field:score|condition:uid={$self}|rule:score+5|cycle:24|max:5', '', '2', '0', '1380173180');
INSERT INTO `lx_action` VALUES ('3', 'review', '评论', '评论积分+1，无限制', 'table:member|field:score|condition:uid={$self}|rule:score+1', '', '2', '1', '1383285646');
INSERT INTO `lx_action` VALUES ('4', 'add_document', '发表文档', '积分+10，每天上限5次', 'table:member|field:score|condition:uid={$self}|rule:score+10|cycle:24|max:5', '[user|get_nickname]在[time|time_format]发表了一篇文章。\r\n表[model]，记录编号[record]。', '2', '0', '1386139726');
INSERT INTO `lx_action` VALUES ('5', 'add_document_topic', '发表讨论', '积分+5，每天上限10次', 'table:member|field:score|condition:uid={$self}|rule:score+5|cycle:24|max:10', '', '2', '0', '1383285551');
INSERT INTO `lx_action` VALUES ('6', 'update_config', '更新配置', '新增或修改或删除配置', '', '', '1', '1', '1383294988');
INSERT INTO `lx_action` VALUES ('7', 'update_model', '更新模型', '新增或修改模型', '', '', '1', '1', '1383295057');
INSERT INTO `lx_action` VALUES ('8', 'update_attribute', '更新属性', '新增或更新或删除属性', '', '', '1', '1', '1383295963');
INSERT INTO `lx_action` VALUES ('9', 'update_channel', '更新导航', '新增或修改或删除导航', '', '', '1', '1', '1383296301');
INSERT INTO `lx_action` VALUES ('10', 'update_menu', '更新菜单', '新增或修改或删除菜单', '', '', '1', '1', '1383296392');
INSERT INTO `lx_action` VALUES ('11', 'update_category', '更新分类', '新增或修改或删除分类', '', '', '1', '1', '1383296765');
INSERT INTO `lx_action` VALUES ('24', 'delete_code_sample', '删除代码示例', '删除代码示例', '', '[user|get_nickname]在[time|time_format]删除代码示例', '1', '1', '1453987069');
INSERT INTO `lx_action` VALUES ('22', 'insert_code_sample', '新增代码示例', '新增代码示例', '', '[user|get_nickname]在[time|time_format]新增代码示例', '1', '1', '1453987065');
INSERT INTO `lx_action` VALUES ('23', 'update_code_sample', '更新代码示例', '更新代码示例', '', '[user|get_nickname]在[time|time_format]更新代码示例', '1', '1', '1453987067');
INSERT INTO `lx_action` VALUES ('25', 'insert_link', '新增网站连接', '新增网站连接', '', '[user|get_nickname]在[time|time_format]新增网站连接', '1', '1', '1454231740');
INSERT INTO `lx_action` VALUES ('26', 'update_link', '更新网站连接', '更新网站连接', '', '[user|get_nickname]在[time|time_format]更新网站连接', '1', '1', '1454231740');
INSERT INTO `lx_action` VALUES ('27', 'delete_link', '删除网站连接', '删除网站连接', '', '[user|get_nickname]在[time|time_format]删除网站连接', '1', '1', '1454231740');
INSERT INTO `lx_action` VALUES ('28', 'user_login_fail', '用户登录失败', '用户登录失败，记录用户尝试密码次数', '', '[user|get_nickname]在[time|time_format]尝试登录失败', '1', '1', '1455762830');
INSERT INTO `lx_action` VALUES ('29', 'user_profile', '修改资料', '在管理中心修改资料', '', '[user|get_nickname]在[time|time_format]修改了用户资料', '1', '1', '1387181220');

-- ----------------------------
-- Table structure for `lx_action_log`
-- ----------------------------
DROP TABLE IF EXISTS `lx_action_log`;
CREATE TABLE `lx_action_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `action_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '行为id',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '执行用户id',
  `action_ip` bigint(20) NOT NULL COMMENT '执行行为者ip',
  `model` varchar(50) NOT NULL DEFAULT '' COMMENT '触发行为的表',
  `record_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '触发行为的数据id',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '日志备注',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '执行行为的时间',
  PRIMARY KEY (`id`),
  KEY `action_ip_ix` (`action_ip`),
  KEY `action_id_ix` (`action_id`),
  KEY `user_id_ix` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=526 DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED COMMENT='行为日志表';

-- ----------------------------
-- Records of lx_action_log
-- ----------------------------

-- ----------------------------
-- Table structure for `lx_addons`
-- ----------------------------
DROP TABLE IF EXISTS `lx_addons`;
CREATE TABLE `lx_addons` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(40) NOT NULL COMMENT '插件名或标识',
  `title` varchar(20) NOT NULL DEFAULT '' COMMENT '中文名',
  `description` text COMMENT '插件描述',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `config` text COMMENT '配置',
  `author` varchar(40) DEFAULT '' COMMENT '作者',
  `version` varchar(20) DEFAULT '' COMMENT '版本号',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '安装时间',
  `has_adminlist` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否有后台列表',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COMMENT='插件表';

-- ----------------------------
-- Records of lx_addons
-- ----------------------------
INSERT INTO `lx_addons` VALUES ('15', 'EditorForAdmin', '后台编辑器', '用于增强整站长文本的输入和显示', '1', '{\"editor_type\":\"2\",\"editor_wysiwyg\":\"1\",\"editor_height\":\"500px\",\"editor_resize_type\":\"1\"}', 'thinkphp', '0.1', '1383126253', '0');
INSERT INTO `lx_addons` VALUES ('2', 'SiteStat', '站点统计信息', '统计站点的基础信息', '1', '{\"title\":\"\\u7cfb\\u7edf\\u4fe1\\u606f\",\"width\":\"1\",\"display\":\"1\",\"status\":\"0\"}', 'thinkphp', '0.1', '1379512015', '0');
INSERT INTO `lx_addons` VALUES ('3', 'DevTeam', '开发团队信息', '开发团队成员信息', '1', '{\"title\":\"OneThink\\u5f00\\u53d1\\u56e2\\u961f\",\"width\":\"2\",\"display\":\"1\"}', 'thinkphp', '0.1', '1379512022', '0');
INSERT INTO `lx_addons` VALUES ('4', 'SystemInfo', '系统环境信息', '用于显示一些服务器的信息', '1', '{\"title\":\"\\u7cfb\\u7edf\\u4fe1\\u606f\",\"width\":\"2\",\"display\":\"1\"}', 'thinkphp', '0.1', '1379512036', '0');
INSERT INTO `lx_addons` VALUES ('5', 'Editor', '前台编辑器', '用于增强整站长文本的输入和显示', '1', '{\"editor_type\":\"2\",\"editor_wysiwyg\":\"1\",\"editor_height\":\"300px\",\"editor_resize_type\":\"1\"}', 'thinkphp', '0.1', '1379830910', '0');
INSERT INTO `lx_addons` VALUES ('6', 'Attachment', '附件', '用于文档模型上传附件', '1', 'null', 'thinkphp', '0.1', '1379842319', '1');
INSERT INTO `lx_addons` VALUES ('9', 'SocialComment', '通用社交化评论', '集成了各种社交化评论插件，轻松集成到系统中。', '1', '{\"comment_type\":\"1\",\"comment_uid_youyan\":\"\",\"comment_short_name_duoshuo\":\"\",\"comment_data_list_duoshuo\":\"\"}', 'thinkphp', '0.1', '1380273962', '0');

-- ----------------------------
-- Table structure for `lx_attachment`
-- ----------------------------
DROP TABLE IF EXISTS `lx_attachment`;
CREATE TABLE `lx_attachment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `title` char(30) NOT NULL DEFAULT '' COMMENT '附件显示名',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '附件类型',
  `source` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '资源ID',
  `record_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '关联记录ID',
  `download` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '下载次数',
  `size` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '附件大小',
  `dir` int(12) unsigned NOT NULL DEFAULT '0' COMMENT '上级目录ID',
  `sort` int(8) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`),
  KEY `idx_record_status` (`record_id`,`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='附件表';

-- ----------------------------
-- Records of lx_attachment
-- ----------------------------

-- ----------------------------
-- Table structure for `lx_attribute`
-- ----------------------------
DROP TABLE IF EXISTS `lx_attribute`;
CREATE TABLE `lx_attribute` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '字段名',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '字段注释',
  `field` varchar(100) NOT NULL DEFAULT '' COMMENT '字段定义',
  `type` varchar(20) NOT NULL DEFAULT '' COMMENT '数据类型',
  `value` varchar(100) NOT NULL DEFAULT '' COMMENT '字段默认值',
  `remark` varchar(100) NOT NULL DEFAULT '' COMMENT '备注',
  `is_show` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否显示',
  `extra` varchar(255) NOT NULL DEFAULT '' COMMENT '参数',
  `model_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '模型id',
  `is_must` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否必填',
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '状态',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `validate_rule` varchar(255) NOT NULL DEFAULT '',
  `validate_time` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `error_info` varchar(100) NOT NULL DEFAULT '',
  `validate_type` varchar(25) NOT NULL DEFAULT '',
  `auto_rule` varchar(100) NOT NULL DEFAULT '',
  `auto_time` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `auto_type` varchar(25) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `model_id` (`model_id`)
) ENGINE=MyISAM AUTO_INCREMENT=42 DEFAULT CHARSET=utf8 COMMENT='模型属性表';

-- ----------------------------
-- Records of lx_attribute
-- ----------------------------
INSERT INTO `lx_attribute` VALUES ('1', 'uid', '用户ID', 'int(10) unsigned NOT NULL ', 'num', '0', '', '0', '', '1', '0', '1', '1384508362', '1383891233', '', '0', '', '', '', '0', '');
INSERT INTO `lx_attribute` VALUES ('2', 'name', '标识', 'char(40) NOT NULL ', 'string', '', '同一根节点下标识不重复', '1', '', '1', '0', '1', '1383894743', '1383891233', '', '0', '', '', '', '0', '');
INSERT INTO `lx_attribute` VALUES ('3', 'title', '标题', 'char(80) NOT NULL ', 'string', '', '文档标题', '1', '', '1', '0', '1', '1383894778', '1383891233', '', '0', '', '', '', '0', '');
INSERT INTO `lx_attribute` VALUES ('4', 'category_id', '所属分类', 'int(10) unsigned NOT NULL ', 'string', '', '', '0', '', '1', '0', '1', '1384508336', '1383891233', '', '0', '', '', '', '0', '');
INSERT INTO `lx_attribute` VALUES ('5', 'description', '描述', 'char(140) NOT NULL ', 'textarea', '', '', '1', '', '1', '0', '1', '1383894927', '1383891233', '', '0', '', '', '', '0', '');
INSERT INTO `lx_attribute` VALUES ('6', 'root', '根节点', 'int(10) unsigned NOT NULL ', 'num', '0', '该文档的顶级文档编号', '0', '', '1', '0', '1', '1384508323', '1383891233', '', '0', '', '', '', '0', '');
INSERT INTO `lx_attribute` VALUES ('7', 'pid', '所属ID', 'int(10) unsigned NOT NULL ', 'num', '0', '父文档编号', '0', '', '1', '0', '1', '1384508543', '1383891233', '', '0', '', '', '', '0', '');
INSERT INTO `lx_attribute` VALUES ('8', 'model_id', '内容模型ID', 'tinyint(3) unsigned NOT NULL ', 'num', '0', '该文档所对应的模型', '0', '', '1', '0', '1', '1384508350', '1383891233', '', '0', '', '', '', '0', '');
INSERT INTO `lx_attribute` VALUES ('9', 'type', '内容类型', 'tinyint(3) unsigned NOT NULL ', 'select', '2', '', '1', '1:目录\r\n2:主题\r\n3:段落', '1', '0', '1', '1384511157', '1383891233', '', '0', '', '', '', '0', '');
INSERT INTO `lx_attribute` VALUES ('10', 'position', '推荐位', 'smallint(5) unsigned NOT NULL ', 'checkbox', '0', '多个推荐则将其推荐值相加', '1', '[DOCUMENT_POSITION]', '1', '0', '1', '1383895640', '1383891233', '', '0', '', '', '', '0', '');
INSERT INTO `lx_attribute` VALUES ('11', 'link_id', '外链', 'int(10) unsigned NOT NULL ', 'num', '0', '0-非外链，大于0-外链ID,需要函数进行链接与编号的转换', '1', '', '1', '0', '1', '1383895757', '1383891233', '', '0', '', '', '', '0', '');
INSERT INTO `lx_attribute` VALUES ('12', 'cover_id', '封面', 'int(10) unsigned NOT NULL ', 'picture', '0', '0-无封面，大于0-封面图片ID，需要函数处理', '1', '', '1', '0', '1', '1384147827', '1383891233', '', '0', '', '', '', '0', '');
INSERT INTO `lx_attribute` VALUES ('13', 'display', '可见性', 'tinyint(3) unsigned NOT NULL ', 'radio', '1', '', '1', '0:不可见\r\n1:所有人可见', '1', '0', '1', '1386662271', '1383891233', '', '0', '', 'regex', '', '0', 'function');
INSERT INTO `lx_attribute` VALUES ('14', 'deadline', '截至时间', 'int(10) unsigned NOT NULL ', 'datetime', '0', '0-永久有效', '1', '', '1', '0', '1', '1387163248', '1383891233', '', '0', '', 'regex', '', '0', 'function');
INSERT INTO `lx_attribute` VALUES ('15', 'attach', '附件数量', 'tinyint(3) unsigned NOT NULL ', 'num', '0', '', '0', '', '1', '0', '1', '1387260355', '1383891233', '', '0', '', 'regex', '', '0', 'function');
INSERT INTO `lx_attribute` VALUES ('16', 'view', '浏览量', 'int(10) unsigned NOT NULL ', 'num', '0', '', '1', '', '1', '0', '1', '1383895835', '1383891233', '', '0', '', '', '', '0', '');
INSERT INTO `lx_attribute` VALUES ('17', 'comment', '评论数', 'int(10) unsigned NOT NULL ', 'num', '0', '', '1', '', '1', '0', '1', '1383895846', '1383891233', '', '0', '', '', '', '0', '');
INSERT INTO `lx_attribute` VALUES ('18', 'extend', '扩展统计字段', 'int(10) unsigned NOT NULL ', 'num', '0', '根据需求自行使用', '0', '', '1', '0', '1', '1384508264', '1383891233', '', '0', '', '', '', '0', '');
INSERT INTO `lx_attribute` VALUES ('19', 'level', '优先级', 'int(10) unsigned NOT NULL ', 'num', '0', '越高排序越靠前', '1', '', '1', '0', '1', '1383895894', '1383891233', '', '0', '', '', '', '0', '');
INSERT INTO `lx_attribute` VALUES ('20', 'create_time', '创建时间', 'int(10) unsigned NOT NULL ', 'datetime', '0', '', '1', '', '1', '0', '1', '1383895903', '1383891233', '', '0', '', '', '', '0', '');
INSERT INTO `lx_attribute` VALUES ('21', 'update_time', '更新时间', 'int(10) unsigned NOT NULL ', 'datetime', '0', '', '0', '', '1', '0', '1', '1384508277', '1383891233', '', '0', '', '', '', '0', '');
INSERT INTO `lx_attribute` VALUES ('22', 'status', '数据状态', 'tinyint(4) NOT NULL ', 'radio', '0', '', '0', '-1:删除\r\n0:禁用\r\n1:正常\r\n2:待审核\r\n3:草稿', '1', '0', '1', '1384508496', '1383891233', '', '0', '', '', '', '0', '');
INSERT INTO `lx_attribute` VALUES ('23', 'parse', '内容解析类型', 'tinyint(3) unsigned NOT NULL ', 'select', '0', '', '0', '0:html\r\n1:ubb\r\n2:markdown', '2', '0', '1', '1384511049', '1383891243', '', '0', '', '', '', '0', '');
INSERT INTO `lx_attribute` VALUES ('24', 'content', '文章内容', 'text NOT NULL ', 'editor', '', '', '1', '', '2', '0', '1', '1383896225', '1383891243', '', '0', '', '', '', '0', '');
INSERT INTO `lx_attribute` VALUES ('25', 'template', '详情页显示模板', 'varchar(100) NOT NULL ', 'string', '', '参照display方法参数的定义', '1', '', '2', '0', '1', '1383896190', '1383891243', '', '0', '', '', '', '0', '');
INSERT INTO `lx_attribute` VALUES ('26', 'bookmark', '收藏数', 'int(10) unsigned NOT NULL ', 'num', '0', '', '1', '', '2', '0', '1', '1383896103', '1383891243', '', '0', '', '', '', '0', '');
INSERT INTO `lx_attribute` VALUES ('27', 'parse', '内容解析类型', 'tinyint(3) unsigned NOT NULL ', 'select', '0', '', '0', '0:html\r\n1:ubb\r\n2:markdown', '3', '0', '1', '1387260461', '1383891252', '', '0', '', 'regex', '', '0', 'function');
INSERT INTO `lx_attribute` VALUES ('28', 'content', '下载详细描述', 'text NOT NULL ', 'editor', '', '', '1', '', '3', '0', '1', '1383896438', '1383891252', '', '0', '', '', '', '0', '');
INSERT INTO `lx_attribute` VALUES ('29', 'template', '详情页显示模板', 'varchar(100) NOT NULL ', 'string', '', '', '1', '', '3', '0', '1', '1383896429', '1383891252', '', '0', '', '', '', '0', '');
INSERT INTO `lx_attribute` VALUES ('30', 'file_id', '文件ID', 'int(10) unsigned NOT NULL ', 'file', '0', '需要函数处理', '1', '', '3', '0', '1', '1383896415', '1383891252', '', '0', '', '', '', '0', '');
INSERT INTO `lx_attribute` VALUES ('31', 'download', '下载次数', 'int(10) unsigned NOT NULL ', 'num', '0', '', '1', '', '3', '0', '1', '1383896380', '1383891252', '', '0', '', '', '', '0', '');
INSERT INTO `lx_attribute` VALUES ('32', 'size', '文件大小', 'bigint(20) unsigned NOT NULL ', 'num', '0', '单位bit', '1', '', '3', '0', '1', '1383896371', '1383891252', '', '0', '', '', '', '0', '');

-- ----------------------------
-- Table structure for `lx_auth_extend`
-- ----------------------------
DROP TABLE IF EXISTS `lx_auth_extend`;
CREATE TABLE `lx_auth_extend` (
  `group_id` mediumint(10) unsigned NOT NULL COMMENT '用户id',
  `extend_id` mediumint(8) unsigned NOT NULL COMMENT '扩展表中数据的id',
  `type` tinyint(1) unsigned NOT NULL COMMENT '扩展类型标识 1:栏目分类权限;2:模型权限',
  UNIQUE KEY `group_extend_type` (`group_id`,`extend_id`,`type`),
  KEY `uid` (`group_id`),
  KEY `group_id` (`extend_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户组与分类的对应关系表';

-- ----------------------------
-- Records of lx_auth_extend
-- ----------------------------
INSERT INTO `lx_auth_extend` VALUES ('1', '1', '2');
INSERT INTO `lx_auth_extend` VALUES ('1', '2', '2');
INSERT INTO `lx_auth_extend` VALUES ('1', '3', '2');
INSERT INTO `lx_auth_extend` VALUES ('2', '1', '1');
INSERT INTO `lx_auth_extend` VALUES ('2', '2', '1');
INSERT INTO `lx_auth_extend` VALUES ('2', '41', '1');
INSERT INTO `lx_auth_extend` VALUES ('2', '42', '1');

-- ----------------------------
-- Table structure for `lx_auth_group`
-- ----------------------------
DROP TABLE IF EXISTS `lx_auth_group`;
CREATE TABLE `lx_auth_group` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户组id,自增主键',
  `module` varchar(20) NOT NULL DEFAULT '' COMMENT '用户组所属模块',
  `type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '组类型',
  `title` char(20) NOT NULL DEFAULT '' COMMENT '用户组中文名称',
  `description` varchar(80) NOT NULL DEFAULT '' COMMENT '描述信息',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '用户组状态：为1正常，为0禁用,-1为删除',
  `rules` varchar(500) NOT NULL DEFAULT '' COMMENT '用户组拥有的规则id，多个规则 , 隔开',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of lx_auth_group
-- ----------------------------
INSERT INTO `lx_auth_group` VALUES ('1', 'admin', '1', '管理员用户组', '', '1', '1,2,3,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,54,55,61,62,63,64,65,66,67,68,69,70,71,72,73,74,79,80,82,88,94,95,107,108,109,110,211,214,217');
INSERT INTO `lx_auth_group` VALUES ('2', 'admin', '1', '内容编辑用户组', '', '1', '1,2,7,8,9,10,11,12,13,14,15,16,17,18,26,27,74,79,107,108,109,110,211,217');

-- ----------------------------
-- Table structure for `lx_auth_group_access`
-- ----------------------------
DROP TABLE IF EXISTS `lx_auth_group_access`;
CREATE TABLE `lx_auth_group_access` (
  `uid` int(10) unsigned NOT NULL COMMENT '用户id',
  `group_id` mediumint(8) unsigned NOT NULL COMMENT '用户组id',
  UNIQUE KEY `uid_group_id` (`uid`,`group_id`),
  KEY `uid` (`uid`),
  KEY `group_id` (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of lx_auth_group_access
-- ----------------------------

-- ----------------------------
-- Table structure for `lx_auth_rule`
-- ----------------------------
DROP TABLE IF EXISTS `lx_auth_rule`;
CREATE TABLE `lx_auth_rule` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '规则id,自增主键',
  `module` varchar(20) NOT NULL COMMENT '规则所属module',
  `type` tinyint(2) NOT NULL DEFAULT '1' COMMENT '1-url;2-主菜单',
  `name` char(80) NOT NULL DEFAULT '' COMMENT '规则唯一英文标识',
  `title` char(20) NOT NULL DEFAULT '' COMMENT '规则中文描述',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否有效(0:无效,1:有效)',
  `condition` varchar(300) NOT NULL DEFAULT '' COMMENT '规则附加条件',
  PRIMARY KEY (`id`),
  KEY `module` (`module`,`status`,`type`)
) ENGINE=MyISAM AUTO_INCREMENT=239 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of lx_auth_rule
-- ----------------------------
INSERT INTO `lx_auth_rule` VALUES ('1', 'admin', '2', 'Admin/Index/index', '首页', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('2', 'admin', '2', 'Admin/Article/index', '档案', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('3', 'admin', '2', 'Admin/User/index', '用户', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('4', 'admin', '2', 'Admin/Addons/index', '扩展', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('5', 'admin', '2', 'Admin/Config/group', '系统', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('7', 'admin', '1', 'Admin/article/add', '新增', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('8', 'admin', '1', 'Admin/article/edit', '编辑', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('9', 'admin', '1', 'Admin/article/setStatus', '改变状态', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('10', 'admin', '1', 'Admin/article/update', '保存', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('11', 'admin', '1', 'Admin/article/autoSave', '保存草稿', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('12', 'admin', '1', 'Admin/article/move', '移动', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('13', 'admin', '1', 'Admin/article/copy', '复制', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('14', 'admin', '1', 'Admin/article/paste', '粘贴', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('15', 'admin', '1', 'Admin/article/permit', '还原', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('16', 'admin', '1', 'Admin/article/clear', '清空', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('17', 'admin', '1', 'Admin/Article/examine', '审核列表', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('18', 'admin', '1', 'Admin/article/recycle', '回收站', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('19', 'admin', '1', 'Admin/User/addaction', '新增用户行为', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('20', 'admin', '1', 'Admin/User/editaction', '编辑用户行为', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('21', 'admin', '1', 'Admin/User/saveAction', '保存用户行为', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('22', 'admin', '1', 'Admin/User/setStatus', '变更行为状态', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('23', 'admin', '1', 'Admin/User/changeStatus?method=forbidUser', '禁用会员', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('24', 'admin', '1', 'Admin/User/changeStatus?method=resumeUser', '启用会员', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('25', 'admin', '1', 'Admin/User/changeStatus?method=deleteUser', '删除会员', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('26', 'admin', '1', 'Admin/User/index', '用户信息', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('27', 'admin', '1', 'Admin/User/action', '用户行为', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('28', 'admin', '1', 'Admin/AuthManager/changeStatus?method=deleteGroup', '删除', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('29', 'admin', '1', 'Admin/AuthManager/changeStatus?method=forbidGroup', '禁用', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('30', 'admin', '1', 'Admin/AuthManager/changeStatus?method=resumeGroup', '恢复', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('31', 'admin', '1', 'Admin/AuthManager/createGroup', '新增', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('32', 'admin', '1', 'Admin/AuthManager/editGroup', '编辑', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('33', 'admin', '1', 'Admin/AuthManager/writeGroup', '保存用户组', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('34', 'admin', '1', 'Admin/AuthManager/group', '授权', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('35', 'admin', '1', 'Admin/AuthManager/access', '访问授权', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('36', 'admin', '1', 'Admin/AuthManager/user', '成员授权', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('37', 'admin', '1', 'Admin/AuthManager/removeFromGroup', '解除授权', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('38', 'admin', '1', 'Admin/AuthManager/addToGroup', '保存成员授权', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('39', 'admin', '1', 'Admin/AuthManager/category', '分类授权', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('40', 'admin', '1', 'Admin/AuthManager/addToCategory', '保存分类授权', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('41', 'admin', '1', 'Admin/AuthManager/index', '权限管理', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('42', 'admin', '1', 'Admin/Addons/create', '创建', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('43', 'admin', '1', 'Admin/Addons/checkForm', '检测创建', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('44', 'admin', '1', 'Admin/Addons/preview', '预览', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('45', 'admin', '1', 'Admin/Addons/build', '快速生成插件', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('46', 'admin', '1', 'Admin/Addons/config', '设置', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('47', 'admin', '1', 'Admin/Addons/disable', '禁用', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('48', 'admin', '1', 'Admin/Addons/enable', '启用', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('49', 'admin', '1', 'Admin/Addons/install', '安装', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('50', 'admin', '1', 'Admin/Addons/uninstall', '卸载', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('51', 'admin', '1', 'Admin/Addons/saveconfig', '更新配置', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('52', 'admin', '1', 'Admin/Addons/adminList', '插件后台列表', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('53', 'admin', '1', 'Admin/Addons/execute', 'URL方式访问插件', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('54', 'admin', '1', 'Admin/Addons/index', '插件管理', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('55', 'admin', '1', 'Admin/Addons/hooks', '钩子管理', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('56', 'admin', '1', 'Admin/model/add', '新增', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('57', 'admin', '1', 'Admin/model/edit', '编辑', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('58', 'admin', '1', 'Admin/model/setStatus', '改变状态', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('59', 'admin', '1', 'Admin/model/update', '保存数据', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('60', 'admin', '1', 'Admin/Model/index', '模型管理', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('61', 'admin', '1', 'Admin/Config/edit', '编辑', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('62', 'admin', '1', 'Admin/Config/del', '删除', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('63', 'admin', '1', 'Admin/Config/add', '新增', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('64', 'admin', '1', 'Admin/Config/save', '保存', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('65', 'admin', '1', 'Admin/Config/group', '网站设置', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('66', 'admin', '1', 'Admin/Config/index', '配置管理', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('67', 'admin', '1', 'Admin/Channel/add', '新增', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('68', 'admin', '1', 'Admin/Channel/edit', '编辑', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('69', 'admin', '1', 'Admin/Channel/del', '删除', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('70', 'admin', '1', 'Admin/Channel/index', '导航管理', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('71', 'admin', '1', 'Admin/Category/edit', '编辑', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('72', 'admin', '1', 'Admin/Category/add', '新增', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('73', 'admin', '1', 'Admin/Category/remove', '删除', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('74', 'admin', '1', 'Admin/Category/index', '分类管理', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('75', 'admin', '1', 'Admin/file/upload', '上传控件', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('76', 'admin', '1', 'Admin/file/uploadPicture', '上传图片', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('77', 'admin', '1', 'Admin/file/download', '下载', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('94', 'admin', '1', 'Admin/AuthManager/modelauth', '模型授权', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('79', 'admin', '1', 'Admin/article/batchOperate', '导入', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('80', 'admin', '1', 'Admin/Database/index?type=export', '备份数据库', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('81', 'admin', '1', 'Admin/Database/index?type=import', '还原数据库', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('82', 'admin', '1', 'Admin/Database/export', '备份', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('83', 'admin', '1', 'Admin/Database/optimize', '优化表', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('84', 'admin', '1', 'Admin/Database/repair', '修复表', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('86', 'admin', '1', 'Admin/Database/import', '恢复', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('87', 'admin', '1', 'Admin/Database/del', '删除', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('88', 'admin', '1', 'Admin/User/add', '新增用户', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('89', 'admin', '1', 'Admin/Attribute/index', '属性管理', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('90', 'admin', '1', 'Admin/Attribute/add', '新增', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('91', 'admin', '1', 'Admin/Attribute/edit', '编辑', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('92', 'admin', '1', 'Admin/Attribute/setStatus', '改变状态', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('93', 'admin', '1', 'Admin/Attribute/update', '保存数据', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('95', 'admin', '1', 'Admin/AuthManager/addToModel', '保存模型授权', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('96', 'admin', '1', 'Admin/Category/move', '移动', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('97', 'admin', '1', 'Admin/Category/merge', '合并', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('98', 'admin', '1', 'Admin/Config/menu', '后台菜单管理', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('99', 'admin', '1', 'Admin/Article/mydocument', '内容', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('100', 'admin', '1', 'Admin/Menu/index', '菜单管理', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('101', 'admin', '1', 'Admin/other', '其他', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('102', 'admin', '1', 'Admin/Menu/add', '新增', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('103', 'admin', '1', 'Admin/Menu/edit', '编辑', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('104', 'admin', '1', 'Admin/Think/lists?model=article', '文章管理', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('105', 'admin', '1', 'Admin/Think/lists?model=download', '下载管理', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('106', 'admin', '1', 'Admin/Think/lists?model=config', '配置管理', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('107', 'admin', '1', 'Admin/Action/actionlog', '行为日志', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('108', 'admin', '1', 'Admin/User/updatePassword', '修改密码', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('109', 'admin', '1', 'Admin/User/updateNickname', '修改昵称', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('110', 'admin', '1', 'Admin/action/edit', '查看行为日志', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('205', 'admin', '1', 'Admin/think/add', '新增数据', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('111', 'admin', '2', 'Admin/article/index', '文档列表', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('112', 'admin', '2', 'Admin/article/add', '新增', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('113', 'admin', '2', 'Admin/article/edit', '编辑', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('114', 'admin', '2', 'Admin/article/setStatus', '改变状态', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('115', 'admin', '2', 'Admin/article/update', '保存', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('116', 'admin', '2', 'Admin/article/autoSave', '保存草稿', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('117', 'admin', '2', 'Admin/article/move', '移动', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('118', 'admin', '2', 'Admin/article/copy', '复制', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('119', 'admin', '2', 'Admin/article/paste', '粘贴', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('120', 'admin', '2', 'Admin/article/batchOperate', '导入', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('121', 'admin', '2', 'Admin/article/recycle', '回收站', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('122', 'admin', '2', 'Admin/article/permit', '还原', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('123', 'admin', '2', 'Admin/article/clear', '清空', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('124', 'admin', '2', 'Admin/User/add', '新增用户', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('125', 'admin', '2', 'Admin/User/action', '用户行为', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('126', 'admin', '2', 'Admin/User/addAction', '新增用户行为', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('127', 'admin', '2', 'Admin/User/editAction', '编辑用户行为', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('128', 'admin', '2', 'Admin/User/saveAction', '保存用户行为', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('129', 'admin', '2', 'Admin/User/setStatus', '变更行为状态', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('130', 'admin', '2', 'Admin/User/changeStatus?method=forbidUser', '禁用会员', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('131', 'admin', '2', 'Admin/User/changeStatus?method=resumeUser', '启用会员', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('132', 'admin', '2', 'Admin/User/changeStatus?method=deleteUser', '删除会员', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('133', 'admin', '2', 'Admin/AuthManager/index', '权限管理', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('134', 'admin', '2', 'Admin/AuthManager/changeStatus?method=deleteGroup', '删除', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('135', 'admin', '2', 'Admin/AuthManager/changeStatus?method=forbidGroup', '禁用', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('136', 'admin', '2', 'Admin/AuthManager/changeStatus?method=resumeGroup', '恢复', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('137', 'admin', '2', 'Admin/AuthManager/createGroup', '新增', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('138', 'admin', '2', 'Admin/AuthManager/editGroup', '编辑', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('139', 'admin', '2', 'Admin/AuthManager/writeGroup', '保存用户组', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('140', 'admin', '2', 'Admin/AuthManager/group', '授权', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('141', 'admin', '2', 'Admin/AuthManager/access', '访问授权', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('142', 'admin', '2', 'Admin/AuthManager/user', '成员授权', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('143', 'admin', '2', 'Admin/AuthManager/removeFromGroup', '解除授权', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('144', 'admin', '2', 'Admin/AuthManager/addToGroup', '保存成员授权', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('145', 'admin', '2', 'Admin/AuthManager/category', '分类授权', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('146', 'admin', '2', 'Admin/AuthManager/addToCategory', '保存分类授权', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('147', 'admin', '2', 'Admin/AuthManager/modelauth', '模型授权', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('148', 'admin', '2', 'Admin/AuthManager/addToModel', '保存模型授权', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('149', 'admin', '2', 'Admin/Addons/create', '创建', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('150', 'admin', '2', 'Admin/Addons/checkForm', '检测创建', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('151', 'admin', '2', 'Admin/Addons/preview', '预览', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('152', 'admin', '2', 'Admin/Addons/build', '快速生成插件', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('153', 'admin', '2', 'Admin/Addons/config', '设置', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('154', 'admin', '2', 'Admin/Addons/disable', '禁用', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('155', 'admin', '2', 'Admin/Addons/enable', '启用', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('156', 'admin', '2', 'Admin/Addons/install', '安装', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('157', 'admin', '2', 'Admin/Addons/uninstall', '卸载', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('158', 'admin', '2', 'Admin/Addons/saveconfig', '更新配置', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('159', 'admin', '2', 'Admin/Addons/adminList', '插件后台列表', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('160', 'admin', '2', 'Admin/Addons/execute', 'URL方式访问插件', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('161', 'admin', '2', 'Admin/Addons/hooks', '钩子管理', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('162', 'admin', '2', 'Admin/Model/index', '模型管理', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('163', 'admin', '2', 'Admin/model/add', '新增', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('164', 'admin', '2', 'Admin/model/edit', '编辑', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('165', 'admin', '2', 'Admin/model/setStatus', '改变状态', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('166', 'admin', '2', 'Admin/model/update', '保存数据', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('167', 'admin', '2', 'Admin/Attribute/index', '属性管理', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('168', 'admin', '2', 'Admin/Attribute/add', '新增', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('169', 'admin', '2', 'Admin/Attribute/edit', '编辑', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('170', 'admin', '2', 'Admin/Attribute/setStatus', '改变状态', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('171', 'admin', '2', 'Admin/Attribute/update', '保存数据', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('172', 'admin', '2', 'Admin/Config/index', '配置管理', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('173', 'admin', '2', 'Admin/Config/edit', '编辑', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('174', 'admin', '2', 'Admin/Config/del', '删除', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('175', 'admin', '2', 'Admin/Config/add', '新增', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('176', 'admin', '2', 'Admin/Config/save', '保存', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('177', 'admin', '2', 'Admin/Menu/index', '菜单管理', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('178', 'admin', '2', 'Admin/Channel/index', '导航管理', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('179', 'admin', '2', 'Admin/Channel/add', '新增', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('180', 'admin', '2', 'Admin/Channel/edit', '编辑', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('181', 'admin', '2', 'Admin/Channel/del', '删除', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('182', 'admin', '2', 'Admin/Category/index', '核心', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('183', 'admin', '2', 'Admin/Category/edit', '编辑', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('184', 'admin', '2', 'Admin/Category/add', '新增', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('185', 'admin', '2', 'Admin/Category/remove', '删除', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('186', 'admin', '2', 'Admin/Category/move', '移动', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('187', 'admin', '2', 'Admin/Category/merge', '合并', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('188', 'admin', '2', 'Admin/Database/index?type=export', '备份数据库', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('189', 'admin', '2', 'Admin/Database/export', '备份', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('190', 'admin', '2', 'Admin/Database/optimize', '优化表', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('191', 'admin', '2', 'Admin/Database/repair', '修复表', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('192', 'admin', '2', 'Admin/Database/index?type=import', '还原数据库', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('193', 'admin', '2', 'Admin/Database/import', '恢复', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('194', 'admin', '2', 'Admin/Database/del', '删除', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('195', 'admin', '2', 'Admin/other', '其他', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('196', 'admin', '2', 'Admin/Menu/add', '新增', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('197', 'admin', '2', 'Admin/Menu/edit', '编辑', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('198', 'admin', '2', 'Admin/Think/lists?model=article', '应用', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('199', 'admin', '2', 'Admin/Think/lists?model=download', '下载管理', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('200', 'admin', '2', 'Admin/Think/lists?model=config', '应用', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('201', 'admin', '2', 'Admin/Action/actionlog', '行为日志', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('202', 'admin', '2', 'Admin/User/updatePassword', '修改密码', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('203', 'admin', '2', 'Admin/User/updateNickname', '修改昵称', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('204', 'admin', '2', 'Admin/action/edit', '查看行为日志', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('206', 'admin', '1', 'Admin/think/edit', '编辑数据', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('207', 'admin', '1', 'Admin/Menu/import', '导入', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('208', 'admin', '1', 'Admin/Model/generate', '生成', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('209', 'admin', '1', 'Admin/Addons/addHook', '新增钩子', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('210', 'admin', '1', 'Admin/Addons/edithook', '编辑钩子', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('211', 'admin', '1', 'Admin/Article/sort', '文档排序', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('212', 'admin', '1', 'Admin/Config/sort', '排序', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('213', 'admin', '1', 'Admin/Menu/sort', '排序', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('214', 'admin', '1', 'Admin/Channel/sort', '排序', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('215', 'admin', '1', 'Admin/Category/operate/type/move', '移动', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('216', 'admin', '1', 'Admin/Category/operate/type/merge', '合并', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('217', 'admin', '1', 'Admin/article/index', '文档列表', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('218', 'admin', '1', 'Admin/think/lists', '数据列表', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('223', 'admin', '1', 'Admin/CodeSample/index', '代码示例管理', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('224', 'admin', '1', 'Admin/CodeSample/add', '新增', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('225', 'admin', '1', 'Admin/CodeSample/edit', '编辑', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('226', 'admin', '1', 'Admin/CodeSample/del', '删除', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('228', 'admin', '1', 'Admin/Config/clearcache', '清除缓存', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('235', 'admin', '1', 'Admin/Link/edit', '编辑', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('234', 'admin', '1', 'Admin/Link/add', '新增', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('233', 'admin', '1', 'Admin/Link/index', '网站连接管理', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('236', 'admin', '1', 'Admin/Link/del', '删除', '1', '');
INSERT INTO `lx_auth_rule` VALUES ('237', 'admin', '1', 'Admin/./uc_server', 'UCenter', '-1', '');
INSERT INTO `lx_auth_rule` VALUES ('238', 'admin', '1', 'Admin/User/ucenter', 'UCenter', '1', '');

-- ----------------------------
-- Table structure for `lx_category`
-- ----------------------------
DROP TABLE IF EXISTS `lx_category`;
CREATE TABLE `lx_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '分类ID',
  `name` varchar(30) NOT NULL COMMENT '标志',
  `title` varchar(50) NOT NULL COMMENT '标题',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID',
  `sort` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序（同级有效）',
  `list_row` tinyint(3) unsigned NOT NULL DEFAULT '10' COMMENT '列表每页行数',
  `meta_title` varchar(50) NOT NULL DEFAULT '' COMMENT 'SEO的网页标题',
  `keywords` varchar(255) NOT NULL DEFAULT '' COMMENT '关键字',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `content` text,
  `template_index` varchar(100) NOT NULL DEFAULT '' COMMENT '频道页模板',
  `template_lists` varchar(100) NOT NULL DEFAULT '' COMMENT '列表页模板',
  `template_detail` varchar(100) NOT NULL DEFAULT '' COMMENT '详情页模板',
  `template_edit` varchar(100) NOT NULL DEFAULT '' COMMENT '编辑页模板',
  `model` varchar(100) NOT NULL DEFAULT '' COMMENT '列表绑定模型',
  `model_sub` varchar(100) NOT NULL DEFAULT '' COMMENT '子文档绑定模型',
  `type` varchar(100) NOT NULL DEFAULT '' COMMENT '允许发布的内容类型',
  `link_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '外链',
  `ispart` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '栏目属性(模仿织梦)',
  `allow_publish` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否允许发布内容',
  `display` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '可见性',
  `reply` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否允许回复',
  `check` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '发布的文章是否需要审核',
  `reply_model` varchar(100) NOT NULL DEFAULT '',
  `extend` text COMMENT '扩展设置',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '数据状态',
  `icon` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分类图标',
  `groups` varchar(255) NOT NULL DEFAULT '' COMMENT '分组定义',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_name` (`name`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM AUTO_INCREMENT=63 DEFAULT CHARSET=utf8 COMMENT='分类表';

-- ----------------------------
-- Records of lx_category
-- ----------------------------
INSERT INTO `lx_category` VALUES ('1', 'xinwenzixun', '新闻资讯', '0', '1', '10', '', '', '', '', '', '', '', '', '2', '2', '2', '0', '0', '0', '1', '0', '0', '', null, '1379474947', '1454312830', '1', '0', '');
INSERT INTO `lx_category` VALUES ('2', 'gongsixinwen', '公司新闻', '1', '1', '10', '', '', '', '', '', '', '', '', '2', '2', '2', '0', '0', '1', '1', '0', '0', '', null, '1379475028', '1454299446', '1', '0', '');
INSERT INTO `lx_category` VALUES ('41', 'hangyexinwen', '行业新闻', '1', '2', '10', '', '', '', '', '', '', '', '', '2', '2', '2', '0', '0', '1', '1', '0', '0', '', null, '1453037979', '1454290430', '1', '0', '');
INSERT INTO `lx_category` VALUES ('42', 'zhuanyezhishi', '专业知识', '1', '4', '10', '', '', '', '<div style=\"text-align:center;\">\r\n	<span style=\"color:#404040;font-family:\'microsoft yahei\', Helvetica, Tahoma, Arial, sans-serif;font-size:14px;font-weight:bold;line-height:30px;background-color:#F6F6F6;\">容</span><span style=\"color:#404040;font-family:\'microsoft yahei\', Helvetica, Tahoma, Arial, sans-serif;font-size:14px;font-weight:bold;line-height:30px;background-color:#F6F6F6;\">容</span><span style=\"color:#404040;font-family:\'microsoft yahei\', Helvetica, Tahoma, Arial, sans-serif;font-size:14px;font-weight:bold;line-height:30px;background-color:#F6F6F6;\">容</span><span style=\"color:#404040;font-family:\'microsoft yahei\', Helvetica, Tahoma, Arial, sans-serif;font-size:14px;font-weight:bold;line-height:30px;background-color:#F6F6F6;\">容</span><span style=\"color:#404040;font-family:\'microsoft yahei\', Helvetica, Tahoma, Arial, sans-serif;font-size:14px;font-weight:bold;line-height:30px;background-color:#F6F6F6;\">容</span><span style=\"line-height:1.5;\"></span> \r\n</div>', '', '', '', '', '2', '2', '2', '0', '0', '1', '1', '0', '0', '', null, '1453038051', '1454290443', '1', '0', '');
INSERT INTO `lx_category` VALUES ('43', 'chenggonganli', '成功案例', '0', '2', '6', '', '', '', '', '', 'lists_case', '', '', '2', '2', '2', '0', '0', '0', '1', '0', '0', '', null, '1453038089', '1454312839', '1', '0', '');
INSERT INTO `lx_category` VALUES ('44', 'fuwuhangye', '服务行业', '43', '1', '6', '', '', '', '', '', 'lists_case', '', '', '2', '2', '2', '0', '0', '1', '1', '0', '0', '', null, '1453038117', '1454312848', '1', '0', '');
INSERT INTO `lx_category` VALUES ('45', 'fangchanhangye', '房产行业', '43', '3', '6', '', '', '', '', '', 'lists_case', '', '', '2', '2', '2', '0', '0', '1', '1', '0', '0', '', null, '1453038159', '1454290484', '1', '0', '');
INSERT INTO `lx_category` VALUES ('46', 'fuwuxiangmu', '服务项目', '0', '3', '10', '', '', '', '<article class=\"container info\">\r\n	<h3 class=\"h-h2\" style=\"text-align:center;\">\r\n		办公室环境系统建设专家</h3>\r\n	<p class=\"m-shu\" style=\"text-align:center;\">\r\n		致力于办公环境策划，成就中国高端办公环境策划领航者</p>\r\n	<hr class=\"m-sx-40\" />\r\n	<div class=\"row\">\r\n		<div class=\"col-xs-12 col-sm-12 col-md-3 col-lg-2 visible-lg visible-md\">\r\n			<p class=\"m-s-10\">\r\n				<img alt=\"装饰工程服务\" src=\"/Uploads/allimg/151202/1-15120222061B27.png\" style=\"border-width: 0px; border-style: solid; width: 160px; height: 160px;\" title=\"装饰工程服务\" /></p>\r\n		</div>\r\n		<div class=\"col-xs-12 col-sm-12 col-md-9 col-lg-10\">\r\n			<h3>\r\n				装饰工程服务&nbsp;Decoration Engineering Services</h3>\r\n			<p>\r\n				装饰工程是指房屋建筑施工中包括抹灰、油漆、刷浆、玻璃、裱糊、饰面、罩面板和花饰等工艺的工程，它是房屋建筑施工的最后一个施工过程，其具体内容包括内外墙面和顶棚的的抹灰，内外墙饰面和镶面、楼地面的饰面、房屋立面花饰的安装、门窗等木制品和金属品的油漆刷浆等。</p>\r\n		</div>\r\n	</div>\r\n	<hr class=\"m-sx-40\" />\r\n	<div class=\"row\">\r\n		<div class=\"col-xs-12 col-sm-12 col-md-3 col-lg-2 visible-lg visible-md\">\r\n			<p class=\"m-s-10\">\r\n				<img alt=\"办公室装修服务\" src=\"/Uploads/allimg/151202/1-151202220632T7.png\" style=\"border-width: 0px; border-style: solid; width: 160px; height: 160px;\" title=\"办公室装修服务\" /></p>\r\n		</div>\r\n		<div class=\"col-xs-12 col-sm-12 col-md-9 col-lg-10\">\r\n			<h3>\r\n				装修工程服务&nbsp;Decoration Engineering Services</h3>\r\n			<p>\r\n				随着生活水平的不断提高，装修这一行业慢慢地从建筑这一大行业之中脱离开来，发展成为了一个专门的子行业。通常意义上，装修公司的职责范围应该包括前期装修设计、 装修材料选配、装修施工、后期配饰、保修维护等几个阶段。</p>\r\n		</div>\r\n	</div>\r\n	<hr class=\"m-sx-40\" />\r\n	<div class=\"row\">\r\n		<div class=\"col-xs-12 col-sm-12 col-md-3 col-lg-2 visible-lg visible-md\">\r\n			<p class=\"m-s-10\">\r\n				<img alt=\"办公室装饰服务\" src=\"/Uploads/allimg/151202/1-151202220640462.png\" style=\"border-width: 0px; border-style: solid; width: 160px; height: 160px;\" title=\"办公室装饰服务\" /></p>\r\n		</div>\r\n		<div class=\"col-xs-12 col-sm-12 col-md-9 col-lg-10\">\r\n			<h3>\r\n				中央空调服务&nbsp;Central Air-conditioning Services</h3>\r\n			<p>\r\n				中央空调系统由冷热源系统和空气调节系统组成。采用液体汽化制冷的原理为空气调节系统提供所需冷量，用以抵消室内环境的冷负荷；制热系统为空气调节系统提供用以抵消室内环境热负荷的热量。制冷系统是中央空调系统至关重要的部分，其采用种类、运行方式、结构形式等直接影响了中央空调系统在运行中的经济性、高效性、合理性。</p>\r\n		</div>\r\n	</div>\r\n	<hr class=\"m-sx-40\" />\r\n	<div class=\"row\">\r\n		<div class=\"col-xs-12 col-sm-12 col-md-3 col-lg-2 visible-lg visible-md\">\r\n			<p class=\"m-s-10\">\r\n				<img alt=\"办公室保鲜服务\" src=\"/Uploads/allimg/151202/1-15120222064HZ.png\" style=\"border-width: 0px; border-style: solid; width: 160px; height: 160px;\" title=\"办公室保鲜服务\" /></p>\r\n		</div>\r\n		<div class=\"col-xs-12 col-sm-12 col-md-9 col-lg-10\">\r\n			<h3>\r\n				通风工程服务&nbsp;Ventilation engineering Services</h3>\r\n			<p>\r\n				通风按照范围可分为全面通风和局部通风。全面通风也称稀释通风，它是对整个空间进行换气。局部通风是在污染物的产生地点直接把被污染的空气收集起来排至室外，或者直接向局部空间供给新鲜空气。局部通风具有通风效果好、风量节省等优点。</p>\r\n		</div>\r\n	</div>\r\n	<hr class=\"m-sx-40\" />\r\n	<div class=\"row\">\r\n		<div class=\"col-xs-12 col-sm-12 col-md-3 col-lg-2 visible-lg visible-md\">\r\n			<p class=\"m-s-10\">\r\n				<img alt=\"VIP增值服务\" src=\"/Uploads/allimg/151202/1-151202220A5194.png\" style=\"border-width: 0px; border-style: solid; width: 160px; height: 160px;\" title=\"VIP增值服务\" /></p>\r\n		</div>\r\n		<div class=\"col-xs-12 col-sm-12 col-md-9 col-lg-10\">\r\n			<h3>\r\n				VIP增值服务 VIP Value-added Services</h3>\r\n			<p>\r\n				加入VIP分享客户联谊会，与国内一线品牌大企业面对面交流，扩充人脉，交换思维，有梦一起飞。定期举办新、老客户联谊会、交流会、企业讨论会，充分整合资源，让客户通过月亮湾交流平台，加强合作，促进交流，最大限度的实现资源共享。成为铂金会员的客户，即可获得企业现代管理咨询、品牌策划、营销策划、业务培训等方面的资讯与服务，全面提升客户的品牌价值，助您的企业搏击风浪，迈向辉煌！</p>\r\n		</div>\r\n	</div>\r\n	<hr class=\"m-sx-40\" />\r\n	<p style=\"text-align:center;\">\r\n		<a class=\"btn btn-success x-m-none\" href=\"/a/lianxiwomen/\" target=\"_blank\">我们已经准备好了，现在就联系我们！</a></p>\r\n	<p align=\"center\" class=\"pageLink\">\r\n		&nbsp;</p>\r\n	<div class=\"content_page\">\r\n		&nbsp;</div>\r\n</article>\r\n', 'service', '', '', '', '2', '2', '2', '0', '1', '0', '1', '0', '0', '', null, '1453038218', '1454319182', '1', '0', '');
INSERT INTO `lx_category` VALUES ('47', 'fuwuyoushi', '服务优势', '46', '1', '10', '', '', '', '<article class=\"container info\">\r\n	《织梦58》诞生于2014年6月（www.dede58.com），以提供分享精品织梦源码及织梦建站过程常遇到的问题解决方案汇总为主要宗旨。<br />\r\n	《织梦58》内容涉及: 企业类织梦源码，门户类织梦源码，及工作室或博客类等基于织梦系统仿制等风格。<br />\r\n	《织梦58》将向着共享化、全面化、专业化、深度化、免费化的多元方向发展，打造实用快捷的建站体验，为会员及用户提供高质量的服务。<br />\r\n	《织梦58》感谢无数关注、支持我们的会员及各位访客们，感谢您们的信任。年轻的《织梦58》愿与同样充满活力的您，彼此相伴，共同成长！<br />\r\n	《织梦58》只专心做一件事，便是做好的完整的织梦源码！<br />\r\n	<br />\r\n	织梦58开站以来，尽管没有积累太多用户口碑和市场份额，但我们一直努力，每天保持更新各行各业好源码，让找织梦源码去dede58已经成为很多织梦爱好者的习惯。<br />\r\n	相信通过我们的努力,dede58会越来越好！<br />\r\n	<br />\r\n	dede58离不开您的参与，如果您：<br />\r\n	1.要出售自己持有的好源码？欢迎您来这里分享，经过审核后会支付一定的金币做为回报。<br />\r\n	2.苦苦寻觅织梦源码 、迫切希望得到一个改改就能用上的企业网站？这里或许能很快解决您的问题。<br />\r\n	3.想找个网络模板收藏夹存放自己喜爱的源码或模板？ 这里就是您的选择。<br />\r\n	4.想结交更多热爱织梦源码建站的朋友？那就快来分享您的源码，寻找志趣相投的朋友吧。</article>\r\n', '', '', '', '', '2', '2', '2', '0', '1', '1', '1', '0', '0', '', null, '1453038262', '1454249814', '1', '0', '');
INSERT INTO `lx_category` VALUES ('48', 'fuwufanchou', '服务范畴', '46', '2', '10', '', '', '', '<article class=\"container info\">\r\n	《织梦58》诞生于2014年6月（www.dede58.com），以提供分享精品织梦源码及织梦建站过程常遇到的问题解决方案汇总为主要宗旨。<br />\r\n	《织梦58》内容涉及: 企业类织梦源码，门户类织梦源码，及工作室或博客类等基于织梦系统仿制等风格。<br />\r\n	《织梦58》将向着共享化、全面化、专业化、深度化、免费化的多元方向发展，打造实用快捷的建站体验，为会员及用户提供高质量的服务。<br />\r\n	《织梦58》感谢无数关注、支持我们的会员及各位访客们，感谢您们的信任。年轻的《织梦58》愿与同样充满活力的您，彼此相伴，共同成长！<br />\r\n	《织梦58》只专心做一件事，便是做好的完整的织梦源码！<br />\r\n	<br />\r\n	织梦58开站以来，尽管没有积累太多用户口碑和市场份额，但我们一直努力，每天保持更新各行各业好源码，让找织梦源码去dede58已经成为很多织梦爱好者的习惯。<br />\r\n	相信通过我们的努力,dede58会越来越好！<br />\r\n	<br />\r\n	dede58离不开您的参与，如果您：<br />\r\n	1.要出售自己持有的好源码？欢迎您来这里分享，经过审核后会支付一定的金币做为回报。<br />\r\n	2.苦苦寻觅织梦源码 、迫切希望得到一个改改就能用上的企业网站？这里或许能很快解决您的问题。<br />\r\n	3.想找个网络模板收藏夹存放自己喜爱的源码或模板？ 这里就是您的选择。<br />\r\n	4.想结交更多热爱织梦源码建站的朋友？那就快来分享您的源码，寻找志趣相投的朋友吧。</article>\r\n', '', '', '', '', '2', '2', '2', '0', '1', '1', '1', '0', '0', '', null, '1453038279', '1454249836', '1', '0', '');
INSERT INTO `lx_category` VALUES ('49', 'guanyuwomen', '关于我们', '0', '5', '10', '', '', '', '<div class=\"row xg\">\r\n	<div class=\"col-xs-12 col-sm-4 col-md-5 col-lg-6\">\r\n		<img alt=\"关于我们\" src=\"/Uploads/allimg/151202/1-1512020S955W0.jpg\" title=\"关于我们\" />\r\n	</div>\r\n	<div class=\"col-xs-12 col-sm-8 col-md-7 col-lg-6\">\r\n		<h3 class=\"h3\">\r\n			企业介绍\r\n		</h3>\r\n		<p>\r\n			某某有限公司系上海装饰装修行业协会会员单位、上海市装饰装修资质单位、上海市绿色健康装潢定点检测单位，主要从事住宅、商业空间、写字楼、别墅的装饰设计与施工。 公司拥有一批资深的设计人员和经验丰富的施工队。几年来，公司在一直秉承以诚为...\r\n		</p>\r\n		<p class=\"mb-none anniu\">\r\n			<a class=\"btn btn-success\" href=\"/#\">品牌 Brand →</a>企业视频 Video →\r\n		</p>\r\n	</div>\r\n</div>\r\n<!-- Video -->\r\n<div class=\"modal fade\" id=\"myVideo\">\r\n	<div class=\"modal-dialog\">\r\n		<div class=\"modal-content\">\r\n			<div class=\"modal-header\">\r\n				×\r\n				<h4 class=\"modal-title\" id=\"myModalLabel\">\r\n					企业视频\r\n				</h4>\r\n			</div>\r\n			<div class=\"modal-body text-center\">\r\n				<iframe class=\"visible-lg\" frameborder=\"0\" height=\"310px\" src=\"http://player.youku.com/embed/XNDUxNDU0OTQ0\" width=\"100%\">\r\n				</iframe>\r\n			</div>\r\n		</div>\r\n	</div>\r\n</div>\r\n<!-- Video -->\r\n<hr class=\"m-sx-50\" />\r\n<div class=\"row\">\r\n	<div class=\"col-xs-12 col-sm-8 col-md-7 col-lg-6\">\r\n		<h3 class=\"h3\">\r\n			我们的理念 Our Philosophy\r\n		</h3>\r\n		<p>\r\n			在企业建设过程中，培育了真诚、专业、服务、创新的精神，凭借这种精神，取得了令人瞩目的业绩，同时也形成了自身的企业文化。每年都要定期对全体员工进行全面培训，提升员工...\r\n		</p>\r\n		<p class=\"mb-none anniu\">\r\n			<a class=\"btn btn-success\" href=\"/#\" target=\"_blank\">我们的服务 More →</a>\r\n		</p>\r\n	</div>\r\n	<div class=\"col-xs-12 col-sm-4 col-md-5 col-lg-6 text-right\">\r\n		<img alt=\"我们的概念\" class=\"mt\" src=\"/Uploads/allimg/151202/1-1512020T154407.jpg\" title=\"我们的概念\" />\r\n	</div>\r\n</div>\r\n<hr class=\"m-sx-50\" />\r\n<h3 class=\"h3\">\r\n	我们服务过哪些客户 Customers that We Have Served\r\n</h3>\r\n<p>\r\n	上海飞宇建筑工程有限公司成立于2006年2月06日，注册资本8000万元人民币。公司总部位于长沙芙蓉区，交通便利，位置优越，拥有总建筑面积约6000平方米的自主产权综合办公大楼，公司现有员工360余人，其中，经济管理和技术管理人员600余人！\r\n</p>\r\n<hr class=\"m-sx-50\" />\r\n<div class=\"diwei text-center\">\r\n	<h3 class=\"h3\">\r\n		我们的行业地位 Our Status in the Industry\r\n	</h3>\r\n	<div class=\"row\">\r\n		<div class=\"col-xs-12 col-sm-4 col-md-4 col-lg-4\">\r\n			<img alt=\"为一流的客户，创造一流的环境\" src=\"/Uploads/allimg/151202/1-1512020T019436.jpg\" style=\"width:360px;height:210px;\" title=\"为一流的客户，创造一流的环境\" /> \r\n			<p>\r\n				为一流的客户，创造一流的环境\r\n			</p>\r\n		</div>\r\n		<div class=\"col-xs-12 col-sm-4 col-md-4 col-lg-4\">\r\n			<img alt=\"整合一流的伙伴，携手实现共赢\" src=\"/Uploads/allimg/151202/1-1512020T052440.jpg\" style=\"width:360px;height:210px;\" title=\"整合一流的伙伴，携手实现共赢\" /> \r\n			<p>\r\n				整合一流的伙伴，携手实现共赢\r\n			</p>\r\n		</div>\r\n		<div class=\"col-xs-12 col-sm-4 col-md-4 col-lg-4\">\r\n			<img alt=\"聚集一流的人才，实现人生梦想\" src=\"/Uploads/allimg/151202/1-1512020T142347.jpg\" style=\"height:210px;width:360px;\" title=\"聚集一流的人才，实现人生梦想\" /> \r\n			<p>\r\n				聚集一流的人才，实现人生梦想\r\n			</p>\r\n		</div>\r\n	</div>\r\n</div>\r\n<p align=\"center\" class=\"pageLink\">\r\n	<br />\r\n</p>\r\n<div class=\"content_page\">\r\n	&nbsp;\r\n</div>', '', '', '', '', '2', '2', '2', '0', '1', '0', '1', '0', '0', '', null, '1453038312', '1454289914', '1', '0', '');
INSERT INTO `lx_category` VALUES ('50', 'gongsijieshao', '公司介绍', '49', '1', '10', '', '', '', '<div class=\"row xg\">\r\n	<div class=\"col-xs-12 col-sm-4 col-md-5 col-lg-6\">\r\n		<img alt=\"关于我们\" src=\"/Uploads/allimg/151202/1-1512020S955W0.jpg\" title=\"关于我们\" /> \r\n	</div>\r\n	<div class=\"col-xs-12 col-sm-8 col-md-7 col-lg-6\">\r\n		<h3 class=\"h3\">\r\n			企业介绍\r\n		</h3>\r\n		<p>\r\n			某某有限公司系上海装饰装修行业协会会员单位、上海市装饰装修资质单位、上海市绿色健康装潢定点检测单位，主要从事住宅、商业空间、写字楼、别墅的装饰设计与施工。 公司拥有一批资深的设计人员和经验丰富的施工队。几年来，公司在一直秉承以诚为...\r\n		</p>\r\n		<p class=\"mb-none anniu\">\r\n			<a class=\"btn btn-success\" href=\"/#\">品牌 Brand →</a>企业视频 Video →\r\n		</p>\r\n	</div>\r\n</div>\r\n<!-- Video -->\r\n<div class=\"modal fade\" id=\"myVideo\">\r\n	<div class=\"modal-dialog\">\r\n		<div class=\"modal-content\">\r\n			<div class=\"modal-header\">\r\n				×\r\n				<h4 class=\"modal-title\" id=\"myModalLabel\">\r\n					企业视频\r\n				</h4>\r\n			</div>\r\n			<div class=\"modal-body text-center\">\r\n				<iframe class=\"visible-lg\" frameborder=\"0\" height=\"310px\" src=\"http://player.youku.com/embed/XNDUxNDU0OTQ0\" width=\"100%\">\r\n				</iframe>\r\n			</div>\r\n		</div>\r\n	</div>\r\n</div>\r\n<!-- Video -->\r\n<hr class=\"m-sx-50\" />\r\n<div class=\"row\">\r\n	<div class=\"col-xs-12 col-sm-8 col-md-7 col-lg-6\">\r\n		<h3 class=\"h3\">\r\n			我们的理念 Our Philosophy\r\n		</h3>\r\n		<p>\r\n			在企业建设过程中，培育了真诚、专业、服务、创新的精神，凭借这种精神，取得了令人瞩目的业绩，同时也形成了自身的企业文化。每年都要定期对全体员工进行全面培训，提升员工...\r\n		</p>\r\n		<p class=\"mb-none anniu\">\r\n			<a class=\"btn btn-success\" href=\"/#\" target=\"_blank\">我们的服务 More →</a> \r\n		</p>\r\n	</div>\r\n	<div class=\"col-xs-12 col-sm-4 col-md-5 col-lg-6 text-right\">\r\n		<img alt=\"我们的概念\" class=\"mt\" src=\"/Uploads/allimg/151202/1-1512020T154407.jpg\" title=\"我们的概念\" /> \r\n	</div>\r\n</div>\r\n<hr class=\"m-sx-50\" />\r\n<h3 class=\"h3\">\r\n	我们服务过哪些客户 Customers that We Have Served\r\n</h3>\r\n<p>\r\n	上海飞宇建筑工程有限公司成立于2006年2月06日，注册资本8000万元人民币。公司总部位于长沙芙蓉区，交通便利，位置优越，拥有总建筑面积约6000平方米的自主产权综合办公大楼，公司现有员工360余人，其中，经济管理和技术管理人员600余人！\r\n</p>\r\n<hr class=\"m-sx-50\" />\r\n<div class=\"diwei text-center\">\r\n	<h3 class=\"h3\">\r\n		我们的行业地位 Our Status in the Industry\r\n	</h3>\r\n	<div class=\"row\">\r\n		<div class=\"col-xs-12 col-sm-4 col-md-4 col-lg-4\">\r\n			<img alt=\"为一流的客户，创造一流的环境\" src=\"/Uploads/allimg/151202/1-1512020T019436.jpg\" style=\"width:360px;height:210px;\" title=\"为一流的客户，创造一流的环境\" /> \r\n			<p>\r\n				为一流的客户，创造一流的环境\r\n			</p>\r\n		</div>\r\n		<div class=\"col-xs-12 col-sm-4 col-md-4 col-lg-4\">\r\n			<img alt=\"整合一流的伙伴，携手实现共赢\" src=\"/Uploads/allimg/151202/1-1512020T052440.jpg\" style=\"width:360px;height:210px;\" title=\"整合一流的伙伴，携手实现共赢\" /> \r\n			<p>\r\n				整合一流的伙伴，携手实现共赢\r\n			</p>\r\n		</div>\r\n		<div class=\"col-xs-12 col-sm-4 col-md-4 col-lg-4\">\r\n			<img alt=\"聚集一流的人才，实现人生梦想\" src=\"/Uploads/allimg/151202/1-1512020T142347.jpg\" style=\"height:210px;width:360px;\" title=\"聚集一流的人才，实现人生梦想\" /> \r\n			<p>\r\n				聚集一流的人才，实现人生梦想\r\n			</p>\r\n		</div>\r\n	</div>\r\n</div>\r\n<p align=\"center\" class=\"pageLink\">\r\n	<br />\r\n</p>\r\n<div class=\"content_page\">\r\n	&nbsp;\r\n</div>', '', '', '', '', '2', '2', '2', '0', '1', '0', '1', '0', '0', '', null, '1453038342', '1454290588', '1', '0', '');
INSERT INTO `lx_category` VALUES ('51', 'wenhualinian', '文化理念', '49', '2', '10', '', '', '', '<div class=\"text-center\">\r\n	<h3 class=\"h3\">\r\n		文化理念\r\n	</h3>\r\n</div>\r\n<hr class=\"m-sx-50\" />\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	在企业建设过程中，培育了\"真诚、专业、服务、创新\"的精神，凭借这种精神，取得了令人瞩目的业绩，同时也形成了自身的企业文化。每年都要定期对全体员工进行全面培训，提升员工业务素质，增强大家主人翁意识感。 &nbsp;\r\n</p>\r\n<p>\r\n	<strong>装饰企业文化 &nbsp;</strong><br />\r\n企业精神：真诚 专业 服务 创新 &nbsp;<br />\r\n企业愿望：成为中国家居文化产业的卓越集成商<br />\r\n企业宗旨：以人为本，过程精品，全程无忧 &nbsp;<br />\r\n装饰宣传口号：让回家成为一种诱惑 &nbsp;为了保持设计的高水准，经常聘请名家举行讲座、论坛，引导设计师不断汲取国际前沿的设计理念与技巧；为了从根本哂纳感提升服务品质与客户满意度，还主动开展工人职业技能培训。此外，每年特别组织全体员工参加野外拓展培训、户外旅游等各种形式的活动。通过这些活动充分激发了员工的个人潜能，增强了团队力、聚力和创造力，使员工在合作与竞争中感悟到理解、信任、责任和协作的重要性，体会到团队巨大的力量。<br />\r\n标准、规范、快捷、人性、全新的服务体验 &nbsp; &nbsp;<br />\r\n<br />\r\n<strong>我们提供：</strong><br />\r\n标准化的服务模式、规范化的服务管理、快捷的服务方式、人性化的服务体验。<br />\r\n通过提供全面、及时、科学的问题解决方案和服务保障，为客户带来全新而温馨的服务体验，真正做到装修、全程无忧。 &nbsp;<br />\r\n倡导绿色服务、人文服务、品质服务 &nbsp; &nbsp;<br />\r\n我们倡导：绿色服务全程化--从设计方案、材料选择、施工管理全过程，最大限度地\r\n</p>\r\n<p>\r\n	保障环保健康，维护人居环境。人文服务个性化--坚持以人为本，突出人文关怀，真正理解和支持客户的个性化需求。品质服务超值化--通过优质的服务，增加服务附加值，超越客户期望。 &nbsp;引领行业服务标准不断升级 &nbsp;\r\n</p>\r\n<p>\r\n	我们决心：接轨国际先进标准，建立全新的行业服务标准，创建知名的服务品牌，以实际行动带动行业服务水平的全面提升。竭诚为客户服务，不断创新、追求卓越。\r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<hr class=\"m-sx-50\" />', '', '', '', '', '2', '2', '2', '0', '1', '0', '1', '0', '0', '', null, '1453038373', '1454290579', '1', '0', '');
INSERT INTO `lx_category` VALUES ('52', 'zuzhijiagou', '组织架构', '49', '3', '10', '', '', '', '<div class=\"text-center\">\r\n	<h3 class=\"h3\">\r\n		组织架构\r\n	</h3>\r\n</div>\r\n<hr class=\"m-sx-50\" />\r\n<p>\r\n	&nbsp;<img alt=\"\" src=\"/Uploads/allimg/151202/1-1512020T604504.jpg\" style=\"width:720px;height:418px;\" /> \r\n</p>\r\n<hr class=\"m-sx-50\" />', '', '', '', '', '2', '2', '2', '0', '1', '0', '1', '0', '0', '', null, '1453038397', '1454290564', '1', '0', '');
INSERT INTO `lx_category` VALUES ('53', 'meitibaodao', '媒体报道', '1', '3', '10', '', '', '', '', '', '', '', '', '2', '2', '2', '0', '0', '1', '1', '0', '0', '', null, '1454078792', '1454290437', '1', '0', '');
INSERT INTO `lx_category` VALUES ('54', 'qiyeyoushi', '企业优势', '49', '4', '10', '', '', '', '<div class=\"text-center\">\r\n	<h3 class=\"h3\">\r\n		企业优势\r\n	</h3>\r\n</div>\r\n<hr class=\"m-sx-50\" />\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	人类自从有了居所，门窗就成为生活中的重要组成部分。从茹毛饮血时期的茅草棚屋到科技膨胀时期的高楼大厦，门窗时刻伴随着人类。<br />\r\n<br />\r\n从最早的木窗开始，直到新中国发展的塑钢门窗、到后来跟上脚步时代转变的铝合金门窗、及当代最高级的断桥铝门窗。这都是中国门窗史的突破和展现。由于早期的木窗不够具备安全和防盗性能，所以在新社会时期就开始改变转型成塑钢门窗了，塑钢门窗防水性能较好，但是不够美观，在寒冬和炎热的夏季时不能良好的起到防寒和保湿效果。后期门窗市场开始研发推出铝合金门窗，铝合金门窗外观提升较大，颜色有较多的选择，在防盗性上面也是加强了很多，那时的铝合金门窗质量是最好了。到了后面设备的发展和人们对生活水平不断的提升，在门窗行业中也是有着高要求好水平，就出现了高级隔热断桥铝门窗，断桥铝门窗，壁厚更宽，更结实，且有着更好的隔音和隔热效果，并且质量也是最佳的，所以断桥铝门窗也是受到了全国各地的广大的喜爱。直到最新高科技门窗的研发实现了实木和高级铝型材的完美结合，运用了高科技技术和顶尖设备进行多次复合和密封，制作完成的铝包木门窗。<br />\r\n<br />\r\n新型门窗的研发和发展史，说明了现在人们对生活的水平和态度，随着市场经济改革的不断深化，随着家居环境越来越高端的需求，门窗市场的竞争也呈现出白热化状态。行业内公司实力高低不齐，理念各一。<br />\r\n<br />\r\n2012年，是门窗行业发展史上重要的一年，的旗下“中鸿森特”品牌就是在这样的背景下诞生。 面对机遇和挑战，以其先进的工艺，精湛的技术，优异的质量和完善的售后服务在门业市场上掀起一股旋风，受到了客户的赞誉，得到业界同行的钦佩。 走向世界，在竞争中发展。如今，以从容和日臻成熟的心态时刻面对未来的挑战，致力于阳光房系统、节能窗系统、隔断门系统的产品研发和制作，一如既往地向各位新老客户提供更满意的产品和更完善的售后服务，全心全意为客户打造时尚、典雅、高贵的温馨家园。\r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<hr class=\"m-sx-50\" />', '', '', '', '', '2', '2', '2', '0', '1', '0', '1', '0', '0', '', null, '1454079061', '1454289873', '1', '0', '');
INSERT INTO `lx_category` VALUES ('55', 'lianxiwomen', '联系我们', '0', '6', '10', '', '', '', '<div class=\"text-center\">\r\n	<h3 class=\"h3\">\r\n		联系我们\r\n	</h3>\r\n</div>\r\n<hr class=\"m-sx-50\" />\r\n<p>\r\n	<br />\r\n</p>\r\n<div>\r\n	中国 . 南宁市民族大道131号航洋国际城3号楼1633-1635室\r\n</div>\r\n<div>\r\n	0771-3388661\r\n</div>\r\n<div>\r\n	159-7772-2298 24 Hours 服务\r\n</div>\r\n<div>\r\n	3358304@qq.com\r\n</div>\r\n<p>\r\n	<br />\r\n</p>\r\n<hr class=\"m-sx-50\" />', '', '', '', '', '2', '2', '2', '0', '1', '0', '1', '0', '0', '', null, '1454079076', '1454378743', '1', '0', '');
INSERT INTO `lx_category` VALUES ('56', 'qiyetuandui', '企业团队', '0', '4', '10', '', '', '', '', '', 'lists_team', '', '', '2', '2', '2', '0', '0', '0', '1', '0', '0', '', null, '1454079111', '1454319940', '1', '0', '');
INSERT INTO `lx_category` VALUES ('57', 'jiaoyuhangye', '教育行业', '43', '2', '6', '', '', '', '', '', 'lists_case', '', '', '2', '2', '2', '0', '0', '1', '1', '0', '0', '', null, '1454079149', '1454290461', '1', '0', '');
INSERT INTO `lx_category` VALUES ('58', 'nengyuanhangye', '能源行业', '43', '4', '6', '', '', '', '', '', 'lists_case', '', '', '2', '2', '2', '0', '0', '1', '1', '0', '0', '', null, '1454079166', '1454290491', '1', '0', '');
INSERT INTO `lx_category` VALUES ('59', 'shejituandui', '设计团队', '56', '1', '8', '', '', '', '', '', 'lists_team', '', '', '2', '2', '2', '0', '0', '1', '1', '0', '0', '', null, '1454079242', '1454320398', '1', '0', '');
INSERT INTO `lx_category` VALUES ('60', 'cehuatuandui', '策划团队', '56', '3', '8', '', '', '', '', '', 'lists_team', '', '', '2', '2', '2', '0', '0', '1', '1', '0', '0', '', null, '1454079259', '1454320400', '1', '0', '');
INSERT INTO `lx_category` VALUES ('61', 'jishutuandui', '技术团队', '56', '2', '8', '', '', '', '', '', 'lists_team', '', '', '2', '2', '2', '0', '0', '1', '1', '0', '0', '', null, '1454079280', '1454290509', '1', '0', '');
INSERT INTO `lx_category` VALUES ('62', 'guanlituandui', '管理团队', '56', '4', '8', '', '', '', '', '', 'lists_team', '', '', '2', '2', '2', '0', '0', '1', '1', '0', '0', '', null, '1454079293', '1454290524', '1', '0', '');

-- ----------------------------
-- Table structure for `lx_channel`
-- ----------------------------
DROP TABLE IF EXISTS `lx_channel`;
CREATE TABLE `lx_channel` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '频道ID',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级频道ID',
  `title` char(30) NOT NULL COMMENT '频道标题',
  `entitle` char(30) NOT NULL DEFAULT '',
  `url` char(100) NOT NULL COMMENT '频道连接',
  `sort` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '导航排序',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  `target` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '新窗口打开',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of lx_channel
-- ----------------------------
INSERT INTO `lx_channel` VALUES ('1', '0', '首页', 'Home', '/', '1', '1379475111', '1454328915', '1', '0');
INSERT INTO `lx_channel` VALUES ('2', '0', '新闻资讯', 'News', 'Category/index?category=xinwenzixun', '2', '1379475131', '1453038446', '1', '0');
INSERT INTO `lx_channel` VALUES ('3', '0', '服务项目', 'Service', 'Category/index?category=fuwuxiangmu', '4', '1379475154', '1453883897', '1', '0');
INSERT INTO `lx_channel` VALUES ('4', '2', '公司新闻', '', 'Category/index?category=gongsixinwen', '1', '1379475111', '1379475111', '1', '0');
INSERT INTO `lx_channel` VALUES ('5', '2', '行业新闻', '', 'Category/index?category=hangyexinwen', '2', '1379475111', '1379923177', '1', '0');
INSERT INTO `lx_channel` VALUES ('6', '2', '媒体报道', '', 'Category/index?category=meitibaodao', '3', '1379475111', '1379923177', '1', '0');
INSERT INTO `lx_channel` VALUES ('7', '2', '专业知识', '', 'Category/index?category=zhuanyezhishi', '4', '1379475111', '1379923177', '1', '0');
INSERT INTO `lx_channel` VALUES ('8', '0', '成功案例', 'Cases', 'Category/index?category=chenggonganli', '3', '1379475154', '1379475154', '1', '0');
INSERT INTO `lx_channel` VALUES ('9', '0', '企业团队', 'Team', 'Category/index?category=qiyetuandui', '5', '1379475154', '1379475154', '1', '0');
INSERT INTO `lx_channel` VALUES ('10', '0', '关于我们', 'About', 'Category/index?category=guanyuwomen', '6', '1379475154', '1454076576', '1', '0');
INSERT INTO `lx_channel` VALUES ('11', '8', '服务行业', '', 'Category/index?category=fuwuhangye', '1', '1454076387', '1454076387', '1', '0');
INSERT INTO `lx_channel` VALUES ('12', '8', '教育行业', '', 'Category/index?category=jiaoyuhangye', '2', '1454076409', '1454076409', '1', '0');
INSERT INTO `lx_channel` VALUES ('13', '8', '房产行业', '', 'Category/index?category=fangchanhangye', '3', '1454076431', '1454076431', '1', '0');
INSERT INTO `lx_channel` VALUES ('14', '8', '能源行业', '', 'Category/index?category=nengyuanhangye', '4', '1454076458', '1454076458', '1', '0');
INSERT INTO `lx_channel` VALUES ('15', '3', '服务优势', '', 'Category/index?category=fuwuyoushi', '1', '1454076481', '1454076481', '1', '0');
INSERT INTO `lx_channel` VALUES ('16', '3', '服务范畴', '', 'Category/index?category=fuwufanchou', '2', '1454076494', '1454076494', '1', '0');
INSERT INTO `lx_channel` VALUES ('17', '9', '设计团队', '', 'Category/index?category=shejituandui', '1', '1454076506', '1454320419', '1', '0');
INSERT INTO `lx_channel` VALUES ('18', '9', '策划团队', '', 'Category/index?category=cehuatuandui', '3', '1454076527', '1454320428', '1', '0');
INSERT INTO `lx_channel` VALUES ('19', '9', '技术团队', '', 'Category/index?category=jishutuandui', '2', '1454076539', '1454076539', '1', '0');
INSERT INTO `lx_channel` VALUES ('20', '9', '管理团队', '', 'Category/index?category=guanlituandui', '4', '1454076548', '1454076548', '1', '0');
INSERT INTO `lx_channel` VALUES ('21', '10', '公司介绍', '', 'Category/index?category=gongsijieshao', '1', '1454076632', '1454076632', '1', '0');
INSERT INTO `lx_channel` VALUES ('22', '10', '文化理念', '', 'Category/index?category=wenhualinian', '2', '1454076645', '1454076645', '1', '0');
INSERT INTO `lx_channel` VALUES ('23', '10', '组织架构', '', 'Category/index?category=zuzhijiagou', '3', '1454076653', '1454076653', '1', '0');
INSERT INTO `lx_channel` VALUES ('24', '10', '企业优势', '', 'Category/index?category=qiyeyoushi', '4', '1454076665', '1454076872', '1', '0');
INSERT INTO `lx_channel` VALUES ('27', '10', '联系我们', 'Contact', 'Category/index?category=lianxiwomen', '7', '1454077115', '1454166320', '1', '0');

-- ----------------------------
-- Table structure for `lx_code_sample`
-- ----------------------------
DROP TABLE IF EXISTS `lx_code_sample`;
CREATE TABLE `lx_code_sample` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '标识 (唯一标识)',
  `code` char(30) NOT NULL COMMENT '编码 (唯一编码代码)',
  `title` varchar(100) NOT NULL COMMENT '标题 (标题建议不要太短或者太长)',
  `phone` char(11) NOT NULL DEFAULT '' COMMENT '手机号 (请填写您的手机号码)',
  `email` char(50) NOT NULL DEFAULT '' COMMENT '电子邮箱 (请填写您的电子邮箱地址)',
  `sort` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序 (整数：按排列序号从小到大排列)',
  `content` text COMMENT '内容 (可以编辑富文本哦)',
  `from` char(20) NOT NULL DEFAULT '' COMMENT '来源 (这是一个下拉框)',
  `specialty` varchar(100) NOT NULL DEFAULT '' COMMENT '特长 (这是一个多选框)',
  `sex` tinyint(2) NOT NULL DEFAULT '0' COMMENT '性别 (这是一个单选框)',
  `test_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '测试时间 (说明：测试时间)',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间 (记录更新时间)',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间 (记录创建时间)',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_code` (`code`),
  KEY `phone` (`phone`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COMMENT='代码示例 (说明文字-这个表是用于代码生成器演示)';

-- ----------------------------
-- Records of lx_code_sample
-- ----------------------------
INSERT INTO `lx_code_sample` VALUES ('1', 'aaaaa', '标题建议不要太短或标题建议不要太短或', '13471007324', 'jsiq@qq.com', '33', '<h3 class=\"para-title level-3\" style=\"text-align:center;font-size:18px;color:#333333;font-family:\'Microsoft YaHei\', SimHei, Verdana;font-weight:500;background-color:#FFFFFF;\">\r\n	<span class=\"title-text\">标题建议不要太短或标题建议不要太短或</span>\r\n</h3>\r\n<h3 class=\"para-title level-3\" style=\"font-size:18px;color:#333333;font-family:\'Microsoft YaHei\', SimHei, Verdana;font-weight:500;background-color:#FFFFFF;\">\r\n	<span class=\"title-text\"></span> \r\n</h3>', 'internet', 'swim', '0', '1454034630', '1454049825', '1454035049');

-- ----------------------------
-- Table structure for `lx_config`
-- ----------------------------
DROP TABLE IF EXISTS `lx_config`;
CREATE TABLE `lx_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '配置ID',
  `name` varchar(64) NOT NULL DEFAULT '' COMMENT '配置名称',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '配置类型',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '配置说明',
  `group` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '配置分组',
  `extra` varchar(255) NOT NULL DEFAULT '' COMMENT '配置值',
  `remark` varchar(100) NOT NULL DEFAULT '' COMMENT '配置说明',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  `value` text COMMENT '配置值',
  `sort` smallint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_name` (`name`),
  KEY `type` (`type`),
  KEY `group` (`group`)
) ENGINE=MyISAM AUTO_INCREMENT=58 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of lx_config
-- ----------------------------
INSERT INTO `lx_config` VALUES ('1', 'WEB_SITE_TITLE', '1', '网站标题', '1', '', '网站标题前台显示标题', '1378898976', '1379235274', '1', '广西亮星科技有限公司', '0');
INSERT INTO `lx_config` VALUES ('2', 'WEB_SITE_DESCRIPTION', '2', '网站描述', '1', '', '网站搜索引擎描述', '1378898976', '1379235841', '1', '广西亮星科技有限公司官方网站', '1');
INSERT INTO `lx_config` VALUES ('3', 'WEB_SITE_KEYWORD', '2', '网站关键字', '1', '', '网站搜索引擎关键字', '1378898976', '1381390100', '1', 'nnbirghtstar,BrightStar,ThinkPHP,OneThink,CMS,内容管理,内容管理框架', '8');
INSERT INTO `lx_config` VALUES ('4', 'WEB_SITE_CLOSE', '4', '关闭站点', '1', '0:关闭,1:开启', '站点关闭后其他用户不能访问，管理员可以正常访问', '1378898976', '1379235296', '1', '1', '1');
INSERT INTO `lx_config` VALUES ('9', 'CONFIG_TYPE_LIST', '3', '配置类型列表', '4', '', '主要用于数据解析和页面表单的生成', '1378898976', '1379235348', '1', '0:数字\r\n1:字符\r\n2:文本\r\n3:数组\r\n4:枚举', '9');
INSERT INTO `lx_config` VALUES ('10', 'WEB_SITE_ICP', '1', '网站备案号', '1', '', '设置在网站底部显示的备案号，如“沪ICP备12007941号-2', '1378900335', '1379235859', '1', '桂ICP备14001304', '9');
INSERT INTO `lx_config` VALUES ('11', 'DOCUMENT_POSITION', '3', '文档推荐位', '2', '', '文档推荐位，推荐到多个位置KEY值相加即可', '1379053380', '1379235329', '1', '1:列表推荐\r\n2:频道推荐\r\n4:首页推荐', '3');
INSERT INTO `lx_config` VALUES ('12', 'DOCUMENT_DISPLAY', '3', '文档可见性', '2', '', '文章可见性仅影响前台显示，后台不收影响', '1379056370', '1379235322', '1', '0:所有人可见\r\n1:仅注册会员可见\r\n2:仅管理员可见', '4');
INSERT INTO `lx_config` VALUES ('13', 'COLOR_STYLE', '4', '后台色系', '1', 'default_color:默认\r\nblue_color:紫罗兰', '后台颜色风格', '1379122533', '1379235904', '1', 'default_color', '10');
INSERT INTO `lx_config` VALUES ('20', 'CONFIG_GROUP_LIST', '3', '配置分组', '4', '', '配置分组', '1379228036', '1384418383', '1', '1:基本\r\n2:内容\r\n3:用户\r\n4:系统\r\n5:枚举\r\n6:前台界面', '20');
INSERT INTO `lx_config` VALUES ('21', 'HOOKS_TYPE', '3', '钩子的类型', '4', '', '类型 1-用于扩展显示内容，2-用于扩展业务处理', '1379313397', '1379313407', '1', '1:视图\r\n2:控制器', '21');
INSERT INTO `lx_config` VALUES ('22', 'AUTH_CONFIG', '3', 'Auth配置', '4', '', '自定义Auth.class.php类配置', '1379409310', '1379409564', '1', 'AUTH_ON:1\r\nAUTH_TYPE:2', '22');
INSERT INTO `lx_config` VALUES ('23', 'OPEN_DRAFTBOX', '4', '是否开启草稿功能', '2', '0:关闭草稿功能\r\n1:开启草稿功能\r\n', '新增文章时的草稿功能配置', '1379484332', '1379484591', '1', '1', '1');
INSERT INTO `lx_config` VALUES ('24', 'DRAFT_AOTOSAVE_INTERVAL', '0', '自动保存草稿时间', '2', '', '自动保存草稿的时间间隔，单位：秒', '1379484574', '1386143323', '1', '60', '2');
INSERT INTO `lx_config` VALUES ('25', 'LIST_ROWS', '0', '后台每页记录数', '2', '', '后台数据每页显示记录数', '1379503896', '1380427745', '1', '10', '10');
INSERT INTO `lx_config` VALUES ('26', 'USER_ALLOW_REGISTER', '4', '是否允许用户注册', '3', '0:关闭注册\r\n1:允许注册', '是否开放用户注册', '1379504487', '1379504580', '1', '1', '26');
INSERT INTO `lx_config` VALUES ('27', 'CODEMIRROR_THEME', '4', '预览插件的CodeMirror主题', '4', '3024-day:3024 day\r\n3024-night:3024 night\r\nambiance:ambiance\r\nbase16-dark:base16 dark\r\nbase16-light:base16 light\r\nblackboard:blackboard\r\ncobalt:cobalt\r\neclipse:eclipse\r\nelegant:elegant\r\nerlang-dark:erlang-dark\r\nlesser-dark:lesser-dark\r\nmidnight:midnight', '详情见CodeMirror官网', '1379814385', '1384740813', '1', 'ambiance', '27');
INSERT INTO `lx_config` VALUES ('28', 'DATA_BACKUP_PATH', '1', '数据库备份根路径', '4', '', '路径必须以 / 结尾', '1381482411', '1381482411', '1', './Application/Runtime/Data/', '28');
INSERT INTO `lx_config` VALUES ('29', 'DATA_BACKUP_PART_SIZE', '0', '数据库备份卷大小', '4', '', '该值用于限制压缩后的分卷最大长度。单位：B；建议设置20M', '1381482488', '1381729564', '1', '20971520', '29');
INSERT INTO `lx_config` VALUES ('30', 'DATA_BACKUP_COMPRESS', '4', '数据库备份文件是否启用压缩', '4', '0:不压缩\r\n1:启用压缩', '压缩备份文件需要PHP环境支持gzopen,gzwrite函数', '1381713345', '1381729544', '1', '1', '30');
INSERT INTO `lx_config` VALUES ('31', 'DATA_BACKUP_COMPRESS_LEVEL', '4', '数据库备份文件压缩级别', '4', '1:普通\r\n4:一般\r\n9:最高', '数据库备份文件的压缩级别，该配置在开启压缩时生效', '1381713408', '1381713408', '1', '9', '31');
INSERT INTO `lx_config` VALUES ('32', 'DEVELOP_MODE', '4', '开启开发者模式', '4', '0:关闭\r\n1:开启', '是否开启开发者模式', '1383105995', '1383291877', '1', '1', '32');
INSERT INTO `lx_config` VALUES ('33', 'ALLOW_VISIT', '3', '不受限控制器方法', '0', '', '', '1386644047', '1386644741', '1', '0:article/draftbox\r\n1:article/mydocument\r\n2:Category/tree\r\n3:Index/verify\r\n4:file/upload\r\n5:file/download\r\n6:user/updatePassword\r\n7:user/updateNickname\r\n8:user/submitPassword\r\n9:user/submitNickname\r\n10:file/uploadpicture', '0');
INSERT INTO `lx_config` VALUES ('34', 'DENY_VISIT', '3', '超管专限控制器方法', '0', '', '仅超级管理员可访问的控制器方法', '1386644141', '1386644659', '1', '0:Addons/addhook\r\n1:Addons/edithook\r\n2:Addons/delhook\r\n3:Addons/updateHook\r\n4:Admin/getMenus\r\n5:Admin/recordList\r\n6:AuthManager/updateRules\r\n7:AuthManager/tree', '0');
INSERT INTO `lx_config` VALUES ('35', 'REPLY_LIST_ROWS', '0', '回复列表每页条数', '2', '', '', '1386645376', '1387178083', '1', '10', '0');
INSERT INTO `lx_config` VALUES ('36', 'ADMIN_ALLOW_IP', '2', '后台允许访问IP', '4', '', '多个用逗号分隔，如果不配置表示不限制IP访问', '1387165454', '1387165553', '1', '', '36');
INSERT INTO `lx_config` VALUES ('37', 'SHOW_PAGE_TRACE', '4', '是否显示页面Trace', '4', '0:关闭\r\n1:开启', '是否显示页面Trace信息', '1387165685', '1387165685', '1', '0', '37');
INSERT INTO `lx_config` VALUES ('45', 'ENUM_CODE_SAMPLE_FROM', '4', '枚举_代码示例_来源', '5', 'internet:来自网络\nofficial:官方出品\noriginal:原创作品', '枚举_代码示例_来源(by CodeGenerator)', '1453987078', '1453987078', '1', '', '12');
INSERT INTO `lx_config` VALUES ('46', 'ENUM_CODE_SAMPLE_SPECIALTY', '4', '枚举_代码示例_特长', '5', 'swim:游泳\nbadminton:羽毛球\nfootball:足球\nbasketball:篮球\nsing:唱歌\ndance:跳舞', '枚举_代码示例_特长(by CodeGenerator)', '1454034174', '1454034174', '1', '', '12');
INSERT INTO `lx_config` VALUES ('47', 'ENUM_CODE_SAMPLE_SEX', '4', '枚举_代码示例_性别', '5', '0:保密\n1:男\n2:女\n3:人妖', '枚举_代码示例_性别(by CodeGenerator)', '1454034249', '1454034249', '1', '', '12');
INSERT INTO `lx_config` VALUES ('48', 'ENUM_LINK_TARGET', '4', '枚举_网站连接_打开方式', '5', '_self:当前窗口\r\n_blank:新开窗口', '枚举_网站连接_打开方式(by CodeGenerator)', '1454231748', '1454234375', '1', '', '12');
INSERT INTO `lx_config` VALUES ('49', 'USER_ALLOW_LOGIN', '4', '是否允许用户登录', '3', '0:关闭登录\r\n1:允许登录', '是否开放用户登录', '1379504487', '1455694952', '1', '1', '49');
INSERT INTO `lx_config` VALUES ('50', 'USER_ALLOW_QQLOGIN', '4', '是否允许QQ登录', '3', '0:关闭QQ登录\r\n1:允许QQ登录', '是否开放用户QQ登录', '1455717300', '1455717300', '1', '1', '50');
INSERT INTO `lx_config` VALUES ('51', 'USER_ALLOW_WXLOGIN', '4', '是否允许微信登录', '3', '0:关闭微信登录\r\n1:允许微信登录', '是否开放微信登录', '1455717364', '1455717364', '1', '1', '51');
INSERT INTO `lx_config` VALUES ('52', 'USER_AUTH_KEY', '1', '登录cookie键名', '3', '', '', '1455780146', '1455846156', '1', 'BS_AUTH', '52');
INSERT INTO `lx_config` VALUES ('53', 'USER_LOGIN_NEED_VALIDATECODE', '4', '是否每个用户登录都要输入验证码', '3', '0:不需要输入验证码\r\n1:需要输入验证码', '是否每个用户登录都要输入验证码，如果配置为0，则只有24小时内输入密码>n次的用户才需要输入验证码', '1455780512', '1455780512', '1', '0', '53');
INSERT INTO `lx_config` VALUES ('54', 'USER_LOGIN_NEED_VCODE_COUNT', '0', '24小时内输入密码>n次的用户才需要输入验证码', '3', '', '只有24小时内输入密码>次的用户才需要输入验证码', '1455780816', '1455780816', '1', '5', '54');
INSERT INTO `lx_config` VALUES ('55', 'USER_EMAIL_SUBJECT', '1', '用户email验证邮件主题', '3', '', '用户email验证邮件主题', '1455800872', '1455801275', '1', '大力摇邮箱帐号验证邮件', '55');
INSERT INTO `lx_config` VALUES ('56', 'USER_EMAIL_BODY', '2', '用户email验证邮件内容', '3', '', '用户email验证邮件内容', '1455800922', '1455801311', '1', '尊敬的用户，{username}:<br/>\r\n  这是一封来自【大力摇】发来的验证邮件，<a href=\"{hrefurl}\" target=\"_blank\">请点击此连接验证您的邮箱</a>。<br/>\r\n如果您的浏览器点击以上连接无效，请将以下网址<br/>\r\n{copyurl}<br/>\r\n复制到浏览器进行验证! <br/>\r\n感谢您对来秀网的支持！^_^', '56');
INSERT INTO `lx_config` VALUES ('57', 'WEB_SITE_CONTAINER', '4', '首页是否使用宽屏', '6', 'container:不使用宽屏\r\ncontainer-fluid:完全适应屏幕', '即网页容器对应的class', '1456119725', '1456119759', '1', 'container', '0');

-- ----------------------------
-- Table structure for `lx_document`
-- ----------------------------
DROP TABLE IF EXISTS `lx_document`;
CREATE TABLE `lx_document` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '文档ID',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `name` char(40) NOT NULL DEFAULT '' COMMENT '标识',
  `title` char(80) NOT NULL DEFAULT '' COMMENT '标题',
  `category_id` int(10) unsigned NOT NULL COMMENT '所属分类',
  `group_id` smallint(3) unsigned NOT NULL COMMENT '所属分组',
  `description` char(140) NOT NULL DEFAULT '' COMMENT '描述',
  `root` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '根节点',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属ID',
  `model_id` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '内容模型ID',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '2' COMMENT '内容类型',
  `position` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '推荐位',
  `link_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '外链',
  `cover_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '封面',
  `display` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '可见性',
  `deadline` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '截至时间',
  `attach` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '附件数量',
  `view` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '浏览量',
  `comment` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '评论数',
  `extend` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '扩展统计字段',
  `level` int(10) NOT NULL DEFAULT '0' COMMENT '优先级',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '数据状态',
  PRIMARY KEY (`id`),
  KEY `idx_category_status` (`category_id`,`status`),
  KEY `idx_status_type_pid` (`status`,`uid`,`pid`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COMMENT='文档模型基础表';

-- ----------------------------
-- Records of lx_document
-- ----------------------------
INSERT INTO `lx_document` VALUES ('1', '1', '', 'OneThink1.1开发版发布啦', '2', '0', '期待已久的OneThink最新版发布', '0', '0', '2', '2', '7', '0', '1', '1', '0', '0', '37', '0', '0', '0', '1406001360', '1452847102', '1');
INSERT INTO `lx_document` VALUES ('3', '1', '', '这是我的第一个公司新闻', '2', '0', '', '0', '0', '2', '2', '7', '0', '0', '1', '0', '0', '0', '0', '0', '0', '1453115889', '1453115889', '-1');
INSERT INTO `lx_document` VALUES ('4', '1', '', '亮星内容管理框架V1.0版本上线啦', '2', '0', '这个一个基于onethink的开发框架，做了一些适当的修改，作为公司的PHP通用开发框架。', '0', '0', '2', '2', '7', '0', '1', '1', '0', '0', '52', '0', '0', '0', '1453115940', '1454294751', '1');
INSERT INTO `lx_document` VALUES ('5', '1', '', '七夕情人节HTML5+CSS3案例精选', '44', '0', '七夕秀恩爱，也要看时间点，不能提前，也不能晚，因为秀恩爱，早晚都会死的快，所以我们选择了周五的中午，这样也可以让牛郎和织女的鹊桥更加坚固和持久，接下来，我们看看那', '0', '0', '2', '2', '7', '0', '2', '1', '0', '0', '33', '0', '0', '0', '1454313667', '1454313667', '1');
INSERT INTO `lx_document` VALUES ('6', '1', '', 'TGideas：探究H5的不同表现手法', '44', '0', '文艺、走心的手绘风格 项目背景 雷霆战机周年庆，项目组为雷霆进行了拍摄了一部品牌短片，围绕整个影片策划了一个h5对其进行传播。 视频主要内容讲述几个小朋友从小心怀对星空', '0', '0', '2', '2', '7', '0', '3', '1', '0', '0', '16', '0', '0', '0', '1454313833', '1454313833', '1');
INSERT INTO `lx_document` VALUES ('7', '1', '', '快来学学这十佳H5特效吧！', '44', '0', '在市面上有一大批H5页面模板制作工具，诚然，他们方便了很多非专业设计师设计制作H5页面。但是不得不吐槽的是，很多模板工具平台上的作品大部分还停留在左飞入右飞出的初级境界', '0', '0', '2', '2', '7', '0', '4', '1', '0', '0', '53', '0', '0', '0', '1454313939', '1454313939', '1');
INSERT INTO `lx_document` VALUES ('8', '1', '', '提升用户体验的7大微交互', '44', '0', '众所周知，我们总是依据封面来判断书的好坏，聪明的设计师会创造实用有吸引力的界面。潜在用户可能会被吸引，但如何一直黏住他们呢？ 要试着回答这个问题，所有一切都指向人本', '0', '0', '2', '2', '7', '0', '5', '1', '0', '0', '59', '0', '0', '0', '1454314094', '1454314094', '1');
INSERT INTO `lx_document` VALUES ('9', '1', '', '腾讯TGideas：一个牛逼H5诞生记', '44', '0', '【小编有话说】首先给大家隆重介绍下腾讯互动娱乐市场部的专业设计团队--TGideas，爱果果收录过好多这个团队开发的H5网站，无论从创意还是技术上他们的作品都在引领这H5发展的方向', '0', '0', '2', '2', '7', '0', '6', '1', '0', '0', '27', '0', '0', '0', '1454314212', '1454314212', '1');
INSERT INTO `lx_document` VALUES ('10', '1', '', '设计师应当多考虑产品，而不是功能', '44', '0', '当我们谈到用户体验时，我们总会想到产品的简洁、美观、一系列易用的功能性它们为用户带去便捷。然而实际上，功能只是产品里微小的一部分。产品思维意味着考虑具体的用户需求', '0', '0', '2', '2', '7', '0', '7', '1', '0', '0', '72', '0', '0', '0', '1454314285', '1454330478', '1');
INSERT INTO `lx_document` VALUES ('11', '1', '', '中国工量具行业开启“互联网+”时代', '58', '0', '据悉，中国工量具商城是由哈尔滨林克斯贸易公司创建的，早在2012年，哈尔滨林克斯贸易公司就已经在工量具制造业如何结合互联网转型发展方面进行了思考，通过对我国工量具行业市', '0', '0', '2', '2', '3', '0', '8', '1', '0', '0', '133', '0', '0', '0', '1454314391', '1454376154', '1');
INSERT INTO `lx_document` VALUES ('12', '1', '', '张钧甯', '59', '0', '生活赋予我们一种巨大的和无限高贵的礼品，这就是青春：充满着力量，充满着期待志愿，充满着求知和斗争的志向，充满着希望信心和青春。', '0', '0', '2', '2', '7', '0', '9', '1', '0', '0', '0', '0', '0', '0', '1454319419', '1454319419', '1');
INSERT INTO `lx_document` VALUES ('13', '1', '', '杨颖', '59', '0', '十年历程，我们经历了风雨也看见了彩虹;十年沉淀，我们一如既往专注高品质专业服务;十年回首，感恩一路支持的朋友们。安华瓷砖将从客户的全方位需求出发，致力于为全球消费者', '0', '0', '2', '2', '7', '0', '10', '1', '0', '0', '1', '0', '0', '0', '1454319604', '1454319604', '1');
INSERT INTO `lx_document` VALUES ('14', '1', '', '唐嫣', '59', '0', '对于任何一家成功的企业来说，服务都是至关重要的。好产品 好服务=好的市场口碑。为了给消费者提供完善优质的服务，安华瓷砖设立了感动365服务，赢得了无数客户的信任和认可，', '0', '0', '2', '2', '7', '0', '11', '1', '0', '0', '22', '0', '0', '0', '1454319713', '1454319713', '1');
INSERT INTO `lx_document` VALUES ('15', '1', '', '刘亦菲', '59', '0', '我们不仅期待通过企业创造的差异化价值，让我们的品牌具有较高价值的使用功能与物理属性的产品，更要通过贯注文化、传承文化，与消费者进行情感和生活上的沟通与共鸣，变得有', '0', '0', '2', '2', '7', '0', '12', '1', '0', '0', '2', '0', '0', '0', '1454319772', '1454319772', '1');
INSERT INTO `lx_document` VALUES ('16', '1', '', '林允儿', '59', '0', '对于自己一手培育起来的事业，秦道禄认为，作为一个年轻的品牌，在各个方面都存在着不稳定的因素，竞争对手可以轻而易举超越关公坊。因此，他对自己的定位是一个服务者，抓好', '0', '0', '2', '2', '7', '0', '13', '1', '0', '0', '1', '0', '0', '0', '1454319826', '1454319826', '1');

-- ----------------------------
-- Table structure for `lx_document_article`
-- ----------------------------
DROP TABLE IF EXISTS `lx_document_article`;
CREATE TABLE `lx_document_article` (
  `id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文档ID',
  `parse` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '内容解析类型',
  `content` text NOT NULL COMMENT '文章内容',
  `template` varchar(100) NOT NULL DEFAULT '' COMMENT '详情页显示模板',
  `bookmark` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '收藏数',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文档模型文章表';

-- ----------------------------
-- Records of lx_document_article
-- ----------------------------
INSERT INTO `lx_document_article` VALUES ('1', '0', '<h1>\r\n	OneThink1.1开发版发布&nbsp;\r\n</h1>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	<strong>OneThink是一个开源的内容管理框架，基于最新的ThinkPHP3.2版本开发，提供更方便、更安全的WEB应用开发体验，采用了全新的架构设计和命名空间机制，融合了模块化、驱动化和插件化的设计理念于一体，开启了国内WEB应用傻瓜式开发的新潮流。&nbsp;</strong> \r\n</p>\r\n<h2>\r\n	主要特性：\r\n</h2>\r\n<p>\r\n	1. 基于ThinkPHP最新3.2版本。\r\n</p>\r\n<p>\r\n	2. 模块化：全新的架构和模块化的开发机制，便于灵活扩展和二次开发。&nbsp;\r\n</p>\r\n<p>\r\n	3. 文档模型/分类体系：通过和文档模型绑定，以及不同的文档类型，不同分类可以实现差异化的功能，轻松实现诸如资讯、下载、讨论和图片等功能。\r\n</p>\r\n<p>\r\n	4. 开源免费：OneThink遵循Apache2开源协议,免费提供使用。&nbsp;\r\n</p>\r\n<p>\r\n	5. 用户行为：支持自定义用户行为，可以对单个用户或者群体用户的行为进行记录及分享，为您的运营决策提供有效参考数据。\r\n</p>\r\n<p>\r\n	6. 云端部署：通过驱动的方式可以轻松支持平台的部署，让您的网站无缝迁移，内置已经支持SAE和BAE3.0。\r\n</p>\r\n<p>\r\n	7. 云服务支持：即将启动支持云存储、云安全、云过滤和云统计等服务，更多贴心的服务让您的网站更安心。\r\n</p>\r\n<p>\r\n	8. 安全稳健：提供稳健的安全策略，包括备份恢复、容错、防止恶意攻击登录，网页防篡改等多项安全管理功能，保证系统安全，可靠、稳定的运行。&nbsp;\r\n</p>\r\n<p>\r\n	9. 应用仓库：官方应用仓库拥有大量来自第三方插件和应用模块、模板主题，有众多来自开源社区的贡献，让您的网站“One”美无缺。&nbsp;\r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	<strong>&nbsp;OneThink集成了一个完善的后台管理体系和前台模板标签系统，让你轻松管理数据和进行前台网站的标签式开发。&nbsp;</strong> \r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<h2>\r\n	后台主要功能：\r\n</h2>\r\n<p>\r\n	1. 用户Passport系统\r\n</p>\r\n<p>\r\n	2. 配置管理系统&nbsp;\r\n</p>\r\n<p>\r\n	3. 权限控制系统\r\n</p>\r\n<p>\r\n	4. 后台建模系统&nbsp;\r\n</p>\r\n<p>\r\n	5. 多级分类系统&nbsp;\r\n</p>\r\n<p>\r\n	6. 用户行为系统&nbsp;\r\n</p>\r\n<p>\r\n	7. 钩子和插件系统\r\n</p>\r\n<p>\r\n	8. 系统日志系统&nbsp;\r\n</p>\r\n<p>\r\n	9. 数据备份和还原\r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	&nbsp;[ 官方下载：&nbsp;<a href=\"http://www.onethink.cn/download.html\" target=\"_blank\">http://www.onethink.cn/download.html</a>&nbsp;&nbsp;开发手册：<a href=\"http://document.onethink.cn/\" target=\"_blank\">http://document.onethink.cn/</a>&nbsp;]&nbsp;\r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	<strong>OneThink开发团队 2013~2014</strong> \r\n</p>', '', '0');
INSERT INTO `lx_document_article` VALUES ('3', '0', '', '', '0');
INSERT INTO `lx_document_article` VALUES ('4', '0', '<p>\r\n	这个一个基于onethink的开发框架，做了一些适当的修改，作为公司的PHP通用开发框架。\r\n</p>\r\n<p>\r\n	<img src=\"/Uploads/Editor/2016-01-18/569ccb3c969ee.jpg\" alt=\"\" /> \r\n</p>\r\n主要修改点：<br />\r\n1、把内核从ThinkPHP_3.2.3beta，改为 ThinkPHP_3.2.3 full版： &nbsp;即换掉ThinkPHP目录，并且把\\ThinkPHP\\Library\\OT\\拷贝过来<br />\r\n2、去掉install模块，改为手工安装<br />\r\n3、增加代码生成器CodeGenerator，自动生成单表管理代码<br />\r\n4、修改后台：把配置数据改为通用的数据，角色改为“管理员用户组”“内容编辑用户组”，默认管理员：lxadmin/lxadmin<br />\r\n4、修改前台，把原来的模版废弃掉，改为淘宝买到的模版模版，模版演示：http://demo866.dede58.com/。<br />', '', '0');
INSERT INTO `lx_document_article` VALUES ('5', '0', '<span style=\"color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px; line-height: 30px;\">七夕秀恩爱，也要看时间点，不能提前，也不能晚，因为秀恩爱，&ldquo;早晚&rdquo;都会死的快，所以我们选择了周五的中午，这样也可以让牛郎和织女的鹊桥更加坚固和持久，接下来，我们看看那些品牌又在七夕这一天玩了一把。 小知识：七夕节，又名乞巧节、七巧节或七姐诞，发源于中国，是华人地区以及部分受汉族文化影响的东亚国家传统节日，农历七月七日夜或七月六日夜妇女在庭院向织女星乞求智巧，故称为&ldquo;&rdquo;乞巧&ldquo;。其起源于对自然的崇拜及妇女穿针乞巧，后被赋予了牛郎织女的传说使其成为象征爱情的节日。</span><br style=\"box-sizing: border-box; color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px; line-height: 30px;\" />\r\n<br style=\"box-sizing: border-box; color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px; line-height: 30px;\" />\r\n<span style=\"color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px; line-height: 30px;\">项目亮点：当你手机收到微信，滑动后手机屏幕时(模拟真实手机碎屏效果)，看到女友的微信，却是要加班的节奏之时，老板给你一条不加班的微信，最后&hellip;&hellip;你加不加班，我不知道，但是我知道这一次，智联招聘打得算是成功，他们很用心的帮助一大波员工的心声说出他们不敢说的心声，只要动动手指，就能把自己想说的转出去，何乐而不为！又在侧面告诉用户，来我们智联上找工作，更加人性化，帮你寻觅的企业七夕节不会让你加班。可谓是一箭双雕。</span>', '', '0');
INSERT INTO `lx_document_article` VALUES ('6', '0', '<span style=\"color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px; line-height: 30px;\">文艺、走心的手绘风格 项目背景 雷霆战机周年庆，项目组为雷霆进行了拍摄了一部品牌短片，围绕整个影片策划了一个h5对其进行传播。 视频主要内容讲述几个小朋友从小心怀对星空的梦想，通过不断研究学习长大后当上了航天工作者完成了自己一直的梦想，来阐述如今繁华社会里仍有千千万万不断追随自己的内心的人。引发大家对天空的梦想的共鸣走情感路线。 前期构思 本次尝试了2套方向展现&mdash;&mdash;围绕视频内容的静帧截图直接展示，主打宇宙情怀的手绘风格文艺的展示。 此前移动端上大多是以单页大长图+视频按钮进行宣传的方式，作为首个尝试试水两种方向对最终视频造成的转化率影响。 风格推导 风格一 视频元素提取： 根据其视频提取关键元素：收音机、天文望远镜、宇宙飞船、风铃。</span><br style=\"box-sizing: border-box; color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px; line-height: 30px;\" />\r\n<br style=\"box-sizing: border-box; color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px; line-height: 30px;\" />\r\n<span style=\"color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px; line-height: 30px;\">整体氛围围绕四个关键词 科幻、复古、文艺和走心。 于是最终采用牛皮纸背景来展现复古+手绘主元素使科幻主题更添亲和力增添情怀，不同于以往的写实风。 为了在手机上更生动的展示，每一个元素搭配小动画来使手绘的物件更具有生命力（例收音机动画：打开天线&mdash;&mdash;搜频&mdash;&mdash;搜到声音）。一段描述性的文案搭配小动画增加画面故事的完整性，同时配合空灵的背景声效，让用户在体验h5的时候，在符合移动端用户体验同时让用户在视觉、听觉上感受到情怀产生共鸣。</span><br style=\"box-sizing: border-box; color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px; line-height: 30px;\" />\r\n<br style=\"box-sizing: border-box; color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px; line-height: 30px;\" />\r\n<span style=\"color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px; line-height: 30px;\">1.TVC的推广，使用视频本身元素和再创作元素，对于传播哪一个更合适？ 一开始老板担心跟视频本身的结合度不够，项目组对于手绘风格与游戏内风格差异大有所担忧，于是首推的是静帧视频截图版本，出于探究精神我们在微信公众账号对手绘版本进行传播。 （当时老板的担心，项目组的倾向，设计团队的坚持，最终加推后的数据反馈） &nbsp;从最终的数据可以看到，手绘版本在只有唯一宣传入口（微信公众账号）和视频静帧有多方的宣传渠道的对比下，两者的数据达到相当，且手绘版本的点击全文阅读量远高于以往文章点击量，由此可见在创作元素来宣传视频并无影响，游戏用户更多是乐于接受不同风格的尝试。</span><br style=\"box-sizing: border-box; color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px; line-height: 30px;\" />\r\n<br style=\"box-sizing: border-box; color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px; line-height: 30px;\" />\r\n<span style=\"color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px; line-height: 30px;\">2.手绘插画类型的H5，在设计和重构阶段应该注意的问题 A．给到重构的psd文件分层将每个元素合理打包能减少重构负担提高工作效率，将主元素转化为智能对象和背景分开，若主元素内有单独需要动画的部件，再另外区分开来。 &nbsp; 3． H5需要的插画类型与传统插画的区别 A．由于手机屏幕的限制，一屏内出现一个主要元素最为舒适，更容易引导用户在体验中看到重点。画面排版过满容易分散用户注意力失去焦点。 B．矢量扁平风格的插画和可复用背景能大大减少文件包大小提升加载速度。 C．简单的图形加上微妙的肌理感能瞬间提升画面质感。</span>', '', '0');
INSERT INTO `lx_document_article` VALUES ('7', '0', '<span style=\"color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px; line-height: 30px;\">在市面上有一大批H5页面模板制作工具，诚然，他们方便了很多非专业设计师设计制作H5页面。但是不得不吐槽的是，很多模板工具平台上的作品大部分还停留在左飞入右飞出的初级境界，看多了真心有些腻，动效如无美感还不如看静态的。&nbsp;那H5里有哪些高级动效了？小派几乎仔细体验了国内所有优秀H5页面作品，结合自己的一些理解，整理出下面十大H5页面特效。我们的H5作品如果能用上其中一两个，相信能增色不少！术语也许不够专业，欢迎吐槽！</span>\r\n<h4 style=\"box-sizing: border-box; font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-weight: 500; line-height: 30px; color: rgb(102, 102, 102); margin-top: 10px; margin-bottom: 10px; font-size: 18px;\">\r\n	先说说展示方面的动画特效！</h4>\r\n<h3 style=\"box-sizing: border-box; font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; line-height: 36px; color: rgb(51, 51, 51); margin: 25px 0px; font-size: 24px;\">\r\n	1. 粒子特效</h3>\r\n<img alt=\"0\" src=\"/Uploads/allimg/151202/0UH343D-0.gif\" style=\"box-sizing: border-box; border: 0px; vertical-align: middle; max-width: 100%; margin: 8px 0px; color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px; line-height: 30px;\" /><br style=\"box-sizing: border-box; color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px; line-height: 30px;\" />\r\n<br style=\"box-sizing: border-box; color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px; line-height: 30px;\" />\r\n<span style=\"color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px; line-height: 30px;\">星际传奇：这是探索宇宙的门票&nbsp;移动网站&nbsp;百度百科：为模拟现实中的水、火、雾、气等效果由各种三维软件开发的制作模块，原理是将无数的单个粒子组合使其呈现出固定形态，借由控制器，脚本来控制其整体或单个的运动，模拟出现真实的效果。 如上作品，那些粒子流和小火花，就属于粒子特效，很有真实感。</span>\r\n<h3 style=\"box-sizing: border-box; font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; line-height: 36px; color: rgb(51, 51, 51); margin: 25px 0px; font-size: 24px;\">\r\n	2. SVG路径动画</h3>\r\n<img alt=\"\" data-original=\"/Uploads/allimg/151009/2312562V1-1.jpg\" src=\"/Uploads/allimg/151202/0UH33a4-1.jpg\" style=\"box-sizing: border-box; border: 0px; vertical-align: middle; height: auto; max-width: 100%; margin: 8px 0px; color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px; line-height: 30px;\" /><br style=\"box-sizing: border-box; color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px; line-height: 30px;\" />\r\n<span style=\"color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px; line-height: 30px;\">元小望：SVG练习NO.1_描述孤独&nbsp;简而言之，就是让SVG的描边像是有人绘制一样的动画效果。动画轻巧不失真，特别适合那些崇尚简约设计风格的网页。 上作品是派友（Epub360用户）元小望用Epub360设计制作的，大家感受下，是不是很酷！</span>\r\n<h3 style=\"box-sizing: border-box; font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; line-height: 36px; color: rgb(51, 51, 51); margin: 25px 0px; font-size: 24px;\">\r\n	3. 序列帧动画</h3>\r\n<img alt=\"\" data-original=\"https://mmbiz.qlogo.cn/mmbiz/cwcCWQCa4xfvZWvA5Jz2aibNztbk0kZeNxEKKpsxS4JNZ9olYXGUKem62l1FOAIiaFtMEU28Guhnd0yXZO7gc7zw/0?wx_fmt=jpeg\" src=\"https://mmbiz.qlogo.cn/mmbiz/cwcCWQCa4xfvZWvA5Jz2aibNztbk0kZeNxEKKpsxS4JNZ9olYXGUKem62l1FOAIiaFtMEU28Guhnd0yXZO7gc7zw/0?wx_fmt=jpeg\" style=\"box-sizing: border-box; border: 0px; vertical-align: middle; height: auto; max-width: 100%; margin: 8px 0px; color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px; line-height: 30px;\" /><br style=\"box-sizing: border-box; color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px; line-height: 30px;\" />\r\n<span style=\"color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px; line-height: 30px;\">百度百科：序列帧动画是只在时间轴的每帧上逐帧绘制不同的内容，使其连续播放而成动画。 因为逐帧动画的帧序列内容不一样，不但给制作增加了负担而且最终输出的文件量也很大，但它的优势也很明显：逐帧动画具有非常大的灵活性，几乎可以表现任何想表现的内容，而它类似与电影的播放模式，很适合于表演细腻的动画。例如：人物或动物急剧转身、头发及衣服的飘动、走路、说话以及精致的3D效果等等。 派友晗萧模仿原版用Epub360制作了&ldquo;我们之间只有一个字&rdquo;的优化版，大家看看Epub360序列帧组件性能如何？</span>\r\n<h3 style=\"box-sizing: border-box; font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; line-height: 36px; color: rgb(51, 51, 51); margin: 25px 0px; font-size: 24px;\">\r\n	4. 全线性动画</h3>\r\n<img alt=\"\" data-original=\"https://mmbiz.qlogo.cn/mmbiz/cwcCWQCa4xfvZWvA5Jz2aibNztbk0kZeNjHbJMicApSAicSaqvf7MAJe2d37JhvEf8vzAnViaVAyjEkB9zzyNTH1iaw/0?wx_fmt=jpeg\" src=\"https://mmbiz.qlogo.cn/mmbiz/cwcCWQCa4xfvZWvA5Jz2aibNztbk0kZeNjHbJMicApSAicSaqvf7MAJe2d37JhvEf8vzAnViaVAyjEkB9zzyNTH1iaw/0?wx_fmt=jpeg\" style=\"box-sizing: border-box; border: 0px; vertical-align: middle; height: auto; max-width: 100%; margin: 8px 0px; color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px; line-height: 30px;\" /><br style=\"box-sizing: border-box; color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px; line-height: 30px;\" />\r\n<span style=\"color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px; line-height: 30px;\">腾讯UP大会：生命之下，想象之上&nbsp;全线性动画可以理解为动画连续，几乎不间断播放，像视频一样流畅细腻。 这支H5打破了传统幻灯片式的呈现方式，塑造出了一种宽广、素雅、幽静的整体感受，该作品也被很多人推崇为H5里的动画片。</span>\r\n<h3 style=\"box-sizing: border-box; font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; line-height: 36px; color: rgb(51, 51, 51); margin: 25px 0px; font-size: 24px;\">\r\n	5. Cinemagraphic</h3>\r\n<img alt=\"20150720142216_11924\" src=\"/Uploads/allimg/151202/0UH34596-2.gif\" style=\"box-sizing: border-box; border: 0px; vertical-align: middle; height: auto; max-width: 100%; margin: 8px 0px; color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px; line-height: 30px;\" /><br style=\"box-sizing: border-box; color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px; line-height: 30px;\" />\r\n<span style=\"color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px; line-height: 30px;\">Levi&#39;s: &ldquo;换&rdquo;醒你的夏天&nbsp;国内首个Cinemagraphic互动广告&nbsp;什么是Cinemegraphic？如果你不知道，就有点OUT了。顾名思义，Cinema是电影摄影，Graph是图片，Cinemagraphic是一项将神奇的局部运动赋予静态照片的新技术。 其中Cinemagraphic的应用恰到好处地了诠释了&ldquo;自然风&rdquo;的概念，只见画面上，人物的头发和衣角飞舞着，仿佛吹拂着一阵阵自然风，在炎炎夏日，他们依旧感受着清爽，尽情玩耍。作为互动者的人们，当看到画面上被风拂过的Cinemagraphic动态场景，他们也身临其境地感受到自然风所能带来的这种清爽感受。</span>\r\n<h4 style=\"box-sizing: border-box; font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-weight: 500; line-height: 30px; color: rgb(102, 102, 102); margin-top: 10px; margin-bottom: 10px; font-size: 18px;\">\r\n	下面说说和交互相关的动画特效。</h4>\r\n<h3 style=\"box-sizing: border-box; font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; line-height: 36px; color: rgb(51, 51, 51); margin: 25px 0px; font-size: 24px;\">\r\n	6. 全景</h3>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 10px; line-height: 30px; color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px;\">\r\n	<img alt=\"\" data-original=\"https://mmbiz.qlogo.cn/mmbiz/cwcCWQCa4xfvZWvA5Jz2aibNztbk0kZeN1cEJibA7vjzdricyDZ0g70pCXreT575YVER68np8zmREbLWibzE1TkS6A/0?wx_fmt=jpeg\" src=\"https://mmbiz.qlogo.cn/mmbiz/cwcCWQCa4xfvZWvA5Jz2aibNztbk0kZeN1cEJibA7vjzdricyDZ0g70pCXreT575YVER68np8zmREbLWibzE1TkS6A/0?wx_fmt=jpeg\" style=\"box-sizing: border-box; border: 0px; vertical-align: middle; height: auto; max-width: 100%; margin: 8px 0px;\" /></p>\r\n<span style=\"color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px; line-height: 30px;\">杜蕾斯的第一座美术馆&nbsp;移动网站&nbsp;虚拟全景美术馆的概念并不新鲜。其鼻祖应该是 Google 的 Art Project，让你能够在线浏览全世界大多数博物馆和美术馆。杜蕾斯&ldquo;美术馆&rdquo;的创新，在于它其实是热门广告形式H5页面的伪装。&ldquo;我们想要通过多重手段（比如馆内的彩蛋、12 点闭馆无法访问等等）来创造一个虚拟的真实空间，而不是传统H5的单线程教育的逻辑。&rdquo; Socialab 杜蕾斯组的负责人说，&ldquo;液体主义群展将是杜蕾斯美术馆无数展览中的第一个。&rdquo;</span>\r\n<h3 style=\"box-sizing: border-box; font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; line-height: 36px; color: rgb(51, 51, 51); margin: 25px 0px; font-size: 24px;\">\r\n	7. 3D</h3>\r\n<img alt=\"\" data-original=\"https://mmbiz.qlogo.cn/mmbiz/cwcCWQCa4xfvZWvA5Jz2aibNztbk0kZeNicibvRpvwrZVmw1UwXlJTmGIJKSLxMQ8glcpr9oXMmb3UmTnviaAeicnGw/0?wx_fmt=png\" src=\"https://mmbiz.qlogo.cn/mmbiz/cwcCWQCa4xfvZWvA5Jz2aibNztbk0kZeNicibvRpvwrZVmw1UwXlJTmGIJKSLxMQ8glcpr9oXMmb3UmTnviaAeicnGw/0?wx_fmt=png\" style=\"box-sizing: border-box; border: 0px; vertical-align: middle; height: auto; max-width: 100%; margin: 8px 0px; color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px; line-height: 30px;\" /><span style=\"color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px; line-height: 30px;\">&nbsp;</span><br style=\"box-sizing: border-box; color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px; line-height: 30px;\" />\r\n<span style=\"color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px; line-height: 30px;\">康师傅：2015最飞羊的新春祝愿&nbsp;祝福灯笼可以360旋转呈现，而且具有夜空繁星中题字灯笼飞来飞去的3D炫目效果，彰显&ldquo;最飞羊的新春祝愿&rdquo;。其中意趣和精妙所在，恐非文字所能表述。</span>\r\n<h3 style=\"box-sizing: border-box; font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; line-height: 36px; color: rgb(51, 51, 51); margin: 25px 0px; font-size: 24px;\">\r\n	8. 点击碎屏</h3>\r\n<img alt=\"\" data-original=\"https://mmbiz.qlogo.cn/mmbiz/cwcCWQCa4xfvZWvA5Jz2aibNztbk0kZeN1gTO6tBmv19pY179JibUzs6OtJnRDZVSWibaCx9QCz1NEkQUlELsUdfg/0?wx_fmt=jpeg\" src=\"https://mmbiz.qlogo.cn/mmbiz/cwcCWQCa4xfvZWvA5Jz2aibNztbk0kZeN1gTO6tBmv19pY179JibUzs6OtJnRDZVSWibaCx9QCz1NEkQUlELsUdfg/0?wx_fmt=jpeg\" style=\"box-sizing: border-box; border: 0px; vertical-align: middle; height: auto; max-width: 100%; margin: 8px 0px; color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px; line-height: 30px;\" /><br style=\"box-sizing: border-box; color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px; line-height: 30px;\" />\r\n<br style=\"box-sizing: border-box; color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px; line-height: 30px;\" />\r\n<span style=\"color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px; line-height: 30px;\">大众点评：这个陌生来电你敢接吗？ 移动网站&nbsp;&ldquo;点击屏幕&rdquo;不新鲜，但是这种屏幕击碎的画面好像也特别讨人喜欢，大概有三轮左右的&ldquo;击碎&rdquo;动作，这是整个H5的互动高峰。发现这种&ldquo;屏幕敲击&rdquo;的常规动作特别有惯性，点一点就停不下来。是不是抓住了手机族的某些&ldquo;强迫症&rdquo;特点。</span>\r\n<h3 style=\"box-sizing: border-box; font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; line-height: 36px; color: rgb(51, 51, 51); margin: 25px 0px; font-size: 24px;\">\r\n	9. 长按逐字</h3>\r\n<img alt=\"\" data-original=\"https://mmbiz.qlogo.cn/mmbiz/cwcCWQCa4xfvZWvA5Jz2aibNztbk0kZeN0dZP7ickhvBWDeYZABvicaJSW7dQGq4dzib2XJFJfTZJdPO1NwMJqkqNg/0?wx_fmt=jpeg\" src=\"https://mmbiz.qlogo.cn/mmbiz/cwcCWQCa4xfvZWvA5Jz2aibNztbk0kZeN0dZP7ickhvBWDeYZABvicaJSW7dQGq4dzib2XJFJfTZJdPO1NwMJqkqNg/0?wx_fmt=jpeg\" style=\"box-sizing: border-box; border: 0px; vertical-align: middle; height: auto; max-width: 100%; margin: 8px 0px; color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px; line-height: 30px;\" /><br style=\"box-sizing: border-box; color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px; line-height: 30px;\" />\r\n<br style=\"box-sizing: border-box; color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px; line-height: 30px;\" />\r\n<span style=\"color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px; line-height: 30px;\">韩寒再谈一加：1步1步看清韩寒&nbsp;移动网站&nbsp;整个H5页面用打字机的形式呈现，随着用户按下按钮，纸片会逐渐显示出韩寒从1999年起，为人熟知或不知的成长轨迹。触发逐字等动效很有真实感。</span>\r\n<h3 style=\"box-sizing: border-box; font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; line-height: 36px; color: rgb(51, 51, 51); margin: 25px 0px; font-size: 24px;\">\r\n	10.滑动触发</h3>\r\n<p style=\"box-sizing: border-box; margin: 0px 0px 10px; line-height: 30px; color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px;\">\r\n	<img alt=\"\" data-original=\"https://mmbiz.qlogo.cn/mmbiz/cwcCWQCa4xfvZWvA5Jz2aibNztbk0kZeNTuPfqA4IpgVdnpnKQAWgribWMXkBrSCG2c4F9QYu3CHf85Iu8LiaghWQ/0?wx_fmt=jpeg\" src=\"https://mmbiz.qlogo.cn/mmbiz/cwcCWQCa4xfvZWvA5Jz2aibNztbk0kZeNTuPfqA4IpgVdnpnKQAWgribWMXkBrSCG2c4F9QYu3CHf85Iu8LiaghWQ/0?wx_fmt=jpeg\" style=\"box-sizing: border-box; border: 0px; vertical-align: middle; height: auto; max-width: 100%; margin: 8px 0px;\" /></p>\r\n<span style=\"color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px; line-height: 30px;\">腾讯视频：这一幕你期待已久&nbsp;移动网站&nbsp;长页H5，滑动触发动效，和呆板的幻灯片式样的H5拉开了距离。Epub360虽然能实现带动效带触发的长页，但是目前暂时还不能实现这种滑动触发动画。</span>', '', '0');
INSERT INTO `lx_document_article` VALUES ('8', '0', '&lt;div style=\"box-sizing: border-box; color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px; line-height: 30px;\"&gt;<br />\r\n众所周知，我们总是依据封面来判断书的好坏，聪明的设计师会创造实用有吸引力的界面。潜在用户可能会被吸引，但如何一直黏住他们呢？ 要试着回答这个问题，所有一切都指向人本设计，其中用户是最主要的考量。以人为本：你的应用应该使用日常用语，包括情绪、口语，外观还要有一丝&amp;ldquo;诱惑力&amp;rdquo;。界面应当成为你的好朋友，时刻准备给出建议提升你的体验，让你会心一笑。 现在揭幕：是微交互在起作用。精确的说，这主要是界面附带的交互动画，使它更具表现力。优秀的动画能够：&lt;/div&gt;<br />\r\n&lt;div style=\"box-sizing: border-box; color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px; line-height: 30px;\"&gt;<br />\r\n在用户体验中，关键是你如何对待用户，还有他们使用产品时的感受。极小的细节都值得加倍留心。微交互提供了用户所需的反馈，表达了当前运行状况。无论背后逻辑有多么复杂，都能使界面更亲切。&lt;/div&gt;<br />\r\n&lt;div style=\"box-sizing: border-box; color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px; line-height: 30px;\"&gt;<br />\r\n1. 显示系统状态&lt;/div&gt;<br />\r\n&lt;div style=\"box-sizing: border-box; color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px; line-height: 30px;\"&gt;<br />\r\n&amp;nbsp;&lt;/div&gt;<br />\r\n&lt;div style=\"box-sizing: border-box; color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px; line-height: 30px;\"&gt;<br />\r\nJakob Nielsen在可用性原则启示第一条中描述：让用户始终知晓当前在发生什么。用户希望立马得到回应，但总有些情况下，网站需要一点时间等待操作完成。 那么，界面就应当在背景处显示图形，反映完成百分比。或是播放声音，让用户了解当前发生的事情。这个原则也关系到文件传输：不要让用户觉得无聊，给他们看进度条。即使是不太令人愉快的通知，比如传输失败，也应该以令人喜爱的方式展现。让你的用户微笑！&lt;/div&gt;<br />\r\n&lt;div style=\"box-sizing: border-box; color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px; line-height: 30px;\"&gt;<br />\r\n001b 001c&lt;/div&gt;<br />\r\n&lt;div style=\"box-sizing: border-box; color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px; line-height: 30px;\"&gt;<br />\r\n&amp;nbsp;&lt;/div&gt;<br />\r\n&lt;div style=\"box-sizing: border-box; color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px; line-height: 30px;\"&gt;<br />\r\n2. 突出显示变化&lt;/div&gt;<br />\r\n&lt;div style=\"box-sizing: border-box; color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px; line-height: 30px;\"&gt;<br />\r\n&amp;nbsp;&lt;/div&gt;<br />\r\n&lt;div style=\"box-sizing: border-box; color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px; line-height: 30px;\"&gt;<br />\r\n通常为了节省空间，应用会在需要时把某个按钮替换掉。有时我们需要展现通知，确保用户注意到了。动画可以吸引他们注意，不至于忽略你认为重要的信息。&lt;/div&gt;<br />\r\n&lt;div style=\"box-sizing: border-box; color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px; line-height: 30px;\"&gt;<br />\r\n002a 002b 002c&lt;/div&gt;<br />\r\n&lt;div style=\"box-sizing: border-box; color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px; line-height: 30px;\"&gt;<br />\r\n&amp;nbsp;&lt;/div&gt;<br />\r\n&lt;div style=\"box-sizing: border-box; color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px; line-height: 30px;\"&gt;<br />\r\n3. 保持前后关联&lt;/div&gt;<br />\r\n&lt;div style=\"box-sizing: border-box; color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px; line-height: 30px;\"&gt;<br />\r\n&amp;nbsp;&lt;/div&gt;<br />\r\n&lt;div style=\"box-sizing: border-box; color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px; line-height: 30px;\"&gt;<br />\r\n在这个智能手机和小屏幕智能手表的时代，难以在屏幕上展现大量信息。有一种处理方式，是在不同页面之间保持清晰的导航。让用户理解什么东西来自何处，便能轻易回溯。有多种方式可以实现：&lt;/div&gt;<br />\r\n&lt;div style=\"box-sizing: border-box; color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px; line-height: 30px;\"&gt;<br />\r\n003a 003b&lt;/div&gt;<br />\r\n&lt;div style=\"box-sizing: border-box; color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px; line-height: 30px;\"&gt;<br />\r\n&amp;nbsp;&lt;/div&gt;<br />\r\n&lt;div style=\"box-sizing: border-box; color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px; line-height: 30px;\"&gt;<br />\r\n4. 非标准布局&lt;/div&gt;<br />\r\n&lt;div style=\"box-sizing: border-box; color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px; line-height: 30px;\"&gt;<br />\r\n&amp;nbsp;&lt;/div&gt;<br />\r\n&lt;div style=\"box-sizing: border-box; color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px; line-height: 30px;\"&gt;<br />\r\n继续之前的例子，微交互应当帮助用户理解如何操作非标准的布局，去除不必要的疑惑。照片前后滑动、滚动式图表和旋转角色都是很棒的选择：&lt;/div&gt;<br />\r\n&lt;div style=\"box-sizing: border-box; color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px; line-height: 30px;\"&gt;<br />\r\n004a 004b 004c&lt;/div&gt;<br />\r\n&lt;div style=\"box-sizing: border-box; color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px; line-height: 30px;\"&gt;<br />\r\n&amp;nbsp;&lt;/div&gt;<br />\r\n&lt;div style=\"box-sizing: border-box; color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px; line-height: 30px;\"&gt;<br />\r\n5. 行动号召&lt;/div&gt;<br />\r\n&lt;div style=\"box-sizing: border-box; color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px; line-height: 30px;\"&gt;<br />\r\n&amp;nbsp;&lt;/div&gt;<br />\r\n&lt;div style=\"box-sizing: border-box; color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px; line-height: 30px;\"&gt;<br />\r\n除了帮助用户有效地操作应用，微交互也有鼓励用户操作的能力：持续浏览、点赞或分享内容，只因为这很有吸引力，用户不舍离去：&lt;/div&gt;<br />\r\n&lt;div style=\"box-sizing: border-box; color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px; line-height: 30px;\"&gt;<br />\r\n005a 005b 005c&lt;/div&gt;<br />\r\n&lt;div style=\"box-sizing: border-box; color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px; line-height: 30px;\"&gt;<br />\r\n&amp;nbsp;&lt;/div&gt;<br />\r\n&lt;div style=\"box-sizing: border-box; color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px; line-height: 30px;\"&gt;<br />\r\n6. 输入的视觉化&lt;/div&gt;<br />\r\n&lt;div style=\"box-sizing: border-box; color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px; line-height: 30px;\"&gt;<br />\r\n&amp;nbsp;&lt;/div&gt;<br />\r\n&lt;div style=\"box-sizing: border-box; transition: all 0.3s linear; color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px; line-height: 30px;\"&gt;<br />\r\n所有应用中，数据录入都是最重要的元素之一。数据录入决定了用户所得结果的质量。通常，这部分很无趣，但微交互可以使它与众不同：&lt;/div&gt;<br />\r\n&lt;div style=\"box-sizing: border-box; color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px; line-height: 30px;\"&gt;<br />\r\n006a 006b 006c&lt;/div&gt;<br />\r\n&lt;div style=\"box-sizing: border-box; color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px; line-height: 30px;\"&gt;<br />\r\n&amp;nbsp;&lt;/div&gt;<br />\r\n&lt;div style=\"box-sizing: border-box; color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px; line-height: 30px;\"&gt;<br />\r\n7. 使教程生动形象&lt;/div&gt;<br />\r\n&lt;div style=\"box-sizing: border-box; color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px; line-height: 30px;\"&gt;<br />\r\n&amp;nbsp;&lt;/div&gt;<br />\r\n&lt;div style=\"box-sizing: border-box; color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px; line-height: 30px;\"&gt;<br />\r\n当然，在应用发布后，动画可以教育用户。它突出一些基本功能和控件，排除用户在未来的使用中的障碍。&lt;/div&gt;<br />\r\n<div>\r\n	<br />\r\n</div>', '', '0');
INSERT INTO `lx_document_article` VALUES ('9', '0', '&lt;div style=\"box-sizing: border-box; transition: all 0.3s linear; color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px; line-height: 30px;\"&gt;<br />\r\n【小编有话说】首先给大家隆重介绍下腾讯互动娱乐市场部的专业设计团队--TGideas，爱果果收录过好多这个团队开发的H5网站，无论从创意还是技术上他们的作品都在引领这H5发展的方向，是小编非常仰慕的一个团队。 这篇文章原标题《2015发布会回顾-品牌篇》，是腾讯2015发布会的一个预热H5从立项到创意再到开发的回顾，小编通读后发现这其实是一篇非常好的教程类文章，给那些正在摸索或者准备要做H5的小伙伴们指明了开发的路线，必须重点推荐给大家。 前方高能预警！！！&lt;/div&gt;<br />\r\n&lt;div style=\"box-sizing: border-box; color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px; line-height: 30px;\"&gt;<br />\r\n回顾2014年发布会的移动端邀请函，当时算是一款比较优秀的H5网页，在交互方式（动画、单屏滑动等）做了新的尝试，有非常好的反馈，成了后续H5学习的范例。一年过去了，H5大势流行了，各式各样，花样辈出，市场颇大。所以这一届发布会在移动端品牌建设有更大的挑战，创新突破的优良作风也继续坚持下去&amp;hellip;&amp;hellip;OH!&lt;br style=\"box-sizing: border-box;\" /&gt;<br />\r\n&lt;br style=\"box-sizing: border-box;\" /&gt;<br />\r\n&lt;div style=\"box-sizing: border-box;\"&gt;<br />\r\n这次发布会的整体包装，团队参与度也比较高，从前期策略规划的，了解目标对象，分析行业、媒体、大众人文特征及心理特征，把控发布会整体格调，预算管理&amp;hellip;&amp;hellip;，到创意策划与执行的视觉、视频、展会体验都有深入参与。 &amp;rdquo;想象力H5&amp;ldquo;在整个发布会包装中处于线上品牌线的预热阶段，我们的目的是要做出一个符合2015年发布品牌形象的H5，提出这一届发布会的品牌概念&amp;mdash;&amp;mdash;让想象绽放，总的来说就是要把气质搞起来。 接下来就开始进入正题了啦！&lt;/div&gt;<br />\r\n&lt;div style=\"box-sizing: border-box;\"&gt;<br />\r\n寻找想象力（头脑风暴）&lt;/div&gt;<br />\r\n&lt;div style=\"box-sizing: border-box;\"&gt;<br />\r\n&amp;nbsp;&lt;/div&gt;<br />\r\n&lt;div style=\"box-sizing: border-box;\"&gt;<br />\r\n我们已知的信息，2015年的slogan&amp;mdash;&amp;mdash;让想象绽放，然后整个创意都将围绕着&amp;ldquo;想象力&amp;rdquo;开始展开联想&amp;hellip;&amp;hellip; 发散发散，首先看看大家是怎么表现想象力的！ 游戏类 1.画面、故事、互动&amp;hellip;&amp;hellip;全方面营造展示想象力的氛围，将想象力具象，比较直白，多重刺激快速给人留下印象。例如： -纪念碑谷&amp;mdash;&amp;mdash;游戏体验不论从故事、画面还是互动，都充满了想象力，让人充满遐想，太爱了; -Gorogoa&amp;mdash;&amp;mdash;一款手工精心画出的世界悬浮的独特的益智游戏，整个游戏画面分为四大块，场景切换相当出乎意料，扑朔迷离； -谜画之塔&amp;mdash;&amp;mdash;解谜游戏，解救女孩Iris，游戏把具有想象力的画作通过想象力转化成场景，整个体验穿梭在城堡的画作和Iris的画作，进入华丽的想象力世界。&lt;/div&gt;<br />\r\n&lt;/div&gt;<br />\r\n&lt;br /&gt;<br />\r\n<div>\r\n	<br />\r\n</div>', '', '0');
INSERT INTO `lx_document_article` VALUES ('10', '0', '<div style=\"color:#666666;font-family:微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun;font-size:14px;\">\r\n	当我们谈到用户体验时，我们总会想到产品的简洁、美观、一系列易用的功能性——它们为用户带去便捷。然而实际上，功能只是产品里微小的一部分。产品思维意味着考虑具体的用户需求：为了完成工作、为了达到目标、为了提升效益。 1 用户体验的核心不是一堆产品功能，事实上，这是用户要拿这个产品来做的事。Uber的核心用户体验是无论何时何地都能轻易地叫到出租车。展现还有多久出租车可以到达的倒计时是一个延伸用户体验的合适的功能。但是Uber的产品远不仅仅依靠这个功能运行。另一方面，倒计时也无法脱离产品单独存在。功能和产品有种相互关系——功能无法脱离产品应用。这也是为什么设计师应当首先考虑产品，而不是功能。\r\n</div>\r\n<div style=\"color:#666666;font-family:微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun;font-size:14px;\">\r\n	发现产品的应用对象\r\n</div>\r\n<div style=\"color:#666666;font-family:微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun;font-size:14px;\">\r\n	&nbsp;\r\n</div>\r\n<div style=\"color:#666666;font-family:微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun;font-size:14px;\">\r\n	产品总有核心用户体验，它是产品存在的基础原因。它往往满足人们某种需求或是帮助人们完成某一项工作。只有这样，它才是在提供有价值的东西，才是有意义的。如果问题本身就不存在，又或者是解决方法不合适，产品就变得毫无意义，也没有人会去使用，反而会引起产品的衰败。错误的解决方案可以被修正但是我们对根本不存在的问题无能为力。所以，如何才能确定自己在解决一个真正的问题？我们无法百分百保证这一问题，但是可以通过观察用户、与用户交谈来尽量避免这个问题。所以发现用户在意的问题并想办法解决它吧！ Clay Christensen曾经想要提高奶昔的销量。他想让奶昔变得更甜一些，多增加一些口味并且将一份做得更大些。然而这些都没用。直到他仔细观察了来买奶昔的顾客，他发现来买奶昔的顾客只是想让他们上班的路上不再那么无聊——奶昔是口感浓稠的饮料，很久才能喝掉并且能填饱肚子。这是问题所在，却不是用户自己发现的，最终Christensen将奶昔做得更厚了些，果然销量随之上升了。\r\n</div>\r\n<div style=\"color:#666666;font-family:微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun;font-size:14px;\">\r\n	从产品出发，为特定的人群做特定的功能\r\n</div>\r\n<div style=\"color:#666666;font-family:微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun;font-size:14px;\">\r\n	&nbsp;\r\n</div>\r\n<div style=\"color:#666666;font-family:微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun;font-size:14px;\">\r\n	从产品出发有助于构建出成功的功能。通过界定产品所解决的功能——“为什么我们要做这个产品？”界定目标人群——“谁有这个问题需求？”确定解决方案——“我们怎么来做？”以上三点为创作新功能提供指导，确定目标有助于功能的成功。 2\r\n</div>\r\n<div style=\"color:#666666;font-family:微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun;font-size:14px;\">\r\n	为问题找到合适的解决方法\r\n</div>\r\n<div style=\"color:#666666;font-family:微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun;font-size:14px;\">\r\n	&nbsp;\r\n</div>\r\n<div style=\"color:#666666;font-family:微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun;font-size:14px;\">\r\n	当方法恰能解决问题时，产品就是完美的。方法描述了解决问题的具体流程。因此，问题和方法的契合度决定了产品的核心用户体验。具体的功能延伸了用户体验并支持但无法代替其核心。交互设计和视觉设计可以让产品美观、易用、让人喜欢甚至在竞争中脱颖而出，但它无法赋予产品意义。所以问题和方法的契合度才是产品是否成功的决定性因素。\r\n</div>\r\n<div style=\"color:#666666;font-family:微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun;font-size:14px;\">\r\n	产品的界定\r\n</div>\r\n<div style=\"color:#666666;font-family:微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun;font-size:14px;\">\r\n	&nbsp;\r\n</div>\r\n<div style=\"color:#666666;font-family:微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun;font-size:14px;\">\r\n	考虑到产品时，用户体验设计师首先需要回答下列问题：我们要解决什么（用户问题）？我们面向谁（目标人群）？我们为什么要做（愿景）？我们怎么做（策略）？我们到做到什么样（目标）？只有这样，我们思考究竟在做什么时才有意义。3\r\n</div>\r\n<div style=\"color:#666666;font-family:微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun;font-size:14px;\">\r\n	产品思维的力量\r\n</div>\r\n<div style=\"color:#666666;font-family:微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun;font-size:14px;\">\r\n	&nbsp;\r\n</div>\r\n<div style=\"color:#666666;font-family:微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun;font-size:14px;\">\r\n	产品思维有助于设计师为特定的人群构建特定的功能。有助于整体理解产品的用户体验，不仅仅是单纯的产品交互、视觉设计。它帮设计师确定做出的东西确能解决某个问题。无论何时它都能帮助作出正确的决定。产品思维帮助用户体验设计师提出恰当的问题、构建恰当的功能以及更高效地与相关人员沟通。帮助设计者说“不”并在增加新功能前深思熟虑。无论是需要一个新功能还是别人提出了一个新产品的点子，在绘制框架图做架构之前，设计者总能问出正确的问题——“它与产品契合吗？”、“他解决了现实的用户问题了吗”、“人们是想要还是需要？”——让我们先找出这些答案！如此，就能保持产品简洁而富有价值。 作出正确的决定是作出成功的、用户需要的产品的基础。产品思维使产品经理与交互设计师的沟通卓有成效，从而作出更强的产品。这就是为什么产品思维会成为下一个用户体验之魂。&nbsp;\r\n</div>', '', '0');
INSERT INTO `lx_document_article` VALUES ('11', '0', '<div style=\"margin:0px;padding:10px 0px 0px;font-family:\'Microsoft Yahei\', Arial, Verdana, Tahoma, Helvetica, sans-serif;color:#666666;font-size:14px;\">\r\n	据悉，中国工量具商城是由哈尔滨林克斯贸易公司创建的，早在2012年，哈尔滨林克斯贸易公司就已经在“工量具制造业”如何结合互联网转型发展方面进行了思考，通过对我国工量具行业市场的深入调研，明确了建设行业电子商务平台的实施方案。\r\n</div>\r\n<div style=\"margin:0px;padding:10px 0px 0px;font-family:\'Microsoft Yahei\', Arial, Verdana, Tahoma, Helvetica, sans-serif;color:#666666;font-size:14px;\">\r\n	中国工量具商城的建设目标是：携手工量具行业知名生产厂商，共同打造以工量具为核心的集服务、资源、信息、交易为一体的综合交易平台;打造行业内首家非标产品在线定制招投标管理平台; 打造行业商品流、资金流、物流、信息流的大数据集成平台。\r\n</div>\r\n<div style=\"margin:0px;padding:10px 0px 0px;font-family:\'Microsoft Yahei\', Arial, Verdana, Tahoma, Helvetica, sans-serif;color:#666666;font-size:14px;\">\r\n	中国工量具商城专注于打造符合行业发展趋势的、基于物流体系的O2O管理模式：即成为行业首家品牌专营平台;成为行业内企业拓展市场、推广产品的主要平台;成为行业内企业全国客户的订货管理平台;成为行业内企业国内市场销售资源分配管理平台;成为行业内企业国内市场售后服务综合管理平台。\r\n</div>\r\n<div style=\"margin:0px;padding:10px 0px 0px;font-family:\'Microsoft Yahei\', Arial, Verdana, Tahoma, Helvetica, sans-serif;color:#666666;font-size:14px;\">\r\n	中国工量具商城依托六十余年的行业经验，打造了具有独特优势的行业电商平台。一是，全商城均为厂家直销，产品质量和服务有保证;二是，众多“名品”汇聚，易于搜寻、比价;三是、商家O2O渠道管理模式，使物流更加快捷;四是、定制产品在线招标，流程短、速度快;五是、工量具学院为用户提供全面系统的专业知识;\r\n</div>\r\n<div style=\"margin:0px;padding:10px 0px 0px;font-family:\'Microsoft Yahei\', Arial, Verdana, Tahoma, Helvetica, sans-serif;color:#666666;font-size:14px;\">\r\n	中国工量具商城抓住“互联网+制造业”融合发展的机遇，通过电子商务平台整合行业资源，优化企业商业架构，实现“互联网+工量具行业”的转型升级，培育企业新的经济增长业态。同时必会给消费者带来非常舒适的购物体验。\r\n</div>', '', '0');
INSERT INTO `lx_document_article` VALUES ('12', '0', '&lt;span style=\"color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px; line-height: 30px;\"&gt;生活赋予我们一种巨大的和无限高贵的礼品，这就是青春：充满着力量，充满着期待志愿，充满着求知和斗争的志向，充满着希望信心和青春。&lt;/span&gt;', '', '0');
INSERT INTO `lx_document_article` VALUES ('13', '0', '&lt;div class=\"bd\" style=\"box-sizing: border-box; color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px; line-height: 30px;\"&gt;<br />\r\n十年历程，我们经历了风雨也看见了彩虹;十年沉淀，我们一如既往专注高品质专业服务;十年回首，感恩一路支持的朋友们。安华瓷砖将从客户的全方位需求出发，致力于为全球消费者提供更健康、更舒适、更具人性化的生活空间。&lt;/div&gt;<br />\r\n&lt;div&gt;<br />\r\n&amp;nbsp;&lt;/div&gt;<br />\r\n&lt;p align=\"center\" class=\"pageLink\" style=\"box-sizing: border-box; margin: 0px 0px 10px; line-height: 30px; color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px;\"&gt;<br />\r\n&amp;nbsp;&lt;/p&gt;<br />\r\n<div>\r\n	<br />\r\n</div>', '', '0');
INSERT INTO `lx_document_article` VALUES ('14', '0', '&lt;span style=\"color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px; line-height: 30px;\"&gt;对于任何一家成功的企业来说，服务都是至关重要的。好产品 好服务=好的市场口碑。为了给消费者提供完善优质的服务，安华瓷砖设立了感动365服务，赢得了无数客户的信任和认可，并且在3月份由网易家居发起的家居企业售后服务电话调查的上百家企业中获得了第四名的优异成绩，并被授予&amp;ldquo;最佳售后服务奖&amp;rdquo;的殊荣。&amp;ldquo;诚信是我们的态度、服务是我们的根本、创新是我们的目标&amp;rdquo;安华瓷砖负责人这样说道。安华瓷砖会始终坚持为广大客户提供优质的、专业化的贯穿售前、售中、售后的一站式服务。&lt;/span&gt;', '', '0');
INSERT INTO `lx_document_article` VALUES ('15', '0', '<span style=\"color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px; line-height: 30px;\">我们不仅期待通过企业创造的差异化价值，让我们的品牌具有较高价值的使用功能与物理属性的产品，更要通过贯注文化、传承文化，与消费者进行情感和生活上的沟通与共鸣，变得有血、有肉、有生命、有性格，成为消费者的一种心灵依恋。</span>', '', '0');
INSERT INTO `lx_document_article` VALUES ('16', '0', '<span style=\"color: rgb(102, 102, 102); font-family: 微软雅黑, \'Hiragino Sans GB\', \'Microsoft YaHei\', tahoma, arial, simsun; font-size: 14px; line-height: 30px;\">对于自己一手培育起来的事业，秦道禄认为，作为一个年轻的品牌，在各个方面都存在着不稳定的因素，竞争对手可以轻而易举超越关公坊。因此，他对自己的定位是一个服务者，抓好每一个环节，细化对品牌和市场的服务，从深层次挖掘服务内涵，将服务切实落到实处，成为关公坊人的共识。</span>', '', '0');

-- ----------------------------
-- Table structure for `lx_document_download`
-- ----------------------------
DROP TABLE IF EXISTS `lx_document_download`;
CREATE TABLE `lx_document_download` (
  `id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文档ID',
  `parse` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '内容解析类型',
  `content` text NOT NULL COMMENT '下载详细描述',
  `template` varchar(100) NOT NULL DEFAULT '' COMMENT '详情页显示模板',
  `file_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文件ID',
  `download` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '下载次数',
  `size` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '文件大小',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文档模型下载表';

-- ----------------------------
-- Records of lx_document_download
-- ----------------------------

-- ----------------------------
-- Table structure for `lx_file`
-- ----------------------------
DROP TABLE IF EXISTS `lx_file`;
CREATE TABLE `lx_file` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '文件ID',
  `name` char(30) NOT NULL DEFAULT '' COMMENT '原始文件名',
  `savename` char(20) NOT NULL DEFAULT '' COMMENT '保存名称',
  `savepath` char(30) NOT NULL DEFAULT '' COMMENT '文件保存路径',
  `ext` char(5) NOT NULL DEFAULT '' COMMENT '文件后缀',
  `mime` char(40) NOT NULL DEFAULT '' COMMENT '文件mime类型',
  `size` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文件大小',
  `md5` char(32) NOT NULL DEFAULT '' COMMENT '文件md5',
  `sha1` char(40) NOT NULL DEFAULT '' COMMENT '文件 sha1编码',
  `location` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '文件保存位置',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '远程地址',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上传时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_md5` (`md5`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文件表';

-- ----------------------------
-- Records of lx_file
-- ----------------------------

-- ----------------------------
-- Table structure for `lx_hooks`
-- ----------------------------
DROP TABLE IF EXISTS `lx_hooks`;
CREATE TABLE `lx_hooks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(40) NOT NULL DEFAULT '' COMMENT '钩子名称',
  `description` text COMMENT '描述',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '类型',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `addons` varchar(255) NOT NULL DEFAULT '' COMMENT '钩子挂载的插件 ''，''分割',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of lx_hooks
-- ----------------------------
INSERT INTO `lx_hooks` VALUES ('1', 'pageHeader', '页面header钩子，一般用于加载插件CSS文件和代码', '1', '0', '', '1');
INSERT INTO `lx_hooks` VALUES ('2', 'pageFooter', '页面footer钩子，一般用于加载插件JS文件和JS代码', '1', '0', 'ReturnTop', '1');
INSERT INTO `lx_hooks` VALUES ('3', 'documentEditForm', '添加编辑表单的 扩展内容钩子', '1', '0', 'Attachment', '1');
INSERT INTO `lx_hooks` VALUES ('4', 'documentDetailAfter', '文档末尾显示', '1', '0', 'Attachment,SocialComment', '1');
INSERT INTO `lx_hooks` VALUES ('5', 'documentDetailBefore', '页面内容前显示用钩子', '1', '0', '', '1');
INSERT INTO `lx_hooks` VALUES ('6', 'documentSaveComplete', '保存文档数据后的扩展钩子', '2', '0', 'Attachment', '1');
INSERT INTO `lx_hooks` VALUES ('7', 'documentEditFormContent', '添加编辑表单的内容显示钩子', '1', '0', 'Editor', '1');
INSERT INTO `lx_hooks` VALUES ('8', 'adminArticleEdit', '后台内容编辑页编辑器', '1', '1378982734', 'EditorForAdmin', '1');
INSERT INTO `lx_hooks` VALUES ('13', 'AdminIndex', '首页小格子个性化显示', '1', '1382596073', 'SiteStat,SystemInfo,DevTeam', '1');
INSERT INTO `lx_hooks` VALUES ('14', 'topicComment', '评论提交方式扩展钩子。', '1', '1380163518', 'Editor', '1');
INSERT INTO `lx_hooks` VALUES ('16', 'app_begin', '应用开始', '2', '1384481614', '', '1');

-- ----------------------------
-- Table structure for `lx_link`
-- ----------------------------
DROP TABLE IF EXISTS `lx_link`;
CREATE TABLE `lx_link` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '标识 (唯一标识)',
  `group` varchar(512) NOT NULL DEFAULT '' COMMENT '分组 (即网站上出现的位置，如[friend_link][home_banner]，和可以用汉字)',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '标题 (连接的标题,如果是图片连接，会出现在图片的alt属性)',
  `img` varchar(256) NOT NULL DEFAULT '' COMMENT '图片 (如果是图片连接，请填写此值)',
  `url` varchar(256) NOT NULL DEFAULT '' COMMENT '地址 (连接地址)',
  `target` varchar(100) NOT NULL DEFAULT '' COMMENT '打开方式 (这是一个单选框)',
  `sort` int(10) unsigned NOT NULL DEFAULT '0',
  `desc` varchar(512) NOT NULL DEFAULT '' COMMENT '描述 (备注信息、或者说明内容)',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间 (记录更新时间)',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间 (记录创建时间)',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COMMENT='网站连接 (网站配置连接，如友情连接、横幅广告、各种广告)';

-- ----------------------------
-- Records of lx_link
-- ----------------------------
INSERT INTO `lx_link` VALUES ('1', 'friend_link', '广西广电网络', '', 'http://s.96335.com', '_blank', '0', '网站底部友情连接', '1455498315', '1454231860');
INSERT INTO `lx_link` VALUES ('2', 'friend_link', '广西亮星科技', '', 'http://www.gxbrightstar.com', '_self', '1', '网站底部友情连接', '1455498545', '1454231913');
INSERT INTO `lx_link` VALUES ('3', 'friend_link', '亮星游戏', '', 'http://www.lxgame.cn', '_self', '0', '网站底部友情连接', '1454232145', '1454231977');
INSERT INTO `lx_link` VALUES ('4', 'friend_link', '兆和优品', '', 'http://www.gxzhyp.com', '_blank', '0', '网站底部友情连接', '1454232186', '1454232186');
INSERT INTO `lx_link` VALUES ('5', 'friend_link', '绿野音乐节', '', 'http://www.gxlvye.net', '_blank', '0', '网站底部友情连接', '1454232215', '1454232215');
INSERT INTO `lx_link` VALUES ('6', 'friend_link', 'BrightstarThink', '', 'http://www.gxbrightstar.com', '_self', '0', '网站底部友情连接', '1454232269', '1454232269');
INSERT INTO `lx_link` VALUES ('7', 'home_banner', '广西亮星科技有限公司', '/Public/Home/tplgxbs/skin/images/2015010652407685.jpg', 'Category/index?category=guanyuwomen', '_self', '0', '首页大图hanner', '1454331659', '1454234144');
INSERT INTO `lx_link` VALUES ('8', 'home_banner', '广西亮星科技有限公司', '/Public/Home/tplgxbs/skin/images/2014103061516877.jpg', 'Category/index?category=guanyuwomen', '_self', '0', '首页大图hanner', '1454331665', '1454234275');
INSERT INTO `lx_link` VALUES ('9', 'home_banner', '广西亮星科技有限公司', '/Public/Home/tplgxbs/skin/images/2014111067662073.jpg', 'Category/index?category=guanyuwomen', '_self', '0', '首页大图hanner', '1454331670', '1454234301');
INSERT INTO `lx_link` VALUES ('10', 'home_banner', '广西亮星科技有限公司', '/Public/Home/tplgxbs/skin/images/2014072663454633.jpg', 'Category/index?category=guanyuwomen', '_self', '0', '首页大图hanner', '1454331675', '1454234325');
INSERT INTO `lx_link` VALUES ('11', 'lianxiwomen_banner', '联系我们', '/Public/Home/tplgxbs/skin/images/20.jpg', '#', '', '0', '联系我们', '1454237541', '1454234949');
INSERT INTO `lx_link` VALUES ('12', 'guanyuwomen_banner', '关于我们', '/Public/Home/tplgxbs/skin/images/1.jpg', '#', '_self', '0', '', '1454327946', '1454238010');
INSERT INTO `lx_link` VALUES ('13', 'fuwuxiangmu_banner', '服务项目', '/Public/Home/tplgxbs/skin/images/16.jpg', '#', '_self', '0', '', '1454327960', '1454250032');
INSERT INTO `lx_link` VALUES ('14', 'xinwenzixun_banner', '新闻资讯', '/Public/Home/tplgxbs/skin/images/6.jpg', '#', '_self', '0', '', '1454327971', '1454250679');
INSERT INTO `lx_link` VALUES ('15', 'chenggonganli_banner', '成功案例', '/Public/Home/tplgxbs/skin/images/11.jpg', '#', '_self', '0', '', '1454327983', '1454250741');
INSERT INTO `lx_link` VALUES ('16', 'qiyetuandui_banner', '企业团队', '/Public/Home/tplgxbs/skin/images/21.jpg', '#', '_self', '0', '', '1454327993', '1454250828');

-- ----------------------------
-- Table structure for `lx_member`
-- ----------------------------
DROP TABLE IF EXISTS `lx_member`;
CREATE TABLE `lx_member` (
  `uid` int(10) unsigned NOT NULL COMMENT '用户ID',
  `nickname` char(16) NOT NULL DEFAULT '' COMMENT '昵称',
  `fullname` char(16) NOT NULL DEFAULT '' COMMENT '姓名',
  `sex` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '性别；0：保密，1：男；2：女',
  `birthday` date NOT NULL DEFAULT '0000-00-00' COMMENT '生日',
  `qq` char(10) NOT NULL DEFAULT '' COMMENT '联系QQ号',
  `phone` char(11) NOT NULL DEFAULT '' COMMENT '联系电话',
  `country` varchar(100) NOT NULL DEFAULT '' COMMENT '国家，如中国为CN',
  `province` varchar(100) NOT NULL DEFAULT '' COMMENT '用户个人资料填写的省份',
  `city` varchar(100) NOT NULL DEFAULT '' COMMENT '普通用户个人资料填写的城市',
  `area` varchar(100) NOT NULL DEFAULT '' COMMENT '城区',
  `score` mediumint(8) NOT NULL DEFAULT '0' COMMENT '用户积分',
  `login` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '登录次数',
  `reg_ip` bigint(20) NOT NULL DEFAULT '0' COMMENT '注册IP',
  `reg_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '注册时间',
  `last_login_ip` bigint(20) NOT NULL DEFAULT '0' COMMENT '最后登录IP',
  `last_login_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `update_time` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '会员状态',
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COMMENT='会员表';

-- ----------------------------
-- Records of lx_member
-- ----------------------------
INSERT INTO `lx_member` VALUES ('1', '亮超人', '', '0', '0000-00-00', '', '', '', '', '', '', '180', '100', '0', '1452844836', '2130706433', '1460428860', '0', '1');
INSERT INTO `lx_member` VALUES ('7', 'neirong', '李三毛', '0', '0000-00-00', '', '13471008888', '', '广西', '南宁市', '市辖区', '30', '5', '0', '0', '2130706433', '1460428792', '1458895781', '1');

-- ----------------------------
-- Table structure for `lx_member_safe`
-- ----------------------------
DROP TABLE IF EXISTS `lx_member_safe`;
CREATE TABLE `lx_member_safe` (
  `id` int(11) unsigned NOT NULL,
  `regtype` enum('normal','email','phone','weixin','qq') NOT NULL DEFAULT 'normal',
  `ask` varchar(128) NOT NULL DEFAULT '' COMMENT '安全问题',
  `answer` varchar(64) NOT NULL DEFAULT '' COMMENT '问题答案',
  `asksts` int(2) NOT NULL DEFAULT '0' COMMENT '状态  0未设置 1已设置',
  `askststime` int(11) unsigned NOT NULL DEFAULT '0',
  `askvtimes` int(11) NOT NULL DEFAULT '0' COMMENT '尝试验证次数',
  `email` varchar(32) NOT NULL DEFAULT '' COMMENT 'Email',
  `emailvcode` varchar(32) NOT NULL DEFAULT '' COMMENT 'Email验证吗',
  `emailsts` int(2) NOT NULL DEFAULT '1' COMMENT '状态  0未验证 1已发送验证邮件待邮件验证  2已验证',
  `emailststime` int(11) unsigned NOT NULL DEFAULT '0',
  `emailvtimes` int(11) NOT NULL DEFAULT '0' COMMENT '尝试验证次数',
  `phone` varchar(11) NOT NULL DEFAULT '' COMMENT '移动电话',
  `phonevcode` varchar(32) NOT NULL DEFAULT '' COMMENT 'phone验证吗',
  `phonests` int(2) NOT NULL DEFAULT '0' COMMENT '状态  0未设置 1已设置待验证  2已验证',
  `phoneststime` int(11) unsigned NOT NULL DEFAULT '0',
  `phonevtimes` int(11) NOT NULL DEFAULT '0' COMMENT '尝试验证次数',
  `wxopenid` varchar(32) NOT NULL DEFAULT '' COMMENT '微信openid',
  `wxvcode` varchar(32) NOT NULL DEFAULT '' COMMENT '微信验证吗',
  `wxsts` int(2) NOT NULL DEFAULT '0' COMMENT '状态  0未设置 1已设置待验证  2已验证',
  `wxststime` int(11) unsigned NOT NULL DEFAULT '0',
  `qqopenid` varchar(32) NOT NULL DEFAULT '' COMMENT 'QQ的openid',
  `qqvcode` varchar(32) NOT NULL DEFAULT '' COMMENT 'QQ验证吗',
  `qqsts` int(2) NOT NULL DEFAULT '0' COMMENT '状态  0未设置 1已设置未验证  2已验证',
  `qqststime` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ux_member_safe_em` (`email`),
  KEY `ix_member_safe_ph` (`phone`),
  KEY `ix_member_safe_wx` (`wxopenid`),
  KEY `ix_member_safe_qq` (`qqopenid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='安全信息';

-- ----------------------------
-- Records of lx_member_safe
-- ----------------------------

-- ----------------------------
-- Table structure for `lx_menu`
-- ----------------------------
DROP TABLE IF EXISTS `lx_menu`;
CREATE TABLE `lx_menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '文档ID',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '标题',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID',
  `sort` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序（同级有效）',
  `url` char(255) NOT NULL DEFAULT '' COMMENT '链接地址',
  `hide` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否隐藏',
  `tip` varchar(255) NOT NULL DEFAULT '' COMMENT '提示',
  `group` varchar(50) DEFAULT '' COMMENT '分组',
  `is_dev` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否仅开发者模式可见',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `status` (`status`)
) ENGINE=MyISAM AUTO_INCREMENT=515 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of lx_menu
-- ----------------------------
INSERT INTO `lx_menu` VALUES ('1', '首页', '0', '1', 'Index/index', '0', '', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('2', '档案', '0', '2', 'Article/index', '0', '', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('3', '文档列表', '2', '0', 'article/index', '1', '', '内容', '0', '1');
INSERT INTO `lx_menu` VALUES ('4', '新增', '3', '0', 'article/add', '0', '', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('5', '编辑', '3', '0', 'article/edit', '0', '', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('6', '改变状态', '3', '0', 'article/setStatus', '0', '', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('7', '保存', '3', '0', 'article/update', '0', '', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('8', '保存草稿', '3', '0', 'article/autoSave', '0', '', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('9', '移动', '3', '0', 'article/move', '0', '', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('10', '复制', '3', '0', 'article/copy', '0', '', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('11', '粘贴', '3', '0', 'article/paste', '0', '', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('12', '导入', '3', '0', 'article/batchOperate', '0', '', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('13', '回收站', '2', '0', 'article/recycle', '1', '', '内容', '0', '1');
INSERT INTO `lx_menu` VALUES ('14', '还原', '13', '0', 'article/permit', '0', '', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('15', '清空', '13', '0', 'article/clear', '0', '', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('16', '用户', '0', '3', 'User/index', '0', '', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('17', '用户信息', '16', '0', 'User/index', '0', '', '用户管理', '0', '1');
INSERT INTO `lx_menu` VALUES ('18', '新增用户', '17', '0', 'User/add', '0', '添加新用户', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('19', '用户行为', '16', '0', 'User/action', '0', '', '行为管理', '0', '1');
INSERT INTO `lx_menu` VALUES ('20', '新增用户行为', '19', '0', 'User/addaction', '0', '', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('21', '编辑用户行为', '19', '0', 'User/editaction', '0', '', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('22', '保存用户行为', '19', '0', 'User/saveAction', '0', '\"用户->用户行为\"保存编辑和新增的用户行为', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('23', '变更行为状态', '19', '0', 'User/setStatus', '0', '\"用户->用户行为\"中的启用,禁用和删除权限', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('24', '禁用会员', '19', '0', 'User/changeStatus?method=forbidUser', '0', '\"用户->用户信息\"中的禁用', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('25', '启用会员', '19', '0', 'User/changeStatus?method=resumeUser', '0', '\"用户->用户信息\"中的启用', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('26', '删除会员', '19', '0', 'User/changeStatus?method=deleteUser', '0', '\"用户->用户信息\"中的删除', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('27', '权限管理', '16', '0', 'AuthManager/index', '0', '', '用户管理', '0', '1');
INSERT INTO `lx_menu` VALUES ('28', '删除', '27', '0', 'AuthManager/changeStatus?method=deleteGroup', '0', '删除用户组', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('29', '禁用', '27', '0', 'AuthManager/changeStatus?method=forbidGroup', '0', '禁用用户组', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('30', '恢复', '27', '0', 'AuthManager/changeStatus?method=resumeGroup', '0', '恢复已禁用的用户组', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('31', '新增', '27', '0', 'AuthManager/createGroup', '0', '创建新的用户组', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('32', '编辑', '27', '0', 'AuthManager/editGroup', '0', '编辑用户组名称和描述', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('33', '保存用户组', '27', '0', 'AuthManager/writeGroup', '0', '新增和编辑用户组的\"保存\"按钮', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('34', '授权', '27', '0', 'AuthManager/group', '0', '\"后台 \\ 用户 \\ 用户信息\"列表页的\"授权\"操作按钮,用于设置用户所属用户组', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('35', '访问授权', '27', '0', 'AuthManager/access', '0', '\"后台 \\ 用户 \\ 权限管理\"列表页的\"访问授权\"操作按钮', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('36', '成员授权', '27', '0', 'AuthManager/user', '0', '\"后台 \\ 用户 \\ 权限管理\"列表页的\"成员授权\"操作按钮', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('37', '解除授权', '27', '0', 'AuthManager/removeFromGroup', '0', '\"成员授权\"列表页内的解除授权操作按钮', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('38', '保存成员授权', '27', '0', 'AuthManager/addToGroup', '0', '\"用户信息\"列表页\"授权\"时的\"保存\"按钮和\"成员授权\"里右上角的\"添加\"按钮)', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('39', '分类授权', '27', '0', 'AuthManager/category', '0', '\"后台 \\ 用户 \\ 权限管理\"列表页的\"分类授权\"操作按钮', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('40', '保存分类授权', '27', '0', 'AuthManager/addToCategory', '0', '\"分类授权\"页面的\"保存\"按钮', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('41', '模型授权', '27', '0', 'AuthManager/modelauth', '0', '\"后台 \\ 用户 \\ 权限管理\"列表页的\"模型授权\"操作按钮', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('42', '保存模型授权', '27', '0', 'AuthManager/addToModel', '0', '\"分类授权\"页面的\"保存\"按钮', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('43', '扩展', '0', '7', 'Addons/index', '0', '', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('44', '插件管理', '43', '1', 'Addons/index', '0', '', '扩展', '0', '1');
INSERT INTO `lx_menu` VALUES ('45', '创建', '44', '0', 'Addons/create', '0', '服务器上创建插件结构向导', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('46', '检测创建', '44', '0', 'Addons/checkForm', '0', '检测插件是否可以创建', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('47', '预览', '44', '0', 'Addons/preview', '0', '预览插件定义类文件', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('48', '快速生成插件', '44', '0', 'Addons/build', '0', '开始生成插件结构', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('49', '设置', '44', '0', 'Addons/config', '0', '设置插件配置', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('50', '禁用', '44', '0', 'Addons/disable', '0', '禁用插件', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('51', '启用', '44', '0', 'Addons/enable', '0', '启用插件', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('52', '安装', '44', '0', 'Addons/install', '0', '安装插件', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('53', '卸载', '44', '0', 'Addons/uninstall', '0', '卸载插件', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('54', '更新配置', '44', '0', 'Addons/saveconfig', '0', '更新插件配置处理', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('55', '插件后台列表', '44', '0', 'Addons/adminList', '0', '', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('56', 'URL方式访问插件', '44', '0', 'Addons/execute', '0', '控制是否有权限通过url访问插件控制器方法', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('57', '钩子管理', '43', '2', 'Addons/hooks', '0', '', '扩展', '0', '1');
INSERT INTO `lx_menu` VALUES ('58', '模型管理', '68', '3', 'Model/index', '0', '', '系统设置', '0', '1');
INSERT INTO `lx_menu` VALUES ('59', '新增', '58', '0', 'model/add', '0', '', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('60', '编辑', '58', '0', 'model/edit', '0', '', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('61', '改变状态', '58', '0', 'model/setStatus', '0', '', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('62', '保存数据', '58', '0', 'model/update', '0', '', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('63', '属性管理', '68', '0', 'Attribute/index', '1', '网站属性配置。', '系统设置', '0', '1');
INSERT INTO `lx_menu` VALUES ('64', '新增', '63', '0', 'Attribute/add', '0', '', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('65', '编辑', '63', '0', 'Attribute/edit', '0', '', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('66', '改变状态', '63', '0', 'Attribute/setStatus', '0', '', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('67', '保存数据', '63', '0', 'Attribute/update', '0', '', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('68', '系统', '0', '4', 'Config/group', '0', '', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('69', '网站设置', '68', '1', 'Config/group', '0', '', '系统设置', '0', '1');
INSERT INTO `lx_menu` VALUES ('70', '配置管理', '68', '4', 'Config/index', '0', '', '系统设置', '0', '1');
INSERT INTO `lx_menu` VALUES ('71', '编辑', '70', '0', 'Config/edit', '0', '新增编辑和保存配置', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('72', '删除', '70', '0', 'Config/del', '0', '删除配置', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('73', '新增', '70', '0', 'Config/add', '0', '新增配置', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('74', '保存', '70', '0', 'Config/save', '0', '保存配置', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('75', '菜单管理', '68', '5', 'Menu/index', '0', '', '系统设置', '0', '1');
INSERT INTO `lx_menu` VALUES ('76', '导航管理', '500', '2', 'Channel/index', '0', '', '核心管理', '0', '1');
INSERT INTO `lx_menu` VALUES ('77', '新增', '76', '0', 'Channel/add', '0', '', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('78', '编辑', '76', '0', 'Channel/edit', '0', '', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('79', '删除', '76', '0', 'Channel/del', '0', '', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('80', '分类管理', '500', '1', 'Category/index', '0', '', '核心管理', '0', '1');
INSERT INTO `lx_menu` VALUES ('81', '编辑', '80', '0', 'Category/edit', '0', '编辑和保存栏目分类', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('82', '新增', '80', '0', 'Category/add', '0', '新增栏目分类', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('83', '删除', '80', '0', 'Category/remove', '0', '删除栏目分类', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('84', '移动', '80', '0', 'Category/operate/type/move', '0', '移动栏目分类', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('85', '合并', '80', '0', 'Category/operate/type/merge', '0', '合并栏目分类', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('86', '备份数据库', '68', '0', 'Database/index?type=export', '0', '', '数据备份', '0', '1');
INSERT INTO `lx_menu` VALUES ('87', '备份', '86', '0', 'Database/export', '0', '备份数据库', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('88', '优化表', '86', '0', 'Database/optimize', '0', '优化数据表', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('89', '修复表', '86', '0', 'Database/repair', '0', '修复数据表', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('90', '还原数据库', '68', '0', 'Database/index?type=import', '0', '', '数据备份', '0', '1');
INSERT INTO `lx_menu` VALUES ('91', '恢复', '90', '0', 'Database/import', '0', '数据库恢复', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('92', '删除', '90', '0', 'Database/del', '0', '删除备份文件', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('93', '其他', '0', '5', 'other', '1', '', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('96', '新增', '75', '0', 'Menu/add', '0', '', '系统设置', '0', '1');
INSERT INTO `lx_menu` VALUES ('98', '编辑', '75', '0', 'Menu/edit', '0', '', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('106', '行为日志', '16', '0', 'Action/actionlog', '0', '', '行为管理', '0', '1');
INSERT INTO `lx_menu` VALUES ('108', '修改密码', '16', '0', 'User/updatePassword', '1', '', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('109', '修改昵称', '16', '0', 'User/updateNickname', '1', '', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('110', '查看行为日志', '106', '0', 'action/edit', '1', '', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('112', '新增数据', '58', '0', 'think/add', '1', '', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('113', '编辑数据', '58', '0', 'think/edit', '1', '', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('114', '导入', '75', '0', 'Menu/import', '0', '', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('115', '生成', '58', '0', 'Model/generate', '0', '', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('116', '新增钩子', '57', '0', 'Addons/addHook', '0', '', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('117', '编辑钩子', '57', '0', 'Addons/edithook', '0', '', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('118', '文档排序', '3', '0', 'Article/sort', '1', '', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('119', '排序', '70', '0', 'Config/sort', '1', '', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('120', '排序', '75', '0', 'Menu/sort', '1', '', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('121', '排序', '76', '0', 'Channel/sort', '1', '', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('122', '数据列表', '58', '0', 'think/lists', '1', '', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('123', '审核列表', '3', '0', 'Article/examine', '1', '', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('500', '核心', '0', '2', 'Category/index', '0', '', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('505', '清除缓存', '68', '7', 'Config/clearcache', '0', '', '系统设置', '0', '1');
INSERT INTO `lx_menu` VALUES ('506', '代码示例管理', '500', '0', 'CodeSample/index', '0', '', '数据管理', '0', '1');
INSERT INTO `lx_menu` VALUES ('507', '新增', '506', '0', 'CodeSample/add', '0', '', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('508', '编辑', '506', '0', 'CodeSample/edit', '0', '', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('509', '删除', '506', '0', 'CodeSample/del', '0', '', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('510', '网站连接管理', '500', '3', 'Link/index', '0', '', '核心管理', '0', '1');
INSERT INTO `lx_menu` VALUES ('511', '新增', '510', '0', 'Link/add', '0', '', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('512', '编辑', '510', '0', 'Link/edit', '0', '', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('513', '删除', '510', '0', 'Link/del', '0', '', '', '0', '1');
INSERT INTO `lx_menu` VALUES ('514', 'UCenter', '16', '0', 'User/ucenter', '0', '用户中心', '用户管理', '0', '1');

-- ----------------------------
-- Table structure for `lx_model`
-- ----------------------------
DROP TABLE IF EXISTS `lx_model`;
CREATE TABLE `lx_model` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '模型ID',
  `name` char(30) NOT NULL DEFAULT '' COMMENT '模型标识',
  `title` char(30) NOT NULL DEFAULT '' COMMENT '模型名称',
  `extend` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '继承的模型',
  `relation` varchar(30) NOT NULL DEFAULT '' COMMENT '继承与被继承模型的关联字段',
  `need_pk` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '新建表时是否需要主键字段',
  `field_sort` text COMMENT '表单字段排序',
  `field_group` varchar(255) NOT NULL DEFAULT '1:基础' COMMENT '字段分组',
  `attribute_list` text COMMENT '属性列表（表的字段）',
  `attribute_alias` varchar(255) NOT NULL DEFAULT '' COMMENT '属性别名定义',
  `template_list` varchar(100) NOT NULL DEFAULT '' COMMENT '列表模板',
  `template_add` varchar(100) NOT NULL DEFAULT '' COMMENT '新增模板',
  `template_edit` varchar(100) NOT NULL DEFAULT '' COMMENT '编辑模板',
  `list_grid` text COMMENT '列表定义',
  `list_row` smallint(2) unsigned NOT NULL DEFAULT '10' COMMENT '列表数据长度',
  `search_key` varchar(50) NOT NULL DEFAULT '' COMMENT '默认搜索字段',
  `search_list` varchar(255) NOT NULL DEFAULT '' COMMENT '高级搜索的字段',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `engine_type` varchar(25) NOT NULL DEFAULT 'MyISAM' COMMENT '数据库引擎',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='文档模型表';

-- ----------------------------
-- Records of lx_model
-- ----------------------------
INSERT INTO `lx_model` VALUES ('1', 'document', '基础文档', '0', '', '1', '{\"1\":[\"1\",\"2\",\"3\",\"4\",\"5\",\"6\",\"7\",\"8\",\"9\",\"10\",\"11\",\"12\",\"13\",\"14\",\"15\",\"16\",\"17\",\"18\",\"19\",\"20\",\"21\",\"22\"]}', '1:基础', '', '', '', '', '', 'id:编号\r\ntitle:标题:[EDIT]\r\ntype:类型\r\nupdate_time:最后更新\r\nstatus:状态\r\nview:浏览\r\nid:操作:[EDIT]|编辑,[DELETE]|删除', '0', '', '', '1383891233', '1384507827', '1', 'MyISAM');
INSERT INTO `lx_model` VALUES ('2', 'article', '文章', '1', '', '1', '{\"1\":[\"3\",\"24\",\"2\",\"5\"],\"2\":[\"9\",\"13\",\"19\",\"10\",\"12\",\"16\",\"17\",\"26\",\"20\",\"14\",\"11\",\"25\"]}', '1:基础,2:扩展', '', '', '', '', '', '', '0', '', '', '1383891243', '1387260622', '1', 'MyISAM');
INSERT INTO `lx_model` VALUES ('3', 'download', '下载', '1', '', '1', '{\"1\":[\"3\",\"28\",\"30\",\"32\",\"2\",\"5\",\"31\"],\"2\":[\"13\",\"10\",\"27\",\"9\",\"12\",\"16\",\"17\",\"19\",\"11\",\"20\",\"14\",\"29\"]}', '1:基础,2:扩展', '', '', '', '', '', '', '0', '', '', '1383891252', '1387260449', '1', 'MyISAM');

-- ----------------------------
-- Table structure for `lx_password_log`
-- ----------------------------
DROP TABLE IF EXISTS `lx_password_log`;
CREATE TABLE `lx_password_log` (
  `id` int(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned NOT NULL DEFAULT '0',
  `checkname` varchar(64) NOT NULL COMMENT '验证用户名 可能是邮箱/会员帐号/手机号',
  `checkpassword` varchar(32) NOT NULL COMMENT '验证时用户输入的密码',
  `checkret` int(2) NOT NULL DEFAULT '0' COMMENT '状态  0只输入未验证密码   1验证成功  2验证失败',
  `addtime` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `ix_password_log_uid` (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=100094 DEFAULT CHARSET=utf8 COMMENT='用户密码日志';

-- ----------------------------
-- Records of lx_password_log
-- ----------------------------

-- ----------------------------
-- Table structure for `lx_picture`
-- ----------------------------
DROP TABLE IF EXISTS `lx_picture`;
CREATE TABLE `lx_picture` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id自增',
  `path` varchar(255) NOT NULL DEFAULT '' COMMENT '路径',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '图片链接',
  `md5` char(32) NOT NULL DEFAULT '' COMMENT '文件md5',
  `sha1` char(40) NOT NULL DEFAULT '' COMMENT '文件 sha1编码',
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '状态',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of lx_picture
-- ----------------------------
INSERT INTO `lx_picture` VALUES ('1', '/Uploads/Picture/2016-02-01/56aec6dc6c6a3.png', '', '9bb089dd2f8d3b07e3ffba4a9c5c1df6', 'df85c3259a413e3ec03fc1d0aa3ea374213f7832', '1', '1454294748');
INSERT INTO `lx_picture` VALUES ('2', '/Uploads/allimg/151202/1-1512020U6210-L.jpg', '', '', '', '1', '1454294748');
INSERT INTO `lx_picture` VALUES ('3', '/Uploads/allimg/151202/1-1512020U6420-L.jpg', '', '', '', '1', '1454294748');
INSERT INTO `lx_picture` VALUES ('4', '/Uploads/allimg/151202/0UH343D-0.gif', '', '', '', '1', '1454294748');
INSERT INTO `lx_picture` VALUES ('5', '/Uploads/allimg/151202/1-1512020Z60AJ.jpg', '', '', '', '1', '1454294748');
INSERT INTO `lx_picture` VALUES ('6', '/Uploads/allimg/151202/1-15120209164U54.gif', '', '', '', '1', '1454294748');
INSERT INTO `lx_picture` VALUES ('7', '/Uploads/allimg/151202/1-1512020911363F.gif', '', '', '', '1', '1454294748');
INSERT INTO `lx_picture` VALUES ('8', '/Uploads/allimg/151202/1-1512022352450-L.png', '', '', '', '1', '1454294748');
INSERT INTO `lx_picture` VALUES ('9', '/Uploads/allimg/151202/1-1512020920110-L.jpg', '', '', '', '1', '1454294748');
INSERT INTO `lx_picture` VALUES ('10', '/Uploads/allimg/151202/1-1512020919530-L.jpg', '', '', '', '1', '1454294748');
INSERT INTO `lx_picture` VALUES ('11', '/Uploads/allimg/151202/1-1512020919240-L.jpg', '', '', '', '1', '1454294748');
INSERT INTO `lx_picture` VALUES ('12', '/Uploads/allimg/151202/1-151202091Z00-L.jpg', '', '', '', '1', '1454294748');
INSERT INTO `lx_picture` VALUES ('13', '/Uploads/allimg/151202/1-151202091S60-L.jpg', '', '', '', '1', '1454294748');

-- ----------------------------
-- Table structure for `lx_qq_userinfo`
-- ----------------------------
DROP TABLE IF EXISTS `lx_qq_userinfo`;
CREATE TABLE `lx_qq_userinfo` (
  `openid` char(32) NOT NULL,
  `token` varchar(64) NOT NULL DEFAULT '' COMMENT '授权',
  `ret` int(8) NOT NULL DEFAULT '0' COMMENT '接口返回值',
  `msg` varchar(512) NOT NULL DEFAULT '' COMMENT '接口返回值',
  `is_lost` int(8) NOT NULL DEFAULT '0' COMMENT '接口返回值',
  `nickname` varchar(60) NOT NULL DEFAULT '' COMMENT '昵称',
  `gender` varchar(64) NOT NULL DEFAULT '' COMMENT '昵称',
  `province` varchar(512) NOT NULL DEFAULT '' COMMENT '用户个人资料填写的省份',
  `city` varchar(512) NOT NULL DEFAULT '' COMMENT '普通用户个人资料填写的城市',
  `year` varchar(20) NOT NULL DEFAULT '',
  `figureurl` varchar(512) NOT NULL DEFAULT '' COMMENT '用户头像 ',
  `figureurl_1` varchar(512) NOT NULL DEFAULT '' COMMENT '用户头像 ',
  `figureurl_2` varchar(512) NOT NULL DEFAULT '' COMMENT '用户头像 ',
  `figureurl_qq_1` varchar(512) NOT NULL DEFAULT '' COMMENT '用户头像 ',
  `figureurl_qq_2` varchar(512) NOT NULL DEFAULT '' COMMENT '用户头像 ',
  `is_yellow_vip` varchar(20) NOT NULL DEFAULT '',
  `vip` varchar(20) NOT NULL DEFAULT '',
  `yellow_vip_level` varchar(20) NOT NULL DEFAULT '',
  `level` varchar(20) NOT NULL DEFAULT '',
  `is_yellow_year_vip` varchar(20) NOT NULL DEFAULT '',
  `modifytime` int(11) unsigned NOT NULL DEFAULT '0',
  `addtime` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`openid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='保存QQ用户信息(add by heqh)';

-- ----------------------------
-- Records of lx_qq_userinfo
-- ----------------------------

-- ----------------------------
-- Table structure for `lx_send_email_log`
-- ----------------------------
DROP TABLE IF EXISTS `lx_send_email_log`;
CREATE TABLE `lx_send_email_log` (
  `id` int(20) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `email` varchar(32) NOT NULL DEFAULT '' COMMENT 'Email',
  `subject` varchar(256) NOT NULL DEFAULT '' COMMENT '主题',
  `body` varchar(4000) NOT NULL DEFAULT '' COMMENT '内容',
  `ip` varchar(20) DEFAULT NULL,
  `ret` varchar(256) NOT NULL DEFAULT '' COMMENT '结果',
  `addtime` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `ix_send_email_log_uid` (`userid`)
) ENGINE=MyISAM AUTO_INCREMENT=625 DEFAULT CHARSET=utf8 COMMENT='发送邮件日志';

-- ----------------------------
-- Records of lx_send_email_log
-- ----------------------------

-- ----------------------------
-- Table structure for `lx_send_sms_log`
-- ----------------------------
DROP TABLE IF EXISTS `lx_send_sms_log`;
CREATE TABLE `lx_send_sms_log` (
  `id` int(20) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `phone` varchar(11) NOT NULL DEFAULT '' COMMENT '移动电话',
  `body` varchar(256) NOT NULL DEFAULT '' COMMENT '内容',
  `ip` varchar(20) DEFAULT NULL,
  `vcode` varchar(32) NOT NULL DEFAULT '' COMMENT 'phone验证吗',
  `ret` varchar(256) NOT NULL DEFAULT '' COMMENT '结果',
  `addtime` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `ix_send_phone_log_uid` (`userid`),
  KEY `ix_send_phone_log_phone` (`phone`)
) ENGINE=MyISAM AUTO_INCREMENT=100081 DEFAULT CHARSET=utf8 COMMENT='发送短信日志';

-- ----------------------------
-- Records of lx_send_sms_log
-- ----------------------------

-- ----------------------------
-- Table structure for `lx_url`
-- ----------------------------
DROP TABLE IF EXISTS `lx_url`;
CREATE TABLE `lx_url` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '链接唯一标识',
  `url` char(255) NOT NULL DEFAULT '' COMMENT '链接地址',
  `short` char(100) NOT NULL DEFAULT '' COMMENT '短网址',
  `status` tinyint(2) NOT NULL DEFAULT '2' COMMENT '状态',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_url` (`url`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='链接表';

-- ----------------------------
-- Records of lx_url
-- ----------------------------

-- ----------------------------
-- Table structure for `lx_userdata`
-- ----------------------------
DROP TABLE IF EXISTS `lx_userdata`;
CREATE TABLE `lx_userdata` (
  `uid` int(10) unsigned NOT NULL COMMENT '用户id',
  `type` tinyint(3) unsigned NOT NULL COMMENT '类型标识',
  `target_id` int(10) unsigned NOT NULL COMMENT '目标id',
  UNIQUE KEY `uid` (`uid`,`type`,`target_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of lx_userdata
-- ----------------------------

-- ----------------------------
-- Table structure for `lx_wechat_qrcode`
-- ----------------------------
DROP TABLE IF EXISTS `lx_wechat_qrcode`;
CREATE TABLE `lx_wechat_qrcode` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned NOT NULL DEFAULT '0',
  `scene` varchar(64) NOT NULL DEFAULT '' COMMENT '场景值ID',
  `sts` int(2) NOT NULL DEFAULT '0' COMMENT '状态  0初始化状态    1用户已经扫描',
  `ststime` int(11) unsigned NOT NULL DEFAULT '0',
  `ticket` varchar(256) NOT NULL DEFAULT '' COMMENT '凭借此ticket可以在有效时间内换取二维码',
  `expire_seconds` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '二维码的有效时间，以秒为单位。最大不超过1800',
  `url` varchar(256) NOT NULL DEFAULT '' COMMENT '二维码图片解析后的地址，开发者可根据该地址自行生成需要的二维码图片',
  `scanopenid` varchar(32) NOT NULL DEFAULT '' COMMENT '微信openid，用户扫码以后才填写',
  `action` varchar(64) NOT NULL DEFAULT '' COMMENT '网站执行的动作',
  `addtime` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ix_wechat_qrcode_scene` (`scene`),
  KEY `ix_wechat_qrcode_uid` (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=348 DEFAULT CHARSET=utf8 COMMENT='保存微信场景二维码信息';

-- ----------------------------
-- Records of lx_wechat_qrcode
-- ----------------------------

-- ----------------------------
-- Table structure for `lx_wechat_userinfo`
-- ----------------------------
DROP TABLE IF EXISTS `lx_wechat_userinfo`;
CREATE TABLE `lx_wechat_userinfo` (
  `openid` char(32) NOT NULL,
  `subscribe` int(2) NOT NULL DEFAULT '0' COMMENT '是否粉丝',
  `nickname` varchar(60) NOT NULL DEFAULT '' COMMENT '昵称',
  `sex` smallint(1) DEFAULT '0' COMMENT '性别；0：保密，1：男；2：女',
  `language` varchar(64) NOT NULL DEFAULT '' COMMENT '语言，如中国为zh_CN',
  `province` varchar(512) NOT NULL DEFAULT '' COMMENT '用户个人资料填写的省份',
  `city` varchar(512) NOT NULL DEFAULT '' COMMENT '普通用户个人资料填写的城市',
  `country` varchar(512) NOT NULL DEFAULT '' COMMENT '国家，如中国为CN',
  `headimgurl` varchar(512) NOT NULL DEFAULT '' COMMENT '用户头像 ',
  `privilege` varchar(512) NOT NULL DEFAULT '' COMMENT '用户特权信息',
  `unionid` varchar(64) NOT NULL DEFAULT '' COMMENT '只有在用户将公众号绑定到微信开放平台帐号后，才会出现该字段',
  `subscribe_time` int(11) unsigned NOT NULL DEFAULT '0',
  `groupid` int(9) unsigned NOT NULL DEFAULT '0',
  `modifytime` int(11) unsigned NOT NULL DEFAULT '0',
  `addtime` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`openid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='保存微信的用户信息(add by heqh)';

-- ----------------------------
-- Records of lx_wechat_userinfo
-- ----------------------------

-- ----------------------------
-- Table structure for `uc_admins`
-- ----------------------------
DROP TABLE IF EXISTS `uc_admins`;
CREATE TABLE `uc_admins` (
  `uid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `username` char(15) NOT NULL DEFAULT '',
  `allowadminsetting` tinyint(1) NOT NULL DEFAULT '0',
  `allowadminapp` tinyint(1) NOT NULL DEFAULT '0',
  `allowadminuser` tinyint(1) NOT NULL DEFAULT '0',
  `allowadminbadword` tinyint(1) NOT NULL DEFAULT '0',
  `allowadmintag` tinyint(1) NOT NULL DEFAULT '0',
  `allowadminpm` tinyint(1) NOT NULL DEFAULT '0',
  `allowadmincredits` tinyint(1) NOT NULL DEFAULT '0',
  `allowadmindomain` tinyint(1) NOT NULL DEFAULT '0',
  `allowadmindb` tinyint(1) NOT NULL DEFAULT '0',
  `allowadminnote` tinyint(1) NOT NULL DEFAULT '0',
  `allowadmincache` tinyint(1) NOT NULL DEFAULT '0',
  `allowadminlog` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uc_admins
-- ----------------------------

-- ----------------------------
-- Table structure for `uc_applications`
-- ----------------------------
DROP TABLE IF EXISTS `uc_applications`;
CREATE TABLE `uc_applications` (
  `appid` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(16) NOT NULL DEFAULT '',
  `name` varchar(20) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `authkey` varchar(255) NOT NULL DEFAULT '',
  `ip` varchar(15) NOT NULL DEFAULT '',
  `viewprourl` varchar(255) NOT NULL,
  `apifilename` varchar(30) NOT NULL DEFAULT 'uc.php',
  `charset` varchar(8) NOT NULL DEFAULT '',
  `dbcharset` varchar(8) NOT NULL DEFAULT '',
  `synlogin` tinyint(1) NOT NULL DEFAULT '0',
  `recvnote` tinyint(1) DEFAULT '0',
  `extra` text NOT NULL,
  `tagtemplates` text NOT NULL,
  `allowips` text NOT NULL,
  PRIMARY KEY (`appid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uc_applications
-- ----------------------------
INSERT INTO `uc_applications` VALUES ('1', 'OTHER', 'BrightStarThink', 'http://www.brightstarthink.com', 'cfbc4N7ByEbEc+luItsUtPAB1eeZpU77tyYqrjEzlt4xgSx82ONYcbB5xP9sImOrE1eD9dI8Ez1w8YOAYw', '', '', 'uc.php', '', '', '1', '0', 'a:2:{s:7:\"apppath\";s:0:\"\";s:8:\"extraurl\";a:0:{}}', '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\r\n<root>\r\n	<item id=\"template\"><![CDATA[]]></item>\r\n</root>', '');

-- ----------------------------
-- Table structure for `uc_badwords`
-- ----------------------------
DROP TABLE IF EXISTS `uc_badwords`;
CREATE TABLE `uc_badwords` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `admin` varchar(15) NOT NULL DEFAULT '',
  `find` varchar(255) NOT NULL DEFAULT '',
  `replacement` varchar(255) NOT NULL DEFAULT '',
  `findpattern` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `find` (`find`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uc_badwords
-- ----------------------------

-- ----------------------------
-- Table structure for `uc_domains`
-- ----------------------------
DROP TABLE IF EXISTS `uc_domains`;
CREATE TABLE `uc_domains` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `domain` char(40) NOT NULL DEFAULT '',
  `ip` char(15) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uc_domains
-- ----------------------------

-- ----------------------------
-- Table structure for `uc_failedlogins`
-- ----------------------------
DROP TABLE IF EXISTS `uc_failedlogins`;
CREATE TABLE `uc_failedlogins` (
  `ip` char(15) NOT NULL DEFAULT '',
  `count` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `lastupdate` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uc_failedlogins
-- ----------------------------

-- ----------------------------
-- Table structure for `uc_feeds`
-- ----------------------------
DROP TABLE IF EXISTS `uc_feeds`;
CREATE TABLE `uc_feeds` (
  `feedid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `appid` varchar(30) NOT NULL DEFAULT '',
  `icon` varchar(30) NOT NULL DEFAULT '',
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(15) NOT NULL DEFAULT '',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `hash_template` varchar(32) NOT NULL DEFAULT '',
  `hash_data` varchar(32) NOT NULL DEFAULT '',
  `title_template` text NOT NULL,
  `title_data` text NOT NULL,
  `body_template` text NOT NULL,
  `body_data` text NOT NULL,
  `body_general` text NOT NULL,
  `image_1` varchar(255) NOT NULL DEFAULT '',
  `image_1_link` varchar(255) NOT NULL DEFAULT '',
  `image_2` varchar(255) NOT NULL DEFAULT '',
  `image_2_link` varchar(255) NOT NULL DEFAULT '',
  `image_3` varchar(255) NOT NULL DEFAULT '',
  `image_3_link` varchar(255) NOT NULL DEFAULT '',
  `image_4` varchar(255) NOT NULL DEFAULT '',
  `image_4_link` varchar(255) NOT NULL DEFAULT '',
  `target_ids` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`feedid`),
  KEY `uid` (`uid`,`dateline`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uc_feeds
-- ----------------------------

-- ----------------------------
-- Table structure for `uc_friends`
-- ----------------------------
DROP TABLE IF EXISTS `uc_friends`;
CREATE TABLE `uc_friends` (
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `friendid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `direction` tinyint(1) NOT NULL DEFAULT '0',
  `version` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `delstatus` tinyint(1) NOT NULL DEFAULT '0',
  `comment` char(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`version`),
  KEY `uid` (`uid`),
  KEY `friendid` (`friendid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uc_friends
-- ----------------------------

-- ----------------------------
-- Table structure for `uc_mailqueue`
-- ----------------------------
DROP TABLE IF EXISTS `uc_mailqueue`;
CREATE TABLE `uc_mailqueue` (
  `mailid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `touid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `tomail` varchar(32) NOT NULL,
  `frommail` varchar(100) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `charset` varchar(15) NOT NULL,
  `htmlon` tinyint(1) NOT NULL DEFAULT '0',
  `level` tinyint(1) NOT NULL DEFAULT '1',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `failures` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `appid` smallint(6) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`mailid`),
  KEY `appid` (`appid`),
  KEY `level` (`level`,`failures`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uc_mailqueue
-- ----------------------------

-- ----------------------------
-- Table structure for `uc_memberfields`
-- ----------------------------
DROP TABLE IF EXISTS `uc_memberfields`;
CREATE TABLE `uc_memberfields` (
  `uid` mediumint(8) unsigned NOT NULL,
  `blacklist` text NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uc_memberfields
-- ----------------------------
INSERT INTO `uc_memberfields` VALUES ('1', '');
INSERT INTO `uc_memberfields` VALUES ('7', '');

-- ----------------------------
-- Table structure for `uc_members`
-- ----------------------------
DROP TABLE IF EXISTS `uc_members`;
CREATE TABLE `uc_members` (
  `uid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `username` char(15) NOT NULL DEFAULT '',
  `password` char(32) NOT NULL DEFAULT '',
  `email` char(32) NOT NULL DEFAULT '',
  `myid` char(30) NOT NULL DEFAULT '',
  `myidkey` char(16) NOT NULL DEFAULT '',
  `regip` char(15) NOT NULL DEFAULT '',
  `regdate` int(10) unsigned NOT NULL DEFAULT '0',
  `lastloginip` int(10) NOT NULL DEFAULT '0',
  `lastlogintime` int(10) unsigned NOT NULL DEFAULT '0',
  `salt` char(6) NOT NULL,
  `secques` char(8) NOT NULL DEFAULT '',
  PRIMARY KEY (`uid`),
  UNIQUE KEY `username` (`username`),
  KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uc_members
-- ----------------------------
INSERT INTO `uc_members` VALUES ('1', 'lxadmin', '93738018614ceac27943c8f66b3901c6', 'lxadmin@qq.com', '', '', '127.0.0.1', '1455673023', '0', '0', 'f31fe5', '');
INSERT INTO `uc_members` VALUES ('7', 'neirong', 'b8191326016f158f809af12cc46b65ca', 'neirong@qq.com', '', '', '127.0.0.1', '1455675159', '0', '0', '76a6f6', '');

-- ----------------------------
-- Table structure for `uc_mergemembers`
-- ----------------------------
DROP TABLE IF EXISTS `uc_mergemembers`;
CREATE TABLE `uc_mergemembers` (
  `appid` smallint(6) unsigned NOT NULL,
  `username` char(15) NOT NULL,
  PRIMARY KEY (`appid`,`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uc_mergemembers
-- ----------------------------

-- ----------------------------
-- Table structure for `uc_newpm`
-- ----------------------------
DROP TABLE IF EXISTS `uc_newpm`;
CREATE TABLE `uc_newpm` (
  `uid` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uc_newpm
-- ----------------------------

-- ----------------------------
-- Table structure for `uc_notelist`
-- ----------------------------
DROP TABLE IF EXISTS `uc_notelist`;
CREATE TABLE `uc_notelist` (
  `noteid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `operation` char(32) NOT NULL,
  `closed` tinyint(4) NOT NULL DEFAULT '0',
  `totalnum` smallint(6) unsigned NOT NULL DEFAULT '0',
  `succeednum` smallint(6) unsigned NOT NULL DEFAULT '0',
  `getdata` mediumtext NOT NULL,
  `postdata` mediumtext NOT NULL,
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `pri` tinyint(3) NOT NULL DEFAULT '0',
  `app1` tinyint(4) NOT NULL,
  PRIMARY KEY (`noteid`),
  KEY `closed` (`closed`,`pri`,`noteid`),
  KEY `dateline` (`dateline`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uc_notelist
-- ----------------------------
INSERT INTO `uc_notelist` VALUES ('1', 'updateapps', '1', '0', '0', '', '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\r\n<root>\r\n	<item id=\"1\">\r\n		<item id=\"appid\"><![CDATA[1]]></item>\r\n		<item id=\"type\"><![CDATA[DISCUZX]]></item>\r\n		<item id=\"name\"><![CDATA[BrightStarThink]]></item>\r\n		<item id=\"url\"><![CDATA[http://www.brightstarthink.com]]></item>\r\n		<item id=\"ip\"><![CDATA[]]></item>\r\n		<item id=\"viewprourl\"><![CDATA[]]></item>\r\n		<item id=\"apifilename\"><![CDATA[uc.php]]></item>\r\n		<item id=\"charset\"><![CDATA[]]></item>\r\n		<item id=\"synlogin\"><![CDATA[1]]></item>\r\n		<item id=\"extra\">\r\n			<item id=\"apppath\"><![CDATA[]]></item>\r\n		</item>\r\n		<item id=\"recvnote\"><![CDATA[0]]></item>\r\n	</item>\r\n	<item id=\"UC_API\"><![CDATA[http://www.brightstarthink.com/uc_server]]></item>\r\n</root>', '0', '0', '0');
INSERT INTO `uc_notelist` VALUES ('2', 'updatepw', '1', '0', '0', 'username=lxadmin&password=', '', '0', '0', '0');
INSERT INTO `uc_notelist` VALUES ('3', 'updatepw', '1', '0', '0', 'username=lxadmin&password=', '', '0', '0', '0');
INSERT INTO `uc_notelist` VALUES ('4', 'updateapps', '1', '0', '0', '', '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\r\n<root>\r\n	<item id=\"1\">\r\n		<item id=\"appid\"><![CDATA[1]]></item>\r\n		<item id=\"type\"><![CDATA[DISCUZX]]></item>\r\n		<item id=\"name\"><![CDATA[BrightStarThink]]></item>\r\n		<item id=\"url\"><![CDATA[http://www.brightstarthink.com]]></item>\r\n		<item id=\"ip\"><![CDATA[]]></item>\r\n		<item id=\"viewprourl\"><![CDATA[]]></item>\r\n		<item id=\"apifilename\"><![CDATA[uc.php]]></item>\r\n		<item id=\"charset\"><![CDATA[]]></item>\r\n		<item id=\"synlogin\"><![CDATA[1]]></item>\r\n		<item id=\"extra\">\r\n			<item id=\"apppath\"><![CDATA[]]></item>\r\n			<item id=\"extraurl\">\r\n			</item>\r\n		</item>\r\n		<item id=\"recvnote\"><![CDATA[0]]></item>\r\n	</item>\r\n	<item id=\"UC_API\"><![CDATA[http://www.brightstarthink.com/uc_server]]></item>\r\n</root>', '0', '0', '0');
INSERT INTO `uc_notelist` VALUES ('5', 'updateapps', '1', '0', '0', '', '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\r\n<root>\r\n	<item id=\"1\">\r\n		<item id=\"appid\"><![CDATA[1]]></item>\r\n		<item id=\"type\"><![CDATA[OTHER]]></item>\r\n		<item id=\"name\"><![CDATA[BrightStarThink]]></item>\r\n		<item id=\"url\"><![CDATA[http://www.brightstarthink.com]]></item>\r\n		<item id=\"ip\"><![CDATA[]]></item>\r\n		<item id=\"viewprourl\"><![CDATA[]]></item>\r\n		<item id=\"apifilename\"><![CDATA[uc.php]]></item>\r\n		<item id=\"charset\"><![CDATA[]]></item>\r\n		<item id=\"synlogin\"><![CDATA[1]]></item>\r\n		<item id=\"extra\">\r\n			<item id=\"apppath\"><![CDATA[]]></item>\r\n			<item id=\"extraurl\">\r\n			</item>\r\n		</item>\r\n		<item id=\"recvnote\"><![CDATA[0]]></item>\r\n	</item>\r\n	<item id=\"UC_API\"><![CDATA[http://www.brightstarthink.com/uc_server]]></item>\r\n</root>', '0', '0', '0');
INSERT INTO `uc_notelist` VALUES ('6', 'updateapps', '1', '0', '0', '', '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\r\n<root>\r\n	<item id=\"1\">\r\n		<item id=\"appid\"><![CDATA[1]]></item>\r\n		<item id=\"type\"><![CDATA[OTHER]]></item>\r\n		<item id=\"name\"><![CDATA[BrightStarThink]]></item>\r\n		<item id=\"url\"><![CDATA[http://www.brightstarthink.com]]></item>\r\n		<item id=\"ip\"><![CDATA[127.0.0.1]]></item>\r\n		<item id=\"viewprourl\"><![CDATA[]]></item>\r\n		<item id=\"apifilename\"><![CDATA[uc.php]]></item>\r\n		<item id=\"charset\"><![CDATA[]]></item>\r\n		<item id=\"synlogin\"><![CDATA[1]]></item>\r\n		<item id=\"extra\">\r\n			<item id=\"apppath\"><![CDATA[]]></item>\r\n			<item id=\"extraurl\">\r\n			</item>\r\n		</item>\r\n		<item id=\"recvnote\"><![CDATA[0]]></item>\r\n	</item>\r\n	<item id=\"UC_API\"><![CDATA[http://www.brightstarthink.com/uc_server]]></item>\r\n</root>', '0', '0', '0');
INSERT INTO `uc_notelist` VALUES ('7', 'updateapps', '1', '0', '0', '', '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\r\n<root>\r\n	<item id=\"1\">\r\n		<item id=\"appid\"><![CDATA[1]]></item>\r\n		<item id=\"type\"><![CDATA[OTHER]]></item>\r\n		<item id=\"name\"><![CDATA[BrightStarThink]]></item>\r\n		<item id=\"url\"><![CDATA[http://www.brightstarthink.com]]></item>\r\n		<item id=\"ip\"><![CDATA[]]></item>\r\n		<item id=\"viewprourl\"><![CDATA[]]></item>\r\n		<item id=\"apifilename\"><![CDATA[uc.php]]></item>\r\n		<item id=\"charset\"><![CDATA[]]></item>\r\n		<item id=\"synlogin\"><![CDATA[1]]></item>\r\n		<item id=\"extra\">\r\n			<item id=\"apppath\"><![CDATA[]]></item>\r\n			<item id=\"extraurl\">\r\n			</item>\r\n		</item>\r\n		<item id=\"recvnote\"><![CDATA[0]]></item>\r\n	</item>\r\n	<item id=\"UC_API\"><![CDATA[http://www.brightstarthink.com/uc_server]]></item>\r\n</root>', '0', '0', '0');

-- ----------------------------
-- Table structure for `uc_pm_indexes`
-- ----------------------------
DROP TABLE IF EXISTS `uc_pm_indexes`;
CREATE TABLE `uc_pm_indexes` (
  `pmid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `plid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`pmid`),
  KEY `plid` (`plid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uc_pm_indexes
-- ----------------------------

-- ----------------------------
-- Table structure for `uc_pm_lists`
-- ----------------------------
DROP TABLE IF EXISTS `uc_pm_lists`;
CREATE TABLE `uc_pm_lists` (
  `plid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `authorid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `pmtype` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `subject` varchar(80) NOT NULL,
  `members` smallint(5) unsigned NOT NULL DEFAULT '0',
  `min_max` varchar(17) NOT NULL,
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `lastmessage` text NOT NULL,
  PRIMARY KEY (`plid`),
  KEY `pmtype` (`pmtype`),
  KEY `min_max` (`min_max`),
  KEY `authorid` (`authorid`,`dateline`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uc_pm_lists
-- ----------------------------

-- ----------------------------
-- Table structure for `uc_pm_members`
-- ----------------------------
DROP TABLE IF EXISTS `uc_pm_members`;
CREATE TABLE `uc_pm_members` (
  `plid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `isnew` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `pmnum` int(10) unsigned NOT NULL DEFAULT '0',
  `lastupdate` int(10) unsigned NOT NULL DEFAULT '0',
  `lastdateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`plid`,`uid`),
  KEY `isnew` (`isnew`),
  KEY `lastdateline` (`uid`,`lastdateline`),
  KEY `lastupdate` (`uid`,`lastupdate`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uc_pm_members
-- ----------------------------

-- ----------------------------
-- Table structure for `uc_pm_messages_0`
-- ----------------------------
DROP TABLE IF EXISTS `uc_pm_messages_0`;
CREATE TABLE `uc_pm_messages_0` (
  `pmid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `plid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `authorid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `message` text NOT NULL,
  `delstatus` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`pmid`),
  KEY `plid` (`plid`,`delstatus`,`dateline`),
  KEY `dateline` (`plid`,`dateline`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uc_pm_messages_0
-- ----------------------------

-- ----------------------------
-- Table structure for `uc_pm_messages_1`
-- ----------------------------
DROP TABLE IF EXISTS `uc_pm_messages_1`;
CREATE TABLE `uc_pm_messages_1` (
  `pmid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `plid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `authorid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `message` text NOT NULL,
  `delstatus` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`pmid`),
  KEY `plid` (`plid`,`delstatus`,`dateline`),
  KEY `dateline` (`plid`,`dateline`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uc_pm_messages_1
-- ----------------------------

-- ----------------------------
-- Table structure for `uc_pm_messages_2`
-- ----------------------------
DROP TABLE IF EXISTS `uc_pm_messages_2`;
CREATE TABLE `uc_pm_messages_2` (
  `pmid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `plid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `authorid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `message` text NOT NULL,
  `delstatus` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`pmid`),
  KEY `plid` (`plid`,`delstatus`,`dateline`),
  KEY `dateline` (`plid`,`dateline`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uc_pm_messages_2
-- ----------------------------

-- ----------------------------
-- Table structure for `uc_pm_messages_3`
-- ----------------------------
DROP TABLE IF EXISTS `uc_pm_messages_3`;
CREATE TABLE `uc_pm_messages_3` (
  `pmid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `plid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `authorid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `message` text NOT NULL,
  `delstatus` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`pmid`),
  KEY `plid` (`plid`,`delstatus`,`dateline`),
  KEY `dateline` (`plid`,`dateline`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uc_pm_messages_3
-- ----------------------------

-- ----------------------------
-- Table structure for `uc_pm_messages_4`
-- ----------------------------
DROP TABLE IF EXISTS `uc_pm_messages_4`;
CREATE TABLE `uc_pm_messages_4` (
  `pmid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `plid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `authorid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `message` text NOT NULL,
  `delstatus` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`pmid`),
  KEY `plid` (`plid`,`delstatus`,`dateline`),
  KEY `dateline` (`plid`,`dateline`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uc_pm_messages_4
-- ----------------------------

-- ----------------------------
-- Table structure for `uc_pm_messages_5`
-- ----------------------------
DROP TABLE IF EXISTS `uc_pm_messages_5`;
CREATE TABLE `uc_pm_messages_5` (
  `pmid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `plid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `authorid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `message` text NOT NULL,
  `delstatus` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`pmid`),
  KEY `plid` (`plid`,`delstatus`,`dateline`),
  KEY `dateline` (`plid`,`dateline`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uc_pm_messages_5
-- ----------------------------

-- ----------------------------
-- Table structure for `uc_pm_messages_6`
-- ----------------------------
DROP TABLE IF EXISTS `uc_pm_messages_6`;
CREATE TABLE `uc_pm_messages_6` (
  `pmid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `plid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `authorid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `message` text NOT NULL,
  `delstatus` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`pmid`),
  KEY `plid` (`plid`,`delstatus`,`dateline`),
  KEY `dateline` (`plid`,`dateline`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uc_pm_messages_6
-- ----------------------------

-- ----------------------------
-- Table structure for `uc_pm_messages_7`
-- ----------------------------
DROP TABLE IF EXISTS `uc_pm_messages_7`;
CREATE TABLE `uc_pm_messages_7` (
  `pmid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `plid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `authorid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `message` text NOT NULL,
  `delstatus` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`pmid`),
  KEY `plid` (`plid`,`delstatus`,`dateline`),
  KEY `dateline` (`plid`,`dateline`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uc_pm_messages_7
-- ----------------------------

-- ----------------------------
-- Table structure for `uc_pm_messages_8`
-- ----------------------------
DROP TABLE IF EXISTS `uc_pm_messages_8`;
CREATE TABLE `uc_pm_messages_8` (
  `pmid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `plid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `authorid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `message` text NOT NULL,
  `delstatus` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`pmid`),
  KEY `plid` (`plid`,`delstatus`,`dateline`),
  KEY `dateline` (`plid`,`dateline`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uc_pm_messages_8
-- ----------------------------

-- ----------------------------
-- Table structure for `uc_pm_messages_9`
-- ----------------------------
DROP TABLE IF EXISTS `uc_pm_messages_9`;
CREATE TABLE `uc_pm_messages_9` (
  `pmid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `plid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `authorid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `message` text NOT NULL,
  `delstatus` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`pmid`),
  KEY `plid` (`plid`,`delstatus`,`dateline`),
  KEY `dateline` (`plid`,`dateline`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uc_pm_messages_9
-- ----------------------------

-- ----------------------------
-- Table structure for `uc_protectedmembers`
-- ----------------------------
DROP TABLE IF EXISTS `uc_protectedmembers`;
CREATE TABLE `uc_protectedmembers` (
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `username` char(15) NOT NULL DEFAULT '',
  `appid` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `admin` char(15) NOT NULL DEFAULT '0',
  UNIQUE KEY `username` (`username`,`appid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uc_protectedmembers
-- ----------------------------

-- ----------------------------
-- Table structure for `uc_settings`
-- ----------------------------
DROP TABLE IF EXISTS `uc_settings`;
CREATE TABLE `uc_settings` (
  `k` varchar(32) NOT NULL DEFAULT '',
  `v` text NOT NULL,
  PRIMARY KEY (`k`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uc_settings
-- ----------------------------
INSERT INTO `uc_settings` VALUES ('accessemail', '');
INSERT INTO `uc_settings` VALUES ('censoremail', '');
INSERT INTO `uc_settings` VALUES ('censorusername', '');
INSERT INTO `uc_settings` VALUES ('dateformat', 'y-n-j');
INSERT INTO `uc_settings` VALUES ('doublee', '0');
INSERT INTO `uc_settings` VALUES ('nextnotetime', '0');
INSERT INTO `uc_settings` VALUES ('timeoffset', '28800');
INSERT INTO `uc_settings` VALUES ('privatepmthreadlimit', '25');
INSERT INTO `uc_settings` VALUES ('chatpmthreadlimit', '30');
INSERT INTO `uc_settings` VALUES ('chatpmmemberlimit', '35');
INSERT INTO `uc_settings` VALUES ('pmfloodctrl', '15');
INSERT INTO `uc_settings` VALUES ('pmcenter', '1');
INSERT INTO `uc_settings` VALUES ('sendpmseccode', '1');
INSERT INTO `uc_settings` VALUES ('pmsendregdays', '0');
INSERT INTO `uc_settings` VALUES ('maildefault', 'username@21cn.com');
INSERT INTO `uc_settings` VALUES ('mailsend', '1');
INSERT INTO `uc_settings` VALUES ('mailserver', 'smtp.21cn.com');
INSERT INTO `uc_settings` VALUES ('mailport', '25');
INSERT INTO `uc_settings` VALUES ('mailauth', '1');
INSERT INTO `uc_settings` VALUES ('mailfrom', 'UCenter <username@21cn.com>');
INSERT INTO `uc_settings` VALUES ('mailauth_username', 'username@21cn.com');
INSERT INTO `uc_settings` VALUES ('mailauth_password', 'password');
INSERT INTO `uc_settings` VALUES ('maildelimiter', '0');
INSERT INTO `uc_settings` VALUES ('mailusername', '1');
INSERT INTO `uc_settings` VALUES ('mailsilent', '1');
INSERT INTO `uc_settings` VALUES ('version', '1.6.0');

-- ----------------------------
-- Table structure for `uc_sqlcache`
-- ----------------------------
DROP TABLE IF EXISTS `uc_sqlcache`;
CREATE TABLE `uc_sqlcache` (
  `sqlid` char(6) NOT NULL DEFAULT '',
  `data` char(100) NOT NULL,
  `expiry` int(10) unsigned NOT NULL,
  PRIMARY KEY (`sqlid`),
  KEY `expiry` (`expiry`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uc_sqlcache
-- ----------------------------

-- ----------------------------
-- Table structure for `uc_tags`
-- ----------------------------
DROP TABLE IF EXISTS `uc_tags`;
CREATE TABLE `uc_tags` (
  `tagname` char(20) NOT NULL,
  `appid` smallint(6) unsigned NOT NULL DEFAULT '0',
  `data` mediumtext,
  `expiration` int(10) unsigned NOT NULL,
  KEY `tagname` (`tagname`,`appid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uc_tags
-- ----------------------------

-- ----------------------------
-- Table structure for `uc_vars`
-- ----------------------------
DROP TABLE IF EXISTS `uc_vars`;
CREATE TABLE `uc_vars` (
  `name` char(32) NOT NULL DEFAULT '',
  `value` char(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`name`)
) ENGINE=MEMORY DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uc_vars
-- ----------------------------
