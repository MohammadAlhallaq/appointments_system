<template>

    <div class="h-screen flex flex-col justify-center items-center">
        <div class="w-full max-w-xs">
            <form class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4" @submit.prevent="submit">
                <div v-if="errors.credentials"
                     class="bg-orange-100 border-l-4 border-orange-500 text-orange-700 p-4 mb-4" role="alert">
                    <p class="font-bold">Heads up</p>
                    <p>{{ errors.credentials }}</p>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="username">
                        Username
                    </label>
                    <input v-model="form.username" :class="{ 'border-red-500': errors.username }"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight mb-3 focus:outline-none focus:shadow-outline"
                           id="username" type="text">
                    <p v-if="errors.username" class="text-red-500 text-xs italic">Please choose a password.</p>

                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                        Password
                    </label>
                    <input v-model="form.password" :class="{ 'border-red-500': errors.password }"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline"
                           id="password" type="password">
                    <p v-if="errors.password" class="text-red-500 text-xs italic">Please choose a password.</p>
                </div>
                <div class="flex items-center justify-between">
                    <button
                        class="w-full bg-blue-500 hover:bg-blue-400 text-white font-bold py-2 px-4 border-b-4 border-blue-700 hover:border-blue-500 rounded"
                        type="submit">
                        Sign In
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>

import {reactive} from 'vue'
import {Inertia} from '@inertiajs/inertia'

export default {

    layout: null,
    props: {
        errors: Object,
    },

    setup() {
        const form = reactive({
            username: null,
            password: null,
            rememberMe: false,
        })

        function submit() {
            Inertia.post('login', form)
        }

        return {form, submit}
    },
}
</script>
