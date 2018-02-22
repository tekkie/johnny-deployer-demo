require( 'dotenv' ).config( {silent: true} );

const watsonReply = ({
    type: 'incoming',
    name: 'watson-middleware',
    controller: (bot, update) => {
        if (process.env.SLACK_BOT_USER_ID !== update.raw.event.user) {
            return bot.sendTextCascadeTo(update.watsonUpdate.output.text, update.sender.id)
            .catch((err) => {
                console.log(err.message);
            })
        }
    }
});

module.exports = {
    watsonReply,
}