<template>
    <div>
        <h1 class="px-3 text-center mb-5">Enterprise Managment System</h1>
        <LoginComponent></LoginComponent>
    </div>
</template>

<script>
import LoginComponent from './LoginComponent.vue';

    export default {
        name: "IndexComponent",
        components:{
            LoginComponent
        },
        mounted() {

        }
    }
</script>
