<template>
    <div class="edit-comment">
                <span class="pull-right">
                    <a @click="confirm()" title="Confirm"><span class="glyphicon glyphicon-ok"></span></a>
                    <a @click="cancel()" title="Cancel"><span class="glyphicon glyphicon-remove"></span></a>
                </span>
        <textarea class="form-control" rows="3" v-model="editedComment.text"></textarea>
    </div>
</template>

<script>
    export default {
        props: ['comment'],
        data: function () {
            return {
                commentTemp: ''
            }
        },
        computed: {
            editedComment: function () {
                return this.comment;
            }
        },
        created: function () {
            this.commentTemp = this.comment.text;
        },
        methods: {
            confirm: function () {
                const self = this;
                axios.post('comment/accept', {
                    message: this.editedComment.text,
                    id: this.editedComment.id
                }).then( function (res) {
                    self.$emit('edit', {
                        text: res.data[0].text,
                        updated_at: res.data[0].updated_at
                    })
                }).catch(function (err) {
                    console.log(err);
                });
            },
            cancel: function () {
                this.$emit('edit', { text: this.commentTemp });
            }
        }
    }
</script>