<?php

namespace Tests\Unit\Core;

class Db
{
    public function connect($host)
    {
        return 'connect to ' . $host;
    }
}
