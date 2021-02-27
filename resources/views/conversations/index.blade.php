@extends('conversations.layouts.app')

@section('content')
    <h2>Conversations</h2>

    <div class="list-group">
        @forelse($conversations->items as $conversation)
            <a href="{{ route('conversations.show', ['conversation' => $conversation->id]) }}"
               class="list-group-item d-flex justify-content-between align-items-center list-group-item-action">
                {{ $conversation->contact->msisdn }}

                <span class="badge badge-primary badge-pill">{{ $conversation->messages->totalCount }}</span>
            </a>
        @empty
            no messages...
        @endforelse
    </div>
@endsection
