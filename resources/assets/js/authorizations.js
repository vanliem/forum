let user = window.App.user;

module.exports = {
    own(model, prop = 'user_id') {
        return model[prop] === user.id;
    },
    isAdmin() {
        return ['levanliem', 'vanliem'].includes(user.name);
    }
};
