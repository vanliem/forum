let user = window.App.user;

module.exports = {
    updateReply(reply) {
        return reply.owner.id === user.id;
    },

    updateThread (thread) {
        return thread.user.id === user.id;
    },

    own(model, prop = 'user_id') {
        return model[prop] === user.id;
    }
};
