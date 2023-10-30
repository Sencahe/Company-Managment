<template>
    <h3 class="py-4">List of Companies</h3>

    <div class="d-flex justify-content-start w-100 mb-3">
        <RouterLink :to="{name: 'CompanyFormComponentAdd'}">
            <i class="fa-solid text-success add-button fa-square-plus"></i>
        </RouterLink>
    </div>

    <div class="w-100 overflow-auto table-responsive mb-3">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Logo</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Website</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>

            <tbody>
                <tr v-for="(company, index) in companies" :key="index" class="table-row">

                    <td>
                        <img :src="company.logo" :alt="'Logo not found'" style="max-height: 50px;">
                    </td>
                    <td class="table-cell">
                        {{ company.name }}
                    </td>
                    <td>
                        {{ company.email }}
                    </td>
                    <td>
                        {{ company.website }}
                    </td>
                    <td>
                        <div class="d-flex justify-content-center">
                            <RouterLink :to="{ name: 'CompanyFormComponentEdit', params: { id: company.id } }">
                                <i class="fas mx-2 fa-edit text-primary"></i>
                            </RouterLink>
                            <button @click.prevent="deleteCompanyConfirm(company.id, company.name)" class="btn p-0">
                                <i class="fas mx-2 fa-trash text-danger"></i>
                            </button>
                        </div>
                    </td>

                </tr>
            </tbody>

        </table>

        <nav class="mt-5">
            <ul class="pagination mb-4">

                <li class="page-item" v-for="page in pages" :key="page" :class="{ active: page.active, disabled: page.url === null}">
                    <button v-html="page.label" class="page-link" :disabled="page.active" href="#" @click="getCompanies(page.url)"></button>
                </li>

            </ul>
        </nav>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: "CompaniesComponent",
    data() {
        return {
            companies: [],
            pages: [],
            currentPageUrl: ""
        }
    },
    mounted() {
        this.getCompanies("/request/companies?page=1");
    },
    computed: {
    },
    methods: {
        getCompanies(pageUrl) {
            axios.get(pageUrl
            ).then(response => {
                this.companies = response.data.data;
                this.pages = response.data.links;
                this.currentPageUrl = pageUrl;
            }).catch(error => {
                //
            });
        },
        deleteCompanyConfirm(id, name) {
            this.$swal({
                title: 'Are you sure?',
                text: `You will delete ${name} from the company list, as well as all the employees that belongs to this company.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.deleteCompany(id, name);
                }
            });
        },
        deleteCompany(id, name) {
            axios.delete('/request/company/' + id
            ).then(response => {
                this.$swal({
                    icon: 'success',
                    title: 'Deleted!',
                    text: `${name} was successfully deleted`,
                })

                this.getCompanies(this.currentPageUrl);

            }).catch(error => {
                var title = "";
                var text = "";
                if(error.response.status == 403){
                    title = "Forbbiden";
                    text = "You are not allowed to perform this action!";
                } else {
                    title = "Oops...";
                    text = "Something went wrong!";
                }
                this.$swal({
                    icon: 'error',
                    title: title,
                    text: text
                })
            });
        }
    }
}
</script>
