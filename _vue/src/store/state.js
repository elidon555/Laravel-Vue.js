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
    plans: {
        data: [],
    },
    plan: {
        subscriptionId: '',
        priceId: '',
        planId: '',
    },
    billingInfo: {
        email: '',
        name: '',
        address: '',
        city: '',
        state: '',
        postalCode: '',
    },
    stripe:{
        clientId:"",
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
    dateOptions: [
        {key: '1d', text: 'Last Day'},
        {key: '1k', text: 'Last Week'},
        {key: '2k', text: 'Last 2 Weeks'},
        {key: '1m', text: 'Last Month'},
        {key: '3m', text: 'Last 3 Months'},
        {key: '6m', text: 'Last 6 Months'},
        {key: 'all', text: 'All Time'},
    ]
}
