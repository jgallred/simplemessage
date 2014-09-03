<?php

require_once 'TestCase.php';

use Jgallred\SimpleMessage\Messaging\Typed\Entry;
use Jgallred\Simplemessage\Messaging\Typed\Messages;

class TypedMessagesTest extends TestCase
{

    /**
     * The Typed\Messages instance.
     *
     * @var Messages
     */
    public $messages;

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        $this->messages = new Messages;
    }

    /**
     * @test
     *
     * Tests the Messages:addType method
     *
     * @group laravel
     */
    public function add_typed_method_should_create_entry()
    {
        $this->messages->addTyped('test', 'info');

        $actual = $this->messages->getMessages()['info'][0];
        $this->assertInstanceOf('\Jgallred\SimpleMessage\Messaging\Typed\Entry', $actual);
        $this->assertEquals('test', $actual->text);
        $this->assertEquals('info', $actual->type);
    }

    /**
     * @test
     *
     * Tests the Messages:addType method
     *
     * @group laravel
     */
    public function add_typed_method_should_put_messages_in_array()
    {
        $this->messages->addTyped('test', 'info');
        $expected = array('info' => array(new Entry('test', 'info')));
        $this->assertEquals($expected, $this->messages->getMessages());
    }

    /**
     * @test
     *
     * Tests the Messages:addType method
     *
     * @group laravel
     */
    public function add_typed_method_should_put_default_messages_under_the_default_key()
    {
        $this->messages->addTyped('test');
        $expected = array(':default:' => array(new Entry('test')));
        $this->assertEquals($expected, $this->messages->getMessages());
    }

    /**
     * @test
     *
     * Test the Messages::addType method
     *
     * @group laravel
     */
    public function add_typed_messages_should_not_create_duplicate_messages()
    {
        $this->messages->addTyped('test', 'success');
        $this->messages->addTyped('test', 'success');
        $this->assertCount(1, $this->messages->getMessages());
    }

    /**
     * @test
     *
     * Tests Messages::add method
     *
     * @group laravel
     */
    public function add_method_should_add_entry_to_array()
    {
        $this->messages->add('success', 'ok');
        $this->assertEquals(array('success' => array(new Entry('ok', 'success'))), $this->messages->getMessages());
    }

    /**
     * @test
     *
     * Tests Messages::all method
     *
     * @group laravel
     */
    public function all_method_should_return_flat_array_of_entries()
    {
        $this->messages = new Messages(array(
            'success' => array(new Entry('ok')),
            'error' => array(new Entry('problem'), new Entry('hiccup'))
        ));

        $expected = array(new Entry('ok'), new Entry('problem'), new Entry('hiccup'));
        $this->assertEquals($expected, $this->messages->all());
    }

    /**
     * @test
     *
     * Tests Messages::has method
     *
     * @group laravel
     */
    public function has_method_returns_true()
    {
        $this->messages = new Messages(array('success' => array(new Entry('ok', 'success'))));
        $this->assertTrue($this->messages->has('success'));
    }

    /**
     * @test
     *
     * Tests Messages::has method
     *
     * @group laravel
     */
    public function has_method_returns_false()
    {
        $this->assertFalse($this->messages->has('foo'));
    }

    /**
     * @test
     *
     * Tests Messages:first method
     *
     * @group laravel
     */
    public function first_method_should_retrieve_messsages_by_type()
    {
        $this->messages = new Messages(array('success' => array(new Entry('ok', 'success'))));
        $this->assertEquals(new Entry('ok', 'success'), $this->messages->first());
        $this->assertEquals(new Entry('ok', 'success'), $this->messages->first('success'));
        $this->assertEquals(null, $this->messages->first('foo'));
    }

    /**
     * @test
     *
     * Tests Messages::get method
     *
     * @group laravel
     */
    public function get_method_should_retrieve_messages_by_type()
    {
        $this->messages = new Messages(array('success' => array(new Entry('ok', 'success'))));
        $this->assertEquals(array(new Entry('ok', 'success')), $this->messages->get('success'));
        $this->assertEquals(array(), $this->messages->get('foo'));
    }

    /**
     * @test
     *
     * Test the Messages::first method.
     * Test the Messages::get method.
     * Test the Messages::all method.
     *
     * @group laravel
     */
    public function messages_should_respect_format()
    {
        $this->messages->addTyped('ok', 'success');
        $this->assertEquals(
            new Entry('<p class="success">ok</p>', 'success'),
            $this->messages->first(null, '<p class=":type">:message</p>')
        );
        $this->assertEquals(
            new Entry('<p class="success">ok</p>', 'success'),
            $this->messages->first('success', '<p class=":type">:message</p>')
        );
        $this->assertEquals(
            array(new Entry('<p class="success">ok</p>', 'success')),
            $this->messages->get('success', '<p class=":type">:message</p>')
        );
        $this->assertEquals(
            array(new Entry('<p class="success">ok</p>', 'success')),
            $this->messages->all('<p class=":type">:message</p>')
        );
    }

    /**
     * @test
     *
     * Test the Messages::get method.
     *
     * @group laravel
     */
    public function message_formatting_should_return_entry_copy()
    {
        $this->messages->addTyped('ok', 'success');
        $this->assertEquals(
            array(new Entry('<p>ok</p>', 'success')),
            $this->messages->get('success', '<p>:message</p>')
        );
        $this->assertEquals(array('success' => array(new Entry('ok', 'success'))), $this->messages->getMessages());
    }

}