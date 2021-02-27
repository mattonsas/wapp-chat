/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

Vue.component('chat-messages', require('./components/ChatMessages.vue'));
Vue.component('chat-form', require('./components/ChatForm.vue'));

const app = new Vue({
	el: '#app',

	data: {
		messages: []
	},

	created() {
		this.fetchMessages();
	},

	methods: {
		fetchMessages() {
			let conversationId = localStorage.getItem('conversation-id');

			let url = '/api/conversations/' + conversationId + '/messages';

			axios.get(url).then(response => {
				this.messages = response.data.messages.items
			});
		},

		sendMessage(message) {
			let conversationId = localStorage.getItem('conversation-id');

			let url = '/api/conversations/' + conversationId + '/messages';

			axios.post(url, message).then(response => {
				this.messages.push(response.message)
			})
		}
	}
});

Echo.private('conversation').listen('MessageSent', (e) => {
	// console.log(e);

	this.messages.push({
		conversation: e.conversation,
		message: e.message.message
	})
})