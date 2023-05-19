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
import SubscriptionsReport from "../views/Reports/SubscriptionsReport.vue";
import SubscribersReport from "../views/Reports/SubscribersReport.vue";
import Signup from "../views/Signup.vue";
import Roles from "../views/Roles/Roles.vue";
import Permissions from "../views/Permissions/Permissions.vue";
import Contents from "../views/Content/Contents.vue";
import Profile from "../views/Profile/Profile.vue";
import SubscriptionPlans from "../views/SubscriptionPlans/SubscriptionPlans.vue";
import Payments from "../views/Payments/Payments.vue";
import Subscriptions from "../views/Subscriptions/Subscriptions.vue";
import PlanView from "../views/Subscribe/PlanView.vue";
import guest from "../middleware/guest";
import auth from "../middleware/auth";
import middlewarePipeline from "./middlewarePipeline";
import role from "../middleware/role";


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
    meta: { middleware: [auth] },
    children: [
      {
        path: 'dashboard',
        name: 'app.dashboard',
        component: Dashboard,
        meta: { middleware: [auth,role(['role',''])] },
      },
      {
        path: 'contents/:id?',
        name: 'app.contents',
        component: Contents,
      },
      {
        path: 'users',
        name: 'app.users',
        component: Users,
        meta: { middleware: [guest, role(['admin']) ] }
      },
        {
            path: 'test',
            name: 'app.test',
            component: PlanView,
        },
      {
        path: 'roles',
        name: 'app.roles',
        component: Roles,
        meta: { middleware: [auth, role(['admin'])] }
      },
      {
        path: 'permissions',
        name: 'app.permissions',
        component: Permissions,
        meta: { middleware: [auth, role(['admin'])] }
      },
      {
        path: 'subscription-plans',
        name: 'app.subscriptionPlans',
        component: SubscriptionPlans,
        meta: { middleware: [auth] }
      },
      {
        path: 'payments',
        name: 'app.payments',
        component: Payments,
        meta: { middleware: [auth, role(['admin'])] }
      },
      {
        path: 'subscriptions',
        name: 'app.subscriptions',
        component: Subscriptions,
        meta: { middleware: [auth, role(['admin'])] }
      },
      {
        path: 'profile',
        name: 'app.profile',
        component: Profile,
        meta: { middleware: [auth] }

      },
      {
        path: '/report',
        name: 'reports',
        component: Report,
        meta: { middleware: [guest, role(['admin','finance'])] },
        children: [
          {
            path: 'subscriptions/:date?',
            name: 'reports.subscriptions',
            component: SubscriptionsReport
          },
          {
            path: 'subscribers/:date?',
            name: 'reports.subscribers',
            component: SubscribersReport
          }
        ]
      },
    ]
  },
  {
    path: '/login/:user_id?',
    name: 'login',
    component: Login,
    meta: { middleware: [guest] }
  },  {
    path: '/signup',
    name: 'signup',
    component: Signup,
    meta: { middleware: [guest] }
  },
  {
    path: '/request-password',
    name: 'requestPassword',
    component: RequestPassword,
    meta: { middleware: [guest] }
  },
  {
    path: '/reset-password/:token',
    name: 'resetPassword',
    component: ResetPassword,
    meta: { middleware: [guest] }
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
  const middleware = to.meta.middleware;
  const context = { to, from, next, store };

  if (!middleware) {
    return next();
  }

  const pipeline = middlewarePipeline(context, middleware, 0);
  pipeline();
});



export default router;
