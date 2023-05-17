export default {
    user: {
        token: sessionStorage.getItem('TOKEN'),
        data: JSON.parse(sessionStorage.getItem('AUTH')) ?? {},
    },
    products: {
        loading: false,
        data: [],
        links: [],
        from: null,
        to: null,
        page: 1,
        limit: null,
        total: null
    },
    users: {
        loading: false,
        data: [],
        links: [],
        from: null,
        to: null,
        page: 1,
        limit: null,
        total: null
    },
    roles: {
        loading: false,
        data: [],
        links: [],
        from: null,
        to: null,
        page: 1,
        limit: null,
        total: null
    },
    permissions: {
        loading: false,
        data: [],
        links: [],
        from: null,
        to: null,
        page: 1,
        limit: null,
        total: null
    },
    contents: {
        loading: false,
        data: [],
        subscriptionPlans:[],
        user:[],
        links: [],
        from: null,
        to: null,
        page: 1,
        limit: null,
        total: null
    },
    customers: {
        loading: false,
        data: [],
        links: [],
        from: null,
        to: null,
        page: 1,
        limit: null,
        total: null
    },
    orders: {
        loading: false,
        data: [],
        links: [],
        from: null,
        to: null,
        page: 1,
        limit: null,
        total: null
    },
    plan: {
        name:"",
        price:"",
        price_id:""
    },
    billingInfo: {
        email: '',
        name: '',
        address: '',
        city: '',
        state: '',
        postalCode: '',
        country: '',
    },
    stripe:{
        clientId:"",
        clientSecret:"",
        clientName:"",
        subscriptionId:"",
        planId:"",
        planName:"",
        planPrice:"",
    },
    toast: {
        show: false,
        message: '',
        delay: 5000
    },
    subscriptionPlans:{
        loading: false,
        data: [],
        links: [],
        from: null,
        to: null,
        page: 1,
        limit: null,
        total: null,
        monthly: [],
        yearly: []
    },
    guestImage : '',
    dateOptions: [
        {value: '1d', title: 'Last Day'},
        {value: '1k', title: 'Last Week'},
        {value: '2k', title: 'Last 2 Weeks'},
        {value: '1m', title: 'Last Month'},
        {value: '3m', title: 'Last 3 Months'},
        {value: '6m', title: 'Last 6 Months'},
        {value: 'all', title: 'All Time'},
    ]
}
