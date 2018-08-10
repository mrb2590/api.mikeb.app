import Vue from 'vue';
import Vuex from 'vuex';
import axios from 'axios';
import api from '../api';

Vue.use(Vuex);

const filesStore = new Vuex.Store({
    state: {
        files: [],
        // favouriteNotes: []
    },
    mutations: {
        FETCH(state, files) {
            state.files = files
        },
        // FETCH_FAVOURITE(state, favouriteNotes) {
        //     state.favouriteNotes = favouriteNotes;
        // }
    },
    actions: {
        fetch({ commit }, query) {
            return axios.get(api.files, {
                params: {
                    page: query.page,
                    limit: query.limit
                }
            })
                .then(response => commit('FETCH', response.data))
                .catch();
        },
        add({}, title) {
            // axios.post(`${notes}`, {
            //     'title': title,
            //     'is_favourite': false,
            // });
        },
        deleteFile({}, id) {
            axios.delete(`${api.files}/${id}`)
                .then(() => this.dispatch('fetch'))
                .catch();
        },
        edit({}, file) {
            axios.patch(`${api.files}/${file.id}`, {
                filename: file.filename
            })
                .then(() => this.dispatch('fetch'));
        },
        // toggleFavourite({}, id) {
        //     axios.put(`${files}/${id}/toggleFavourite`, {
        //         is_favourite: true
        //     })
        //         .then(() => this.dispatch('fetch'))
        // },
        // fetchFavourite({commit}) {
        //     return axios.get(`${notes}?type=favourite`)
        //         .then(response => commit('FETCH_FAVOURITE', response.data))
        //         .catch();
        // },
    }
});

export default filesStore;
