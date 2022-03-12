<template>

<!--    Sidebar-->
    <side-bar></side-bar>
<!--    End Sidebar-->

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <!-- Navbar -->
        <nav-bar></nav-bar>
        <!-- End Navbar -->
        <div class="container-fluid py-4">
            <slot></slot>
        </div>
    </main>

</template>

<script>

import navBar from '../components/navBar.vue';
import sideBar from '../components/sideBar.vue';

export default {
    components: {navBar, sideBar}
}
</script>
