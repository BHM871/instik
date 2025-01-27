<?php

namespace Instik\DTO;

class FeedFiltersDto {

	public function __construct(
		private readonly ?string $text = null,
		private readonly ?string $orderBy = 'id',
		private readonly ?string $order = 'DESC'
	) {}

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