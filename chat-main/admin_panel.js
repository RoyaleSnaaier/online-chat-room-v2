// Add a class to the nav when scrolling down for sticky effect
window.addEventListener('scroll', function() {
    var nav = document.querySelector('nav');
    nav.classList.toggle('sticky', window.scrollY > 0);
});

// Add a class to the nav when hovering over an item for an animated effect
var navItems = document.querySelectorAll('nav ul li a');
navItems.forEach(function(item) {
    item.addEventListener('mouseover', function() {
        item.classList.add('animated');
    });
    item.addEventListener('mouseout', function() {
        item.classList.remove('animated');
    });
});


// Function to scroll the chat table to the bottom
function scrollToBottom() {
    var chatMessages = document.getElementById("chat-messages");
    if (chatMessages) {
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
}

// Scroll the chat table to the bottom when the page loads
window.onload = function() {
    console.log("Page loaded."); // Check if this message appears in the console
    scrollToBottom(); // Scroll to the bottom
};

///////////////cursoorrrrrr////////
