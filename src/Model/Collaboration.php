<?php 

namespace Ixmanuel\Nexus\Model;

interface Collaboration
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