<?php
declare(strict_types=1);

namespace App\Common\Repository\Dev;
use App\Common\Dao\Mysql\Dev\MenuRelationDao\Orm as MenuRelationDao;
class MenuRelationRepository extends BaseRepository{


    public function __construct(
        protected MenuRelationDao $dao
    ) {}



    public function getAncestorLink(array $params)
    {
        // 祖先link
        ["max_level" => $max_level, "ancestor_link" => $ancestor_link, "level_link" => $level_link] = $this->dao->getAncestorLink(
            (int)$params['menu_pid']
        );

        $ancestor_link = $ancestor_link != "" ?  explode(",", $ancestor_link) : [];
        $level_link = $level_link != "" ?  explode(",", $level_link) : [];

        return [
            "max_level" => $max_level,
            "ancestor_link" => $ancestor_link,
            "level_link" => $level_link
        ];
    }


}
