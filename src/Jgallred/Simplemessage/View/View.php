<?php namespace Jgallred\Simplemessage\View;

use Illuminate\View\View as LaravelView;
use Illuminate\View\Engines\EngineInterface;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Lang;
use Jgallred\Simplemessage\Messaging\Typed\Messages as TypedMessages;

class View extends LaravelView {

	/**
	 * Create a new view instance.
	 *
	 * @param  \Jgallred\Simplemessage\View\Environment  $environment
	 * @param  \Illuminate\View\Engines\EngineInterface  $engine
	 * @param  string  $view
	 * @param  string  $path
	 * @param  array   $data
	 * @return void
	 */
	public function __construct(Environment $environment, EngineInterface $engine, $view, $path, $data = array())
	{
		parent::__construct($environment, $engine, $view, $path, $data);

		// If a session driver has been specified, we will bind an instance of the
		// message container to every view. If a container instance
		// exists in the session, we will use that instance.
		$key = 'messages';
		if ( ! isset($this->data[$key]))
		{
			if (Session::isStarted() and Session::has($key))
			{
				$this->data[$key] = Session::get($key);
			}
			else
			{
				$this->data[$key] = new TypedMessages();
			}
		}
	}

    /**
	 * Add general message to the view data.
	 *
	 * This method allows you to convienently pass messages to views.
	 *
	 * <code>
	 *		return View::make('some.view')->withMessage('Your item was added.');
	 *
	 *		return View::make('some.view')->withMessage('Your item was added.', 'success');
	 * </code>
	 *
	 * @param  string $text
	 * @param  string $type
	 * @return View
	 */
	public function withMessage($text, $type = '')
	{
        $key = 'messages';
		if (isset($this->data[$key]))
		{
			$this->data[$key]->add_typed($text, $type);
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
		return $this->withMessage(Lang::get($key), $type);
	}
}