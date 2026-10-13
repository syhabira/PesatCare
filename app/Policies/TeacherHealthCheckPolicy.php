<?php

namespace App\Policies;

use App\Models\TeacherHealthCheck;
use App\Models\User;

class TeacherHealthCheckPolicy
{
    public function view(User $u, TeacherHealthCheck $c): bool
    {
        return $u->isAdmin() || ($u->isGuru() && $u->teacher && $c->teacher_id === $u->teacher->id);
    }
}