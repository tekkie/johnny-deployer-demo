require( 'dotenv' ).config( {silent: true} );

var events = require('events');
var myEmitter = new events.EventEmitter();

//valid request listener for async response after api call
myEmitter.on('nlu', (resp, bot, update) => {
    var resp2 = JSON.stringify(resp);
    const message = {
        recipient: {
            id: update.sender.id,
        },
        message: {
            text: "text"+resp2+"text"
        },
    };
    return bot.sendMessage(message,{ ignoreMiddleware: true });
});


// Create service wrapper for Natural Language Understanding
const NaturalLanguageUnderstandingV1 = require('watson-developer-cloud/natural-language-understanding/v1.js');
const nlu = new NaturalLanguageUnderstandingV1({
    'username': process.env.NATURAL_LANGUAGE_UNDERSTANDING_USERNAME || '<username>',
    'password': process.env.NATURAL_LANGUAGE_UNDERSTANDING_PASSWORD || '<password>',
    version_date: NaturalLanguageUnderstandingV1.VERSION_DATE_2017_02_27
});

// Define what features you want to extract with NLU
var features= {
    entities: {
        //can add custom nlu model with <model: model_name>
    },
    semantic_roles: {
    }
};

const multiplyReply = ({
    type: 'outgoing',
    name: 'multiply-reply',
    controller: (bot, update, message, next) => {
        // console.log(update.watsonUpdate);
        // console.log(update.session.watsonContext);
        // console.log(update.watsonConversation);
        if (message.message.text === '!calculate') {
            var first_number = Number(update.watsonUpdate.entities[1].value)
            var second_number = Number(update.watsonUpdate.entities[2].value)
            var result = (first_number * second_number).toString()
            message.message.text = result;
        }
        next();
    }
});

// const botTyping = ({
//     type: 'outgoing',
//     name: 'show-indicator-before-sending-message',
//     controller: (bot, update, message, next) => {
//         const userId = message.recipient.id;
//         bot.sendIsTypingMessageTo(userId, { ignoreMiddleware: true })
//         .then(() => {
//             setTimeout(() => {
//                 next();
//             }, 1000);
//         });
//     },
// });

const validRequest = ({
    type:'outgoing',
    name:'apiCallResponse',
    controller: (bot,update,message,next) => {
        if (update.session.watsonContext.valid_request == true) {
            nlu.analyze({
                'html': update.watsonUpdate.input.text, // Buffer or String
                features
            },
            (err, response) => {
                if (err)
                    console.log('error:', err);
                else {
                    var resp = response;
                    myEmitter.emit('nlu', resp, bot, update);
                }
            });
        }
        next();
    }
});

module.exports = {
    validRequest,
    multiplyReply,
}