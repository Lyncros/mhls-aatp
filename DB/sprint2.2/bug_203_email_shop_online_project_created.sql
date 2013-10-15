
INSERT INTO `EmailTemplates` (`Type`, `Name`, `SubName`, `FromName`, `FromEmail`, `ReplyTo`, `Subject`, `Content`) 
VALUES ('Module', 'ProjectsShopOnline', 'Project created', 'McGraw Hill - AATP', 'no-reply@[[System Action="GetShortDomain"]]', 'no-reply@[[System Action="GetShortDomain"]]', 'A new Project Shop Online was created- ISBN-10 [[Data Name="ISBN10"]]',
'<html>
	<body>
		<center>
			<table width="640">
				<tr>
					<td style="padding:20px; padding-top:0px; text-align:center; background-color:black;">
						<img src="http://[[System Action="GetDomain"]]/Theme/Default/Default/Logo.png" alt="The Almighty App For All Things Project" style="margin-bottom:20px;">
						<table width="640" style="background:#ffffff; border:1px solid #E1E1E1;" cellspacing="0">
							<tr>
								<th colspan="2" style="width:33%; line-height:29px; padding-left:16px; color:#212121; white-space:nowrap; vertical-align:top; text-align:left; background-image:url(\'http://[[System Action="GetDomain"]]/Theme/Default/Default/Email_TableHeaderBackground.png\');">ISBN-10</th>
							</tr>
							<tr>
								<td colspan="2" style="text-align:left; line-height:29px; padding-left:16px; vertical-align:top; border-top:1px solid #E1E1E1;">[[Data Name="ISBN10"]]</td>
							</tr>
							<tr>
								<th colspan="2" style="width:33%; line-height:29px; padding-left:16px; color:#212121; white-space:nowrap; vertical-align:top; text-align:left; border-top:1px solid #E1E1E1; background-image:url(\'http://[[System Action="GetDomain"]]/Theme/Default/Default/Email_TableHeaderBackground.png\');">Date needed</th>
							</tr>
							<tr>
								<td colspan="2" style="text-align:left; line-height:29px; padding-left:16px; vertical-align:top; border-top:1px solid #E1E1E1;">[[Data Name="DateNeeded"]]</td>
							</tr>
							<tr>
								<th colspan="6" style="line-height:29px; padding-left:16px; color:#212121; white-space:nowrap; vertical-align:top; text-align:left; border-top:1px solid #E1E1E1; background-image:url(\'http://[[System Action="GetDomain"]]/Theme/Default/Default/Email_TableHeaderBackground.png\');">Type of ISBN</th>
							</tr>
							<tr>
								<td colspan="6" style="text-align:left; line-height:29px; padding-left:16px; vertical-align:top; border-top:1px solid #E1E1E1;">[[Data Name="ISBNType"]]</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</center>
	</body>
</html>');




INSERT INTO `EmailTemplates` (`Type`, `Name`, `SubName`, `FromName`, `FromEmail`, `ReplyTo`, `Subject`, `Content`) 
VALUES ('Module', 'ProjectsShopOnline', 'Project created requester', 'McGraw Hill - AATP', 'no-reply@[[System Action="GetShortDomain"]]', 'no-reply@[[System Action="GetShortDomain"]]', 'You have created a new Project Shop Online - ISBN-10 [[Data Name="ISBN10"]]',
'<html>
	<body>
		<center>
			<table width="640">
				<tr>
					<td style="padding:20px; padding-top:0px; text-align:center; background-color:black;">
						<img src="http://[[System Action="GetDomain"]]/Theme/Default/Default/Logo.png" alt="The Almighty App For All Things Project" style="margin-bottom:20px;">
						<table width="640" style="background:#ffffff; border:1px solid #E1E1E1;" cellspacing="0">
							<tr>
								<th colspan="2" style="width:33%; line-height:29px; padding-left:16px; color:#212121; white-space:nowrap; vertical-align:top; text-align:left; background-image:url(\'http://[[System Action="GetDomain"]]/Theme/Default/Default/Email_TableHeaderBackground.png\');">ISBN-10</th>
							</tr>
							<tr>
								<td colspan="2" style="text-align:left; line-height:29px; padding-left:16px; vertical-align:top; border-top:1px solid #E1E1E1;">[[Data Name="ISBN10"]]</td>
							</tr>
							<tr>
								<th colspan="2" style="width:33%; line-height:29px; padding-left:16px; color:#212121; white-space:nowrap; vertical-align:top; text-align:left; border-top:1px solid #E1E1E1; background-image:url(\'http://[[System Action="GetDomain"]]/Theme/Default/Default/Email_TableHeaderBackground.png\');">Date needed</th>
							</tr>
							<tr>
								<td colspan="2" style="text-align:left; line-height:29px; padding-left:16px; vertical-align:top; border-top:1px solid #E1E1E1;">[[Data Name="DateNeeded"]]</td>
							</tr>
							<tr>
								<th colspan="6" style="line-height:29px; padding-left:16px; color:#212121; white-space:nowrap; vertical-align:top; text-align:left; border-top:1px solid #E1E1E1; background-image:url(\'http://[[System Action="GetDomain"]]/Theme/Default/Default/Email_TableHeaderBackground.png\');">Type of ISBN</th>
							</tr>
							<tr>
								<td colspan="6" style="text-align:left; line-height:29px; padding-left:16px; vertical-align:top; border-top:1px solid #E1E1E1;">[[Data Name="ISBNType"]]</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</center>
	</body>
</html>');




