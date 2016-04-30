<?php 

namespace Ixmanuel\Nexus;

use Exception;
use Ixmanuel\Nexus\Model\Assignable;

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
final class Assignment implements Assignable
{
    /**
     * @var string
     */
    private $contract;

    /**
     * @var string
     */
    private $object;

    /**
     * @param string $contract
     * @param string $object
     */
    public function __construct(string $contract, string $object)
    {
        $this->contract = $contract;

        $this->object = $object;   
    }  

    /**
     * Convenience constructors.
     *
     * @param string $constructor
     * @param array $args
     * @return mixed
     *
     * @throws \Exception
     */
    public function __call(string $constructor, array $args)
    {
        if (! method_exists($this->object, $constructor)) {
            throw new Exception(
                "Error Processing Request. The convenience constructor [{$constructor} doesn't exist.]", 
                1
            );
        }

        return $this->object::$constructor(...$args);
    }

    /**
     * It wraps the new operator.
     * 
     * @param array $args
     *
     * @return mixed object
     */
    public function new(...$args)
    {
        return new $this->object(...$args);
    }
}
