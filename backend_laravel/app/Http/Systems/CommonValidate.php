<?php

declare(strict_types=1);
use App\Rules\{NotZero,ArrValidator};

return [
     'pageSize' => ['required' , new NotZero ] ,
     'pageNum' => ['required' , new NotZero ],
     'ids' => ['required', new ArrValidator(['required',new NotZero])],
     'insert_organi_id' => [new ArrValidator(['required','numeric'])],
     'insert_nick_name' => ['string','max:50'],
     'insert_organi_name' => ['string','max:50'],
];
