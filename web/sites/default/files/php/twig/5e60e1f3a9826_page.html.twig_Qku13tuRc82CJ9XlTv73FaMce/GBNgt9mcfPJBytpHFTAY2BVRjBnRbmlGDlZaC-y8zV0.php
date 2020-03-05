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

/* themes/custom/bfss_custom/templates/system/page.html.twig */
class __TwigTemplate_5360660ee65ea24adc4e133e3f1d7b1508f29526fe8b90c416001f9c6094ae6b extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = [
            'main' => [$this, 'block_main'],
            'header' => [$this, 'block_header'],
            'sidebar_first' => [$this, 'block_sidebar_first'],
            'highlighted' => [$this, 'block_highlighted'],
            'help' => [$this, 'block_help'],
            'content' => [$this, 'block_content'],
            'sidebar_second' => [$this, 'block_sidebar_second'],
            'footer' => [$this, 'block_footer'],
        ];
        $this->sandbox = $this->env->getExtension('\Twig\Extension\SandboxExtension');
        $tags = ["set" => 63, "block" => 65, "if" => 369];
        $filters = ["escape" => 111];
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
        echo "<link rel=\"stylesheet\" href=\"/themes/custom/bfss_custom/css/style-custom.css\">
<link href=\"https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i&display=swap\" rel=\"stylesheet\">
<link rel=\"stylesheet\" href=\"https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css\">
<link rel=\"stylesheet\" href=\"https://pro.fontawesome.com/releases/v5.8.2/css/all.css\">
<script src=\"https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js\"></script>
<script src=\"https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js\"></script>
<script src=\"https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js\"></script>
<link rel=\"stylesheet\" href=\"https://pro.fontawesome.com/releases/v5.8.2/css/all.css\">

";
        // line 63
        $context["container"] = (($this->getAttribute($this->getAttribute(($context["theme"] ?? null), "settings", []), "fluid_container", [])) ? ("container-fluid") : ("container"));
        // line 65
        $this->displayBlock('main', $context, $blocks);
        // line 368
        echo "
";
        // line 369
        if ($this->getAttribute(($context["page"] ?? null), "footer", [])) {
            // line 370
            echo "  ";
            $this->displayBlock('footer', $context, $blocks);
        }
    }

    // line 65
    public function block_main($context, array $blocks = [])
    {
        // line 66
        echo "  <div role=\"main\" class=\"main-container remove_padding js-quickedit-main-content\">
    <div class=\"container\">

      ";
        // line 70
        echo "      <div class=\"row border_bottom custom-nav-box\">
      <div class=\"col-lg-2 pr-0 pl-0 header_leftbar\">
         <nav class=\"navbar navbar-expand-xs bg-dark navbar-dark\">
            <!-- Brand -->
            <a class=\"navbar-brand\" href=\"/dashboard\"><img src=\"/themes/custom/bfss_custom/images/images-dashboard/logo.png\"></a>
            <!-- Toggler/collapsibe Button -->
              <div id=\"cssmenu\">
               <div id=\"menu-button\">
                      <span class=\" toggle_icon fa fa-bars\"></span>
            </div>
            </div>
         </nav>
      </div>
      <div class=\"col-lg-10 col-md-12 header_rightbar\">
         <div class=\"row\">
            <div class=\"col-lg-2 col-md-3 col-sm-6 col-6 pr-0 pl-0 top_right\">
               <div class=\"calender_img\">
                  <i class=\"fal fa-calendar-alt\"></i>
                  <p>Scheduled <br>
                     Assessments 
                  </p>
               </div>
            </div>
            <div class=\"col-lg-2 col-md-3 col-sm-6 col-6 pr-0 pl-0 athletic_icon_section\">
               <div class=\"athletic_profile\" data-toggle=\"modal\" data-target=\"#myModal\">
                  <i class=\"far fa-chart-network\"></i>
                  <p>My Athletic <br>
                     Profile 
                  </p>
               </div>
            </div>
            <div class=\"col-lg-6 pl-0 pr-0 mobile_hide\" >
\t\t\t <div class=\"athletic_profile\"></div>
\t\t\t </div>
            <div class=\"col-lg-1 col-md-3 col-sm-6 col-6 pl-0 pr-0 bell_sec\">
               <div class=\"bell-icon-sec\">
                  <img src=\"/themes/custom/bfss_custom/images/images-dashboard/bell-icon.png\">
               </div>
            </div>
            <div class=\"col-lg-1 col-md-3 col-sm-6 col-6 pl-0 pr-0 profile_img_section\">
               <div class=\"profile-sec\">
                  <img src=\"";
        // line 111
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["user_img_url"] ?? null)), "html", null, true);
        echo "\" onclick=\"openForm()\">
                  <!----------- profile pop up start----------- -->
                  <div class=\"chat-popup\" id=\"myForm\">
                     <div class=\"form-container\">
                        <div class=\"container\">
                           <div class=\"pop_img_sec\">
                              <div class=\"row\">
                                 <div class=\"col-md-4 pr-0 \">
                                    <img src=\"";
        // line 119
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["user_img_url"] ?? null)), "html", null, true);
        echo "\">
                                 </div>
                                 <div class=\"col-md-8\">
                                    <h2>";
        // line 122
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["user_name"] ?? null)), "html", null, true);
        echo "</h2>
                                    <p>";
        // line 123
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["user_roles"] ?? null)), "html", null, true);
        echo "</p>
                                 </div>
                              </div>
                           </div>
                           <div class=\"pop_text_sec\">
                              <a href=\"#\">
                                 <a href='/edit/user'><p><i class=\"far fa-edit\"></i>Edit Account Profile</p></a>
                              </a>
                              <a href=\"#\">
                                 <p><i class=\"fas fa-user-tie\"></i>BFSS Manager</p>
                              </a>
                              <a href=\"#\" data-toggle=\"modal\" data-target=\"#my-profile-Modal\">
                                  <a href='edit/parent'><p><i class=\"fas fa-user\"></i>Parent / Guardian</p></a>
                              </a>
                              <a href=\"#\">
                                 <p><i class=\"fas fa-credit-card\"></i>Payment / Receipts</p>
                              </a>
                              <a href=\"#\">
                                 <a href='/user/logout'><p><i class=\"fas fa-power-off\"></i>Sign Out</p></a>
                              </a>
                              <button type=\"button\" class=\"btn cancel\" onclick=\"closeForm()\">Close</button>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <!-------Modal Pop start------>
            <div class=\"profile-right-modal\">
               <div class=\"container\">
                  <!-- The Modal -->
                  <div class=\"modal fade\" id=\"my-profile-Modal\">
                     <div class=\"modal-dialog modal-lg\">
                        <div class=\"modal-content\">
                           <!-- Modal Header -->
                           <div class=\"modal-header\">
                              <h4 class=\"modal-title\">Welcome Jodi,to continue you must complete all the required
                                 fields below.
                              </h4>
                              <!--  <button type=\"button\" class=\"close\" data-dismiss=\"modal\">&times;</button> -->
                           </div>
                           <!-- Modal body -->
                           <div class=\"modal-body\">
                              <div class=\"row\">
                                 <div class=\"col-md-7\">
                                    <button class=\"accordion\">ATHLETE'S INFORMATION <i class=\"fas fa-info\"></i></button>
                                    <div class=\"panel\">
                                       <input type=\"text\" name=\"name\" placeholder=\"Jodi\">
                                       <input type=\"text\" name=\"name\" placeholder=\"Blogs\">
                                       <div class=\"form-group\">
                                          <select class=\"form-control\" id=\"sel1\">
                                             <option>AZ</option>
                                             <option>AB</option>
                                             <option>AC</option>
                                             <option>AD</option>
                                          </select>
                                       </div>
                                       <input type=\"text\" name=\"name\" placeholder=\"City\">
                                       <div class=\"form-group\">
                                          <select class=\"form-control\" id=\"sel1\">
                                             <option>Birth Gender</option>
                                             <option>Male</option>
                                             <option>Female</option>
                                          </select>
                                       </div>
                                       <input type=\"text\" name=\"name\" placeholder=\"DOB:MM/DD/YYYY\">
                                       <input type=\"text\" name=\"name\" placeholder=\"Height in Inches\">
                                       <input type=\"text\" name=\"name\" placeholder=\"Weight in Pounds\">
                                    </div>
                                    <!-------SOCIAL/CLUB/UNIVERSITY---SEC--- START-->
                                    <div class=\"social-club-sec\">
                                       <button class=\"accordion\">SOCIAL/CLUB/UNIVERSITY <i class=\"far fa-trash-alt\"></i></button>
                                       <div class=\"panel\">
                                          <div class=\"form-group\">
                                             <select class=\"form-control\" id=\"sel1\">
                                                <option>Organization Type</option>
                                                <option>ABCD</option>
                                                <option>ABCD</option>
                                                <option>ABCD</option>
                                             </select>
                                          </div>
                                          <div class=\"form-group\">
                                             <select class=\"form-control\" id=\"sel1\">
                                                <option>Organization Name</option>
                                                <option>ABCD</option>
                                                <option>ABCD</option>
                                                <option>ABCD</option>
                                                <option>ABCD</option>
                                             </select>
                                          </div>
                                          <input type=\"text\" name=\"name\" placeholder=\"Coach's Last Name(Optional)\">
                                          <input type=\"text\" name=\"name\" placeholder=\"Sports\">
                                          <input type=\"text\" name=\"name\" placeholder=\"Position\">
                                          <h3><a href=\"#\"><i class=\"fal fa-plus\"></i>Additional Position</a></h3>
                                          <textarea placeholder=\"Your Stats\" rows=\"4\"></textarea>
                                       </div>
                                       <h3 class=\"add-organization\"><a href=\"#\"><i class=\"fal fa-plus\"></i>Add Another Organization</a></h3>
                                    </div>
                                    <!-------SOCIAL/CLUB/UNIVERSITY---SEC--- END-->
                                 </div>
                                 <div class=\"col-md-5\">
                                    <div class=\"socila-media-sec\">
                                       <button class=\"accordion\">SOCIAL MEDIA <i class=\"fas fa-info\"></i></button>
                                       <div class=\"panel\">
                                          <input type=\"text\" name=\"name\" placeholder=\"Your Instagram Account(Optional)\">
                                          <input type=\"text\" name=\"name\" placeholder=\"Your Youtube / Vimeo Channel(Optional)\">
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <!-- Modal footer -->
                           <div class=\"modal-footer\">
                              <button type=\"button\" class=\"btn btn-secondary\" data-dismiss=\"modal\">Close</button>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <!-------Modal Pop End------>
            <!----------- profile pop up -----End--------- -->
         </div>
      </div>
   </div>
     
      ";
        // line 249
        if ($this->getAttribute(($context["page"] ?? null), "header", [])) {
            // line 250
            echo "        ";
            $this->displayBlock('header', $context, $blocks);
            // line 257
            echo "      ";
        }
        // line 258
        echo "
      ";
        // line 260
        echo "      ";
        if ($this->getAttribute(($context["page"] ?? null), "sidebar_first", [])) {
            // line 261
            echo "        ";
            $this->displayBlock('sidebar_first', $context, $blocks);
            // line 266
            echo "      ";
        }
        // line 267
        echo "
      ";
        // line 269
        echo "      ";
        // line 270
        $context["content_classes"] = [0 => ((($this->getAttribute(        // line 271
($context["page"] ?? null), "sidebar_first", []) && $this->getAttribute(($context["page"] ?? null), "sidebar_second", []))) ? ("col-sm-6") : ("")), 1 => ((($this->getAttribute(        // line 272
($context["page"] ?? null), "sidebar_first", []) && twig_test_empty($this->getAttribute(($context["page"] ?? null), "sidebar_second", [])))) ? ("col-sm-9") : ("")), 2 => ((($this->getAttribute(        // line 273
($context["page"] ?? null), "sidebar_second", []) && twig_test_empty($this->getAttribute(($context["page"] ?? null), "sidebar_first", [])))) ? ("col-sm-9") : ("")), 3 => (((twig_test_empty($this->getAttribute(        // line 274
($context["page"] ?? null), "sidebar_first", [])) && twig_test_empty($this->getAttribute(($context["page"] ?? null), "sidebar_second", [])))) ? ("col-sm-12") : (""))];
        // line 277
        echo "      <section ";
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->renderVar("class='row'");
        echo ">
          <!--Header left side section -->
      <div class=\"left_side_section pl-0 pr-0\">

         <div class=\"sidenav\" id=\"sidenav\">
            <ul class=\"side_nav mb-0\">
               <li class=\"sub_menu\">
                  <button class=\"side_button\"><span class=\"side_menu_text\">NAVIGATION</span></button> 
               </li>
               <li class=\"sub_menu active\">
                  <button class=\"side_button\"><i class=\"fas fa-home\"></i><span class=\"side_menu_text\">Dashboard</span></button>
               </li>
               <li class=\"sub-menu-drop\">
                  <!--  <button class=\"side_button\">Calender-View & Book</button> -->
                  <nav>
                     <div class=\"dropdown\">
                        <a class=\"drop\" href=\"#\"><i class=\"fal fa-calendar-alt\"></i><span class=\"side_menu_text\">Calender-View & Book</span><span class=\"down-arrow fa fa-angle-down\"></span></a>
                        <ul class=\"dropdown-menu\">
                           <li><a href=\"/upcoming-group-assessments\"> Upcoming Group Assessments </a></li>
                           <li><a href=\"#\"> Book a Private Assessment</a></li>
                           <li><a href=\"#\">My Scheduled Assessment</a></li>
                        </ul>
                     </div>
                  </nav>
               </li>
               <li class=\"sub_menu\">
                  <button class=\"side_button\"><i class=\"far fa-chart-bar\"></i><span class=\"side_menu_text\">My Assessment Status</span></button>
               </li>
               <li class=\"sub-menu-drop\">
                  <nav>
                     <div class=\"dropdown\">

                        <a class=\"drop\" href=\"/bfssAthleteProfile\"><i class=\"far fa-chart-network\"></i><span class=\"side_menu_text\">Athletic Profile</span><span class=\"down-arrow fa fa-angle-down\"></span></a>

                        <ul class=\"dropdown-menu\">
                           <li><a href=\"/bfssAthleteProfile\">Edit My Athletic Profile </a></li>
                           <li><a href=\"#\">View My Webpage</a></li>
                        </ul>
                     </div>
                  </nav>
               </li>
               <!-- <li class=\"sub_menu\"> -->
               <!-- <button class=\"side_button\"><i class=\"far fa-cogs\"></i></i>Support</button> -->
               <!-- </li> -->
               <li class=\"sub-menu-drop\">
                  <nav>
                     <div class=\"dropdown\">
                        <a class=\"drop\" href=\"#\"><i class=\"far fa-cogs\"></i></i><span class=\"side_menu_text\">Support</span><span class=\"down-arrow fa fa-angle-down\"></span></a>
                        <ul class=\"dropdown-menu\">
                           <li><a href=\"/faq-page\"> FAQs </a></li>
                           <li><a href=\"#\">Ticketing</a></li>
                        </ul>
                     </div>
                  </nav>
               </li>
            </ul>
         </div>
      </div>

        ";
        // line 337
        echo "        ";
        if ($this->getAttribute(($context["page"] ?? null), "highlighted", [])) {
            // line 338
            echo "          ";
            $this->displayBlock('highlighted', $context, $blocks);
            // line 341
            echo "        ";
        }
        // line 342
        echo "
        ";
        // line 344
        echo "        ";
        if ($this->getAttribute(($context["page"] ?? null), "help", [])) {
            // line 345
            echo "          ";
            $this->displayBlock('help', $context, $blocks);
            // line 348
            echo "        ";
        }
        // line 349
        echo "
        ";
        // line 351
        echo "        ";
        $this->displayBlock('content', $context, $blocks);
        // line 355
        echo "      </section>

      ";
        // line 358
        echo "      ";
        if ($this->getAttribute(($context["page"] ?? null), "sidebar_second", [])) {
            // line 359
            echo "        ";
            $this->displayBlock('sidebar_second', $context, $blocks);
            // line 364
            echo "      ";
        }
        // line 365
        echo "    </div>
  </div>
";
    }

    // line 250
    public function block_header($context, array $blocks = [])
    {
        // line 251
        echo "        <div class=\"row dashboard_row\">
          <div class=\"col-sm-12\" role=\"heading\">
            ";
        // line 253
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["page"] ?? null), "header", [])), "html", null, true);
        echo "
          </div>
        </div>
        ";
    }

    // line 261
    public function block_sidebar_first($context, array $blocks = [])
    {
        // line 262
        echo "          <aside class=\"col-sm-3\" role=\"complementary\">
            ";
        // line 263
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["page"] ?? null), "sidebar_first", [])), "html", null, true);
        echo "
          </aside>
        ";
    }

    // line 338
    public function block_highlighted($context, array $blocks = [])
    {
        // line 339
        echo "            <div class=\"highlighted\">";
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["page"] ?? null), "highlighted", [])), "html", null, true);
        echo "</div>
          ";
    }

    // line 345
    public function block_help($context, array $blocks = [])
    {
        // line 346
        echo "            ";
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["page"] ?? null), "help", [])), "html", null, true);
        echo "
          ";
    }

    // line 351
    public function block_content($context, array $blocks = [])
    {
        // line 352
        echo "          <a id=\"main-content\"></a>
          ";
        // line 353
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["page"] ?? null), "content", [])), "html", null, true);
        echo "
        ";
    }

    // line 359
    public function block_sidebar_second($context, array $blocks = [])
    {
        // line 360
        echo "          <aside class=\"col-sm-3\" role=\"complementary\">
            ";
        // line 361
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["page"] ?? null), "sidebar_second", [])), "html", null, true);
        echo "
          </aside>
        ";
    }

    // line 370
    public function block_footer($context, array $blocks = [])
    {
        // line 371
        echo "    <footer class=\"footer ";
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["container"] ?? null)), "html", null, true);
        echo "\" role=\"contentinfo\">
      ";
        // line 372
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["page"] ?? null), "footer", [])), "html", null, true);
        echo "
    </footer>
  ";
    }

    public function getTemplateName()
    {
        return "themes/custom/bfss_custom/templates/system/page.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  511 => 372,  506 => 371,  503 => 370,  496 => 361,  493 => 360,  490 => 359,  484 => 353,  481 => 352,  478 => 351,  471 => 346,  468 => 345,  461 => 339,  458 => 338,  451 => 263,  448 => 262,  445 => 261,  437 => 253,  433 => 251,  430 => 250,  424 => 365,  421 => 364,  418 => 359,  415 => 358,  411 => 355,  408 => 351,  405 => 349,  402 => 348,  399 => 345,  396 => 344,  393 => 342,  390 => 341,  387 => 338,  384 => 337,  321 => 277,  319 => 274,  318 => 273,  317 => 272,  316 => 271,  315 => 270,  313 => 269,  310 => 267,  307 => 266,  304 => 261,  301 => 260,  298 => 258,  295 => 257,  292 => 250,  290 => 249,  161 => 123,  157 => 122,  151 => 119,  140 => 111,  97 => 70,  92 => 66,  89 => 65,  83 => 370,  81 => 369,  78 => 368,  76 => 65,  74 => 63,  63 => 54,);
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
<link rel=\"stylesheet\" href=\"/themes/custom/bfss_custom/css/style-custom.css\">
<link href=\"https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i&display=swap\" rel=\"stylesheet\">
<link rel=\"stylesheet\" href=\"https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css\">
<link rel=\"stylesheet\" href=\"https://pro.fontawesome.com/releases/v5.8.2/css/all.css\">
<script src=\"https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js\"></script>
<script src=\"https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js\"></script>
<script src=\"https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js\"></script>
<link rel=\"stylesheet\" href=\"https://pro.fontawesome.com/releases/v5.8.2/css/all.css\">

{% set container = theme.settings.fluid_container ? 'container-fluid' : 'container' %}
{# Main #}
{% block main %}
  <div role=\"main\" class=\"main-container remove_padding js-quickedit-main-content\">
    <div class=\"container\">

      {# Header #}
      <div class=\"row border_bottom custom-nav-box\">
      <div class=\"col-lg-2 pr-0 pl-0 header_leftbar\">
         <nav class=\"navbar navbar-expand-xs bg-dark navbar-dark\">
            <!-- Brand -->
            <a class=\"navbar-brand\" href=\"/dashboard\"><img src=\"/themes/custom/bfss_custom/images/images-dashboard/logo.png\"></a>
            <!-- Toggler/collapsibe Button -->
              <div id=\"cssmenu\">
               <div id=\"menu-button\">
                      <span class=\" toggle_icon fa fa-bars\"></span>
            </div>
            </div>
         </nav>
      </div>
      <div class=\"col-lg-10 col-md-12 header_rightbar\">
         <div class=\"row\">
            <div class=\"col-lg-2 col-md-3 col-sm-6 col-6 pr-0 pl-0 top_right\">
               <div class=\"calender_img\">
                  <i class=\"fal fa-calendar-alt\"></i>
                  <p>Scheduled <br>
                     Assessments 
                  </p>
               </div>
            </div>
            <div class=\"col-lg-2 col-md-3 col-sm-6 col-6 pr-0 pl-0 athletic_icon_section\">
               <div class=\"athletic_profile\" data-toggle=\"modal\" data-target=\"#myModal\">
                  <i class=\"far fa-chart-network\"></i>
                  <p>My Athletic <br>
                     Profile 
                  </p>
               </div>
            </div>
            <div class=\"col-lg-6 pl-0 pr-0 mobile_hide\" >
\t\t\t <div class=\"athletic_profile\"></div>
\t\t\t </div>
            <div class=\"col-lg-1 col-md-3 col-sm-6 col-6 pl-0 pr-0 bell_sec\">
               <div class=\"bell-icon-sec\">
                  <img src=\"/themes/custom/bfss_custom/images/images-dashboard/bell-icon.png\">
               </div>
            </div>
            <div class=\"col-lg-1 col-md-3 col-sm-6 col-6 pl-0 pr-0 profile_img_section\">
               <div class=\"profile-sec\">
                  <img src=\"{{user_img_url}}\" onclick=\"openForm()\">
                  <!----------- profile pop up start----------- -->
                  <div class=\"chat-popup\" id=\"myForm\">
                     <div class=\"form-container\">
                        <div class=\"container\">
                           <div class=\"pop_img_sec\">
                              <div class=\"row\">
                                 <div class=\"col-md-4 pr-0 \">
                                    <img src=\"{{user_img_url}}\">
                                 </div>
                                 <div class=\"col-md-8\">
                                    <h2>{{user_name}}</h2>
                                    <p>{{user_roles}}</p>
                                 </div>
                              </div>
                           </div>
                           <div class=\"pop_text_sec\">
                              <a href=\"#\">
                                 <a href='/edit/user'><p><i class=\"far fa-edit\"></i>Edit Account Profile</p></a>
                              </a>
                              <a href=\"#\">
                                 <p><i class=\"fas fa-user-tie\"></i>BFSS Manager</p>
                              </a>
                              <a href=\"#\" data-toggle=\"modal\" data-target=\"#my-profile-Modal\">
                                  <a href='edit/parent'><p><i class=\"fas fa-user\"></i>Parent / Guardian</p></a>
                              </a>
                              <a href=\"#\">
                                 <p><i class=\"fas fa-credit-card\"></i>Payment / Receipts</p>
                              </a>
                              <a href=\"#\">
                                 <a href='/user/logout'><p><i class=\"fas fa-power-off\"></i>Sign Out</p></a>
                              </a>
                              <button type=\"button\" class=\"btn cancel\" onclick=\"closeForm()\">Close</button>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <!-------Modal Pop start------>
            <div class=\"profile-right-modal\">
               <div class=\"container\">
                  <!-- The Modal -->
                  <div class=\"modal fade\" id=\"my-profile-Modal\">
                     <div class=\"modal-dialog modal-lg\">
                        <div class=\"modal-content\">
                           <!-- Modal Header -->
                           <div class=\"modal-header\">
                              <h4 class=\"modal-title\">Welcome Jodi,to continue you must complete all the required
                                 fields below.
                              </h4>
                              <!--  <button type=\"button\" class=\"close\" data-dismiss=\"modal\">&times;</button> -->
                           </div>
                           <!-- Modal body -->
                           <div class=\"modal-body\">
                              <div class=\"row\">
                                 <div class=\"col-md-7\">
                                    <button class=\"accordion\">ATHLETE'S INFORMATION <i class=\"fas fa-info\"></i></button>
                                    <div class=\"panel\">
                                       <input type=\"text\" name=\"name\" placeholder=\"Jodi\">
                                       <input type=\"text\" name=\"name\" placeholder=\"Blogs\">
                                       <div class=\"form-group\">
                                          <select class=\"form-control\" id=\"sel1\">
                                             <option>AZ</option>
                                             <option>AB</option>
                                             <option>AC</option>
                                             <option>AD</option>
                                          </select>
                                       </div>
                                       <input type=\"text\" name=\"name\" placeholder=\"City\">
                                       <div class=\"form-group\">
                                          <select class=\"form-control\" id=\"sel1\">
                                             <option>Birth Gender</option>
                                             <option>Male</option>
                                             <option>Female</option>
                                          </select>
                                       </div>
                                       <input type=\"text\" name=\"name\" placeholder=\"DOB:MM/DD/YYYY\">
                                       <input type=\"text\" name=\"name\" placeholder=\"Height in Inches\">
                                       <input type=\"text\" name=\"name\" placeholder=\"Weight in Pounds\">
                                    </div>
                                    <!-------SOCIAL/CLUB/UNIVERSITY---SEC--- START-->
                                    <div class=\"social-club-sec\">
                                       <button class=\"accordion\">SOCIAL/CLUB/UNIVERSITY <i class=\"far fa-trash-alt\"></i></button>
                                       <div class=\"panel\">
                                          <div class=\"form-group\">
                                             <select class=\"form-control\" id=\"sel1\">
                                                <option>Organization Type</option>
                                                <option>ABCD</option>
                                                <option>ABCD</option>
                                                <option>ABCD</option>
                                             </select>
                                          </div>
                                          <div class=\"form-group\">
                                             <select class=\"form-control\" id=\"sel1\">
                                                <option>Organization Name</option>
                                                <option>ABCD</option>
                                                <option>ABCD</option>
                                                <option>ABCD</option>
                                                <option>ABCD</option>
                                             </select>
                                          </div>
                                          <input type=\"text\" name=\"name\" placeholder=\"Coach's Last Name(Optional)\">
                                          <input type=\"text\" name=\"name\" placeholder=\"Sports\">
                                          <input type=\"text\" name=\"name\" placeholder=\"Position\">
                                          <h3><a href=\"#\"><i class=\"fal fa-plus\"></i>Additional Position</a></h3>
                                          <textarea placeholder=\"Your Stats\" rows=\"4\"></textarea>
                                       </div>
                                       <h3 class=\"add-organization\"><a href=\"#\"><i class=\"fal fa-plus\"></i>Add Another Organization</a></h3>
                                    </div>
                                    <!-------SOCIAL/CLUB/UNIVERSITY---SEC--- END-->
                                 </div>
                                 <div class=\"col-md-5\">
                                    <div class=\"socila-media-sec\">
                                       <button class=\"accordion\">SOCIAL MEDIA <i class=\"fas fa-info\"></i></button>
                                       <div class=\"panel\">
                                          <input type=\"text\" name=\"name\" placeholder=\"Your Instagram Account(Optional)\">
                                          <input type=\"text\" name=\"name\" placeholder=\"Your Youtube / Vimeo Channel(Optional)\">
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <!-- Modal footer -->
                           <div class=\"modal-footer\">
                              <button type=\"button\" class=\"btn btn-secondary\" data-dismiss=\"modal\">Close</button>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <!-------Modal Pop End------>
            <!----------- profile pop up -----End--------- -->
         </div>
      </div>
   </div>
     
      {% if page.header %}
        {% block header %}
        <div class=\"row dashboard_row\">
          <div class=\"col-sm-12\" role=\"heading\">
            {{ page.header }}
          </div>
        </div>
        {% endblock %}
      {% endif %}

      {# Sidebar First #}
      {% if page.sidebar_first %}
        {% block sidebar_first %}
          <aside class=\"col-sm-3\" role=\"complementary\">
            {{ page.sidebar_first }}
          </aside>
        {% endblock %}
      {% endif %}

      {# Content #}
      {%
        set content_classes = [
          page.sidebar_first and page.sidebar_second ? 'col-sm-6',
          page.sidebar_first and page.sidebar_second is empty ? 'col-sm-9',
          page.sidebar_second and page.sidebar_first is empty ? 'col-sm-9',
          page.sidebar_first is empty and page.sidebar_second is empty ? 'col-sm-12'
        ]
      %}
      <section {{ \"class='row'\" }}>
          <!--Header left side section -->
      <div class=\"left_side_section pl-0 pr-0\">

         <div class=\"sidenav\" id=\"sidenav\">
            <ul class=\"side_nav mb-0\">
               <li class=\"sub_menu\">
                  <button class=\"side_button\"><span class=\"side_menu_text\">NAVIGATION</span></button> 
               </li>
               <li class=\"sub_menu active\">
                  <button class=\"side_button\"><i class=\"fas fa-home\"></i><span class=\"side_menu_text\">Dashboard</span></button>
               </li>
               <li class=\"sub-menu-drop\">
                  <!--  <button class=\"side_button\">Calender-View & Book</button> -->
                  <nav>
                     <div class=\"dropdown\">
                        <a class=\"drop\" href=\"#\"><i class=\"fal fa-calendar-alt\"></i><span class=\"side_menu_text\">Calender-View & Book</span><span class=\"down-arrow fa fa-angle-down\"></span></a>
                        <ul class=\"dropdown-menu\">
                           <li><a href=\"/upcoming-group-assessments\"> Upcoming Group Assessments </a></li>
                           <li><a href=\"#\"> Book a Private Assessment</a></li>
                           <li><a href=\"#\">My Scheduled Assessment</a></li>
                        </ul>
                     </div>
                  </nav>
               </li>
               <li class=\"sub_menu\">
                  <button class=\"side_button\"><i class=\"far fa-chart-bar\"></i><span class=\"side_menu_text\">My Assessment Status</span></button>
               </li>
               <li class=\"sub-menu-drop\">
                  <nav>
                     <div class=\"dropdown\">

                        <a class=\"drop\" href=\"/bfssAthleteProfile\"><i class=\"far fa-chart-network\"></i><span class=\"side_menu_text\">Athletic Profile</span><span class=\"down-arrow fa fa-angle-down\"></span></a>

                        <ul class=\"dropdown-menu\">
                           <li><a href=\"/bfssAthleteProfile\">Edit My Athletic Profile </a></li>
                           <li><a href=\"#\">View My Webpage</a></li>
                        </ul>
                     </div>
                  </nav>
               </li>
               <!-- <li class=\"sub_menu\"> -->
               <!-- <button class=\"side_button\"><i class=\"far fa-cogs\"></i></i>Support</button> -->
               <!-- </li> -->
               <li class=\"sub-menu-drop\">
                  <nav>
                     <div class=\"dropdown\">
                        <a class=\"drop\" href=\"#\"><i class=\"far fa-cogs\"></i></i><span class=\"side_menu_text\">Support</span><span class=\"down-arrow fa fa-angle-down\"></span></a>
                        <ul class=\"dropdown-menu\">
                           <li><a href=\"/faq-page\"> FAQs </a></li>
                           <li><a href=\"#\">Ticketing</a></li>
                        </ul>
                     </div>
                  </nav>
               </li>
            </ul>
         </div>
      </div>

        {# Highlighted #}
        {% if page.highlighted %}
          {% block highlighted %}
            <div class=\"highlighted\">{{ page.highlighted }}</div>
          {% endblock %}
        {% endif %}

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
{% endblock %}

{% if page.footer %}
  {% block footer %}
    <footer class=\"footer {{ container }}\" role=\"contentinfo\">
      {{ page.footer }}
    </footer>
  {% endblock %}
{% endif %}
", "themes/custom/bfss_custom/templates/system/page.html.twig", "/var/www/5ppsystem.com/web/themes/custom/bfss_custom/templates/system/page.html.twig");
    }
}
