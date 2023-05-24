import Vue from "vue";
class Session {
    constructor() {
        this.VM = new Vue({
            data: () => ({
                config: null
            })
        });
    }
    set user(user) {
        this.VM.$data.config.user = user;
    }
    set config(config) {
        this.VM.$data.config = config;
    }
    get user() {
        return this.VM.$data.config.user;
    }
    set school(school) {
        this.VM.$data.config.school = school;
    }
    get school() {
        return this.VM.$data.config.school;
    }
}

Vue.prototype.$session = new Session();