import './bootstrap';

import Alpine from 'alpinejs';
import productSearch from './components/productSearch';

window.Alpine = Alpine;

// Live search autocomplete component (used by the header search box).
Alpine.data('productSearch', productSearch);

Alpine.start();
