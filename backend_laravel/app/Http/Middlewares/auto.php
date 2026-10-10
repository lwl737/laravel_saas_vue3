<?php

declare(strict_types=1);

return [
    // "Admin" => [\App\Http\Middlewares\Modules\CheckAdminLogin::class],
    "Dev" => [
        \App\Http\Middlewares\Modules\Dev\CheckReferer::class,
        [  "module" => \App\Http\Middlewares\Modules\Dev\CheckDevLogin::class, 'exclude' => ["Dev.Admin.login"]],
        [
            "module" => \App\Http\Middlewares\Modules\Dev\CheckDevLoginEnable::class,
            'exclude' => ["Dev.Admin.login", "Dev.Upload.File.getConfig", "Dev.Upload.File.schedule"]
        ],
        [
            "module" => \App\Http\Middlewares\Modules\Dev\CheckDevOrgani::class,
            'exclude' => ["Dev.Admin.login", "Dev.Upload.File.getConfig", "Dev.Upload.File.schedule"]
        ],
        [
            "module" => \App\Http\Middlewares\Modules\Dev\CheckDevAdminsAuth::class,
            'exclude' => [
                "Dev.Admin.login",
                "Dev.Admin.checkLogin",
                "Dev.Upload.File.getConfig",
                "Dev.Upload.File.schedule",
            ]
        ]
    ],
    "Dev.Develop" => [
        ["module" => \App\Http\Middlewares\Modules\Dev\CheckDevDevelopAuth::class]
    ],
    "Saas" => [
        \App\Http\Middlewares\Modules\Saas\CheckReferer::class,
        \App\Http\Middlewares\Modules\Saas\CheckTenacy::class,
        ["module" => \App\Http\Middlewares\Modules\Saas\CheckSaasLogin::class, 'exclude' => ["Saas.Admin.login"]],
        [
            "module" => \App\Http\Middlewares\Modules\Saas\CheckSaasLoginEnable::class,
            'exclude' => ["Saas.Admin.login", "Saas.Upload.File.getConfig", "Saas.Upload.File.schedule"]
        ],
        [
            "module" => \App\Http\Middlewares\Modules\Saas\CheckSaasOrgani::class,
            'exclude' => ["Saas.Admin.login", "Saas.Upload.File.getConfig", "Saas.Upload.File.schedule"]
        ],
        [
            "module" => \App\Http\Middlewares\Modules\Saas\CheckSaasAdminsAuth::class,
            'exclude' => [
                "Saas.Admin.login",
                "Saas.Admin.checkLogin",
                "Saas.Upload.File.getConfig",
                "Saas.Upload.File.schedule",
            ]
        ]
    ]
];
