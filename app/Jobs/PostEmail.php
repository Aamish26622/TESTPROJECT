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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PostEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Post::with(['website', 'postEmailUsers'])
            ->chunk(100, function ($posts) {
            foreach ($posts as $post) {
                Subscription::with('user')
                    ->where('website_id', $post->website_id)
                    ->chunk(100, function ($subscriptions) use ($post) {
                    foreach ($subscriptions as $subscription) {
                        if (!count($post->postEmailUsers->where('id', $subscription->user->id))) {
                            Mail::send('emails.new_post', array('post' => $post), function ($message) use ($subscription) {
                                $message->to($subscription->user->email)
                                    ->subject('New Post');
                            });
                            $post->postEmailUsers()->save($subscription->user);
                        }
                    }
                });
            }
        });
    }
}
