<script setup>
import { useAuthStore } from "@/stores/auth";
import MainLayout from "@/Layouts/MainLayout.vue";
import UserLink from "@/Components/User/UserLink.vue";
import BarbecueLink from "@/Components/Barbecues/BarbecueLink.vue";
import axios from 'axios';

const authStore = useAuthStore();
authStore.updateUserData();

function formatDate(date) {
    return `${date.split('T')[0].split('-')[2]}/${date.split('T')[0].split('-')[1]}/${date.split('T')[0].split('-')[0]} a les ${date.split('T')[1].split(':')[0]}:${date.split('T')[1].split(':')[1]}`;
}

function acceptFriendRequest(id) {
    axios.post('/sendfriendrequest/' + id).then(() => {
        authStore.updateUserData();
    });
}

function rejectFriendRequest(id) {
    axios.delete('/friends/' + id).then(() => {
        authStore.updateUserData();
    });
}

function acceptBarbecueJoinRequest(barbecueId, userId) {
    axios.post(`acceptbarbecuejoinrequest/${barbecueId}/${userId}`).then(res => {
        console.log(res.data);
        authStore.updateUserData();
    });
}

function rejectBarbecueJoinRequest(barbecueId, userId) {
    axios.delete(route('rejectbarbecuejoinrequest', [barbecueId, userId])).then(res => {
        authStore.updateUserData();
    });
}

function acceptBarbecueInvitation(barbecueId, userId) {
    axios.post(`acceptbarbecueinvitation/${barbecueId}/${userId}`).then(res => {
        console.log(res.data);
        authStore.updateUserData();
    });
}

</script>

<template>
    <MainLayout title="Notificacions">
        <template #main-content>
            <div class="notifications-container">
                <div class="notification" v-for="notification in authStore.user?.notifications" :key="notification.id">
                    <div class="notification-content" v-if="notification.type == 'friend_request'">
                        <div class="notification-left">
                            <div class="profile-image">
                                <img :src="notification.friend?.image" alt="Imatge de perfil" />
                            </div>
                            <div>
                                <div class="profile-names">
                                    <strong>
                                        <UserLink :userId="notification.friend?.id"
                                            :name="notification.friend?.name + ' ' + notification.friend?.surnames" />
                                    </strong>
                                    <span>&nbsp;t'ha enviat una sol·licitud d'amistat.</span>
                                </div>
                                <div class="notification-date">
                                    {{ formatDate(notification.created_at) }}
                                </div>
                            </div>
                        </div>
                        <div class="notification-right">
                            <div class="notification-buttons">
                                <button @click="acceptFriendRequest(notification.friend.id)">Acceptar</button>
                                <button @click="rejectFriendRequest(notification.friend.id)">Rebutjar</button>
                            </div>
                        </div>
                    </div>
                    <div class="notification-content" v-if="notification.type == 'barbecue_join_request'">
                        <div class="notification-left">
                            <div class="profile-image">
                                <img :src="notification.user?.image" alt="Imatge de perfil" />
                            </div>
                            <div>
                                <div class="profile-names">
                                    <strong>
                                        <UserLink :userId="notification.friend?.id"
                                            :name="notification.user?.name + ' ' + notification.user?.surnames" />
                                    </strong>
                                    <span>&nbsp;es vol unir a&nbsp;</span>
                                    <strong>
                                        <BarbecueLink :barbecueId="notification.barbecue?.id" :name="notification.barbecue?.title" />
                                    </strong>
                                </div>
                                <div class="notification-date">
                                    {{ formatDate(notification.created_at) }}
                                </div>
                            </div>
                        </div>
                        <div class="notification-right">
                            <div class="notification-buttons">
                                <button @click="acceptBarbecueJoinRequest(notification.barbecue?.id, notification.user?.id)">Acceptar</button>
                                <button @click="rejectBarbecueJoinRequest(notification.barbecue?.id, notification.user?.id)">Rebutjar</button>
                            </div>
                        </div>
                    </div>
                    <div class="notification-content" v-if="notification.type == 'barbecue_invitation'">
                        <div class="notification-left">
                            <div class="profile-image">
                                <img :src="notification.user?.image" alt="Imatge de perfil" />
                            </div>
                            <div>
                                <div class="profile-names">
                                    <strong>
                                        <UserLink :userId="notification.friend?.id"
                                            :name="notification.user?.name + ' ' + notification.user?.surnames" />
                                    </strong>
                                    <span>&nbsp;t'ha invitat a&nbsp;</span>
                                    <strong>
                                        <BarbecueLink :barbecueId="notification.barbecue?.id" :name="notification.barbecue?.title" />
                                    </strong>
                                </div>
                                <div class="notification-date">
                                    {{ formatDate(notification.created_at) }}
                                </div>
                            </div>
                        </div>
                        <div class="notification-right">
                            <div class="notification-buttons">
                                <button @click="acceptBarbecueInvitation(notification.barbecue?.id, notification.guest_id)">Acceptar</button>
                                <button @click="rejectBarbecueJoinRequest(notification.barbecue?.id, notification.user?.id)">Rebutjar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </MainLayout>
</template>

<style scoped>

.notifications-container {
    display: grid;
    gap: 20px;
}

.notification {
    height: 80px;
    width: 100%;
    background: #fff;
    border-radius: 20px;
    padding: 20px;
}

.notification-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    height: 100%;
}

.profile-image {
    width: 50px;
    height: 50px;
}

.profile-image img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
}

.notification-left {
    display: flex;
    gap: 10px;
}

.notification-date {
    font-size: 12px;
    color: #777;
}

.notification-buttons {
    display: flex;
    gap: 10px;
}

.notification-buttons button {
    background: #FF6100;
    color: #fff;
    border: none;
    border-radius: 10px;
    padding: 10px 10px;
}

.notification-buttons button:last-child {
    background: #909090;
}
</style>