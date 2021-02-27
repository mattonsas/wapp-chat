<?php

namespace App\Services;

use MessageBird\Client;
use MessageBird\Objects\Conversation\Content;
use MessageBird\Objects\Conversation\Message;
use MessageBird\Objects\Conversation\Conversation;

/**
 * Class MessageBirdService
 *
 * @package App\Services
 */
class MessageBirdService {
    /**
     * MessageBird API mode (live / sandbox)
     *
     * @var string $mode
     */
    private $mode;

    /**
     * MessageBird API key
     *
     * @var string $access_key
     */
    private $access_key;

    /**
     * MessageBird API client instance
     *
     * @var Client $client
     */
    private $client;

    /**
     * MessageBirdService constructor.
     */
    public function __construct () {
        $this->mode = config('services.messagebird.mode');
        $this->access_key = config('services.messagebird.access_key');

        if ($this->mode === 'live') {
            $this->client = new Client($this->access_key);
        } else {
            $this->client = new Client($this->access_key, null, [Client::ENABLE_CONVERSATIONSAPI_WHATSAPP_SANDBOX]);
        }
    }

    /**
     * Get list of conversations
     *
     * @param  array  $params  Optional params
     *
     * @return \MessageBird\Objects\BaseList|\MessageBird\Resources\Conversation\Conversations
     */
    public function getConversations (array $params = []) {
        return $this->client->conversations->getList($params);
    }

    /**
     * Get specific conversation
     *
     * @param  string  $conversationId  Conversation ID
     *
     * @return \MessageBird\Resources\Conversation\Conversations
     *
     * @throws \MessageBird\Exceptions\RequestException
     * @throws \MessageBird\Exceptions\ServerException
     */
    public function getConversation (string $conversationId) {
        return $this->client->conversations->read($conversationId);
    }

    /**
     * Get conversation messages
     *
     * @param  string  $conversationId  Conversation ID
     * @param  array   $params          Optional params
     *
     * @return \MessageBird\Objects\BaseList|\MessageBird\Resources\Conversation\Messages
     */
    public function getConversationMessages (string $conversationId, array $params = []) {
        return $this->client->conversationMessages->getList($conversationId, $params);
    }

    /**
     * Send message to conversation
     *
     * @param  Conversation  $conversation
     * @param  string        $text
     *
     * @return Message|\MessageBird\Resources\Conversation\Messages
     *
     * @throws \MessageBird\Exceptions\HttpException
     * @throws \MessageBird\Exceptions\RequestException
     * @throws \MessageBird\Exceptions\ServerException
     */
    public function sendMessage (Conversation $conversation, string $text) {
        $content = new Content;
        $content->text = $text;

        $message = new Message;
        $message->channelId = config('services.messagebird.channel_id');
        $message->content = $content;
        $message->to = $conversation->contact->id;
        $message->type = Content::TYPE_TEXT;

        $message = $this->client->conversationMessages->create($conversation->id, $message);

        return $message;
    }
}