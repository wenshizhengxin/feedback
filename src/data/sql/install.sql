SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for epii_feedback
-- ----------------------------
CREATE TABLE IF NOT EXISTS `epii_feedback`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '反馈消息表主键id',
  `feedback_title` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '反馈消息标题（如果有的话）',
  `feedback_type` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '反馈消息类型：1-项目需求；2-项目bug；3-优化建议',
  `level` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '反馈级别：1-放弃；10-一般；20-紧急；99-非常紧急',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '反馈描述',
  `img` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '图片（多个间用“,”隔开）',
  `status` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '状态：0-已提交；1-已阅览；5-处理中；9-已退回；10-已解决',
  `from_uid` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '提交人uid',
  `access_time` int(10) UNSIGNED NULL DEFAULT 0 COMMENT '访问时间',
  `dealing_time` int(10) UNSIGNED NULL DEFAULT 0 COMMENT '开始处理时间',
  `finish_time` int(10) UNSIGNED NULL DEFAULT 0 COMMENT '完成时间',
  `return_time` int(10) UNSIGNED NULL DEFAULT 0 COMMENT '退回时间',
  `create_time` int(10) UNSIGNED NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '反馈消息表';

SET FOREIGN_KEY_CHECKS = 1;
