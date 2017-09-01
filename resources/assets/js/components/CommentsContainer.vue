<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <comments-list :comments="comments"></comments-list>
                <div class="panel panel-default">
                    <div class="panel-heading">Write your comment</div>
                    <div class="panel-body">
                        <new-comment @create="value => { comments.push(value) }"></new-comment>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import CommentsList from './CommentsList.vue';
    export default {
        components: {
            commentsList: CommentsList
        },
        data: function () {
            return {
                comments: []
            }
        },
        created: function() {
            $.getJSON('comments', function (data) {
                this.comments = data;
            }.bind(this))
        }
    }
</script>