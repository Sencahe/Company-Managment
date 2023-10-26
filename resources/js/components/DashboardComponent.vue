
<template>
        <div class="d-flex flex-column flex-md-row flex-grow-1">
            <!-- Dashboard Nav -->
            <DashboardNavComponent></DashboardNavComponent>
            <!-- Dashboard Content -->
            <div class="d-flex mx-3 flex-column align-items-center text-center flex-grow-1">
                <h1 class="mt-5">Enterprise Managment System</h1>
                <section class="container mx-2 px-2 w-100 flex-grow-1 d-flex flex-column justify-content-center align-items-center">
                    <RouterView></RouterView>
                </section>
            </div>
        </div>

</template>

<script>
import DashboardNavComponent from "./DashboardNavComponent.vue";

export default {
    name: "DashboardComponenet",
    components: {
        DashboardNavComponent
    },
    computed: {

    },
    mounted() {
    }
};
</script>
