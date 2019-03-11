<?php declare(strict_types = 1);

namespace WebChemistry\DemandComponent;

use Nette\Application\UI\Control;
use Nette\Application\UI\Presenter;
use Nette\Utils\Strings;

class DemandComponent extends Control {

	public const HANDLE = 'demand!';

	/** @var bool */
	private $visible = false;

	/** @var Control */
	private $control;

	/** @var mixed[] */
	protected $rendering = [
		'above' => null,
		'below' => null,
		'before' => null,
	];

	public function __construct(Control $control) {
		$this->control = $control;

		$this->onAnchor[] = function (): void {
			if (!$this->visible && $this->isSignalReceived()) {
				$this->visible = true;
				$this->redrawControl('demand');
			}

			// attach component
			$this->getComponent('demand');
		};
	}

	public function setBelowRender(?string $renderer): self {
		$this->rendering['below'] = $this->processRenderer($renderer);

		return $this;
	}

	public function setAboveRender(?string $renderer): self {
		$this->rendering['above'] = $this->processRenderer($renderer);

		return $this;
	}

	public function setBeforeRender(?string $renderer): self {
		$this->rendering['before'] = $this->processRenderer($renderer);

		return $this;
	}

	protected function processRenderer(?string $renderer) {
		if (is_string($renderer)) {
			return ['demand', $renderer];
		}

		return $renderer;
	}

	public function setVisible(bool $visible = true): self {
		$this->visible = $visible;

		return $this;
	}

	public function isVisible(): bool {
		return $this->visible;
	}

	public function getControl(): Control {
		return $this->control;
	}

	protected function isSignalReceived(): bool {
		/** @var Presenter $presenter */
		$presenter = $this->getPresenter();
		$signal = $presenter->getSignal();

		if ($signal === null) {
			return false;
		}
		$receiver = $signal[0];

		return Strings::startsWith($receiver, $this->lookupPath() . '-demand');
	}

	protected function createComponentDemand(): Control {
		return $this->control;
	}

	public function render(): void {
		$template = $this->getTemplate();
		$template->setFile(__DIR__ . '/templates/demand.latte');

		$template->visible = $this->visible;
		$template->rendering = $this->rendering;

		$template->render();
	}

	public function renderLink(): void {
		echo $this->getLink();
	}

	public function getLink(): string {
		return $this->link(self::HANDLE);
	}

	public function handleDemand(): void {
		$this->visible = true;

		if ($this->getPresenter()->isAjax()) {
			$this->redrawControl('demand');
		}
	}

}
