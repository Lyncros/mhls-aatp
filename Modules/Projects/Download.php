<?

	// Record Download Request
	$Data = Array(
		"ProjectsResourcesID"		=> intval($_GET["ResourcesID"]),
		"Timestamp"					=> time(),
		"UsersID"					=> CSecurity::GetUsersID(),
	);
	CTable::Add("ProjectsResourcesDownloads", $Data);
	
	$Resource = new CProjectsResources();
	$Resource->OnLoad(intval($_GET["ResourcesID"]));
	
	header("Content-Type: ".mime_content_type(CProjectsResources::HasFile($Resource->ID)));
	header('Content-Disposition: attachment; filename="'.urlencode(str_replace(" ", "_", $Resource->FilenameOriginal)).'"');
	
	echo file_get_contents(CProjectsResources::HasFile($Resource->ID));

?>