<?php

class Service
{
    private $telegram;
    private $redis;

    public function __construct($telegram)
    {
        $this->redis = new Redis();
        $this->redis->connect('127.0.0.1', 6379);
        $this->telegram = $telegram;
        $this->chat_id=(!is_null($telegram->Callback_Data()))?$telegram->Callback_Query()['from']['id']:$telegram->ChatID();
    }

    public function __deconstruct()
    {
        $this->redis->close();
    }

    public function debug($array){
        $this->telegram->sendMessage([
            'chat_id' =>32512143,
            'text' => print_r($array,True)
        ]);
    }
}
