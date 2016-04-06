<?php

namespace Ixmanuel\OpenCreation;

class PodsTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function it_tests_the_php_style()
    {
        $client = new ClientObject2(
            "Client Name",
            (new Pods)
                ->use(ModelA::class, ProductA::class)
                ->use(ModelB::class, PartB::class)
        );

        $test = new TestIdentity;
        $this->assertTrue($client->productA()->identification()['number'] == $test->id());
        $this->assertTrue($client->partB()->number() == $test->id());
    }

    /** @test */
    public function it_tests_the_composer_style()
    {
        $client = new ClientObject2(
            "Client Name",
            (new Pods)
                ->require(ModelA::class, ProductA::class)
                ->require(ModelB::class, PartB::class)
        );

        $test = new TestIdentity;
        $this->assertTrue($client->productA()->identification()['number'] == $test->id());
        $this->assertTrue($client->partB()->number() == $test->id());
    }    

    /** @test */
    public function it_tests_the_object_thinking_style()
    {
        $client = new ClientObject2(
            "Client Name",
            new Pods(
                new Pod2(ModelA::class, ProductA::class),
                new Pod2(ModelB::class, PartB::class)
            )
        );

        $test = new TestIdentity;
        $this->assertTrue($client->productA()->identification()['number'] == $test->id());
        $this->assertTrue($client->partB()->number() == $test->id());
    }    
}

class ClientObject2 implements ClientModel
{
    /// For testing OpenCreation.
    public function __construct(string $name, Model\Packages $packages)
    {
        $this->name = $name;

        $this->test = new TestIdentity;

        $this->packages = $packages;
    }

    public function productA() : ModelA
    {
        return $this->packages->new(ModelA::class, $this->test->id(), $this->test->name());
    }

    public function partB() : ModelB
    {
        return $this->packages->new(ModelB::class, $this->test->id());
    }
}