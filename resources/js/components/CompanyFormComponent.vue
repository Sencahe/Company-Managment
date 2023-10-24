<template>

    <h3 class="my-5">{{ labelAction }} Company: {{company.name}}</h3>

    <form v-if="isNewCompany || company.id != undefined" class="text-start border p-4">
        <p v-if="unexpectedError" class="text-danger">There has been an unexpected error. Please, try again later</p>

        <!-- Company Name -->
        <div class="mb-3">
            <div class="d-flex">
                <label for="companyName" class="form-label" :class="{ 'text-danger': errorData.name }" >Company Name</label>
                <label v-if="errorData.name" class="text-danger ms-1"> - {{ errorData.name[0] }}</label>
            </div>
            <input v-model="company.name" type="text" class="form-control" :class="{ 'border-danger': errorData.name }" id="companyName" placeholder="Enter company name" required>
        </div>
        <!-- Website -->
        <div class="mb-3">
            <div class="d-flex">
                <label for="websiteUrl" class="form-label" :class="{ 'text-danger': errorData.website }">Website URL</label>
                <label v-if="errorData.website" class="text-danger ms-1"> - {{ errorData.website[0] }}</label>
            </div>
            <input v-model="company.website" type="url" class="form-control" :class="{ 'border-danger': errorData.website }"  id="websiteUrl" placeholder="Enter company website URL" required>
        </div>
        <!-- Email -->
        <div class="mb-3">
            <div class="d-flex">
                <label for="email" class="form-label" :class="{ 'text-danger': errorData.email }">Email</label>
                <label v-if="errorData.email" class="text-danger ms-1"> - {{ errorData.email[0] }}</label>
            </div>
            <input v-model="company.email" type="email" class="form-control" :class="{ 'border-danger': errorData.email }" id="email" placeholder="Enter company email" required>
        </div>
        <!-- Logo -->
        <div class="mb-3">
            <div class="d-flex">
                <label for="logoFile" class="form-label" :class="{ 'text-danger': errorData.logoFile }">Comapany Logo (min 100x100)</label>
                <label v-if="errorData.logoFile" class="text-danger ms-1"> - {{ errorData.logoFile[0] }}</label>
            </div>

            <div class="d-flex justify-content-between">
                <input @change="uploadImage" type="file" class="form-control me-3" :class="{ 'border-danger': errorData.logoFile }" id="logoFile" :required="isNewCompany">

                <div v-if="company.logo != ''" class="mb-3">
                    <img :src=company.logo alt="">
                </div>
            </div>
        </div>

        <button @click.prevent="submit" class="btn btn-primary">Submit</button>
    </form>
    <div v-else>
        <p>404 Company not found :(</p>
    </div>
</template>

<script>
import axios from 'axios';
import Company from '../classes/company';

export default {
    name: "CompanyFormComponent",
    data() {
        return {
            id: null,
            formImgFile: null,
            company: {},
            errorData: {},
            unexpectedError: false
        }
    },
    mounted() {
        if (this.isNewCompany) {
            this.company = new Company();
        } else {
            axios.get('/request/company/' + this.id
            ).then(response => {
                this.company = response.data;
            }).catch(error => {

            });
        }
    },
    computed: {
        labelAction() {
            return this.isNewCompany ? "Add New" : "Edit";
        },
        isNewCompany() {
            this.id = this.$route.params.id;
            return this.id == null;
        },
        axiosConfig() {

            var config = {
                method: "post",
                url: "/request/company" + (this.isNewCompany ? "" : "/" + this.id),
                data: this.company,
                headers: { 'Content-Type': 'multipart/form-data' }
            };
            return config;
        }
    },
    methods: {
        submit() {
            // Create or Update a Company
            this.errorData = {};
            this.unexpectedError = false;

            axios(this.axiosConfig)
                .then(response => {

                    this.$swal(
                        `${this.isNewCompany ? 'Created' : 'Updated'}!`,
                        `${this.company.name} was successfully ${this.isNewCompany ? 'created' : 'updated'}!`,
                        'success'
                    );

                    this.$router.push("/dashboard/company/" + response.data.id);

                }).catch(error => {

                    if (error.response.status == 422) {
                        this.errorData = error.response.data.errors;
                    } else {
                        this.unexpectedError = true;
                    }

                });

        },
        uploadImage(event) {
            // Upload image in formulary and handle url vs file
            this.company.logoFile = event.target.files[0];
            this.company.logo = URL.createObjectURL(this.company.logoFile);
        }
    }
}
</script>


<style>
#logoFile {
    max-width: 75%;
    max-height: 2.4rem;
}
</style>
