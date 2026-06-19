<?php

namespace Tests\Unit;

use App\Support\LegacySqlDump;
use PHPUnit\Framework\TestCase;

class LegacySqlDumpTest extends TestCase
{
    public function test_it_reads_tables_columns_and_rows_from_mysql_dump(): void
    {
        $path = tempnam(sys_get_temp_dir(), 'legacy-sql-');

        file_put_contents($path, <<<'SQL'
CREATE TABLE `pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` text,
  `name` text,
  `content` longtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
INSERT INTO `pages` VALUES (1,'glavnaya','Главная','Текст'),(2,'contacts','Контакты',NULL);
SQL);

        $dump = new LegacySqlDump($path);

        $this->assertSame(['pages'], $dump->tables());
        $this->assertSame(['id', 'url', 'name', 'content'], $dump->columns('pages'));
        $this->assertSame(2, $dump->rowCount('pages'));
        $this->assertSame('glavnaya', $dump->rows('pages', 1)[0]['url']);
        $this->assertNull($dump->rows('pages')[1]['content']);

        unlink($path);
    }
}
