<script setup>
import { defineProps, ref } from 'vue';
import { useAuthStore } from '@/stores/auth';
import { useBarbecueStore } from '@/stores/barbecue';
import Comment from "@/Components/Comments/Comment.vue";

const auth = useAuthStore();
const barbecueStore = useBarbecueStore();

const answerComment = ref(null);
const answerPath = ref([]);
const commentContent = ref('');
const commentTextarea = ref(null);


const props = defineProps({
    root: {
        type: Object,
        required: true,
    },
    barbecueId: {
        type: Number,
        required: true
    }
});

function removeReply() {
    answerComment.value = null;
    answerPath.value = [];

    const commentInput = document.getElementById(`comment-input-${hash}`);
    commentInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
    commentTextarea.value.focus();
}

function adjustTextareaHeight() {
    const textarea = commentTextarea.value;
    textarea.style.height = 'auto';
    textarea.style.height = `${textarea.scrollHeight + 2}px`;
}

function focusComment(commentId) {
    const comment = document.getElementById(`comment-${commentId}`);
    comment.scrollIntoView({ behavior: 'smooth', block: 'center' });
    comment.classList.add('highlight');
    setTimeout(() => {
        comment.classList.remove('highlight');
    }, 2000);
}

function randomCharacterHash(length) {
        let result = 's';
        const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        const charactersLength = characters.length;
        for (let i = 0; i < length; i++) {
            result += characters.charAt(Math.floor(Math.random() * charactersLength));
        }
        return result;
    }

const hash = randomCharacterHash(5);

function writeComment(treePath) {
    answerPath.value = treePath;
    const deepSearch = (backtrackNode, treePath) => {
        for (let i = 0; i < treePath.length - 1; i++) {
            backtrackNode = backtrackNode.find(node => node.id === treePath[i]).replies;
        }
        return backtrackNode.find(node => node.id === treePath[treePath.length - 1]);
    }

    let currentNode = props.root;
    const lastNode = deepSearch(currentNode, treePath);
    answerComment.value = lastNode;

    const commentInput = document.getElementById(`comment-input-${hash}`);
    commentInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
    commentTextarea.value.focus();
}


function sendComment() {
    axios.post(route('comments.store'), {
        comment_id: answerComment.value ? answerComment.value.id : null,
        barbecue_id: answerComment.value ? null : props.barbecueId,
        comment: commentContent.value
    }).then(response => {
        const commentObject = { ...response.data, replies: [] };
        let currentNode = props.root;
        for (let i = 0; i < answerPath.value.length; i++) {
            currentNode = currentNode.find(node => node.id === answerPath.value[i]).replies;
        }
        currentNode.unshift(commentObject);
        commentContent.value = '';
        answerComment.value = null;
        answerPath.value = [];
    });
}

</script>

<template>
    <span>Comentaris ({{ root.length }}) </span>
    <div class="barbecue-comment-input-wrapper">
        
        <div class="barbecue-comment-input" v-if="auth.user">
            <div class="barbecue-comment-profile-image">
                <img :src="auth.user.image" alt="Profile image">
            </div>
            <div class="barbecue-comment-input-itself" :id="'comment-input-' + hash" v-if="auth.user">
                <div class="barbecue-comment-input-itself-answer" v-if="answerComment">
                    <div class="barbecue-comment-input-itself-answer-cross" @click="removeReply">
                        <img class="barbecue-comment-input-itself-answer-cross" src="/assets/svg/cross.svg"
                            alt="Answering" />
                    </div>
                    <div class="barbecue-comment-input-itself-answer-content">
                        <img src="/assets/svg/redo.svg" alt="Answering" />
                        <div class="barbecue-comment-input-itself-answer-content-text"
                            @click="focusComment(answerComment.id)">{{ answerComment.user.name }} {{
                                answerComment.user.surnames }} | {{ answerComment.comment }}</div>
                    </div>
                </div>
                <textarea ref="commentTextarea" v-model="commentContent" @input="adjustTextareaHeight"
                    :placeholder="answerComment ? 'Respon a ' + answerComment.user.name + ' ' + answerComment.user.surnames : 'Escriu un comentari...'">
                </textarea>
            </div>
            <button class="barbecue-comment-send" @click="sendComment" :disabled="!commentContent"
            v-if="auth.user">Enviar</button>
        </div>

    </div>
    <div class="barbecue-footer">
        <div class="barbecue-comments" v-for="(comment, index) in root">
            <Comment @writeComment="writeComment" :comment="comment" :isLast="index === root.length - 1"
                :isFirst="index === 0" />
        </div>
    </div>
</template>

<style scoped>
.barbecue-comment-send {
    background: #FF6100;
    color: #fff;
    padding: 5px 10px;
    border-radius: 10px;
    border: 1px solid transparent;

}

.barbecue-comment-send:disabled {
    border: 1px solid #ddd;
    color: #ddd;
    background: none;
}

.barbecue-comment-input-itself-answer-content-text {
    width: 100%;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.barbecue-comment-input textarea {
    resize: none;
    padding-inline: 10px;
    padding-top: 5px;
    width: 100%;
}

.barbecue-comment-input textarea:focus-visible {
    outline: none;
}

.barbecue-comment-input-itself {
    border: 1px solid #ddd;
    border-radius: 10px;
    overflow: hidden;
    /* width: 100%; */
    
}

.barbecue-comment-input-itself-answer {
    background: #f9f9f9;
    border-bottom: 1px solid #ddd;
    padding: 5px;
    display: flex;
    align-items: center;
    height: 30px;
    gap: 5px;
}

.barbecue-comment-input-itself-answer-cross {
    cursor: pointer;
    height: 100%;
    display: flex;
    align-items: center;
    border-radius: 5px;
}

.barbecue-comment-input-itself-answer-cross:hover {
    background: #dfdfdf;
}

.barbecue-comment-input-itself-answer-cross img {
    height: 60%;
    width: 20px;
}

.barbecue-comment-input-itself-answer-content {
    cursor: pointer;
    height: 100%;
    display: flex;
    align-items: center;
    border-radius: 5px;
    gap: 5px;
    font-size: 14px;
    padding: 5px;
    filter: opacity(0.6);
}

.barbecue-comment-input-itself-answer-content:hover {
    background: #dfdfdf;
    cursor: pointer;
}


.barbecue-comment-input-itself-answer-content img {
    width: 15px;
    height: 15px;
}

.barbecue-comment-input {
    border-radius: 10px;
    display: grid;
    grid-template-columns: 50px auto 80px;
    gap: 10px;
    width: 100%;
}

.barbecue-comment-input-wrapper {
    display: flex;
    gap: 5px;
    width: 100%;
}

.barbecue-comment-profile-image {
    display: flex;
    height: 100%;
}

.barbecue-comment-profile-image img {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
}

</style>