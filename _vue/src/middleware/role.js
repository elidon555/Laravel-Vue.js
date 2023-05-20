export default function role(requiredRoles) {
  return function ({next, store}) {
    if (checkRoles(store.state.user.data.roles, requiredRoles)) {
      next();
    } else {
      next({ name: "app.profile" });
    }
  };
}

function checkRoles(userRoles, requiredRoles) {
  if (userRoles && requiredRoles){
    return userRoles.some(role => requiredRoles.includes(role.name));
  }
}