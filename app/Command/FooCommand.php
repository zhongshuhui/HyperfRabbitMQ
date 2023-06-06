<?php

declare(strict_types=1);

namespace App\Command;

use App\Amqp\Producer\DemoProducer;
use Hyperf\Command\Command as HyperfCommand;
use Hyperf\Command\Annotation\Command;
use Hyperf\Utils\ApplicationContext;
use Hyperf\Utils\WaitGroup;
use Psr\Container\ContainerInterface;

/**
 * @Command
 */
#[Command]
class FooCommand extends HyperfCommand
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        parent::__construct('demo:command');
    }

    public function configure()
    {
        parent::configure();
        $this->setDescription('Hyperf Demo Command');
    }

    public function handle()
    {

        $wg = new WaitGroup();
        // 计数器加二
        $wg->add(1000);
        for($i=0;$i<1000;$i++){
            // 创建协程 A
            co(function () use ($wg) {
                // some code
                $message = new DemoProducer(['data'=>'2222222222222']);
                $producer = ApplicationContext::getContainer()->get(\Hyperf\Amqp\Producer::class);
                $result = $producer->produce($message);
                // 计数器减一
                $wg->done();
            });
        }


        // 等待协程 A 和协程 B 运行完成
        $wg->wait();
    }
}
