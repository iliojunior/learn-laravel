<?php

namespace Tests\Unit;

use SebastianBergmann\Diff\InvalidArgumentException;
use Tests\TestCase;

class LearnTest extends TestCase
{
    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Message
     */
    public function testException()
    {
        throw new InvalidArgumentException("Message");
    }
}
