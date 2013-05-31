<?php namespace Jgallred\Simplemessage\Routing;

use Illuminate\Routing\Redirector as LaravelRedirector;
use Jgallred\Simplemessage\Http\RedirectResponse;

class Redirector extends LaravelRedirector {

	/**
	 * Create a new redirect response.
	 *
	 * @param  string  $path
	 * @param  int     $status
	 * @param  array   $headers
	 * @return \Jgallred\Simplemessage\RedirectResponse
	 */
	protected function createRedirect($path, $status, $headers)
	{
		$redirect = new RedirectResponse($path, $status, $headers);

		if (isset($this->session))
		{
			$redirect->setSession($this->session);
		}

		$redirect->setRequest($this->generator->getRequest());

		return $redirect;
	}

}