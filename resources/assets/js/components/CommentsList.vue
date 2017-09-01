<template>
    <ul class="comments-list">
        <li v-for="comment in comments">
            <div class="comment">
                <div class="comment-head">
                    <span>{{ comment.created_at }}</span>
                    <span v-if="!comment.is_deleted">
                        <a @click="edit(comment.id)"
                           title="Edit"
                           :class="{edited: comment.updated_at}">
                           <span class="glyphicon glyphicon-pencil"></span>
                        </a>
                        <a @click="remove(comment)" title="Remove">
                            <span class="glyphicon glyphicon-remove"></span>
                        </a>
                    </span>
                </div>
                <span v-if="comment.is_deleted" class="deleted-comment">This comment has been removed</span>
                <edit-comment
                        v-else-if="editMode && editCommentId === comment.id"
                        :comment="comment"
                        @edit="edited => { comment.text = edited.text;
                                           comment.updated_at = edited.updated_at || comment.updated_at;
                                           editMode = false }">
                </edit-comment>
                <div class="comment-text" v-else> {{ comment.text }} </div>
                <a @click="reply(comment.id)" v-if="!comment.is_deleted && !editMode">Reply</a>
                <div v-if="showNewCommentField && replyForCommentId === comment.id">
                    <a @click="close()" title="Close" class="pull-right">
                        <span class="glyphicon glyphicon-remove"></span>
                    </a>
                    <new-comment
                            :parent="comment.id"
                            @create="value => { comment.childs.push(value); showNewCommentField = false }">
                    </new-comment>
                </div>
            </div>
            <comments-list v-if="comment.childs.length" :comments="comment.childs"></comments-list>
        </li>
    </ul>
</template>

<script>
    import EditComment from './EditComment.vue';
    export default {
        name: 'comments-list',
        components: {
            editComment: EditComment
        },
        props: ['comments'],
        data: function () {
            return {
                showNewCommentField: false,
                replyForCommentId: null,
                editMode: false,
                editCommentId: null
            }
        },
        methods: {
            reply: function (commentId) {
                this.showNewCommentField = true;
                this.replyForCommentId = commentId;
            },
            edit: function (commentId) {
                this.editMode = true;
                this.editCommentId = commentId;
            },
            remove: function (comment) {
                axios.post('comment/remove', {
                    id: comment.id
                }).then(function () {
                    comment.is_deleted = true;
                    return comment;
                }).catch(function (err) {
                    console.log(err);
                });
            },
            close: function () {
                this.showNewCommentField = false;
            }
        }
    }
</script>