import {createStore} from "vuex";
import {BillingInfo} from "./Modules/BillingInfo";
import {User} from "./Modules/User";
import {Users} from "./Modules/Users";
import {DateOptions} from "./Modules/DateOptions";
import {Payments} from "./Modules/Payments";
import {Permissions} from "./Modules/Permissions";
import {Roles} from "./Modules/Roles";
import {Plan} from "./Modules/Plan";
import {Subscriptions} from "./Modules/Subscriptions";
import {SubscriptionPlans} from "./Modules/SubscriptionPlans";
import {Stripe} from "./Modules/Stripe";
import {GuestImage} from "./Modules/GuestImage";
import {Contents} from "./Modules/Contents";


const store = createStore({
  modules:{
    billingInfo:BillingInfo,
    user:User,
    users:Users,
    dateOptions:DateOptions,
    payments:Payments,
    permissions:Permissions,
    roles:Roles,
    plan:Plan,
    subscriptions:Subscriptions,
    subscriptionPlans:SubscriptionPlans,
    stripe:Stripe,
    guestImage:GuestImage,
    contents:Contents,
  }
})

export default store
