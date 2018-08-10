<template>
    <div class="files">
        <div class="md-toolbar-row">
            <div class="md-toolbar-section-start">
                <div>Page {{ this.paging.page }} of {{ this.paging.last_page }} - {{ this.total_files }} Files</div>
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
            <div class="single-file" v-for="file in files">
                <md-card class="md-xsmall-size-100">
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
    @media (max-width: 600px) {
        .md-card {
            width: calc(100% - 4px);
        }
    }
</style>

<script>
    export default {
        /*
         * The component's data.
         */
        data() {
            return {
                files: [],
                total_files: 0,
                paging: {
                    page: 0,
                    limit: 10,
                    last_page: 1
                }
            };
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
                this.getFiles();
            },

            /**
             * Get all of the authorized tokens for the user.
             */
            getFiles() {
                axios.get('/api/v1/files', {
                    params: {
                        page: this.page,
                        limit: this.limit
                    }
                }).then(response => {
                        this.files = response.data.data;
                        this.total_files = response.data.total;
                        this.paging.last_page = response.data.last_page;
                        this.paging.page = response.data.current_page;
                    });
            },

            nextPage() {
                if (this.paging.page >= this.paging.last_page) {
                    this.paging.page = 0;
                } else {
                    this.paging.page++;
                }

                this.getFiles();
            },

            backPage() {
                if (this.paging.page <= 1) {
                    this.paging.page = this.paging.last_page;
                } else {
                    this.paging.page--;
                }

                this.getFiles();
            }
        }
    }
</script>
