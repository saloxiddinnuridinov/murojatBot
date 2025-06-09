{{--@extends('admin.layouts.simple.master')--}}

{{--@section('css')--}}
{{--@endsection--}}

{{--@section('content')--}}
{{--    <style>--}}
{{--        .response-wrapper {--}}
{{--            margin-bottom: 25px;--}}
{{--            border-bottom: 1px solid #ddd;--}}
{{--            padding-bottom: 15px;--}}
{{--        }--}}

{{--        .response-header {--}}
{{--            font-weight: bold;--}}
{{--            font-size: 16px;--}}
{{--        }--}}

{{--        .response-body {--}}
{{--            margin-top: 10px;--}}
{{--            font-size: 14px;--}}
{{--        }--}}

{{--        .response-date {--}}
{{--            margin-top: 5px;--}}
{{--            font-size: 12px;--}}
{{--            color: #888;--}}
{{--        }--}}

{{--        .message-details {--}}
{{--            margin-bottom: 20px;--}}
{{--        }--}}

{{--        .message-details label {--}}
{{--            font-weight: bold;--}}
{{--        }--}}

{{--        .message-details p {--}}
{{--            margin-bottom: 0;--}}
{{--        }--}}

{{--        .container-fluid {--}}
{{--            max-width: 100%;--}}
{{--        }--}}
{{--    </style>--}}
{{--    <div class="container-fluid">--}}
{{--        <div class="col-sm-12">--}}
{{--            <div class="card">--}}
{{--                <div class="card-header pb-0">--}}
{{--                    <h5>Murojaat va javoblar</h5>--}}
{{--                </div>--}}
{{--                <div class="card-body">--}}
{{--                    <div class="row">--}}
{{--                        <div class="col-sm-12 message-details">--}}
{{--                            --}}{{--                            <div class="row">--}}
{{--                            --}}{{--                                <!-- Message Field -->--}}
{{--                            --}}{{--                                <div class="col-sm-2">--}}
{{--                            --}}{{--                                    <label for="message">Murojaat:</label>--}}
{{--                            --}}{{--                                </div>--}}
{{--                            --}}{{--                                <div class="col-sm-10">--}}
{{--                            --}}{{--                                    <p>{{ $message->message }}</p>--}}
{{--                            --}}{{--                                </div>--}}
{{--                            --}}{{--                            </div>--}}

{{--                            <div class="row">--}}
{{--                                <!-- User Field -->--}}
{{--                                <div class="col-sm-2">--}}
{{--                                    <label for="user">Foydalanuvchi:</label>--}}
{{--                                </div>--}}
{{--                                <div class="col-sm-10">--}}
{{--                                    <p>{{ $message->name . ' ' .  $message->surname}}</p>--}}
{{--                                </div>--}}
{{--                                <div class="col-sm-2">--}}
{{--                                    <label for="user">Telegram:</label>--}}
{{--                                </div>--}}
{{--                                <div class="col-sm-10">--}}
{{--                                    <p>{{ $message->username . ' ' .  $message->phone}}</p>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}

{{--                        <!-- Responses Field -->--}}
{{--                        @foreach($message->messages as $message)--}}
{{--                            <div class="col-sm-12">--}}
{{--                                <h5>Savol: </h5>--}}
{{--                                <div class="response-wrapper">--}}
{{--                                    <div class="response-body">--}}
{{--                                        {!! nl2br(e($message->message)) !!}--}}
{{--                                    </div>--}}
{{--                                    <div class="response-date">--}}
{{--                                        Savol berilgan sana: {{ $message->created_at->format('Y-m-d H:i') }}--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="col-sm-12">--}}
{{--                                <h5>Javoblar:</h5>--}}
{{--                                @forelse ($message->answers as $answer)--}}
{{--                                    <div class="response-wrapper">--}}
{{--                                        <div class="response-header">--}}
{{--                                            Javob beruvchi: {{ $answer->user->name . ' ' . $answer->user->surname }}--}}
{{--                                        </div>--}}
{{--                                        <div class="response-body">--}}
{{--                                            {!! nl2br(e($answer->answer)) !!}--}}
{{--                                        </div>--}}
{{--                                        <div class="response-date">--}}
{{--                                            Javob berilgan sana: {{ $answer->created_at->format('Y-m-d H:i') }}--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                @empty--}}
{{--                                    <p>Bu murojaatga hali javob berilmagan.</p>--}}
{{--                                @endforelse--}}
{{--                            </div>--}}
{{--                        @endforeach--}}

{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="card-footer">--}}
{{--                    <a class="btn btn-primary" href="{{ route('admin.messages.index') }}">Orqaga</a>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--@endsection--}}

{{--@section('script')--}}
{{--@endsection--}}


@extends('admin.layouts.simple.master')


@section('content')
    <style>
        .chat-container {
            max-width: 100%;
            margin: 0 auto;
            padding: 20px;
        }

        .chat-header {
            background-color: #f8f9fa;
            padding: 15px;
            border-bottom: 1px solid #dee2e6;
            margin-bottom: 20px;
        }

        .user-info {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }

        .chat-messages {
            margin-bottom: 30px;
        }

        .message {
            margin-bottom: 20px;
            padding: 15px;
            border-radius: 10px;
            position: relative;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .question {
            background-color: #e3f2fd;
            margin-right: 20%;
            margin-left: 0;
            border-top-left-radius: 0;
        }

        .answer {
            background-color: #f1f1f1;
            margin-left: 20%;
            margin-right: 0;
            border-top-right-radius: 0;
        }

        .message-header {
            font-weight: bold;
            margin-bottom: 5px;
            display: flex;
            justify-content: space-between;
        }

        .message-content {
            margin-bottom: 5px;
            white-space: pre-wrap;
        }

        .message-time {
            font-size: 12px;
            color: #6c757d;
            text-align: right;
        }

        .reply-btn {
            margin-top: 10px;
            background-color: #4e73df;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .reply-btn:hover {
            background-color: #3a5bc7;
        }

        .no-answers {
            color: #6c757d;
            font-style: italic;
            padding: 15px;
            text-align: center;
            background-color: #f8f9fa;
            border-radius: 5px;
            margin-top: 10px;
        }

        .back-btn {
            margin-top: 20px;
        }

        /* Modal styling */
        .modal-textarea {
            min-height: 150px;
            resize: vertical;
        }

        .message-avatar {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: #4e73df;
            color: white;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            font-weight: bold;
        }
    </style>

    <div class="container-fluid">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header pb-0">
                    <h5>Murojaat va javoblar</h5>
                </div>

                <div class="card-body">
                    <div class="chat-container">
                        <!-- User Information -->
                        <div class="user-info">
                            <h5>Foydalanuvchi ma'lumotlari</h5>
                            <div class="d-flex align-items-center mb-2">
                                <div class="message-avatar">
                                    {{ strtoupper(substr($message->name, 0, 1)) }}{{ strtoupper(substr($message->surname, 0, 1)) }}
                                </div>
                                <div>
                                    <strong>{{ $message->name }} {{ $message->surname }}</strong>
                                </div>
                            </div>
                            <p><strong>Telegram:</strong> @{{ $message->username }} | {{ $message->phone }}</p>
                        </div>

                        <!-- Chat Messages -->
                        <div class="chat-messages">
                            @foreach($message->messages as $msg)
                                <!-- Question -->
                                <div class="message question">
                                    <div class="message-header">
                                        <span>Savol</span>
                                    </div>
                                    <div class="message-content">
                                        {!! nl2br(e($msg->message)) !!}
                                    </div>
                                    <div class="message-time">
                                        {{ $msg->created_at->format('Y-m-d H:i') }}
                                    </div>

                                    <button class="btn btn-primary" data-toggle="modal"
                                            data-target="#replyModal{{ $msg->id }}">
                                        <i class="fa fa-reply"></i> Javob yozish
                                    </button>

                                    <!-- Modal -->
                                    <div class="modal fade" id="replyModal{{ $msg->id }}" tabindex="-1" role="dialog"
                                         aria-labelledby="replyModalLabel{{ $msg->id }}" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <form action="{{ route('admin.messages.reply') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="selected_message" value="{{ $msg->id }}">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="replyModalLabel{{ $msg->id }}">Javob
                                                            yozish</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Yopish">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <textarea name="reply" class="form-control modal-textarea"
                                                                  placeholder="Javob yozing..." required></textarea>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Bekor qilish
                                                        </button>
                                                        <button type="submit" class="btn btn-primary">Yuborish</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                </div>

                                <!-- Answers -->
                                @if(count($msg->answers) > 0)
                                    @foreach($msg->answers as $answer)
                                        <div class="message answer">
                                            <div class="message-header">
                                                <span>Javob</span>
                                                <span>{{ $answer->user->name }} {{ $answer->user->surname }}</span>
                                            </div>
                                            <div class="message-content">
                                                {!! nl2br(e($answer->answer)) !!}
                                            </div>
                                            <div class="message-time">
                                                {{ $answer->created_at->format('Y-m-d H:i') }}
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="no-answers">
                                        Bu murojaatga hali javob berilmagan.
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <a class="btn btn-primary back-btn" href="{{ route('admin.messages.index') }}">Orqaga</a>
                </div>
            </div>
        </div>
    </div>
    <!-- jQuery (Bootstrap 4 uchun kerak) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

@endsection
