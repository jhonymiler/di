<?php

use PHPUnit\Framework\TestCase;
use App\Container;
use Tests\Unit\Core\Db;
use Tests\Unit\Database\MysqlDb;
use Tests\Unit\Core\Logger;
use Tests\Unit\Core\EntityManager;
use Tests\Unit\Model\User;

class ContainerTest extends TestCase
{
    public function testResolveBinding()
    {
        $container = Container::getInstance();
        $container->bind(MysqlDb::class, function () {
            // Simula uma configuração personalizada do MysqlDb, caso necessário
            return new MysqlDb(new Db(/*...*/));
        });

        $mysqlDb = $container->get(MysqlDb::class);

        $this->assertInstanceOf(MysqlDb::class, $mysqlDb);
        // Verifique se a instância de MysqlDb possui a instância correta de Db
        $this->assertInstanceOf(Db::class, $mysqlDb->getDb());
    }

    public function testResolveNamespace()
    {
        $container = Container::getInstance();

        // Instâncias das classes usando apenas o namespace
        $mysqlDb = $container->get(MysqlDb::class);
        $user = $container->get(User::class);

        $this->assertInstanceOf(MysqlDb::class, $mysqlDb);
        $this->assertInstanceOf(User::class, $user);
        // Verifique se a instância de User possui a instância correta de MysqlDb
        $this->assertInstanceOf(MysqlDb::class, $user->getDb());
    }

    public function testResolveFunctionDependencies()
    {
        $container = Container::getInstance();

        // Testando o método call() com injeção de dependência para função interna
        $container->call(function (Logger $logger, EntityManager $em) {
            $this->assertInstanceOf(Logger::class, $logger);
            $this->assertInstanceOf(EntityManager::class, $em);
        });
    }

    public function testResolveNonClassDependency()
    {
        $this->expectException(Exception::class);

        $container = Container::getInstance();

        // Tentando resolver uma dependência que não é uma classe (neste caso, uma string)
        $container->get('SomeNonExistentClass');
    }
}
