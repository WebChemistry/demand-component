<?php declare(strict_types = 1);

namespace WebChemistry\DemandComponent;

trait TDemandControl {

	protected function getDemandLink(): string {
		return $this->lookup(DemandComponent::class)->getLink();
	}

	protected function getDemandComponent(): ?DemandComponent {
		return $this->lookup(DemandComponent::class);
	}

	protected function onDemand(callable $callback): void {
		$this->monitor(DemandComponent::class, $callback);
	}

}
