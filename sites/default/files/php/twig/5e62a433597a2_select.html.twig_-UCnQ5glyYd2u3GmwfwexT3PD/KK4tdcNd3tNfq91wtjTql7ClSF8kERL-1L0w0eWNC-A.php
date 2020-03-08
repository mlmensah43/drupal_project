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

/* themes/contrib/bootstrap_barrio/templates/form/select.html.twig */
class __TwigTemplate_2584e6e4aea0d9056c4467019793a4a5e4978d6ea44aa259bd538247948a0128 extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = [
        ];
        $this->sandbox = $this->env->getExtension('\Twig\Extension\SandboxExtension');
        $tags = ["set" => 14, "spaceless" => 20, "for" => 22, "if" => 23];
        $filters = ["escape" => 21];
        $functions = [];

        try {
            $this->sandbox->checkSecurity(
                ['set', 'spaceless', 'for', 'if'],
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
        // line 14
        $context["classes"] = [0 => (((        // line 15
($context["customtype"] ?? null) == "standard")) ? ("form-control") : ("")), 1 => (((        // line 16
($context["customtype"] ?? null) == "custom")) ? ("custom-select") : (""))];
        // line 19
        echo "
";
        // line 20
        ob_start();
        // line 21
        echo "  <select";
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->renderVar($this->env->getExtension('Drupal\flag\TwigExtension\FlagCount')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["attributes"] ?? null), "addClass", [0 => ($context["classes"] ?? null)], "method")), "html", null, true));
        echo ">
    ";
        // line 22
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["options"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["option"]) {
            // line 23
            echo "      ";
            if (($this->getAttribute($context["option"], "type", []) == "optgroup")) {
                // line 24
                echo "        <optgroup label=\"";
                echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->renderVar($this->env->getExtension('Drupal\flag\TwigExtension\FlagCount')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute($context["option"], "label", [])), "html", null, true));
                echo "\">
          ";
                // line 25
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable($this->getAttribute($context["option"], "options", []));
                foreach ($context['_seq'] as $context["_key"] => $context["sub_option"]) {
                    // line 26
                    echo "            <option value=\"";
                    echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->renderVar($this->env->getExtension('Drupal\flag\TwigExtension\FlagCount')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute($context["sub_option"], "value", [])), "html", null, true));
                    echo "\"";
                    echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->renderVar($this->env->getExtension('Drupal\Core\Template\TwigExtension')->renderVar((($this->getAttribute($context["sub_option"], "selected", [])) ? (" selected=\"selected\"") : (""))));
                    echo ">";
                    echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->renderVar($this->env->getExtension('Drupal\flag\TwigExtension\FlagCount')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute($context["sub_option"], "label", [])), "html", null, true));
                    echo "</option>
          ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['sub_option'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 28
                echo "        </optgroup>
      ";
            } elseif (($this->getAttribute(            // line 29
$context["option"], "type", []) == "option")) {
                // line 30
                echo "        <option value=\"";
                echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->renderVar($this->env->getExtension('Drupal\flag\TwigExtension\FlagCount')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute($context["option"], "value", [])), "html", null, true));
                echo "\"";
                echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->renderVar($this->env->getExtension('Drupal\Core\Template\TwigExtension')->renderVar((($this->getAttribute($context["option"], "selected", [])) ? (" selected=\"selected\"") : (""))));
                echo ">";
                echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->renderVar($this->env->getExtension('Drupal\flag\TwigExtension\FlagCount')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute($context["option"], "label", [])), "html", null, true));
                echo "</option>
      ";
            }
            // line 32
            echo "    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['option'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 33
        echo "  </select>
";
        echo trim(preg_replace('/>\s+</', '><', ob_get_clean()));
    }

    public function getTemplateName()
    {
        return "themes/contrib/bootstrap_barrio/templates/form/select.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  119 => 33,  113 => 32,  103 => 30,  101 => 29,  98 => 28,  85 => 26,  81 => 25,  76 => 24,  73 => 23,  69 => 22,  64 => 21,  62 => 20,  59 => 19,  57 => 16,  56 => 15,  55 => 14,);
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
 * Theme override for a select element.
 *
 * Available variables:
 * - attributes: HTML attributes for the select tag.
 * - options: The option element children.
 *
 * @see template_preprocess_select()
 */
#}
{%
  set classes = [
    customtype == 'standard' ? 'form-control',
    customtype == 'custom' ? 'custom-select',
  ]
%}

{% spaceless %}
  <select{{ attributes.addClass(classes) }}>
    {% for option in options %}
      {% if option.type == 'optgroup' %}
        <optgroup label=\"{{ option.label }}\">
          {% for sub_option in option.options %}
            <option value=\"{{ sub_option.value }}\"{{ sub_option.selected ? ' selected=\"selected\"' }}>{{ sub_option.label }}</option>
          {% endfor %}
        </optgroup>
      {% elseif option.type == 'option' %}
        <option value=\"{{ option.value }}\"{{ option.selected ? ' selected=\"selected\"' }}>{{ option.label }}</option>
      {% endif %}
    {% endfor %}
  </select>
{% endspaceless %}
", "themes/contrib/bootstrap_barrio/templates/form/select.html.twig", "/app/drupal-8.8.2/themes/contrib/bootstrap_barrio/templates/form/select.html.twig");
    }
}
