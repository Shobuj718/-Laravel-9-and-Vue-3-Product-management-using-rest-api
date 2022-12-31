import { createWebHistory, createRouter } from 'vue-router'
import store from '@/store'

/* Guest Component */
const Login = () => import('@/components/Login.vue')
const Register = () => import('@/components/Register.vue')
/* Guest Component */

const AllProduct = () => import('@/components/products/index.vue')
const CreateProduct = () => import('@/components/products/create.vue')
const EditProduct = () => import('@/components/products/edit.vue')
const ShowProduct = () => import('@/components/products/show.vue')

/* Layouts */
const DahboardLayout = () => import('@/components/layouts/Default.vue')
const GuestLayout = () => import('@/components/layouts/Guest.vue')

/* Authenticated Component */
const Dashboard = () => import('@/components/Dashboard.vue')
/* Authenticated Component */


const routes = [
    
    {
        name: "login",
        path: "/login",
        component: Login,
        meta: {
            middleware: "guest",
            title: `Login`
        }
    },
    {
        name: "register",
        path: "/register",
        component: Register,
        meta: {
            middleware: "guest",
            title: `Register`
        }
    },

     {
        name: "products",
        path: "/",
        component: AllProduct,
        meta: {
            middleware: "guest",
            title: `All Product`
        }
    },

    {
        name: "products.show",
        path: "/products/show/:id",
        component: ShowProduct,
        meta: {
            middleware: "guest",
            title: `Product Show`
        }
    },
    
    {
        path: "/home",
        component: DahboardLayout,
        meta: {
            middleware: "auth"
        },
        children: [
            {
                name: "dashboard",
                path: '/home',
                component: Dashboard,
                meta: {
                    title: `Dashboard`
                }
            },
           
            {
                name: "products.create",
                path: "/products/create",
                component: CreateProduct,
                meta: {
                    title: `Product create`
                }
            },
            {
                name: "products.edit",
                path: "/products/edit/:id",
                component: EditProduct,
                meta: {
                    title: `Product Update`
                }
            },
            
        ]
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes, // short for `routes: routes`
})

router.beforeEach((to, from, next) => {
    document.title = to.meta.title
    if (to.meta.middleware == "guest") {
        // if (store.state.auth.authenticated) {
        //     next({ name: "dashboard" })
        // }
        next()
    } else {
        if (store.state.auth.authenticated) {
            next()
        } else {
            next({ name: "login" })
        }
    }
})

export default router