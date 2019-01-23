<?php
declare(strict_types=1);

namespace App;

use Telegram\Bot\Api;
use Telegram\Bot\Exceptions\TelegramSDKException;

class Handler
{
    /** @var Api $api */
    private $api;


    public function __construct(Api $api)
    {
        $this->api = $api;
    }

    private function getWebhookUpdate()
    {
        return $this->api->getWebhookUpdate();
    }

    public function getText(): ?string
    {
        return $this->getWebhookUpdate()->getMessage()->getText();
    }

    public function getBlockUserId()
    {
        return $this->getWebhookUpdate()->getMessage()->getReplyToMessage()->getFrom()->getId();
    }

    public function getChatId(): int
    {
        return $this->getWebhookUpdate()->getChat()->getId();
    }

    public function getMessageSender(): string
    {
        return $this->getWebhookUpdate()->getMessage()->getFrom()->getUsername();
    }

    public function getMessageId(): int
    {
        return $this->getWebhookUpdate()->getMessage()->getMessageId();
    }

    public function getReplyId(): int
    {
        return $this->getWebhookUpdate()->getMessage()->getReplyToMessage()->getMessageId();
    }

    public function getReplyToMessageId()
    {
        return $this->getWebhookUpdate()->getMessage()->getReplyToMessage()->getFrom()->getId();
    }

    public function getUsername(): string
    {
        return $this->getWebhookUpdate()->getChat()->getUsername();
    }

    public function getFirstName(): string
    {
        return $this->getWebhookUpdate()->getChat()->getFirstName();
    }

    public function getChatType(): string
    {
        return $this->getWebhookUpdate()->getMessage()->getChat()->getType();
    }

    public function getBlockedUserId(): int
    {
        return $this->getWebhookUpdate()->getMessage()->getReplyToMessage()->getFrom()->getId();
    }

    public function isAdmin(): bool
    {
        if (!$this->getUsername() === $this->getAdmin()) {
            return false;
        }
        return true;
    }


    public function isBotCommand()
    {
        $entities = str_split($this->getText());
        if (! in_array("/", $entities)) {
            return false;
        }
        return true;
    }

    public function isAdminOnGroupChat(): bool
    {
        if (!$this->getMessageSender() === $this->getAdmin()) {
            return false;
        }
        return true;
    }

    public function getWinnerFirstName()
    {
        return $this->getWebhookUpdate()->getMessage()->getReplyToMessage()->getFrom()->getFirstName();
    }

    public function getWinnerId()
    {
        return $this->getWebhookUpdate()->getMessage()->getReplyToMessage()->getFrom()->getId();
    }

    public function getWinnerUsername()
    {
        return $this->getWebhookUpdate()->getMessage()->getReplyToMessage()->getFrom()->getUsername();
    }


    public function isBot(): bool
    {
        return $this->getWebhookUpdate()->getMessage()->getFrom()->getIsBot();
    }

    public function sendMessageToUser(array $params)
    {
        try {
            $this->api->sendMessage($params);
        } catch (TelegramSDKException $e) {
            print $e->getMessage();
        }
    }

    public function blockUser(array $params)
    {
        try {
            $this->api->kickChatMember($params);
        } catch (TelegramSDKException $e) {
            print $e->getMessage();
        }
    }

    public function pinTheMessage(array $params)
    {
        try {
            $this->api->pinChatMessage($params);
        } catch (TelegramSDKException $e) {
            print $e->getMessage();
        }
    }

    public function deleteTheMessage(array $params)
    {
        return $this->api->deleteMessage($params);
    }


    public function getAdmin(): ?string
    {
        return getenv('BOT_ADMIN');
    }
}