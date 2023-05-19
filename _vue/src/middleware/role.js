export default function role(requiredRoles) {
  return function ({next, store}) {
    if (checkRoles(store.state.user.roles,requiredRoles)){
      next()
    } else next({name: "notFound"});
  };
}

  function checkRoles(userRoles, requiredRoles) {
    return userRoles.some(role => requiredRoles.includes(role.name));
  }