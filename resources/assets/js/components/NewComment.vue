<template>
    <div class="new-comment">
        <textarea class="form-control" rows="2" v-model="message" placeholder="Type your message"></textarea>
        <button @click="sendComment()" class="btn btn-primary btn-sm" :disabled="!message.length">Send</button>
    </div>
</template>

<script>
    export default {
        props: ['parent'],
        data: function () {
            return {
                message: ''
            }
        },
        methods: {
            sendComment: function () {
                const self = this;
                axios.post('comment/send', {
                    message: this.message,
                    parentId: this.parent
                }).then( function (res) {
                    res.data[0].childs = [];
                    self.$emit('create', res.data[0])
                }).catch(function (err) {
                    console.log(err);
                });
            }
        }
    }
</script>