const Ziggy = {"url":"http:\/\/localhost","port":null,"defaults":{},"routes":{"login.store":{"uri":"login","methods":["POST"]},"logout":{"uri":"api\/logout","methods":["POST"]},"password.email":{"uri":"forgot-password","methods":["POST"]},"password.update":{"uri":"reset-password","methods":["POST"]},"register.store":{"uri":"register","methods":["POST"]},"user-profile-information.update":{"uri":"user\/profile-information","methods":["PUT"]},"user-password.update":{"uri":"user\/password","methods":["PUT"]},"password.confirmation":{"uri":"user\/confirmed-password-status","methods":["GET","HEAD"]},"password.confirm.store":{"uri":"user\/confirm-password","methods":["POST"]},"two-factor.login.store":{"uri":"two-factor-challenge","methods":["POST"]},"two-factor.enable":{"uri":"user\/two-factor-authentication","methods":["POST"]},"two-factor.confirm":{"uri":"user\/confirmed-two-factor-authentication","methods":["POST"]},"two-factor.disable":{"uri":"user\/two-factor-authentication","methods":["DELETE"]},"two-factor.qr-code":{"uri":"user\/two-factor-qr-code","methods":["GET","HEAD"]},"two-factor.secret-key":{"uri":"user\/two-factor-secret-key","methods":["GET","HEAD"]},"two-factor.recovery-codes":{"uri":"user\/two-factor-recovery-codes","methods":["GET","HEAD"]},"profile.show":{"uri":"user\/profile","methods":["GET","HEAD"]},"other-browser-sessions.destroy":{"uri":"user\/other-browser-sessions","methods":["DELETE"]},"current-user-photo.destroy":{"uri":"user\/profile-photo","methods":["DELETE"]},"current-user.destroy":{"uri":"user","methods":["DELETE"]},"sanctum.csrf-cookie":{"uri":"sanctum\/csrf-cookie","methods":["GET","HEAD"]},"login":{"uri":"\/","methods":["GET","HEAD"]},"register":{"uri":"register","methods":["GET","HEAD"]},"dashboard":{"uri":"dashboard","methods":["GET","HEAD"]},"user.update":{"uri":"user\/update","methods":["POST"]},"user.update-password":{"uri":"user\/update-password","methods":["POST"]},"restaurants.index":{"uri":"restaurants","methods":["GET","HEAD"]},"restaurants.create":{"uri":"restaurants\/create","methods":["GET","HEAD"]},"restaurants.show":{"uri":"restaurants\/{id}","methods":["GET","HEAD"],"parameters":["id"]},"restaurants.update":{"uri":"restaurants\/{restaurant}","methods":["PUT"],"parameters":["restaurant"]},"restaurants.store":{"uri":"restaurants","methods":["POST"]},"restaurants.edit":{"uri":"restaurants\/{id}\/edit","methods":["GET","HEAD"],"parameters":["id"]},"get.municipios":{"uri":"get\/municipios","methods":["GET","HEAD"]},"user.profile":{"uri":"perfil","methods":["GET","HEAD"]},"restaurants.AccountConfig":{"uri":"configuracio","methods":["GET","HEAD"]},"create-restaurant":{"uri":"restaurant\/crear","methods":["GET","HEAD"]},"restaurant.management":{"uri":"restaurant\/{id}\/gestio","methods":["GET","HEAD"],"parameters":["id"]},"user.restaurant":{"uri":"user\/{userId}\/restaurant","methods":["GET","HEAD"],"parameters":["userId"]},"reserves.store":{"uri":"reserves","methods":["POST"]},"taules.index":{"uri":"taules","methods":["GET","HEAD"]},"restaurant.reserves":{"uri":"restaurants\/{id}\/reserves","methods":["GET","HEAD"],"parameters":["id"]},"reserves.update":{"uri":"reserves\/{id}","methods":["PUT"],"parameters":["id"]},"storage.local":{"uri":"storage\/{path}","methods":["GET","HEAD"],"wheres":{"path":".*"},"parameters":["path"]}}};
if (typeof window !== 'undefined' && typeof window.Ziggy !== 'undefined') {
  Object.assign(Ziggy.routes, window.Ziggy.routes);
}
export { Ziggy };
