<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dev\Saas;

use App\Common\Repository\Saas\OrganiRepository;
use App\Helpers\Output\Json\Success;

class Organi extends BaseController
{
   public function option(
    OrganiRepository $organiRepository
   ){

     ;
     return $this->output([
        "tree" =>  $organiRepository->tenancyInitialize($this->params['tenant_id'])->tree(
             null , false
        )
    ], Success::SEL_SUCCESS);
   }
}
