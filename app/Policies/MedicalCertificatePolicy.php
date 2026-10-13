<?php

namespace App\Policies;

use App\Models\MedicalCertificate;
use App\Models\User;

class MedicalCertificatePolicy
{
    public function view(User $u, MedicalCertificate $c): bool
    {
        if ($u->isAdmin()) return true;
        return $u->isMurid() && $u->student && $c->visit->student_id === $u->student->id;
    }

    public function download(User $u, MedicalCertificate $c): bool
    {
        if ($u->isAdmin()) return true;
        return config('uks.student_can_download_pdf') && $this->view($u, $c);
    }
}