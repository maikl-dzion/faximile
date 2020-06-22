// import Vue from 'vue'
import axios from 'axios';

const HEADER_JWT_TOKEN_NAME = 'X-User-Jwt-Token';

const axiosHeaders = {
    "jwt_token": "",
    "Accept": "application/json",
    "Access-Control-Allow-Origin" : "*",
    "X-Requested-With": "XMLHttpRequest",
    "Access-Control-Allow-Methods" : "GET,POST,PUT,DELETE,OPTIONS",
    "Access-Control-Allow-Headers" : "*",
    "X-User-Jwt-Token": "",
}

axios.defaults.headers = axiosHeaders;

// import TopHeader from '../components/TopHeader';

const HttpService = {
    install (Vue, options) {
        Vue.mixin({

            data: () => ({
                apiUrl,
                restApiUrl,
            }),

            methods : {

                cutFirstChar(str) {
                    let firstChar = str.substring(0, 1);
                    if(firstChar == '/') {
                        str = str.slice(1, str.length);
                    }
                    return str;
                },

                http(url, method = 'get', data = null) {
                    url = this.cutFirstChar(url);
                    const apiUri = this.restApiUrl + url;
                    const token  = this.getToken();
                    // axios.defaults.headers = axiosHeaders;
                    axios.defaults.headers[HEADER_JWT_TOKEN_NAME] = token;
                    return axios[method](apiUri, data);
                },

                get(url) {
                    return new Promise((resolve, reject) => {
                        this.http(url, 'get')
                            .then(response => {

                                let data = response.data;
                                if(this.validateResponse(data))
                                    resolve(data)

                            }).catch(error => {

                                 let err = this.errorHttpRender(error);
                                 lg(err);
                                 reject(error);

                            });
                    });
                },

                post(url, data = null) {
                    return new Promise((resolve, reject) => {
                        this.http(url, 'post', data)
                            .then(response => {

                                let data = response.data;
                                if(this.validateResponse(data))
                                    resolve(data)

                            }).catch(error => {

                                let err = this.errorHttpRender(error);
                                lg(error);
                                reject(error);

                            });
                    });
                },

                postSend(url, data = null) {

                    const apiUri = this.apiUrl + url;
                    const token  = this.getToken();
                    axios.defaults.headers[HEADER_JWT_TOKEN_NAME] = token;

                    return new Promise((resolve, reject) => {
                        axios['post'](apiUri, data)
                            .then(response => {
                                let data = response.data;
                                if(this.validateResponse(data))
                                    resolve(data)
                            }).catch(error => {
                                let err = this.errorHttpRender(error);
                                lg(error);
                                reject(error);
                            });
                    });
                },

                validateResponse(data){
                    // debugger;
                    if(typeof data !== 'object') {
                        lg(data);
                        return false;
                    }

                    if('error' in data) {
                        lg(data);
                        return false;
                    }

                    return true;
                },

                errorHttpRender(error) {

                    let message = '';
                    let file = '';
                    let line = '';
                    if(error['response']  && error['response']['data']) {
                        let resp = error.response['data'];
                        if('message' in resp)
                            message = resp.message;
                        if('file' in resp)
                            file = resp.file;
                        if('line' in resp)
                            line = resp.line;
                    }

                    let err = {
                        url     : error.config.url,
                        method  : error.config.method,
                        message,
                        file,
                        line,
                        // resp : error.response.data,
                    };

                    return err;
                },

                /////////////////////
                // OLD HTTP SERVICE
                makeRequest(url, method = 'get', data=null) {
                    url = this.cutFirstChar(url);
                    const apiUri = this.apiUrl + url;
                    const token  = this.getToken();
                    axios.defaults.headers[HEADER_JWT_TOKEN_NAME] = token;
                    return axios[method](apiUri, data);
                },

                send(url, method = 'get', data = null) {
                    return new Promise((resolve, reject) => {
                        this.makeRequest(url, method, data)
                            .then(response => {

                                let data = response.data;
                                if(this.validateResponse(data))
                                    resolve(data)

                            }).catch(error => {
                                let err = this.errorHttpRender(error);
                                lg(err);
                                // reject(error);
                            });
                    });
                },

            }, //// --- Methods --
            //////////////////////

            created() {},
            computed: {},

        }) //////// VueMixin
    }
}

export default HttpService
