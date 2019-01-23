<?php
declare(strict_types=1);

namespace App\Bot\Role\Admin;

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
        switch ($this->bot->getText()) {
            case "/start":
                $this->bot->sendMessageToUser([
                    'chat_id' => $this->bot->getChatId(),
                    'text' => "Привет, {$this->bot->getFirstName()}"
                ]);
                break;
            case "/delete":
                $this->db->deleteFromLastId($this->db->getLastId());
                $this->bot->sendMessageToUser([
                    'chat_id' => $this->bot->getChatId(),
                    'text' => 'Последняя задача удалена.'
                ]);
                break;
            default:
                $this->db->saveProblems($this->bot->getText());
                $this->bot->sendMessageToUser([
                    'chat_id' => $this->bot->getChatId(),
                    'text' => 'Данные успешно сохранены.'
                ]);
                break;
        }
    }

}