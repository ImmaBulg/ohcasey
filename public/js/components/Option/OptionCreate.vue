<template>
    <layout>
        <h1 slot="title">Новая опция</h1>
        <template slot="panel-title">Добавить опицю</template>
        <template slot="body">
            <option-form></option-form>
        </template>
    </layout>
</template>

<script>
    import Layout from './../Layout.vue'
    import OptionForm from './Form.vue'

    export default {
        components:{
            Layout,
            OptionForm
        }
    }
</script>