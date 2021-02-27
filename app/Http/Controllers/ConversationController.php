<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Services\MessageBirdService;

/**
 * Class ConversationController
 *
 * @package App\Http\Controllers
 */
class ConversationController extends Controller {
    /**
     * Get conversations page
     *
     * @param  MessageBirdService  $messageBirdService
     *
     * @return View
     */
    public function index (MessageBirdService $messageBirdService) : View {
        $conversations = $messageBirdService->getConversations();

        return view('conversations.index')
            ->with(['conversations' => $conversations]);
    }

    /**
     * Get specific conversation with messages
     *
     * @param  string              $conversation
     * @param  MessageBirdService  $messageBirdService
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|View
     * @throws \MessageBird\Exceptions\RequestException
     * @throws \MessageBird\Exceptions\ServerException
     */
    public function show (string $conversation, MessageBirdService $messageBirdService) {
        $conversationEntity = $messageBirdService->getConversation($conversation);

        $conversationMessages = $messageBirdService->getConversationMessages($conversationEntity->id);

        return view('conversations.show')
            ->with([
                'conversation' => $conversationEntity,
                'messages'     => $conversationMessages
            ]);
    }
}
