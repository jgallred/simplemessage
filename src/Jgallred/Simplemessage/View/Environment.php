<?php namespace Jgallred\Simplemessage\View;

use Illuminate\View\Environment as LaravelEnvironment;

class Environment extends LaravelEnvironment {

	/**
	 * Get a evaluated view contents for the given view.
	 *
	 * @param  string  $view
	 * @param  array   $data
	 * @param  array   $mergeData
	 * @return \Illuminate\View\View
	 */
	public function make($view, $data = array(), $mergeData = array())
	{
		$path = $this->finder->find($view);

		$data = array_merge($mergeData, $this->parseData($data));

		return new View($this, $this->getEngineFromPath($path), $view, $path, $data);
	}
}