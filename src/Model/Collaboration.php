<?php 

namespace Ixmanuel\Nexus\Model;

interface Collaboration
{
    /**
     * It creates new objects defined in this class.
     * 
     * @param array $args
     *
     * @return mixed object
     */
    public function new(...$args);    	
}