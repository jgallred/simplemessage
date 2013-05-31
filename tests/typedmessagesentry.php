<?php

use Jgallred\SimpleMessage\Messaging\Typed\Entry;

class EntryTest extends TestCase {

    /**
     * Tests Entry::__toString method
     *
     * @group laravel
     */
    public function testToStringReturnsEntryText() {
        $this->assertEquals('test', new Entry('test'));
    }

}