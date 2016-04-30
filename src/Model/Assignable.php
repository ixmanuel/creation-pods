<?php 

namespace Ixmanuel\Nexus\Model;

interface Assignable
{
    /**
     * It wraps the new operator.
     * 
     * @param array $args
     *
     * @return mixed object
     */
    public function new(...$args);    	
}