<?php

require_once './Libraries/Twig/Autoloader.php';

class CTemplateModule extends CModuleGeneric {

    const DATE_FORMAT = "n/j/Y";

    public $JSFile = "";
    private $Twig;

    function __construct($ViewsFolder) {
        parent::__construct();

        Twig_Autoloader::register();
        $Loader = new Twig_Loader_Filesystem($ViewsFolder);
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
    }

    /*
     * Renders a template, passing its parameters.
     * By default, the template name is the same as the page requested.
     * And the params will be built by a function called like the page requested plus string Params.
     */

    function OnRenderContent() {
        $page = $_GET["Page"];

        $templateName = $this->GetTemplateName($page);
        $params = $this->GetTemplateParams($page);

        $template = $this->Twig->loadTemplate($templateName . ".twig");
        $template->display($params);
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
        if (parent::CanAccess($Action) == false) {
            return Array(0, "You do not have permission to perform this action");
        }

        return parent::OnAJAX($Action);
    }

    function FormatDate($timestamp) {
        return date(self::DATE_FORMAT, $timestamp);
    }

}

?>