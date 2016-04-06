<?php 

namespace Ixmanuel\OpenCreation\Model;

interface DependencyCreation
{
    /**
     * @return array [string:this]
     */
    public function toDictionary() : array;

    /**
     * It creates new objects defined in this class.
     * 
     * @param array $args
     *
     * @return mixed object
     */
    public function new(...$args);    	
}