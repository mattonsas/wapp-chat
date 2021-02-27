<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Services\MessageBirdService;
use App\Events\MessageSent;

/**
 * Class MessageController
 *
 * @package App\Http\Controllers\API
 */
class MessageController extends Controller {
    /**
     * Get conversation messages
     *
     * @param                      $conversation
     * @param  MessageBirdService  $messageBirdService
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Foundation\Application|Response
     */
    public function get ($conversation, MessageBirdService $messageBirdService) {
        $messages = $messageBirdService->getConversationMessages($conversation);

        return response(['messages' => $messages], Response::HTTP_OK);
    }

    /**
     * Send message
     *
     * @param                      $conversation
     * @param  Request             $request
     * @param  MessageBirdService  $messageBirdService
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Foundation\Application|\Illuminate\Http\Response
     *
     * @throws \MessageBird\Exceptions\HttpException
     * @throws \MessageBird\Exceptions\RequestException
     * @throws \MessageBird\Exceptions\ServerException
     */
    public function send ($conversation, Request $request, MessageBirdService $messageBirdService) {
        $conversation = $messageBirdService->getConversation($conversation);

        $message = $messageBirdService->sendMessage($conversation, $request->get('message'));

        broadcast(new MessageSent($conversation, $message));

        return response(['msg' => 'success', 'message' => $message], Response::HTTP_OK);
    }
}
