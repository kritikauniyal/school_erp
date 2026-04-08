<?php

namespace App\Repositories;

use App\Models\Fee;
use Carbon\Carbon;

class FeeRepository extends BaseRepository
{
    public function __construct(Fee $fee)
    {
        parent::__construct($fee);
    }

    public function upcoming(int $limit = 10)
    {
        return $this->model->newQuery()
            ->with(['student.user'])
            ->whereDate('due_date', '>=', Carbon::today())
            ->orderBy('due_date')
            ->limit($limit)
            ->get();
    }
}

