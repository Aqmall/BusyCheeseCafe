/** @type {import('tailwindcss').Config} */
export default {
    content: ["./resources/**/*.blade.php", "./resources/**/*.js"],
    theme: {
        extend: {
            colors: {
                "cafe-primary": "#FFD700", // Warm golden yellow
                "cafe-primary-dark": "#E6C200",
                "cafe-secondary": "#333333", // Deep charcoal
                "cafe-beige": "#F5EFE6", // Warm beige
                "cafe-accent": "#4A90E2", // Friendly blue
                "status-available": "#28A745",
                "status-booked": "#FFC107",
                "status-occupied": "#DC3545",
            },
        },
    },
    plugins: [],
};
