<?php namespace Jgallred\Simplemessage\View;

use Illuminate\Session\Store;
use Illuminate\Translation\Translator;
use Illuminate\View\View as LaravelView;
use Illuminate\View\Engines\EngineInterface;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Lang;
use Jgallred\Simplemessage\Messaging\Typed\Messages as TypedMessages;
use Illuminate\View\Factory as LaravelFactory;

class View extends LaravelView
{

    /**
     * @var Translator
     */
    protected $translator;

    /**
     * @var string
     */
    private $messages_key = 'messages';

    /**
     * Create a new view instance.
     *
     * @param LaravelFactory $environment
     * @param \Illuminate\View\Engines\EngineInterface $engine
     * @param \Illuminate\Translation\Translator $translator
     * @param string $view
     * @param string $path
     * @param array $data
     * @param Store $session
     * @return \Jgallred\Simplemessage\View\View
     */
    public function __construct(
        LaravelFactory $environment,
        EngineInterface $engine,
        Translator $translator,
        $view,
        $path,
        $data = array(),
        Store $session = null
    ) {
        parent::__construct($environment, $engine, $view, $path, $data);

        $this->translator = $translator;

        // If a session driver has been specified, we will bind an instance of the
        // message container to every view. If a container instance
        // exists in the session, we will use that instance.
        if (!isset($this->data[$this->messages_key])) {
            if ($session && $session->isStarted() and $session->has($this->messages_key)) {
                $this->data[$this->messages_key] = $session->get($this->messages_key);
            } else {
                $this->data[$this->messages_key] = new TypedMessages();
            }
        }
    }

    /**
     * Add general message to the view data.
     *
     * This method allows you to conveniently pass messages to views.
     *
     * <code>
     *        return View::make('some.view')->withMessage('Your item was added.');
     *
     *        return View::make('some.view')->withMessage('Your item was added.', 'success');
     * </code>
     *
     * @param  string $text
     * @param  string $type
     * @return View
     */
    public function withMessage($text, $type = '')
    {
        if (isset($this->data[$this->messages_key])) {
            $this->data[$this->messages_key]->add_typed($text, $type);
        }

        return $this;
    }

    /**
     * Flashes a language line message to the view data.
     * Just pass in the same key as you would with Lang::line().
     * The language will be the current language specified in the URL.
     *
     * <code>
     *   // Redirect and flash a localized message to the session
     *   return View::make('some.view')->withLangMessage('items.added');
     *
     *   // Flash a localized message with type
     *   return View::make('some.view')->withLangMessage('items.added', 'success');
     * </code>
     *
     * @param  string $key
     * @param  string $type
     * @return View
     */
    public function withLangMessage($key, $type = '')
    {
        return $this->withMessage($this->translator->get($key), $type);
    }
}