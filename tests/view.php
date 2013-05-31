<?php

use Jgallred\SimpleMessage\View\View;

class ViewTest extends TestCase {

    /**
     * Tear down the testing environment.
     */
    public function tearDown() {
        View::$shared = array();
        unset(Event::$events['composing: test.basic']);
    }

    /**
     * Test the View class constructor.
     *
     * @group laravel
     */
    public function testEmptyGeneralMessageContainerSetOnViewWhenNoGeneralMessagesInSession() {
        $view = new View('home.index');

        $this->assertInstanceOf('Jgallred\\SimpleMessage\\Messaging\\Typed\\Messages', $view->data['messages']);
    }

}