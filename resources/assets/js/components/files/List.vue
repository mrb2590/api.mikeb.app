<template>
    <div class="list-files">
        <div class="md-toolbar-row">
            <div class="md-toolbar-section-start">
                <div>Page {{ files.current_page }} of {{ files.last_page }} - {{ files.total }} Files</div>
            </div>

            <div class="md-toolbar-section-end">
                <md-button class="md-icon-button" @click="backPage">
                    <md-icon>navigate_before</md-icon>
                </md-button>

                <md-button class="md-icon-button" @click="nextPage">
                    <md-icon>navigate_next</md-icon>
                </md-button>
            </div>
        </div>

        <div class="explorer">
            <div class="single-file" v-for="file in files.data">
                <md-card>
                    <md-card-header>
                        <md-card-header-text>
                            <div class="md-title">{{ file.original_filename }}</div>
                            <div class="md-subhead">file</div>
                        </md-card-header-text>
                    </md-card-header>

                    <md-card-actions>
                        <md-button>Action</md-button>
                        <md-button>Action</md-button>
                    </md-card-actions>
                </md-card>
            </div>
        </div>
    </div>
</template>

<style lang="scss" scoped>
    .explorer {
        padding: 10px;
    }
    .md-card {
        width: 320px;
        max-width: calc(100% - 10px);
        margin: 4px;
        display: block;
        vertical-align: top;
        float: left;
    }
    @media (max-width: 720px) {
        .md-card {
            width: calc(100% - 4px);
        }
    }
</style>

<script>
    import { mapState } from 'vuex';

    export default {
        /*
         * The component's data.
         */
        data() {
            return {};
        },

        /**
         * Prepare the component.
         */
        mounted() {
            this.prepareComponent();
        },

        methods: {
            /**
             * Prepare the component.
             */
            prepareComponent() {
                let page = 1, limit = 10;
                this.$store.dispatch('fetch', {
                    page: page,
                    limit: limit
                });
            },

            nextPage() {
                let page = this.files.current_page;

                if (page >= this.files.last_page) {
                    page = 0;
                } else {
                    page++;
                }

                this.$store.dispatch('fetch', {
                    page: page,
                    limit: 10
                });
            },

            backPage() {
                let page = this.files.current_page;

                if (page <= 1) {
                    page = this.files.last_page;
                } else {
                    page--;
                }

                this.$store.dispatch('fetch', {
                    page: page,
                    limit: 10
                });
            }
        },

        computed: {
            ...mapState(['files'])
        }
    }
</script>
