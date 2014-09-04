<?php

require_once 'TestCase.php';

use Jgallred\Simplemessage\Messaging\Typed\Entry;

class TypedMessagesEntryTest extends TestCase
{

    /**
     * @test
     *
     * Tests Entry::__toString method
     *
     * @group laravel
     */
    public function to_string_should_return_entry_text()
    {
        $this->assertEquals('test', new Entry('test'));
    }

}