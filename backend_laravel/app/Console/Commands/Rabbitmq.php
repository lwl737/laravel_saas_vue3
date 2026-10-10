<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use VladimirYuldashev\LaravelQueueRabbitMQ\Queue\Connectors\RabbitMQConnector;
use VladimirYuldashev\LaravelQueueRabbitMQ\Queue\RabbitMQQueue;

// use App\Services\Settings\ServiceProvider as SettingService;
// use App\Utils\Oss\StsToken as StsTokenUtil;
// use Illuminate\Support\Facades\Log;
// use Illuminate\Contracts\Queue\Queue;

class Rabbitmq extends Command
{

    protected $signature = 'rabbitmq:queue-dead-init
                            {connection=rabbitmq : The name of the queue connection to use}
                            {--queue=}
                            {--dead-exchange=}
                            {--dead-exchange-type=direct}
                            {--dead-exchange-durable=1}
                            {--dead-exchange-auto-delete=0}
                            {--dead-queue=}
                            {--dead-routing-key=}';

    protected $description = '声明和启动死信队列';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(RabbitMQConnector $connector)
    {
        if(!$this->option('queue')) return $this->error("没有传入queue参数");
        $connector = $connector->connect($this->getConfig());
        if ($connector->isQueueExists( $this->option('queue') )) {
            //该队列已存在直接启动
            $this->warn('Queue already exists.');
            $this->start();
            return false;
        }
        $this->deadExchange($connector);  //声明死信交换机
        $this->deadQueue($connector);  //声明死信队列
        $this->deadExchangeQueueBind($connector); //绑定死信队列和死信交换机
        $this->initQueue($connector);  //初始化队列
        $this->start();
    }

    public function start(){
        Artisan::call("queue:work ".$this->argument('connection')." --queue=".$this->option('queue'));
    }


    public function deadExchange(RabbitMQQueue $rabbitMQQueue){
        $exchange = $this->getDeadExchangeName();
        if ($rabbitMQQueue->isExchangeExists($exchange)) {
            // $this->warn('Exchange already exists.');
            //交换机已存在
            return false;
        }

        $rabbitMQQueue->declareExchange(
            $exchange,
            $this->option('dead-exchange-type'),
            (bool) $this->option('dead-exchange-durable'),
            (bool) $this->option('dead-exchange-auto-delete')
        );


    }


    public function deadQueue(RabbitMQQueue $rabbitMQQueue){
        $dead_queue =  $this->getDeadQueue();
        if ($rabbitMQQueue->isQueueExists( $dead_queue )) {
            // $this->warn('Queue already exists.');
            return false;
        }
        $arguments = [];


        $rabbitMQQueue->declareQueue(
            $dead_queue,
            true,
            false,
            $arguments
        );

    }

    public function deadExchangeQueueBind(RabbitMQQueue $rabbitMQQueue){
        $dead_routing_key = $this->getDeadRouteKey();

        $rabbitMQQueue->bindQueue(
            $this->getDeadQueue(),
            $this->getDeadExchangeName(),
            (string) $dead_routing_key
        );
    }

    public function initQueue(RabbitMQQueue $rabbitMQQueue){
        $queue =  $this->option('queue');

        $arguments = [];

        $arguments['x-dead-letter-exchange'] =  $this->getDeadExchangeName();
        $arguments['x-dead-letter-routing-key'] = $this->getDeadRouteKey();

        $rabbitMQQueue->declareQueue(
            $queue,
            true,
            false,
            $arguments
        );
    }

    private function getConfig(){
         return $this->laravel['config']->get('queue.connections.'.$this->argument('connection'));
    }

    private function getDeadExchangeName(){
        $exchange = $this->option('dead-exchange');

        if(!$exchange){
            $exchange =  $this->option('queue') . "_dead_exchange";
        }
        return $exchange;
    }

    private function getDeadQueue(){
        $dead_queue =  $this->option('dead-queue');
        if(!$dead_queue){
            $queue =  $this->option('queue');
            $dead_queue =  $queue . "_dead_queue";
        }
        return $dead_queue;
    }

    private function getDeadRouteKey(){
        $dead_routing_key = $this->option('dead-routing-key');
        if(!$dead_routing_key){
            $dead_routing_key = $this->option('queue') . "_dead_routing_key";
        }

        return $dead_routing_key;
    }
}
