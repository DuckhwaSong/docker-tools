SET NAMES utf8mb4 ;

-- shopSno 변경
-- UPDATE `fm_config` SET `value`='851230' WHERE `groupcd`='system' AND `codecd`='shopSno';
INSERT INTO `fm_config` VALUES ('system','service','a:11:{s:4:"code";s:6:"P_ARSC";s:4:"name";s:16:"P_ARSC 개발용";s:12:"hosting_name";s:18:"슈퍼비지니스";s:12:"setting_date";s:10:"2024-07-18";s:11:"expire_date";s:10:"2124-07-18";s:10:"disk_space";i:1024000;s:7:"traffic";s:9:"무제한";s:6:"sms_id";N;s:3:"cid";s:8:"gsunsoft";s:13:"max_board_cnt";s:1:"5";s:15:"max_manager_cnt";s:1:"1";}',NOW());
INSERT INTO `fm_config` VALUES ('system','shopSno','851230',NOW());

--  언어/화폐
INSERT INTO fm_currency (admin_env_seq,currency,currency_type,currency_amout,currency_exchange,cutting_price,cutting_action,currency_symbol,currency_symbol_position) VALUES
(1,'USD','compare',0.00,1.20,0.100,'ascending','$','after'),
(1,'KRW','basic',0.00,1137.30,0.010,'ascending','원','after'),
(1,'CNY','compare',0.00,6.68,0.100,'ascending','&yen;','after'),
(1,'JPY','compare',0.00,104.28,0.100,'ascending','円','after'),
(1,'EUR','compare',0.00,0.91,0.100,'ascending','&euro;','after');

--  슈퍼 관리자
INSERT INTO `fm_manager` (`manager_seq`, `manager_id`, `mpasswd`, `mname`, `mphoto`, `memail`, `mphone`, `mcellphone`, `limit_ip`, `manager_log`, `lastlogin_date`, `mregdate`, `passwordChange`, `passwordUpdateTime`, `gnb_icon_view`, `member_download_passwd`, `auth_hp`) VALUES
(1, 'gabia1', '4cbe5c8f11fe0c01c97d77dfbae3daff', '관리자', '', NULL, NULL, NULL, NULL, '', '2024-04-25 10:54:56', '2024-01-22 17:27:33', 'N', '2124-01-22 17:27:33', 'y', NULL, NULL),
(2, 'gabia-', '52bbea75ee75a6f1d24781d56e6c8ffb9b76e1024013b516bbaaac27e21ae9e0', '관리자', '', '', '', '', '', '', "0000-00-00 00:00:00", "0000-00-00 00:00:00", "N", "2099-01-01 00:00:00", "y", null, null);

-- 슈퍼 관리자 권한
INSERT INTO `fm_manager_auth` (`idx`, `shopSno`, `manager_seq`, `codecd`, `value`) VALUES
(44, 851230, 1, 'account_view', 'Y'),
(7, 851230, 1, 'autodeposit_act', 'Y'),
(6, 851230, 1, 'autodeposit_view', 'Y'),
(75, 851230, 1, 'board_manger', 'Y'),
(109, 851230, 1, 'broadcast_act', 'Y'),
(77, 851230, 1, 'counsel_act', 'Y'),
(76, 851230, 1, 'counsel_view', 'Y'),
(25, 851230, 1, 'coupon_act', 'Y'),
(24, 851230, 1, 'coupon_view', 'Y'),
(45, 851230, 1, 'design_act', 'Y'),
(18, 851230, 1, 'dormancy_view', 'Y'),
(27, 851230, 1, 'event_act', 'Y'),
(26, 851230, 1, 'event_view', 'Y'),
(29, 851230, 1, 'gift_act', 'Y'),
(28, 851230, 1, 'gift_view', 'Y'),
(13, 851230, 1, 'goods_act', 'Y'),
(12, 851230, 1, 'goods_view', 'Y'),
(108, 851230, 1, 'ifdo_marketing_view', 'Y'),
(33, 851230, 1, 'joincheck_act', 'Y'),
(32, 851230, 1, 'joincheck_view', 'Y'),
(22, 851230, 1, 'kakaotalk_setting', 'Y'),
(23, 851230, 1, 'kakaotalk_view', 'Y'),
(1, 851230, 1, 'manager_yn', 'Y'),
(34, 851230, 1, 'marketplace_act', 'Y'),
(35, 851230, 1, 'marketplace_view', 'Y'),
(15, 851230, 1, 'member_act', 'Y'),
(17, 851230, 1, 'member_download', 'Y'),
(16, 851230, 1, 'member_promotion', 'Y'),
(21, 851230, 1, 'member_send', 'Y'),
(14, 851230, 1, 'member_view', 'Y'),
(106, 851230, 1, 'mobileapp_push', 'Y'),
(105, 851230, 1, 'mobileapp_setting', 'Y'),
(107, 851230, 1, 'o2osetting_act', 'Y'),
(103, 851230, 1, 'openmarket_linkage_setting', 'Y'),
(104, 851230, 1, 'openmarket_order_goods', 'Y'),
(3, 851230, 1, 'order_deposit', 'Y'),
(4, 851230, 1, 'order_goods_export', 'Y'),
(2, 851230, 1, 'order_view', 'Y'),
(5, 851230, 1, 'personal_act', 'Y'),
(111, 851230, 1, 'private_masking', 'Y'),
(43, 851230, 1, 'provider_act', 'Y'),
(42, 851230, 1, 'provider_view', 'Y'),
(31, 851230, 1, 'referer_act', 'Y'),
(30, 851230, 1, 'referer_view', 'Y'),
(9, 851230, 1, 'refund_act', 'Y'),
(8, 851230, 1, 'refund_view', 'Y'),
(113, 851230, 1, 'report_act', 'Y'),
(112, 851230, 1, 'report_view', 'Y'),
(11, 851230, 1, 'sales_view', 'Y'),
(93, 851230, 1, 'scmautoorder_act', 'Y'),
(92, 851230, 1, 'scmautoorder_view', 'Y'),
(99, 851230, 1, 'scmcarryingout_act', 'Y'),
(98, 851230, 1, 'scmcarryingout_view', 'Y'),
(85, 851230, 1, 'scmgoods_act', 'Y'),
(84, 851230, 1, 'scmgoods_view', 'Y'),
(91, 851230, 1, 'scminven_view', 'Y'),
(90, 851230, 1, 'scmledger_view', 'Y'),
(87, 851230, 1, 'scmrevision_act', 'Y'),
(86, 851230, 1, 'scmrevision_view', 'Y'),
(95, 851230, 1, 'scmsorder_act', 'Y'),
(94, 851230, 1, 'scmsorder_view', 'Y'),
(101, 851230, 1, 'scmsordforwhs_view', 'Y'),
(100, 851230, 1, 'scmsordwhs_view', 'Y'),
(89, 851230, 1, 'scmstockmove_act', 'Y'),
(88, 851230, 1, 'scmstockmove_view', 'Y'),
(79, 851230, 1, 'scmstore_act', 'Y'),
(78, 851230, 1, 'scmstore_view', 'Y'),
(81, 851230, 1, 'scmtrader_act', 'Y'),
(80, 851230, 1, 'scmtrader_view', 'Y'),
(102, 851230, 1, 'scmtraderaccount_view', 'Y'),
(83, 851230, 1, 'scmwarehouse_act', 'Y'),
(82, 851230, 1, 'scmwarehouse_view', 'Y'),
(97, 851230, 1, 'scmwarehousing_act', 'Y'),
(96, 851230, 1, 'scmwarehousing_view', 'Y'),
(61, 851230, 1, 'setting_address_act', 'Y'),
(60, 851230, 1, 'setting_address_view', 'Y'),
(74, 851230, 1, 'setting_admin_view', 'Y'),
(55, 851230, 1, 'setting_bank_view', 'Y'),
(47, 851230, 1, 'setting_basic_detail', 'Y'),
(46, 851230, 1, 'setting_basic_view', 'Y'),
(71, 851230, 1, 'setting_deliverycompany_act', 'Y'),
(70, 851230, 1, 'setting_deliverycompany_view', 'Y'),
(59, 851230, 1, 'setting_goodscd_act', 'Y'),
(58, 851230, 1, 'setting_goodscd_view', 'Y'),
(57, 851230, 1, 'setting_member_act', 'Y'),
(56, 851230, 1, 'setting_member_view', 'Y'),
(53, 851230, 1, 'setting_operating_act', 'Y'),
(52, 851230, 1, 'setting_operating_view', 'Y'),
(63, 851230, 1, 'setting_order_act', 'Y'),
(62, 851230, 1, 'setting_order_view', 'Y'),
(54, 851230, 1, 'setting_pg_view', 'Y'),
(73, 851230, 1, 'setting_protect_act', 'Y'),
(72, 851230, 1, 'setting_protect_view', 'Y'),
(67, 851230, 1, 'setting_reserve_act', 'Y'),
(66, 851230, 1, 'setting_reserve_view', 'Y'),
(65, 851230, 1, 'setting_sale_act', 'Y'),
(64, 851230, 1, 'setting_sale_view', 'Y'),
(49, 851230, 1, 'setting_seo_act', 'Y'),
(48, 851230, 1, 'setting_seo_view', 'Y'),
(69, 851230, 1, 'setting_shipping_act', 'Y'),
(68, 851230, 1, 'setting_shipping_view', 'Y'),
(51, 851230, 1, 'setting_snsconf_act', 'Y'),
(50, 851230, 1, 'setting_snsconf_view', 'Y'),
(41, 851230, 1, 'statistic_epc', 'Y'),
(40, 851230, 1, 'statistic_goods', 'Y'),
(38, 851230, 1, 'statistic_member', 'Y'),
(39, 851230, 1, 'statistic_sales', 'Y'),
(36, 851230, 1, 'statistic_summary', 'Y'),
(37, 851230, 1, 'statistic_visitor', 'Y'),
(10, 851230, 1, 'temporary_view', 'Y'),
(110, 851230, 1, 'vod_act', 'Y'),
(20, 851230, 1, 'withdrawal_act', 'Y'),
(19, 851230, 1, 'withdrawal_view', 'Y');


-- 권한 추가
INSERT INTO fm_manager_auth (shopSno,manager_seq,codecd,value)
VALUES ((SELECT value FROM fm_config WHERE codecd ='shopSno'),(SELECT manager_seq FROM fm_manager WHERE manager_id='gabia-'),'manager_yn','Y');

-- 권한 추가
INSERT INTO `fm_admin_env`(`admin_env_seq`, `shopSno`, `use_yn`, `admin_env_name`, `favicon`, `currency`, `temp_domain`, `domain`, `language`, `compare_currency`, `first_goods_date`, `store_type`, `store_location`)
VALUES ('1','851230', 'y', 'dev', '', 'KRW', 'demo.devbox.firstmall.kr','demo.devbox.firstmall.kr', 'KR', NULL, '2019-02-14 16:44','on', 'KOR');

-- ssl 사용안함 설정
UPDATE `fm_config` SET `value`='0' WHERE `groupcd`='system' AND `codecd`='ssl_use';

