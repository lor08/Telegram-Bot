<?php
declare(strict_types=1);

namespace App\Bot\Role\User;

use App\Handler;
use App\Storage\DB;

class PrivateChat
{
    private $bot;

    private $db;

    public function __construct(Handler $bot, DB $db)
    {
        $this->bot = $bot;
        $this->db = $db;
    }

    public function start()
    {
        if ($this->bot->getText() === "/start") {
            $this->bot->sendMessageToUser([
                'chat_id' => $this->bot->getChatId(),
                'text' => 'Вы можете предложить идею для развития библиотеки или отправить логическую задачу, которую мы обязательно опубликуем в чате Библиотеки. Спасибо.'
            ]);
        } elseif($this->bot->isBotCommand() === false) {
            $this->bot->sendMessageToUser([
                'chat_id' => $this->bot->getAdmin(),
                'text' => "Сообщение от @{$this->bot->getUsername()}({$this->bot->getChatId()}):\n{$this->bot->getText()}"
            ]);
            $this->bot->sendMessageToUser([
                'chat_id' => $$this->bot->getChatId(),
                'text' => "Спасибо, ваше сообщение отправлено администратору."
            ]);

        } elseif($this->bot->isBotCommand() === true) {
            $this->bot->sendMessageToUser([
                'chat_id' => $this->bot->getChatId(),
                'text' => 'Команды  боту не годятся в качестве сообщения администрации.'
            ]);

        }
    }
}