<?php

namespace Instik\DTO;

use ReflectionClass;

class FeedFiltersDto {

	public function __construct(
		private readonly ?string $text = null,
		private readonly ?string $orderBy = 'id',
		private readonly ?string $order = 'DESC'
	) {}

	public function toArray() : array {
		$array = [];

		$reflection = new ReflectionClass($this);
		foreach ($reflection->getProperties() as $property) {
			$value = $property->getValue($this);

			if ($value != null)
				$array[$property->getName()] = $value;
		}

		return $array;
	}

	public function getText() : ?string {
		return $this->text;
	}

	public function getOrderBy() : ?string {
		return $this->orderBy;
	}

	public function getOrder() : ?string {
		return $this->order;
	}

}