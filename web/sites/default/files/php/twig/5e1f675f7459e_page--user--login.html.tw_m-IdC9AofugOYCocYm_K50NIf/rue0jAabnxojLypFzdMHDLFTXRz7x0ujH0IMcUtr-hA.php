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

/* themes/custom/bfss_custom/templates/page--user--login.html.twig */
class __TwigTemplate_7c69e2f51bd975f8a7ab48cdb5fb7c44958deb8d2240c000fcea3ada3c62427e extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = [
            'main' => [$this, 'block_main'],
            'help' => [$this, 'block_help'],
            'content' => [$this, 'block_content'],
            'highlighted' => [$this, 'block_highlighted'],
            'sidebar_second' => [$this, 'block_sidebar_second'],
            'footer' => [$this, 'block_footer'],
        ];
        $this->sandbox = $this->env->getExtension('\Twig\Extension\SandboxExtension');
        $tags = ["set" => 54, "block" => 57, "if" => 117];
        $filters = ["escape" => 58];
        $functions = [];

        try {
            $this->sandbox->checkSecurity(
                ['set', 'block', 'if'],
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
        // line 54
        $context["container"] = (($this->getAttribute($this->getAttribute(($context["theme"] ?? null), "settings", []), "fluid_container", [])) ? ("container-fluid") : ("container"));
        // line 55
        echo "
";
        // line 57
        $this->displayBlock('main', $context, $blocks);
        // line 116
        echo "
";
        // line 117
        if ($this->getAttribute(($context["page"] ?? null), "footer", [])) {
            // line 118
            echo "    ";
            $this->displayBlock('footer', $context, $blocks);
        }
    }

    // line 57
    public function block_main($context, array $blocks = [])
    {
        // line 58
        echo "    <div role=\"main\" class=\"main-container ";
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["container"] ?? null)), "html", null, true);
        echo " js-quickedit-main-content\">
        <div class=\"row\">
            <div class=\"sub-header\">
                <div class=\"sub-header-logo\">
                    <img src=\"/sites/default/files/BFSS-LogoFinal-Yellow.png\" />
                </div>
            </div>
            <div class=\"register-area col-lg-3 col-md-4 col-sm-5 col-xs-9\">
                <h3>Create a New User Account</h3>
                <button class=\"register-here bfss-button\">REGISTER HERE</button>
            </div>

            ";
        // line 71
        echo "            ";
        // line 72
        $context["content_classes"] = [0 => ((($this->getAttribute(        // line 73
($context["page"] ?? null), "sidebar_first", []) && $this->getAttribute(($context["page"] ?? null), "sidebar_second", []))) ? ("col-sm-6") : ("")), 1 => ((($this->getAttribute(        // line 74
($context["page"] ?? null), "sidebar_first", []) && twig_test_empty($this->getAttribute(($context["page"] ?? null), "sidebar_second", [])))) ? ("col-sm-9") : ("")), 2 => ((($this->getAttribute(        // line 75
($context["page"] ?? null), "sidebar_second", []) && twig_test_empty($this->getAttribute(($context["page"] ?? null), "sidebar_first", [])))) ? ("col-sm-9") : ("")), 3 => (((twig_test_empty($this->getAttribute(        // line 76
($context["page"] ?? null), "sidebar_first", [])) && twig_test_empty($this->getAttribute(($context["page"] ?? null), "sidebar_second", [])))) ? ("col-sm-12") : (""))];
        // line 79
        echo "            ";
        // line 80
        echo "            <section class=\"registration-area col-lg-3 col-md-4 col-sm-5 col-xs-9\">


                ";
        // line 84
        echo "                ";
        if ($this->getAttribute(($context["page"] ?? null), "help", [])) {
            // line 85
            echo "                    ";
            $this->displayBlock('help', $context, $blocks);
            // line 88
            echo "                ";
        }
        // line 89
        echo "
                ";
        // line 91
        echo "                ";
        $this->displayBlock('content', $context, $blocks);
        // line 95
        echo "
                ";
        // line 97
        echo "                ";
        if ($this->getAttribute(($context["page"] ?? null), "highlighted", [])) {
            // line 98
            echo "                    ";
            $this->displayBlock('highlighted', $context, $blocks);
            // line 101
            echo "                ";
        }
        // line 102
        echo "            </section>

            ";
        // line 105
        echo "            ";
        if ($this->getAttribute(($context["page"] ?? null), "sidebar_second", [])) {
            // line 106
            echo "                ";
            $this->displayBlock('sidebar_second', $context, $blocks);
            // line 111
            echo "            ";
        }
        // line 112
        echo "            </div>
        </div>
    </div>
";
    }

    // line 85
    public function block_help($context, array $blocks = [])
    {
        // line 86
        echo "                        ";
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["page"] ?? null), "help", [])), "html", null, true);
        echo "
                    ";
    }

    // line 91
    public function block_content($context, array $blocks = [])
    {
        // line 92
        echo "                    <a id=\"main-content\"></a>
                    ";
        // line 93
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["page"] ?? null), "content", [])), "html", null, true);
        echo "
                ";
    }

    // line 98
    public function block_highlighted($context, array $blocks = [])
    {
        // line 99
        echo "                        <div class=\"highlighted\">";
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["page"] ?? null), "highlighted", [])), "html", null, true);
        echo "</div>
                    ";
    }

    // line 106
    public function block_sidebar_second($context, array $blocks = [])
    {
        // line 107
        echo "                    <aside class=\"col-sm-3\" role=\"complementary\">
                        ";
        // line 108
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["page"] ?? null), "sidebar_second", [])), "html", null, true);
        echo "
                    </aside>
                ";
    }

    // line 118
    public function block_footer($context, array $blocks = [])
    {
        // line 119
        echo "        <footer class=\"footer ";
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["container"] ?? null)), "html", null, true);
        echo "\" role=\"contentinfo\">
            ";
        // line 120
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["page"] ?? null), "footer", [])), "html", null, true);
        echo "
        </footer>
    ";
    }

    public function getTemplateName()
    {
        return "themes/custom/bfss_custom/templates/page--user--login.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  213 => 120,  208 => 119,  205 => 118,  198 => 108,  195 => 107,  192 => 106,  185 => 99,  182 => 98,  176 => 93,  173 => 92,  170 => 91,  163 => 86,  160 => 85,  153 => 112,  150 => 111,  147 => 106,  144 => 105,  140 => 102,  137 => 101,  134 => 98,  131 => 97,  128 => 95,  125 => 91,  122 => 89,  119 => 88,  116 => 85,  113 => 84,  108 => 80,  106 => 79,  104 => 76,  103 => 75,  102 => 74,  101 => 73,  100 => 72,  98 => 71,  82 => 58,  79 => 57,  73 => 118,  71 => 117,  68 => 116,  66 => 57,  63 => 55,  61 => 54,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("{#
/**
 * @file
 * Default theme implementation to display a single page.
 *
 * The doctype, html, head and body tags are not in this template. Instead they
 * can be found in the html.html.twig template in this directory.
 *
 * Available variables:
 *
 * General utility variables:
 * - base_path: The base URL path of the Drupal installation. Will usually be
 *   \"/\" unless you have installed Drupal in a sub-directory.
 * - is_front: A flag indicating if the current page is the front page.
 * - logged_in: A flag indicating if the user is registered and signed in.
 * - is_admin: A flag indicating if the user has permission to access
 *   administration pages.
 *
 * Site identity:
 * - front_page: The URL of the front page. Use this instead of base_path when
 *   linking to the front page. This includes the language domain or prefix.
 *
 * Page content (in order of occurrence in the default page.html.twig):
 * - title_prefix: Additional output populated by modules, intended to be
 *   displayed in front of the main title tag that appears in the template.
 * - title: The page title, for use in the actual content.
 * - title_suffix: Additional output populated by modules, intended to be
 *   displayed after the main title tag that appears in the template.
 * - messages: Status and error messages. Should be displayed prominently.
 * - tabs: Tabs linking to any sub-pages beneath the current page (e.g., the
 *   view and edit tabs when displaying a node).
 * - node: Fully loaded node, if there is an automatically-loaded node
 *   associated with the page and the node ID is the second argument in the
 *   page's path (e.g. node/12345 and node/12345/revisions, but not
 *   comment/reply/12345).
 *
 * Regions:
 * - page.header: Items for the header region.
 * - page.navigation: Items for the navigation region.
 * - page.navigation_collapsible: Items for the navigation (collapsible) region.
 * - page.highlighted: Items for the highlighted content region.
 * - page.help: Dynamic help text, mostly for admin pages.
 * - page.content: The main content of the current page.
 * - page.sidebar_first: Items for the first sidebar.
 * - page.sidebar_second: Items for the second sidebar.
 * - page.footer: Items for the footer region.
 *
 * @ingroup templates
 *
 * @see template_preprocess_page()
 * @see html.html.twig
 */
#}
{% set container = theme.settings.fluid_container ? 'container-fluid' : 'container' %}

{# Main #}
{% block main %}
    <div role=\"main\" class=\"main-container {{ container }} js-quickedit-main-content\">
        <div class=\"row\">
            <div class=\"sub-header\">
                <div class=\"sub-header-logo\">
                    <img src=\"/sites/default/files/BFSS-LogoFinal-Yellow.png\" />
                </div>
            </div>
            <div class=\"register-area col-lg-3 col-md-4 col-sm-5 col-xs-9\">
                <h3>Create a New User Account</h3>
                <button class=\"register-here bfss-button\">REGISTER HERE</button>
            </div>

            {# Content #}
            {%
                set content_classes = [
                page.sidebar_first and page.sidebar_second ? 'col-sm-6',
                page.sidebar_first and page.sidebar_second is empty ? 'col-sm-9',
                page.sidebar_second and page.sidebar_first is empty ? 'col-sm-9',
                page.sidebar_first is empty and page.sidebar_second is empty ? 'col-sm-12'
            ]
            %}
            {#<section{{ content_attributes.addClass(content_classes) }}>#}
            <section class=\"registration-area col-lg-3 col-md-4 col-sm-5 col-xs-9\">


                {# Help #}
                {% if page.help %}
                    {% block help %}
                        {{ page.help }}
                    {% endblock %}
                {% endif %}

                {# Content #}
                {% block content %}
                    <a id=\"main-content\"></a>
                    {{ page.content }}
                {% endblock %}

                {# Highlighted #}
                {% if page.highlighted %}
                    {% block highlighted %}
                        <div class=\"highlighted\">{{ page.highlighted }}</div>
                    {% endblock %}
                {% endif %}
            </section>

            {# Sidebar Second #}
            {% if page.sidebar_second %}
                {% block sidebar_second %}
                    <aside class=\"col-sm-3\" role=\"complementary\">
                        {{ page.sidebar_second }}
                    </aside>
                {% endblock %}
            {% endif %}
            </div>
        </div>
    </div>
{% endblock %}

{% if page.footer %}
    {% block footer %}
        <footer class=\"footer {{ container }}\" role=\"contentinfo\">
            {{ page.footer }}
        </footer>
    {% endblock %}
{% endif %}
", "themes/custom/bfss_custom/templates/page--user--login.html.twig", "/var/www/5ppsystem.com/web/themes/custom/bfss_custom/templates/page--user--login.html.twig");
    }
}
