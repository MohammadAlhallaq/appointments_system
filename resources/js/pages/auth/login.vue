<template>
    <div class="max-w-screen-xl px-4 py-16 mx-auto sm:px-6 lg:px-8">
        <div class="max-w-lg mx-auto">
            <form
                action=""
                class="p-8 mt-6 mb-0 space-y-4 rounded-lg shadow-2xl"
                @submit.prevent="submit"
            >
                <!-- Success -->

                <div
                    class="p-4 text-red-700 border rounded border-red-900/10 bg-red-50"
                    role="alert"
                    v-if="showErrors"
                >
                    <ul
                        class="mt-1 ml-2 text-xs list-disc list-inside"
                        v-for="error in errors"
                    >
                        <li>{{ error }}</li>
                    </ul>
                </div>

                <div>
                    <label for="username" class="text-sm font-medium"
                    >Username</label
                    >
                    <div class="relative mt-1">
                        <input
                            type="text"
                            id="username"
                            class="w-full p-4 pr-12 text-sm border-gray-200 rounded-lg shadow-sm"
                            placeholder="Enter email"
                            v-model="form.username"
                        />
                    </div>
                </div>

                <div>
                    <label for="password" class="text-sm font-medium"
                    >Password</label
                    >
                    <div class="relative mt-1">
                        <input
                            type="text"
                            id="password"
                            class="w-full p-4 pr-12 text-sm border-gray-200 rounded-lg shadow-sm fa-eye"
                            placeholder="Enter password"
                            v-model="form.password"
                        />

                        <span class="absolute inset-y-0 inline-flex items-center right-4">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="w-5 h-5 text-gray-400"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                />
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                />
                            </svg>
                        </span>
                    </div>
                </div>

                <button
                    type="submit"
                    class="block w-full px-5 py-3 text-sm font-medium text-white bg-indigo-600 rounded-lg"
                >
                    Sign in
                </button>
            </form>
        </div>
    </div>
</template>

<script>
import {reactive} from "vue";
import {Inertia} from "@inertiajs/inertia";

export default {
    layout: null,

    props: {
        errors: {
            type: Object,
            default: null,
        },
    },

    setup(props) {
        const showErrors = false;

        const form = reactive({
            username: null,
            password: null,
            rememberMe: false,
        });

        function submit() {
            Inertia.post("login", form);

            if (props.errors != null) {
                this.showErrors = true
            } else {
                this.showErrors = false
            }
        }

        return {form, submit, showErrors};
    },
};
</script>
