<template>
    <ul class="pagination" style="margin: 2px 0; float: right;" v-if="rows.last_page > 1">
        <li v-if="rows.prev_page_url"><a @click="changePage(1)">&#171;</a></li>
        <li v-if="rows.prev_page_url"><a @click="changePage(rows.current_page - 1)">&#8249;</a></li>
        <li class="paginate_button" v-for="page in pagesNumbers" :class="[(page == rows.current_page) ? 'active' : '']">
            <a @click="changePage(page)">{{page}}</a>
        </li>
        <li v-if="rows.next_page_url"><a @click="changePage(rows.current_page + 1)">&#8250;</a></li>
        <li v-if="rows.next_page_url"><a @click="changePage(rows.last_page)">&#187;</a></li>
    </ul>
</template>


<script>
    export default {
        props: ['rows'],
        data(){
            return {
                offset: 4,
            }
        },
        methods:{
            changePage(page){
                this.rows.current_page = page
                this.$emit('update_table')
            }
        },
        computed: {
            pagesNumbers() {
                if (!this.rows.to) {
                    return [];
                }
                var from = this.rows.current_page - this.offset;
                if (from < 1) {
                    from = 1;
                }
                var to = from + (this.offset * 2);
                if (to >= this.rows.last_page) {
                    to = this.rows.last_page;
                }
                var pagesArray = [];
                while (from <= to) {
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;
            }
        }
    }
</script>