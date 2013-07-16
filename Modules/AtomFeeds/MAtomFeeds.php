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
			return array(	'Project Number','Project Value','District Manager','LSC','LSS',
							'LSR','Associate Creative Analyst','Creative Analyst','Creative Consultant','Institutional Sales Rep',
							
							'Primary Customer','Customer Phone','Customer Email','Lead Author','Title',
							'MHID','Ed','Imp','Net Price','Estimated UMC',
							
							'Actual UMC','School','Status','Course State Date','Due Date',
							'QOH','QOH Date','Product Type','Stat Sponsor Code','2012 YTD Sales Net Units',
							
							'2012 YTD Sales Net Revenue','2012 YTD Sales Gross Units','2012 YTD Sales Gross Revenue','2011 Sales Net Units','2011 Sales Net Revenue',
							'2011 Sales Gross Units','2011 Sales Gross Revenue', 'Request Plant','Plant Paid','Plant Left',
							
							'Vendor','Date Paid','ISBN-10','Custom ISBN','Spec Doc Link',
							'Connect Request ID link','Milestones','Milestone Completion Percentage');
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
				$ProjectInfo[2] 	= $Project->ProjectValue;
				$ProjectInfo[3] 	= implode (CSV_ITEM_DELIMITER,$Project->GetDistrictManagersCompleteNames());
				$ProjectInfo[4] 	= implode (CSV_ITEM_DELIMITER,$Project->GetLSCsCompleteNames());
				$ProjectInfo[5] 	= implode (CSV_ITEM_DELIMITER,$Project->GetLSSsCompleteNames());
				$ProjectInfo[6] 	= implode (CSV_ITEM_DELIMITER,$Project->GetLSRsCompleteNames());
				$ProjectInfo[7] 	= implode (CSV_ITEM_DELIMITER,$Project->GetJuniorCreativeAnalystsCompleteNames());
				$ProjectInfo[8] 	= implode (CSV_ITEM_DELIMITER,$Project->GetCreativeAnalystsCompleteNames());
				$ProjectInfo[9] 	= implode (CSV_ITEM_DELIMITER,$Project->GetCreativeConsultantsCompleteNames());
				$ProjectInfo[10] 	= implode (CSV_ITEM_DELIMITER,$Project->GetInstitutionalSalesRepsCompleteNames());
				
				$ProjectInfo[11] 	= $Project->PrimaryCustomer;
				$ProjectInfo[12] 	= $Project->CustomerPhone;
				$ProjectInfo[13] 	= $Project->CustomerEmail;				
				$ProjectInfo[14] 	= $Project->LeadAuthor;				
				$ProjectInfo[15] 	= $Project->Title;
				$ProjectInfo[16] 	= $Project->MHID;
				$ProjectInfo[17] 	= $Project->Ed;
				$ProjectInfo[18] 	= $Project->Imp;
				$ProjectInfo[19] 	= $Project->NetPrice;
				$ProjectInfo[20] 	= $Project->EstimatedUMC;
				
				$ProjectInfo[21] 	= $Project->ActualUMC;
				$ProjectInfo[22] 	= $Project->School;
				$ProjectInfo[23] 	= $Project->StatusName();
				$ProjectInfo[24] 	= date("m-d-Y",$Project->CourseStartDate);
				$ProjectInfo[25] 	= date("m-d-Y",$Project->DueDate);
				$ProjectInfo[26] 	= $Project->QOH;
				$ProjectInfo[27] 	= date("m-d-Y",$Project->QOHDate);
				$ProjectInfo[28] 	= $Project->GetProductTypesList();
				$ProjectInfo[29] 	= $Project->StatSponsorCode;
				$ProjectInfo[30] 	= $Project->{"2012YTDSalesNetUnits"};
				
				$ProjectInfo[31] 	= $Project->{"2012YTDSalesNetRevenue"};
				$ProjectInfo[32] 	= $Project->{"2012YTDSalesGrossUnits"};
				$ProjectInfo[33] 	= $Project->{"2012YTDSalesGrossRevenue"};
				$ProjectInfo[34] 	= $Project->{"2011SalesNetUnits"}; 
				$ProjectInfo[35] 	= $Project->{"2011SalesNetRevenue"}; 
				$ProjectInfo[36] 	= $Project->{"2011SalesGrossUnits"}; 
				$ProjectInfo[37] 	= $Project->{"2011SalesGrossRevenue"}; 
				$ProjectInfo[38] 	= $Project->RequestPlant; 
				$ProjectInfo[39] 	= $Project->PlantPaid; 
				$ProjectInfo[40] 	= $Project->PlantLeft; 
				
				$ProjectInfo[41] 	= $Project->VenderUsed; 
				$ProjectInfo[42] 	= date("m-d-Y",$Project->DatePaid); 
				$ProjectInfo[43] 	= $Project->ISBN10; 
				$ProjectInfo[44] 	= $Project->CustomISBN; 
				$ProjectInfo[45] 	= $Project->SpecDocLink;
				$ProjectInfo[46] 	= $Project->{"ConnectRequestIDLink"}; 
				$ProjectInfo[47] 	= $this->FormatMilestone($Project->Milestones);
				$ProjectInfo[48] 	= $Project->GetMilestonCompletionPercentage();				
				
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
