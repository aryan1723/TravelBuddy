<?php
    session_start();
    // Clear any previous messages
    $signup_success = $_SESSION['signup_success'] ?? '';
    $signup_errors = $_SESSION['signup_errors'] ?? [];
    $signup_data = $_SESSION['signup_data'] ?? [];
    $login_error = $_SESSION['login_error'] ?? '';
    
    // Clear session variables after using them
    unset($_SESSION['signup_success'], $_SESSION['signup_errors'], $_SESSION['signup_data'], $_SESSION['login_error']);
    ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login/Signup - Travelbuddy</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    // Form validation functions
    function validateSignupForm(form) {
        let isValid = true;
        const errors = {};
        
        // Name validation
        const name = form.elements['name'].value.trim();
        if (!name) {
            errors.name = 'Name is required';
            isValid = false;
        }
        
        // Email validation
        const email = form.elements['email'].value.trim();
        if (!email) {
            errors.email = 'Email is required';
            isValid = false;
        } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            errors.email = 'Please enter a valid email address';
            isValid = false;
        }
        
        // Password validation
        const password = form.elements['password'].value;
        if (!password) {
            errors.password = 'Password is required';
            isValid = false;
        } else if (password.length < 8) {
            errors.password = 'Password must be at least 8 characters';
            isValid = false;
        }
        
        // Confirm password validation
        const confirmPassword = form.elements['confirmPassword'].value;
        if (!confirmPassword) {
            errors.confirmPassword = 'Please confirm your password';
            isValid = false;
        } else if (password !== confirmPassword) {
            errors.confirmPassword = 'Passwords do not match';
            isValid = false;
        }
        
        return { isValid, errors };
    }
    
    function validateLoginForm(form) {
        let isValid = true;
        const errors = {};
        
        // Email validation
        const email = form.elements['email'].value.trim();
        if (!email) {
            errors.email = 'Email is required';
            isValid = false;
        } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            errors.email = 'Please enter a valid email address';
            isValid = false;
        }
        
        // Password validation
        const password = form.elements['password'].value;
        if (!password) {
            errors.password = 'Password is required';
            isValid = false;
        }
        
        return { isValid, errors };
    }
    
    // Display error messages
    function displayErrors(form, errors) {
        // Clear previous errors
        const errorElements = form.querySelectorAll('.error-message');
        errorElements.forEach(el => el.remove());
        
        // Clear previous error classes
        const inputs = form.querySelectorAll('input');
        inputs.forEach(input => {
            input.classList.remove('border-red-500');
            const parentDiv = input.parentElement;
            if (parentDiv) {
                parentDiv.classList.remove('border-red-500');
            }
        });
        
        // Add new errors
        for (const field in errors) {
            const input = form.querySelector(`[name="${field}"]`);
            if (input) {
                input.classList.add('border-red-500');
                const parentDiv = input.parentElement;
                if (parentDiv) {
                    parentDiv.classList.add('border-red-500');
                }
                
                const errorElement = document.createElement('p');
                errorElement.className = 'error-message text-red-500 text-xs mt-1';
                errorElement.textContent = errors[field];
                
                // Insert after the input's parent div
                const inputContainer = input.closest('div');
                if (inputContainer) {
                    inputContainer.parentNode.insertBefore(errorElement, inputContainer.nextSibling);
                }
            }
        }
    }
    
    // Form submission handlers
    document.addEventListener('DOMContentLoaded', function() {
        const signupForm = document.getElementById('signupForm');
        if (signupForm) {
            signupForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const { isValid, errors } = validateSignupForm(this);
                
                if (isValid) {
                    this.submit();
                } else {
                    displayErrors(this, errors);
                }
            });
        }
        
        const loginForm = document.getElementById('loginForm');
        if (loginForm) {
            loginForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const { isValid, errors } = validateLoginForm(this);
                
                if (isValid) {
                    this.submit();
                } else {
                    displayErrors(this, errors);
                }
            });
        }
    });
</script>
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }
        /* Responsive styles for the auth container */
        @media (max-width: 768px) {
            .auth-container { flex-direction: column; height: auto !important; }
            .image-container, .form-container { position: relative !important; width: 100% !important; left: 0 !important; }
            .image-container { height: 300px !important; }
        }
        /* Styles for larger screens */
        @media (min-width: 769px) {
            .auth-container { height: 650px; overflow: hidden; position: relative; }
            .image-container { position: absolute; top: 0; width: 55%; height: 100%; transition: all 0.5s ease; left: 0; }
            .form-container { position: absolute; top: 0; left: 55%; width: 45%; height: 100%; transition: all 0.5s ease; }
            .image-container.is-login { left: 45%; }
            .form-container.is-login { left: 0; }
        }
        .form-image { width: 100%; height: 100%; object-fit: cover; }
        /* Style for feedback messages */
        .form-feedback { min-height: 1.5rem; /* Reserve space */ font-size: 0.875rem; font-weight: 500; margin-top: 0.5rem; }
        .form-feedback.success { color: #10B981; /* Tailwind green-600 */ }
        .form-feedback.error { color: #EF4444; /* Tailwind red-500 */ }
    </style>
</head>

<body class="bg-gradient-to-r from-[#FE6371] to-[#F1811F]" x-data="{ isLogin: false, mobileMenu: false }">
    
    <div class="min-h-screen bg-[url('assets/images/bg_doodle_white2.png')]">
        <header class="p-4 md:px-20 md:py-15">
            <div class="px-4 md:px-12 py-3 md:py-5 bg-white rounded-full flex justify-between items-center">
                <div class="text-xl md:text-2xl font-bold">
                    travel<span class="text-[#FF5E00]">buddy</span>
                </div>

                <button class="md:hidden" @click="mobileMenu = !mobileMenu">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-16 6h16"></path></svg>
                </button>

                <div class="hidden md:flex items-center space-x-8">
                    <nav class="flex space-x-10 text-[14px] font-semibold text-gray-700">
                        <a href="index.php" class="hover:text-[#FF5E00] transition duration-300">Home</a>
                        <a href="index.php#features" class="hover:text-[#FF5E00] transition duration-300">Features</a>
                        <a href="index.php#about" class="hover:text-[#FF5E00] transition duration-300">About</a>
                        <a href="index.php#contact" class="hover:text-[#FF5E00] transition duration-300">Contact</a>
                    </nav>
                    <button @click="isLogin = !isLogin"
                       class="border-2 rounded-full text-[12px] px-8 py-3 text-white bg-[#FE6371] hover:bg-[#ff4957] transition duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#FE6371]">
                        <span x-text="isLogin ? 'NEED TO SIGNUP?' : 'HAVE AN ACCOUNT?'"></span> </button>
                </div>
            </div>

             <div x-show="mobileMenu" @click.outside="mobileMenu = false"
                  class="md:hidden mt-2 bg-white rounded-lg p-4 shadow-lg"
                  x-transition:enter="transition ease-out duration-200"
                  x-transition:enter-start="opacity-0 scale-95"
                  x-transition:enter-end="opacity-100 scale-100"
                  x-transition:leave="transition ease-in duration-150"
                  x-transition:leave-start="opacity-100 scale-100"
                  x-transition:leave-end="opacity-0 scale-95">
                <nav class="flex flex-col space-y-4 text-[14px] font-semibold text-gray-700">
                    <a href="index.php" @click="mobileMenu = false" class="hover:text-[#FF5E00] transition duration-300">Home</a>
                    <a href="index.php#features" @click="mobileMenu = false" class="hover:text-[#FF5E00] transition duration-300">Features</a>
                    <a href="index.php#about" @click="mobileMenu = false" class="hover:text-[#FF5E00] transition duration-300">About</a>
                    <a href="index.php#contact" @click="mobileMenu = false" class="hover:text-[#FF5E00] transition duration-300">Contact</a>
                </nav>
                <button @click="isLogin = !isLogin; mobileMenu = false"
                   class="block w-full mt-4 border-2 rounded-full text-[12px] px-8 py-3 text-white bg-[#FE6371] hover:bg-[#ff4957] transition duration-300 text-center focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-[#FE6371]">
                    <span x-text="isLogin ? 'NEED TO SIGNUP?' : 'HAVE AN ACCOUNT?'"></span>
                </button>
             </div>
        </header>

        <div class="px-4 md:px-20 lg:px-40 pb-10">
            <div class="rounded-tl-[40px] md:rounded-tl-[80px] rounded-br-[40px] md:rounded-br-[80px] shadow-2xl auth-container flex">
                <div class="image-container bg-[#FFA5A5] p-10" :class="{'is-login': isLogin}">
                    <div class="rounded-2xl overflow-hidden h-full">
                        <img x-show="!isLogin" src="./assets/images/signup.png" alt="Person signing up illustration" class="form-image">
                         <img x-show="isLogin" src="./assets/images/login.png" alt="Person logging in illustration" class="form-image">
                    </div>
                </div>

                <div class="form-container p-6 md:p-12 bg-white flex flex-col justify-center" :class="{'is-login': isLogin}">
                    <div class="max-w-md mx-auto w-full">

                        <div x-show="!isLogin" x-transition.opacity.duration.300ms>
                            <h1 class="text-2xl md:text-3xl font-extrabold text-[#263238] mb-2">Signup</h1>
                            <h3 class="text-base md:text-[18px] font-semibold text-gray-500 mb-8">Start your journey</h3>

                            <?php if ($signup_success): ?>
                                <div class="form-feedback success mb-4">
                                    <?php echo htmlspecialchars($signup_success); ?>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($signup_errors)): ?>
                                <div class="form-feedback error mb-4">
                                    <ul class="list-disc pl-5">
                                        <?php foreach ($signup_errors as $error): ?>
                                            <li><?php echo htmlspecialchars($error); ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>

                            <form id="signupForm" action="signup_handler.php" method="POST" class="space-y-6">
                                <input type="hidden" name="formType" value="signup">
                                <div>
                                    <label for="signup-name" class="sr-only">Name</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none"><img src="assets/icons/User.png" alt="" class="w-5 h-5 text-gray-400"></div>
                                        <input id="signup-name" type="text" name="name" placeholder="Name" required 
                                               class="w-full pl-10 pr-4 py-3 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#FF5E00] focus:border-transparent"
                                               value="<?php echo htmlspecialchars($signup_data['name'] ?? ''); ?>">
                                    </div>
                                </div>
                                <div>
                                     <label for="signup-email" class="sr-only">Email</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none"><img src="assets/icons/Mail outline.png" alt="" class="w-5 h-5 text-gray-400"></div>
                                        <input id="signup-email" type="email" name="email" placeholder="Email" required 
                                               class="w-full pl-10 pr-4 py-3 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#FF5E00] focus:border-transparent"
                                               value="<?php echo htmlspecialchars($signup_data['email'] ?? ''); ?>">
                                    </div>
                                </div>
                                <div>
                                     <label for="signup-password" class="sr-only">Password</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none"><img src="assets/icons/Unlock.png" alt="" class="w-5 h-5 text-gray-400"></div>
                                        <input id="signup-password" type="password" name="password" placeholder="Password" required minlength="8" 
                                               class="w-full pl-10 pr-4 py-3 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#FF5E00] focus:border-transparent">
                                    </div>
                                </div>
                                <div>
                                     <label for="signup-confirm-password" class="sr-only">Confirm Password</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none"><img src="assets/icons/Unlock.png" alt="" class="w-5 h-5 text-gray-400"></div>
                                        <input id="signup-confirm-password" type="password" name="confirmPassword" placeholder="Confirm Password" required 
                                               class="w-full pl-10 pr-4 py-3 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#FF5E00] focus:border-transparent">
                                    </div>
                                </div>

                                <button type="submit" class="w-full px-6 py-3 rounded-md bg-[#FF5E00] text-white font-semibold hover:bg-[#E05500] transition-colors shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#FF5E00] disabled:opacity-75">SIGNUP</button>
                            </form>

                            <p class="text-center text-gray-600 mt-6">Already have an account? <button @click="isLogin = true" class="text-[#FF5E00] font-semibold hover:underline focus:outline-none">Login</button></p>
                        </div>

                        <div x-show="isLogin" x-transition.opacity.duration.300ms>
                            <h1 class="text-2xl md:text-3xl font-extrabold text-[#263238] mb-2">Login</h1>
                            <h3 class="text-base md:text-[18px] font-semibold text-gray-500 mb-8">Welcome back</h3>

                            <?php if ($login_error): ?>
                                <div class="form-feedback error mb-4">
                                    <?php echo htmlspecialchars($login_error); ?>
                                </div>
                            <?php endif; ?>

                            <form id="loginForm" action="login_handler.php" method="POST" class="space-y-6">
                                 <input type="hidden" name="formType" value="login">
                                 <div>
                                     <label for="login-email" class="sr-only">Email</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none"><img src="assets/icons/Mail outline.png" alt="" class="w-5 h-5 text-gray-400"></div>
                                        <input id="login-email" type="email" name="email" placeholder="Email" required 
                                               class="w-full pl-10 pr-4 py-3 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#FF5E00] focus:border-transparent">
                                    </div>
                                </div>
                                <div>
                                     <label for="login-password" class="sr-only">Password</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none"><img src="assets/icons/Unlock.png" alt="" class="w-5 h-5 text-gray-400"></div>
                                        <input id="login-password" type="password" name="password" placeholder="Password" required 
                                               class="w-full pl-10 pr-4 py-3 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#FF5E00] focus:border-transparent">
                                    </div>
                                </div>

                                <button type="submit" class="w-full px-6 py-3 rounded-md bg-[#FF5E00] text-white font-semibold hover:bg-[#E05500] transition-colors shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#FF5E00] disabled:opacity-75">LOGIN</button>
                            </form>

                            <p class="text-center text-gray-600 mt-6">Don't have an account? <button @click="isLogin = false" class="text-[#FF5E00] font-semibold hover:underline focus:outline-none">Signup</button></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Remove feedback messages after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                const feedbackElements = document.querySelectorAll('.form-feedback');
                feedbackElements.forEach(el => {
                    el.style.transition = 'opacity 0.5s ease';
                    el.style.opacity = '0';
                    setTimeout(() => el.remove(), 500);
                });
            }, 5000);
        });
    </script>
</body>
</html>