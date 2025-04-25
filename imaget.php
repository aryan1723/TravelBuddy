<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image to Text Translation | TravelBuddy</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/tesseract.js@4/dist/tesseract.min.js"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f7fafc;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        header {
            background: linear-gradient(to right, #f97316, #fb923c);
            color: white;
            padding: 15px 0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
        }
        .back-btn {
            background: none;
            border: none;
            color: white;
            font-size: 16px;
            cursor: pointer;
            display: flex;
            align-items: center;
        }
        .back-btn i {
            margin-right: 5px;
        }
        .main-content {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-bottom: 30px;
        }
        h1 {
            color: #f97316;
            margin-top: 0;
        }
        .upload-area {
            border: 2px dashed #d1d5db;
            border-radius: 8px;
            padding: 30px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            margin-bottom: 20px;
        }
        .upload-area:hover {
            border-color: #f97316;
            background-color: #fff7ed;
        }
        .upload-icon {
            font-size: 48px;
            color: #f97316;
            margin-bottom: 15px;
        }
        .preview-container {
            margin-top: 20px;
            text-align: center;
        }
        .image-preview {
            max-width: 100%;
            max-height: 300px;
            border-radius: 8px;
            display: none;
        }
        .language-select {
            width: 100%;
            padding: 10px;
            border: 1px solid #d1d5db;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .btn {
            background: linear-gradient(to right, #f97316, #fb923c);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
            margin-right: 10px;
            margin-bottom: 10px;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .btn:disabled {
            background: #d1d5db;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }
        .result-area {
            margin-top: 30px;
        }
        .result-text {
            width: 100%;
            min-height: 150px;
            padding: 15px;
            border: 1px solid #d1d5db;
            border-radius: 5px;
            resize: vertical;
        }
        .progress-container {
            margin-top: 20px;
            display: none;
        }
        .progress-bar {
            height: 10px;
            background-color: #e5e7eb;
            border-radius: 5px;
            overflow: hidden;
        }
        .progress {
            height: 100%;
            background-color: #f97316;
            width: 0%;
            transition: width 0.3s;
        }
        .status {
            margin-top: 10px;
            font-size: 14px;
            color: #6b7280;
        }
        footer {
            background-color: #ff5e00;
            color: white;
            padding: 30px 0;
            text-align: center;
        }
        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        .copyright {
            margin-top: 20px;
            font-size: 14px;
            color: rgba(255, 255, 255, 0.7);
        }
        .language-selectors {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
        }
        .language-selector {
            flex: 1;
        }
        .api-selector {
            margin-bottom: 20px;
        }
        .api-selector label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
        }
        .error-message {
            color: #e53e3e;
            margin-top: 10px;
            font-size: 14px;
        }
        .btn-group {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 20px;
        }
        .api-key-input {
            width: 100%;
            padding: 10px;
            border: 1px solid #d1d5db;
            border-radius: 5px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <header>
        <div class="header-content">
            <div class="logo">travelbuddy</div>
            <button class="back-btn" onclick="window.history.back()">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </button>
        </div>
    </header>

    <div class="container">
        <div class="main-content">
            <h1><i class="fas fa-image"></i> Image to Text Translation</h1>
            <p>Upload an image containing text to extract and translate it</p>
            
            <div id="uploadArea" class="upload-area">
                <div class="upload-icon">
                    <i class="fas fa-cloud-upload-alt"></i>
                </div>
                <h3>Click to upload image or drag and drop</h3>
                <p>Supported formats: JPG, PNG, BMP</p>
                <input type="file" id="imageInput" accept="image/*" style="display: none;">
            </div>
            
            <div class="preview-container">
                <img id="imagePreview" class="image-preview" alt="Image preview">
            </div>
            
            <div class="language-selectors">
                <div class="language-selector">
                    <label for="sourceLanguage">Source Language (in image):</label>
                    <select id="sourceLanguage" class="language-select">
                        <option value="eng">English</option>
                        <option value="hin">Hindi</option>
                        <option value="ben">Bengali</option>
                        <option value="tam">Tamil</option>
                        <option value="tel">Telugu</option>
                        <option value="mar">Marathi</option>
                        <option value="guj">Gujarati</option>
                        <option value="kan">Kannada</option>
                        <option value="mal">Malayalam</option>
                        <option value="pan">Punjabi</option>
                    </select>
                </div>
                
                <div class="language-selector">
                    <label for="targetLanguage">Target Language:</label>
                    <select id="targetLanguage" class="language-select">
                        <option value="en">English</option>
                        <option value="hi" selected>Hindi</option>
                        <option value="bn">Bengali</option>
                        <option value="ta">Tamil</option>
                        <option value="te">Telugu</option>
                        <option value="mr">Marathi</option>
                        <option value="gu">Gujarati</option>
                        <option value="kn">Kannada</option>
                        <option value="ml">Malayalam</option>
                        <option value="pa">Punjabi</option>
                    </select>
                </div>
            </div>
            
            <div class="api-selector" hidden>
                <label for="translationApi">Translation Service:</label>
                <select id="translationApi" class="language-select">
                    <option value="gemini">Gemini AI (Requires API Key)</option>
                </select>
                <input type="text" id="geminiApiKey" class="api-key-input" placeholder="Enter Gemini API Key (if using Gemini)" style="display: none;">
            </div>
            
            <div class="btn-group">
                <button id="extractBtn" class="btn" disabled>
                    <i class="fas fa-language"></i> Extract Text
                </button>
                <button id="translateBtn" class="btn" disabled>
                    <i class="fas fa-exchange-alt"></i> Translate
                </button>
                <button id="copyBtn" class="btn" disabled>
                    <i class="fas fa-copy"></i> Copy Text
                </button>
                <button id="clearBtn" class="btn">
                    <i class="fas fa-trash"></i> Clear
                </button>
            </div>
            
            <div class="progress-container" id="progressContainer">
                <div class="progress-bar">
                    <div class="progress" id="progressBar"></div>
                </div>
                <div class="status" id="statusText">Initializing...</div>
                <div id="errorMessage" class="error-message"></div>
            </div>
            
            <div class="result-area">
                <label for="resultText">Extracted Text:</label>
                <textarea id="resultText" class="result-text" ></textarea>
            </div>
            
            <div class="result-area" id="translationResultArea" style="display: none;">
                <label for="translatedText">Translated Text:</label>
                <textarea id="translatedText" class="result-text" readonly></textarea>
            </div>
        </div>
    </div>

    <footer>
        <div class="footer-content">
            <div class="logo" style="font-size: 28px; margin-bottom: 15px;">travel<span style="color: white;">buddy</span></div>
            <p>Your smart travel companion for every journey</p>
            <div class="copyright">
                &copy; 2025 TravelBuddy. All rights reserved.
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const uploadArea = document.getElementById('uploadArea');
            const imageInput = document.getElementById('imageInput');
            const imagePreview = document.getElementById('imagePreview');
            const extractBtn = document.getElementById('extractBtn');
            const translateBtn = document.getElementById('translateBtn');
            const copyBtn = document.getElementById('copyBtn');
            const clearBtn = document.getElementById('clearBtn');
            const resultText = document.getElementById('resultText');
            const translatedText = document.getElementById('translatedText');
            const sourceLanguage = document.getElementById('sourceLanguage');
            const targetLanguage = document.getElementById('targetLanguage');
            const translationApi = document.getElementById('translationApi');
            const geminiApiKey = 'AIzaSyBk5G9lWQVncYumqRgxDjWAFRxz8fBAXQ0';
            const progressContainer = document.getElementById('progressContainer');
            const progressBar = document.getElementById('progressBar');
            const statusText = document.getElementById('statusText');
            const errorMessage = document.getElementById('errorMessage');
            const translationResultArea = document.getElementById('translationResultArea');
            
            let extractedText = '';
            
            // Show/hide Gemini API key field based on selection
            translationApi.addEventListener('change', function() {
                geminiApiKey.style.display = this.value === 'gemini' ? 'block' : 'none';
            });
            
            // Handle file upload
            uploadArea.addEventListener('click', () => {
                imageInput.click();
            });
            
            imageInput.addEventListener('change', (e) => {
                if (e.target.files.length > 0) {
                    const file = e.target.files[0];
                    if (file.type.match('image.*')) {
                        // Display preview
                        const reader = new FileReader();
                        reader.onload = (event) => {
                            imagePreview.src = event.target.result;
                            imagePreview.style.display = 'block';
                        };
                        reader.readAsDataURL(file);
                        
                        // Enable extract button
                        extractBtn.disabled = false;
                        
                        // Reset other buttons and result
                        translateBtn.disabled = true;
                        copyBtn.disabled = true;
                        resultText.value = '';
                        translatedText.value = '';
                        extractedText = '';
                        translationResultArea.style.display = 'none';
                        errorMessage.textContent = '';
                    }
                }
            });
            
            // Drag and drop functionality
            uploadArea.addEventListener('dragover', (e) => {
                e.preventDefault();
                uploadArea.style.borderColor = '#f97316';
                uploadArea.style.backgroundColor = '#fff7ed';
            });
            
            uploadArea.addEventListener('dragleave', () => {
                uploadArea.style.borderColor = '#d1d5db';
                uploadArea.style.backgroundColor = 'white';
            });
            
            uploadArea.addEventListener('drop', (e) => {
                e.preventDefault();
                uploadArea.style.borderColor = '#d1d5db';
                uploadArea.style.backgroundColor = 'white';
                
                if (e.dataTransfer.files.length > 0) {
                    const file = e.dataTransfer.files[0];
                    if (file.type.match('image.*')) {
                        imageInput.files = e.dataTransfer.files;
                        
                        // Display preview
                        const reader = new FileReader();
                        reader.onload = (event) => {
                            imagePreview.src = event.target.result;
                            imagePreview.style.display = 'block';
                        };
                        reader.readAsDataURL(file);
                        
                        // Enable extract button
                        extractBtn.disabled = false;
                        
                        // Reset other buttons and result
                        translateBtn.disabled = true;
                        copyBtn.disabled = true;
                        resultText.value = '';
                        translatedText.value = '';
                        extractedText = '';
                        translationResultArea.style.display = 'none';
                        errorMessage.textContent = '';
                    }
                }
            });
            
            // Extract text from image
            extractBtn.addEventListener('click', () => {
                if (!imageInput.files.length) return;
                
                const file = imageInput.files[0];
                const lang = sourceLanguage.value === 'auto' ? targetLanguage.value : sourceLanguage.value;
                
                // Show progress
                progressContainer.style.display = 'block';
                progressBar.style.width = '0%';
                statusText.textContent = 'Processing image...';
                errorMessage.textContent = '';
                
                // Disable buttons during processing
                extractBtn.disabled = true;
                translateBtn.disabled = true;
                copyBtn.disabled = true;
                
                // Use Tesseract.js for OCR
                Tesseract.recognize(
                    file,
                    lang,
                    {
                        logger: m => {
                            if (m.status === 'recognizing text') {
                                progressBar.style.width = `${m.progress * 100}%`;
                                statusText.textContent = `Processing: ${Math.round(m.progress * 100)}%`;
                            } else {
                                statusText.textContent = m.status;
                            }
                        }
                    }
                ).then(({ data: { text } }) => {
                    extractedText = text.trim();
                    resultText.value = extractedText;
                    
                    // Enable buttons
                    extractBtn.disabled = false;
                    translateBtn.disabled = false;
                    copyBtn.disabled = extractedText.length === 0;
                    
                    // Hide progress
                    progressContainer.style.display = 'none';
                    statusText.textContent = 'Text extracted successfully!';
                    
                    // Hide translation result if exists
                    translationResultArea.style.display = 'none';
                }).catch(err => {
                    console.error(err);
                    resultText.value = 'Error extracting text from image. Please try again.';
                    errorMessage.textContent = err.message || 'OCR processing failed';
                    
                    // Enable extract button
                    extractBtn.disabled = false;
                    
                    // Hide progress
                    progressContainer.style.display = 'none';
                    statusText.textContent = 'Error occurred during processing';
                });
            });
            
            // Translation API endpoints
            const API_ENDPOINTS = {
                libretranslate: {
                    detect: 'https://libretranslate.de/detect',
                    translate: 'https://libretranslate.de/translate'
                },
                mymemory: {
                    translate: 'https://api.mymemory.translated.net/get'
                }
            };
            
            // Language code mapping
            const LANGUAGE_MAP = {
                eng: { code: 'en', mymemory: 'en' },
                hin: { code: 'hi', mymemory: 'hi' },
                ben: { code: 'bn', mymemory: 'bn' },
                tam: { code: 'ta', mymemory: 'ta' },
                tel: { code: 'te', mymemory: 'te' },
                mar: { code: 'mr', mymemory: 'mr' },
                guj: { code: 'gu', mymemory: 'gu' },
                kan: { code: 'kn', mymemory: 'kn' },
                mal: { code: 'ml', mymemory: 'ml' },
                pan: { code: 'pa', mymemory: 'pa' },
                auto: { code: 'auto', mymemory: 'auto' }
            };
            
            // Improved translate function with multiple API fallbacks
            async function translateText(text, sourceLang, targetLang) {
                const api = translationApi.value;
                let translatedText = '';
                let error = '';
                
                try {
                    switch(api) {
                        case 'libretranslate':
                            translatedText = await translateWithLibreTranslate(text, sourceLang, targetLang);
                            break;
                        case 'mymemory':
                            translatedText = await translateWithMyMemory(text, sourceLang, targetLang);
                            break;
                        case 'gemini':
                            translatedText = await translateWithGemini(text, sourceLang, targetLang);
                            break;
                        default:
                            throw new Error('Invalid translation API selected');
                    }
                } catch (e) {
                    console.error(`Translation with ${api} failed:`, e);
                    error = e.message;
                    
                    // Fallback to next available API
                    if (api !== 'mymemory') {
                        statusText.textContent = 'First attempt failed, trying fallback...';
                        try {
                            translatedText = await translateWithMyMemory(text, sourceLang, targetLang);
                            error = '';
                        } catch (e2) {
                            console.error('Fallback translation failed:', e2);
                            error = `All translation attempts failed. Last error: ${e2.message}`;
                        }
                    }
                }
                
                return { translatedText, error };
            }
            
            // LibreTranslate implementation
            async function translateWithLibreTranslate(text, sourceLang, targetLang) {
                // First detect language if auto
                if (sourceLang === 'auto') {
                    const detectResponse = await fetch(API_ENDPOINTS.libretranslate.detect, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ q: text })
                    });
                    
                    const detectData = await detectResponse.json();
                    sourceLang = detectData[0]?.language || 'en';
                }
                
                const translateResponse = await fetch(API_ENDPOINTS.libretranslate.translate, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        q: text,
                        source: sourceLang,
                        target: targetLang
                    })
                });
                
                if (!translateResponse.ok) {
                    throw new Error('LibreTranslate API request failed');
                }
                
                const translateData = await translateResponse.json();
                if (!translateData.translatedText) {
                    throw new Error('No translation returned from LibreTranslate');
                }
                return translateData.translatedText;
            }
            
            // MyMemory implementation
            async function translateWithMyMemory(text, sourceLang, targetLang) {
                const sourceCode = LANGUAGE_MAP[sourceLang]?.mymemory || sourceLang;
                const targetCode = LANGUAGE_MAP[targetLang]?.mymemory || targetLang;
                
                if (sourceCode === 'auto') {
                    // MyMemory doesn't support auto-detection, assume English
                    sourceLang = 'en';
                }
                
                const response = await fetch(
                    `${API_ENDPOINTS.mymemory.translate}?q=${encodeURIComponent(text)}&langpair=${sourceCode}|${targetCode}`
                );
                
                if (!response.ok) {
                    throw new Error('MyMemory API request failed');
                }
                
                const data = await response.json();
                if (!data.responseData || !data.responseData.translatedText) {
                    throw new Error(data.responseStatus || 'No translation returned from MyMemory');
                }
                return data.responseData.translatedText;
            }
            
            // Gemini AI implementation
            async function translateWithGemini(text, sourceLang, targetLang) {
                const apiKey = geminiApiKey.value.trim();
                if (!apiKey) {
                    throw new Error('Please enter your Gemini API key');
                }
                
                // First detect language if auto
                if (sourceLang === 'auto') {
                    // For Gemini, we'll just pass the auto-detection to the model
                    sourceLang = '';
                }
                
                // Get language names for the prompt
                const targetLangName = targetLanguage.options[targetLanguage.selectedIndex].text;
                
                const prompt = `Translate the following text to ${targetLangName}:\n\n${text}`;
                
                const response = await fetch(`https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key=${apiKey}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        contents: [{
                            parts: [{
                                text: prompt
                            }]
                        }]
                    })
                });
                
                if (!response.ok) {
                    const errorData = await response.json();
                    throw new Error(errorData.error?.message || 'Gemini API request failed');
                }
                
                const data = await response.json();
                if (!data.candidates || !data.candidates[0]?.content?.parts || !data.candidates[0].content.parts[0]?.text) {
                    throw new Error('No translation returned from Gemini');
                }
                
                return data.candidates[0].content.parts[0].text;
            }
            
            // Translate button click handler
            translateBtn.addEventListener('click', async () => {
                if (!extractedText) return;
                
                progressContainer.style.display = 'block';
                progressBar.style.width = '0%';
                statusText.textContent = 'Translating text...';
                errorMessage.textContent = '';
                
                extractBtn.disabled = true;
                translateBtn.disabled = true;
                copyBtn.disabled = true;
                
                try {
                    const sourceLang = sourceLanguage.value;
                    const targetLang = targetLanguage.value;
                    
                    progressBar.style.width = '30%';
                    const { translatedText: result, error } = await translateText(extractedText, sourceLang, targetLang);
                    
                    if (error) {
                        translatedText.value = `Error: ${error}`;
                        statusText.textContent = 'Translation failed';
                        errorMessage.textContent = error;
                    } else {
                        translatedText.value = result;
                        statusText.textContent = 'Translation complete!';
                        progressBar.style.width = '100%';
                    }
                    
                    translationResultArea.style.display = 'block';
                } catch (error) {
                    console.error('Translation error:', error);
                    translatedText.value = `Error: ${error.message}`;
                    statusText.textContent = 'Translation failed';
                    errorMessage.textContent = error.message;
                    translationResultArea.style.display = 'block';
                } finally {
                    extractBtn.disabled = false;
                    translateBtn.disabled = false;
                    copyBtn.disabled = false;
                    
                    setTimeout(() => {
                        progressContainer.style.display = 'none';
                    }, 1000);
                }
            });
            
            // Copy text to clipboard
            copyBtn.addEventListener('click', () => {
                const textToCopy = translatedText.value || resultText.value;
                if (!textToCopy) return;
                
                navigator.clipboard.writeText(textToCopy).then(() => {
                    // Show feedback
                    const originalText = copyBtn.innerHTML;
                    copyBtn.innerHTML = '<i class="fas fa-check"></i> Copied!';
                    setTimeout(() => {
                        copyBtn.innerHTML = originalText;
                    }, 2000);
                });
            });
            
            // Clear all inputs and results
            clearBtn.addEventListener('click', () => {
                imageInput.value = '';
                imagePreview.src = '';
                imagePreview.style.display = 'none';
                resultText.value = '';
                translatedText.value = '';
                extractedText = '';
                extractBtn.disabled = true;
                translateBtn.disabled = true;
                copyBtn.disabled = true;
                translationResultArea.style.display = 'none';
                progressContainer.style.display = 'none';
                errorMessage.textContent = '';
            });
        });
    </script>
</body>
</html>