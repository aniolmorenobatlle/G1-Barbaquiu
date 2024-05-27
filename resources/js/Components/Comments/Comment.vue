<script setup>
import { ref, defineProps, defineEmits } from "vue";
import Comment from "@/Components/Comments/Comment.vue"
import UserLink from "@/Components/User/UserLink.vue"
import Timestamp from "@/Components/Time/Timestamp.vue"
import axios from "axios";

const emit = defineEmits(["writeComment"]);

const props = defineProps({
    comment: {
        type: Object,
        required: true
    },
    level: {
        type: Number,
        required: false,
        default: 0
    },
    isLast: {
        type: Boolean,
        required: false,
        default: false
    },
    isFirst: {
        type: Boolean,
        required: false,
        default: false
    }
});

const newReplies = ref([]);

const showReplies = ref(false);
const answering = ref(false);

function toggleReplies() {
    showReplies.value = !showReplies.value;
}

function writeComment(treePath) {
    let tp;
    if (treePath.constructor.name == "PointerEvent") {
        tp = [props.comment.id];
        emit("writeComment", tp);
        console.log(tp);
        return;
    }
    tp = [props.comment.id, ...treePath];
    console.log(tp);    
    emit("writeComment", tp);
}

</script>

<template>
    <div class="comment-container">
        <div class="comment-vertical-branch" v-if="!isLast || (comment.replies.length && showReplies)"
            :style="{
                left: (level * 80) + 24 + 'px',
                height: isLast ? '68px' : 'calc(100%)'
            }">
        </div>
        <div class="comment-horizontal-branch" v-if="isFirst && level > 0"
            :style="{ left: (level * 80) - 56 + 'px' }">
        </div>
        <div class="comment-itself-wrapper" :style="{ marginLeft: level * 80 + 'px' }">
            <div class="comment-itself">
                <div class="comment-profile-image">
                    <img :src="props.comment?.user?.image" alt="Profile image">
                </div>
                <div class="comment-itself-content" :id="'comment-' + props.comment.id">
                    <div class="comment-itself-header">
                        <UserLink :userId="props.comment?.user?.id"
                            :name="props.comment?.user?.name + ' ' + props.comment?.user?.surnames" />
                        <Timestamp :datetime="props.comment.created_at" class="comment-timestamp" />
                    </div>
                    <div class="comment-itself-text">
                        <span>{{ props.comment?.comment }}</span>
                    </div>
                </div>
            </div>
            <div class="comment-buttons" v-if="level < 3">
                <button @click="writeComment" class="comment-toggle-answer-button">
                    {{ answering ? 'Cancel·lar' : 'Respondre' }}
                </button>
                <button v-if="props.comment?.replies?.length" @click="toggleReplies">
                    {{ showReplies ? 'Amaga' : 'Mostra' }}
                    {{ props.comment?.replies?.length }} respost{{ props.comment?.replies?.length > 1 ? 'es' : 'a' }}
                </button>
            </div>
        </div>
        <div class="comment-replies" v-if="showReplies && props.comment?.replies?.length">
            <Comment
            v-for="(reply, index) in [...newReplies, ...comment.replies]"
            :comment="reply" :level="level + 1"
            class="comment"
            :isLast="index === comment.replies.length - 1"
            :isFirst="index === 0"
            @writeComment="writeComment"
            />
        </div>
    </div>
</template>

<style scoped>

.comment-itself-header {
    display: flex;
    justify-content: space-between;
}

.comment-container {
    position: relative;
}

.comment-vertical-branch {
    position: absolute;
    --line-width: 2px;
    --border-radius: 15px;
    width: calc(var(--line-width));
    left: calc(25px - var(--line-width) / 2);
    top: calc(50px);
    background: #ddd;
}

.comment-horizontal-branch {
    position: absolute;
    --line-width: 2px;
    top: -12px;
    height: 40px;
    border-left: var(--line-width) solid #ddd;
    border-bottom: var(--line-width) solid #ddd;
    width: 56px;
    border-bottom-left-radius: 15px;
}

.comment-itself-content {
    background: #fff;
    padding: 10px 15px 5px 15px;
    border-radius: 10px;
    width: 100%;
    position: relative;
    border: 1px solid #ddd;
}

.comment-itself-content::after {
    content: '';
    width: 15px;
    height: 15px;
    background: #fff;
    position: absolute;
    transform: rotate(45deg);
    top: 10px;
    left: -8px;
    border-left: 1px solid #ddd;
    border-bottom: 1px solid #ddd;
}

/* @keyframes active-comment {
    0% {
        background: red;
    }
    100% {
        background: #fff;
    }
} */

.comment-itself {
    display: grid;
    grid-template-columns: 50px auto;
    gap: 20px;
}

.comment-itself-wrapper {
    margin-bottom: 10px;
}

.comment-profile-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.comment-profile-image {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    overflow: hidden;
}

.comment-timestamp {
    color: #999;
}
</style>

<style>

.comment-answer-input {
    margin-left: 60px;
}

.comment-buttons {
    margin-left: 60px;
}

.comment-buttons button {
    color: #FF6100;
    background: none;
    font-size: 14px;
    background: none;
    border-radius: 10px;
    padding: 5px 10px;
}

.comment-buttons button:hover {
    text-decoration: underline;
}

.comment-buttons button:active {
    transform: scale(0.95);
    transition: transform 0.05s;
}

.comment-toggle-answer-button {
    width: 80px;
    text-align: left;
}

.highlight {
    animation: active-comment 2s ease-in-out;
}

.highlight::after {
    animation: active-comment 2s ease-in-out;
}

@keyframes active-comment {
    0% {
        border-color: #FF6100;
    }

    100% {
        border-color: #ddd;
    }
}
</style>