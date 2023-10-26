<template>

    <div>
        <h2 class="mb-5">Welcome <b>{{ user.name }}</b> to the Dashboard!</h2>
    </div>

    <div class="mt-4 row justify-content-center">

        <div class="row justify-content-center">
            <div class="col-md-3 row justify-content-center">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-header">Companies</div>
                    <div class="card-body">
                        <h5 class="card-title">{{ companiesCount }}</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-3 row justify-content-center">
                <div class="card text-white bg-success mb-3">
                    <div class="card-header">Employees</div>
                    <div class="card-body">
                        <h5 class="card-title">{{ employeesCount }}</h5>
                    </div>
                </div>
            </div>

        </div>

        <div style="max-width: 700px;" class="my-4">
            <h5 class="text-start mb-3">Companies with most employees</h5>
            <Bar :key="barKeyComponent"  :data="companiesChartData" :options="CompaniesChartOptions" />
        </div>

    </div>



</template>

<script>
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale
} from 'chart.js'
import { Bar } from 'vue-chartjs'
//import * as chartConfig from './chartConfig.js'

ChartJS.register(CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend)

export default {
    name: "DashboardIndexComponent",
    components: {
        Bar
    },
    data() {
        return {
            user: "",
            barKeyComponent: 0,
            employeesCount: 0,
            companiesCount: 0,
            companiesChartData: {
                labels: [],
                datasets: [
                    {
                        label: 'Employees',
                        backgroundColor: '#198754',
                        data: []
                    }
                ]
            },
            CompaniesChartOptions: {
                responsive: true,
                maintainAspectRatio: true
            }
        }
    },
    mounted(){
        this.getCompaniesChartData();
    },
    created() {
        this.user = JSON.parse(localStorage.getItem('user'));
        this.getCompanies();
        this.getEmployees();
        this.getCompaniesChartData();
    },
    methods: {
        getCompanies() {
            axios.get("/request/companies/count"
            ).then(response => {
                this.companiesCount = response.data.count;
            }).catch(error => {
                //
            });
        },
        getEmployees() {
            axios.get("/request/employees/count"
            ).then(response => {
                this.employeesCount = response.data.count;
            }).catch(error => {
                //
            });
        },
        getCompaniesChartData(){
            axios.get("/request/companies/by_employees/5"
            ).then(response => {
                this.setCompaniesChartData(response.data)
            }).catch(error => {
               // console.log(error);
            });
        },
        setCompaniesChartData(companiesChartData){
            var labels = [];
            var data = [];
            companiesChartData.forEach(company => {
                labels.push(company.name);
                data.push(parseInt(company.employees_count));
            });
            this.companiesChartData.labels = labels;
            this.companiesChartData.datasets[0].data = data;
            this.barKeyComponent += 1;
           // this.barKeyComponent += 1;
        }
    },
}

</script>

<style>
.card {
    max-width: 200px;
}
</style>
