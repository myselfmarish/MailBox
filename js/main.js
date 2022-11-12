import { SendMail } from "./components/mailer.js";
const responseBox = document.getElementById("responseBox"),
      responseData = document.getElementById("response"),
      closeWindow = document.getElementById("closeResponse");
        responseBox.classList.add("hidden");
        closeWindow.addEventListener("click",()=>{
            responseBox.classList.add("hidden");
        },false);

(() => {
    const { createApp } = Vue

    createApp({
        data() {
            return {
                message: 'Hello Vue!'
            }
        },

        methods: {
            processMailFailure(result) {
                let parsedResponse = JSON.parse(result.message).message;
                responseData.innerHTML = "";
                for (const message of parsedResponse){
                    responseData.innerHTML+=message+"<br>";
                }
                responseBox.classList.remove("hidden");
            },

            processMailSuccess(result) {
                responseData.innerHTML=result.message;
                responseBox.classList.remove("hidden");
            },

            processMail(event) {
                // use the SendMail component to process mail
                SendMail(this.$el.parentNode)
                    .then(data => this.processMailSuccess(data))
                    .catch(err => this.processMailFailure(err));
            }
        }
    }).mount('#mail-form')
})();
