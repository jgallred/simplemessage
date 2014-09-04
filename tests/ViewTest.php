<?php

use Jgallred\Simplemessage\View\Factory;

require_once 'TestCase.php';

class ViewTest extends TestCase
{

    /**
     * @var \Mockery\Mock|\Illuminate\Session\Store
     */
    private $mock_session;

    /**
     * @var \Mockery\Mock|\Illuminate\Translation\Translator
     */
    private $mock_translator;

    /**
     * @var \Mockery\Mock|\Illuminate\View\Engines\EngineResolver
     */
    private $mock_engine_resolver;

    /**
     * @var \Mockery\Mock|\Illuminate\Contracts\Events\Dispatcher
     */
    private $mock_view_finder;

    /**
     * @var \Mockery\Mock|\Illuminate\Events\Dispatcher
     */
    private $mock_dispatcher;

    /**
     * @var Factory
     */
    private $factory;

    protected function setUp()
    {
        parent::setUp();

        $this->mock_view_finder = Mockery::mock('Illuminate\View\ViewFinderInterface')->shouldIgnoreMissing();
        $this->mock_engine_resolver = Mockery::mock('Illuminate\View\Engines\EngineResolver')->shouldIgnoreMissing();
        $this->mock_translator = Mockery::mock('\Illuminate\Translation\Translator')->shouldIgnoreMissing();
        $this->mock_session = Mockery::mock('\Illuminate\Session\Store')->shouldIgnoreMissing();
        $this->mock_dispatcher = Mockery::mock('\Illuminate\Contracts\Events\Dispatcher')->shouldIgnoreMissing();

        $this->mock_engine_resolver->shouldReceive('resolve')
            ->andReturn(Mockery::mock('\Illuminate\View\Engines\EngineInterface')->shouldIgnoreMissing());

        $this->factory = new Factory(
            $this->mock_engine_resolver,
            $this->mock_view_finder,
            $this->mock_dispatcher,
            $this->mock_translator
        );

        $this->factory->setSession($this->mock_session);
    }

    /**
     * @test
     */
    public function views_should_have_an_empty_general_message_container_by_default()
    {
        $this->mock_view_finder->shouldReceive('find')->andReturn('home/index.php');

        $view = $this->factory->make('home.index');

        $this->assertInstanceOf(
            'Jgallred\\SimpleMessage\\Messaging\\Typed\\Messages',
            $view->getData()['messages']
        );
    }

    /**
     * @test
     */
    public function views_should_flashed_message_container_if_available()
    {
        $this->mock_view_finder->shouldReceive('find')->andReturn('home/index.php');

        $messages = new \Jgallred\Simplemessage\Messaging\Typed\Messages();
        $messages->addTyped("Test");

        $this->mock_session->shouldReceive('isStarted')->andReturn(true);
        $this->mock_session->shouldReceive('has')->with('messages')->andReturn(true);
        $this->mock_session->shouldReceive('get')->atLeast(1)->with('messages')->andReturn($messages);

        $view = $this->factory->make('home.index');

        $this->assertEquals($messages, $view->getData()['messages']);
        $this->assertNotEmpty($messages);
    }
}