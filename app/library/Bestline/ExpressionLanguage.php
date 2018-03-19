<?php

namespace Bestline;

use Bestline\ExpressionLanguage\Functions;
class ExpressionLanguage extends \Symfony\Component\ExpressionLanguage\ExpressionLanguage
{
    protected function registerFunctions()
    {
        parent::registerFunctions();
    }
    
    public function evaluate($expression, $values = array())
    {
        $values += array('get' => new Functions());
        
        if(isset($values['height']) && !isset($values['length'])) {
            $values['length'] = $values['height'];
        }
        
        if(isset($values['length']) && !isset($values['height'])) {
            $values['height'] = $values['length'];
        }
        
        return parent::evaluate($expression, $values);
    }
}