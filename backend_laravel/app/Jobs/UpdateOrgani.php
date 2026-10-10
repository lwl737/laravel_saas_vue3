<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Common\Repository\Dev\OrganiAdminsBindRepository;
use Illuminate\Support\Facades\DB;

class UpdateOrgani extends Job
{
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(
        OrganiAdminsBindRepository $organiAdminsBindRepository
    )
    {

        ["admins_id" => $admins_id, "organi_id" => $organi_id] = $this->getData();


        DB::transaction(function () use ($organiAdminsBindRepository, $admins_id, $organi_id) {
            foreach ($organi_id as $item) $organiAdminsBindRepository->insertUpdatedStr(["organi_id" => $item, "admins_id" => $admins_id]);

            $organiAdminsBindRepository->where("admins_id", $admins_id)->whereNotIn("organi_id", $organi_id)->delete();
        });
    }
}
