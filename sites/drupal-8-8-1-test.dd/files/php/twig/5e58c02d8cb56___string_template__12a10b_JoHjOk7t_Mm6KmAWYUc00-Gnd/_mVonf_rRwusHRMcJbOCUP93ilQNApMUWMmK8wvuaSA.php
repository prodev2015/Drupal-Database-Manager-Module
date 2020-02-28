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

/* __string_template__12a10bb83209fae1f40ee2a329aca5e45be6db535043e3152e56a90540414900 */
class __TwigTemplate_5619f2773faf12563f8927fee2ec83c2c98c5f22e42f8a78d6b9d3500a5fe870 extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = [
        ];
        $this->sandbox = $this->env->getExtension('\Twig\Extension\SandboxExtension');
        $tags = ["set" => 8, "trans" => 20];
        $filters = ["escape" => 7];
        $functions = ["attach_library" => 7, "path" => 8];

        try {
            $this->sandbox->checkSecurity(
                ['set', 'trans'],
                ['escape'],
                ['attach_library', 'path']
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
        $__internal_b8a44bb7188f10fa054f3681425c559c29de95cd0490f5c67a67412aafc0f453 = $this->env->getExtension("Drupal\\webprofiler\\Twig\\Extension\\ProfilerExtension");
        $__internal_b8a44bb7188f10fa054f3681425c559c29de95cd0490f5c67a67412aafc0f453->enter($__internal_b8a44bb7188f10fa054f3681425c559c29de95cd0490f5c67a67412aafc0f453_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "__string_template__12a10bb83209fae1f40ee2a329aca5e45be6db535043e3152e56a90540414900"));

        // line 6
        echo "
";
        // line 7
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->env->getExtension('Drupal\Core\Template\TwigExtension')->attachLibrary("core/drupal.dialog.ajax"), "html", null, true);
        echo "
";
        // line 8
        $context["simple_form"] = $this->env->getExtension('Drupal\Core\Template\TwigExtension')->getPath("form_api_example.simple_form");
        // line 9
        $context["multistep_form"] = $this->env->getExtension('Drupal\Core\Template\TwigExtension')->getPath("form_api_example.multistep_form");
        // line 10
        $context["input_demo"] = $this->env->getExtension('Drupal\Core\Template\TwigExtension')->getPath("form_api_example.input_demo");
        // line 11
        $context["build_demo"] = $this->env->getExtension('Drupal\Core\Template\TwigExtension')->getPath("form_api_example.build_demo");
        // line 12
        $context["container_demo"] = $this->env->getExtension('Drupal\Core\Template\TwigExtension')->getPath("form_api_example.container_demo");
        // line 13
        $context["state_demo"] = $this->env->getExtension('Drupal\Core\Template\TwigExtension')->getPath("form_api_example.state_demo");
        // line 14
        $context["vertical_tabs_demo"] = $this->env->getExtension('Drupal\Core\Template\TwigExtension')->getPath("form_api_example.vertical_tabs_demo");
        // line 15
        $context["ajax_demo"] = $this->env->getExtension('Drupal\Core\Template\TwigExtension')->getPath("form_api_example.ajax_color_demo");
        // line 16
        $context["ajax_addmore"] = $this->env->getExtension('Drupal\Core\Template\TwigExtension')->getPath("form_api_example.ajax_addmore");
        // line 17
        $context["modal_form"] = $this->env->getExtension('Drupal\Core\Template\TwigExtension')->getPath("form_api_example.modal_form", ["nojs" => "nojs"]);
        // line 18
        $context["block_admin"] = $this->env->getExtension('Drupal\Core\Template\TwigExtension')->getPath("block.admin_display");
        // line 19
        echo "
";
        // line 20
        echo t("<p>Form examples to demonstrate common UI solutions using the Drupal Form API</p>
<p><a href=@simple_form>Simple form</a></p>
<p><a href=@multistep_form>Multistep form</a></p>
<p><a href=@input_demo>Common input elements</a></p>
<p><a href=@build_demo>Build form demo</a></p>
<p><a href=@container_demo>Container elements</a></p>
<p><a href=@state_demo>Form state binding</a></p>
<p><a href=@vertical_tabs_demo>Vertical tab elements</a></p>
<p><a href=@ajax_demo>Ajax form</a></p>
<p><a href=@ajax_addmore>Add-more button</a></p>
<p><a href=@modal_form>Modal form</a></p>

<p>This module also provides a block, \"Example: Display a form\" that
    demonstrates how to display a form in a block. This same technique can be
    used whenever you need to display a form that is not the primary content of
    a page. You can enable it on your site <a href=@block_admin>using the
    block admin page</a>.</p>", array("@simple_form" =>         // line 22
($context["simple_form"] ?? null), "@multistep_form" =>         // line 23
($context["multistep_form"] ?? null), "@input_demo" =>         // line 24
($context["input_demo"] ?? null), "@build_demo" =>         // line 25
($context["build_demo"] ?? null), "@container_demo" =>         // line 26
($context["container_demo"] ?? null), "@state_demo" =>         // line 27
($context["state_demo"] ?? null), "@vertical_tabs_demo" =>         // line 28
($context["vertical_tabs_demo"] ?? null), "@ajax_demo" =>         // line 29
($context["ajax_demo"] ?? null), "@ajax_addmore" =>         // line 30
($context["ajax_addmore"] ?? null), "@modal_form" =>         // line 31
($context["modal_form"] ?? null), "@block_admin" =>         // line 36
($context["block_admin"] ?? null), ));
        
        $__internal_b8a44bb7188f10fa054f3681425c559c29de95cd0490f5c67a67412aafc0f453->leave($__internal_b8a44bb7188f10fa054f3681425c559c29de95cd0490f5c67a67412aafc0f453_prof);

    }

    public function getTemplateName()
    {
        return "__string_template__12a10bb83209fae1f40ee2a329aca5e45be6db535043e3152e56a90540414900";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  117 => 36,  116 => 31,  115 => 30,  114 => 29,  113 => 28,  112 => 27,  111 => 26,  110 => 25,  109 => 24,  108 => 23,  107 => 22,  90 => 20,  87 => 19,  85 => 18,  83 => 17,  81 => 16,  79 => 15,  77 => 14,  75 => 13,  73 => 12,  71 => 11,  69 => 10,  67 => 9,  65 => 8,  61 => 7,  58 => 6,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "__string_template__12a10bb83209fae1f40ee2a329aca5e45be6db535043e3152e56a90540414900", "");
    }
}
