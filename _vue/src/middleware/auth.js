export default function auth({ to, next, store }) {
  if (!store.state.user.token) {
    store.dispatch("getCurrentUser").then(() => {
      if (!store.state.user.token) {
        next({name:'login'});
      } else {
        next()
      }
    });
  } else {
    next();
  }
}
