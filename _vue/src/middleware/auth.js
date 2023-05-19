export default function auth({ to, next, store }) {
  const loginQuery = { path: "/login", query: { redirect: to.fullPath } };

  if (store.state.user.token) {
    store.dispatch("getCurrentUser").then(() => {
      if (!store.state.user.data) next(loginQuery);
      else next();
    });
  } else {
    next();
  }
}
