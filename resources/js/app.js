import './bootstrap';


import $ from "jquery";

window.$ = window.jQuery = $;

import "./cart"; // Import your cart.js file

// access to private channel
var channel = Echo.private(`App.Models.User.${userID}`);
// make private notification listner to the notification event
channel.notification(function (data) {
    console.log(data);
    alert(data.body);
});
