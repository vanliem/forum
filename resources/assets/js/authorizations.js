let user = window.App.user;

module.exports = {
    updateReply(reply) {
        return reply.owner.id === user.id;
    }
};
