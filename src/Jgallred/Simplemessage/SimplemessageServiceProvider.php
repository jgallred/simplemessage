<?php namespace Jgallred\Simplemessage;

use Illuminate\Support\ServiceProvider;
use Jgallred\Simplemessage\Routing\Redirector;
use Jgallred\Simplemessage\View\Environment;

class SimplemessageServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('jgallred/simplemessage');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->registerRedirector();

        $this->registerEnvironment();
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

	/**
	 * Register the Redirector service.
	 *
	 * @return void
	 */
	protected function registerRedirector()
	{
		$this->app['redirect'] = $this->app->share(function($app)
		{
			$redirector = new Redirector($app['url']);

			// If the session is set on the application instance, we'll inject it into
			// the redirector instance. This allows the redirect responses to allow
			// for the quite convenient "with" methods that flash to the session.
			if (isset($app['session']))
			{
				$redirector->setSession($app['session']);
			}

			return $redirector;
		});
	}

	/**
	 * Register the view environment.
	 *
	 * @return void
	 */
	public function registerEnvironment()
	{
		$this->app['view'] = $this->app->share(function($app)
		{
			// Next we need to grab the engine resolver instance that will be used by the
			// environment. The resolver will be used by an environment to get each of
			// the various engine implementations such as plain PHP or Blade engine.
			$resolver = $app['view.engine.resolver'];

			$finder = $app['view.finder'];

			$env = new Environment($resolver, $finder, $app['events']);

			// We will also set the container instance on this view environment since the
			// view composers may be classes registered in the container, which allows
			// for great testable, flexible composers for the application developer.
			$env->setContainer($app);

			$env->share('app', $app);

			return $env;
		});
	}

}