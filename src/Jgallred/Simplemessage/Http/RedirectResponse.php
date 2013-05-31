<?php namespace Jgallred\Simplemessage\Http;

use Illuminate\Http\RedirectResponse as LaravelResponse;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Lang;
use Jgallred\Simplemessage\Messaging\Typed\Messages as TypedMessages;

class RedirectResponse extends LaravelResponse {

	/**
	 * Flash a general message to the session data.
	 *
	 * This method allows you to conveniently pass messages to views.
	 *
	 * <code>
	 *		// Redirect and flash messages to the session
	 *		return Redirect::to('item_list')->withMessage('Your item was added.');
	 *
	 *    // Flash a message with a type
	 *		return Redirect::to('item_list')->withMessage('Your item was added.', 'success');
	 * </code>
	 *
	 * @param  string $text
	 * @param  string $type
	 * @return Redirect
	 */
	public function withMessage($text, $type = '')
	{
		$messages = Session::get('messages', new TypedMessages);

		$messages->add_typed($text, $type);

		Session::flash('messages', $messages);

		return $this;
	}

	/**
	 * Flashes a language line message to the session data.
	 * Just pass in the same key as you would with Lang::line().
	 * The language will be the current language specified in the URL.
	 *
	 * <code>
	 *   // Redirect and flash a localized message to the session
	 *   Redirect::to('item_list')->withLangMessage('items.added');
	 *
	 *   // Flash a localized message with type
	 *   Redirect::to('item_list')->withLangMessage('items.added', 'success');
	 * </code>
	 *
	 * @param  string $key
	 * @param  string $type
	 * @return Redirect
	 */
	public function withLangMessage($key, $type = '')
	{
		return $this->withMessage(Lang::get($key), $type);
	}

}