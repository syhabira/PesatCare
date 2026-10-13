<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Visit;

class VisitPolicy
{
    public function view(User $u, Visit $v): bool
    {
        if ($u->isAdmin()) return true;
        if ($u->isGuru()) return $u->teacher && $v->teacher_id === $u->teacher->id;
        if ($u->isMurid()) return $u->student && $v->student_id === $u->student->id;
        return false;
    }

    public function manage(User $u): bool { return $u->isAdmin(); }
}