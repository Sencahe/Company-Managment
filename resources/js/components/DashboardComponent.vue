
<template>
    <div class="container-fluid p-0">
        <div class="d-flex" >

            <DashboardNavComponent></DashboardNavComponent>
            <!-- Dashboard Content -->
            <div class="d-flex mx-3 flex-column align-items-center text-center w-100 min-vh-100">
                <h1 class="mt-3">Enterprise Managment System</h1>
                <section class="container mx-2 px-2 w-100 h-100 d-flex flex-column justify-content-center align-items-center">
                    <RouterView></RouterView>
                </section>
            </div>

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



