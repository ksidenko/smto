INSERT INTO `p2_user` (`id`,`name`,`firstName`,`lastName`,`eMail`,`password`,`status`)
VALUES
	(1,'admin','Website','Administrator','__ADMIN_EMAIL__','__ADMIN_PASSWORD__',40);


--
-- Daten für Tabelle `p2_auth_item`
--

INSERT INTO `p2_auth_item` (`name`, `type`, `description`, `bizrule`, `data`) VALUES
('admin', 2, NULL, NULL, NULL),
('createFile', 0, NULL, NULL, NULL),
('createPage', 0, NULL, NULL, NULL),
('createUser', 0, NULL, NULL, NULL),
('createWidget', 0, NULL, NULL, NULL),
('deleteFile', 0, NULL, NULL, NULL),
('deletePage', 0, NULL, NULL, NULL),
('deleteUser', 0, NULL, NULL, NULL),
('deleteWidget', 0, NULL, NULL, NULL),
('editFile', 0, NULL, NULL, NULL),
('editor', 2, NULL, NULL, NULL),
('editPage', 0, NULL, NULL, NULL),
('editUser', 0, NULL, NULL, NULL),
('editWidget', 0, NULL, NULL, NULL),
('fileManager', 1, NULL, NULL, NULL),
('pageManager', 1, NULL, NULL, NULL),
('userManager', 1, NULL, NULL, NULL),
('viewFile', 0, NULL, NULL, NULL),
('viewPage', 0, NULL, NULL, NULL),
('viewUser', 0, NULL, NULL, NULL),
('viewWidget', 0, NULL, NULL, NULL),
('widgetManager', 1, NULL, NULL, NULL),
('member', 2, NULL, NULL, NULL);

--
-- Daten für Tabelle `p2_auth_item_child`
--

INSERT INTO `p2_auth_item_child` (`parent`, `child`) VALUES
('fileManager', 'createFile'),
('pageManager', 'createPage'),
('userManager', 'createUser'),
('widgetManager', 'createWidget'),
('fileManager', 'deleteFile'),
('pageManager', 'deletePage'),
('userManager', 'deleteUser'),
('widgetManager', 'deleteWidget'),
('fileManager', 'editFile'),
('pageManager', 'editPage'),
('userManager', 'editUser'),
('widgetManager', 'editWidget'),
('admin', 'fileManager'),
('editor', 'fileManager'),
('admin', 'pageManager'),
('editor', 'pageManager'),
('admin', 'userManager'),
('fileManager', 'viewFile'),
('pageManager', 'viewPage'),
('userManager', 'viewUser'),
('widgetManager', 'viewWidget'),
('admin', 'widgetManager'),
('editor', 'widgetManager');


INSERT INTO `p2_auth_assignment` (`itemname`, `userid`, `bizrule`, `data`) VALUES
('admin', '1', '', 's:0:"";'),
('editor', '1', '', 's:0:"";'),
('member', '1', '', 's:0:"";');



--
-- Daten für Tabelle `p2_cell`
--

INSERT INTO `p2_cell` (`id`, `classPath`, `classProps`, `rank`, `cellId`, `moduleId`, `controllerId`, `actionName`, `requestParam`, `sessionParam`, `cookieParam`, `applicationParam`, `moduleParam`, `p2_infoId`) VALUES
(1, 'p2.widgets.html.P2HtmlWidget', '{"id":"1"}', 200, 'mainCell', '', 'site', 'index', '', '', '', '', '', 12);

--
-- Daten für Tabelle `p2_html`
--

INSERT INTO `p2_html` (`id`, `name`, `html`, `p2_infoId`) VALUES
(1, 'welcome', '<p>\r\n	This is the homepage of <em>Website Name</em>. You may modify the following site by logging in as &#39;admin&#39;.</p>\r\n<p>\r\n	Thank you for choosing p2!</p>\r\n', 11);

--
-- Daten für Tabelle `p2_info`
--

INSERT INTO `p2_info` (`id`, `model`, `modelId`, `language`, `status`, `type`, `createdBy`, `createdAt`, `modifiedBy`, `modifiedAt`, `begin`, `end`, `keywords`, `customData`) VALUES
(3, 'P2Page', 1, NULL, 30, NULL, 1, '1970-01-01 00:00:00', 1, '1970-01-01 00:00:00', NULL, NULL, NULL, NULL),
(11, 'P2Html', 1, 'en_us', 30, '', 1, '2009-11-14 20:09:22', 1, '2010-01-17 22:46:04', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', ''),
(12, 'P2Cell', 1, 'en_us', 30, '', 1, '2009-11-14 20:09:27', 1, '2010-01-17 22:43:57', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '');

--
-- Daten für Tabelle `p2_page`
--

INSERT INTO `p2_page` (`id`, `name`, `descriptiveName`, `url`, `parentId`, `rank`, `view`, `layout`, `replaceMethod`, `p2_infoId`) VALUES
(1, 'root', NULL, NULL, 1, 100, NULL, NULL, NULL, 3);
