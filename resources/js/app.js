import './bootstrap';
import iziToast from 'izitoast';
import 'izitoast/dist/css/iziToast.min.css'; 
window.iziToast = iziToast;
/*
  Add custom scripts here
*/
import.meta.glob([
  '../assets/img/**',
  // '../assets/json/**',
  '../assets/vendor/fonts/**'
]);
