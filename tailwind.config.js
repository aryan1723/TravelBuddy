// tailwind.config.js
module.exports = {
    content: ["./index.html", "./src/**/*.{js,ts,jsx,tsx}"],
    theme: {
      extend: {
        animation: {
          waves: "waveForward 8s linear infinite",
          'waves-reverse': "waveReverse 8s linear infinite",
        },
        keyframes: {
          waveForward: {
            "0%": { backgroundPositionX: "1000px" },
            "100%": { backgroundPositionX: "0px" },
          },
          waveReverse: {
            "0%": { backgroundPositionX: "-1000px" },
            "100%": { backgroundPositionX: "0px" },
          },
        },
      },
    },
    plugins: [],
  }
  