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

/* modules/custom/acme/templates/hello.html.twig */
class __TwigTemplate_586fe397d31129a503723c3387fc6865e163cea950e2ea7b49d9c805cb54a121 extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = [
        ];
        $this->sandbox = $this->env->getExtension('\Twig\Extension\SandboxExtension');
        $tags = [];
        $filters = ["escape" => 135];
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
        echo " 
</html>
<html>   
<meta charset=\"utf-8\">
<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
<link rel=\"stylesheet\" href=\"/themes/custom/bfss_custom/css/style-custom.css\">
<link href=\"https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i&display=swap\" rel=\"stylesheet\">
<link rel=\"stylesheet\" href=\"https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css\">
<link rel=\"stylesheet\" href=\"https://pro.fontawesome.com/releases/v5.8.2/css/all.css\">
<script src=\"https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js\"></script>
<script src=\"https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js\"></script>
<script src=\"https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js\"></script>
</head>
<script type=\"text/javascript\">
   \$(document).ready(function(){
   \$('.dropdown > a').click(function(e){
     e.preventDefault();
     e.stopPropagation();
     \$(this).siblings('.dropdown-menu').toggleClass('open');
     \$(this).find('.down-arrow').toggleClass('open-caret');

   });

   \$(document).click(function(){
       \$('.dropdown-menu').removeClass('open');
       \$('.down-arrow').removeClass('open-caret');  
     });
   });
   
   function openForm() {
   document.getElementById(\"myForm\").style.display = \"block\";
   }
   
   function closeForm() {
   document.getElementById(\"myForm\").style.display = \"none\";
   }
   
   
</script>
<body>
   
   </div>
   <div class=\"row\" style=\"margin-left: 0px;margin-right: 0px;\">
      <!-- ------------Side Navbar----------start -->

     
      <!-- ------------Side Navbar----------end -->
      <div class=\"right_side_section pl-0 pr-0\">
         <div class=\"dash-main-right\">
            <h1><i class=\"fas fa-home\"></i> > Dashboard </h1>
            <div class=\"dash-sub-main\">
               <img src=\"/themes/custom/bfss_custom/images/images-dashboard/dash-img.png\">  
               <h2><span>ALL FEATURES</span><br>Dashboard  </h2>
            </div>
         </div>
         <!----------Quick Link Section -----Start------->
         <div class=\"quick-link\">
            <div class=\"row\" style=\"margin-left: 0px;margin-right: 0px;\">
               <div class=\"col-lg-8 \">
                  <h1>Quick links</h1>
                  <div class=\"row\">
                     <div class=\"col-md-3 mob_bottom_margin\">
                        <div class=\"quick-link-sub-sec\">
                           <i class=\"fal fa-calendar-alt\"></i>
                           <p>My Scheduled <br>
                              Assessments
                           </p>
                        </div>
                     </div>
                     <div class=\"col-md-3 mob_bottom_margin\">
                        <div class=\"quick-link-sub-sec\">
                           <i class=\"far fa-chart-bar\"></i>
                           <p>My Assessment <br>
                              Stats
                           </p>
                        </div>
                     </div>
                     <div class=\"col-md-3 mob_bottom_margin\">
                           <a href='/bfssAthleteProfile' class=\"atheltic_link\">
                              <div class=\"quick-link-sub-sec\">
                           <i class=\"far fa-chart-network\"></i>
                              <p>Athletic Profile</p>
                        </div>
                           </a>
                     </div>
                     <div class=\"col-md-3\">
                        <div class=\"quick-link-sub-sec\">
                           <i class=\"fas fa-lightbulb-on\"></i>
                           <p>Frequently Asked<br>
                              Questions
                           </p>
                        </div>
                     </div>
                  </div>
               </div>
               <div class=\"col-lg-4\">
                  <div class=\"my-assessment\">
                     <button class=\"accordion\">MY ASSESSMENTS</button>
                     <div class=\"panel\">
                        <h1>Elite Assessment</h1>
                        <p style=\"color:#115719;\">December 29,2019</p>
                        <h1 style=\"color:#f76907;\">Starter Assessment</h1>
                        <p>September 29,2019</p>
                        <h1 style=\"color:#f76907;\">Starter Assessment</h1>
                        <p>March 29,2019</p>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <!----------Quick Link Section -----End-------> 
         <!----------Tabs Section -----Start------->
         <div class=\"tab-main-sec\">
            <div class=\"row\" style=\"margin-left: 0px;margin-right: 0px;\">
               <div class=\"col-lg-8 col-md-12\">
                  <div class=\"row\">
                     <div class=\"col-xl-9 col-lg-8 col-md-6 col-6\">
                        <h2 class=\"mb-0\"><i class=\"fal fa-angle-left mr-2\"></i><span class=\"month_year\">August 2019</span><i class=\"fal fa-angle-right ml-2\"></i></h2>
                        <p class=\"pl-4\">************</p>
                     </div>
                     <div class=\"col-xl-3 col-lg-4 col-md-6 col-6 search_icon\">
                      <a href=\"#\" class=\"pr-3\"> <i class=\"fas fa-search\"></i></a>
                        <input placeholder=\"month view\" type=\"text\" id=\"date-picker-example\" class=\"form-control datepicker mt-4\">
                     </div>
                  </div>
                  <div class=\"tab-nav\">
                     <ul class=\"mb-4\">
                        <li><a href=\"#\">Categories </a> </li>
                        <li><a href=\"#\">Tags </a></li>
                        <li><a href=\"#\">Organizers </a></li>
                        <li><a href=\"#\">Venues </a></li>
                     </ul>
                  </div>
                  <!-- ---------Profile Card---HTML----Start-->
               ";
        // line 135
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["assessments_block"] ?? null)), "html", null, true);
        echo "
          <!-- ---------Profile Card---HTML----End-->
          
               </div>
         
               <div class=\"col-md-1\"></div>
         <div class=\"col-md-3\"></div>
            </div>
         </div>
      </div>
   </div>
   <div class=\"container\">
      <div class=\"modal fade\" id=\"myModal\" role=\"dialog\">
         <div class=\"modal-dialog\">
            <div class=\"modal-content\">
               <div class=\"modal-header\">
                  <div class = \"modals-section\">
                     <div class = \"row\">
                        <div class = \"col-md-7 newzz\">
                           <span> <img src=\"/themes/custom/bfss_custom/images/images-dashboard/better-image.png\"></span>
                        </div>
                        <div class = \"col-md-5 newzz\">
                           <div class = \"modals-bg\">
                              <h4>Aug<br> 04</h4>
                              <h1>Atheltic Profile Assessments</h1>
                              <p> Williams field high school</p>
                           </div>
                        </div>
                        <div class=\"modal-body\">
                           <p>Some text in the modal.</p>
                        </div>
                        <div class=\"modal-footer\">
                           <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Close</button>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class=\"modal\" id=\"athelets-modal\">
  <div class=\"modal-dialog\">
    <div class=\"modal-content\">

      <!-- Modal Header -->
      <!-- <div class=\"modal-header\">
        <button type=\"button\" class=\"close\" data-dismiss=\"modal\">&times;</button>
      </div> -->

      <!-- Modal body -->
      <div class=\"modal-body\">
\t\t<div class=\"row\">
\t\t\t<div class=\"col-md-12 pb-2\">
\t\t\t\t<p>Welcome Jodi, to continue you must complete all the required fields below.</p>
\t\t\t</div>
\t\t</div>
\t\t\t<div class=\"row\">
\t\t\t\t<div class=\"col-md-8\">
\t\t\t\t\t<div id=\"accordion\">
\t\t\t\t\t\t<div class=\"card\">
\t\t\t\t\t\t\t<div class=\"card-header\">
\t\t\t\t\t\t\t  <a class=\"card-link d-flex justify-content-between\" data-toggle=\"collapse\" href=\"#collapseOne\">
\t\t\t\t\t\t\t\t<span><i class=\"fas fa-minus text-orange pr-2\"></i>ATHLETE'S INFORMATION</span>
\t\t\t\t\t\t\t\t<i class=\"fas fa-info text-orange\"></i>
\t\t\t\t\t\t\t  </a>
\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t\t<div id=\"collapseOne\" class=\"collapse show\" data-parent=\"#accordion\">
\t\t\t\t\t\t\t  <div class=\"card-body py-4 my-2\">
\t\t\t\t\t\t\t\t<div class=\"form-group\">
\t\t\t\t\t\t\t\t\t<input type=\"text\" class=\"form-control\" placeholder=\"Jodi\" disabled>
\t\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t\t\t<div class=\"form-group\">
\t\t\t\t\t\t\t\t\t<input type=\"text\" class=\"form-control\" placeholder=\"Bloggs\" disabled>
\t\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t\t\t<div class=\"form-group\">
\t\t\t\t\t\t\t\t\t<select class=\"form-control\" id=\"exampleFormControlSelect1\">
\t\t\t\t\t\t\t\t\t  <option>AZ</option>
\t\t\t\t\t\t\t\t\t  <option>2</option>
\t\t\t\t\t\t\t\t\t  <option>3</option>
\t\t\t\t\t\t\t\t\t  <option>4</option>
\t\t\t\t\t\t\t\t\t  <option>5</option>
\t\t\t\t\t\t\t\t\t</select>
\t\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t\t\t<div class=\"form-group\">
\t\t\t\t\t\t\t\t\t<input type=\"number\" class=\"form-control\" placeholder=\"Qty\">
\t\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t\t\t<div class=\"form-group\">
\t\t\t\t\t\t\t\t\t<select class=\"form-control\" id=\"exampleFormControlSelect2\">
\t\t\t\t\t\t\t\t\t  <option>Birth Gender</option>
\t\t\t\t\t\t\t\t\t  <option>2</option>
\t\t\t\t\t\t\t\t\t  <option>3</option>
\t\t\t\t\t\t\t\t\t  <option>4</option>
\t\t\t\t\t\t\t\t\t  <option>5</option>
\t\t\t\t\t\t\t\t\t</select>
\t\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t\t\t<div class=\"form-group\">
\t\t\t\t\t\t\t\t\t<input type=\"number\" class=\"form-control\" placeholder=\"Height In Inches\">
\t\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t\t\t<div class=\"form-group\">
\t\t\t\t\t\t\t\t\t<input type=\"number\" class=\"form-control\" placeholder=\"Weight In Pounds\">
\t\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t  </div>
\t\t\t\t\t\t</div>
\t\t\t\t\t  <div class=\"card\">
\t\t\t\t\t\t<div class=\"card-header\">
\t\t\t\t\t\t  <a class=\"collapsed card-link d-flex justify-content-between\" data-toggle=\"collapse\" href=\"#collapseTwo\">
\t\t\t\t\t\t\t<span><i class=\"fas fa-minus text-orange pr-2\"></i>SCHOOL/CLUB/UNIVERSITY</span>
\t\t\t\t\t\t\t<i class=\"far fa-trash-alt text-orange\"></i>
\t\t\t\t\t\t  </a>
\t\t\t\t\t\t</div>
\t\t\t\t\t\t<div id=\"collapseTwo\" class=\"collapse show\">
\t\t\t\t\t\t  <div class=\"card-body py-4 my-2\">
\t\t\t\t\t\t\t<div class=\"form-group\">
\t\t\t\t\t\t\t\t<select class=\"form-control\" id=\"exampleFormControlSelect3\">
\t\t\t\t\t\t\t\t  <option>Organization Type</option>
\t\t\t\t\t\t\t\t  <option>2</option>
\t\t\t\t\t\t\t\t  <option>3</option>
\t\t\t\t\t\t\t\t  <option>4</option>
\t\t\t\t\t\t\t\t  <option>5</option>
\t\t\t\t\t\t\t\t</select>
\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t\t<div class=\"form-group\">
\t\t\t\t\t\t\t\t<select class=\"form-control\" id=\"exampleFormControlSelect4\">
\t\t\t\t\t\t\t\t  <option>Organization Name</option>
\t\t\t\t\t\t\t\t  <option>2</option>
\t\t\t\t\t\t\t\t  <option>3</option>
\t\t\t\t\t\t\t\t  <option>4</option>
\t\t\t\t\t\t\t\t  <option>5</option>
\t\t\t\t\t\t\t\t</select>
\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t\t<div class=\"form-group\">
\t\t\t\t\t\t\t\t\t<input type=\"text\" class=\"form-control\" placeholder=\"Coach's Last Name(Optional)\">
\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t\t<div class=\"form-group\">
\t\t\t\t\t\t\t\t<input type=\"text\" class=\"form-control\" placeholder=\"Sport\">
\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t\t<div class=\"form-group\">
\t\t\t\t\t\t\t\t<input type=\"text\" class=\"form-control\" placeholder=\"Position\">
\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t\t<button type=\"button\" class=\"bg-transparent border-0 text-orange\">+ Additional Position</button>
\t\t\t\t\t\t\t
\t\t\t\t\t\t  </div>
\t\t\t\t\t\t</div>
\t\t\t\t\t  </div>
\t\t\t\t\t</div>
\t\t\t\t</div>
\t\t\t\t<div class=\"col-md-4\">
\t\t\t\t\t<div class=\"card\">
\t\t\t\t\t\t<div class=\"card-header\">
\t\t\t\t\t\t  <a class=\"collapsed card-link d-flex justify-content-between\" data-toggle=\"collapse\" href=\"#collapseThree\">
\t\t\t\t\t\t\t<span><i class=\"fas fa-minus text-orange pr-2\"></i>SOCIAL MEDIA</span>
\t\t\t\t\t\t\t<i class=\"fas fa-info text-orange\"></i>
\t\t\t\t\t\t  </a>
\t\t\t\t\t\t</div>
\t\t\t\t\t\t<div id=\"collapseThree\" class=\"collapse show\">
\t\t\t\t\t\t\t<div class=\"card-body py-4 my-2\">
\t\t\t\t\t\t\t\t<div class=\"form-group\">
\t\t\t\t\t\t\t\t\t<input type=\"text\" class=\"form-control\" placeholder=\"Your Instagram Account(Optional)\">
\t\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t\t\t<div class=\"form-group\">
\t\t\t\t\t\t\t\t\t<input type=\"text\" class=\"form-control\" placeholder=\"Your Youtube/Vimeo Channel(Optional)\">
\t\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t</div>
\t\t\t\t\t  </div>
\t\t\t\t</div>
\t\t\t</div>\t
\t\t</div>
      <!-- Modal footer -->
      <!-- <div class=\"modal-footer\">
        <button type=\"button\" class=\"btn btn-danger\" data-dismiss=\"modal\">Close</button>
      </div> -->

    </div>
  </div>
   <!-----Model_End------>
   <script>
      var acc = document.getElementsByClassName(\"accordion\");
      var i;
      
      for (i = 0; i < acc.length; i++) {
       acc[i].addEventListener(\"click\", function() {
         this.classList.toggle(\"active\");
         var panel = this.nextElementSibling;
         if (panel.style.display === \"none\") {
           panel.style.display = \"block\";
         } else {
           panel.style.display = \"none\";
         }
       });
      }
      
   </script>
</body>
</html>";
    }

    public function getTemplateName()
    {
        return "modules/custom/acme/templates/hello.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  191 => 135,  55 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source(" 
</html>
<html>   
<meta charset=\"utf-8\">
<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
<link rel=\"stylesheet\" href=\"/themes/custom/bfss_custom/css/style-custom.css\">
<link href=\"https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i&display=swap\" rel=\"stylesheet\">
<link rel=\"stylesheet\" href=\"https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css\">
<link rel=\"stylesheet\" href=\"https://pro.fontawesome.com/releases/v5.8.2/css/all.css\">
<script src=\"https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js\"></script>
<script src=\"https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js\"></script>
<script src=\"https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js\"></script>
</head>
<script type=\"text/javascript\">
   \$(document).ready(function(){
   \$('.dropdown > a').click(function(e){
     e.preventDefault();
     e.stopPropagation();
     \$(this).siblings('.dropdown-menu').toggleClass('open');
     \$(this).find('.down-arrow').toggleClass('open-caret');

   });

   \$(document).click(function(){
       \$('.dropdown-menu').removeClass('open');
       \$('.down-arrow').removeClass('open-caret');  
     });
   });
   
   function openForm() {
   document.getElementById(\"myForm\").style.display = \"block\";
   }
   
   function closeForm() {
   document.getElementById(\"myForm\").style.display = \"none\";
   }
   
   
</script>
<body>
   
   </div>
   <div class=\"row\" style=\"margin-left: 0px;margin-right: 0px;\">
      <!-- ------------Side Navbar----------start -->

     
      <!-- ------------Side Navbar----------end -->
      <div class=\"right_side_section pl-0 pr-0\">
         <div class=\"dash-main-right\">
            <h1><i class=\"fas fa-home\"></i> > Dashboard </h1>
            <div class=\"dash-sub-main\">
               <img src=\"/themes/custom/bfss_custom/images/images-dashboard/dash-img.png\">  
               <h2><span>ALL FEATURES</span><br>Dashboard  </h2>
            </div>
         </div>
         <!----------Quick Link Section -----Start------->
         <div class=\"quick-link\">
            <div class=\"row\" style=\"margin-left: 0px;margin-right: 0px;\">
               <div class=\"col-lg-8 \">
                  <h1>Quick links</h1>
                  <div class=\"row\">
                     <div class=\"col-md-3 mob_bottom_margin\">
                        <div class=\"quick-link-sub-sec\">
                           <i class=\"fal fa-calendar-alt\"></i>
                           <p>My Scheduled <br>
                              Assessments
                           </p>
                        </div>
                     </div>
                     <div class=\"col-md-3 mob_bottom_margin\">
                        <div class=\"quick-link-sub-sec\">
                           <i class=\"far fa-chart-bar\"></i>
                           <p>My Assessment <br>
                              Stats
                           </p>
                        </div>
                     </div>
                     <div class=\"col-md-3 mob_bottom_margin\">
                           <a href='/bfssAthleteProfile' class=\"atheltic_link\">
                              <div class=\"quick-link-sub-sec\">
                           <i class=\"far fa-chart-network\"></i>
                              <p>Athletic Profile</p>
                        </div>
                           </a>
                     </div>
                     <div class=\"col-md-3\">
                        <div class=\"quick-link-sub-sec\">
                           <i class=\"fas fa-lightbulb-on\"></i>
                           <p>Frequently Asked<br>
                              Questions
                           </p>
                        </div>
                     </div>
                  </div>
               </div>
               <div class=\"col-lg-4\">
                  <div class=\"my-assessment\">
                     <button class=\"accordion\">MY ASSESSMENTS</button>
                     <div class=\"panel\">
                        <h1>Elite Assessment</h1>
                        <p style=\"color:#115719;\">December 29,2019</p>
                        <h1 style=\"color:#f76907;\">Starter Assessment</h1>
                        <p>September 29,2019</p>
                        <h1 style=\"color:#f76907;\">Starter Assessment</h1>
                        <p>March 29,2019</p>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <!----------Quick Link Section -----End-------> 
         <!----------Tabs Section -----Start------->
         <div class=\"tab-main-sec\">
            <div class=\"row\" style=\"margin-left: 0px;margin-right: 0px;\">
               <div class=\"col-lg-8 col-md-12\">
                  <div class=\"row\">
                     <div class=\"col-xl-9 col-lg-8 col-md-6 col-6\">
                        <h2 class=\"mb-0\"><i class=\"fal fa-angle-left mr-2\"></i><span class=\"month_year\">August 2019</span><i class=\"fal fa-angle-right ml-2\"></i></h2>
                        <p class=\"pl-4\">************</p>
                     </div>
                     <div class=\"col-xl-3 col-lg-4 col-md-6 col-6 search_icon\">
                      <a href=\"#\" class=\"pr-3\"> <i class=\"fas fa-search\"></i></a>
                        <input placeholder=\"month view\" type=\"text\" id=\"date-picker-example\" class=\"form-control datepicker mt-4\">
                     </div>
                  </div>
                  <div class=\"tab-nav\">
                     <ul class=\"mb-4\">
                        <li><a href=\"#\">Categories </a> </li>
                        <li><a href=\"#\">Tags </a></li>
                        <li><a href=\"#\">Organizers </a></li>
                        <li><a href=\"#\">Venues </a></li>
                     </ul>
                  </div>
                  <!-- ---------Profile Card---HTML----Start-->
               {{ assessments_block }}
          <!-- ---------Profile Card---HTML----End-->
          
               </div>
         
               <div class=\"col-md-1\"></div>
         <div class=\"col-md-3\"></div>
            </div>
         </div>
      </div>
   </div>
   <div class=\"container\">
      <div class=\"modal fade\" id=\"myModal\" role=\"dialog\">
         <div class=\"modal-dialog\">
            <div class=\"modal-content\">
               <div class=\"modal-header\">
                  <div class = \"modals-section\">
                     <div class = \"row\">
                        <div class = \"col-md-7 newzz\">
                           <span> <img src=\"/themes/custom/bfss_custom/images/images-dashboard/better-image.png\"></span>
                        </div>
                        <div class = \"col-md-5 newzz\">
                           <div class = \"modals-bg\">
                              <h4>Aug<br> 04</h4>
                              <h1>Atheltic Profile Assessments</h1>
                              <p> Williams field high school</p>
                           </div>
                        </div>
                        <div class=\"modal-body\">
                           <p>Some text in the modal.</p>
                        </div>
                        <div class=\"modal-footer\">
                           <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Close</button>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class=\"modal\" id=\"athelets-modal\">
  <div class=\"modal-dialog\">
    <div class=\"modal-content\">

      <!-- Modal Header -->
      <!-- <div class=\"modal-header\">
        <button type=\"button\" class=\"close\" data-dismiss=\"modal\">&times;</button>
      </div> -->

      <!-- Modal body -->
      <div class=\"modal-body\">
\t\t<div class=\"row\">
\t\t\t<div class=\"col-md-12 pb-2\">
\t\t\t\t<p>Welcome Jodi, to continue you must complete all the required fields below.</p>
\t\t\t</div>
\t\t</div>
\t\t\t<div class=\"row\">
\t\t\t\t<div class=\"col-md-8\">
\t\t\t\t\t<div id=\"accordion\">
\t\t\t\t\t\t<div class=\"card\">
\t\t\t\t\t\t\t<div class=\"card-header\">
\t\t\t\t\t\t\t  <a class=\"card-link d-flex justify-content-between\" data-toggle=\"collapse\" href=\"#collapseOne\">
\t\t\t\t\t\t\t\t<span><i class=\"fas fa-minus text-orange pr-2\"></i>ATHLETE'S INFORMATION</span>
\t\t\t\t\t\t\t\t<i class=\"fas fa-info text-orange\"></i>
\t\t\t\t\t\t\t  </a>
\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t\t<div id=\"collapseOne\" class=\"collapse show\" data-parent=\"#accordion\">
\t\t\t\t\t\t\t  <div class=\"card-body py-4 my-2\">
\t\t\t\t\t\t\t\t<div class=\"form-group\">
\t\t\t\t\t\t\t\t\t<input type=\"text\" class=\"form-control\" placeholder=\"Jodi\" disabled>
\t\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t\t\t<div class=\"form-group\">
\t\t\t\t\t\t\t\t\t<input type=\"text\" class=\"form-control\" placeholder=\"Bloggs\" disabled>
\t\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t\t\t<div class=\"form-group\">
\t\t\t\t\t\t\t\t\t<select class=\"form-control\" id=\"exampleFormControlSelect1\">
\t\t\t\t\t\t\t\t\t  <option>AZ</option>
\t\t\t\t\t\t\t\t\t  <option>2</option>
\t\t\t\t\t\t\t\t\t  <option>3</option>
\t\t\t\t\t\t\t\t\t  <option>4</option>
\t\t\t\t\t\t\t\t\t  <option>5</option>
\t\t\t\t\t\t\t\t\t</select>
\t\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t\t\t<div class=\"form-group\">
\t\t\t\t\t\t\t\t\t<input type=\"number\" class=\"form-control\" placeholder=\"Qty\">
\t\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t\t\t<div class=\"form-group\">
\t\t\t\t\t\t\t\t\t<select class=\"form-control\" id=\"exampleFormControlSelect2\">
\t\t\t\t\t\t\t\t\t  <option>Birth Gender</option>
\t\t\t\t\t\t\t\t\t  <option>2</option>
\t\t\t\t\t\t\t\t\t  <option>3</option>
\t\t\t\t\t\t\t\t\t  <option>4</option>
\t\t\t\t\t\t\t\t\t  <option>5</option>
\t\t\t\t\t\t\t\t\t</select>
\t\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t\t\t<div class=\"form-group\">
\t\t\t\t\t\t\t\t\t<input type=\"number\" class=\"form-control\" placeholder=\"Height In Inches\">
\t\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t\t\t<div class=\"form-group\">
\t\t\t\t\t\t\t\t\t<input type=\"number\" class=\"form-control\" placeholder=\"Weight In Pounds\">
\t\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t  </div>
\t\t\t\t\t\t</div>
\t\t\t\t\t  <div class=\"card\">
\t\t\t\t\t\t<div class=\"card-header\">
\t\t\t\t\t\t  <a class=\"collapsed card-link d-flex justify-content-between\" data-toggle=\"collapse\" href=\"#collapseTwo\">
\t\t\t\t\t\t\t<span><i class=\"fas fa-minus text-orange pr-2\"></i>SCHOOL/CLUB/UNIVERSITY</span>
\t\t\t\t\t\t\t<i class=\"far fa-trash-alt text-orange\"></i>
\t\t\t\t\t\t  </a>
\t\t\t\t\t\t</div>
\t\t\t\t\t\t<div id=\"collapseTwo\" class=\"collapse show\">
\t\t\t\t\t\t  <div class=\"card-body py-4 my-2\">
\t\t\t\t\t\t\t<div class=\"form-group\">
\t\t\t\t\t\t\t\t<select class=\"form-control\" id=\"exampleFormControlSelect3\">
\t\t\t\t\t\t\t\t  <option>Organization Type</option>
\t\t\t\t\t\t\t\t  <option>2</option>
\t\t\t\t\t\t\t\t  <option>3</option>
\t\t\t\t\t\t\t\t  <option>4</option>
\t\t\t\t\t\t\t\t  <option>5</option>
\t\t\t\t\t\t\t\t</select>
\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t\t<div class=\"form-group\">
\t\t\t\t\t\t\t\t<select class=\"form-control\" id=\"exampleFormControlSelect4\">
\t\t\t\t\t\t\t\t  <option>Organization Name</option>
\t\t\t\t\t\t\t\t  <option>2</option>
\t\t\t\t\t\t\t\t  <option>3</option>
\t\t\t\t\t\t\t\t  <option>4</option>
\t\t\t\t\t\t\t\t  <option>5</option>
\t\t\t\t\t\t\t\t</select>
\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t\t<div class=\"form-group\">
\t\t\t\t\t\t\t\t\t<input type=\"text\" class=\"form-control\" placeholder=\"Coach's Last Name(Optional)\">
\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t\t<div class=\"form-group\">
\t\t\t\t\t\t\t\t<input type=\"text\" class=\"form-control\" placeholder=\"Sport\">
\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t\t<div class=\"form-group\">
\t\t\t\t\t\t\t\t<input type=\"text\" class=\"form-control\" placeholder=\"Position\">
\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t\t<button type=\"button\" class=\"bg-transparent border-0 text-orange\">+ Additional Position</button>
\t\t\t\t\t\t\t
\t\t\t\t\t\t  </div>
\t\t\t\t\t\t</div>
\t\t\t\t\t  </div>
\t\t\t\t\t</div>
\t\t\t\t</div>
\t\t\t\t<div class=\"col-md-4\">
\t\t\t\t\t<div class=\"card\">
\t\t\t\t\t\t<div class=\"card-header\">
\t\t\t\t\t\t  <a class=\"collapsed card-link d-flex justify-content-between\" data-toggle=\"collapse\" href=\"#collapseThree\">
\t\t\t\t\t\t\t<span><i class=\"fas fa-minus text-orange pr-2\"></i>SOCIAL MEDIA</span>
\t\t\t\t\t\t\t<i class=\"fas fa-info text-orange\"></i>
\t\t\t\t\t\t  </a>
\t\t\t\t\t\t</div>
\t\t\t\t\t\t<div id=\"collapseThree\" class=\"collapse show\">
\t\t\t\t\t\t\t<div class=\"card-body py-4 my-2\">
\t\t\t\t\t\t\t\t<div class=\"form-group\">
\t\t\t\t\t\t\t\t\t<input type=\"text\" class=\"form-control\" placeholder=\"Your Instagram Account(Optional)\">
\t\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t\t\t<div class=\"form-group\">
\t\t\t\t\t\t\t\t\t<input type=\"text\" class=\"form-control\" placeholder=\"Your Youtube/Vimeo Channel(Optional)\">
\t\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t</div>
\t\t\t\t\t  </div>
\t\t\t\t</div>
\t\t\t</div>\t
\t\t</div>
      <!-- Modal footer -->
      <!-- <div class=\"modal-footer\">
        <button type=\"button\" class=\"btn btn-danger\" data-dismiss=\"modal\">Close</button>
      </div> -->

    </div>
  </div>
   <!-----Model_End------>
   <script>
      var acc = document.getElementsByClassName(\"accordion\");
      var i;
      
      for (i = 0; i < acc.length; i++) {
       acc[i].addEventListener(\"click\", function() {
         this.classList.toggle(\"active\");
         var panel = this.nextElementSibling;
         if (panel.style.display === \"none\") {
           panel.style.display = \"block\";
         } else {
           panel.style.display = \"none\";
         }
       });
      }
      
   </script>
</body>
</html>", "modules/custom/acme/templates/hello.html.twig", "/var/www/bfss.mindimage.net/web/modules/custom/acme/templates/hello.html.twig");
    }
}
