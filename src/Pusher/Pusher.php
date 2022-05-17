<?php
namespace Multipedidos;

class Pusher
{
    private $pusher;

    public function __construct($defaultConnection = 'pusher')
    {
        $settings = config('broadcasting.connections.'.$defaultConnection);

        $this->pusher = new Pusher\Pusher(
            $settings['key'],
            $settings['secret'],
            $settings['app_id'],
            $settings['options']
        );
    }

    public function trigger($channel, $event, $data = null)
    {
        return $pusher->trigger($channel, $event, $data);
    }

}