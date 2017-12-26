<?php
/**
 * |--------------------------------------------------------------------------
 * |
 * |--------------------------------------------------------------------------
 * Created by PhpStorm.
 * User: weaving
 * Date: 19/12/2017
 * Time: 1:40 PM
 */

namespace App\Observer\Art;


use App\Models\ArtShowComment;
use App\Notifications\ArtShowCommentReply;

class ArtShowCommentObserver
{


    public function created( ArtShowComment $comment )
    {
        $comment->art_show->increment('comment_count');

        //有parent_id 说明是回复
        if($comment->parent_id){

            $parent=ArtShowComment::find($comment->parent_id);
            //通知
            $parent->owner->notify(new ArtShowCommentReply($comment));
        }
    }

    public function deleting(ArtShowComment $comment){
        //顶级评论
        $comment->art_show->decrement('comment_count',1);

    }
}