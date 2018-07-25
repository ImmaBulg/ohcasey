<template>
    <layout>
        <h1 slot="title">Редкатровать тег</h1>
        <template slot="panel-title">Редактирование тега</template>
        <template slot="body">
            <tag-form></tag-form>
        </template>
    </layout>
</template>

<script>
    import Layout from './../Layout.vue'
    import TagForm from './Form.vue'

    export default {
        components:{
            Layout,
            TagForm
        }
    }
</script>