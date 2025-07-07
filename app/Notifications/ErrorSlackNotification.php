<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class ErrorSlackNotification extends Notification
{
    use Queueable;
    protected $exception;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(\Exception $exception)
    {
        $this->$exception = $exception;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['slack'];
    }

    /**
     * Get the Slack representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\SlackMessage
     */
    public function toSlack($notifiable)
    {
        $e = $this->exception;

        if(!is_object($e)) {
            return (new SlackMessage)
                ->error()
                ->content('*Exception Triggered*')
                ->attachment(function ($attachment) {
                    $attachment->fields([
                        'Environment' => config('app.env'),
                        'Message' => 'Non-object exception received',
                        'Code' => 500,
                        'Location' => 'Unknown',
                        'Trace' => 'No stack trace available'
                    ]);
                });
        }

        $message = method_exists($e, 'getMessage') ? $e->getMessage() : 'Unknown error';
        $code = method_exists($e, 'getCode') ? $e->getCode() : 500;
        $file = method_exists($e, 'getFile') ? $e->getFile() : 'Unknown file';
        $line = method_exists($e, 'getLine') ? $e->getLine() : 0;

        $trace = method_exists($e, 'getTraceAsString')
            ? substr($e->getTraceAsString(), 0, 1000)
            : 'No stack trace available';

        return (new SlackMessage)
            ->error()
            ->content('*Exception Triggered*')
            ->attachment(function ($attachment) use ($message, $code, $file, $line, $trace) {
                $attachment->fields([
                    'Environment' => config('app.env'),
                    'Message' => $message,
                    'Code' => $code,
                    'Location' => "{$file}:{$line}",
                    'Trace' => "```{$trace}```"
                ]);
            });
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [

        ];
    }
}
