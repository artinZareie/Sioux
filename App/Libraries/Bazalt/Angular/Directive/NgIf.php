<?php

namespace App\Libraries\Bazalt\Angular\Directive;

class NgIf extends \App\Libraries\Bazalt\Angular\Directive
{
    public function apply()
    {
        $attrs = $this->attributes();
        $attrValue = $attrs['ng-if'];

        $value = $this->scope->getValue($attrValue);
        $this->element->removeAttribute('ng-if');
        if (!$value) {
            $parent = $this->element->parentNode;
            $parent->removeChild($this->element);
        }
    }
}
