<?php 

namespace Ixmanuel\Nexus\Model;

interface Assigning
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