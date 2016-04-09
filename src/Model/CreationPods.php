<?php 

namespace Ixmanuel\CreationPods\Model;

interface CreationPods
{
   /**
    * Aggregate constructor. Alias of use.
    *
    * @param string $model
    * @param string $object
    *
    * @return Self
    */
    public function define(string $model, string $object) : CreationPods;  

    /**
     * It creates new objects defined in this class.
     * 
     * @param string $model
     * @param array $args
     *
     * @return mixed object
     */
    public function requireNew(string $model, ...$args);	
}