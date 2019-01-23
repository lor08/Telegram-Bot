<?php
declare(strict_types=1);

namespace App\Storage;

class DB
{
    private $pdo;

    public function __construct(array $params)
    {
        $this->pdo = new \PDO($params['dsn'], $params['username'], $params['password']);
    }

    public function findAllProblems(): array
    {
        $problems = $this->pdo->query('SELECT content FROM problems');

        return $problems->fetchAll(\PDO::FETCH_OBJ);
    }

    public function saveProblems(string $content): void
    {
        $problem = $this->pdo->prepare('INSERT INTO problems(content) VALUES (:content)');
        $problem->bindParam('content', $content);
        $problem->execute();
    }

    public function getLastId(): int
    {
        $lastId = $this->pdo->query('SELECT MAX(id) AS lastId FROM problems');
        return $lastId->fetchObject()->lastId;
    }

    public function deleteFromLastId(int $lastId): void
    {
        $deletedPost = $this->pdo->prepare('DELETE FROM problems WHERE id = :lastId');
        $deletedPost->bindParam('lastId', $lastId);
        $deletedPost->execute();
    }


    public function find(int $id)
    {
        $problem = $this->pdo->prepare('SELECT content FROM problems WHERE id = :id');
        $problem->bindParam('id', $id);
        $problem->execute();
        return $problem->fetch(\PDO::FETCH_OBJ);
    }

    public function deleteProblemById(int $id): void
    {
        $problem = $this->pdo->prepare('DELETE FROM problems WHERE id = :id');
        $problem->bindParam('id', $id);
        $problem->execute();
    }

    public function addBotCommand(string $botCommand): void
    {
        $command = $this->pdo->prepare('INSERT INTO commands (bot_command) VALUES (:command)');
        $command->bindParam('command', $botCommand);
        $command->execute();
    }

    public function storeWinners(string $firstName, string $username, int $chatId)
    {
        $winners = $this->pdo->prepare('INSERT INTO winners(first_name, username, chat_id) VALUES (:first_name, :username, :chat_id)');
        $winners->bindParam('first_name', $firstName);
        $winners->bindParam('username', $username);
        $winners->bindParam('chat_id', $chatId);
        $winners->execute();
    }

    public function getWinnerInfo(int $chatId)
    {
        $userInfo = $this->pdo->prepare('SELECT score FROM winners WHERE chat_id = :chat_id');
        $userInfo->bindParam('chat_id', $chatId);
        $userInfo->execute();
        return $userInfo->fetch(\PDO::FETCH_OBJ);
    }

    public function updateScore(int $score, int $chatId)
    {
        $updatedScore = $this->pdo->prepare("UPDATE winners SET score = :score WHERE chat_id = :chat_id");
        $updatedScore->bindParam('score', $score);
        $updatedScore->bindParam('chat_id', $chatId);
        $updatedScore->execute();
    }

    public function findWinnersInfo()
    {
        $winners = $this->pdo->query("SELECT first_name, username, score FROM winners ORDER BY score DESC LIMIT 3");
        return $winners->fetchAll(\PDO::FETCH_OBJ);
    }

    public function getLastBotCommand(int $id)
    {
        $lastCommand = $this->pdo->prepare("SELECT bot_command FROM commands WHERE id = :id");
        $lastCommand->bindParam('id', $id);
        $lastCommand->execute();
        return $lastCommand->fetch(\PDO::FETCH_OBJ);
    }

    public function lastCommandId()
    {
        $command = $this->pdo->query("SELECT MAX(id) AS lastId FROM commands");
        return $command->fetchObject()->lastId;
    }


}