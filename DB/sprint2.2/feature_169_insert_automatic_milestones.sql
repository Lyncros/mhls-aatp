INSERT INTO `Milestones`
(`Name`,`CustomerApproval`,`Summary`,`ExpectedDeliveryDate`,`ActualDeliveryDate`,`PlantAllocated`,
`Status`,`Active`,`CreatedUsersID`,`CreatedIPAddress`)
VALUES ('Cover Loaded', 0, 'Cover Loaded in MPD', 0, 0, 0, 'Active', 1, 1, 'localhost');

INSERT INTO `Milestones`
(`Name`,`CustomerApproval`,`Summary`,`ExpectedDeliveryDate`,`ActualDeliveryDate`,`PlantAllocated`,
`Status`,`Active`,`CreatedUsersID`,`CreatedIPAddress`)
VALUES ('Flags Flipped', 0, 'Flags Flipped in MPD', 0, 0, 0, 'Active', 1, 1, 'localhost');

INSERT INTO `Milestones`
(`Name`,`CustomerApproval`,`Summary`,`ExpectedDeliveryDate`,`ActualDeliveryDate`,`PlantAllocated`,
`Status`,`Active`,`CreatedUsersID`,`CreatedIPAddress`)
VALUES ('Email notification Shop', 0, 'Email notification sent to requestor Shop', 0, 0, 0, 'Active', 1, 1, 'localhost');

INSERT INTO `Milestones`
(`Name`,`CustomerApproval`,`Summary`,`ExpectedDeliveryDate`,`ActualDeliveryDate`,`PlantAllocated`,
`Status`,`Active`,`CreatedUsersID`,`CreatedIPAddress`)
VALUES ('Private Offer setup using the tool', 0, 'Private Offer setup using the tool', 0, 0, 0, 'Active', 1, 1, 'localhost');

INSERT INTO `Milestones`
(`Name`,`CustomerApproval`,`Summary`,`ExpectedDeliveryDate`,`ActualDeliveryDate`,`PlantAllocated`,
`Status`,`Active`,`CreatedUsersID`,`CreatedIPAddress`)
VALUES ('Screenshot of solution taken', 0, 'Screenshot of solution taken', 0, 0, 0, 'Active', 1, 1, 'localhost');

INSERT INTO `Milestones`
(`Name`,`CustomerApproval`,`Summary`,`ExpectedDeliveryDate`,`ActualDeliveryDate`,`PlantAllocated`,
`Status`,`Active`,`CreatedUsersID`,`CreatedIPAddress`)
VALUES ('Email notification Private', 0, 'Email notification sent to requestor Private', 0, 0, 0, 'Active', 1, 1, 'localhost');

INSERT INTO `EmailTemplates` (`Type`, `Name`, `SubName`, `Content`, `FromName`, `FromEmail`, 
`ReplyTo`, `Subject`) 
VALUES ('Module', 'ProjectsShopOnline', 'MilestoneCompleted', 
'<html><body><center><table width=\'640\'>
				<tr>
					<td style=\'padding:20px; padding-top:0px; text-align:center; background-color:black;\'>
						<img src=\'http://[[System Action="GetDomain"]]/Theme/Default/Default/Logo.png\' alt=\'The Almighty App For All Things Project\' style=\'margin-bottom:20px;\'>
						<table width=\'640\' style=\'background:#ffffff; border:1px solid #E1E1E1;\' cellspacing=\'0\'>
							<tr>
								<th colspan=\'2\' style=\'width:33%; line-height:29px; padding-left:16px; color:#212121; white-space:nowrap; vertical-align:top; text-align:left; background-image:url(\"http://[[System Action="GetDomain"]]/Theme/Default/Default/Email_TableHeaderBackground.png\");\'>Shop link</th>
							</tr>
							<tr>
								<td colspan=\'2\' style=\'text-align:left; line-height:29px; padding-left:16px; vertical-align:top; border-top:1px solid #E1E1E1;\'><a href="[[Data Name="ShopLink"]]">[[Data Name="ShopLink"]]</a></td>
							</tr>
							<tr>
								<th colspan=\'6\' style=\'line-height:29px; padding-left:16px; color:#212121; white-space:nowrap; vertical-align:top; text-align:left; border-top:1px solid #E1E1E1; background-image:url(\"http://[[System Action="GetDomain"]]/Theme/Default/Default/Email_TableHeaderBackground.png\");\'>Milestone</th>
							</tr>
							<tr>
								<td colspan=\'6\' style=\'text-align:left; line-height:29px; padding-left:16px; vertical-align:top; border-top:1px solid #E1E1E1;\'>Your MHSHOP Site has been setup!</td>
							</tr>
							<tr>
								<th colspan=\'6\' style=\'line-height:29px; padding-left:16px; color:#212121; white-space:nowrap; vertical-align:top; text-align:left; border-top:1px solid #E1E1E1; background-image:url(\"http://[[System Action="GetDomain"]]/Theme/Default/Default/Email_TableHeaderBackground.png\");\'>Summary</th>
							</tr>
							<tr>
								<td colspan=\'6\' style=\'text-align:left; padding:16px; vertical-align:top; border-top:1px solid #E1E1E1;\'><p>The link above is exactly what students need to purchase the product. The entire process is as simple as ordering from any online site. The biggest difference is that students need to cut/paste or re-type the URL exactly as it appears above.</p><p>A best practice for professors is to include the link in their syllabus, an email or post it directly into their LMS. Students will not be able search for the product at shopmcgrawhill.com since it\'s a custom product. Once students establish an account, they\'ll be able to purchase with a valid credit card.</p><p>On the site, at the bottom of every page, is button that says \'Need Help\'. Students can always click this link. Then, they can use the navigation on the side of the page or the button that says \'Ordering Info\' which includes a Phone Number and email address for Customer Service. I cut/pasted for convenience below. Also, when students get an email confirmation it includes much the same information. And, when they receive the PDF of their invoice the following day, the same information.</p>
<p>It\'s probably also helpful to know:<ul><li>The student can place the order at any time. It would be put on backorder until it becomes available and then it will ship out.</li><li>Students will see a credit card authorization immediately that will fall off in a couple of business days.</li><li>The actual charge will not go through until we ship the item out.</li></ul></p><p>Finally, for additional questions about the purchase process I would suggest they contact our eCommerce Customer Service after reviewing the available FAQs available. Clearly, the Q&A for \'How can I find the books I want quickly and easily?\' doesn\'t apply in this case, but the remaining FAQs may prove helpful, <a href=\'http://shop.mcgraw-hill.com/mhshop/support\'>shop.mcgraw-hill.com/mhshop/support</a>.</p><p>Customer Service may be reached between the hours of 8:00 AM to 4:30 PM EST, Monday through Friday at:<ul><li>Telephone: (877) 833-5524</li><li>Fax: (614) 759-3749</li><li>Email: <a href=\'mailto:customer.service@mheducation.com\' target=\'_top\'>customer.service@mheducation.com</a></li></ul></p></td>
							</tr>
						</table>
					</td>
				</tr>
</table></center></body></html>', 'McGraw Hill - AATP', 
'no-reply@[[System Action=\"GetShortDomain\"]]', 
'no-reply@[[System Action=\"GetShortDomain\"]]', 
'Milestone complete - Project [[Data Name=\"ISBN\"]]');

