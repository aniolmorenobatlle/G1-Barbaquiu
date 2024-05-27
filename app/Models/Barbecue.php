<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\Image;
use App\Models\Comment;
use App\Models\BarbecueFriendship;
use App\Models\Review;

class Barbecue extends Model
{
    use HasFactory, \Staudenmeir\LaravelMergedRelations\Eloquent\HasMergedRelationships;

    protected $fillable = [
        'user_id',
        'latitude',
        'longitude',
        'address',
        'title',
        'content',
        'date',
    ];
    public function invitations()
    {
        return $this->belongsToMany(User::class, 'barbecues_friendships', 'user_id', 'guest_id')->withPivot('accepted', 'is_admin', 'barbecue_id')->withTimestamps();
    }

    public function requests()
    {
        return $this->belongsToMany(User::class, 'barbecues_friendships', 'guest_id', 'user_id')->wherePivot('invitation', false)->withPivot('accepted', 'is_admin', 'barbecue_id')->withTimestamps();
    }

    public function acceptedInvitations()
    {
        return $this->belongsToMany(User::class, 'barbecues_friendships', 'user_id', 'guest_id')->wherePivot('accepted', true);
    }

    public function acceptedRequests()
    {
        return $this->belongsToMany(User::class, 'barbecues_friendships', 'guest_id', 'user_id')->wherePivot('accepted', true);
    }

    public function members()
    {
        return $this->mergedRelationWithModel(User::class, 'barbecues_friendships_view');
    }

    public function sendInvitation(User $guest, User $user)
    {
        // $this->invitations()->attach($guest->id, ['accepted' => false, 'barbecue_id' => $this->id, 'invitation' => true]);

        BarbecueFriendship::create([
            'user_id' => $user->id,
            'guest_id' => $guest->id,
            'accepted' => false,
            'barbecue_id' => $this->id,
            'invitation' => true,
        ]);
    }

    public function recieveJoinRequest(User $user)
    {
        $this->requests()->attach($user->id, ['accepted' => false, 'barbecue_id' => $this->id]);
    }

    public function acceptJoinRequest(User $user)
    {
        $this->requests()->updateExistingPivot($user->id, ['accepted' => true, 'barbecue_id' => $this->id]);
    }

    public function recieveInvitationAcceptance(User $user)
    {
        $this->invitations()->updateExistingPivot($user->id, ['accepted' => true, 'barbecue_id' => $this->id]);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function basket()
    {
        return $this->HasOne(Basket::class);
    }
    
    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->with('user', 'replies')->orderBy('created_at', 'desc');
    }

    public function friendships()
    {
        return $this->hasMany(BarbecueFriendship::class);
    }

    public function messages()
    {
        return $this->hasMany(ChatMessage::class)->with('user');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
