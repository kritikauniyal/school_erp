<?php

namespace App\Repositories;

use App\Models\SchoolClass;

class ClassRepository extends BaseRepository
{
    public function __construct(SchoolClass $class)
    {
        parent::__construct($class);
    }

    public function withSections()
    {
        return $this->model->newQuery()->with('sections');
    }
}

