<?php
declare(strict_types=1);

namespace App\Bot\Role\Admin;

use App\Handler;
use App\Storage\DB;

class CommonChat
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
            case "/get@proglib_helper_bot":
            case "/get":
                $id = $this->db->getLastId();
                $this->bot->sendMessageToUser([
                    'chat_id' => $this->bot->getChatId(),
                    'text' => "#problem\n\n{$this->db->find($id)->content}"
                ]);
                $this->bot->pinTheMessage([
                    'chat_id'=> $this->bot->getChatId(),
                    'message_id' => $this->bot->getMessageId() + 1,
                    'disable_notification' => true
                ]);
                $this->db->deleteProblemById($id);
                $this->db->addBotCommand($this->bot->getText());
                break;
            case "/correct@proglib_helper_bot":
            case "/correct":
                $this->db->storeWinners($this->bot->getWinnerFirstName(), $this->bot->getWinnerUsername(), $this->bot->getWinnerId());
                $score = $this->db->getWinnerInfo($this->bot->getWinnerId())->score + 1;
                $this->db->updateScore($score, $this->bot->getWinnerId());
                $score = isset($this->db->getWinnerInfo($this->bot->getWinnerId())->score) ? $this->db->getWinnerInfo($this->bot->getWinnerId())->score : 1;
                $this->bot->sendMessageToUser([
                    'chat_id' => $this->bot->getChatId(),
                    'text' => "Правильный ответ! {$this->bot->getWinnerFirstName()} заработал(а) 1 балл. Всего баллов: {$score}",
                    'reply_to_message_id' => $this->bot->getReplyId()
                ]);
                $this->db->addBotCommand($this->bot->getText());
                break;
            case "/info@proglib_helper_bot":
            case "/info":
                $winners = $this->db->findWinnersInfo();
                if (isset($winners[0])) {
                    if (isset($winners[0]->username)) {
                        $this->bot->sendMessageToUser([
                            'chat_id' => $this->bot->getChatId(),
                            'text' => "Список лидеров:\n@{$winners[0]->username} - {$winners[0]->score}\n@{$winners[1]->username} - {$winners[1]->score}\n@{$winners[2]->username} - {$winners[2]->score}\n"
                        ]);
                    } else {
                        $this->bot->sendMessageToUser([
                            'chat_id' => $this->bot->getChatId(),
                            'text' => "Список лидеров:\n@{$winners[0]->first_name} - {$winners[0]->score}\n@{$winners[1]->first_name} - {$winners[1]->score}\n@{$winners[2]->first_name} - {$winners[2]->score}\n"
                        ]);
                    }
                } else {
                    $this->bot->sendMessageToUser([
                        'chat_id' => $this->bot->getChatId(),
                        'text' => "Лидеров нет."
                    ]);
                }
                break;
            case "/ban@proglib_helper_bot":
            case "/ban":
                $this->bot->blockUser([
                    'chat_id' => $this->bot->getChatId(),
                    'user_id' => $this->bot->getBlockUserId()
                ]);

                break;
        }
    }
}