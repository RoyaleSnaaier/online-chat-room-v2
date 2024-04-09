window.onload = function() {
    var chatBox = document.querySelector('.chat-box');
    chatBox.scrollTop = chatBox.scrollHeight;
}









// Function to make an AJAX request to update user status
function updateUserStatus(status, callback) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "update_status.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                // Call the callback function with the response text
                callback(null, xhr.responseText);
            } else {
                // Call the callback function with an error message
                callback("Error updating user status", null);
            }
        }
    };
    xhr.send("status=" + encodeURIComponent(status));
}

// Function to update user status to Online
function updateUserStatusToOnline() {
    updateUserStatus("Online", function(error, response) {
        if (!error) {
            console.log("User status changed to Online");
        } else {
            console.error(error);
        }
    });
}

// Function to update user status to Away
function updateUserStatusToAway() {
    updateUserStatus("Away", function(error, response) {
        if (!error) {
            console.log("User status changed to Away");
        } else {
            console.error(error);
        }
    });
}

// Function to update user status to Offline
function updateUserStatusToOffline() {
    updateUserStatus("Offline", function(error, response) {
        if (!error) {
            console.log("User status changed to Offline");
        } else {
            console.error(error);
        }
    });
}

// Event listener for mousemove event
document.addEventListener("mousemove", function() {
    updateUserStatusToOnline();
});

// Event listener for visibility change
document.addEventListener("visibilitychange", function() {
    if (document.visibilityState === 'hidden') {
        updateUserStatusToAway();
    }
});

// Event listener for beforeunload
window.addEventListener("beforeunload", function(event) {
    updateUserStatusToOffline();
});


////////////////gsap////////////////

// GSAP animation for chat box
gsap.from('.chat-box', {
    duration: 1,
    opacity: 0,
    y: 50,
    ease: 'easeInOut'
});

// GSAP animation for chat messages
gsap.from('.chat-message', {
    duration: 1,
    opacity: 0,
    y: 50,
    ease: 'easeInOut',
    stagger: 0.2
});

// GSAP animation for chat form input field when it gets focus
const inputField = document.querySelector(".input-field");
inputField.addEventListener('focusin', () => {
    gsap.to('.input-field', {
        duration: 0.5,
        width: 200,
        ease: 'easeInOut'
    });
});

// GSAP animation for chat form input field when it loses focus
inputField.addEventListener('focusout', () => {
    gsap.to('.input-field', {
        duration: 0.5,
        width: 100,
        ease: 'easeInOut'
    });
});