<?php

/**
 * Description of SidebarMenu
 *
 * @author nbutarelli
 */
class CSidebarMenu {

    public $name;
    public $displayName;
    public $securityName;
    public $securityAction;
    public $submenus;

    public function __construct($name, $displayName = "", $secName = "", $secAction = "") {
        $this->name = $name;
        $this->displayName = empty($displayName) ? $name : $displayName;
        $this->securityName = $secName;
        $this->securityAction = $secAction;
        $this->submenus = array();
    }

    function addSubmenu($jsAction, $name, $displayName = "") {
        $displayName = empty($displayName) ? $name : $displayName;

        $submenu = new CSidebarSubmenu($name, $displayName, $jsAction);
        $this->submenus[] = $submenu;
    }

    function hasChildrens() {
        return count($this->submenus);
    }

    /**
     * 
     * @param CUsers $user
     */
    function canView($user) {
        if ($this->isSecured()) {
            return $user->CanAccess($this->securityName, $this->securityAction);
        } else {
            return true;
        }
    }

    function isSecured() {
        return !empty($this->securityName) && !empty($this->securityAction);
    }

    public static function BuildProjectsSideMenu() {
        $ProjectMenu = new CSidebarMenu("Projects");
        $ProjectMenu->addSubmenu("MProjects.MoveToDetails();", "ProjectDetails", "Project Details");
        $ProjectMenu->addSubmenu("MProjects.MoveToMilestones();", "Milestones");
        $ProjectMenu->addSubmenu("MProjects.MoveToMessages();", "MessageBoard", "Message Board");
        $ProjectMenu->addSubmenu("MProjects.MoveToNotifications();", "Notifications");

        return Array(
            "Projects" => Array(
                new CSidebarMenu("Add", "Add Project", "ProjectDetails", "Add"),
                $ProjectMenu,
            ),
            "MilestoneList" => Array(
                new CSidebarMenu("MilestonesImAssignedTo", "Milestones I'm assigned to", "MilestonsImAssignedTo", "View")),
            "VendorManagement" => Array(
                new CSidebarMenu("VendorManagement", "Vendor Management")
            )
        );
    }

}

/**
 * @author nbutarelli
 */
class CSidebarSubmenu {

    public $name;
    public $displayName;
    public $jsAction;

    public function __construct($name, $displayName, $jsAction) {
        $this->name = $name;
        $this->displayName = $displayName;
        $this->jsAction = $jsAction;
    }

}

?>
