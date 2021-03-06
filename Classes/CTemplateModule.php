<?php

require_once './Libraries/Twig/Autoloader.php';

class CTemplateModule extends CModuleGeneric {

    const DATE_FORMAT = "n/j/Y";

    protected $JSFile = "";
    protected $Twig;

    function __construct($ViewsFolder) {
        parent::__construct();

        Twig_Autoloader::register();
        $Loader = new Twig_Loader_Filesystem($ViewsFolder);
        $Loader->addPath('./Views/Shared');
        $this->Twig = new Twig_Environment($Loader, array(
                //FIXME: 'cache' => './Libraries/Twig/cache',
        ));

        //load every static method from CForm, to enable reuse from within Twig templates
        $reflection = new ReflectionClass('CForm');
        foreach ($reflection->getMethods(ReflectionMethod::IS_STATIC) as $StaticMethod) {
            $this->Twig->addFunction(new Twig_SimpleFunction($StaticMethod->name, function() use ($StaticMethod) {
                        return forward_static_call_array("CForm::{$StaticMethod->name}", func_get_args());
                    }));
        }
		
        $this->Twig->addFilter(new Twig_SimpleFilter("FormatDate", function($timestamp) {
            //WHY are we using the first line (now commented) instead of the second? Becouse of the php version on the server
            //return date(CTemplateModule::DATE_FORMAT, $timestamp);
            return is_null($timestamp) ? "-" : date(CTemplateModule::DATE_FORMAT, $timestamp);//$this->FormatDate($timestamp);
        }));
    }

    /**
     * Renders a template, passing its parameters.
     * By default, the template name is the same as the page requested.
     * And the params will be built by a function called like the page requested plus string Params.
     */
    function OnRenderContent() {
        $Action = $this->GetActionName();
        $Params = $this->GetTemplateParams($Action);
        
        if($this->LoggedUserCanAccess($Params)) {
            $Template = $this->LoadTemplate($Action);
        } else {
            $Template = $this->Twig->loadTemplate("NoPermission.twig");
        }
        
        $Template->display($Params);
    }

    function LoadTemplate($Action) {
        $TemplateName = $this->GetTemplateName($Action);

        return $this->Twig->loadTemplate($TemplateName . ".twig");
    }

    function GetTemplateName($Page) {
        $TemplateName = $Page;
        
        $getTemplateName = $Page . "Template";
        if (method_exists($this, $getTemplateName)) {
            $TemplateName = $this->{$getTemplateName}();
        }
        
        return $TemplateName;
    }

    function GetTemplateParams($Page) {
        $Params = array();

        $getParams = $Page . "Params";
        if (method_exists($this, $getParams)) {
            $Params = $this->{$getParams}();
        }

        return $Params;
    }

    //----------------------------------------------------------------------
    function OnRenderJS() {
        if (!empty($this->JSFile)) {
            $this->FileControl->LoadFile($this->JSFile, CFILE_TYPE_JS);
        }
    }

    //----------------------------------------------------------------------
    function OnRenderCSS() {
        $this->FileControl->LoadFile("style.css", CFILE_TYPE_CSS);
    }

    //----------------------------------------------------------------------
    function OnAJAX($Action) {
        if (method_exists($this, $Action)) {
            return $this->$Action();
        }

        return parent::OnAJAX($Action);
    }

    //----------------------------------------------------------------------
    function FormatDate($timestamp) {
        return is_null($timestamp) ? "-" : date(self::DATE_FORMAT, $timestamp);
    }
    
    //----------------------------------------------------------------------
    function GetActionName() {
        $ActionName = $_GET["Page"];

        return (is_null($ActionName)) ? $this->IndexAction() : $ActionName;
    }
    
    function IndexAction() {
        return "Index";
    }
    
    private function LoggedUserCanAccess($Params) {   
        if(array_key_exists("Permissions", $Params)) {
            $Permissions = $Params["Permissions"];
            $CanAccess = true;
            foreach ($Permissions as $Permission) {
                $CanAccess = $CanAccess && parent::CanAccess($Permission);
            }
            
            return $CanAccess;
        } else {
            return true;
        }
    }

}

?>