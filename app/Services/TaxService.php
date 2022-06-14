<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use App\Contracts\Repositories\TaxRepositoryInterface;
use App\Models\Taxonomy;

class TaxService {

	private $taxRepository;

	public function __construct(TaxRepositoryInterface $taxRepository)
	{
		$this->taxRepository = $taxRepository;
	}

	public function create(array $data) : Taxonomy
	{
		// Create taxonomy
		$taxonomy = $this->taxRepository->create($data);

		// Set taxonomy icon
		$this->updateIcon($taxonomy, $data);

		return $taxonomy;
	}

	public function update($taxonomy, array $data) : Taxonomy
	{
		// Update taxonomy
		$taxonomy = $this->taxRepository->update($taxonomy, $data);

		// Set taxonomy icon
		$this->updateIcon($taxonomy, $data);

		return $taxonomy;
	}

	public function updateIcon(Taxonomy $taxonomy, array $data) : Taxonomy
	{
		if (isset($data['icon']) && $data['icon'] instanceof UploadedFile) {
			$taxonomy->setIcon($data['icon']);
		} elseif ( ! isset($data['icon'])) {
			$taxonomy->media()->delete();
		}

		return $taxonomy;
	}

	public function delete($id)
	{
		$this->taxRepository->delete($id);
	}
}
