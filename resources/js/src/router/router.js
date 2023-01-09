import { createRouter,createWebHistory } from "vue-router";
import Home from "../views/Home.vue"
import ImportDrug from "../views/ImportDrug.vue"
const router = createRouter({
    history : createWebHistory('/'),
    routes:[
        {
            path : '/',
            name : 'home',
            component : Home
        },{
            path : '/import-drug',
            name : 'import-drug',
            component : ImportDrug
        }
    ]
})

export default router
