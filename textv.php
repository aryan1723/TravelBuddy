<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Text Translation | TravelBuddy</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f7fafc;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh; /* Ensure footer stays at bottom */
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            flex: 1; /* Allow container to grow */
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
            gap: 5px;
        }
        /* .back-btn i { margin-right: 5px; } */ /* Replaced by gap */
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
            margin-bottom: 10px; /* Added margin */
            display: flex;
            align-items: center;
            gap: 10px;
        }
         .main-content > p:first-of-type { /* Target intro paragraph */
             margin-bottom: 25px;
             color: #6b7280;
         }

        .input-area label,
        .language-selector label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #374151;
        }

        .input-area {
            margin-bottom: 20px;
        }
        .text-input {
            width: 100%;
            min-height: 150px;
            padding: 15px;
            border: 1px solid #d1d5db;
            border-radius: 5px;
            resize: vertical;
            font-family: 'Poppins', sans-serif;
            font-size: 16px; /* Ensure font size */
            line-height: 1.5; /* Improve readability */
            box-sizing: border-box; /* Include padding/border in width */
        }
        .language-select {
            width: 100%;
            padding: 10px 12px; /* Adjusted padding */
            border: 1px solid #d1d5db;
            border-radius: 5px;
            margin-bottom: 20px; /* Consistent margin */
            font-size: 16px; /* Ensure font size */
            box-sizing: border-box;
            background-color: white; /* Ensure background */
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
            /* margin-right: 10px; Removed for gap */
            /* margin-bottom: 10px; Removed for gap */
            display: inline-flex; /* Use inline-flex for alignment */
            align-items: center;
            justify-content: center;
            gap: 8px; /* Space between icon and text */
            font-size: 16px; /* Ensure font size */
        }
        .btn:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            opacity: 0.9; /* Slight fade on hover */
        }
        .btn:disabled {
            background: #d1d5db;
            color: #6b7280; /* Lighter text when disabled */
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
            opacity: 0.7;
        }
        .result-area {
            margin-top: 30px;
        }
        .result-area label {
             display: block;
             margin-bottom: 8px;
             font-weight: 600;
             color: #374151;
        }
        .result-text { /* Style for translated text output */
            width: 100%;
            min-height: 150px;
            padding: 15px;
            border: 1px solid #d1d5db;
            border-radius: 5px;
            resize: vertical;
            font-family: 'Poppins', sans-serif; /* Ensure font */
            font-size: 16px; /* Ensure font size */
            line-height: 1.5;
            background-color: #f9fafb; /* Slightly different background */
            color: #1f2937; /* Darker text */
            box-sizing: border-box;
        }
        .progress-container {
            margin-top: 20px;
            display: none; /* Hide initially */
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
            transition: width 0.3s ease-in-out; /* Smoother transition */
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
            margin-top: 30px; /* Ensure space above footer */
        }
        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        .copyright {
            margin-top: 15px; /* Adjusted margin */
            font-size: 14px;
            color: rgba(255, 255, 255, 0.8); /* Slightly more opaque */
        }
        .language-selectors {
            display: flex;
            flex-direction: column; /* Stack on small screens */
            gap: 20px; /* Increased gap */
            margin-bottom: 25px; /* Adjusted margin */
        }
        .language-selector {
            flex: 1;
            min-width: 150px; /* Prevent excessive shrinking */
        }

        @media (min-width: 640px) { /* Apply side-by-side on larger screens */
             .language-selectors {
                 flex-direction: row;
                 gap: 15px;
             }
        }

        .api-selector { /* Although hidden, keep styles for potential future use */
            margin-bottom: 20px;
        }
        .api-selector label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
        }
        .error-message {
            color: #dc2626; /* Slightly darker red */
            margin-top: 15px; /* Adjusted margin */
            font-size: 14px;
            background-color: #fee2e2;
            border: 1px solid #fca5a5;
            padding: 10px 15px;
            border-radius: 5px;
            display: none; /* Hide initially */
            line-height: 1.4;
        }
         .error-message strong {
             font-weight: 600;
         }
        .btn-group {
            display: flex;
            flex-wrap: wrap; /* Allow buttons to wrap */
            gap: 10px; /* Space between buttons */
            margin-bottom: 20px;
        }
        .api-key-input { /* Although hidden */
            width: 100%;
            padding: 10px;
            border: 1px solid #d1d5db;
            border-radius: 5px;
            margin-bottom: 15px;
            box-sizing: border-box;
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
            <h1><i class="fas fa-language"></i> Text Translation</h1>
            <p>Enter text, select languages, and click Translate & Speak to hear the translation.</p>

            <div class="input-area">
                <label for="sourceText">Enter Text to Translate:</label>
                <textarea id="sourceText" class="text-input" placeholder="Type or paste text here..."></textarea>
            </div>

            <div class="language-selectors">
                <div class="language-selector">
                    <label for="sourceLanguage">From:</label>
                    <select id="sourceLanguage" class="language-select">
                        <option value="en">English</option>
                        <option value="hi">Hindi</option>
                        <option value="es">Spanish</option>
                        <option value="fr">French</option>
                        <option value="de">German</option>
                        <option value="ja">Japanese</option>
                        <option value="zh">Chinese</option>
                        <option value="ar">Arabic</option>
                        <option value="bn">Bengali</option>
                        <option value="ta">Tamil</option>
                        <option value="te">Telugu</option>
                        <option value="mr">Marathi</option>
                        <option value="gu">Gujarati</option>
                        <option value="kn">Kannada</option>
                        <option value="ml">Malayalam</option>
                        <option value="pa">Punjabi</option>
                        <option value="ru">Russian</option>
                        <option value="it">Italian</option>
                        <option value="pt">Portuguese</option>
                        </select>
                </div>

                <div class="language-selector">
                    <label for="targetLanguage">To:</label>
                    <select id="targetLanguage" class="language-select">
                        <option value="en">English</option>
                        <option value="hi" selected>Hindi</option>
                        <option value="es">Spanish</option>
                        <option value="fr">French</option>
                        <option value="de">German</option>
                        <option value="ja">Japanese</option>
                        <option value="zh">Chinese</option>
                        <option value="ar">Arabic</option>
                        <option value="bn">Bengali</option>
                        <option value="ta">Tamil</option>
                        <option value="te">Telugu</option>
                        <option value="mr">Marathi</option>
                        <option value="gu">Gujarati</option>
                        <option value="kn">Kannada</option>
                        <option value="ml">Malayalam</option>
                        <option value="pa">Punjabi</option>
                        <option value="ru">Russian</option>
                        <option value="it">Italian</option>
                        <option value="pt">Portuguese</option>
                        </select>
                </div>
            </div>

            <div class="api-selector" hidden>
                <label for="translationApi">Translation Service:</label>
                <select id="translationApi" class="language-select">
                    <option value="gemini" selected>Gemini AI</option>
                </select>
                <input type="text" id="geminiApiKey" class="api-key-input" value="AIzaSyBk5G9lWQVncYumqRgxDjWAFRxz8fBAXQ0" readonly hidden>
            </div>

            <div class="btn-group">
                <button id="translateSpeakBtn" class="btn">
                    <i class="fas fa-exchange-alt"></i> Translate & Speak
                </button>
                <button id="copyBtn" class="btn" disabled>
                    <i class="fas fa-copy"></i> Copy Translation
                </button>
                <button id="clearBtn" class="btn">
                    <i class="fas fa-trash"></i> Clear Text
                </button>
            </div>

            <div class="progress-container" id="progressContainer">
                <div class="progress-bar">
                    <div class="progress" id="progressBar"></div>
                </div>
                <div class="status" id="statusText">Enter text and click translate.</div>
                <div id="errorMessage" class="error-message"></div>
            </div>

             <div class="result-area" id="resultArea" style="display: none;" hidden>
                <label for="resultText">Translated Text:</label>
                <textarea id="resultText" class="result-text" readonly></textarea>
            </div>
        </div>
    </div>

    <footer>
        <div class="footer-content">
            <div class="logo" style="font-size: 28px; margin-bottom: 15px;">travel<span style="color: white;">buddy</span></div>
            <p>Your smart travel companion for every journey</p>
            <div class="copyright">
                 &copy; <?php echo date("Y"); ?> TravelBuddy. All rights reserved.
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // DOM Elements
            const sourceText = document.getElementById('sourceText');
            const translateSpeakBtn = document.getElementById('translateSpeakBtn');
            const copyBtn = document.getElementById('copyBtn');
            const clearBtn = document.getElementById('clearBtn');
            const resultText = document.getElementById('resultText'); // Textarea for result
            const resultArea = document.getElementById('resultArea'); // Container for result textarea
            const sourceLanguage = document.getElementById('sourceLanguage');
            const targetLanguage = document.getElementById('targetLanguage');
            // const translationApi = document.getElementById('translationApi'); // Not needed as API is fixed
            const geminiApiKey = document.getElementById('geminiApiKey'); // Get hardcoded key
            const progressContainer = document.getElementById('progressContainer');
            const progressBar = document.getElementById('progressBar');
            const statusText = document.getElementById('statusText');
            const errorMessage = document.getElementById('errorMessage');

            // Speech Synthesis API
            const synth = window.speechSynthesis;
            let currentUtterance = null;
            let translatedTextValue = ''; // Store the latest translated text

            // --- Hardcoded Gemini API Key ---
            // Key is directly read from the hidden input's value attribute
            // const GEMINI_API_KEY = 'AIzaSyBk5G9lWQVncYumqRgxDjWAFRxz8fBAXQ0'; // Can read from input instead


            // Initialize speech synthesis voices (needed for some browsers)
            function loadVoices() {
                 if (typeof speechSynthesis === 'undefined') {
                      console.warn("Speech Synthesis not supported by this browser.");
                      return;
                 }
                // Get voices (this helps populate the list on some browsers)
                const voices = synth.getVoices();
                console.log(`Loaded ${voices.length} voices.`);
            }

            // Load voices when they become available or immediately if already loaded
            if (typeof speechSynthesis !== 'undefined') {
                 loadVoices();
                 if (speechSynthesis.onvoiceschanged !== undefined) {
                     speechSynthesis.onvoiceschanged = loadVoices;
                 }
             } else {
                 showError("Your browser does not support Speech Synthesis.");
                 // Optionally disable speak functionality here
             }


            // --- Gemini AI Translation Function ---
            async function translateWithGemini(text, sourceLang, targetLang) {
                const apiKey = geminiApiKey.value.trim(); // Read key from hidden input
                if (!apiKey) {
                    throw new Error('Gemini API key is missing.'); // Should not happen with hardcoded value
                }

                // For Gemini, auto-detection is handled by the model if sourceLang is empty or 'auto'
                // We pass the language codes directly if selected.
                const sourceLangCode = sourceLang; // e.g., 'en', 'hi'
                const targetLangCode = targetLang; // e.g., 'hi', 'en'

                // Get full language names for a potentially more natural prompt
                const sourceLangName = sourceLanguage.options[sourceLanguage.selectedIndex].text;
                const targetLangName = targetLanguage.options[targetLanguage.selectedIndex].text;

                // Construct a clear prompt
                 const prompt = `Translate the following text from ${sourceLangName} (${sourceLangCode}) to ${targetLangName} (${targetLangCode}). Provide only the translated text:\n\n${text}`;
                 console.log("Gemini Prompt:", prompt);


                // Use the corrected model name
                const modelName = 'gemini-1.5-flash'; // Or 'gemini-1.5-pro-latest' if preferred/needed
                const apiUrl = `https://generativelanguage.googleapis.com/v1beta/models/${modelName}:generateContent?key=${apiKey}`;

                const response = await fetch(apiUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        contents: [{
                            parts: [{
                                text: prompt
                            }]
                        }],
                        // Optional: Add safety settings if needed
                        // "safetySettings": [
                        //     { "category": "HARM_CATEGORY_HARASSMENT", "threshold": "BLOCK_MEDIUM_AND_ABOVE" },
                        //     // Add other categories as needed
                        // ],
                        // Optional: Add generation config if needed
                        // "generationConfig": { "temperature": 0.7 }
                    })
                });

                 if (!response.ok) {
                    let errorMsg = `Gemini API Error (${response.status})`;
                    try {
                         const errorData = await response.json();
                         errorMsg = errorData.error?.message || `HTTP Error ${response.status}: ${JSON.stringify(errorData)}`;
                    } catch (e) {
                        errorMsg = `HTTP Error ${response.status}: ${response.statusText}`;
                    }
                    throw new Error(errorMsg);
                }

                const data = await response.json();

                // Check response structure carefully
                 if (data.candidates && data.candidates[0] && data.candidates[0].content && data.candidates[0].content.parts && data.candidates[0].content.parts[0] && data.candidates[0].content.parts[0].text) {
                     return data.candidates[0].content.parts[0].text.trim(); // Return translated text
                 } else if (data.promptFeedback && data.promptFeedback.blockReason) {
                     throw new Error(`Translation blocked by Gemini: ${data.promptFeedback.blockReason}`);
                 } else {
                    console.error("Unexpected Gemini response structure:", data);
                    throw new Error('No valid translation returned from Gemini API.');
                }
            }

            // --- Speak the translated text function (with robust voice selection) ---
             function speakTranslatedText(text) {
                 if (typeof speechSynthesis === 'undefined') {
                     showError("Speech Synthesis not supported.");
                     return;
                 }

                 // Cancel any ongoing speech
                 if (synth.speaking) {
                      console.log("Cancelling previous speech.");
                      synth.cancel();
                 }

                 // Ensure text is provided
                 if (!text) {
                      console.warn("Speak function called with empty text.");
                      statusText.textContent = 'Nothing to speak.';
                      progressContainer.style.display = 'none';
                      // Re-enable button if we decided not to speak
                      translateSpeakBtn.disabled = false;
                      translateSpeakBtn.innerHTML = '<i class="fas fa-exchange-alt"></i> Translate & Speak';
                      return;
                 }

                 const voices = synth.getVoices();

                 // --- Robust Voice Selection ---
                 if (voices.length === 0) {
                      console.error("Speech synthesis voices not loaded.");
                      errorMessage.innerHTML = '<strong>Speech Error:</strong> Voices not loaded. Please wait or refresh.'; // Use innerHTML
                      loadVoices(); // Attempt to reload voices
                      translateSpeakBtn.disabled = false;
                      translateSpeakBtn.innerHTML = '<i class="fas fa-exchange-alt"></i> Translate & Speak';
                      return;
                 }

                 const targetLang = targetLanguage.value; // e.g., 'hi', 'en', 'fr'
                 let selectedVoice = null;

                 // Filter for voices matching the exact language code or language code with region
                 const suitableVoices = voices.filter(voice =>
                      voice.lang === targetLang || voice.lang.startsWith(targetLang + '-')
                 );

                 if (suitableVoices.length > 0) {
                      // Prefer a local voice if available within the suitable ones
                      selectedVoice = suitableVoices.find(voice => !voice.localService) || suitableVoices[0];
                      console.log(`Found ${suitableVoices.length} suitable voice(s) for language '${targetLang}'. Selected:`, selectedVoice.name, `(${selectedVoice.lang})`);
                 } else {
                      console.warn(`No voices found specifically for language code '${targetLang}'.`);
                      errorMessage.innerHTML = `<strong>Speech Error:</strong> Sorry, no voice available for the selected language (${targetLanguage.options[targetLanguage.selectedIndex].text}) in your browser.`; // Use innerHTML
                      translateSpeakBtn.disabled = false;
                      translateSpeakBtn.innerHTML = '<i class="fas fa-exchange-alt"></i> Translate & Speak';
                      progressContainer.style.display = 'none';
                      return; // Stop execution if no voice is found
                 }
                  // --- End Robust Voice Selection ---


                 // Create utterance
                 currentUtterance = new SpeechSynthesisUtterance(text);
                 currentUtterance.voice = selectedVoice; // Assign the found voice
                 currentUtterance.lang = selectedVoice.lang; // Set utterance lang explicitly
                 currentUtterance.rate = 1.0; // Adjust rate as needed (0.1 to 10)
                 currentUtterance.pitch = 1.0; // Adjust pitch as needed (0 to 2)

                 // Clear previous error message before speaking
                 errorMessage.style.display = 'none';
                 errorMessage.innerHTML = ''; // Use innerHTML
                 progressContainer.style.display = 'block'; // Show progress for speech prep
                 progressBar.style.width = '0%';
                 statusText.textContent = 'Preparing speech...';

                 // --- Event Handlers ---
                  currentUtterance.onboundary = (event) => {
                      const textLength = currentUtterance.text.length;
                      if (event.name === 'word' && textLength > 0) {
                          const progress = Math.min(((event.charIndex + event.charLength) / textLength) * 100, 100);
                          progressBar.style.width = `${progress}%`;
                      }
                  };

                 currentUtterance.onstart = () => {
                     console.log("Speech started.");
                     statusText.textContent = 'Speaking...';
                      translateSpeakBtn.disabled = true; // Ensure button is disabled while speaking
                      translateSpeakBtn.innerHTML = '<i class="fas fa-volume-up"></i> Speaking...';
                 };

                 currentUtterance.onend = () => {
                     console.log("Speech finished.");
                     progressBar.style.width = '100%';
                     statusText.textContent = 'Speech completed';
                     translateSpeakBtn.disabled = false; // Re-enable button AFTER speech ends
                     translateSpeakBtn.innerHTML = '<i class="fas fa-exchange-alt"></i> Translate & Speak';

                     setTimeout(() => {
                         if (statusText.textContent === 'Speech completed') {
                              progressContainer.style.display = 'none';
                         }
                     }, 1500);
                 };

                 currentUtterance.onerror = (event) => {
                     console.error('SpeechSynthesis Error:', event.error);
                     errorMessage.innerHTML = '<strong>Speech Error:</strong> ' + event.error; // Use innerHTML
                     errorMessage.style.display = 'block';
                     progressContainer.style.display = 'none';
                     translateSpeakBtn.disabled = false; // Re-enable on error
                     translateSpeakBtn.innerHTML = '<i class="fas fa-exchange-alt"></i> Translate & Speak';
                 };

                 // Speak the text with a small delay
                  setTimeout(() => {
                     console.log(`Attempting to speak with voice: ${selectedVoice.name} (${selectedVoice.lang})`);
                     synth.speak(currentUtterance);
                  }, 100);

             }

            // --- Translate & Speak Button Click Handler ---
            translateSpeakBtn.addEventListener('click', async () => {
                const textToTranslate = sourceText.value.trim();
                if (!textToTranslate) {
                    showError('Please enter some text to translate.');
                    return;
                }

                // Clear previous results and errors
                hideError();
                resultText.value = '';
                translatedTextValue = '';
                resultArea.style.display = 'none'; // Hide result area initially
                copyBtn.disabled = true;
                if (synth.speaking) synth.cancel(); // Stop any current speech

                progressContainer.style.display = 'block';
                progressBar.style.width = '0%';
                statusText.textContent = 'Translating text...';
                translateSpeakBtn.disabled = true;
                translateSpeakBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';


                try {
                    const sourceLang = sourceLanguage.value;
                    const targetLang = targetLanguage.value;

                    progressBar.style.width = '30%'; // Initial progress
                    statusText.textContent = 'Calling Translation API...';

                    // Use Gemini for translation
                    const translationResult = await translateWithGemini(textToTranslate, sourceLang, targetLang);

                    translatedTextValue = translationResult; // Store result
                    resultText.value = translatedTextValue; // Display result
                    resultArea.style.display = 'none'; // Show result area
                    statusText.textContent = 'Translation complete!';
                    progressBar.style.width = '100%';
                    copyBtn.disabled = false; // Enable copy button

                    // Automatically speak the translated text after a short delay
                    setTimeout(() => {
                         speakTranslatedText(translatedTextValue);
                         // Keep button disabled until speech ends/fails (handled in speak function)
                    }, 500); // Delay before speaking

                } catch (error) {
                    console.error('Translation/Speak error:', error);
                    statusText.textContent = 'Operation failed';
                    showError(`<strong>Error:</strong> ${error.message}`);
                    // Re-enable button on failure
                    translateSpeakBtn.disabled = false;
                    translateSpeakBtn.innerHTML = '<i class="fas fa-exchange-alt"></i> Translate & Speak';
                    progressContainer.style.display = 'none'; // Hide progress on error
                }
                // Note: The speak function handles re-enabling the button upon speech completion/error
            });

            // --- Copy Button Click Handler ---
            copyBtn.addEventListener('click', () => {
                if (!translatedTextValue) return;

                navigator.clipboard.writeText(translatedTextValue).then(() => {
                    const originalText = copyBtn.innerHTML;
                    copyBtn.innerHTML = '<i class="fas fa-check"></i> Copied!';
                    copyBtn.disabled = true; // Temporarily disable after copy
                    setTimeout(() => {
                        copyBtn.innerHTML = originalText;
                        copyBtn.disabled = false; // Re-enable
                    }, 2000);
                }).catch(err => {
                     console.error('Failed to copy text: ', err);
                     showError('Failed to copy text to clipboard.');
                 });
            });

            // --- Clear Button Click Handler ---
            clearBtn.addEventListener('click', () => {
                sourceText.value = '';
                resultText.value = '';
                translatedTextValue = '';
                resultArea.style.display = 'none'; // Hide result area
                translateSpeakBtn.disabled = false; // Ensure translate button is enabled
                translateSpeakBtn.innerHTML = '<i class="fas fa-exchange-alt"></i> Translate & Speak';
                copyBtn.disabled = true;
                progressContainer.style.display = 'none';
                hideError();
                statusText.textContent = 'Enter text and click translate.'; // Reset status

                // Stop any ongoing speech
                if (synth.speaking) {
                     synth.cancel();
                }
            });

             // Helper to show error messages
             function showError(message) {
                 errorMessage.innerHTML = message; // Use innerHTML for potential formatting
                 errorMessage.style.display = 'block';
             }

             // Helper to hide error messages
             function hideError() {
                 errorMessage.style.display = 'none';
                 errorMessage.innerHTML = '';
             }

        });
    </script>
</body>
</html>