<?php

namespace Doctrine\Tests\ORM\Mapping;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Events;

class ClassMetadataInfoTest extends \Doctrine\Tests\OrmTestCase
{
    const QUOTED_TABLE_NAME = '`foo`.`bar`';
    const TABLE_NAME = 'foo.bar';
    const TABLE_NAME_NO_SCHEMA = 'bar';

    public function testIsQuotingTableNameWithSchema()
    {
        $tableName = $this->getClassMetadataInfo(self::TABLE_NAME)->getQuotedTableName(
            $this->getPlataformMock()->reveal()
        );

        $this->assertEquals(self::TABLE_NAME, $tableName);

        $tableName = $this->getClassMetadataInfo(self::QUOTED_TABLE_NAME)->getQuotedTableName(
            $this->getPlataformMock()->reveal()
        );

        $this->assertEquals(self::QUOTED_TABLE_NAME, $tableName);

        $tableName = $this->getClassMetadataInfo(self::TABLE_NAME_NO_SCHEMA)->getQuotedTableName(
            $this->getPlataformMock()->reveal()
        );

        $this->assertEquals(self::TABLE_NAME_NO_SCHEMA, $tableName);
    }

    protected function getClassMetadataInfo($tableName)
    {
        $classMetadataInfo = new \Doctrine\ORM\Mapping\ClassMetadataInfo('EntityFoo');
        $classMetadataInfo->setPrimaryTable([
            'name' => $tableName
        ]);

        return $classMetadataInfo;
    }

    protected function getPlataformMock()
    {
        $tableName = explode('.', self::TABLE_NAME);
        $tableNameQuoted = explode('.', self::QUOTED_TABLE_NAME);

        $platform = $this->prophesize('\Doctrine\DBAL\Platforms\AbstractPlatform');
        $platform->quoteIdentifier($tableName[0])->willReturn($tableNameQuoted[0]);
        $platform->quoteIdentifier($tableName[1])->willReturn($tableNameQuoted[1]);

        return $platform;
    }
}
