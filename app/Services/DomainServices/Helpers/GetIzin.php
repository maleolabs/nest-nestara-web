<?php

namespace App\Services\DomainServices\Helpers;

use App\Services\DomainServices\PerizinanService;

class GetIzin
{
    protected PerizinanService $perizinanService;

    private string $search = '';

    private array $filter_status = [];

    private array $filter_status_progress = [];

    private array $filter_keterangan = [];

    private ?string $filter_start_date = null;

    private ?string $filter_end_date = null;

    private string $sort = 'DESC';

    private string $order_by = 'created_at';

    private int $page = 1;

    public function __construct()
    {
        $this->perizinanService = app(PerizinanService::class);
    }

    public function search(string $search): self
    {
        $this->search = $search;
        return $this;
    }

    public function filterStatus(array $filter_status): self
    {
        $this->filter_status = $filter_status;
        return $this;
    }

    public function filterStatusProgress(array $filter_status_progress): self
    {
        $this->filter_status_progress = $filter_status_progress;
        return $this;
    }

    public function filterKeterangan(array $filter_keterangan): self
    {
        $this->filter_keterangan = $filter_keterangan;
        return $this;
    }

    public function startDate(string $filter_start_date): self
    {
        $this->filter_start_date = $filter_start_date;
        return $this;
    }

    public function endDate(string $filter_end_date): self
    {
        $this->filter_end_date = $filter_end_date;
        return $this;
    }

    public function sort(string $sort): self
    {
        $this->sort = $sort;
        return $this;
    }

    public function orderBy(string $order_by): self
    {
        $this->order_by = $order_by;
        return $this;
    }

    public function page(int $page): self
    {
        $this->page = $page;
        return $this;
    }

    public function paginate(int $limit)
    {
        return $this->perizinanService->get(
            $this->search,
            $this->filter_status,
            $this->filter_status_progress,
            $this->filter_keterangan,
            $this->filter_start_date,
            $this->filter_end_date,
            $this->sort,
            $limit,
            $this->order_by,
            true,
            $this->page
        );
    }

    public function get(?int $limit = null): array
    {
        return [];
    }

}