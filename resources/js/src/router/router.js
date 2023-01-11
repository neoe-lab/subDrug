import { createRouter,createWebHistory } from "vue-router";
import Home from "../views/Home.vue"
import ImportDrug from "../views/ImportDrug.vue"
import NotFoundPage from "../components/NotFoundPage.vue"
const router = createRouter({
    history : createWebHistory('/'),
    routes:[
        {
            path : '/',
            name : 'home',
            component : Home
        },{
            path : '/import',
            name : 'import-drug',
            component : ImportDrug
        },
        {
            path : "/:pathMatch(.*)*",
            component : NotFoundPage
        }
    ]
})

export default router
