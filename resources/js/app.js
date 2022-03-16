// require('./bootstrap');

import {createApp, h} from 'vue'
import {createInertiaApp} from '@inertiajs/inertia-vue3'
import {InertiaProgress} from '@inertiajs/progress'
import {Head, Link} from '@inertiajs/inertia-vue3'
import Layout from "./layouts/layout";

InertiaProgress.init()

createInertiaApp({
    title: title => ` My Patients - ${title}`,
    resolve: name => {
        const page = require(`./Pages/${name}`).default
        if (page.layout === undefined) {
            page.layout = Layout
        }
        return page
    },
    setup({el, App, props, plugin}) {
        createApp({render: () => h(App, props)})
            .use(plugin)
            .component('Head', Head)
            .component('Link', Link)
            .mixin({methods: {route}})
            .mount(el)
    },
})
