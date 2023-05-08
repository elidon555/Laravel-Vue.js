import {createRouter, createWebHistory} from "vue-router";
import AppLayout from '../components/AppLayout.vue'
import Login from "../views/Login.vue";
import Dashboard from "../views/Dashboard.vue";
import Users from "../views/Users/Users.vue";
import RequestPassword from "../views/RequestPassword.vue";
import ResetPassword from "../views/ResetPassword.vue";
import NotFound from "../views/NotFound.vue";
import store from "../store";
import Report from "../views/Reports/Report.vue";
import OrdersReport from "../views/Reports/OrdersReport.vue";
import CustomersReport from "../views/Reports/CustomersReport.vue";
import Signup from "../views/Signup.vue";
import Roles from "../views/Roles/Roles.vue";
import Permissions from "../views/Permissions/Permissions.vue";
import {computed} from "vue";
import Contents from "../views/Content/Contents.vue";
import Profile from "../views/Profile/Profile.vue";
import CheckoutView from "../views/Subscribe/CheckoutView.vue";
import SubscriptionPlans from "../views/SubscriptionPlans/SubscriptionPlans.vue";


const routes = [
  {
    path: '/',
    redirect: '/app'
  },
  {
    path: '/app',
    name: 'app',
    redirect: '/app/dashboard',
    component: AppLayout,
    meta: {
      requiresAuth: true
    },
    children: [
      {
        path: 'dashboard',
        name: 'app.dashboard',
        component: Dashboard
      },
      {
        path: 'contents',
        name: 'app.contents',
        component: Contents
      },
      {
        path: 'users',
        name: 'app.users',
        component: Users,
        meta: {
          roles: ["admin"],
        },
      },
      {
        path: 'roles',
        name: 'app.roles',
        component: Roles,
        meta: {
          roles: ["admin"],
        },
      },
      {
        path: 'permissions',
        name: 'app.permissions',
        component: Permissions,
        meta: {
          roles: ["admin"],
        },
      },
      {
        path: 'subscriptionPlans',
        name: 'app.subscriptionPlans',
        component: SubscriptionPlans,
        meta: {
          roles: ["admin","user"],
        },
      },
      {
        path: 'profile',
        name: 'app.profile',
        component: Profile,
      },
      {
        path: 'subscribe',
        name: 'app.subscribe',
        component: CheckoutView,
      },
      {
        path: '/report',
        name: 'reports',
        component: Report,
        children: [
          {
            path: 'orders/:date?',
            name: 'reports.orders',
            component: OrdersReport
          },
          {
            path: 'customers/:date?',
            name: 'reports.customers',
            component: CustomersReport
          }
        ]
      },
    ]
  },
  {
    path: '/login',
    name: 'login',
    component: Login,
    meta: {
      requiresGuest: true
    }
  },  {
    path: '/signup',
    name: 'signup',
    component: Signup,
    meta: {
      requiresGuest: true
    }
  },
  {
    path: '/request-password',
    name: 'requestPassword',
    component: RequestPassword,
    meta: {
      requiresGuest: true
    }
  },
  {
    path: '/reset-password/:token',
    name: 'resetPassword',
    component: ResetPassword,
    meta: {
      requiresGuest: true
    }
  },
  {
    path: '/:pathMatch(.*)',
    name: 'notfound',
    component: NotFound,
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes
})


router.beforeEach((to, from, next) => {

  const auth = store.state.user.data
  const token = store.state.user.token

  if (to.meta.requiresAuth && !token) {
    next({name: 'login'})
  } else if (token && auth.roles && to.meta.roles ) {

    const hasRole = auth.roles.some(role => to.meta.roles.includes(role['name']));
    if (!hasRole) next({ name: 'app.dashboard' });

    next();

} else if (to.meta.requiresGuest && token) {
    next({name: 'app.dashboard'})
  } else {
    next();
  }

})

export default router;
