// Time-based greeting
function updateGreeting() {
    const hour = new Date().getHours();
    let greeting;
    
    if (hour < 12) {
        greeting = "Good morning";
    } else if (hour < 18) {
        greeting = "Good afternoon";
    } else {
        greeting = "Good evening";
    }
    
    document.getElementById('greeting').textContent = `${greeting}, Traveler!`;
}

// Logout function
function logout() {
    window.location.href = 'index.html';
}

// Side menu functionality
const menuBtn = document.getElementById('menuBtn');
const closeMenuBtn = document.getElementById('closeMenuBtn');
const sideMenu = document.getElementById('sideMenu');

menuBtn.addEventListener('click', () => {
    sideMenu.classList.remove('right-[-300px]');
    sideMenu.classList.add('right-0');
});

closeMenuBtn.addEventListener('click', () => {
    sideMenu.classList.remove('right-0');
    sideMenu.classList.add('right-[-300px]');
});

// Modal functionality
function openModal(feature) {
    document.getElementById(`${feature}Modal`).classList.remove('hidden');
    document.getElementById(`${feature}Modal`).classList.add('flex');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
    document.getElementById(modalId).classList.remove('flex');
}

// Close modals when clicking outside
document.querySelectorAll('[id$="Modal"]').forEach(modal => {
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            closeModal(modal.id);
        }
    });
});

// Image translation API configuration
const IMAGE_TRANSLATOR_API = {
    url: 'https://torii-image-translator.p.rapidapi.com/upload',
    headers: {
        'x-rapidapi-host': 'torii-image-translator.p.rapidapi.com',
        'x-rapidapi-key': '3d86c2d6aemshdc9a7b41679cbaep166b94jsnf3b7c3893b4f',
        'font': 'noto',
        'stroke_disabled': 'true'
    }
};

// Image upload functionality
const imageUploadArea = document.getElementById('imageUploadArea');
const imageInput = document.getElementById('imageInput');
const processImageBtn = document.getElementById('processImageBtn');

imageUploadArea.addEventListener('click', () => {
    imageInput.click();
});

imageInput.addEventListener('change', (e) => {
    if (e.target.files.length > 0) {
        const file = e.target.files[0];
        if (file.type.match('image.*')) {
            // Show file name and preview
            const previewUrl = URL.createObjectURL(file);
            imageUploadArea.innerHTML = `
                <i class="fas fa-check-circle text-green-500 text-4xl mb-2"></i>
                <p class="text-gray-700">${file.name}</p>
                <img src="${previewUrl}" class="max-w-full mt-2 rounded" alt="Preview">
            `;
            processImageBtn.disabled = false;
            
            // Show original image preview
            document.getElementById('originalImagePreview').src = previewUrl;
            document.getElementById('translatedImageContainer').classList.remove('hidden');
            
            // Clear previous results
            document.getElementById('imageTranslationResult').value = '';
        }
    }
});

// Drag and drop for image upload
imageUploadArea.addEventListener('dragover', (e) => {
    e.preventDefault();
    imageUploadArea.classList.add('border-orange-500', 'bg-orange-50');
});

imageUploadArea.addEventListener('dragleave', () => {
    imageUploadArea.classList.remove('border-orange-500', 'bg-orange-50');
});

imageUploadArea.addEventListener('drop', (e) => {
    e.preventDefault();
    imageUploadArea.classList.remove('border-orange-500', 'bg-orange-50');
    
    if (e.dataTransfer.files.length > 0) {
        const file = e.dataTransfer.files[0];
        if (file.type.match('image.*')) {
            imageInput.files = e.dataTransfer.files;
            const previewUrl = URL.createObjectURL(file);
            imageUploadArea.innerHTML = `
                <i class="fas fa-check-circle text-green-500 text-4xl mb-2"></i>
                <p class="text-gray-700">${file.name}</p>
                <img src="${previewUrl}" class="max-w-full mt-2 rounded" alt="Preview">
            `;
            processImageBtn.disabled = false;
            
            // Show original image preview
            document.getElementById('originalImagePreview').src = previewUrl;
            document.getElementById('translatedImageContainer').classList.remove('hidden');
            
            // Clear previous results
            document.getElementById('imageTranslationResult').value = '';
        }
    }
});

// Process image translation
async function processImage() {
    const file = document.getElementById('imageInput').files[0];
    const targetLang = document.getElementById('targetLangImage').value;
    const output = document.getElementById('imageTranslationResult');
    
    if (!file) {
        alert('Please upload an image first');
        return;
    }
    
    output.value = "Processing image translation...";
    processImageBtn.disabled = true;
    processImageBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Translating...';
    
    try {
        const formData = new FormData();
        formData.append('file', file);
        
        const response = await fetch(IMAGE_TRANSLATOR_API.url, {
            method: 'POST',
            headers: {
                ...IMAGE_TRANSLATOR_API.headers,
                'target_lang': targetLang
            },
            body: formData
        });
        
        if (!response.ok) {
            throw new Error(`API error: ${response.status}`);
        }
        
        const result = await response.json();
        
        if (result.translated_text) {
            output.value = result.translated_text;
        } else if (result.error) {
            output.value = `Error: ${result.error}`;
        } else {
            output.value = "Translation completed but no text was returned.";
        }
    } catch (error) {
        console.error('Image translation error:', error);
        output.value = `Failed to translate image: ${error.message}`;
    } finally {
        processImageBtn.disabled = false;
        processImageBtn.innerHTML = '<i class="fas fa-language mr-2"></i> Translate Text';
    }
}

// Initialize
updateGreeting();