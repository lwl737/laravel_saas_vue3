<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Mysql\Orm\Dev\OrganiAdminsBind;
use Illuminate\Support\Facades\DB;

class UpdateOrgani extends Job
{
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        ["admins_id" => $admins_id, "organi_id" => $organi_id] = $this->getData();

        $model = new OrganiAdminsBind;

        DB::transaction(function () use ($model, $admins_id, $organi_id) {
            foreach ($organi_id as $item) $model->insertUpdatedStr(["organi_id" => $item, "admins_id" => $admins_id]);

            $model->where("admins_id", $admins_id)->whereNotIn("organi_id", $organi_id)->delete();
        });
    }
}
