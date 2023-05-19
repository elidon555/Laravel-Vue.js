export default function auth({ to, next, store }) {

  if (!store.state.user.token && to.name!=='app.contents') {
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
