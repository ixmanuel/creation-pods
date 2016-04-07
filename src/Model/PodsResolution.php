<?php 

namespace Ixmanuel\OpenCreation\Model;

interface PodsResolution
{
   /**
    * Aggregate constructor.
    *
    * @param string $model
    * @param string $object
    *
    * @return Self
    */
    public function use(string $model, string $object) : PodsResolution;

   /**
    * Aggregate constructor. Alias of use.
    *
    * @param string $model
    * @param string $object
    *
    * @return Self
    */
    public function require(string $model, string $object) : PodsResolution;  

    /**
     * It creates new objects defined in this class.
     * 
     * @param string $model
     * @param array $args
     *
     * @return mixed object
     */
    public function new(string $model, ...$args);	
}