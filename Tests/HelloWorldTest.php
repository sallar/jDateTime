<?php

class HelloWorldTest extends PHPUnit_Framework_TestCase
{
    public function testHelloWorld()
    {
        $helloWorld = new HelloWorld($this->pdo);

        $this->assertEquals('Hello World', $helloWorld->hello());
    }
}
