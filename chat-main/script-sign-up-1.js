//////////////////cursorrrrrrrr////////////////
var cursor = document.querySelector('.cursor');
var cursorinner = document.querySelector('.cursor2');
var a = document.querySelectorAll('a');

document.addEventListener('mousemove', function(e){
  var x = e.clientX;
  var y = e.clientY;
  cursor.style.transform = `translate3d(calc(${e.clientX}px - 50%), calc(${e.clientY}px - 50%), 0)`
});

document.addEventListener('mousemove', function(e){
  var x = e.clientX;
  var y = e.clientY;
  cursorinner.style.left = x + 'px';
  cursorinner.style.top = y + 'px';
});

document.addEventListener('mousedown', function(){
  cursor.classList.add('click');
  cursorinner.classList.add('cursorinnerhover')
});

document.addEventListener('mouseup', function(){
  cursor.classList.remove('click')
  cursorinner.classList.remove('cursorinnerhover')
});

a.forEach(item => {
  item.addEventListener('mouseover', () => {
    cursor.classList.add('hover');
  });
  item.addEventListener('mouseleave', () => {
    cursor.classList.remove('hover');
  });
})

// ///////////////////transition///////////////////

// document.getElementById('myForm').addEventListener('submit', function(e) {
//     e.preventDefault();
//     var firstname = document.querySelector('input[name="firstname"]');
//     var lastname = document.querySelector('input[name="lastname"]');
//     var email = document.querySelector('input[name="email"]');
//     var terms = document.querySelector('input[name="terms"]');

//     if (firstname.value && lastname.value && email.value && terms.checked) {
//         var bubble = document.getElementById('bubble');
//         bubble.classList.add('animate');
//         setTimeout(function() {
//             bubble.classList.remove('animate');
//             bubble.classList.add('animate2');
//             setTimeout(function() {
//                 bubble.classList.remove('animate2');
//                 document.getElementById('form_contact').classList.add('fadeOut'); // Add the fadeOut class
//                 setTimeout(function() {
//                     document.getElementById('form_contact').submit(); // Submit the form
//                 }); // Wait for the fadeOut animation to finish
//             }, 2000); // Same duration as the CSS transition
//         }, 2000); // Same duration as the CSS transition
//     }
// });

// ///////////////////gsap///////////////////////
// var colorsChanged = false; // Flag to keep track of whether the colors have been changed

// var originalBodyColor = document.body.style.backgroundColor;
// var originalBubbleColor = window.getComputedStyle(document.querySelector('.bubble-1')).backgroundColor;
// var originalFormBackgroundColor = window.getComputedStyle(document.querySelector('#form_contact')).backgroundColor;
// var originalFormTextColor = window.getComputedStyle(document.querySelector('#form_contact')).color;
// var originalSubmitButtonColor = window.getComputedStyle(document.querySelector('#form_contact input[type="submit"]')).backgroundColor;
// var originalSubmitButtonTextColor = window.getComputedStyle(document.querySelector('#form_contact input[type="submit"]')).color;

// document.getElementById('reverseColorsButton').addEventListener('click', function() {
//         var tl = gsap.timeline();

//         if (!colorsChanged) {
//                 // If the colors haven't been changed, change them
//                 tl.to('body', {backgroundColor: 'black', duration: 1})
//                     .to('.bubble-1, .bubble-2, .bubble-3', {backgroundColor: 'lightgray', duration: 1}, "-=1") // "-=1" means start 1 second before the end of the previous animation
//                     .to('#form_contact input, #form_contact select, #form_contact textarea', {backgroundColor: '#ccc', duration: 1}, "-=1") // Change the colors of the form elements
//                     .to('#form_contact input[type="submit"]', {backgroundColor: '#0099ff', duration: 1}, "-=1"); // Change the color of the submit button

//                 colorsChanged = true; // Update the flag
//         } else {
//                 // If the colors have been changed, change them back to their original state
//                 tl.to('body', {backgroundColor: originalBodyColor, duration: 1})
//                     .to('.bubble-1, .bubble-2, .bubble-3', {backgroundColor: originalBubbleColor, duration: 1}, "-=1") // "-=1" means start 1 second before the end of the previous animation
//                     .to('#form_contact input, #form_contact select, #form_contact textarea', {backgroundColor: originalFormBackgroundColor, color: originalFormTextColor, duration: 1}, "-=1") // Change the colors of the form elements back to their original state
//                     .to('#form_contact input[type="submit"]', {backgroundColor: originalSubmitButtonColor, color: originalSubmitButtonTextColor, duration: 1}, "-=1"); // Change the color of the submit button back to its original state

//                 colorsChanged = false; // Update the flag
//         }
// });


/////////////////////////error///////////////////////////
const form = document.querySelector(".signup form"),
continueBtn = form.querySelector(".button input"),
errorText = form.querySelector(".error-text");

form.addEventListener("submit", (e) => {
  e.preventDefault();
});

continueBtn.onclick = () => {
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "signup.php", true);
  xhr.onload = () => {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        let data = xhr.response;
        if (data === "success") {
          location.href = "index.php";
        } else {
          errorText.style.display = "block";
          errorText.textContent = data;
        }
      }
    }
  };
  let formData = new FormData(form);
  xhr.send(formData);
};