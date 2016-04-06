<?php 

namespace Ixmanuel\OpenCreation\Model;

interface PodResolution
{
   /**
    * Aggregate constructor.
    *
    * @param string $model
    * @param string $object
    *
    * @return Self
    */
    public function use(string $model, string $object) : PodResolution;

   /**
    * Aggregate constructor. Alias of use.
    *
    * @param string $model
    * @param string $object
    *
    * @return Self
    */
    public function require(string $model, string $object) : PodResolution;  

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