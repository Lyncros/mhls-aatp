INSERT INTO `aatp`.`Milestones`
(`Name`,`CustomerApproval`,`Summary`,`ExpectedDeliveryDate`,`ActualDeliveryDate`,`PlantAllocated`,
`Status`,`Active`,`CreatedUsersID`,`CreatedIPAddress`)
VALUES ('Cover Loaded', 0, 'Cover Loaded in MPD', 0, 0, 0, 'Active', 1, 1, 'localhost');

INSERT INTO `aatp`.`Milestones`
(`Name`,`CustomerApproval`,`Summary`,`ExpectedDeliveryDate`,`ActualDeliveryDate`,`PlantAllocated`,
`Status`,`Active`,`CreatedUsersID`,`CreatedIPAddress`)
VALUES ('Flags Flipped', 0, 'Flags Flipped in MPD', 0, 0, 0, 'Active', 1, 1, 'localhost');

INSERT INTO `aatp`.`Milestones`
(`Name`,`CustomerApproval`,`Summary`,`ExpectedDeliveryDate`,`ActualDeliveryDate`,`PlantAllocated`,
`Status`,`Active`,`CreatedUsersID`,`CreatedIPAddress`)
VALUES ('Email notification Shop', 0, 'Email notification sent to requestor Shop', 0, 0, 0, 'Active', 1, 1, 'localhost');

INSERT INTO `aatp`.`Milestones`
(`Name`,`CustomerApproval`,`Summary`,`ExpectedDeliveryDate`,`ActualDeliveryDate`,`PlantAllocated`,
`Status`,`Active`,`CreatedUsersID`,`CreatedIPAddress`)
VALUES ('Private Offer setup using the tool', 0, 'Private Offer setup using the tool', 0, 0, 0, 'Active', 1, 1, 'localhost');

INSERT INTO `aatp`.`Milestones`
(`Name`,`CustomerApproval`,`Summary`,`ExpectedDeliveryDate`,`ActualDeliveryDate`,`PlantAllocated`,
`Status`,`Active`,`CreatedUsersID`,`CreatedIPAddress`)
VALUES ('Screenshot of solution taken', 0, 'Screenshot of solution taken', 0, 0, 0, 'Active', 1, 1, 'localhost');

INSERT INTO `aatp`.`Milestones`
(`Name`,`CustomerApproval`,`Summary`,`ExpectedDeliveryDate`,`ActualDeliveryDate`,`PlantAllocated`,
`Status`,`Active`,`CreatedUsersID`,`CreatedIPAddress`)
VALUES ('Email notification Private', 0, 'Email notification sent to requestor Private', 0, 0, 0, 'Active', 1, 1, 'localhost');

INSERT INTO `aatp`.`EmailTemplates` (`Type`, `Name`, `SubName`, `Content`, `FromName`, `FromEmail`, `ReplyTo`, `Subject`) VALUES ('Module', 'ProjectsShopOnline', 'MilestoneCompleted', '<html>\n	<body>\n		<center>\n			<table width=\'640\'>\n				<tr>\n					<td style=\"padding:20px; padding-top:0px; text-align:center; background-color:black;\">\n						<img src=\'http://[[System Action=\"GetDomain\"]]/Theme/Default/Default/Logo.png\' alt=\'The Almighty App For All Things Project\' style=\'margin-bottom:20px;\'>\n						<table width=\'640\' style=\'background:#ffffff; border:1px solid #E1E1E1;\' cellspacing=\'0\'>\n							<tr>\n								<th colspan=\'2\' style=\"width:33%; line-height:29px; padding-left:16px; color:#212121; white-space:nowrap; vertical-align:top; text-align:left; background-image:url(\'http://[[System Action=\"GetDomain\"]]/Theme/Default/Default/Email_TableHeaderBackground.png\');\">ISBN</th>\n							</tr>\n							<tr>\n								<td colspan=\'2\' style=\'text-align:left; line-height:29px; padding-left:16px; vertical-align:top; border-top:1px solid #E1E1E1;\'>[[Data Name=\"ISBN\"]]</td>\n							</tr>\n							<tr>\n								<th colspan=\'2\' style=\"width:33%; line-height:29px; padding-left:16px; color:#212121; white-space:nowrap; vertical-align:top; text-align:left; background-image:url(\'http://[[System Action=\"GetDomain\"]]/Theme/Default/Default/Email_TableHeaderBackground.png\');\">Shop link</th>\n							</tr>\n							<tr>\n								<td colspan=\'2\' style=\'text-align:left; line-height:29px; padding-left:16px; vertical-align:top; border-top:1px solid #E1E1E1;\'>[[Data Name=\"ShopLink\"]]</td>\n							</tr>\n							<tr>\n								<th colspan=\'6\' style=\"line-height:29px; padding-left:16px; color:#212121; white-space:nowrap; vertical-align:top; text-align:left; border-top:1px solid #E1E1E1; background-image:url(\'http://[[System Action=\"GetDomain\"]]/Theme/Default/Default/Email_TableHeaderBackground.png\');\">Milestone</th>\n							</tr>\n							<tr>\n								<td colspan=\'6\' style=\'text-align:left; line-height:29px; padding-left:16px; vertical-align:top; border-top:1px solid #E1E1E1;\'>[[Data Name=\"Milestone\"]]</td>\n							</tr>\n							<tr>\n								<th colspan=\'6\' style=\"line-height:29px; padding-left:16px; color:#212121; white-space:nowrap; vertical-align:top; text-align:left; border-top:1px solid #E1E1E1; background-image:url(\'http://[[System Action=\"GetDomain\"]]/Theme/Default/Default/Email_TableHeaderBackground.png\');\">Summary</th>\n							</tr>\n							<tr>\n								<td colspan=\'6\' style=\'text-align:left; padding:16px; vertical-align:top; border-top:1px solid #E1E1E1;\'>[[Data Name=\"Summary\" Format=\"nl2br\"]]</td>\n							</tr>\n						</table>\n					</td>\n				</tr>\n			</table>\n		</center>\n	</body>\n</html>', 'McGraw Hill - AATP', 'no-reply@[[System Action=\"GetShortDomain\"]]', 'no-reply@[[System Action=\"GetShortDomain\"]]', 'Milestone complete - Project [[Data Name=\"ISBN\"]]');

