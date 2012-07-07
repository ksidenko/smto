DROP TABLE IF EXISTS `p2_auth_assignment`;
DROP TABLE IF EXISTS `p2_auth_item_child`;
DROP TABLE IF EXISTS `p2_auth_item`;
DROP TABLE IF EXISTS `p2_cell`;
DROP TABLE IF EXISTS `p2_file`;
DROP TABLE IF EXISTS `p2_html`;
DROP TABLE IF EXISTS `p2_info`;
DROP TABLE IF EXISTS `p2_log`;
DROP TABLE IF EXISTS `p2_page`;
DROP TABLE IF EXISTS `p2_user`;
DROP TABLE IF EXISTS `p2_config`;

-- -----------------------------------------------------
-- Table `p2_user`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `p2_user` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(32) NOT NULL ,
  `firstName` VARCHAR(64) NOT NULL COMMENT 'Real name' ,
  `lastName` VARCHAR(64) NOT NULL COMMENT 'Real name' ,
  `eMail` VARCHAR(128) NOT NULL COMMENT 'Verification e-mails will be sent to this address' ,
  `verifyEmail` VARCHAR(128) NULL COMMENT 'Updated e-mail address, to be verified' ,
  `password` VARCHAR(32) NOT NULL COMMENT 'Hash-value' ,
  `token` VARCHAR(64) NULL COMMENT 'Random string for authentification' ,
  `tokenExpires` INT NULL ,
  `status` INT NULL DEFAULT 20 COMMENT '0=DELETED, 10=BLOCKED, 20=NEW, 30=VERIFIED, 40=ACTIVE' ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `uname` (`name` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `p2_file`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `p2_file` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(128) NOT NULL ,
  `filePath` VARCHAR(255) NOT NULL ,
  `fileType` VARCHAR(32) NULL ,
  `fileSize` INT NOT NULL ,
  `fileOriginalName` VARCHAR(128) NOT NULL ,
  `fileMd5` VARCHAR(32) NOT NULL ,
  `fileInfo` TEXT NULL ,
  `p2_infoId` INT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `uname` (`name` ASC) ,
  UNIQUE INDEX `ufile` (`filePath` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `p2_html`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `p2_html` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(64) NOT NULL ,
  `html` TEXT NULL ,
  `p2_infoId` INT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `uname` (`name` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `p2_page`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `p2_page` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(128) NOT NULL COMMENT 'unique name of the page, usage: URLs' ,
  `descriptiveName` VARCHAR(255) NULL COMMENT 'Full name of the page, usage: menu text, link text' ,
  `url` VARCHAR(255) NULL COMMENT 'If a string the URL of the link or, if a JSONString {route:\'\', params{...}} it will be parsed with createUrl()' ,
  `parentId` INT NOT NULL DEFAULT 1 COMMENT 'The id of this page\'s parent' ,
  `rank` INT NOT NULL DEFAULT 100 COMMENT 'Order for menus' ,
  `view` VARCHAR(64) NULL COMMENT 'View path to render' ,
  `layout` VARCHAR(64) NULL COMMENT 'Layout path to render' ,
  `replaceMethod` VARCHAR(128) NULL COMMENT 'Method to replace this node in menus entirely' ,
  `p2_infoId` INT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `uname` (`name` ASC) ,
  INDEX `fk_p2_page_p2_page` (`parentId` ASC) ,
  CONSTRAINT `fk_p2_page_p2_page`
    FOREIGN KEY (`parentId` )
    REFERENCES `p2_page` (`id` )
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `p2_cell`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `p2_cell` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `classPath` VARCHAR(255) NOT NULL COMMENT 'The path to the wiget class' ,
  `classProps` TEXT NULL COMMENT 'JSONObject with widget properties' ,
  `rank` INT NOT NULL DEFAULT 100 COMMENT 'Order within cell' ,
  `cellId` VARCHAR(64) NULL ,
  `moduleId` VARCHAR(32) NULL ,
  `controllerId` VARCHAR(32) NULL ,
  `actionName` VARCHAR(32) NULL ,
  `requestParam` VARCHAR(32) NULL ,
  `sessionParam` VARCHAR(32) NULL ,
  `cookieParam` VARCHAR(32) NULL ,
  `applicationParam` VARCHAR(32) NULL ,
  `moduleParam` VARCHAR(32) NULL ,
  `p2_infoId` INT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `p2_info`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `p2_info` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `model` VARCHAR(64) NULL COMMENT 'Model class this entry belongs to' ,
  `modelId` INT NOT NULL COMMENT 'Id for model' ,
  `language` VARCHAR(16) NULL COMMENT 'Language for this item, NULL means available in all languages' ,
  `status` INT NOT NULL DEFAULT 30 COMMENT 'STATUS_DELETED    = 0;\nSTATUS_DRAFT      = 10;\nSTATUS_PENDING    = 20;\nSTATUS_ACTIVE     = 30;\nSTATUS_LOCKED     = 40;\nSTATUS_ARCHIVE    = 50;\n' ,
  `type` VARCHAR(64) NULL COMMENT 'A configurable (see config/p2.php) type for items' ,
  `checkAccess` VARCHAR(32) NULL ,
  `createdBy` INT NULL ,
  `createdAt` DATETIME NOT NULL ,
  `modifiedBy` INT NULL ,
  `modifiedAt` DATETIME NOT NULL ,
  `begin` DATETIME NULL ,
  `end` DATETIME NULL ,
  `keywords` TEXT NULL COMMENT 'Tag-style information' ,
  `customData` TEXT NULL ,
  `parentId` INT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_p2_info_p2_user` (`createdBy` ASC) ,
  INDEX `fk_p2_info_p2_user1` (`modifiedBy` ASC) ,
  UNIQUE INDEX `unique_model_id` (`id` ASC, `model` ASC) ,
  INDEX `fk_p2_info_p2_info1` (`parentId` ASC) ,
  CONSTRAINT `fk_p2_info_p2_user`
    FOREIGN KEY (`createdBy` )
    REFERENCES `p2_user` (`id` )
    ON DELETE SET NULL
    ON UPDATE SET NULL,
  CONSTRAINT `fk_p2_info_p2_user1`
    FOREIGN KEY (`modifiedBy` )
    REFERENCES `p2_user` (`id` )
    ON DELETE SET NULL
    ON UPDATE SET NULL,
  CONSTRAINT `fk_p2_info_p2_info1`
    FOREIGN KEY (`parentId` )
    REFERENCES `p2_info` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `p2_log`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `p2_log` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `description` VARCHAR(255) NULL ,
  `action` VARCHAR(32) NULL ,
  `model` VARCHAR(64) NULL ,
  `modelId` INT NULL ,
  `changes` VARCHAR(255) NULL ,
  `createdBy` INT NULL ,
  `createdAt` DATETIME NULL ,
  `data` TEXT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_p2_log_p2_user` (`createdBy` ASC) ,
  CONSTRAINT `fk_p2_log_p2_user`
    FOREIGN KEY (`createdBy` )
    REFERENCES `p2_user` (`id` )
    ON DELETE SET NULL
    ON UPDATE SET NULL)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `p2_auth_item`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `p2_auth_item` (
  `name` VARCHAR(64) NOT NULL ,
  `type` INT NOT NULL ,
  `description` TEXT NULL DEFAULT NULL ,
  `bizrule` TEXT NULL DEFAULT NULL ,
  `data` TEXT NULL DEFAULT NULL ,
  PRIMARY KEY (`name`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `p2_auth_item_child`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `p2_auth_item_child` (
  `parent` VARCHAR(64) NOT NULL ,
  `child` VARCHAR(64) NOT NULL ,
  PRIMARY KEY (`parent`, `child`) ,
  INDEX `fk_751FF434-A447-407E-B89F-2A640F3268BD` (`child` ASC) ,
  CONSTRAINT `fk_776C1569-3744-451E-96E2-D8F1891CC27A`
    FOREIGN KEY (`parent` )
    REFERENCES `p2_auth_item` (`name` )
    ON DELETE cascade
    ON UPDATE cascade,
  CONSTRAINT `fk_751FF434-A447-407E-B89F-2A640F3268BD`
    FOREIGN KEY (`child` )
    REFERENCES `p2_auth_item` (`name` )
    ON DELETE cascade
    ON UPDATE cascade)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `p2_auth_assignment`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `p2_auth_assignment` (
  `itemname` VARCHAR(64) NOT NULL ,
  `userid` INT NOT NULL ,
  `bizrule` TEXT NULL DEFAULT NULL ,
  `data` TEXT NULL DEFAULT NULL ,
  PRIMARY KEY (`itemname`, `userid`) ,
  INDEX `fk_p2_auth_assignment_p2_user1` (`userid` ASC) ,
  CONSTRAINT `fk_D1C17BEB-E339-4B75-9F2F-9DA964C8C4F2`
    FOREIGN KEY (`itemname` )
    REFERENCES `p2_auth_item` (`name` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_p2_auth_assignment_p2_user1`
    FOREIGN KEY (`userid` )
    REFERENCES `p2_user` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `p2_config`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `p2_config` (
  `key` VARCHAR(64) NOT NULL ,
  `value` TEXT NULL ,
  PRIMARY KEY (`key`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;