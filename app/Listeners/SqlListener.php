<?php

namespace App\Listeners;

class SqlListener
{
    private $logger;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        $this->logger = customerLoggerHandle('sql');
    }

    /**
     * Handle the event.
     *
     * @param  object $event
     * @return void
     */
    public function handle($event)
    {
        $i = 0;
        $rawSql = preg_replace_callback('/\?/', function ($matches) use ($event, &$i) {
            $item = isset($event->bindings[$i]) ? $event->bindings[$i] : $matches[0];
            $i++;

            return gettype($item) == 'string' ? "'$item'" : $item;
        }, $event->sql);
        $this->logger->debug($rawSql, [$event->time]);
    }
}
