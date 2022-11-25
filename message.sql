/*
 Navicat Premium Data Transfer

 Source Server         : 开发环境
 Source Server Type    : MySQL
 Source Server Version : 80012
 Source Host           : localhost:3306
 Source Schema         : tp5

 Target Server Type    : MySQL
 Target Server Version : 80012
 File Encoding         : 65001

 Date: 25/11/2022 10:38:41
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for message
-- ----------------------------
DROP TABLE IF EXISTS `message`;
CREATE TABLE `message`  (
  `sendId` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '发送者ID',
  `recId` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '接受者ID',
  `messageId` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '站内信内容id',
  `create_date` datetime(0) NOT NULL COMMENT '创建时间',
  `title` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '消息标题',
  UNIQUE INDEX `recId_messageId`(`recId`, `messageId`) USING BTREE,
  INDEX `messageId`(`messageId`) USING BTREE,
  INDEX `recId`(`recId`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of message
-- ----------------------------
INSERT INTO `message` VALUES (0, 0, 18, '2022-11-24 22:15:00', '我是标题');
INSERT INTO `message` VALUES (0, 0, 19, '2022-11-24 22:22:38', '我是标题123');
INSERT INTO `message` VALUES (0, 0, 20, '2022-11-24 22:22:51', '123');
INSERT INTO `message` VALUES (0, 0, 21, '2022-11-25 10:22:56', '123');
INSERT INTO `message` VALUES (0, 0, 22, '2022-11-25 10:24:35', '123');
INSERT INTO `message` VALUES (0, 0, 23, '2022-11-25 10:24:42', '123');
INSERT INTO `message` VALUES (0, 0, 24, '2022-11-25 10:25:15', '123');
INSERT INTO `message` VALUES (0, 0, 25, '2022-11-25 10:25:23', '123');
INSERT INTO `message` VALUES (0, 0, 31, '2022-11-25 10:28:57', '群发的');
INSERT INTO `message` VALUES (0, 1, 17, '2022-11-24 21:54:37', '我是标题');
INSERT INTO `message` VALUES (0, 1, 30, '2022-11-25 10:28:45', '12311111');
INSERT INTO `message` VALUES (0, 2, 26, '2022-11-25 10:26:45', '123');
INSERT INTO `message` VALUES (0, 2, 29, '2022-11-25 10:28:18', '12311111');
INSERT INTO `message` VALUES (0, 3, 27, '2022-11-25 10:26:52', '123');
INSERT INTO `message` VALUES (0, 4, 28, '2022-11-25 10:26:56', '123');

-- ----------------------------
-- Table structure for message_customer
-- ----------------------------
DROP TABLE IF EXISTS `message_customer`;
CREATE TABLE `message_customer`  (
  `customerId` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户id',
  `messageId` int(11) NOT NULL COMMENT '消息ID',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '阅读状态',
  UNIQUE INDEX `customerId`(`customerId`, `messageId`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of message_customer
-- ----------------------------
INSERT INTO `message_customer` VALUES (1, 17, 1);
INSERT INTO `message_customer` VALUES (1, 18, 1);
INSERT INTO `message_customer` VALUES (1, 19, 1);
INSERT INTO `message_customer` VALUES (1, 25, 1);

-- ----------------------------
-- Table structure for message_text
-- ----------------------------
DROP TABLE IF EXISTS `message_text`;
CREATE TABLE `message_text`  (
  `messageId` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `message_content` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '消息内容',
  `create_date` datetime(0) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`messageId`) USING BTREE,
  INDEX `messageId`(`messageId`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of message_text
-- ----------------------------
INSERT INTO `message_text` VALUES (17, '测试321', '2022-11-24 21:54:37');
INSERT INTO `message_text` VALUES (18, '测试321', '2022-11-24 22:15:00');
INSERT INTO `message_text` VALUES (19, '测试321123123123', '2022-11-24 22:22:38');
INSERT INTO `message_text` VALUES (20, '测试123啊测试', '2022-11-24 22:22:51');
INSERT INTO `message_text` VALUES (21, '测试123啊测试', '2022-11-25 10:22:56');
INSERT INTO `message_text` VALUES (22, '测试123啊测试', '2022-11-25 10:24:35');
INSERT INTO `message_text` VALUES (23, '测试123啊测试', '2022-11-25 10:24:42');
INSERT INTO `message_text` VALUES (24, '测试123啊测试', '2022-11-25 10:25:15');
INSERT INTO `message_text` VALUES (25, '测试123啊测试', '2022-11-25 10:25:23');
INSERT INTO `message_text` VALUES (26, '测试123啊测试', '2022-11-25 10:26:45');
INSERT INTO `message_text` VALUES (27, '测试123啊测试', '2022-11-25 10:26:52');
INSERT INTO `message_text` VALUES (28, '测试123啊测试', '2022-11-25 10:26:56');
INSERT INTO `message_text` VALUES (29, '测试123啊测试', '2022-11-25 10:28:18');
INSERT INTO `message_text` VALUES (30, '测试123啊测试', '2022-11-25 10:28:45');
INSERT INTO `message_text` VALUES (31, '测试123啊测试', '2022-11-25 10:28:57');

SET FOREIGN_KEY_CHECKS = 1;
