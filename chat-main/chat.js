document.addEventListener('DOMContentLoaded', function () {
    const messageForm = document.getElementById('message-form');

    messageForm.addEventListener('submit', function (event) {
        event.preventDefault();
        
        const formData = new FormData(messageForm);

        fetch('send_message.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                console.log('Message sent successfully');
                fetchMessages(); // Reload messages after successful send
            } else {
                console.error('Error sending message:', data.message);
            }
        })
        .catch(error => {
            console.error('Error sending message:', error);
        });
    });

    function fetchMessages() {
        fetch('fetch_messages.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text(); // Change to text() to log the response text
        })
        .then(data => {
            console.log('Response text:', data); // Log the response text

            // Try parsing response text as JSON
            try {
                const jsonData = JSON.parse(data);
                // Proceed with processing JSON data
                const messageContainer = document.getElementById('message-container');
                messageContainer.innerHTML = '';
                jsonData.forEach(() => {
                    // Process messages
                });
            } catch (error) {
                // Handle JSON parsing error
                console.error('Error parsing JSON:', error);
            }
        })
        .catch(error => {
            console.error('Error fetching messages:', error);
        });
    }
    
    

    fetchMessages();
    setInterval(fetchMessages, 5000); // Fetch messages every 5 seconds
});
