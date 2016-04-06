<?php 

namespace Ixmanuel\OpenCreation\Model;

interface Packages
{
   /**
    * Aggregate constructor.
    *
    * @param string $model
    * @param string $object
    *
    * @return Self
    */
    public function use(string $model, string $object);

   /**
    * Aggregate constructor. Alias of use.
    *
    * @param string $model
    * @param string $object
    */
    public function require(string $model, string $object);    

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