
export const GuestImage = {
    state: () => ({
        img:''
    }),
    mutations: {
        setGuestLayoutImage(state,[image]) {
            state.img = image
        }
    },
    actions: {
        guestImage({commit},image) {
            commit('setGuestLayoutImage', [image])
        }
    },
    getters: {}
}