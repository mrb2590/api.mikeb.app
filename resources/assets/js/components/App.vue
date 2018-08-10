<template>
    <div class="page-container">
        <md-app md-waterfall md-mode="fixed">
            <md-app-toolbar class="md-primary">
                <div class="md-toolbar-row">
                    <div class="md-toolbar-section-start">
                        <md-button class="md-icon-button" @click="menuVisible = !menuVisible">
                            <md-icon>menu</md-icon>
                        </md-button>

                        <span class="md-title">Files</span>
                    </div>

                    <div class="md-toolbar-section-end">
                        <md-button class="md-icon-button">
                            <md-icon>more_vert</md-icon>
                        </md-button>
                    </div>
                </div>

                <div class="md-toolbar-row">
                    <md-tabs class="md-primary">
                        <md-tab id="tab-home" md-label="Home"></md-tab>
                        <md-tab id="tab-pages" md-label="Pages"></md-tab>
                        <md-tab id="tab-posts" md-label="Posts"></md-tab>
                        <md-tab id="tab-favorites" md-label="Favorites"></md-tab>
                    </md-tabs>
                </div>
            </md-app-toolbar>

            <md-app-drawer :md-active.sync="menuVisible">
                <md-toolbar class="md-transparent" md-elevation="0">Navigation</md-toolbar>

                <md-list>
                    <md-list-item @click="menuVisible = !menuVisible" to="login">
                        <md-icon>perm_identity</md-icon>
                        <span class="md-list-item-text">Sign In</span>
                    </md-list-item>

                    <md-list-item @click="menuVisible = !menuVisible" to="files">
                        <md-icon>insert_drive_file</md-icon>
                        <span class="md-list-item-text">Files</span>
                    </md-list-item>

                    <md-list-item @click="menuVisible = !menuVisible">
                        <md-icon>invert_colors_off</md-icon>
                        <span class="md-list-item-text">Theme</span>
                    </md-list-item>
                </md-list>
            </md-app-drawer>

            <md-app-content>
                <router-view></router-view>
            </md-app-content>
        </md-app>
    </div>
</template>

<style lang="scss" scoped>
  .md-app {
    height: 100vh;
  }
</style>

<script>
    import axios from 'axios';
    import { mapState } from 'vuex';

    export default {
        name: 'App',

        /*
         * The component's data.
         */
        data() {
            return {
                menuVisible: false
            }
        },
    }
</script>
