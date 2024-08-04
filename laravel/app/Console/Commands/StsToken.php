<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Dev\Settings\Server as SettingServer;
use App\Utils\Oss\StsToken as StsTokenUtil;
use Illuminate\Support\Facades\Log;

class StsToken extends Command
{

    protected $signature = 'stsToken:action {action : create|check}';

    protected $description = '检查sts';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try{
            $settings =  SettingServer::dev()->getItem(["oss_start", "oss_access_key_id", "oss_access_key_secret", "oss_endpoint", "oss_bucket", "oss_role_arr"]);

            $str = '';

            $action = $this->argument('action');

            if($action === 'create'|| $action === 'check') {
                if ($settings['oss_start'] === 1) {   //oss请求

                    $stsToken = (new StsTokenUtil(
                        $settings["oss_access_key_id"],
                        $settings["oss_access_key_secret"],
                        $settings["oss_endpoint"],
                        $settings["oss_role_arr"],
                    ));

                    $oss_config =  $action === 'create' ?   $stsToken->create() : $stsToken->get();

                    $oss_config['timeout'] = (int)$oss_config['timeout'];

                    $oss_config['oss_bucket'] = $settings['oss_bucket'];

                    foreach ($oss_config as $key => $val)  $str .=  "$key:$val" . PHP_EOL;

                } else  $str = '暂无开启oss';
            } else {
                $str = '没有找到对应action';
            }

            echo $str;
        }catch(\Exception $e){
            Log::channel('dev_sts')->error(json_encode(['time' => date('Y-m-d H:i:s'),'file'=>$e->getFile(),'line' => $e->getLine(), 'error' => $e->getMessage()],JSON_UNESCAPED_UNICODE));
            echo $e->getMessage();
        }
    }
}
