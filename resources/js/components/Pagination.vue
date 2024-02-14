<template>
    <ul class="pagination">
    <li v-if="pagination.current_page > 1">
        <a href="javascript:void(0)" aria-label="Previous" v-on:click.prevent="changePage(pagination.current_page - 1)">
            <span aria-hidden="true">«</span>
            </a>
        </li>
    <li v-for="page in pagesNumber" :class="{'active': page == pagination.current_page}">
        <a href="javascript:void(0)" v-on:click.prevent="changePage(page)">{{ page }}</a>
        </li>
    <li v-if="pagination.current_page < pagination.last_page">
        <a href="javascript:void(0)" aria-label="Next" v-on:click.prevent="changePage(pagination.current_page + 1)">
            <span aria-hidden="true">»</span>
            </a>
        </li>
    </ul>
</template>
<style scoped>
    .pagination {
        display: flex;
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .pagination li {
        margin-right: 5px;
    }
    .pagination li a {
        color: #000;
        text-decoration: none;
        padding: 5px 10px;
        border: 1px solid #ddd;
        border-radius: 3px;
    }
    .pagination li a:hover {
        background: #f2f2f2;
    }
    .pagination li.active a {
        background: #3490dc;
        color: #fff;
    }
</style>
<script>
    export default{
        name: 'pagination',
        props: {
            pagination: {
                type: Object,
                required: true
            },
            offset: {
                type: Number,
                default: 4
            }
        },
        computed: {
            pagesNumber() {
                if (!this.pagination.to) {
                    return [];
                }
                let from = this.pagination.current_page - this.offset;
                if (from < 1) {
                    from = 1;
                }
                let to = from + (this.offset * 2);
                if (to >= this.pagination.last_page) {
                    to = this.pagination.last_page;
                }
                let pagesArray = [];
                for (let page = from; page <= to; page++) {
                    pagesArray.push(page);
                }
                return pagesArray;
            }
        },
        methods : {
            changePage(page) {
                this.pagination.current_page = page;
                this.$emit('paginate');
            }
        }
    }
</script>