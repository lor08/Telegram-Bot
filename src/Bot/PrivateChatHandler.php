<?php
declare(strict_types=1);

namespace App\Bot;

use App\Bot\Role\Admin\PrivateChat as Admin;
use App\Bot\Role\User\PrivateChat as User;
use App\Handler;
use App\Storage\DB;

class PrivateChatHandler
{
    private $db;

    private $bot;

    public function __construct(Handler $bot, DB $db)
    {
        $this->bot = $bot;
        $this->db = $db;
    }

    public function start()
    {
        if ($this->bot->isAdmin()) {
            (new Admin($this->bot, $this->db))->start();
        } else {
            (new User($this->bot, $this->db))->start();
        }
    }
}