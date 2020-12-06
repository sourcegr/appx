<?php

namespace Sourcegr\Tests\Database\QueryBuilder;


use PDO;
use Sourcegr\Framework\Database\DBConnectionManager;
use Sourcegr\Framework\Database\QueryBuilder\QueryBuilder;
use Sourcegr\Framework\Database\QueryBuilder\DB;
use Sourcegr\Framework\Database\QueryBuilder\Raw;
use Sourcegr\Framework\Database\QueryBuilder\Exceptions\DeleteErrorException;
use Sourcegr\Stub\Grammar;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class ActualTestPGsql extends TestCase
{
    static $table = 'testdates';

    private function init()
    {
        $cm = new DBConnectionManager();

        $cm->create('default',
            'pgsql',
            [
                'host' => '127.0.0.1',
                "encoding" => "UTF8",
                'user' => 'default',
                'password' => 'secret',
                'db' => 'test'
            ]);

        $db = new DB($cm->getConnection('default'));
        return $db;
    }

    public function testInsertDate()
    {
        $db = $this->init();
        $now = new Raw('now()');

        $num = $db->Table(static::$table)->count();
        $res = $db->Table(static::$table)->insert([
            'withtz' => $now,
            'dt' => $now
        ]);

        $actual = $db->Table(static::$table)->count();
        $expected = $num + 1;

        $this->assertEquals($expected, $actual);
    }

    public function testGatDate()
    {
        $db = $this->init();
        $db->RAW("SET TIMEZONE='Asia/Manila'");
        $res = $db->Table(static::$table)->first();

        dd(new \DateTime($res['withtz']), new \DateTime($res['dt']));

        $this->assertEquals($expected, $actual);
    }
}