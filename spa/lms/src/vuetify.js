window.Vue = require("vue");
import Vuetify from "vuetify";
import {
    TiptapVuetifyPlugin
} from 'tiptap-vuetify'

import 'tiptap-vuetify/dist/main.css'
import 'vuetify/dist/vuetify.css'

const vuetify = new Vuetify({
    theme: {
        options: {
            customProperties: true,
        },
        themes: {
            light: {
                primary: "#5867dd",
                secondary: "#e8ecfa",
                accent: "#5d78ff",
                error: "#fd397a",
                info: "#5578eb",
                success: "#0abb87",
                warning: "#ffb822"
            }
        }
    }
});


Vue.use(Vuetify);
// use this package's plugin
Vue.use(TiptapVuetifyPlugin, {
    // the next line is important! You need to provide the Vuetify Object to this place.
    vuetify, // same as "vuetify: vuetify"
    // optional, default to 'md' (default vuetify icons before v2.0.0)
    iconsGroup: 'md'
});

export default vuetify
