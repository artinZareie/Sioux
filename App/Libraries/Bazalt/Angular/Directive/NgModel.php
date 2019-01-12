<?php

namespace App\Libraries\Bazalt\Angular\Directive;

class NgModel extends \App\Libraries\Bazalt\Angular\Directive
{
    public function apply()
    {
        $attrs = $this->attributes();
        $attrValue = $attrs['ng-model'];

        $this->element->setAttribute('value', $this->scope[$attrValue]);
    }
}