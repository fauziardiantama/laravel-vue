<template>
    <ul class="pagination">
        <li v-if="pagination.current_page > 1" :class="{ 'disabled': isLoading }">
            <a href="javascript:void(0)" aria-label="Previous" v-on:click.prevent="changePage(pagination.current_page - 1)">
                <span aria-hidden="true">«</span>
            </a>
        </li>
        <li v-for="page in pagesNumber" :class="{ 'active': page == pagination.current_page, 'disabled': isLoading }">
        <a href="javascript:void(0)" v-on:click.prevent="changePage(page)">{{ page }}</a>
    </li>
        <li v-if="pagination.current_page < pagination.last_page" :class="{ 'disabled': isLoading }">
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
        width: 40px; /* Set a fixed width */
        display: inline-block; /* Required for the width to take effect */
        text-align: center; /* Center the text */
    }
    .pagination li a:hover {
        background: #f2f2f2;
    }
    .pagination li.active a {
        background: #3490dc;
        color: #fff;
    }

    .pagination .disabled a {
        color: #ccc;
        cursor: not-allowed;
    }
</style>
<script>
    export default{
        name: 'pagination',
        data() {
        return {
                isLoading: false, // New data property
            };
        },
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
                let from = this.pagination.current_page;
                if (from < 1) {
                    from = 1;
                }
                let to = from + 3; // 4 pages at a time
                if (to >= this.pagination.last_page) {
                    to = this.pagination.last_page;
                    from = to - 3; // Adjust 'from' if 'to' exceeds the last page
                    if (from < 1) {
                        from = 1;
                    }
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
                if (this.isLoading) {
                    return; // Return early if isLoading is true
                }
                this.isLoading = true; // Set isLoading to true when a button is clicked
                this.pagination.current_page = page;
                this.$emit('paginate');
                setTimeout(() => {
                    this.isLoading = false; // Set isLoading back to false when the update is complete
                }, 800);
            }
        }
    }
</script>