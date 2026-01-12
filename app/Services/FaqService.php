<?php

namespace App\Services;

use App\Models\Faq;
use App\Repositories\FaqRepository;

class FaqService extends BaseService
{
    protected string $repositoryName = FaqRepository::class;

    public function create(array $data): Faq
    {
        $order = ($this->repository->max('order') ?? 0) + 1;
        $data['order'] = $order;
        return $this->repository->create($data);
    }

    public function update(Faq $faq, array $data): Faq
    {
        $faq->update($data);
        return $faq;
    }

    public function getFaq($id)
    {
        return $this->repository->find($id);
    }

    public function delete(Faq $faq): void
    {
        $faq->delete();
    }
}
