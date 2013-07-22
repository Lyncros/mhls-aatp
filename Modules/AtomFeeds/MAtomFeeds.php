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
							/*'LSR',*/'Creative Contact',/*'Creative Analyst','Creative Consultant',*/'Institutional Sales Rep',
							
							'Primary Customer','Customer Phone','Customer Email','Lead Author','Title',
							/*'MHID',*/'Ed',/*'Imp',*/'Net Price',/*'Estimated UMC',*/
							
							/*'Actual UMC',*/'School','Status','Course State Date','Due Date',
							/*'QOH','QOH Date',*/'Milestone template','Stat Sponsor Code',/*'2012 YTD Sales Net Units',*/
							
							/*'2012 YTD Sales Net Revenue','2012 YTD Sales Gross Units','2012 YTD Sales Gross Revenue','2011 Sales Net Units','2011 Sales Net Revenue',*/
							/*'2011 Sales Gross Units','2011 Sales Gross Revenue', */'Request Plant','Plant Paid','Plant Left',
							
							'Vendor','Date Paid','ISBN-10',/*'Custom ISBN',*/'Spec Doc Link',
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
				$col = 1;
				
				$ProjectInfo 		= Array();
				$ProjectInfo[$col++] 	= $Project->ProductNumber;
				$ProjectInfo[$col++] 	= $Project->ProjectValue;
				$ProjectInfo[$col++] 	= implode (CSV_ITEM_DELIMITER,$Project->GetDistrictManagersCompleteNames());
				$ProjectInfo[$col++] 	= implode (CSV_ITEM_DELIMITER,$Project->GetLSCsCompleteNames());
				$ProjectInfo[$col++] 	= implode (CSV_ITEM_DELIMITER,$Project->GetLSSsCompleteNames());
				//$ProjectInfo[$col++] 	= implode (CSV_ITEM_DELIMITER,$Project->GetLSRsCompleteNames());
				$ProjectInfo[$col++] 	= implode (CSV_ITEM_DELIMITER,$Project->GetCreativeContactCompleteNames());
				//$ProjectInfo[$col++] 	= implode (CSV_ITEM_DELIMITER,$Project->GetCreativeAnalystsCompleteNames());
				//$ProjectInfo[$col++] 	= implode (CSV_ITEM_DELIMITER,$Project->GetCreativeConsultantsCompleteNames());
				$ProjectInfo[$col++] 	= implode (CSV_ITEM_DELIMITER,$Project->GetInstitutionalSalesRepsCompleteNames());
				//10
				
				$ProjectInfo[$col++] 	= $Project->PrimaryCustomer;
				$ProjectInfo[$col++] 	= $Project->CustomerPhone;
				$ProjectInfo[$col++] 	= $Project->CustomerEmail;				
				$ProjectInfo[$col++] 	= $Project->LeadAuthor;				
				$ProjectInfo[$col++] 	= $Project->Title;
				//$ProjectInfo[$col++] 	= $Project->MHID;
				$ProjectInfo[$col++] 	= $Project->Ed;
				//$ProjectInfo[$col++] 	= $Project->Imp;
				$ProjectInfo[$col++] 	= $Project->NetPrice;
				//$ProjectInfo[$col++] 	= $Project->EstimatedUMC;
				//20
				
				//$ProjectInfo[$col++] 	= $Project->ActualUMC;
				$ProjectInfo[$col++] 	= $Project->School;
				$ProjectInfo[$col++] 	= $Project->StatusName();
				$ProjectInfo[$col++] 	= date("m-d-Y",$Project->CourseStartDate);
				$ProjectInfo[$col++] 	= date("m-d-Y",$Project->DueDate);
				//$ProjectInfo[$col++] 	= $Project->QOH;
				//$ProjectInfo[$col++] 	= date("m-d-Y",$Project->QOHDate);
				$ProjectInfo[$col++] 	= $Project->GetProductTypesList();
				$ProjectInfo[$col++] 	= $Project->StatSponsorCode;
				//$ProjectInfo[$col++] 	= $Project->{"2012YTDSalesNetUnits"};
				//30
				
				//$ProjectInfo[$col++] 	= $Project->{"2012YTDSalesNetRevenue"};
				//$ProjectInfo[$col++] 	= $Project->{"2012YTDSalesGrossUnits"};
				//$ProjectInfo[$col++] 	= $Project->{"2012YTDSalesGrossRevenue"};
				//$ProjectInfo[$col++] 	= $Project->{"2011SalesNetUnits"}; 
				//$ProjectInfo[$col++] 	= $Project->{"2011SalesNetRevenue"}; 
				//$ProjectInfo[$col++] 	= $Project->{"2011SalesGrossUnits"}; 
				//$ProjectInfo[$col++] 	= $Project->{"2011SalesGrossRevenue"}; 
				$ProjectInfo[$col++] 	= $Project->RequestPlant; 
				$ProjectInfo[$col++] 	= $Project->PlantPaid; 
				$ProjectInfo[$col++] 	= $Project->PlantLeft; 
				//40
				
				$ProjectInfo[$col++] 	= $Project->VenderUsed; 
				$ProjectInfo[$col++] 	= date("m-d-Y",$Project->DatePaid); 
				$ProjectInfo[$col++] 	= $Project->ISBN10; 
				//$ProjectInfo[$col++] 	= $Project->CustomISBN; 
				$ProjectInfo[$col++] 	= $Project->SpecDocLink;
				$ProjectInfo[$col++] 	= $Project->{"ConnectRequestIDLink"}; 
				$ProjectInfo[$col++] 	= $this->FormatMilestone($Project->Milestones);
				$ProjectInfo[$col++] 	= $Project->GetMilestonCompletionPercentage();				
				//48
				
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
			
			$From = "";
			$Where = "";
			$GroupBy = " GROUP BY Projects.ID";
			
			$isFirst = true;
			if($ShowDeleted)
				$Where =  "";
			else {
				$Where = "Projects.Deleted = 0";
				$isFirst = false;
			}			
			if($FromCourseStartDate)
			{
				if(!$isFirst) $Where .= " AND ";
				$Where .= "Projects.CourseStartDate >= $FromCourseStartDate";
				$isFirst = false;
			}
			if($ToCourseStartDate)
			{			
				if(!$isFirst) $Where .= " AND ";
				$Where .= "Projects.CourseStartDate <= $ToCourseStartDate";
				$isFirst = false;
			}
			if($FromDueDate)
			{
				if(!$isFirst) $Where .= " AND ";
				$Where .= "Projects.DueDate >= $FromDueDate";
				$isFirst = false;
			}
			if($ToDueDate)
			{
				if(!$isFirst) $Where .= " AND ";
				$Where .= "Projects.DueDate <= $ToDueDate";
				$isFirst = false;
			}
			
			$LSCFirstNames 	= (isset($_GET["LSCFirstNames"])) 	? $_GET["LSCFirstNames"] 	: "";
			$LSCLastNames	= (isset($_GET["LSCLastNames"]))	? $_GET["LSCLastNames"] 	: "";			
			if (!empty($LSCFirstNames) || !empty($LSCLastNames))
			{
				$From .= " 	LEFT JOIN ProjectsLSCs 		ON Projects.ID 			= ProjectsLSCs.ProjectsID
							LEFT JOIN Users AS UsersLSC ON ProjectsLSCs.UsersID = UsersLSC.ID ";
				
				if(!$isFirst) $Where .= " AND ";
				
				$RegExpFirstNames 	= (!empty($LSCFirstNames))?str_replace(',','|',$LSCFirstNames):"a^";
				$RegExpLastNames 	= (!empty($LSCLastNames))?str_replace(',','|',$LSCLastNames):"a^";
				
				$Where .= " (UsersLSC.FirstName REGEXP '$RegExpFirstNames'";
				$Where .= " OR UsersLSC.LastName REGEXP '$RegExpLastNames')";
				
				$isFirst = false;
			}
			
			$LSSFirstNames	= (isset($_GET["LSSFirstNames"])) 	? $_GET["LSSFirstNames"] 	: "";
			$LSSLastNames	= (isset($_GET["LSSLastNames"])) 	? $_GET["LSSLastNames"] 	: "";			
			if (!empty($LSSFirstNames) || !empty($LSSLastNames))
			{
				$From .= " 	LEFT JOIN ProjectsLSSs 		ON Projects.ID 			= ProjectsLSSs.ProjectsID
							LEFT JOIN Users AS UsersLSS ON ProjectsLSSs.UsersID = UsersLSS.ID ";
				
				if(!$isFirst) $Where .= " AND ";
				
				$RegExpFirstNames 	= (!empty($LSSFirstNames))?str_replace(',','|',$LSSFirstNames):"a^";
				$RegExpLastNames 	= (!empty($LSSLastNames))?str_replace(',','|',$LSSLastNames):"a^";
				
				$Where .= " (UsersLSS.FirstName REGEXP '$RegExpFirstNames'";
				$Where .= " OR UsersLSS.LastName REGEXP '$RegExpLastNames')";				
				
				$isFirst = false;
			}
			
			$Status	= (isset($_GET["Status"])) ? $_GET["Status"] : "";			
			if (!empty($Status))
			{				
				$statusId = array_search($Status, CProjects::GetAllStatus());
				if ($statusId)
				{
					if(!$isFirst) $Where .= " AND ";
					$Where .= " Status LIKE '$statusId'";
				}
			}			
			
			return CTable::SelectFields("Projects.*","Projects",(($Where)?"$From WHERE $Where $GroupBy":"")." ORDER BY $OrderBy $OrderType");
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
