<?php

require_once 'TestCase.php';

use Illuminate\Routing\UrlGenerator;
use Illuminate\Session\Store;
use Illuminate\Translation\Translator;
use \Jgallred\Simplemessage\Routing\Redirector;

class RedirectTest extends TestCase
{
    /**
     * @var Redirector
     */
    private $redirector;

    /**
     * @var \Mockery\MockInterface|UrlGenerator
     */
    private $mock_url;

    /**
     * @var \Mockery\MockInterface|Store
     */
    private $mock_session;

    /**
     * @var \Mockery\MockInterface|Translator
     */
    private $mock_translator;

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        parent::setUp();

        $this->mock_url = Mockery::mock('\Illuminate\Routing\UrlGenerator')->shouldIgnoreMissing();
        $this->mock_session = Mockery::mock('\Illuminate\Session\Store')->shouldIgnoreMissing();
        $this->mock_translator = Mockery::mock('\Illuminate\Translation\Translator')->shouldIgnoreMissing();

        $this->redirector = new Redirector($this->mock_url, $this->mock_translator);

        $this->redirector->setSession($this->mock_session);
    }

    /**
     * @test
     *
     * Tests the Redirect::withMessage() method.
     */
    public function should_flashes_messages_to_the_session()
    {
        $messages = new \Jgallred\Simplemessage\Messaging\Typed\Messages();

        $this->mock_session->shouldReceive('get')->andReturn($messages);

        $this->mock_url->shouldReceive('to')->andReturn('/home');
        $this->mock_url->shouldReceive('getRequest')->andReturn(Mockery::mock('\Illuminate\Http\Request'));

        $this->mock_session->shouldReceive('flash')
            ->atLeast(1)
            ->with('messages', $messages);

        $this->redirector->to('')->withMessage('Your thing was added');

        $this->assertCount(1, $messages);
        $this->assertEquals('Your thing was added', $messages->first()->text);
    }

    /**
     * @test
     *
     * Tests the Redirect::withMessage() method.
     */
    public function should_flash_messages_with_type_to_the_session()
    {
        $messages = new \Jgallred\Simplemessage\Messaging\Typed\Messages();

        $this->mock_session->shouldReceive('get')->andReturn($messages);

        $this->mock_url->shouldReceive('to')->andReturn('/home');
        $this->mock_url->shouldReceive('getRequest')->andReturn(Mockery::mock('\Illuminate\Http\Request'));

        $this->mock_session->shouldReceive('flash')
            ->atLeast(1)
            ->with('messages', $messages);

        $this->redirector->to('')->withMessage('Your thing was added', 'success');

        $this->assertCount(1, $messages);
        $this->assertEquals('Your thing was added', $messages->first()->text);
        $this->assertEquals('success', $messages->first()->type);
    }

    /**
     * @test
     *
     * Tests the Redirect::withLangMessage() method.
     */
    public function should_flash_language_line_to_the_session()
    {
        $messages = new \Jgallred\Simplemessage\Messaging\Typed\Messages();

        $this->mock_session->shouldReceive('get')->andReturn($messages);

        $this->mock_url->shouldReceive('to')->andReturn('/home');
        $this->mock_url->shouldReceive('getRequest')->andReturn(Mockery::mock('\Illuminate\Http\Request'));

        $this->mock_session->shouldReceive('flash')
            ->atLeast(1)
            ->with('messages', $messages);

        $this->mock_translator->shouldReceive('get')
            ->atLeast(1)
            ->with('simplemessage::test.test_line')
            ->andReturn('used for unit testing');

        $this->redirector->to('')->withLangMessage('simplemessage::test.test_line');

        $this->assertCount(1, $messages);
        $this->assertEquals('used for unit testing', $messages->first()->text);
    }

    /**
     * @test
     *
     * Tests the Redirect::withLangMessage() method.
     */
    public function should_flash_language_messages_with_type_to_session()
    {
        $messages = new \Jgallred\Simplemessage\Messaging\Typed\Messages();

        $this->mock_session->shouldReceive('get')->andReturn($messages);

        $this->mock_url->shouldReceive('to')->andReturn('/home');
        $this->mock_url->shouldReceive('getRequest')->andReturn(Mockery::mock('\Illuminate\Http\Request'));

        $this->mock_session->shouldReceive('flash')
            ->atLeast(1)
            ->with('messages', $messages);

        $this->mock_translator->shouldReceive('get')
            ->atLeast(1)
            ->with('simplemessage::test.test_line')
            ->andReturn('used for unit testing');

        $this->redirector->to('')->withLangMessage('simplemessage::test.test_line', 'success');

        $this->assertCount(1, $messages);
        $this->assertEquals('used for unit testing', $messages->first()->text);
        $this->assertEquals('success', $messages->first()->type);
    }

}