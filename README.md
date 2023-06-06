# HyperfRabbitMQ
Docker+HyperF+RabbitMq高性能高并发高可用的服务架构

#1.创建hyperf镜像
docker run --name hyperf -v D:/workspace/skeleton:/data/project -p 9501:9501 -it --privileged -u root --entrypoint /bin/sh hyperf/hyperf:7.4-alpine-v3.11-swoole
cd /data/project
composer create-project hyperf/hyperf-skeleton
cd hyperf-skeleton
php bin/hyperf.php start


#2.创建rabbitmq镜像
docker pull rabbitmq:3.9-management
docker run -d --name RabbitMq -p 15672:15672 -p 5672:5672 rabbitmq:3.9-management

#3.hyperf/amqp 是实现 AMQP 标准的组件，主要适用于对 RabbitMQ 的使用
composer require hyperf/amqp
composer require hyperf/command

#4.配置amqp  具体查看代码
#新建hyperf-skeleton\config\autoload\amqp.php

#5使用 gen:producer 命令创建一个 producer    
php bin/hyperf.php gen:amqp-producer DemoProducer 
php bin/hyperf.php gen:command FooCommand

#5创建一个消费者  
php bin/hyperf.php gen:amqp-consumer DemoConsumer
