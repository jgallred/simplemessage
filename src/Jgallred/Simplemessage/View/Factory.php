<?php namespace Jgallred\Simplemessage\View;

use Illuminate\Events\Dispatcher;
use Illuminate\Session\Store;
use Illuminate\Translation\Translator;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\Factory as LaravelFactory;
use Illuminate\View\ViewFinderInterface;

class Factory extends LaravelFactory
{
    /**
     * @var Store
     */
    protected $session;

    /**
     * @var Translator
     */
    protected $translator;

    public function __construct(
        EngineResolver $engines,
        ViewFinderInterface $finder,
        Dispatcher $events,
        Translator $translator
    ) {
        parent::__construct($engines, $finder, $events);

        $this->translator = $translator;
    }


    /**
     * Get a evaluated view contents for the given view.
     *
     * @param  string $view
     * @param  array $data
     * @param  array $mergeData
     * @return \Illuminate\View\View
     */
    public function make($view, $data = array(), $mergeData = array())
    {
        $illuminate_view = parent::make($view, $data, $mergeData);

        return new View(
            $illuminate_view->getFactory(),
            $illuminate_view->getEngine(),
            $this->translator,
            $illuminate_view->getName(),
            $illuminate_view->getPath(),
            $illuminate_view->getData(),
            $this->session
        );
    }

    /**
     * @param \Illuminate\Session\Store $session
     */
    public function setSession($session)
    {
        $this->session = $session;
    }

    /**
     * @return \Illuminate\Session\Store
     */
    public function getSession()
    {
        return $this->session;
    }
}