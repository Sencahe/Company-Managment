
<template>
        <div class="d-flex flex-column flex-md-row flex-grow-1">
            <!--  Navigation -->
            <NavComponent></NavComponent>
            <!-- Main Content -->
            <div class="d-flex mx-3 flex-column align-items-center text-center flex-grow-1">
                <h1 class="mt-5">Company Managment System</h1>
                <section class="container mx-2 px-2 w-100 flex-grow-1 d-flex flex-column justify-content-center align-items-center">
                    <RouterView></RouterView>
                </section>
            </div>
        </div>

</template>

<script>

import NavComponent from "./NavComponent.vue";

export default {
    name: "MainComponent",
    components: {
    NavComponent
},
    computed: {

    },
    mounted() {
    }
};
</script>
