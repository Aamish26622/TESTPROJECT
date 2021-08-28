<?php

namespace App\Jobs;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
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
        try {
            $posts = Post::with(['website.subscribers.user.postEmail', 'postEmailUsers'])->get();
            foreach ($posts as $post) {
                foreach ($post->website->subscribers as $subscriber) {
                    if (!count($subscriber->user->postEmail)) {
                        Mail::send('emails.new_post', array('post' => $post), function ($message) use ($subscriber) {
                            $message->to($subscriber->user->email)
                                ->subject('New Post');
                        });
                        $post->postEmailUsers()->save($subscriber->user);
                    }
                }
            }
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
}
