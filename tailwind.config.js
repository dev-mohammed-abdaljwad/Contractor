/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
  ],
  theme: {
    extend: {
      colors: {
        primary: '#0d631b',
        'pri-mid': '#1D9E75',
        'pri-lt': '#66BB6A',
        'pri-bg': '#E1F5EE',
        surface: '#fafaf5',
        'sur-2': '#f1f1ec',
        outline: '#d0d0c8',
        muted: '#707a6c',
        danger: '#ba1a1a',
        'dan-bg': '#fff5f5',
        amber: '#BA7517',
        'amb-bg': '#FAEEDA',
        blue: '#185FA5',
        'blu-bg': '#E6F1FB',
      },
      fontFamily: {
        arabic: ['Tajawal', 'sans-serif'],
      },
    },
  },
  plugins: [],
}
