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

/* modules/custom/bfss_assessment/templates/custom/modal-assessment.html.twig */
class __TwigTemplate_8809478fd3b4fe051bfd3ca974c6d1b9fa56b00cec19ac5a72659a24d3171157 extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = [
        ];
        $this->sandbox = $this->env->getExtension('\Twig\Extension\SandboxExtension');
        $tags = ["if" => 12];
        $filters = ["escape" => 8, "date" => 14, "slice" => 22];
        $functions = [];

        try {
            $this->sandbox->checkSecurity(
                ['if'],
                ['escape', 'date', 'slice'],
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
        echo "<style>
.col{width: 24%;display: inline-block; margin: 10px; padding:10px; background-color:#c2c2c2; cursor: pointer;}
.container div.row div.col-half {display: inline-block;width: 49%;}
</style>
<div class=\"container\">
\t<div class=\"row tophead\">
\t\t<div class=\"col-half image\">
\t\t\t<img src='";
        // line 8
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["data"] ?? null), "field_image", [])), "html", null, true);
        echo "' alt='";
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["data"] ?? null), "title", [])), "html", null, true);
        echo "' />
\t\t</div>
\t\t<div class=\"col-half title-timing\">
\t\t\t<div>
\t\t\t\t";
        // line 12
        if ($this->getAttribute(($context["data"] ?? null), "latest_timing", [])) {
            // line 13
            echo "\t\t\t\t\t<span class=\"timing-date\">
\t\t\t\t\t";
            // line 14
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["data"] ?? null), "latest_timing", [])), "M d"), "html", null, true);
            echo "
\t\t\t\t\t</span>
\t\t\t\t";
        }
        // line 17
        echo "\t\t\t</div>
\t\t\t<div>
\t\t\t\t<h2>
\t\t\t\t\t";
        // line 20
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["data"] ?? null), "title", [])), "html", null, true);
        echo "
\t\t\t\t</h2>
\t\t\t\t<span class='loca'>";
        // line 22
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, twig_slice($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["data"] ?? null), "field_location", [])), 0, 27), "html", null, true);
        echo " </span>
\t\t\t</div>
\t\t</div>
\t</div>
\t<div class=\"row ticketing\">
\t\t<div class=\"share\">
\t\t<span>Share</span>
\t\t</div>
\t\t<div class=\"ticket\">
\t\t<span>TICKETING</span>
\t\t</div>
\t</div>
\t<div class=\"row details\">
\t\t<div class=\"col-half body\">
\t\t\t<div class='node-body'>
\t\t\t\t";
        // line 37
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["data"] ?? null), "body", [])), "html", null, true);
        echo "
\t\t\t</div>
\t\t</div>
\t\t<div class=\"col-half date-location\">
\t\t\t<div class=\"date-and-time\">
\t\t\t\t<p>DATE AND TIME</p>
\t\t\t\t";
        // line 43
        if ($this->getAttribute(($context["data"] ?? null), "latest_timing", [])) {
            // line 44
            echo "\t\t\t\t\t<span class=\"timing-date\">
\t\t\t\t\t\t";
            // line 45
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["data"] ?? null), "latest_timing", [])), "F, M d, Y h:i A"), "html", null, true);
            echo "
\t\t\t\t\t</span>
\t\t\t\t";
        }
        // line 48
        echo "\t\t\t\t<p>UNTIL</p>
\t\t\t\t<span>
\t\t\t\t";
        // line 50
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["data"] ?? null), "latest_duration", [])), "html", null, true);
        echo "
\t\t\t\t</span>

\t\t\t<div>
\t\t\t<div class=\"location\">
\t\t\t\t<p>LOCATION</p>
\t\t\t\t";
        // line 56
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["data"] ?? null), "field_location", [])), "html", null, true);
        echo "
\t\t\t<div>
\t\t\t<div class=\"ticketing\">
\t\t\t\t<p>TICKETING</p>
\t\t\t\t<a href=\"";
        // line 60
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["data"] ?? null), "url", [])), "html", null, true);
        echo "\" class=\"book-now\">Book Now</a>
\t\t\t<div>
\t\t</div>
\t</div>
</div>";
    }

    public function getTemplateName()
    {
        return "modules/custom/bfss_assessment/templates/custom/modal-assessment.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  152 => 60,  145 => 56,  136 => 50,  132 => 48,  126 => 45,  123 => 44,  121 => 43,  112 => 37,  94 => 22,  89 => 20,  84 => 17,  78 => 14,  75 => 13,  73 => 12,  64 => 8,  55 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("<style>
.col{width: 24%;display: inline-block; margin: 10px; padding:10px; background-color:#c2c2c2; cursor: pointer;}
.container div.row div.col-half {display: inline-block;width: 49%;}
</style>
<div class=\"container\">
\t<div class=\"row tophead\">
\t\t<div class=\"col-half image\">
\t\t\t<img src='{{ data.field_image }}' alt='{{ data.title }}' />
\t\t</div>
\t\t<div class=\"col-half title-timing\">
\t\t\t<div>
\t\t\t\t{% if data.latest_timing %}
\t\t\t\t\t<span class=\"timing-date\">
\t\t\t\t\t{{ data.latest_timing|date(\"M d\") }}
\t\t\t\t\t</span>
\t\t\t\t{% endif %}
\t\t\t</div>
\t\t\t<div>
\t\t\t\t<h2>
\t\t\t\t\t{{ data.title }}
\t\t\t\t</h2>
\t\t\t\t<span class='loca'>{{ data.field_location|slice(0,27) }} </span>
\t\t\t</div>
\t\t</div>
\t</div>
\t<div class=\"row ticketing\">
\t\t<div class=\"share\">
\t\t<span>Share</span>
\t\t</div>
\t\t<div class=\"ticket\">
\t\t<span>TICKETING</span>
\t\t</div>
\t</div>
\t<div class=\"row details\">
\t\t<div class=\"col-half body\">
\t\t\t<div class='node-body'>
\t\t\t\t{{ data.body }}
\t\t\t</div>
\t\t</div>
\t\t<div class=\"col-half date-location\">
\t\t\t<div class=\"date-and-time\">
\t\t\t\t<p>DATE AND TIME</p>
\t\t\t\t{% if data.latest_timing %}
\t\t\t\t\t<span class=\"timing-date\">
\t\t\t\t\t\t{{ data.latest_timing|date(\"F, M d, Y h:i A\") }}
\t\t\t\t\t</span>
\t\t\t\t{% endif %}
\t\t\t\t<p>UNTIL</p>
\t\t\t\t<span>
\t\t\t\t{{ data.latest_duration }}
\t\t\t\t</span>

\t\t\t<div>
\t\t\t<div class=\"location\">
\t\t\t\t<p>LOCATION</p>
\t\t\t\t{{ data.field_location }}
\t\t\t<div>
\t\t\t<div class=\"ticketing\">
\t\t\t\t<p>TICKETING</p>
\t\t\t\t<a href=\"{{ data.url }}\" class=\"book-now\">Book Now</a>
\t\t\t<div>
\t\t</div>
\t</div>
</div>", "modules/custom/bfss_assessment/templates/custom/modal-assessment.html.twig", "/var/www/bfss.mindimage.net/web/modules/custom/bfss_assessment/templates/custom/modal-assessment.html.twig");
    }
}
