<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* modules/custom/bfss_assessment/templates/custom/assessment-success.html.twig */
class __TwigTemplate_7091c9591eaca9982c49f73102c924410e67049c792015ef83539b6fe1974d8c extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = [
        ];
        $this->sandbox = $this->env->getExtension('\Twig\Extension\SandboxExtension');
        $tags = [];
        $filters = ["escape" => 35];
        $functions = [];

        try {
            $this->sandbox->checkSecurity(
                [],
                ['escape'],
                []
            );
        } catch (SecurityError $e) {
            $e->setSourceContext($this->getSourceContext());

            if ($e instanceof SecurityNotAllowedTagError && isset($tags[$e->getTagName()])) {
                $e->setTemplateLine($tags[$e->getTagName()]);
            } elseif ($e instanceof SecurityNotAllowedFilterError && isset($filters[$e->getFilterName()])) {
                $e->setTemplateLine($filters[$e->getFilterName()]);
            } elseif ($e instanceof SecurityNotAllowedFunctionError && isset($functions[$e->getFunctionName()])) {
                $e->setTemplateLine($functions[$e->getFunctionName()]);
            }

            throw $e;
        }

    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        // line 1
        echo "<div class=\"dash-main-right\">
  <h1><i class=\"fas fa-home\"></i> &gt; Dashboard > Your Scheduled Assessment</h1>
  <div class=\"dash-sub-main\">
     <img src=\"/themes/custom/bfss_custom/images/images-dashboard/calender-circle.png\">  
     <h2><span>YOUR</span><br>Scheduled Assessment  </h2>
  </div>
</div>
<div class=\"container\">
  <div class=\"row final\">
    <div class=\"title\">
      <h2> You're Scheduled!
    </div>
  \t<div class=\"main-head\">
      <div class=\"first\">
        <h6>1. Assessment Type</h6>
        <div class=\"bar\"></div>
      </div>
      <div class=\"second\">
        <h6>2. Time</h6>
        <div class=\"bar\"></div>
      </div>
      <div class=\"third\">
        <h6>3. Details</h6>
        <div class=\"bar\"></div>
      </div>
      <div class=\"fourth\">
        <h6>4. Payment</h6>
        <div class=\"bar\"></div>
      </div>
      <div class=\"fifth\">
        <h6>5. Done</h6>
        <div class=\"bar\"></div>
      </div>
    </div>
  <p> ";
        // line 35
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["message"] ?? null)), "html", null, true);
        echo " </p>
  </div>
</div>
";
    }

    public function getTemplateName()
    {
        return "modules/custom/bfss_assessment/templates/custom/assessment-success.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  91 => 35,  55 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("<div class=\"dash-main-right\">
  <h1><i class=\"fas fa-home\"></i> &gt; Dashboard > Your Scheduled Assessment</h1>
  <div class=\"dash-sub-main\">
     <img src=\"/themes/custom/bfss_custom/images/images-dashboard/calender-circle.png\">  
     <h2><span>YOUR</span><br>Scheduled Assessment  </h2>
  </div>
</div>
<div class=\"container\">
  <div class=\"row final\">
    <div class=\"title\">
      <h2> You're Scheduled!
    </div>
  \t<div class=\"main-head\">
      <div class=\"first\">
        <h6>1. Assessment Type</h6>
        <div class=\"bar\"></div>
      </div>
      <div class=\"second\">
        <h6>2. Time</h6>
        <div class=\"bar\"></div>
      </div>
      <div class=\"third\">
        <h6>3. Details</h6>
        <div class=\"bar\"></div>
      </div>
      <div class=\"fourth\">
        <h6>4. Payment</h6>
        <div class=\"bar\"></div>
      </div>
      <div class=\"fifth\">
        <h6>5. Done</h6>
        <div class=\"bar\"></div>
      </div>
    </div>
  <p> {{ message }} </p>
  </div>
</div>
", "modules/custom/bfss_assessment/templates/custom/assessment-success.html.twig", "/var/www/bfss.mindimage.net/web/modules/custom/bfss_assessment/templates/custom/assessment-success.html.twig");
    }
}
