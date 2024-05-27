<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use App\Models\Friend;
use App\Models\Product;
use App\Models\Barbecue;
use App\Models\BarbecueFriendship;
use App\Models\ChatMessage;
use App\Models\BasketProduct;
use App\Models\Basket;
use App\Models\Review;



class User extends Authenticatable
{
    use HasFactory, Notifiable, \Staudenmeir\LaravelMergedRelations\Eloquent\HasMergedRelationships;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'surnames',
        'description',
        'image',
        'vegetarian',
        'lactose',
        'gluten',
        'spicy',
        'halal',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'google_access_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    public function sentFriendRequests()
    {
        return $this->belongsToMany(User::class, 'friendships', 'user_id', 'friend_id')->withPivot('accepted')->withTimestamps();
    }

    public function recievedFriendRequests()
    {
        return $this->belongsToMany(User::class, 'friendships', 'friend_id', 'user_id')->withPivot('accepted')->withTimestamps();
    }

    public function acceptedSentFriendRequests()
    {
        return $this->belongsToMany(User::class, 'friendships', 'user_id', 'friend_id')->wherePivot('accepted', true);
    }

    public function acceptedRecievedFriendRequests()
    {
        return $this->belongsToMany(User::class, 'friendships', 'friend_id', 'user_id')->wherePivot('accepted', true);
    }

    public function friends()
    {
        return $this->mergedRelationWithModel(User::class, 'friends_view');
    }

    public function joinedBarbecues()
    {
        return $this->mergedRelationWithModel(User::class, 'joined_barbecues_view')->with('barbecue');
    }

    public function pendingFriendsRequests()
    {
        return $this->belongsToMany(User::class, 'friendships', 'friend_id', 'user_id')->wherePivot('accepted', false);
    }

    public function alreadySentFriendRequestTo(User $user)
    {
        return $this->sentFriendRequests()->where('friend_id', $user->id)->exists();
    }

    public function alreadyHasFriendRequestFrom(User $user)
    {
        return $this->recievedFriendRequests()->where('user_id', $user->id)->exists();
    }

    public function isTheSameAs(User $user)
    {
        return $this->id === $user->id;
    }

    public function sendFriendRequest(User $user)
    {
        if ($this->isTheSameAs($user)) {
            return;
        }

        if ($this->alreadySentFriendRequestTo($user)) {
            return;
        }

        if ($this->alreadyHasFriendRequestFrom($user)) {
            $this->acceptFriendRequest($user);
            return;
        }

        $this->sentFriendRequests()->attach($user->id, ['accepted' => false, 'created_at' => now()]);
    }

    public function acceptFriendRequest(User $user)
    {
        $friendship = Friendship::where('user_id', $user->id)
            ->where('friend_id', $this->id)
            ->first();

        if ($friendship) {
            $friendship->accepted = true;
            $friendship->save();
        }
    }

    public function isFriendWith(User $user)
    {
        return $this->friends()->where('id', $user->id)->exists();
    }

    public function barbecues()
    {
        return $this->hasMany(Barbecue::class)->with('user', 'images', 'comments');
    }

    public function sendBarbecueJoinRequest(Barbecue $barbecue)
    {
        $barbecue->recieveJoinRequest($this);
    }

    public function acceptBarbecueInvitation(Barbecue $barbecue)
    {
        $barbecue->recieveInvitationAcceptance($this);
    }
    public function removeFriend(User $user)
    {
        $this->sentFriendRequests()->detach($user->id);
        $this->recievedFriendRequests()->detach($user->id);
    }


    public function notifications()
    {
        // this method will return list of notifications
        // friend_requests, barbecue_invitations, barbecue_join_requests 

        // all notifications will be ordered by created_at desc, da igual cual es, estaran desordenadas
        $notifications = [];

        $friendRequests = $this->pendingFriendsRequests()->get();
        $barbecuesJoinRequests = $this->barbecuesJoinRequests();

        // $barbecueInvitations = $this->barbecueInvitations()->where('accepted', false)->with('barbecue', 'user');
        // also load the guest_id from 'users' table as 'guest' in the collection
        // $barbecueInvitations = $this->barbecueInvitations()->where('accepted', false)->with('barbecue', 'user')->get()->toArray();
        $barbecueInvitations = BarbecueFriendship::with('barbecue', 'user')->where('guest_id', $this->id)->where('accepted', false)->where('invitation', true)->get()->toArray();
        

        // dd($barbecueInvitations->get()->toArray());
        

        foreach ($friendRequests as $friendRequest) {
            $notifications[] = [
                'type' => 'friend_request',
                'friend' => $friendRequest,
                'created_at' => $friendRequest->created_at,
            ];
        }

        foreach ($barbecuesJoinRequests as $barbecueJoinRequest) {
            $barbecueJoinRequest['type'] = 'barbecue_join_request';
            if ($barbecueJoinRequest['invitation'] == false) {
                $notifications[] = [
                    'type' => 'barbecue_join_request',
                    'barbecue' => $barbecueJoinRequest['barbecue'],
                    'user' => $barbecueJoinRequest['user'],
                    'created_at' => $barbecueJoinRequest['created_at'],
                    'invitation' => false
                ];
            }

        }

        foreach ($barbecueInvitations as $barbecueInvitation) {
            $barbecueInvitation['type'] = 'barbecue_invitation';
            $notifications[] = [
                'type' => 'barbecue_invitation',
                'barbecue' => $barbecueInvitation['barbecue'],
                'user' => $barbecueInvitation['user'],
                'created_at' => $barbecueInvitation['created_at'],
                'invitation' => $barbecueInvitation['invitation'],
                'guest_id' => $barbecueInvitation['guest_id'],
            ];
        }

        usort($notifications, function ($a, $b) {
            return $a['created_at'] <=> $b['created_at'];
        });

        return $notifications;
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function basketProducts()
    {
        return $this->hasMany(BasketProduct::class);
    }
    public function friendsCount() {
        return $this->friends()->count();
    }

    public function barbecuesFriendships()
    {
        return $this->hasMany(BarbecueFriendship::class, 'user_id');
    }

    public function barbecuesFriendships2()
    {
        return $this->hasMany(BarbecueFriendship::class, 'guest_id');
    }

    public function barbecueInvitations()
    {
        return $this->hasMany(BarbecueFriendship::class, 'guest_id')->where('invitation', true);
    }

    public function barbecuesJoinRequests()
    {
        // $barbecuesCollection = $this->barbecues();

        // $barbecues = $barbecuesCollection->get()->toArray();

        // $reqs = [];

        // foreach ($barbecues as $barbecue) {
        //     // $barbecue->members = $barbecue->members()->get();
        //     $bbq = Barbecue::where('id', $barbecue['id'])->first();
        //     // dd only the first one bbq
        //     $bbqReqs = $bbq->requests()->get()->toArray();


        //     $bbqReqs = array_filter($bbqReqs, function ($req) {
        //         return $req['pivot']['accepted'] == 0;
        //     });
            
        //     foreach ($bbqReqs as $req) {
        //         $req['user'] = $req;
        //         $req['barbecue'] = $bbq->attributes;
        //         $reqs[] = $req;
        //     }
        // }
        // return $reqs;

        return BarbecueFriendship::with('barbecue', 'user')->where('guest_id', $this->id)->where('invitation', false)->where('accepted', false)->get()->toArray();

    }

    public function myBarbecues()
    {
        return $this->barbecuesFriendships()->get();
    }
    
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }


}