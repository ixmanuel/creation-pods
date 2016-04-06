<?php 

namespace Ixmanuel\OpenCreation;

/**
 * The MIT License (MIT)
 *
 * Copyright (c) 2016 ixmanuel
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included
 * in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NON-INFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */
class Pod implements Model\OpenCreation
{

    /*
    |--------------------------------------------------------------------------
    | Open creation for open dependencies = OO Creations
    |--------------------------------------------------------------------------
    |   
    | Its mission is to avoid "new" in the methods. Only convenience 
    | constructors can make use of the "new" operator, but the 
    | implementations are cumbersome:
    |
    |   new AnyClass($param1, [
    |     InterfaceA::class => function(int $p1, $p2) {
    |		return new A($p1, $p2);
    |	  },
    |     InterfaceB::class => function(Protocol $p1) {
    |		return new B($p1);
    |	  }
    |	]);
    |
    | And can be used as follows: 
    |
    | 	$this->mother[InterfaceName::class]($arg1, $arg2); or
    |   $this->mother{InterfaceName::class}($arg1, $arg2);
    |
    | It is non-orthodox but is the best solution: You don't loose the 
    | interface checking for the args. Thus, you can use this 
    | approach.
    | 
    | 
    | But, if you want something less complex and more 
    | intuitive. You can use OpenCreation:
    |
    | 	$this->mother->new(InterfaceName::class, $arg1, $arg2);
    |
    | It looks just less complex, readable, encapsulated 
    | and, doesn't use any magic, nor reflection.
    |
    */


    /**
     * @var array [interfaceName:closure]
     */
    private $dependencies;

    /**
     * @param array $dependenciesAsClosures
     */
    public function __construct(array $dependenciesAsClosures)
    {
        $this->dependencies = $dependenciesAsClosures;
    }

    /**
     * Conveninece constructor.
     *
     * It expects: 
     * [
     *    [ModelA::class => ProductA::class],
     *    [ModelB::class => ProductB::class]
     * ];
     * 
     * @param array $dependencies [string:string]
     */
    public static function require(array $dependencies) : self
    {
        $dependenciesAsClosures = [];

        foreach ($dependencies as $model => $object) {
            $dependenciesAsClosures =
                array_merge(
                    $dependenciesAsClosures,
                    self::closureOf($model, $object)
                );
        }
        
        return new Self($dependenciesAsClosures);
    }    

   /**
    * Conveninece constructor.
    *
    * @param string $model
    * @param string $object
    */
    public static function requireOne(string $model, string $object) : self
    {    
        return self::require([$model => $object]);
    }


    /**
     * Supporting a convenience constructor. It maps the model and object 
     * parameters into a dictionary with model as key and a closure as
     * as value.
     *
     * @param string $model
     * @param string $object
     *
     * @return array
     */
    private static function closureOf(string $model, string $object) : array
    {
        return [$model => function (...$args) use ($object) {return new $object(...$args);}];
    }

    /**
     * It creates new objects defined in this class.
     * 
     * @param string $model
     * @param array $args
     *
     * @return mixed object
     */
    public function new(string $model, ...$args)
    {
        return $this->dependencies[$model](...$args);
    }
}
