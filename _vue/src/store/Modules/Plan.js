
export const Plan = {
    state: () => (
        {
            name:"",
            price:"",
            price_id:""
        }
    ),
    mutations: {
        setPlan(state, [name,price,price_id]) {
            if (name && price) {
                state = {
                    name: name,
                    price:price,
                    price_id:price_id
                }
            }
        }
    },
    actions: {},
    getters: {}
}