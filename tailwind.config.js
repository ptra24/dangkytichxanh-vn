/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./index.html",
    "./src/**/*.{vue,js,ts,jsx,tsx}",
    "./resources/**/*.{blade.php,js,vue}",
  ],
  darkMode: 'class',
  theme: {
    extend: {
      colors: {
        dark: {
          deep: '#030712',      // Deepest black-blue background
          darker: '#090f1d',    // Main dark background
          card: '#0f172a',      // Dark card container background
          border: '#1e293b',    // Slate dark border
          hover: '#1e293b',     // Hover color for dark elements
          accent: '#2563eb'     // Accent blue for general features
        },
        meta: {
          blue: '#1877f2',      // Official Facebook blue
          blueHover: '#166fe5'
        },
        tiktok: {
          cyan: '#00f2fe',
          pink: '#fe0979',
          dark: '#121212'
        },
        whatsapp: {
          green: '#25d366',
          greenHover: '#20ba5a'
        },
        instagram: {
          pink: '#e1306c',
          purple: '#833ab4',
          orange: '#f56040'
        }
      },
      fontFamily: {
        sans: ['Inter', 'Outfit', 'sans-serif'],
      },
      boxShadow: {
        'premium': '0 10px 30px -10px rgba(0, 0, 0, 0.7)',
        'blue-glow': '0 0 20px rgba(37, 99, 235, 0.2)',
        'green-glow': '0 0 20px rgba(37, 211, 102, 0.2)',
        'cyan-glow': '0 0 20px rgba(0, 242, 254, 0.2)',
        'purple-glow': '0 0 20px rgba(225, 48, 108, 0.2)',
      }
    },
  },
  plugins: [],
}
