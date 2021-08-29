<?php

namespace App\Jobs;

use App\Models\Post;
use App\Models\Subscription;
use App\Models\Website;
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
        Subscription::with('user')
            ->where('website_id', $this->post->website_id)
            ->chunk(100, function ($subscriptions) {
            foreach ($subscriptions as $subscription) {
                Mail::send('emails.new_post', array('post' => $this->post), function ($message) use ($subscription) {
                    $message->to($subscription->user->email)
                        ->subject('New Post');
                });
                $this->post->postEmailUsers()->save($subscription->user);
            }
        });
    }
}
