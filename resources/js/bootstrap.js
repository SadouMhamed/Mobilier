import axios from "axios";
window.axios = axios;

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

// Import et configuration d'Alpine.js uniquement si nécessaire
import Alpine from "alpinejs";

// Démarrer Alpine seulement s'il n'est pas déjà initialisé
if (!window.Alpine) {
    window.Alpine = Alpine;
    Alpine.start();
}
