<?php

namespace App\Jobs;

use App\Models\Post;
use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class PostEmailSingle implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $post;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $subscribers = Subscription::with('user.postEmail')
            ->where('website_id', $this->post->website_id)
            ->get();
        foreach ($subscribers as $subscriber) {
            if (!count($this->post->postEmailUsers->where('id', $subscriber->user->id))) {
                Mail::send('emails.new_post', array('post' => $this->post), function ($message) use ($subscriber) {
                    $message->to($subscriber->user->email)
                        ->subject('New Post');
                });
                $this->post->postEmailUsers()->save($subscriber->user);
            }
        }
    }
}
