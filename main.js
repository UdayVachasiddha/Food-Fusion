// 1. Password Toggle Function (Must be at the very top, OUTSIDE the event listener!)
function togglePasswordVisibility(inputId, btnElement) {
    const input = document.getElementById(inputId);
    
    // SVG for Open Eye
    const eyeOpen = `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>`;
    
    // SVG for Closed Eye (Slash)
    const eyeClosed = `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>`;

    if (input.type === "password") {
        input.type = "text";
        btnElement.innerHTML = eyeClosed; 
    } else {
        input.type = "password";
        btnElement.innerHTML = eyeOpen; 
    }
}

// 2. Modal and Cookie Logic
document.addEventListener('DOMContentLoaded', () => {
    startCarousel();
    initializeTimers();
    const modal = document.getElementById('joinModal');
    const btn = document.getElementById('openModalBtn');
    const span = document.querySelector('.close-btn');
    const cookieBanner = document.getElementById('cookieConsent');
    const acceptCookiesBtn = document.getElementById('acceptCookies');

    if(btn) {
        btn.onclick = function() { modal.style.display = "flex"; }
    }
    if(span) {
        span.onclick = function() { modal.style.display = "none"; }
    }
    window.onclick = function(event) {
        if (event.target == modal) { modal.style.display = "none"; }
    }
    if (cookieBanner && acceptCookiesBtn) {
        if (!localStorage.getItem('cookiesAccepted')) {
            cookieBanner.style.display = 'flex';
        } else {
            cookieBanner.style.display = 'none';
        }
        acceptCookiesBtn.onclick = function() {
            localStorage.setItem('cookiesAccepted', 'true');
            cookieBanner.style.display = 'none';
        }
    }
});

// --- Modal Error Handling ---
    // This checks the URL for something like "?error=Invalid Password"
    const urlParams = new URLSearchParams(window.location.search);
    const errorMsg = urlParams.get('error');

    if (errorMsg) {
        // 1. Force the modal to open
        const modal = document.getElementById('joinModal');
        if (modal) modal.style.display = 'flex';

        // 2. Inject the error message into the red box
        const alertBox = document.getElementById('modalAlert');
        if (alertBox) {
            alertBox.style.display = 'block';
            alertBox.className = 'alert alert-error';
            alertBox.textContent = errorMsg; // Safely displays the PHP error
        }
        
        // 3. Clean up the URL so the error doesn't stay there forever if they refresh
        window.history.replaceState({}, document.title, window.location.pathname);
    }

// --- Carousel & Timer Logic ---
let slideIndex = 0;
let slideInterval; // Variable to hold the auto-play timer

function moveCarousel(n) {
    const track = document.querySelector('.carousel-track');
    const slides = document.querySelectorAll('.carousel-slide');
    if (!track || slides.length === 0) return;

    slideIndex += n;
    
    // Loop around
    if (slideIndex >= slides.length) slideIndex = 0;
    else if (slideIndex < 0) slideIndex = slides.length - 1;

    track.style.transform = `translateX(-${slideIndex * 100}%)`;
    
    // If the user clicks manually, reset the auto-play timer so it doesn't double-skip
    resetCarouselInterval();
}

// Function to start the automatic slideshow
function startCarousel() {
    slideInterval = setInterval(() => {
        moveCarousel(1);
    }, 5000); // Changes slide every 5 seconds
}

function resetCarouselInterval() {
    clearInterval(slideInterval);
    startCarousel();
}

// Function to calculate and update the countdown timers
function initializeTimers() {
    const timers = document.querySelectorAll('.countdown-timer');
    
    timers.forEach(timer => {
        // Get the target date from the HTML data attribute
        const targetDate = new Date(timer.getAttribute('data-date')).getTime();
        
        // Update the timer every 1 second
        setInterval(() => {
            const now = new Date().getTime();
            const distance = targetDate - now;
            
            // If the event has passed
            if (distance < 0) {
                timer.innerHTML = "<div class='time-box' style='background: #28a745;'><span>Event Started!</span></div>";
                return;
            }

            // Math to calculate days, hours, mins, secs
            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            // Output the result into the HTML
            timer.innerHTML = `
                <div class="time-box"><span>${days}</span><small>Days</small></div>
                <div class="time-box"><span>${hours}</span><small>Hours</small></div>
                <div class="time-box"><span>${minutes}</span><small>Mins</small></div>
                <div class="time-box"><span>${seconds}</span><small>Secs</small></div>
            `;
        }, 1000);
    });
}

// --- Web Service: Recipe Submission AJAX ---
    const recipeForm = document.getElementById('recipeForm');
    if (recipeForm) {
        recipeForm.addEventListener('submit', function(e) {
            e.preventDefault(); // Stop the page from reloading!

            const submitBtn = document.getElementById('submitRecipeBtn');
            const messageBox = document.getElementById('formMessage');
            
            submitBtn.textContent = 'Publishing...';
            submitBtn.disabled = true;

            // Gather all form data
            const formData = new FormData(recipeForm);

            // Send it to our PHP web service
            fetch('submit_recipe.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                messageBox.style.display = 'block';
                if (data.status === 'success') {
                    messageBox.className = 'alert alert-success';
                    messageBox.textContent = data.message;
                    recipeForm.reset(); // Clear the form
                    // Reload the page after 1.5 seconds to show the new recipe
                    setTimeout(() => { window.location.reload(); }, 1500); 
                } else {
                    messageBox.className = 'alert alert-error';
                    messageBox.textContent = data.message;
                    submitBtn.textContent = 'Publish Recipe';
                    submitBtn.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                messageBox.style.display = 'block';
                messageBox.className = 'alert alert-error';
                messageBox.textContent = 'A network error occurred. Please try again.';
                submitBtn.textContent = 'Publish Recipe';
                submitBtn.disabled = false;
            });
        });
    }

// --- Event Registration Logic ---
window.registerForEvent = function(eventName) {
    const formModal = document.getElementById('eventFormModal');
    const hiddenEventName = document.getElementById('hiddenEventName');
    const formEventNameDisplay = document.getElementById('formEventNameDisplay');
    
    if (formModal && hiddenEventName && formEventNameDisplay) {
        hiddenEventName.value = eventName;
        formEventNameDisplay.textContent = eventName;
        formModal.style.display = 'flex';
    }
};

const eventRegistrationForm = document.getElementById('eventRegistrationForm');
if (eventRegistrationForm) {
    eventRegistrationForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const submitBtn = document.getElementById('submitEventBtn');
        const originalText = submitBtn.textContent;
        submitBtn.textContent = 'Registering...';
        submitBtn.disabled = true;
        
        const formData = new FormData(eventRegistrationForm);
        const eventName = formData.get('event_name');

        fetch('register_event.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
            
            if (data.status === 'success') {
                document.getElementById('eventFormModal').style.display = 'none';
                eventRegistrationForm.reset();
                
                // Show success modal
                const modal = document.getElementById('eventModal');
                const modalMessage = document.getElementById('eventModalMessage');
                const modalTitle = document.getElementById('eventModalTitle');
                
                if (modal && modalMessage) {
                    modalTitle.textContent = "Registration Confirmed!";
                    modalMessage.innerHTML = `You have successfully reserved your spot for:<br><br><strong style="color: var(--primary-color); font-size: 1.1em;">${eventName}</strong><br><br>We'll email you the details shortly!`;
                    modal.style.display = 'flex';
                } else {
                    alert('Successfully registered!');
                }
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
            alert('A network error occurred.');
        });
    });
}