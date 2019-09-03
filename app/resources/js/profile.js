require('./bootstrap');

window.Vue = require('vue');

const app = new Vue({
    el: '#app_msg',
    data: {
        msg: 'test',
        content: '',
        privsteMsgs: [],
        singleMsgs: [],
        msgFrom: '',
        conID: '',
        friend_id: '',
        seen: false,
        newMsgFrom: ''

    },
    ready: function(){
        this.created();

    },

    created(){
        axios.get('http://localhost:8080/app//public/index.php/getMessages')
            .then(response => {
                app.privsteMsgs = response.data; //we are putting data into our posts array
            })
            .catch(function (error) {
                console.log(error); // run if we have error
            });
    },

    methods:{
        messages: function(id){
            axios.get('http://localhost:8080/app/public/index.php/getMessages/' + id)
                .then(response => {
                    console.log(response.data);
                    app.singleMsgs = response.data;
                    app.conID = response.data[0].conversation_id
                })
                .catch(function (error) {
                    console.log(error); // run if we have error
                });
        },

        inputHandler(e){
            if(e.keyCode === 13 && !e.shiftKey){
                e.preventDefault();
                this.sendMsg();
            }
        },

        sendMsg(){
            if(this.msgFrom){
                axios.post('http://localhost:8080/app/public/index.php/sendMessage', {
                    conID: this.conID,
                    msg: this.msgFrom
                })
                    .then( (response) => {
                        console.log(response.data); // show if success
                        if(response.status===200){
                            app.singleMsgs = response.data;
                        }

                    })
                    .catch(function (error) {
                        console.log(error); // run if we have error
                    });

            }
        }
    },
    watch: {
        data: function () {
            console.log("NOVA PORUKA!");
        }
    }
});