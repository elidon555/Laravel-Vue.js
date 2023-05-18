import axiosClient from "../../axios";

export const DateOptions = {
    state: () => (
        [
            {value: '1d', title: 'Last Day'},
            {value: '1k', title: 'Last Week'},
            {value: '2k', title: 'Last 2 Weeks'},
            {value: '1m', title: 'Last Month'},
            {value: '3m', title: 'Last 3 Months'},
            {value: '6m', title: 'Last 6 Months'},
            {value: 'all', title: 'All Time'},
        ]
    ),
    mutations: {},
    actions: {},
    getters: {}
}