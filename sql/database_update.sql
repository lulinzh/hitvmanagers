-- **************2016/07/13 liyu**************
-- 增加用户表字段
alter table uc_members add phone_imei char(32) NOT NULL DEFAULT '' COMMENT '手机唯一识别码IMEI';
alter table uc_members add phone_mac char(32) NOT NULL DEFAULT '' COMMENT '手机MAC地址';
alter table uc_members add tv_code char(32) NOT NULL DEFAULT '' COMMENT '电视机顶盒编码';
-- 增加用户操作日志
insert into lx_action (name,title,remark,log,type,status) values("member_login","会员登录","会员登录","[user|get_nickname]在[time|time_format]登录用户中心。表[model]，记录编号[record]。",2,1);
insert into lx_action (name,title,remark,log,type,status) values("member_register","会员注册","会员注册","[user|get_nickname]在[time|time_format]注册用户。表[model]，记录编号[record]。",2,1);
insert into lx_action (name,title,remark,log,type,status) values("send_reg_sms","发送注册验证码短信","发送注册验证码短信","[user|get_nickname]在[time|time_format]注册用户。表[model]，记录编号[record]。",2,1);

-- **************2016/07/15 liyu**************
-- 增加机顶盒用户绑定表
-- DROP TABLE `lx_tvcode_member`;
CREATE TABLE `lx_tvcode_member` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '标识 (唯一标识)',
	`tv_code` char(32) NOT NULL DEFAULT '' COMMENT '电视机顶盒编码',
	`uid` int(8) NOT NULL DEFAULT '0' COMMENT '用户ID',
	`status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '绑定状态',
	`update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '绑定时间',
-- 主键
	PRIMARY KEY (`id`),
-- 建索引
	KEY `tv_code` (`tv_code`),
	KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT '机顶盒用户绑定表';
alter table lx_tvcode_member add `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '绑定状态';

-- **************2016/07/16 lmx**************
-- 修改会话表,增加设备类型字段
ALTER TABLE `lx_hitv_req_session`
CHANGE COLUMN `action_type` `session_type`  smallint(4) NOT NULL AFTER `id`,
CHANGE COLUMN `tv_box_id` `device_id`  varchar(32) NOT NULL AFTER `session_type`,
CHANGE COLUMN `action_param` `session_in_param`  char(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `user_id`,
CHANGE COLUMN `status` `session_status`  tinyint(1) NOT NULL DEFAULT 0 AFTER `session_in_param`,
ADD COLUMN `session_out_param`  char(100) NULL AFTER `session_in_param`;

ALTER TABLE `lx_hitv_req_session`
ADD COLUMN `device_type`  smallint(4) NOT NULL AFTER `session_type`;

-- **************2016/07/17 lmx**************
-- 修改订单表
ALTER TABLE `lx_hitv_order`
MODIFY COLUMN `gen_user_id`  char(15) NULL DEFAULT NULL AFTER `price`,
MODIFY COLUMN `pay_user_id`  char(15) NULL DEFAULT NULL AFTER `gen_user_id`;

-- **************2016/07/18 lmx**************
-- 修改订单表 加长参数字段长度
ALTER TABLE `lx_hitv_req_session`
MODIFY COLUMN `session_in_param`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER `user_id`,
MODIFY COLUMN `session_out_param`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER `session_in_param`;


-- **************2016/07/18 liyu**************
-- 增加机顶盒用户绑定表字段
alter table lx_tvcode_member add `bd_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '绑定类型，0：登录绑定，1：代付绑定';

-- **************2016/07/18 liyu**************
-- 修改用户表字段
ALTER TABLE `lx_hitv_user`
DROP COLUMN `user_nickname`,
DROP COLUMN `user_phone`,
DROP COLUMN `user_password`,
DROP COLUMN `user_sex`,
ADD COLUMN `app_id`  int(8) NOT NULL AFTER `id`,

MODIFY COLUMN `user_id`  mediumint(8) NOT NULL AFTER `app_id`,
MODIFY COLUMN `hitv_id`  varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `user_id`;
ADD COLUMN `user_id`  mediumint(8) NULL AFTER `app_id`,
ADD COLUMN `hitv_id`  varchar(64) NULL AFTER `user_id`;

-- 增加App表字段
CREATE TABLE `lx_hitv_app` (
`app_id`  integer(8) NOT NULL AUTO_INCREMENT ,
`app_key`  varchar(64) NOT NULL ,
`app_secret`  varchar(64) NOT NULL ,
`app_name`  varchar(64) NOT NULL ,
`app_info`  varchar(128) NULL ,
`app_state`  tinyint(4) NOT NULL DEFAULT 0 ,
PRIMARY KEY (`app_id`)
)

ALTER TABLE `lx_hitv_app`
ADD COLUMN `key_salt`  char(6) NOT NULL AFTER `app_state`;

ALTER TABLE `lx_hitv_user`
ADD COLUMN `salt`  char(6) NOT NULL AFTER `hitv_id`;


CREATE TABLE `lx_hitv_access_token` (
`id`  integer(16) NOT NULL AUTO_INCREMENT ,
`hitv_id`  varchar(64) NOT NULL ,
`app_id`  integer(8) NOT NULL ,
`access_token`  varchar(64) NOT NULL ,
`access_expired_time`  integer(10) NOT NULL ,
`refresh_token`  varchar(64) NOT NULL ,
`refresh_expired_time`  integer(10) NOT NULL ,
PRIMARY KEY (`id`)
)
;


CREATE TABLE `lx_hitv_app_config` (
`id`  int NOT NULL AUTO_INCREMENT ,
`version_name`  varchar(255) NOT NULL ,
`version_code`  int NOT NULL ,
`type`  int NOT NULL ,
`index_url`  text NOT NULL ,
`discover_url`  text NOT NULL ,
`update_url`  text NOT NULL ,
`agreement_url`  text NOT NULL ,
`about_url`  text NOT NULL ,
`splash_link_url`  text NOT NULL ,
`splash_img_url`  text NOT NULL ,
`deprecated`  int NOT NULL DEFAULT 0 ,
PRIMARY KEY (`id`)
)
;

ALTER TABLE `lx_hitv_app_config`
ADD COLUMN `update_info`  text NOT NULL AFTER `splash_img_url`,
ADD COLUMN `version_info`  text NOT NULL AFTER `update_info`,
DEFAULT CHARACTER SET=utf8;





