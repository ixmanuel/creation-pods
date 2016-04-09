<?php 

namespace Ixmanuel\CreationPods;

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
final class Pods implements Model\CreationPods
{
    /*
    | ------------------------------------------------------------------
    | Pods
    | ------------------------------------------------------------------
    |
    | Alternative ways of use:
    |
    | new Pods(
    |       new Pod(ModelA::class, ProductA::class)
    |       new Pod(ModelB::class, ProductB::class)
    | )    
    |
    | (new Pods)
    |       ->define(ModelA::class, ProductA::class)
    |       ->define(ModelB::class, ProductB::class)   
    |
    */

    /**
     * @var array [string:\Ixmanuel\CreationPods\Model\DependencyCreation] 
     */
    private $pods;


    /**
     * @param \Ixmanuel\CreationPods\Model\DependencyCreation $pods
     */
    public function __construct(Model\DependencyCreation ...$pods)
    {
        $this->pods = self::toDictionary(...$pods);
    }

    /**
     * @param array $pods [\Ixamnuel\CreationPods\Model\DependencyCreation]
     *
     * @return array [string:\Ixamnuel\CreationPods\Model\DependencyCreation]
     */
    private static function toDictionary(Model\DependencyCreation ...$pods) : array
    {
        $dictionary = [];

        foreach ($pods as $pod) {
            $dictionary = array_merge($dictionary, $pod->toDictionary());
        }

        return $dictionary;
    }

   /**
    * Aggregate constructor.
    *
    * @param string $model
    * @param string $object
    *
    * @return Self
    */
    public function define(string $model, string $object) : Model\CreationPods
    {
        return new Self(
                ...array_merge(
                    array_values($this->pods),
                    [new Pod($model, $object)]
                )
        );
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
        return $this->pods[$model]->new(...$args);
    }
}
