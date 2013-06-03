<?
	//==========================================================================
	class MAtomFeeds extends CModule {
		function __construct() {
			parent::__construct();
		}

		//----------------------------------------------------------------------
		function OnAJAX($Action) {
			return Array(1, "");
		}

		//----------------------------------------------------------------------
		function OnExecute() {
		}

		//----------------------------------------------------------------------
		function OnRender() {
			// output headers so that the file is downloaded rather than displayed
			header('Content-Type: text/csv; charset=utf-8');
			header('Content-Disposition: attachment; filename=AATPProjects.csv');

			// create a file pointer connected to the output stream
			$Output = fopen('php://output', 'w');

			// output the column headings
			fputcsv($Output, $this->Header(), CSV_COLUMN_DELIMITER);
			
			// output all the projects info
			foreach ($this->ProjectsInfo() as $Project) {
				fputcsv($Output, $Project, CSV_COLUMN_DELIMITER);}
			
			// close the file
			fclose($Output); 
		}
		
		//==========================================================================
		private function Header()
		{
			return array('Project Number','LSC','LSS','LSR','Junior Creative Analyst','Creative Analyst','Creative Consultant','Primary Customer','Lead Author','Title','School','Status','Course State Date','Due Date','Product Type','Lead Notes','Spec Doc Link','Milestones','Milestone Completion Percentage');
		}
		
		//==========================================================================
		private function ProjectsInfo()
		{
			$ProjectListInfo = Array();
			$i = 1;
			
			foreach($this->GetProjects() as $Row) {
				$Project = new CProjects();
				$Project->OnLoad($Row->Current["ID"]);
				
				$ProjectInfo 		= Array();
				$ProjectInfo[1] 	= $Project->ProductNumber;
				$ProjectInfo[2] 	= implode (CSV_ITEM_DELIMITER,$Project->GetLSCsCompleteNames());
				$ProjectInfo[3] 	= implode (CSV_ITEM_DELIMITER,$Project->GetLSSsCompleteNames());
				$ProjectInfo[4] 	= implode (CSV_ITEM_DELIMITER,$Project->GetLSRsCompleteNames());
				$ProjectInfo[5] 	= implode (CSV_ITEM_DELIMITER,$Project->GetJuniorCreativeAnalystsCompleteNames());
				$ProjectInfo[6] 	= implode (CSV_ITEM_DELIMITER,$Project->GetCreativeAnalystsCompleteNames());
				$ProjectInfo[7] 	= implode (CSV_ITEM_DELIMITER,$Project->GetCreativeConsultantsCompleteNames());
				$ProjectInfo[8] 	= $Project->PrimaryCustomer;
				$ProjectInfo[9] 	= $Project->LeadAuthor;
				$ProjectInfo[10] 	= $Project->Title;
				$ProjectInfo[11] 	= $Project->School;
				$ProjectInfo[12] 	= $Project->StatusName();
				$ProjectInfo[13] 	= date("m-d-Y",$Project->CourseStartDate);
				$ProjectInfo[14] 	= date("m-d-Y",$Project->DueDate);
				$ProjectInfo[15] 	= $Project->GetProductTypesList();
				$ProjectInfo[16] 	= $Project->LeadNotes;
				$ProjectInfo[17] 	= "";//TODO: Add here the document when the column exist
				$ProjectInfo[18] 	= $this->FormatMilestone($Project->Milestones);
				$ProjectInfo[19] 	= $Project->GetMilestonCompletionPercentage();
				
				$ProjectListInfo[$i] = $ProjectInfo;
				$i++;
			}
			
			return $ProjectListInfo;
		}
		
		private function FormatMilestone($Milestones)
		{
			$MilestoneList = "";
			$Separator = "";
			foreach($Milestones as $Milestone) {
				$MilestoneList .= $Separator . $Milestone->Name . " - " . $Milestone->Status;
				$Separator = ", ";
			}
			
			return $MilestoneList;
		}
		
		private function GetProjects()
		{
			$OrderBy 			 = (isset($_GET["OrderBy"]) 	&& $this->IsValidOrderBy($_GET["OrderBy"])) 	? $_GET["OrderBy"] 		: "ProductNumber";
			$OrderType 			 = (isset($_GET["OrderType"]) 	&& $this->IsValidOrderType($_GET["OrderType"])) ? $_GET["OrderType"] 	: "ASC";
			$ShowDeleted 		 = (isset($_GET["ShowDeleted"]) && $this->IsTrue($_GET["ShowDeleted"])) 		? true 					: false;
			
			$FromCourseStartDate = (isset($_GET["FromCourseStartDate"]) && strtotime($_GET["FromCourseStartDate"])) ? strtotime($_GET["FromCourseStartDate"])	: "";
			$ToCourseStartDate 	 = (isset($_GET["ToCourseStartDate"]) 	&& strtotime($_GET["ToCourseStartDate"]))	? strtotime($_GET["ToCourseStartDate"]) 	: "";
			$FromDueDate 		 = (isset($_GET["FromDueDate"]) 		&& strtotime($_GET["FromDueDate"]))			? strtotime($_GET["FromDueDate"]) 			: "";
			$ToDueDate 			 = (isset($_GET["ToDueDate"]) 			&& strtotime($_GET["ToDueDate"]))			? strtotime($_GET["ToDueDate"]) 			: "";
			
			$Where = "";
			$isFirst = true;
			if($ShowDeleted)
				$Where =  "";
			else {
				$Where = "Projects.Deleted = 0";
				$isFirst = false;
			}			
			if($FromCourseStartDate)
			{
				if(!$isFirst) $Where .= " && ";
				$Where .= "Projects.CourseStartDate >= $FromCourseStartDate";
				$isFirst = false;
			}
			if($ToCourseStartDate)
			{			
				if(!$isFirst) $Where .= " && ";
				$Where .= "Projects.CourseStartDate <= $ToCourseStartDate";
				$isFirst = false;
			}
			if($FromDueDate)
			{
				if(!$isFirst) $Where .= " && ";
				$Where .= "Projects.DueDate >= $FromDueDate";
				$isFirst = false;
			}
			if($ToDueDate)
			{
				if(!$isFirst) $Where .= " && ";
				$Where .= "Projects.DueDate <= $ToDueDate";
				$isFirst = false;
			}
			
			return CTable::Select("Projects",(($Where)?"WHERE $Where ":"")."ORDER BY $OrderBy $OrderType");
		}
		
		private function IsValidOrderBy($ColumnName)
		{
			return in_array($ColumnName,array('ProductNumber','PrimaryCustomer','LeadAuthor','Title','School','CourseStartDate','DueDate','LeadNotes'));
		}
		
		private function IsValidOrderType($ColumnName)
		{
			return in_array($ColumnName,array('ASC','DESC'));
		}
		
		private function IsTrue($Value)
		{
			return $Value == "true" || $Value == "True" || $Value == "1" ;
		}
	};	
?>
