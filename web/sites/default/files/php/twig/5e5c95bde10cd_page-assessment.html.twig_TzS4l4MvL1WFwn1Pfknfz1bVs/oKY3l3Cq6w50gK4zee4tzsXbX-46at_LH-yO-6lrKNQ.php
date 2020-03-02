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

/* modules/custom/bfss_assessment/templates/custom/page-assessment.html.twig */
class __TwigTemplate_e4b656d43568866f7c99d8cf50b24653da76a63437ff80ed06253a72161d542e extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = [
        ];
        $this->sandbox = $this->env->getExtension('\Twig\Extension\SandboxExtension');
        $tags = ["for" => 6, "if" => 14];
        $filters = ["escape" => 7, "date" => 15, "slice" => 45, "striptags" => 45];
        $functions = [];

        try {
            $this->sandbox->checkSecurity(
                ['for', 'if'],
                ['escape', 'date', 'slice', 'striptags'],
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
</style>
<div class=\"container\">
  <div class=\"row assessment_block\">
  \t";
        // line 6
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["data"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["row"]) {
            // line 7
            echo "\t    <div class=\"col use-ajax assessment_inner\" data-dialog-type=\"modal\" data-dialog-options=\"{&quot;width&quot;:800}\" href=\"";
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute($context["row"], "url", [])), "html", null, true);
            echo "\">
\t    \t<div class=\"top-head\">
\t\t\t\t<div class=\"timing\">
\t\t\t\t\t<h2>
\t\t\t\t\t\t";
            // line 11
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute($context["row"], "title", [])), "html", null, true);
            echo "
\t\t\t\t\t</h2>
\t\t\t\t\t<span>
\t\t\t\t\t\t";
            // line 14
            if ($this->getAttribute($context["row"], "latest_timing", [])) {
                // line 15
                echo "\t\t\t\t\t\t\t";
                echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute($context["row"], "latest_timing", [])), "h:i a"), "html", null, true);
                echo "
\t\t\t\t\t\t";
            }
            // line 16
            echo "\t
\t\t\t\t\t</span>
\t\t\t\t</div>

\t\t\t\t\t<div class=\"timing-date\">

\t\t\t\t\t<span>
\t\t\t\t\t\t";
            // line 23
            if ($this->getAttribute($context["row"], "latest_timing", [])) {
                // line 24
                echo "\t\t\t\t\t\t\t<span class=\"m\">
\t\t\t\t\t\t\t";
                // line 25
                echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute($context["row"], "latest_timing", [])), "M"), "html", null, true);
                echo "
\t\t\t\t\t\t\t</span>
\t\t\t\t\t\t\t<span class=\"d\">
\t\t\t\t\t\t\t";
                // line 28
                echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute($context["row"], "latest_timing", [])), "d"), "html", null, true);
                echo "
\t\t\t\t\t\t\t</span>
\t\t\t\t\t\t\t<span class=\"y\">
\t\t\t\t\t\t\t";
                // line 31
                echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, twig_date_format_filter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute($context["row"], "latest_timing", [])), "Y"), "html", null, true);
                echo "
\t\t\t\t\t\t\t</span>
\t\t\t\t\t\t";
            }
            // line 34
            echo "\t\t\t\t\t</span>
\t\t\t\t</div>

\t\t\t</div>
\t\t\t<div class='image'>
\t\t\t\t<img src='";
            // line 39
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute($context["row"], "field_image", [])), "html", null, true);
            echo "' alt='";
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute($context["row"], "title", [])), "html", null, true);
            echo "' />
\t\t\t</div>
\t\t\t<div class='categories-tags'>
\t\t\t\t<ul><li>TAGS</li><li>CAT HERE</li></ul>
\t\t\t</div>
\t\t\t<div class='node-body'>
\t\t\t\t";
            // line 45
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, twig_slice($this->env, strip_tags($this->sandbox->ensureToStringAllowed($this->getAttribute($context["row"], "body", []))), 0, 100), "html", null, true);
            echo "
\t\t\t</div>
\t\t</div>
\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['row'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 49
        echo "  </div>
</div>
";
    }

    public function getTemplateName()
    {
        return "modules/custom/bfss_assessment/templates/custom/page-assessment.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  148 => 49,  138 => 45,  127 => 39,  120 => 34,  114 => 31,  108 => 28,  102 => 25,  99 => 24,  97 => 23,  88 => 16,  82 => 15,  80 => 14,  74 => 11,  66 => 7,  62 => 6,  55 => 1,);
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
</style>
<div class=\"container\">
  <div class=\"row assessment_block\">
  \t{% for row in data %}
\t    <div class=\"col use-ajax assessment_inner\" data-dialog-type=\"modal\" data-dialog-options=\"{&quot;width&quot;:800}\" href=\"{{ row.url }}\">
\t    \t<div class=\"top-head\">
\t\t\t\t<div class=\"timing\">
\t\t\t\t\t<h2>
\t\t\t\t\t\t{{ row.title }}
\t\t\t\t\t</h2>
\t\t\t\t\t<span>
\t\t\t\t\t\t{% if row.latest_timing %}
\t\t\t\t\t\t\t{{ row.latest_timing|date(\"h:i a\") }}
\t\t\t\t\t\t{% endif %}\t
\t\t\t\t\t</span>
\t\t\t\t</div>

\t\t\t\t\t<div class=\"timing-date\">

\t\t\t\t\t<span>
\t\t\t\t\t\t{% if row.latest_timing %}
\t\t\t\t\t\t\t<span class=\"m\">
\t\t\t\t\t\t\t{{ row.latest_timing|date(\"M\") }}
\t\t\t\t\t\t\t</span>
\t\t\t\t\t\t\t<span class=\"d\">
\t\t\t\t\t\t\t{{ row.latest_timing|date(\"d\") }}
\t\t\t\t\t\t\t</span>
\t\t\t\t\t\t\t<span class=\"y\">
\t\t\t\t\t\t\t{{ row.latest_timing|date(\"Y\") }}
\t\t\t\t\t\t\t</span>
\t\t\t\t\t\t{% endif %}
\t\t\t\t\t</span>
\t\t\t\t</div>

\t\t\t</div>
\t\t\t<div class='image'>
\t\t\t\t<img src='{{ row.field_image }}' alt='{{ row.title }}' />
\t\t\t</div>
\t\t\t<div class='categories-tags'>
\t\t\t\t<ul><li>TAGS</li><li>CAT HERE</li></ul>
\t\t\t</div>
\t\t\t<div class='node-body'>
\t\t\t\t{{ row.body|striptags|slice(0,100) }}
\t\t\t</div>
\t\t</div>
\t{% endfor %}
  </div>
</div>
", "modules/custom/bfss_assessment/templates/custom/page-assessment.html.twig", "/var/www/bfss.mindimage.net/web/modules/custom/bfss_assessment/templates/custom/page-assessment.html.twig");
    }
}
