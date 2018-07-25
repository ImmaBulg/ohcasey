<template>
    <div>
        <loader></loader>
        <div class="row">
            <div class="col-lg-12">
                <slot name="title"></slot>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <slot name="panel-title"></slot>
                    </div>
                    <div class="panel-body">
                        <slot name="body"></slot>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import loader from './loader.vue'

    export default {
        components: {
            loader
        }
    }
</script>