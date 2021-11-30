<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $messagedata;
    public $emailsubject;
    public $fromUser;
    public $topic;

    public function __construct($message,$emailsubject,$fromUser, $topic)
    {
        $this->messagedata = $message;
        $this->emailsubject = $emailsubject;
        $this->fromUser = $fromUser;
        $this->topic = $topic;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {


        return $this->from($this->fromUser)
        ->view('mails.mail')
        ->subject($this->emailsubject)
        ->attach($this->topic->getMedia('attachments')->first()->getPath(), [
            'as' => $this->topic->getMedia('attachments')->first()->getCustomProperty('filename'),
            'mime' => $this->topic->getMedia('attachments')->first()->mime_type,
        ]);
        ;

        return $this->view('view.name');
    }
}
