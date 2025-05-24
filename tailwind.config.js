/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './resources/**/*.blade.php',  // تأكد من إضافة مسار ملفات Blade الخاصة بك
    './resources/**/*.js',
    './resources/**/*.vue',
  ],
  theme: {
    extend: {
      colors: {
        'custom-color': '#ff5733',  // إضافة لون مخصص على سبيل المثال
      },
    },
  },
  plugins: [],
}
