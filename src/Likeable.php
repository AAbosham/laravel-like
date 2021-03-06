<?php

namespace Aabosham\Likeable;

use Aabosham\Likeable\Models\Like;

trait Likeable
{
    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function scopeLikedBy($query, $user)
    {
        return $query->whereHas('likes', function ($query) use ($user) {
            $query->where('user_id', $user->id)
                ->where('isdeleted', 0);
        });
    }

    public function isLikedBy($type = 1, $user = null)
    {
        if ($user == null) {
            if (auth()->check()) {
                $user = auth()->user();
            } else {
                return false;
            }
        }

        return $this->likes()
            ->where('user_id', $user->id)
            ->where('isdeleted', 0)
            ->where('type', $type)
            ->exists();
    }

    public function getLikeType($user = null): string
    {
        $liked = $this->isLikedBy(1, $user);
        $disliked = $this->isLikedBy(-1, $user);

        if($liked){
            return 'like';
        }

        if($disliked){
            return 'dislike';
        }

        return 'none';
    }

    public function like($type = 1, $user = null)
    {
        if ($user == null) {
            if (auth()->check()) {
                $user = auth()->user();
            } else {
                return false;
            }
        }

        if (!in_array($type, [1, -1])) {
            return false;
        }

        $check_like = $this->likes()
            ->where('user_id', $user->id)
            ->where('isdeleted', 0);

        if ($check_like->exists()) {

            $like = $check_like->first();

            $like->type = $type;

            $like_save = $like->save();

            if (!$like_save) {
                return false;
            }

            return true;
        } else {
            $like = new Like();
            $like->uuid = \DB::raw('UUID()');
            $like->user_id = $user->id;
            $like->type = $type;

            $like_save = $this->likes()->save($like);

            if (!$like_save) {
                return false;
            }

            return true;
        }

        return false;
    }

    public function dislike($user = null)
    {
        return $this->like(-1, $user);
    }

    public function unlike($user = null)
    {
        if ($user == null) {
            if (auth()->check()) {
                $user = auth()->user();
            } else {
                return false;
            }
        }

        if ($this->likes()
            ->where('user_id', $user->id)
            ->where('isdeleted', 0)
            ->exists()
        ) {

            $unlike = $this->likes()
                ->where('user_id', $user->id)
                ->where('isdeleted', 0)
                // ->where('likeable_id', $this->id)
                ->update([
                    'isdeleted' => 1,
                    'deleted_at' => now(),
                    'deleted_by' => $user->id,
                ]);

            if (!$unlike) {
                return false;
            }

            return true;
        }

        return true;
    }

    public function likesCount()
    {
        return $this->likes()
            ->where('isdeleted', 0)
            ->where('type', 1)
            ->count();
    }

    public function disLikesCount()
    {
        return $this->likes()
            ->where('isdeleted', 0)
            ->where('type', -1)
            ->count();
    }

    public function likesCountDigital(): string
    {
        $count = $this->likesCount();

        return $this->countDigital($count);
    }

    public function disLikesCountDigital(): string
    {
        $count = $this->disLikesCount();

        return $this->countDigital($count);
    }

    public function countDigital($count): string
    {
        if ($count > (1000 * 1000)) {
            $count_string = ($count / (1000 * 1000)) . 'M';
        } else if ($count > 1000) {
            $count_string = ($count / 1000) . 'K';
        } else {
            $count_string = $count;
        }

        return (string) $count_string;
    }
}
