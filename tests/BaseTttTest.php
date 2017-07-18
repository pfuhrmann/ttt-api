<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

abstract class BaseTttTest extends TestCase
{
    protected function buildLayout(array $layoutTypes): array
    {
        $rows = count($layoutTypes);
        $columns = count($layoutTypes[0]);
        $layout = [];
        for ($row = 0; $row < $rows; $row++) {
            for ($column = 0; $column < $columns; $column++) {
                $layout[$row][$column]['type'] = $layoutTypes[$row][$column];
            }
        }

        return $layout;
    }
}
