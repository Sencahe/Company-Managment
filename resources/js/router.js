import { createWebHistory, createRouter } from "vue-router";
import axios from "axios";
import IndexComponent from "./components/IndexComponent.vue"
import DashboardComponent from "./components/DashboardComponent.vue"
import DashboardIndexComponent from "./components/DashboardIndexComponent.vue"
import EmployeesComponent from "./components/EmployeesComponent.vue"
import EmployeeFormComponent from "./components/EmployeeFormComponent.vue"
import CompaniesComponent from "./components/CompaniesComponent.vue"
import CompanyFormComponent from "./components/CompanyFormComponent.vue"
import NotFound from "./components/NotFound.vue"


const routes = [
  { path: "/", name: "Index", component: IndexComponent },
  {
    path: "/dashboard", name: "Dashboard", component: DashboardComponent,  meta: { requiresAuth: true }, children: [
      { path: "/dashboard", name: "DashboardIndex", component: DashboardIndexComponent },
      { path: "/dashboard/employees", name: "EmployeesComponent", component: EmployeesComponent },
      { path: "/dashboard/employee/", name: "EmployeeFormComponentAdd", component: EmployeeFormComponent },
      { path: "/dashboard/employee/:id", name: "EmployeeFormComponentEdit", component: EmployeeFormComponent },
      { path: "/dashboard/companies", name: "CompaniesComponent", component: CompaniesComponent },
      { path: "/dashboard/company/", name: "CompanyFormComponentAdd", component: CompanyFormComponent },
      { path: "/dashboard/company/:id", name: "CompanyFormComponentEdit", component: CompanyFormComponent }
    ]
  },
  { path: "/:pathMatch(.*)*", component: NotFound }
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to, from, next) => {

  if (to.matched.some(record => record.meta.requiresAuth)) {
    axios.get('/auth/session')
      .then(() => {
        next();
      })
      .catch(() => {
        next('/');
      });

  } else {
    // Proceed to the requested route
    next();
  }
});




export default router;
