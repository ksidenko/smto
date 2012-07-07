-- 2.1-beta-1
ALTER TABLE `P2User` ADD `verifyEmail` VARCHAR( 128 ) NOT NULL AFTER `eMail`;

-- 2.1-beta-3
ALTER TABLE  `P2Info` ADD  `checkAccess` VARCHAR( 32 ) NULL ,
ADD  `parentId` INT  NULL;
UPDATE `P2Info` SET `checkAccess` = NULL WHERE `checkAccess` = '';

-- 2.1-beta-4

-- 2.1-beta-5 (pii to p2)
RENAME TABLE `PiiCell` TO `p2_cell`;
RENAME TABLE `PiiHtml` TO `p2_html` ;
RENAME TABLE `PiiFile` TO `p2_file` ;
RENAME TABLE `PiiPage` TO `p2_page` ;
RENAME TABLE `PiiInfo` TO `p2_info` ;
RENAME TABLE `PiiUser` TO `p2_user` ;
RENAME TABLE `PiiConfig` TO `p2_config` ;
RENAME TABLE `PiiLog` TO `p2_log` ;
RENAME TABLE `PiiAuthAssignment` TO `p2_auth_assignment` ;
RENAME TABLE `PiiAuthItem` TO `p2_auth_item` ;
RENAME TABLE `PiiAuthItemChild` TO `p2_auth_item_child` ;

ALTER TABLE `p2_cell` CHANGE `piiInfoId` `p2_infoId` INT( 11 ) NULL DEFAULT NULL;
ALTER TABLE `p2_html` CHANGE `piiInfoId` `p2_infoId` INT( 11 ) NULL DEFAULT NULL;
ALTER TABLE `p2_page` CHANGE `piiInfoId` `p2_infoId` INT( 11 ) NULL DEFAULT NULL;
ALTER TABLE `p2_file` CHANGE `piiInfoId` `p2_infoId` INT( 11 ) NULL DEFAULT NULL;

UPDATE `p2_cell` SET `classPath` = REPLACE(REPLACE(classPath,'Pii','P2'),'pii','p2');
UPDATE `p2_cell` SET `classPath` = 'p2.widgets.googlefeed.P2GoogleFeedWidget' WHERE classPath = 'p2.widgets.feed.P2GoogleFeedWidget';
UPDATE `p2_cell` SET `classPath` = 'p2.extensions.tweet.ETweet' WHERE classPath = 'p2.extensions.tweet.P2Tweet';
UPDATE `p2_cell` SET `moduleId` = 'p2' WHERE moduleId = 'pii';
UPDATE `p2_cell` SET `controllerId` = 'p2Page' WHERE controllerId = 'piiPage';
UPDATE `p2_info` SET `model` = REPLACE(model,'Pii','P2');
UPDATE `p2_html` SET `html` = REPLACE(html,'/pii/piiFile/image','/p2/p2File/image');

-- 2.1-beta-6

-- 2.1-beta-7
ALTER TABLE `p2_page` DROP FOREIGN KEY `fk_p2_page_p2_page` ;
ALTER TABLE `p2_page` ADD FOREIGN KEY ( `parentId` ) REFERENCES `p2`.`p2_page` (
`id`
) ON DELETE RESTRICT ON UPDATE CASCADE ;

-- 2.1 beta 8
