<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\QaTopicCreateRequest;
use App\Http\Requests\QaTopicReplyRequest;
use App\Models\QaTopic;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
Use Alert;

class MessengerController extends Controller
{
    public function index()
    {
        $topics = QaTopic::where(function ($query) {
            $query
                ->where('creator_id', Auth::id())
                ->orWhere('receiver_id', Auth::id());
        })
            ->orderBy('created_at', 'DESC')
            ->simplePaginate(9);

        $title   = trans('global.all_messages');
        $unreads = $this->unreadTopics();

        return view('admin.messenger.index', compact('topics', 'title', 'unreads'));
    }

    public function createTopic()
    {
        $users = User::all()
            ->except(Auth::id());

        $unreads = $this->unreadTopics();

        return view('admin.messenger.create', compact('users', 'unreads'));
    }

    public function storeTopic(QaTopicCreateRequest $request)
    {

        // return $request;
        //prepare emails
        $emails = explode(',', $request->recepients);
        $emails = array_map('trim', $emails);
        $request['recepients'] =$emails;
        // return json_encode($ema)
        //create validator and verify email
        $request->validate([
            'recepients.*' => 'required|email',
        ]);

        $json_emails = json_encode($emails);
         $topic = QaTopic::create([
            'subject'     => $request->input('subject'),
            'creator_id'  => Auth::id(),
            'receiver_id' => 1,
            'receivers_email'=>$json_emails,
            'fromUser' =>$request->from_email
        ]);


        $topic->messages()->create([
            'sender_id' => Auth::id(),
            'content'   => $request->input('content'),
        ]);

        if($request->hasFile('attachment')){
            $topic->addMedia($request->attachment)
                  ->toMediaCollection('attachments');
        }

         $send_emails = [
             'emails'=>$emails,
              'subject'=>$request->subject,
              'body'=>$request->content,
              'fromUser'=>$request->from_email,
              'topic'   => $topic
            ];

         $this->sendMail($send_emails);

         Alert::toast('Emails sending in progress...', 'success')->timerProgressBar();

        return redirect()->route('admin.messenger.index')->with('success','Emails was added into 1s batch successully');
    }


    public function sendMail($send_emails)
    {
            $send_emails = $send_emails;

        $job = (new \App\Jobs\SendEmail($send_emails))
                ->delay(now()->addSeconds(2));

              dispatch($job);

        return true;
    }

    public function showMessages(QaTopic $topic)
    {
        $this->checkAccessRights($topic);

        foreach ($topic->messages as $message) {
            if ($message->sender_id !== Auth::id() && $message->read_at === null) {
                $message->read_at = Carbon::now();
                $message->save();
            }
        }

        $unreads = $this->unreadTopics();

        return view('admin.messenger.show', compact('topic', 'unreads'));
    }

    public function destroyTopic(QaTopic $topic)
    {
        $this->checkAccessRights($topic);

        $topic->delete();

        return redirect()->route('admin.messenger.index');
    }

    public function showInbox()
    {
        $title = trans('global.inbox');

        $topics = QaTopic::where('receiver_id', Auth::id())
            ->orderBy('created_at', 'DESC')
            ->take(5);

        $unreads = $this->unreadTopics();

        return view('admin.messenger.index', compact('topics', 'title', 'unreads'));
    }

    public function showOutbox()
    {
        $title = trans('global.outbox');

        $topics = QaTopic::orderBy('created_at', 'DESC')
            ->simplePaginate(9);

        $unreads = $this->unreadTopics();

        return view('admin.messenger.index', compact('topics', 'title', 'unreads'));
    }

    public function replyToTopic(QaTopicReplyRequest $request, QaTopic $topic)
    {
        $this->checkAccessRights($topic);

        $topic->messages()->create([
            'sender_id' => Auth::id(),
            'content'   => $request->input('content'),
        ]);

        return redirect()->route('admin.messenger.index');
    }

    public function showReply(QaTopic $topic)
    {
        $this->checkAccessRights($topic);



         $send_emails = [
            'emails'=>json_decode($topic->receivers_email),
             'subject'=> $topic->subject,
             'body'=> $topic->messages()->first()->content,
             'fromUser'=> $topic->fromUser,
             'topic' => $topic
           ];

        $this->sendMail($send_emails);

        Alert::toast('Emails sending in progress...', 'success')->timerProgressBar();



        $unreads = $this->unreadTopics();

        return view('admin.messenger.show', compact('topic', 'unreads'));
    }

    public function unreadTopics(): array
    {
        $topics = QaTopic::where(function ($query) {
            $query
                ->where('creator_id', Auth::id())
                ->orWhere('receiver_id', Auth::id());
        })
            ->with('messages')
            ->orderBy('created_at', 'DESC')
            ->simplePaginate();

        $inboxUnreadCount  = 0;
        $outboxUnreadCount = 0;

        foreach ($topics as $topic) {
            foreach ($topic->messages as $message) {
                if (
                    $message->sender_id !== Auth::id()
                    && $message->read_at === null
                ) {
                    if ($topic->creator_id !== Auth::id()) {
                        ++$inboxUnreadCount;
                    } else {
                        ++$outboxUnreadCount;
                    }
                }
            }
        }

        return [
            'inbox'  => $inboxUnreadCount,
            'outbox' => $outboxUnreadCount,
        ];
    }

    private function checkAccessRights(QaTopic $topic)
    {
        $user = Auth::user();

        if ($topic->creator_id !== $user->id && $topic->receiver_id !== $user->id) {
            return abort(401);
        }
    }
}
