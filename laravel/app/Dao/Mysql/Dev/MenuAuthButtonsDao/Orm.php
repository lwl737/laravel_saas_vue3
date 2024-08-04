<?php

declare(strict_types=1);

namespace App\Dao\Mysql\Dev\MenuAuthButtonsDao;

use App\Dao\Mysql\Dev\BaseDev;
use App\Models\Mysql\Orm\Dev\MenuAuthButtons;

class Orm extends BaseDev
{

    public function __construct()
    {
        $this->query = new MenuAuthButtons;
    }

    public function add(array $params)
    {

        $this->query->buttons = $params['buttons'];

        $this->query->auth_id = $params['auth_id'];

        $this->query->save();
    }

    public function del(array $params)
    {
        return $this->query->where('buttons_id', $params['buttons_id'])->delete();
    }

    public function edit(array $params)
    {
        return  $this->query->where('buttons_id', $params['buttons_id'])->update([
            'buttons' => $params['buttons']
        ]);
    }
}
