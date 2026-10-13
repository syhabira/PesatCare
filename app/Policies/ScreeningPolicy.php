<?php

namespace App\Policies;

use App\Models\Screening;
use App\Models\User;

class ScreeningPolicy
{
    public function view(User $u, Screening $s): bool
    {
        return $u->isAdmin() || ($u->isMurid() && $u->student && $s->student_id === $u->student->id);
    }

    public function download(User $u, Screening $s): bool
    {
        if ($u->isAdmin()) return true;
        return config('uks.student_can_download_pdf') && $this->view($u, $s);
    }
}