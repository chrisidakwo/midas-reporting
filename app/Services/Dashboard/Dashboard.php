<?php

namespace App\Services\Dashboard;

use Illuminate\Support\HtmlString;

class Dashboard {
	public array $scripts = [];

	public array $inlineScripts = [];

	public array $stylesheets = [];

	public array $inlineStylesheets = [];

	/**
	 * @param string $url
	 * @return $this
	 */
	public function script(string $url): self {
		$this->scripts[] = $url;

		return $this;
	}

	/**
	 * @param string $url
	 * @return $this
	 */
	public function stylesheet(string $url): self {
		$this->stylesheets[] = $url;

		return $this;
	}

	/**
	 * @param string $stylesheet
	 * @return $this
	 */
	public function inlineStylesheets(string $stylesheet): self {
		$this->inlineStylesheets[] = $stylesheet;

		return $this;
	}

	/**
	 * @return HtmlString
	 */
	public function assets(): HtmlString {
		$assets = [];

		foreach ($this->scripts as $script) {
			$assets[] = "<script src='{$script}' defer></script>";
		}

		foreach ($this->inlineScripts as $inlineScript) {
			$assets[] = "<script src='{$inlineScript}'></script>";
		}

		foreach ($this->stylesheets as $stylesheet) {
			$assets[] = "<link rel=\"stylesheet\" href=\"{$stylesheet}\">";
		}

		foreach ($this->inlineStylesheets as $inlineStylesheet) {
			$assets[] = "<style>$inlineStylesheet</style>";
		}

		return new HtmlString(implode('', $assets));
	}
}
