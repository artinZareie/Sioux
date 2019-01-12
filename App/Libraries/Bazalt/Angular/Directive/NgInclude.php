<?php

namespace App\Libraries\Bazalt\Angular\Directive;

use App\Libraries\A;

class NgInclude extends \App\Libraries\Bazalt\Angular\Directive
{
    public function apply()
    {
        $attrs = $this->attributes();
        $attrValue = trim($attrs['ng-include'], ' \'');
        $this->element->removeAttribute('ng-include');

        $options = $this->module->options();
        $filename = $options['dir'] . $attrValue;

        $fragment = file_get_contents($filename);

        $frag = $this->element->ownerDocument->createDocumentFragment();
        $frag->appendXML($fragment);

        $this->element->appendChild($frag);

        $nodes = [ $this->module->parser->parse($frag, $this->scope) ];
        $this->node->nodes($nodes);
    }
}
