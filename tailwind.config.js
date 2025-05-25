import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import plugin from 'tailwindcss/plugin';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [
        forms,
        plugin(function({ addComponents, theme }) {
            addComponents({
                '.nav-link': {
                    color: theme('colors.gray.700'),
                    transitionProperty: theme('transitionProperty.colors'),
                    '&:hover': {
                        color: theme('colors.blue.500'),
                    },
                    '@screen dark': {
                        color: theme('colors.gray.300'),
                        '&:hover': {
                            color: theme('colors.blue.400'),
                        },
                    },
                },
                '.active-link': {
                    fontWeight: theme('fontWeight.semibold'),
                    color: theme('colors.blue.600'),
                    '@screen dark': {
                        color: theme('colors.blue.400'),
                    },
                },
            });
        }),
    ],
};