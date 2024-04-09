///////////gsap animation///////////
        // Wait for the window to load
        window.onload = function() {
            // Select all elements to animate
            var elements = document.querySelectorAll('.wrapper-1, .wrapper-2, .wrapper-3');

            // Animate each element
            elements.forEach(function(element, index) {
                // Use gsap.from() to animate from the initial state
                gsap.from(element, {
                    duration: 1, // Duration of the animation in seconds
                    opacity: 0, // Start from fully transparent
                    y: 50, // Start from 50px below the final position
                    delay: index * 0.5, // Delay the start of each animation by 0.5 seconds
                    ease: 'power1.out' // Easing function to make the animation more natural
                });
            });
        };