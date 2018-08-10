<template>
    <div class="login">
        <md-card class="md-xsmall-size-200">
            <md-card-header>
                <md-card-header-text>
                    <div class="md-title">Sign In</div>
                </md-card-header-text>
            </md-card-header>

            <md-card-content>
                <md-field>
                    <md-icon>mail_outline</md-icon>
                    <label>Email</label>
                    <md-input v-model="email" type="email"></md-input>
                    <!-- <span class="md-helper-text">Error text</span> -->
                </md-field>
                <md-field>
                    <md-icon>vpn_key</md-icon>
                    <label>Password</label>
                    <md-input v-model="password" type="password"></md-input>
                    <!-- <span class="md-helper-text">Error text</span> -->
                </md-field>
            </md-card-content>

            <md-card-actions>
                <md-button class="md-primary" @click="postLogin">Go</md-button>
            </md-card-actions>
        </md-card>
    </div>
</template>

<style lang="scss" scoped>
    .md-card {
        width: 320px;
        max-width: calc(100% - 10px);
        margin: 15px;
        display: block;
        vertical-align: top;
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
                email: null,
                password: null,
                oauth: {
                    token_type: null,
                    expires: null,
                    access_token: null,
                    refresh_token: null
                }
            };
        },

        methods: {
            /**
             * Get an acess token.
             */
            postLogin() {
                axios.post('/api/v1/user/login', {
                    email: this.email,
                    password: this.password
                }).then(response => {
                        this.oauth.token_type = response.data.token_type;
                        this.oauth.expires_in = response.data.expires_in;
                        this.oauth.access_token = response.data.access_token;
                        this.oauth.refresh_token = response.data.refresh_token;
                        this.password = null;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            }
        }
    }
</script>
