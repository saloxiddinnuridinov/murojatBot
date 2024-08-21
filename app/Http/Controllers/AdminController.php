<?php

namespace App\Http\Controllers;

use App\Http\Helpers\TelegramManager;
use App\Models\Answer;
use App\Models\TelegramUser;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|exists:users,email',
            'password' => 'required'
        ]);
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return redirect()->back()->withErrors(['password' => 'User is not found']);
        } else {
            if (Hash::check($request->password, $user->password)) {
                if ($user->is_active == 1) {
                    Auth::login($user);
                    return view('admin.dashboard');
                } else {
                    return redirect()->back()->withErrors(['password' => 'User is not active']);
                }
            } else {
                return redirect()->back()->withErrors(['password' => 'Password is incorrect']);
            }
        }
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Message::with('telegramUser');

        if ($request->has('date')) {
            $date = $request->input('date');
            $query->whereDate('created_at', $date);
        }

        // Paginate messages
        $perPage = 10; // Set the number of items per page
        if ( $user->specialist == 'All') {
            $messages = $query->where('answered', 0)->latest()->paginate($perPage);
        }else {
            $messages = $query->where('answered', 0)->where('type', $user->specialist)->latest()->paginate($perPage);
        }

        // Group paginated messages by telegram_user_id
        $groupedMessages = $messages->getCollection()->groupBy('telegram_user_id');

        // Replace the paginated items with the grouped messages
        $messages->setCollection(collect($groupedMessages));

        return view('admin.post.index', compact('messages'));
    }

    public function getAnswers(Request $request)
    {
        $query = Message::with('telegramUser');

        if ($request->has('date')) {
            $date = $request->input('date');
            $query->whereDate('created_at', $date);
        }

        // Paginate messages
        $perPage = 10; // Set the number of items per page
        $messages = $query->where('answered', 1)->latest()->paginate($perPage);

        // Group paginated messages by telegram_user_id
        $groupedMessages = $messages->getCollection()->groupBy('telegram_user_id');

        // Replace the paginated items with the grouped messages
        $messages->setCollection(collect($groupedMessages));

        return view('admin.post.answer', compact('messages'));
    }


    public function show($telegram_user_id)
    {
        $message = TelegramUser::with(['messages.answers'])->find($telegram_user_id);

        return view('admin.post.show', compact('message'));
    }

    public function reply(Request $request)
    {
        $validated = $request->validate([
            'selected_message' => 'required|exists:messages,id',
            'reply' => 'required|string',
        ]);

        $message = Message::findOrFail($validated['selected_message']);
        $replyText = $validated['reply'];

        // Send the reply as a reply to the original message
        app(TelegramBotController::class)->replyToUser($message->telegramUser->telegram_id, $replyText, $message->telegram_message_id);

        $message->answered = 1;
        $message->update();

        $answer = new Answer();
        $answer->user_id = Auth::user()->id;
        $answer->message_id = $message->id;
        $answer->answer = $replyText;
        $answer->save();

        $t_user = TelegramUser::where('id', $message->telegram_user_id)->first();
        $text = "Savol: $message->message " . " Ism: " . $t_user->name . ' ' . $t_user->surname. " Javob: $answer->answer Admin: " . Auth::user()->name . ' ' . Auth::user()->surname;
        TelegramManager::sendTelegram($text);
        return redirect()->route('admin.messages.index')->with('success', 'Reply sent successfully.');
    }


}
