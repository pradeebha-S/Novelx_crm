<?php



namespace App\Notifications;



use Illuminate\Bus\Queueable;

use Illuminate\Notifications\Notification;

use NotificationChannels\WebPush\WebPushMessage;

use NotificationChannels\WebPush\WebPushChannel;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Loginentries;
use App\Models\Task;
use App\Models\Leave;
use App\Models\Permission;



class SimplePushNotification extends Notification

{

    use Queueable;



    protected $title;

    protected $message;

    protected $url;

    public function __construct($title, $message, $url)

    {

        $this->title   = $title;

        $this->message = $message;

        $this->url     = $url;

    }



    public function via($notifiable)

    {

        return [WebPushChannel::class];

    }



    public function toWebPush($notifiable, $notification)

    {

        return (new WebPushMessage)

            ->title($this->title)

            ->body($this->message)

            ->data([

                'url' => $this->url

            ]);

    }

   
}


