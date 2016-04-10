<?php

namespace Ixmanuel\Nexus;

class CollaborationTest extends \PHPUnit_Framework_TestCase
{
    /*
    |
    | The purpose of this solution is to define builders/services into classes.
    | Each builder will create an object that will be returned by a 
    | method of a client class in order to free methods from 
    | "new". The "new" operator will be used only in 
    | convenience constructors. Therefore it 
    | achives "dependency injection", term 
    | that can be re-oriented to "open 
    | dependency". Thus, it offers an 
    | unbreakable class without the 
    | need to create empty objects 
    | that self create fully 
    | identifiable objects.
    |
    */

    /** @test */
    public function it_works_with_two_collaborators_by_contract()
    {
        $client = new ClientObject(
            "Client Name",
            new WorkingWith(ModelA::class, ProductA::class),
            new WorkingWith(ModelB::class, PartB::class)
        );

        $test = new TestIdentity;
        $this->assertTrue($client->productA()->identification()['number'] == $test->id());
        $this->assertTrue($client->partB()->number() == $test->id());
    }



    /** @test */
    public function it_calls_collaborators_from_a_convenience_constructor()
    {
        $client = ClientObject::withName("Client Name");

        $test = new TestIdentity;
        $this->assertTrue($client->productA()->identification()['number'] == $test->id());
        $this->assertTrue($client->partB()->number() == $test->id());
    }  

    /** @test */
    public function it_tests_a_convenience_constructor_of_a_collaborator()
    {
        $client = new ClientObject(
            "Client Name",
            new WorkingWith(ModelA::class, ProductA::class),
            new WorkingWith(ModelB::class, PartB::class)
        );

        $test = new TestIdentity;
        $this->assertTrue($client->unamedProductA()->identification()['number'] == $test->id());
    }             
  

    /** @test */
    public function this_test_is_for_assessing_the_new_anonymous_class_in_php_7()
    {
        /// This is a raw implementation. You can use this in place of Nexus.
        $client = new ClientObjectAnonymous(
            "Client Name",
            new class {
                 public function product1(int $arg1, string $arg2)
                 {
                     return new ProductA($arg1, $arg2);
                 }

                 public function product2(int $arg1)
                 {
                     return new PartB($arg1);
                 }
             }
        );

        $this->assertTrue($client->model1()->identification()['number'] == 1);
        $this->assertTrue($client->model2()->number() == 1);
    }     
}


/*
|
| ------------------------------------------------------------------
| Fake classes illustrate its usage.
| ------------------------------------------------------------------
| 
*/
interface ClientModel
{
    public function productA() : ModelA;
    public function partB() : ModelB;
}

interface TestIdentifiable
{
    public function id() : int;
    public function name() : string;
}

class TestIdentity implements TestIdentifiable
{
    public function id() : int
    {
        return 1;
    }

    public function name() : string
    {
        return "dous";
    }
}

class ClientObject implements ClientModel
{
    /// For testing Nexus.
    public function __construct(string $name, Model\Collaboration $modelA, Model\Collaboration $modelB)
    {
        $this->name = $name;

        $this->test = new TestIdentity;

        $this->modelA = $modelA;

        $this->modelB = $modelB;
    }

    public static function withName(string $name)
    {
        return new Self(
            $name, 
            new WorkingWith(ModelA::class, ProductA::class),
            new WorkingWith(ModelB::class, PartB::class)
        );
    }

    public function productA() : ModelA
    {
        return $this->modelA->new($this->test->id(), $this->test->name());
    }

    public function unamedProductA() : ModelA
    {
        return $this->modelA->withNumber($this->test->id());
    }

    public function partB() : ModelB
    {
        return $this->modelB->new($this->test->id());
    }
}

class ClientObjectAnonymous
{
    /// For testing a solution with anonymous classes.
    public function __construct(string $name, $openDependency)
    {
        $this->name = $name;

        $this->test = new TestIdentity;

        $this->openDependency = $openDependency;
    }

    public function model1() : ModelA
    {
        return $this->openDependency->product1($this->test->id(), $this->test->name());
    }

    public function model2() : ModelB
    {
        return $this->openDependency->product2($this->test->id());
    }
}

interface ModelA
{
    public function identification() : array;
}

interface ModelB
{
    public function number() : int;
}

class ProductA implements ModelA
{
    public function __construct(int $number, string $name)
    {
        $this->identification = ['number' => $number, 'name' => $name];
    }

    public static function withNumber(int $number)
    {
        return new Self($number, (new TestIdentity)->name());
    }

    public function identification() : array
    {
        return $this->identification;
    }
}

class PartB implements ModelB
{
    public function __construct(int $number)
    {
        $this->number = $number;
    }

    public function number() : int
    {
        return $this->number;
    }
}
