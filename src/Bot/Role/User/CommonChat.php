<?php
declare(strict_types=1);

namespace App\Bot\Role\User;

use App\Handler;
use App\Storage\DB;


class CommonChat
{
    private $db;

    private $bot;

    public function __construct(Handler $bot, DB $db)
    {
        $this->bot = $bot;
        $this->db  = $db;
    }

    public function start()
    {
        if ($this->bot->getText() === "/get@proglib_helper_bot") {
            $last_command = $this->db->getLastBotCommand($this->db->lastCommandId())->bot_command;
            if ($this->bot->getText() === $last_command) {
                $this->bot->deleteTheMessage([
                    'chat_id' => $this->bot->getChatId(),
                    'message_id' => $this->bot->getMessageId()
                ]);
            } elseif ($this->bot->getText() !== $last_command) {
                $this->db->addBotCommand($this->bot->getText());
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

            }
        } elseif ($this->bot->getText() === "/correct@proglib_helper_bot" || $this->bot->getText() === "/info@proglib_helper_bot" || $this->bot->getText() === "/ban@proglib_helper_bot" || $this->bot->getText() === "/correct" || $this->bot->getText() === "/info"  || $this->bot->getText() === "/ban" || $this->bot->getText() === "/get" || $this->bot->getText() === "/me" || $this->bot->getText() === "#problem" || $this->bot->getText() === "/problem" || $this->bot->getText() === "/delete" || $this->bot->getText() === "/delete@proglib_helper_bot" || $this->bot->getText() === "/restrict@proglib_helper_bot" || $this->bot->getSticker() || $this->bot->getVideo() || $this->bot->getDocument()) {
            $this->bot->deleteTheMessage([
                'chat_id' => $this->bot->getChatId(),
                'message_id' => $this->bot->getMessageId()
            ]);
        } elseif ($this->bot->getText() === "+") {
            if ($this->bot->getMessageId() === $this->bot->getReplyId() || $this->bot->getReplyToMessageId() === $this->bot->getAdmin()) {
                $this->bot->sendMessageToUser([
                    'chat_id' => $this->bot->getChatId(),
                    'text' => "Нельзя голосовать за себя, бота или админа, а также нельзя голосовать повторно.",
                    'reply_to_message_id' => $this->bot->getMessageId()
                ]);
            } else {
                $score = isset($this->db->getWinnerInfo($this->bot->getWinnerId())->score) ? $this->db->getWinnerInfo($this->bot->getWinnerId())->score : 1;

                if ($score < 4) {
                    $this->bot->sendMessageToUser([
                        'chat_id' => $this->bot->getChatId(),
                        'text' => "@{$this->bot->getWinnerUsername()} +{$score}",
                        'reply_to_message_id' => $this->bot->getReplyId()
                    ]);
                } else {
                    $this->bot->sendMessageToUser([
                        'chat_id' => $this->bot->getChatId(),
                        'text' => "@{$this->bot->getWinnerUsername()} выиграл!",
                        'reply_to_message_id' => $this->bot->getReplyId()
                    ]);

                    $this->db->addBotCommand("/correct@proglib_helper_bot");
                    $this->db->storeWinners($this->bot->getWinnerFirstName(), $this->bot->getWinnerUsername(), $this->bot->getWinnerId());
                    $score = $this->db->getWinnerInfo($this->bot->getWinnerId())->score + 1;
                    $this->db->updateScore($score, $this->bot->getWinnerId());
                }
            }
        }

    }

}