<?php
session_start();
// Fetch session data for contact form (if needed)
$contact_message = $_SESSION['message'] ?? '';
$contact_message_type = $_SESSION['message_type'] ?? '';
$contact_errors = $_SESSION['contact_errors'] ?? [];
$contact_data = $_SESSION['contact_data'] ?? [];
// Clear session data after reading
unset($_SESSION['message'], $_SESSION['message_type'], $_SESSION['contact_errors'], $_SESSION['contact_data']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Travelbuddy</title>
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="src/output.css"> <link rel="stylesheet" href="css/style.css">
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <script>
document.addEventListener('DOMContentLoaded', function() {
    const contactForm = document.getElementById('contactForm');
    
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Clear previous errors
            clearErrors();
            
            // Validate form
            const isValid = validateContactForm();
            
            if (isValid) {
                // If valid, submit the form
                this.submit();
            }
        });
    }
    
    function validateContactForm() {
        let isValid = true;
        const form = document.getElementById('contactForm');
        
        // Name validation
        const name = form.elements['name'].value.trim();
        if (!name) {
            showError('name', 'Name is required');
            isValid = false;
        } else if (name.length < 2) {
            showError('name', 'Name must be at least 2 characters');
            isValid = false;
        }
        
        // Email validation
        const email = form.elements['email'].value.trim();
        if (!email) {
            showError('email', 'Email is required');
            isValid = false;
        } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            showError('email', 'Please enter a valid email address');
            isValid = false;
        }
        
        // Subject validation (optional)
        const subject = form.elements['subject'].value.trim();
        if (subject.length > 100) {
            showError('subject', 'Subject must be less than 100 characters');
            isValid = false;
        }
        
        // Message validation
        const message = form.elements['message'].value.trim();
        if (!message) {
            showError('message', 'Message is required');
            isValid = false;
        } else if (message.length < 10) {
            showError('message', 'Message must be at least 10 characters');
            isValid = false;
        } else if (message.length > 1000) {
            showError('message', 'Message must be less than 1000 characters');
            isValid = false;
        }
        
        return isValid;
    }
    
    function showError(fieldName, message) {
        const field = document.querySelector(`[name="${fieldName}"]`);
        if (!field) return;
        
        // Add error class to input
        field.classList.add('border-red-500');
        field.classList.remove('border-gray-300');
        field.classList.remove('focus:border-[#FF5E00]');
        field.classList.remove('focus:ring-[#FF5E00]');
        
        // Create error message element
        const errorElement = document.createElement('p');
        errorElement.className = 'text-red-500 text-xs mt-1';
        errorElement.textContent = message;
        
        // Insert after the input field
        field.parentNode.insertBefore(errorElement, field.nextSibling);
    }
    
    function clearErrors() {
        // Remove all error messages
        const errorMessages = document.querySelectorAll('.text-red-500');
        errorMessages.forEach(el => el.remove());
        
        // Reset input styles
        const inputs = document.querySelectorAll('#contactForm input, #contactForm textarea');
        inputs.forEach(input => {
            input.classList.remove('border-red-500');
            input.classList.add('border-gray-300');
            input.classList.add('focus:border-[#FF5E00]');
            input.classList.add('focus:ring-[#FF5E00]');
        });
    }
});
</script>
 </head>
<body class="">
  <div id="preloader" class="preloader">
    <div class="preloader-mask preloader-mask-left"></div>
    <div class="preloader-mask preloader-mask-right"></div>
    <div class="preloader-logo">travel<span>buddy</span><div class="preloader-bar"></div></div>
  </div>

  <div id="mobileMenu" class="mobile-menu">
    <a href="#" class="mobile-menu-link">Home</a>
    <a href="#features" class="mobile-menu-link">Features</a>
    <a href="#about" class="mobile-menu-link">About</a>
    <a href="#contact" class="mobile-menu-link">Contact</a>
    <div class="mt-8">
      <a href="login.php" class="border-2 border-black px-6 py-2 rounded-md hover:bg-[#FF5E00] hover:text-white transition duration-300 inline-block">LOGIN</a>
      <a href="#" class="border-2 border-[#FF5E00] text-[#FF5E00] px-6 py-2 ml-3 rounded-md hover:bg-black hover:text-white transition duration-300 inline-block" id="mobileAlertsBtn">ALERTS</a>
    </div>
  </div>

  <div id="alertsPopup" class="popup-overlay">
    <div class="popup-content">
      <span class="popup-close">&times;</span> <h2 class="text-xl font-bold mb-4 text-center">Latest News & Alerts</h2>
      <div id="newsContainer" class="max-h-80 overflow-y-auto pr-2">
          <div class="loading-spinner p-4 text-center text-gray-500">
              Loading news...
          </div>
      </div>
    </div>
  </div>
  <div id="videoPopup" class="popup-overlay">
    <div class="popup-content">
      <span class="popup-close">&times;</span>
      <h2>About Travel Buddy</h2>
      <p>Watch our introduction video to learn more about how Travel Buddy can enhance your railway travel experience.</p>
      <div class="video-container">
        <video width="400" controls muted style="width: 100%;">
          <source src="assets/videos/WhatsApp Video 2025-04-18 at 8.51.25 PM.mp4" type="video/mp4">
          Your browser does not support the video tag.
        </video>
      </div>
    </div>
  </div>
  <div id="main-content" style="opacity: 0;">
    <div class=" bg-opacity-50 mi</div>n-h-screen bg-cover bg-center" style="background-image: url('assets/images/bg_doodle_black.png');">
      <header>
        <div class="w-full px-4 md:px-20 py-12 flex justify-between items-center">
          <div class="text-xl sm:text-3xl font-bold">travel<span class="text-[#FF5E00]">buddy</span></div>
          <div class="flex items-center space-x-4 md:space-x-8">
            <nav class="hidden md:flex space-x-6 md:space-x-10 text-[16px] font-semibold text-gray-700">
              <a href="#" class="hover:text-[#FF5E00] transition duration-300">Home</a>
              <a href="#features" class="hover:text-[#FF5E00] transition duration-300">Features</a>
              <a href="#about" class="hover:text-[#FF5E00] transition duration-300">About</a>
              <a href="#contact" class="hover:text-[#FF5E00] transition duration-300">Contact</a>
            </nav>
            <div class="flex items-center header-buttons">
              <a href="login.php" class="border-2 border-black px-4 md:px-7 py-1 md:py-2 rounded-md hover:bg-[#FF5E00] hover:text-white transition duration-300">LOGIN</a>
              <a href="#" class="border-2 border-[#FF5E00] text-[#FF5E00] px-4 md:px-7 py-1 md:py-2 ml-2 md:ml-3 rounded-md hover:bg-black hover:text-white transition duration-300" id="alertsBtn">ALERTS</a>
            </div>
            <div class="hamburger md:hidden"><span></span><span></span><span></span></div>
          </div>
        </div>
      </header>

      <div class="pb-10">
        <div class="flex flex-col md:flex-row items-center">
          <div class="hero-left md:w-1/2 w-full px-6 md:px-20 pt-10 md:pt-0">
            <h1 class="text-3xl md:text-4xl font-extrabold mb-4 md:pr-20">Your Smart Travel Companion for Every Journey!</h1>
            <p class="text-[14px] text-gray-600 mb-6">
              Travel Buddy makes railway travel seamless with real-time translations, instant station alerts,
              and personalized journey assistance. Whether you're exploring new places or commuting daily,
              Travel Buddy ensures you never miss an important update, announcement, or stop.
            </p>
            <button onclick="window.location.href='login.php'" class="bg-orange-500 text-white px-6 md:px-8 py-2 md:py-3 rounded-sm hover:bg-orange-600 hover:scale- transition duration-300">
              Get Started
            </button>
          </div>
          <div class="hero-right md:w-1/2 w-full floating px-6 md:px-0 mt-8 md:mt-0">
            <img src="assets/images/HERO.png" alt="Travel Buddy App" class="w-full h-full object-cover">
          </div>
        </div>
      </div>
    </div>

    <div id="about" class="w-full px-6 md:px-20 py-14 text-white " style="background-image: url('assets/images/fadeorangerm.png');">
      <div class="flex flex-col md:flex-row items-center justify-between">
        <div class="about-left md:w-1/2 w-full mt-10 md:mt-20">
          <h1 class="text-3xl md:text-4xl font-extrabold mb-4">What is Travel Buddy?</h1>
          <p class="text-[14px] px-1.5">
            Travel Buddy makes railway travel seamless with real-time translations, instant station alerts,
            and personalized journey assistance. Whether you're exploring new places or commuting daily,
            Travel Buddy ensures you never miss an important update or stop.
          </p>
          <button id="moreAboutBtn" class="border-2 border-white text-white mt-5 px-6 md:px-8 py-2 md:py-3 rounded-sm hover:bg-orange-600 transition duration-300">
            know more
          </button>
        </div>
        <div class="about-right md:w-1/2 w-full flex justify-center md:justify-end mt-10 md:mt-0">
          <img src="assets/images/About us page-amico 1.png" alt="About Travel Buddy" class="max-w-full h-auto">
        </div>
      </div>
    </div>

    <hr class="border-t-3 border-dashed border-gray-400 my-4">

    <div id="why-travelbuddy" class="px-6 md:px-20 py-14">
      <h2 class="text-center text-3xl md:text-4xl font-extrabold mb-10">Why We Need Travel Buddy</h2>
      <div class="max-w-6xl mx-auto text-gray-700 space-y-6">
        <p class="text-base text-center md:text-lg leading-relaxed">
          While you could use a generic translation app on your phone, or hope for multilingual staff, these often fall short in the demanding environment of a real station. Generic apps struggle with noise and specific terms, while staff aren't always available or fluent in your language. Basic station systems often lack real-time updates and broad language support.
        </p>
        <p class="text-base text-center md:text-lg leading-relaxed">
          <strong>Travel Buddy</strong> is different, designed specifically for this challenge, offering tangible benefits:
        </p>

        <div class="space-y-4">
          <h3 class="text-xl font-semibold">Getting the Right Information Instantly:</h3>
          <blockquote>
            <strong>Example:</strong> Imagine you hear a quick announcement: "Train 12345 to Delhi is delayed... now leaving from Platform 8." In a rush or with a basic app, you might mishear the platform number or not catch the delay info correctly.
          </blockquote>
          <p class="text-base leading-relaxed">
            <strong>Travel Buddy's Advantage:</strong> It catches the specifics like "delayed" and the correct platform "8", giving you the simple, right information immediately so you know exactly what's changed and where to go, without any confusing guesses.
          </p>
        </div>

        <div class="space-y-4">
          <h3 class="text-xl font-semibold">Speaking Your Language, Wherever You Are:</h3>
          <blockquote>
            <strong>Example:</strong> You've traveled from another region to Lucknow Station. Announcements might be in Hindi, but you need Tamil or Bengali. Finding reliable, real-time translation via standard apps or station resources can be difficult.
          </blockquote>
          <p class="text-base leading-relaxed">
            <strong>Travel Buddy's Advantage:</strong> We offer robust translation for multiple languages, crucially including [e.g., diverse regional Indian languages like Tamil, Bengali, Marathi, etc., alongside major international ones], ensuring far wider accessibility than typical platforms.
          </p>
        </div>

        <div class="space-y-4">
          <h3 class="text-xl font-semibold">All Translation Tools, One Place:</h3>
          <blockquote>
            <strong>Example:</strong> Needing to understand spoken announcements, read signs, and have translations read aloud often means juggling three different apps – a hassle when traveling.
          </blockquote>
          <p class="text-base leading-relaxed">
            <strong>Travel Buddy's Advantage:</strong> We combine Speech-to-Text, Image Translation, and Text-to-Speech on one platform. Instantly switch between listening, reading signs, and hearing text read out – all the tools you need, conveniently integrated.
          </p>
        </div>
      </div>
    </div>

             <div class="text-center pt-6"> <button id="whyWatchVideoBtn" class="bg-orange-500 text-white px-6 md:px-8 py-2 md:py-3 rounded-sm hover:bg-orange-600 transition duration-300 inline-flex items-center shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" />
                    </svg>
                    Watch How It Works
                </button>
            </div>

        </div> </div>
        <style>
  /* Styles specifically for the 'Why Travelbuddy' section */
  #why-travelbuddy {
    /* Add a subtle background or keep it plain */
    /* background-color: #f9fafb; */ /* Example: very light gray */
    padding-top: 3.5rem; /* py-14 */
    padding-bottom: 3.5rem; /* py-14 */
    padding-left: 1.5rem; /* px-6 */
    padding-right: 1.5rem; /* px-6 */
  }

  @media (min-width: 768px) {
    #why-travelbuddy {
      padding-left: 5rem; /* md:px-20 */
      padding-right: 5rem; /* md:px-20 */
    }
  }

  #why-travelbuddy h2 {
    text-align: center;
    font-size: 1.875rem; /* text-3xl */
    line-height: 2.25rem;
    font-weight: 800; /* font-extrabold */
    margin-bottom: 2.5rem; /* mb-10 */
    color: #1f2937; /* text-gray-800 */
  }

  #why-travelbuddy .max-w-6xl {
    max-width: 72rem; /* max-w-6xl */
    margin-left: auto;
    margin-right: auto;
    color: #374151; /* text-gray-700 */
  }

  /* Style each benefit block */
  #why-travelbuddy .space-y-4 {
    margin-bottom: 2rem; /* Add space between benefit blocks */
    padding: 1.5rem; /* Add padding inside each block */
    background-color: #ffffff; /* White background */
    border-radius: 0.5rem; /* rounded-lg */
    border: 1px solid #e5e7eb; /* border-gray-200 */
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06); /* shadow-sm */
  }

   /* Style the heading within each benefit block */
  #why-travelbuddy .space-y-4 h3 {
    font-size: 1.25rem; /* text-xl */
    line-height: 1.75rem;
    font-weight: 600; /* font-semibold */
    color: #FF5E00; /* Travelbuddy orange */
    margin-bottom: 0.75rem; /* mb-3 */
  }

  /* Style the blockquote (Example section) */
  #why-travelbuddy blockquote {
    border-left: 4px solid #fdba74; /* border-orange-300 */
    padding-left: 1rem; /* pl-4 */
    padding-top: 0.5rem; /* py-2 */
    padding-bottom: 0.5rem; /* py-2 */
    margin-top: 1rem; /* my-4 */
    margin-bottom: 1rem; /* my-4 */
    background-color: #fff7ed; /* bg-orange-50 */
    color: #4b5563; /* text-gray-600 */
    font-style: italic;
    font-size: 0.95em; /* Slightly smaller */
  }

  #why-travelbuddy blockquote strong {
    font-style: normal; /* Keep 'Example:' normal */
     color: #B45309; /* Darker orange for emphasis */
  }

  /* Style the paragraphs */
  #why-travelbuddy p.leading-relaxed {
    line-height: 1.625; /* leading-relaxed */
    font-size: 1rem; /* text-base */
    color: #4b5563; /* text-gray-600 */
  }

   #why-travelbuddy p strong {
       font-weight: 600; /* font-semibold */
       color: #1f2937; /* text-gray-800 */
   }

  /* Style the 'Watch How It Works' button container */
  #why-travelbuddy .text-center.pt-6 {
    text-align: center;
    padding-top: 1.5rem; /* pt-6 */
    margin-top: 1rem; /* Add some space above the button */
  }

</style>
<div id="simpleVideoPopup" class="popup-overlay">
    <div class="popup-content p-0 overflow-hidden" style="max-width: 800px; background: black;"> <span class="popup-close text-white bg-black/50 rounded-full leading-none p-1 w-6 h-6 flex items-center justify-center" style="top: 10px; right: 10px; font-size: 18px;">&times;</span>
      <div class="video-container aspect-video"> <video controls muted style="width: 100%; height: 100%; display: block;">
          <source src="assets/videos/need.mp4" type="video/mp4">
          Your browser does not support the video tag.
        </video>
      </div>
    </div>
  </div>

    <hr class="border-t-3 border-dashed border-gray-400 my-4">

    <div id="features" class="bg-[url('/assets/images/bg_doodle_black.png')] bg-opacity-50 bg-cover bg-center pb-10">
      <h1 class="feature-title text-center text-3xl font-extrabold mt-20 px-4">Things we can do for you..</h1>
      <div x-data="featureSlider()" x-init="init()" class="relative w-full max-w-5xl mx-auto px-4 md:px-20 lg:px-32 py-12 overflow-hidden feature-slider-container">
        <div class="flex items-center justify-center relative h-full overflow-hidden">
           <template x-if="features.length > 1">
            <div @click="prev()" class="absolute left-0 transform -translate-x-1/2 transition-all duration-500 ease-in-out opacity-30 hover:opacity-60 cursor-pointer slide-side-card hidden md:flex items-center justify-center w-48 h-64 bg-white shadow-xl rounded-xl p-4 transition-transform duration-300 hover:scale-105"
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-10" x-transition:enter-end="opacity-30 translate-x-0"
                 x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-30 translate-x-0" x-transition:leave-end="opacity-0 -translate-x-10">
              <div>
                <img :src="prevFeature().image" alt="" class="w-20 h-20 mb-2 mx-auto">
                <p class="text-sm text-center font-medium" x-text="prevFeature().title"></p>
              </div>
            </div>
          </template>
          <template x-if="features.length > 0">
            <div class="z-10 border-2 border-dashed border-[#FF5E00] bg-white shadow-2xl rounded-xl p-6 flex flex-col items-center justify-center text-center feature-card mx-auto w-84 h-100"
                 x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 transform scale-90" x-transition:enter-end="opacity-100 transform scale-100"
                 x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-90">
              <img :src="currentFeature().image" alt="" class="w-35 h-35 mb-4 transition-transform duration-500 hover:scale-110">
              <h3 class="text-xl font-bold mb-2" x-text="currentFeature().title"></h3>
              <p class="text-sm text-gray-600" x-text="currentFeature().description"></p>
            </div>
          </template>
           <template x-if="features.length > 1">
            <div @click="next()" class="absolute right-0 transform translate-x-1/2 transition-all duration-500 ease-in-out opacity-30 hover:opacity-60 cursor-pointer slide-side-card hidden md:flex items-center justify-center w-48 h-64 bg-white shadow-2xl rounded-xl p-4 transition-transform duration-300 hover:scale-105"
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-x-10" x-transition:enter-end="opacity-30 translate-x-0"
                 x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-30 translate-x-0" x-transition:leave-end="opacity-0 translate-x-10">
              <div>
                <img :src="nextFeature().image" alt="" class="w-20 h-20 mb-2 mx-auto">
                <p class="text-sm text-center font-medium" x-text="nextFeature().title"></p>
              </div>
            </div>
          </template>
        </div>
        <div class="absolute top-1/2 left-4 md:left-8 transform -translate-y-1/2 z-20" x-show="features.length > 1">
          <button @click="prev()" class="bg-[#FF5E00] text-white hover:bg-orange-600 p-2 rounded-full transition-all duration-300 hover:scale-110 active:scale-95 shadow-lg flex items-center justify-center w-10 h-10">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
          </button>
        </div>
        <div class="absolute top-1/2 right-4 md:right-8 transform -translate-y-1/2 z-20" x-show="features.length > 1">
          <button @click="next()" class="bg-[#FF5E00] text-white hover:bg-orange-600 p-2 rounded-full transition-all duration-300 hover:scale-110 active:scale-95 shadow-lg flex items-center justify-center w-10 h-10">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
          </button>
        </div>
        <div class="flex justify-center mt-6 md:hidden" x-show="features.length > 1">
          <template x-for="(feature, index) in features" :key="index">
            <button @click="currentIndex = index; resetInterval();"
                   class="w-2.5 h-2.5 mx-1 rounded-full focus:outline-none transition-all duration-300"
                   :class="{'bg-[#FF5E00] scale-125': currentIndex === index, 'bg-gray-300 hover:bg-gray-400': currentIndex !== index}">
              <span class="sr-only">Go to slide {index + 1}</span>
            </button>
          </template>
        </div>
      </div>
      </div>

    <hr class="border-t-3 border-dashed border-gray-400 my-4">

    <div id="contact">
      <div class="bg-[#F6F6F6] px-6 md:px-20 py-8">
        <div class="flex flex-col md:flex-row items-center justify-between">
          <div class="contact-left md:w-1/2 w-full">
            <h1 class="text-3xl md:text-4xl font-extrabold mb-6 md:mb-10">Contact Us</h1>
            <?php if ($contact_message): ?>
              <div class="form-message <?php echo $contact_message_type === 'success' ? 'success' : 'error'; ?> mb-4 p-4 rounded border <?php echo $contact_message_type === 'success' ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700'; ?>" role="alert">
                <p class="font-bold"><?php echo $contact_message_type === 'success' ? 'Success!' : 'Error!'; ?></p>
                <p><?php echo htmlspecialchars($contact_message); ?></p>
                <?php if (!empty($contact_errors)): ?>
                  <ul class="list-disc list-inside mt-2">
                    <?php foreach ($contact_errors as $error): ?>
                      <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                  </ul>
                <?php endif; ?>
              </div>
            <?php endif; ?>
            <form action="contact_handler.php" method="POST" class="pr-0 md:pr-10 lg:pr-20" id="contactForm">
              <div class="mb-4 relative">
                <input type="text" name="name" placeholder="Name" required
                       class="border bg-white border-gray-300 rounded-md px-4 py-3 w-full transition duration-300 focus:outline-none focus:border-[#FF5E00] focus:ring-1 focus:ring-[#FF5E00]"
                       value="<?php echo htmlspecialchars($contact_data['name'] ?? ''); ?>">
              </div>
              <div class="mb-4 relative">
                 <input type="email" name="email" placeholder="Email" required
                       class="border bg-white border-gray-300 rounded-md px-4 py-3 w-full transition duration-300 focus:outline-none focus:border-[#FF5E00] focus:ring-1 focus:ring-[#FF5E00]"
                       value="<?php echo htmlspecialchars($contact_data['email'] ?? ''); ?>">
              </div>
              <div class="mb-4 relative">
                <input type="text" name="subject" placeholder="Subject"
                       class="border bg-white border-gray-300 rounded-md px-4 py-3 w-full transition duration-300 focus:outline-none focus:border-[#FF5E00] focus:ring-1 focus:ring-[#FF5E00]"
                       value="<?php echo htmlspecialchars($contact_data['subject'] ?? ''); ?>">
              </div>
              <div class="mb-4 relative">
                <textarea name="message" required
                          class="w-full bg-white border h-40 px-4 py-3 border-gray-300 rounded-md transition duration-300 focus:outline-none focus:border-[#FF5E00] focus:ring-1 focus:ring-[#FF5E00]"
                          placeholder="Type your message here.."><?php echo htmlspecialchars($contact_data['message'] ?? ''); ?></textarea>
              </div>
              <button type="submit" class="bg-orange-500 text-white px-8 md:px-12 py-2 text-md rounded-md hover:bg-orange-600 transition duration-300 disabled:opacity-50" id="submitBtn">
                SEND
              </button>
            </form>
          </div>
          <div class="contact-right md:w-1/2 w-full flex justify-center md:justify-end mt-12 md:mt-0">
            <img src="assets/images/mail.png" alt="Contact Travel Buddy" class="max-w-full h-auto">
          </div>
        </div>
      </div>
    </div>

    <div class="relative h-[120px] w-full overflow-hidden">
      <div class="wave wave1 absolute bottom-0 left-0 w-full h-[100px] z-[1000] opacity-100" style="background: url('./assets/images/orangedark.png') repeat-x; background-size: 1000px 100px;"></div>
      <div class="wave wave2 absolute bottom-[10px] left-0 w-full h-[100px] z-[999] opacity-50" style="background: url('./assets/images/orangedark.png') repeat-x; background-size: 1000px 100px;"></div>
      <div class="wave wave3 absolute bottom-[15px] left-0 w-full h-[100px] z-[1000] opacity-20" style="background: url('./assets/images/orangedark.png') repeat-x; background-size: 1000px 100px;"></div>
      <div class="wave wave4 absolute bottom-[20px] left-0 w-full h-[100px] z-[999] opacity-70" style="background: url('./assets/images/orangedark.png') repeat-x; background-size: 1000px 100px;"></div>
    </div>

    <footer class="relative bg-[#FF5E00] text-white overflow-hidden">
      <div class="container mx-auto px-6 pt-24 pb-12 relative z-20">
        <div class="flex flex-col md:flex-row justify-between items-start">
          <div class="mb-8 md:mb-0">
            <div class="text-3xl font-bold mb-4">travel<span class="text-white">buddy</span></div>
            <p class="text-white/80 text-sm max-w-xs">
              Your smart travel companion for every journey. Making railway travel seamless with real-time assistance.
            </p>
          </div>
          <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8 footer-grid">
            <div>
              <h3 class="font-bold text-lg mb-4">Quick Links</h3>
              <ul class="space-y-2">
                <li><a href="#" class="text-white/80 hover:text-white transition">Home</a></li>
                <li><a href="#features" class="text-white/80 hover:text-white transition">Features</a></li>
                <li><a href="#about" class="text-white/80 hover:text-white transition">About Us</a></li>
                <li><a href="#contact" class="text-white/80 hover:text-white transition">Contact</a></li>
              </ul>
            </div>
            <div>
              <h3 class="font-bold text-lg mb-4">Support</h3>
                <ul class="space-y-2"></ul>
                <li><a href="support.html" class="text-white/80 hover:text-white transition">FAQ</a></li>
                <li><a href="support.html" class="text-white/80 hover:text-white transition">Privacy Policy</a></li>
                <li><a href="support.html" class="text-white/80 hover:text-white transition">Terms of Service</a></li>
                </ul>
            </div>
            <div>
              <h3 class="font-bold text-lg mb-4">Connect</h3>
              <div class="flex space-x-4">
                <a href="#" class="text-white/80 hover:text-white transition transform hover:scale-125">
                  <ion-icon name="logo-facebook" class="text-2xl"></ion-icon>
                </a>
                <a href="#" class="text-white/80 hover:text-white transition transform hover:scale-125">
                  <ion-icon name="logo-twitter" class="text-2xl"></ion-icon>
                </a>
                <a href="#" class="text-white/80 hover:text-white transition transform hover:scale-125">
                  <ion-icon name="logo-instagram" class="text-2xl"></ion-icon>
                </a>
                <a href="#" class="text-white/80 hover:text-white transition transform hover:scale-125">
                  <ion-icon name="logo-linkedin" class="text-2xl"></ion-icon>
                </a>
              </div>
            </div>
          </div>
        </div>
        <div class="border-t border-white/20 mt-12 pt-6 text-center md:text-left">
          <p class="text-white/60 text-sm">
            &copy; <span id="copyright-year">2023</span> Travelbuddy. All rights reserved.
          </p>
        </div>
      </div>
    </footer>
  </div><script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  <script src="https://unpkg.com/scrollreveal"></script>
  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
  <script src="js/script.js"></script>

</body>
</html>