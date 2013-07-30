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
