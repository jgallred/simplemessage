<?php namespace Jgallred\Simplemessage\Routing;

use Illuminate\Routing\Redirector as LaravelRedirector;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Translation\Translator;
use Jgallred\Simplemessage\Http\RedirectResponse;

class Redirector extends LaravelRedirector
{

    /**
     * @var Translator
     */
    protected $translator;

    public function __construct(UrlGenerator $generator, Translator $translator)
    {
        parent::__construct($generator);

        $this->translator = $translator;
    }


    /**
     * Create a new redirect response.
     *
     * @param  string $path
     * @param  int $status
     * @param  array $headers
     * @return RedirectResponse
     */
    protected function createRedirect($path, $status, $headers)
    {
        $redirect = new RedirectResponse($path, $status, $headers);

        if (isset($this->session)) {
            $redirect->setSession($this->session);
        }

        $redirect->setRequest($this->generator->getRequest());

        $redirect->setTranslator($this->translator);

        return $redirect;
    }

}