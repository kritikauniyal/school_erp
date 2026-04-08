<?php

namespace App\Repositories;

use App\Models\Student;

class StudentRepository extends BaseRepository
{
    public function __construct(Student $student)
    {
        parent::__construct($student);
    }

    public function withRelations()
    {
        return $this->model->newQuery()->with(['user', 'class', 'section']);
    }
}

