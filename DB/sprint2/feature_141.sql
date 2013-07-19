ALTER TABLE `aatp`.`UsersGroups` 
ADD COLUMN `AssignableToTODO` TINYINT NULL DEFAULT 0  AFTER `SuperAdmin` ;

UPDATE UsersGroups SET AssignableToTODO = 1 WHERE name IN ('Junior Creative Analyst', 'Creative Analyst', 'Creative Consultant', 'SuperAdmin');

ALTER TABLE `aatp`.`ProjectsMilestonesToDos` 
ADD COLUMN `AssignedTo` INT(10) NULL DEFAULT NULL  AFTER `DeletedIPAddress` ;

INSERT INTO `aatp`.`EmailTemplates` (`Type`, `Name`, `SubName`, `Content`, `FromName`, `FromEmail`, `ReplyTo`, `Subject`) VALUES ('Module', 'Projects', 'You have been assigned to a Milestone To-Do', '<html>\r\n	<body>\r\n		<center>\r\n			<table width=\'640\'>\r\n				<tr>\r\n					<td style=\"padding:20px; padding-top:0px; text-align:center; background-color:black;\">\r\n						<img src=\'http://[[System Action=\"GetDomain\"]]/Theme/Default/Default/Logo.png\' alt=\'The Almighty App For All Things Project\' style=\'margin-bottom:20px;\'>\r\n						<table width=\'640\' style=\'background:#ffffff; border:1px solid #E1E1E1;\' cellspacing=\'0\'>\r\n							<tr>\r\n								<th colspan=\'2\' style=\"width:33%; line-height:29px; padding-left:16px; color:#212121; white-space:nowrap; vertical-align:top; text-align:left; background-image:url(\'http://[[System Action=\"GetDomain\"]]/Theme/Default/Default/Email_TableHeaderBackground.png\');\">Project #</th>\r\n								<th colspan=\'2\' style=\"width:33%; line-height:29px; padding-left:16px; color:#212121; white-space:nowrap; vertical-align:top; text-align:left; background-image:url(\'http://[[System Action=\"GetDomain\"]]/Theme/Default/Default/Email_TableHeaderBackground.png\');\">Date/Time</th>\r\n								<th colspan=\'2\' style=\"width:33%; line-height:29px; padding-left:16px; color:#212121; white-space:nowrap; vertical-align:top; text-align:left; background-image:url(\'http://[[System Action=\"GetDomain\"]]/Theme/Default/Default/Email_TableHeaderBackground.png\');\">User</th>\r\n							</tr>\r\n							<tr>\r\n								<td colspan=\'2\' style=\'text-align:left; line-height:29px; padding-left:16px; vertical-align:top; border-top:1px solid #E1E1E1;\'>[[Data Name=\"ProjectNumber\"]]</td>\r\n								<td colspan=\'2\' style=\'text-align:left; line-height:29px; padding-left:16px; vertical-align:top; border-top:1px solid #E1E1E1;\'>[[Data Name=\"DateTime\"]]</td>\r\n								<td colspan=\'2\' style=\'text-align:left; line-height:29px; padding-left:16px; vertical-align:top; border-top:1px solid #E1E1E1;\'>[[Data Name=\"User\"]]</td>\r\n							</tr>\r\n							<tr>\r\n								<th colspan=\'6\' style=\"line-height:29px; padding-left:16px; color:#212121; white-space:nowrap; vertical-align:top; text-align:left; border-top:1px solid #E1E1E1; background-image:url(\'http://[[System Action=\"GetDomain\"]]/Theme/Default/Default/Email_TableHeaderBackground.png\');\">Name</th>\r\n							</tr>\r\n							<tr>\r\n								<td colspan=\'6\' style=\'text-align:left; line-height:29px; padding-left:16px; vertical-align:top; border-top:1px solid #E1E1E1;\'>[[Data Name=\"Name\"]]</td>\r\n							</tr>\r\n							<tr>\r\n								<th colspan=\'6\' style=\"line-height:29px; padding-left:16px; color:#212121; white-space:nowrap; vertical-align:top; text-align:left; border-top:1px solid #E1E1E1; background-image:url(\'http://[[System Action=\"GetDomain\"]]/Theme/Default/Default/Email_TableHeaderBackground.png\');\">Comment</th>\r\n							</tr>\r\n							<tr>\r\n								<td colspan=\'6\' style=\'text-align:left; padding:16px; vertical-align:top; border-top:1px solid #E1E1E1;\'>[[Data Name=\"Comment\" Format=\"nl2br\"]]</td>\r\n							</tr>\r\n							<tr>\r\n								<th colspan=\'6\' style=\"line-height:29px; padding-left:16px; color:#212121; white-space:nowrap; vertical-align:top; text-align:left; border-top:1px solid #E1E1E1; background-image:url(\'http://[[System Action=\"GetDomain\"]]/Theme/Default/Default/Email_TableHeaderBackground.png\');\">Complete</th>\r\n							</tr>\r\n							<tr>\r\n								<td colspan=\'6\' style=\'text-align:left; line-height:29px; padding-left:16px; vertical-align:top; border-top:1px solid #E1E1E1;\'>[[Data Name=\"Complete\"]]</td>\r\n							</tr>\r\n						</table>\r\n					</td>\r\n				</tr>\r\n			</table>\r\n		</center>\r\n	</body>\r\n</html>', 'McGraw Hill - AATP', 'no-reply@[[System Action=\"GetShortDomain\"]]', 'no-reply@[[System Action=\"GetShortDomain\"]]', 'Assigned Milestone To-Do - Project [[Data Name=\"ProjectNumber\"]]');



