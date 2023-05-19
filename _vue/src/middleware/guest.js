export default function guest({ next, store }) {
  if (!store.state.user.token) {
    next();
  }
}
