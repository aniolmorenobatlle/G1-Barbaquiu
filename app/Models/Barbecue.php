<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barbecue extends Model
{
    use HasFactory, \Staudenmeir\LaravelMergedRelations\Eloquent\HasMergedRelationships;

    protected $fillable = [
        'id',
        'user_id',
        'latitude',
        'longitude',
        'address',
        'title',
        'content',
    ];

    public function invitations()
    {
        return $this->belongsToMany(User::class, 'barbecues_friendships', 'user_id', 'guest_id')->withPivot('accepted', 'is_admin', 'barbecue_id')->withTimestamps();
    }

    public function requests()
    {
        return $this->belongsToMany(User::class, 'barbecues_friendships', 'guest_id', 'user_id')->withPivot('accepted', 'is_admin', 'barbecue_id')->withTimestamps();
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

    public function sendInvitation(User $user)
    {
        $this->invitations()->attach($user->id, ['accepted' => false, 'barbecue_id' => $this->id]);
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

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function images()
    {
        return $this->hasMany(Images::class, 'barbecue_id');
    }
}
