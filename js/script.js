// Wait for the DOM to be fully loaded before running script logic
document.addEventListener('DOMContentLoaded', function() {

    // --- Get Element References ---
    // Get references to buttons and popups needed for interactions
    const alertsBtn = document.getElementById('alertsBtn'); // Button to open news alerts popup (desktop)
    const mobileAlertsBtn = document.getElementById('mobileAlertsBtn'); // Button to open news alerts popup (mobile)
    const moreAboutBtn = document.getElementById('moreAboutBtn'); // Button in 'About' section to open the original video popup
    const whyWatchVideoBtn = document.getElementById('whyWatchVideoBtn'); // Button in 'Why' section to open the NEW video-only popup
    const videoPopup = document.getElementById('videoPopup'); // The original video popup element (with text)
    const simpleVideoPopup = document.getElementById('simpleVideoPopup'); // The NEW video-only popup element
    const alertsPopup = document.getElementById('alertsPopup'); // The news alerts popup element
    const newsContainer = document.getElementById('newsContainer'); // Container inside alerts popup to load news into

    // Get references to other page elements
    const contactForm = document.getElementById('contactForm'); // The contact form
    const submitBtn = document.getElementById('submitBtn'); // Submit button for the contact form
    const messageDiv = document.querySelector('.form-message'); // Area to display contact form success/error messages
    const copyrightYear = document.getElementById('copyright-year'); // Span to display the current year in the footer
    const hamburger = document.querySelector('.hamburger'); // Hamburger menu button for mobile
    const mobileMenu = document.getElementById('mobileMenu'); // The mobile menu container
    const preloader = document.getElementById('preloader'); // The preloader overlay
    const mainContent = document.getElementById('main-content'); // The main content wrapper (for preloader fade-in)

    // Get all generic popup close buttons (usually an 'X')
    const closePopupButtons = document.querySelectorAll('.popup-close');


    // --- Function to Fetch and Display News ---
    // Fetches news content from fetch_news.php and displays it in the alerts popup
    function loadAndShowNews() {
        // Safety check: ensure the target popup and container exist
        if (!alertsPopup || !newsContainer) {
            console.error("Alerts popup or news container not found.");
            return; // Exit if elements are missing
        }

        // 1. Show loading state inside the news container
        newsContainer.innerHTML = '<div class="loading-spinner p-4 text-center text-gray-500">Loading news...</div>';

        // 2. Open the popup immediately to show the loading state
        openPopup(alertsPopup);

        // 3. Fetch news content asynchronously from the PHP script
        // *** Ensure 'fetch_news.php' path is correct relative to index.php ***
        fetch('fetch_news.php')
            .then(response => {
                // Check if the HTTP response status is OK (e.g., 200)
                if (!response.ok) {
                    // If not OK (e.g., 404, 500), throw an error to be caught below
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                // If OK, parse the response body as text (expecting HTML from PHP)
                return response.text();
            })
            .then(html => {
                // 4. Inject the fetched HTML content into the news container
                newsContainer.innerHTML = html;
            })
            .catch(error => {
                // 5. Handle any errors during the fetch process (network error, HTTP error, parsing error)
                console.error('Error fetching news:', error);
                // Display a user-friendly error message inside the container
                newsContainer.innerHTML = '<p class="text-red-500 text-center px-4 py-2">Sorry, could not load news at this time. Please try again later.</p>';
            });
    }

    // --- Event Listeners ---

    // ALERTS button handlers (Desktop & Mobile) - Fetch and show news
    if (alertsBtn) {
        alertsBtn.addEventListener('click', (e) => {
            e.preventDefault(); // Prevent default anchor tag behavior
            loadAndShowNews(); // Call the function to load and display news
        });
    }
    if (mobileAlertsBtn) {
        mobileAlertsBtn.addEventListener('click', (e) => {
            e.preventDefault(); // Prevent default anchor tag behavior
            // Close the mobile menu first if it's open
            if (mobileMenu?.classList.contains('active')) {
                mobileMenu.classList.remove('active');
                hamburger?.classList.remove('active');
                document.body.style.overflow = ''; // Restore body scroll
            }
            loadAndShowNews(); // Call the function to load and display news
        });
    }

    // Video Popup listeners
    // Button in 'About' section opens the original video popup (with text)
    if (moreAboutBtn && videoPopup) {
        moreAboutBtn.addEventListener('click', (e) => {
            e.preventDefault(); // Prevent default anchor tag behavior
            openPopup(videoPopup); // Open the original video popup
        });
    }
    // Button in 'Why' section opens the NEW video-only popup
    if (whyWatchVideoBtn && simpleVideoPopup) { // Check for the NEW popup element
        whyWatchVideoBtn.addEventListener('click', (e) => {
            e.preventDefault(); // Prevent default button behavior
            openPopup(simpleVideoPopup); // Open the NEW video-only popup
        });
    }

    // Generic Close Button Listener - Works for any popup with a .popup-close element
    closePopupButtons.forEach(button => {
        button.addEventListener('click', () => {
            // Find the closest parent element with the .popup-overlay class
            const popupToClose = button.closest('.popup-overlay');
            // If found, close that specific popup
            if (popupToClose) {
                closePopup(popupToClose);
            }
        });
    });

    // Listener for closing popups by clicking the dark overlay background
    document.querySelectorAll('.popup-overlay').forEach(overlay => {
        overlay.addEventListener('click', (e) => {
            // Check if the click target is the overlay itself (and not content within it)
            if (e.target === overlay) {
                closePopup(overlay); // Close the clicked overlay
            }
        });
    });


    // Close any active popup when the Escape key is pressed
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            // Find the currently active popup (if any)
            const activePopup = document.querySelector('.popup-overlay.active');
            // If an active popup exists, close it
            if (activePopup) {
                closePopup(activePopup);
            }
        }
    });

    // Mobile menu toggle logic
    if (hamburger && mobileMenu) {
        hamburger.addEventListener('click', function() {
            // Toggle the 'active' class on the hamburger and the menu
            const isActive = this.classList.toggle('active');
            mobileMenu.classList.toggle('active');
            // Prevent body scrolling when the mobile menu is open
            document.body.style.overflow = isActive ? 'hidden' : '';
        });
    }

    // Contact form submission visual feedback (shows loading spinner)
    if (contactForm && submitBtn) {
        contactForm.addEventListener('submit', function() {
            // Check if the submit button exists before manipulating it
            if(submitBtn) {
                 // Replace button text with a loading indicator
                 submitBtn.innerHTML = '<svg class="animate-spin h-5 w-5 mr-3 inline-block text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Sending...';
                 // Disable the button to prevent multiple submissions
                 submitBtn.disabled = true;
            }
            // The form will submit normally via PHP after this point
        });
    }

    // Auto-hide contact form success/error messages after a delay
    if (messageDiv) {
        setTimeout(() => {
            // Start fade out animation
            messageDiv.style.transition = 'opacity 0.5s ease';
            messageDiv.style.opacity = '0';
            // Remove the element from the DOM after the fade out completes
            setTimeout(() => messageDiv.remove(), 500); // 500ms matches transition duration
        }, 7000); // 7 seconds delay
    }

    // Update the copyright year in the footer dynamically
    if (copyrightYear) {
        copyrightYear.textContent = new Date().getFullYear();
    }

    // --- Preloader Logic ---
    // Get references to preloader elements
    const preloaderBar = document.querySelector('.preloader-bar');
    const maskLeft = document.querySelector('.preloader-mask-left');
    const maskRight = document.querySelector('.preloader-mask-right');

    // Check if all necessary preloader elements exist
    if (preloader && mainContent && preloaderBar && maskLeft && maskRight) {
        // Start the loading bar animation almost immediately
        setTimeout(() => {
            if(preloaderBar) preloaderBar.style.width = '100%';
        }, 50); // Small delay helps ensure rendering

        // Wait for the entire window (including images, scripts, etc.) to load
        window.addEventListener('load', function() {
             // Make the main content visible only after everything is loaded
            if(mainContent) mainContent.style.opacity = '1';
            // Start the preloader mask animation after a short delay
            setTimeout(() => {
                if(maskLeft) maskLeft.style.transform = 'translateX(-100%)';
                if(maskRight) maskRight.style.transform = 'translateX(100%)';
                // Hide the preloader element completely after the masks have animated out
                setTimeout(() => {
                    if(preloader) preloader.style.display = 'none';
                }, 1200); // This duration should match the CSS transition duration for the masks
            }, 600); // Adjust this delay if content takes longer to render visually
        });
    } else {
        // Fallback: If preloader elements are missing, just show the main content immediately
        if(mainContent) mainContent.style.opacity = '1';
        if(preloader) preloader.style.display = 'none';
        console.warn("Preloader elements not found. Skipping animations.");
    }


}); // End DOMContentLoaded

// --- Popup Handling Functions --- (Defined outside DOMContentLoaded for global scope)

/**
 * Opens a popup element.
 * Adds the 'active' class and prevents body scrolling.
 * Handles specific logic for any popup containing a video tag.
 * @param {HTMLElement} popupElement - The popup element to open.
 */
function openPopup(popupElement) {
    // Check if the element exists and is not already active
    if (popupElement && !popupElement.classList.contains('active')) {
        popupElement.classList.add('active'); // Make popup visible
        document.body.style.overflow = 'hidden'; // Prevent background scrolling

        // --- Generic Video Handling ---
        // Check if the popup being opened contains a video element
        const video = popupElement.querySelector('video');
        if (video) {
             video.currentTime = 0; // Reset video to the beginning
             // Attempt to play the video (might be blocked by browser policies if not muted)
             video.play().catch(error => console.error("Video autoplay failed:", error));
        }
        // --- End Generic Video Handling ---

        // Add specific logic for alertsPopup if needed on open
        // if (popupElement.id === 'alertsPopup') { /* ... */ }
    }
}

/**
 * Closes a popup element.
 * Removes the 'active' class and restores body scrolling.
 * Handles specific logic for any popup containing a video tag.
 * @param {HTMLElement} popupElement - The popup element to close.
 */
function closePopup(popupElement) {
    // Check if the element exists and is currently active
    if (popupElement && popupElement.classList.contains('active')) {
        popupElement.classList.remove('active'); // Hide popup
        document.body.style.overflow = ''; // Restore background scrolling

        // --- Generic Video Handling ---
        // Check if the popup being closed contains a video element
        const video = popupElement.querySelector('video');
        if (video) {
            video.pause(); // Pause the video
            // Optional: Reset video to start when closing
            video.currentTime = 0;
        }
        // --- End Generic Video Handling ---

         // Add specific logic for alertsPopup if needed on close
         // if (popupElement.id === 'alertsPopup') { /* ... */ }
    }
}


// --- Alpine.js Feature Slider Function --- (Defined outside DOMContentLoaded)
// Ensure Alpine.js library is loaded in index.php for this to work
function featureSlider() {
    return {
        currentIndex: 0, // Index of the currently displayed feature
        features: [ // Array of feature objects
             // *** Ensure these image paths are correct relative to index.php ***
            { title: "Real-time Translations", description: "Understand announcements in your language instantly.", image: "assets/svg/voice.svg" },
            { title: "Instant Alerts", description: "Get notified of upcoming stations, delays, or platform changes.", image: "assets/svg/notif.svg" },
            { title: "Personalized Assistance", description: "Recommendations and assistance through virtual AI chat bot", image: "assets/svg/chatbot.svg" }
        ],
        intervalId: null, // To store the interval timer for auto-sliding
        // Go to the next feature
        next() {
            if (this.features.length > 0) {
                this.currentIndex = (this.currentIndex + 1) % this.features.length; // Loop back to start
                this.resetInterval(); // Reset timer on manual navigation
            }
        },
        // Go to the previous feature
        prev() {
            if (this.features.length > 0) {
                this.currentIndex = (this.currentIndex - 1 + this.features.length) % this.features.length; // Loop back to end
                this.resetInterval(); // Reset timer on manual navigation
            }
        },
        // Get the data for the current feature
        currentFeature() {
            return this.features.length > 0 ? this.features[this.currentIndex] : { title: 'Loading...', description: '', image: '' }; // Default if empty
        },
        // Get data for the next feature (for side card preview)
        nextFeature() {
            return this.features.length > 0 ? this.features[(this.currentIndex + 1) % this.features.length] : {};
        },
        // Get data for the previous feature (for side card preview)
        prevFeature() {
            return this.features.length > 0 ? this.features[(this.currentIndex - 1 + this.features.length) % this.features.length] : {};
        },
        // Start the automatic sliding interval
        startAutoSlide() {
            // Only start if more than one feature and interval isn't already running
            if (this.features.length > 1 && !this.intervalId) {
                this.intervalId = setInterval(() => { this.next(); }, 10000); // Slide every 10 seconds
            }
        },
        // Stop the automatic sliding interval
        stopAutoSlide() {
            if (this.intervalId) {
                clearInterval(this.intervalId);
                this.intervalId = null;
            }
        },
        // Reset the interval (stop existing, start new) - useful after manual interaction
        resetInterval() {
            this.stopAutoSlide();
            this.startAutoSlide();
        },
        // Initialize the slider when the Alpine component is ready
        init() {
            // Use $nextTick to ensure the element ($el) is available
            this.$nextTick(() => {
                 this.startAutoSlide(); // Start auto-sliding
                 // Add hover listeners to pause/resume auto-slide (optional)
                 if (this.$el) { // Check if the component's root element exists
                    this.$el.addEventListener('mouseenter', () => this.stopAutoSlide());
                    this.$el.addEventListener('mouseleave', () => this.startAutoSlide());
                 } else {
                    console.warn("Alpine slider element ($el) not found during init.");
                 }
            });
        }
    }
}

// --- ScrollReveal Initialization (Optional) ---
// Checks if the ScrollReveal library is loaded before trying to use it
if (typeof ScrollReveal === 'function') {
    // Initialize ScrollReveal with default settings
    const sr = ScrollReveal({
        distance: '60px', // Distance elements move when revealed
        duration: 1500,   // Animation duration in milliseconds
        delay: 400,       // Delay before animation starts in milliseconds
        reset: false      // Animation only happens once (false) or repeats on scroll up (true)
    });

    // Define reveal animations for different sections/elements
    // Example: Animate hero section elements from left/right
    sr.reveal('.hero-left h1, .hero-left p, .hero-left button', { origin: 'left', interval: 100 }); // Staggered reveal
    sr.reveal('.hero-right img', { origin: 'right', delay: 600 });

    // Example: Animate about section elements from right/left
    sr.reveal('.about-left h1, .about-left p, .about-left button', { origin: 'right', interval: 100 });
    sr.reveal('.about-right img', { origin: 'left', delay: 600 });

    // Example: Animate feature section title and slider container
    sr.reveal('.feature-title', { origin: 'top' });
    sr.reveal('.feature-slider-container', { origin: 'bottom', delay: 500 });

    // Example: Animate contact section form elements and image
    sr.reveal('.contact-left h1, .contact-left form > div, .contact-left button', { origin: 'bottom', interval: 100 });
    sr.reveal('.contact-right img', { origin: 'top', delay: 600 });

    // Add more sr.reveal() calls for other elements you want to animate

} else {
    // Log a warning if ScrollReveal library is not found
    console.warn("ScrollReveal is not loaded.");
}
