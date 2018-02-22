require( 'dotenv' ).config( {silent: true} );

const express = require('express');
const Botmaster = require('botmaster');
const SessionWare = require('botmaster-session-ware');
const requestLib = require('request-promise');

console.log(process.env);

const app = express();
app.get('/', (req, res) => {
    res.send('Hello world\n');
});
app.use('/slack-button', express.static(__dirname + '/views'));

const port = process.env.RUNTIME_PORT || 8080;
const myServer = app.listen(port,'0.0.0.0');
const botmaster = new Botmaster({ server: myServer});

console.log('running on: ' + port);

//Slack setup
const SlackBot = require('botmaster-slack');
const slackSettings = {
    webhookEndpoint:     'johnny-hooked',
    storeTeamInfoInFile: true,
    credentials:         {
        clientId: process.env.SLACK_CLIENT_ID || '<clientId>',
        clientSecret: process.env.SLACK_CLIENT_SECRET || '<clientSecret>',
        verificationToken: process.env.SLACK_VER_TOKEN || '<verificationToken>',
    }
};
const slackBot = new SlackBot(slackSettings);
botmaster.addBot(slackBot);

botmaster.use({
    type: 'incoming',
    name: 'slack-incoming-middleware',
    controller: (bot, update) => {
        if (typeof update.raw.event.bot_id !== 'undefined' || process.env.SLACK_BOT_USER_ID === update.raw.event.user) {
            console.log('skipping ' + update.raw.event.text + ' with bot_id=' + update.raw.event.bot_id);
            return;
        }

        var requestOptions = {
            method: 'post',
            url: process.env.BRAIN_ENDPOINT,
            body: {
                message: update.raw.event.text
            },
            json: true,
            resolveWithFullResponse: true,
            simple: false
        };

        requestLib(requestOptions)
            .then(function(response) {
                if (response.statusCode !== 200) {
                    return bot.reply(update, 'I encountered a technical issue that prevented me from understanding you.');
                }

                console.log('data', response.body);
                return bot.reply(update, response.body);
            })
            .catch(function(error) { console.log('problems!', error); })
        ;
    }
});

const sessionWare = SessionWare();
botmaster.useWrapped(sessionWare.incoming, sessionWare.outgoing);

botmaster.on('error', (bot, err) => {
    console.log(bot.type);
    console.log(err.stack);
});

myServer.on('listening', () => {
    console.log('Botmaster server running')
});
