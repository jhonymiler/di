<?php

namespace Tests\Unit\Core;


class Logger
{
    public function log($message)
    {
        echo "Logging: {$message}\n";
    }
}
