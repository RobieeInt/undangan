/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
    "./app/Livewire/**/*.php",
  ],
  theme: {
    extend: {
      colors: {
        cream:    { DEFAULT: '#FBF5DD', light: '#FEFAEE', dark: '#F5EBC4' },
        sand:     { DEFAULT: '#E7E1B1', light: '#EDE8C4', dark: '#D9D28E' },
        forest:   { DEFAULT: '#306D29', light: '#3A8231', dark: '#234F1E', darker: '#1A3C16' },
        emerald:  { DEFAULT: '#0D530E', light: '#0F6510', dark: '#093D0A', darker: '#062B07' },
      },
      fontFamily: {
        serif:      ['"Playfair Display"', '"Cormorant Garamond"', 'Georgia', 'serif'],
        cormorant:  ['"Cormorant Garamond"', 'Georgia', 'serif'],
        playfair:   ['"Playfair Display"', 'Georgia', 'serif'],
        sans:       ['Montserrat', 'system-ui', 'sans-serif'],
      },
      backgroundImage: {
        'gradient-luxury': 'linear-gradient(135deg, #FBF5DD 0%, #E7E1B1 50%, #FBF5DD 100%)',
        'gradient-forest': 'linear-gradient(135deg, #306D29 0%, #0D530E 100%)',
      },
      boxShadow: {
        'luxury':    '0 4px 40px rgba(48, 109, 41, 0.08), 0 1px 8px rgba(0,0,0,0.04)',
        'luxury-lg': '0 8px 60px rgba(48, 109, 41, 0.12), 0 2px 16px rgba(0,0,0,0.06)',
        'glass':     '0 8px 32px rgba(0,0,0,0.08), inset 0 1px 0 rgba(255,255,255,0.6)',
      },
      animation: {
        'fade-in':       'fadeIn 0.8s ease-out',
        'slide-up':      'slideUp 0.6s ease-out',
        'float':         'float 6s ease-in-out infinite',
        'pulse-slow':    'pulse 4s ease-in-out infinite',
      },
      keyframes: {
        fadeIn:  { '0%': { opacity: '0' }, '100%': { opacity: '1' } },
        slideUp: { '0%': { opacity: '0', transform: 'translateY(20px)' }, '100%': { opacity: '1', transform: 'translateY(0)' } },
        float:   { '0%, 100%': { transform: 'translateY(0)' }, '50%': { transform: 'translateY(-10px)' } },
      },
      backdropBlur: { xs: '2px' },
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography'),
  ],
}
