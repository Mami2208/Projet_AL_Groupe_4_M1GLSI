@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <div class="relative bg-white overflow-hidden">
        <div class="max-w-7xl mx-auto">
            <div class="relative z-10 pb-8 bg-white sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32">
                <main class="mt-10 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 md:mt-16 lg:mt-20 lg:px-8 xl:mt-28">
                    <div class="sm:text-center lg:text-left">
                        <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
                            <span class="block">Restez informé avec</span>
                            <span class="block text-blue-600">Actualités+</span>
                        </h1>
                        <p class="mt-3 text-base text-gray-500 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
                            Découvrez les dernières actualités en temps réel, des analyses approfondies et des reportages exclusifs sur tous les sujets qui vous intéressent.
                        </p>
                        <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start">
                            <div class="rounded-md shadow">
                                <a href="#actualites" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 md:py-4 md:text-lg md:px-10">
                                    Voir les actualités
                                </a>
                            </div>
                            <div class="mt-3 sm:mt-0 sm:ml-3">
                                <a href="#categories" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 md:py-4 md:text-lg md:px-10">
                                    Parcourir les catégories
                                </a>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <div class="lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2">
            <img class="h-56 w-full object-cover sm:h-72 md:h-96 lg:w-full lg:h-full" src="https://images.unsplash.com/photo-1495020689067-958852a7765e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1350&q=80" alt="Actualités en image">
        </div>
    </div>
    <!-- Section Actualités -->
    <div id="actualites" class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                    Dernières actualités
                </h2>
                <p class="mt-3 max-w-2xl mx-auto text-xl text-gray-500 sm:mt-4">
                    Restez à jour avec les dernières nouvelles et informations importantes.
                </p>
            </div>

            <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                <!-- Article 1 -->
                <div class="flex flex-col overflow-hidden rounded-lg shadow-lg bg-white card-hover">
                    <div class="flex-shrink-0">
                        <img class="h-48 w-full object-cover" src="https://images.unsplash.com/photo-1496128858413-4126ec88f234?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1350&q=80" alt="Article 1">
                    </div>
                    <div class="flex-1 p-6 flex flex-col justify-between">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-blue-600">
                                <a href="#" class="hover:underline">Politique</a>
                            </p>
                            <a href="#" class="block mt-2">
                                <h3 class="text-xl font-semibold text-gray-900">
                                    Les dernières décisions gouvernementales
                                </h3>
                                <p class="mt-3 text-base text-gray-500">
                                    Analyse approfondie des nouvelles mesures politiques et de leur impact sur la population.
                                </p>
                            </a>
                        </div>
                        <div class="mt-6 flex items-center">
                            <div class="flex-shrink-0">
                                <span class="sr-only">Auteur</span>
                                <img class="h-10 w-10 rounded-full" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=fit&facepad=2&w=256&h=256&q=80" alt="">
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">
                                    <a href="#" class="hover:underline">Jean Dupont</a>
                                </p>
                                <div class="flex space-x-1 text-sm text-gray-500">
                                    <time datetime="2025-07-05">5 Juillet 2025</time>
                                    <span aria-hidden="true">&middot;</span>
                                    <span>5 min de lecture</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Article 2 -->
                <div class="flex flex-col overflow-hidden rounded-lg shadow-lg bg-white card-hover">
                    <div class="flex-shrink-0">
                        <img class="h-48 w-full object-cover" src="https://images.unsplash.com/photo-1542744173-8e7e53415bb0?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1350&q=80" alt="Article 2">
                    </div>
                    <div class="flex-1 p-6 flex flex-col justify-between">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-green-600">
                                <a href="#" class="hover:underline">Environnement</a>
                            </p>
                            <a href="#" class="block mt-2">
                                <h3 class="text-xl font-semibold text-gray-900">
                                    Innovations durables pour demain
                                </h3>
                                <p class="mt-3 text-base text-gray-500">
                                    Découvrez comment les nouvelles technologies vertes transforment notre avenir environnemental.
                                </p>
                            </a>
                        </div>
                        <div class="mt-6 flex items-center">
                            <div class="flex-shrink-0">
                                <span class="sr-only">Auteure</span>
                                <img class="h-10 w-10 rounded-full" src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="">
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">
                                    <a href="#" class="hover:underline">Marie Martin</a>
                                </p>
                                <div class="flex space-x-1 text-sm text-gray-500">
                                    <time datetime="2025-07-04">4 Juillet 2025</time>
                                    <span aria-hidden="true">&middot;</span>
                                    <span>4 min de lecture</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Article 3 -->
                <div class="flex flex-col overflow-hidden rounded-lg shadow-lg bg-white card-hover">
                    <div class="flex-shrink-0">
                        <img class="h-48 w-full object-cover" src="https://images.unsplash.com/photo-1497032628192-86f99bcd90bc?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1350&q=80" alt="Article 3">
                    </div>
                    <div class="flex-1 p-6 flex flex-col justify-between">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-purple-600">
                                <a href="#" class="hover:underline">Technologie</a>
                            </p>
                            <a href="#" class="block mt-2">
                                <h3 class="text-xl font-semibold text-gray-900">
                                    L'IA révolutionne la médecine
                                </h3>
                                <p class="mt-3 text-base text-gray-500">
                                    Comment l'intelligence artificielle transforme le diagnostic et les soins de santé.
                                </p>
                            </a>
                        </div>
                        <div class="mt-6 flex items-center">
                            <div class="flex-shrink-0">
                                <span class="sr-only">Auteur</span>
                                <img class="h-10 w-10 rounded-full" src="https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="">
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">
                                    <a href="#" class="hover:underline">Thomas Leroy</a>
                                </p>
                                <div class="flex space-x-1 text-sm text-gray-500">
                                    <time datetime="2025-07-03">3 Juillet 2025</time>
                                    <span aria-hidden="true">&middot;</span>
                                    <span>6 min de lecture</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-12 text-center">
                <a href="#" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                    Voir plus d'articles
                    <svg class="ml-3 -mr-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </a>
            </div>
        </div>
    </div>

    <!-- Section Catégories -->
    <div id="categories" class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                    Parcourir par catégorie
                </h2>
                <p class="mt-3 max-w-2xl mx-auto text-xl text-gray-500 sm:mt-4">
                    Découvrez nos articles par thème d'actualité.
                </p>
            </div>

            <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-4">
                <!-- Catégorie 1 -->
                <a href="#" class="relative p-6 border border-gray-200 rounded-lg hover:border-blue-500 transition-colors">
                    <div class="flex items-center justify-center h-12 w-12 rounded-md bg-blue-100 text-blue-600 mb-4">
                        <i class="fas fa-landmark text-xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Politique</h3>
                    <p class="text-gray-500">Actualités politiques nationales et internationales</p>
                    <span class="absolute top-4 right-4 text-gray-400 group-hover:text-blue-500">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </span>
                </a>

                <!-- Catégorie 2 -->
                <a href="#" class="relative p-6 border border-gray-200 rounded-lg hover:border-green-500 transition-colors">
                    <div class="flex items-center justify-center h-12 w-12 rounded-md bg-green-100 text-green-600 mb-4">
                        <i class="fas fa-leaf text-xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Environnement</h3>
                    <p class="text-gray-500">Écologie et développement durable</p>
                    <span class="absolute top-4 right-4 text-gray-400 group-hover:text-green-500">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </span>
                </a>

                <!-- Catégorie 3 -->
                <a href="#" class="relative p-6 border border-gray-200 rounded-lg hover:border-purple-500 transition-colors">
                    <div class="flex items-center justify-center h-12 w-12 rounded-md bg-purple-100 text-purple-600 mb-4">
                        <i class="fas fa-laptop-code text-xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Technologie</h3>
                    <p class="text-gray-500">Innovations et avancées technologiques</p>
                    <span class="absolute top-4 right-4 text-gray-400 group-hover:text-purple-500">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </span>
                </a>

                <!-- Catégorie 4 -->
                <a href="#" class="relative p-6 border border-gray-200 rounded-lg hover:border-red-500 transition-colors">
                    <div class="flex items-center justify-center h-12 w-12 rounded-md bg-red-100 text-red-600 mb-4">
                        <i class="fas fa-heartbeat text-xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Santé</h3>
                    <p class="text-gray-500">Actualités médicales et bien-être</p>
                    <span class="absolute top-4 right-4 text-gray-400 group-hover:text-red-500">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </span>
                </a>
            </div>
        </div>
    </div>

    <!-- Section Newsletter -->
    <div class="bg-blue-700">
        <div class="max-w-2xl mx-auto text-center py-16 px-4 sm:py-20 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-extrabold text-white sm:text-4xl">
                <span class="block">Restez informé</span>
                <span class="block">Abonnez-vous à notre newsletter</span>
            </h2>
            <p class="mt-4 text-lg leading-6 text-blue-200">
                Recevez chaque semaine les dernières actualités directement dans votre boîte mail.
            </p>
            <form class="mt-8 sm:flex">
                <label for="email-address" class="sr-only">Adresse email</label>
                <input id="email-address" name="email" type="email" autocomplete="email" required class="w-full px-5 py-3 border border-transparent text-base text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-blue-700 focus:ring-white focus:border-white sm:max-w-xs rounded-md" placeholder="Votre adresse email">
                <div class="mt-3 rounded-md shadow sm:mt-0 sm:ml-3 sm:flex-shrink-0">
                    <button type="submit" class="w-full flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-blue-600 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-blue-700 focus:ring-blue-500">
                        S'abonner
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Pied de page -->
    <footer class="bg-gray-900 text-white">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:py-16 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div class="col-span-2">
                    <h3 class="text-2xl font-bold bg-gradient-to-r from-blue-400 to-purple-500 bg-clip-text text-transparent">
                        Actualités+
                    </h3>
                    <p class="mt-4 text-gray-400">
                        Votre source d'information fiable et complète pour rester informé sur l'actualité française et internationale.
                    </p>
                    <div class="flex mt-6 space-x-6">
                        <a href="#" class="text-gray-400 hover:text-white">
                            <span class="sr-only">Facebook</span>
                            <i class="fab fa-facebook-f text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white">
                            <span class="sr-only">Twitter</span>
                            <i class="fab fa-twitter text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white">
                            <span class="sr-only">Instagram</span>
                            <i class="fab fa-instagram text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white">
                            <span class="sr-only">LinkedIn</span>
                            <i class="fab fa-linkedin-in text-xl"></i>
                        </a>
                    </div>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-300 tracking-wider uppercase">Navigation</h3>
                    <ul class="mt-4 space-y-4">
                        <li><a href="#" class="text-base text-gray-400 hover:text-white">Accueil</a></li>
                        <li><a href="#actualites" class="text-base text-gray-400 hover:text-white">Actualités</a></li>
                        <li><a href="#categories" class="text-base text-gray-400 hover:text-white">Catégories</a></li>
                        <li><a href="#" class="text-base text-gray-400 hover:text-white">À propos</a></li>
                        <li><a href="#" class="text-base text-gray-400 hover:text-white">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-300 tracking-wider uppercase">Légal</h3>
                    <ul class="mt-4 space-y-4">
                        <li><a href="#" class="text-base text-gray-400 hover:text-white">Mentions légales</a></li>
                        <li><a href="#" class="text-base text-gray-400 hover:text-white">Politique de confidentialité</a></li>
                        <li><a href="#" class="text-base text-gray-400 hover:text-white">Conditions d'utilisation</a></li>
                        <li><a href="#" class="text-base text-gray-400 hover:text-white">Politique des cookies</a></li>
                    </ul>
                </div>
            </div>
            <div class="mt-12 border-t border-gray-800 pt-8">
                <p class="text-base text-gray-400 text-center">
                    &copy; 2025 Actualités+. Tous droits réservés.
                </p>
            </div>
        </div>
    </footer>
</div>

<!-- Scripts -->
<script>
    // Animation des cartes au défilement
    document.addEventListener('DOMContentLoaded', function() {
        const animateOnScroll = function() {
            const elements = document.querySelectorAll('.card-hover');
            elements.forEach(element => {
                const elementTop = element.getBoundingClientRect().top;
                const windowHeight = window.innerHeight;
                
                if (elementTop < windowHeight - 100) {
                    element.style.opacity = '1';
                    element.style.transform = 'translateY(0)';
                }
            });
        };

        // Initialisation
        window.addEventListener('scroll', animateOnScroll);
        animateOnScroll(); // Pour les éléments déjà visibles au chargement

        // Gestion du menu mobile
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');
        
        if (mobileMenuButton && mobileMenu) {
            mobileMenuButton.addEventListener('click', function() {
                const expanded = this.getAttribute('aria-expanded') === 'true' || false;
                this.setAttribute('aria-expanded', !expanded);
                mobileMenu.classList.toggle('hidden');
            });
        }
    });
</script>

<style>
    /* Styles personnalisés */
    .gradient-text {
        background: linear-gradient(90deg, #3b82f6, #8b5cf6);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    
    .card-hover {
        transition: transform 0.3s ease, box-shadow 0.3s ease, opacity 0.5s ease;
        opacity: 0;
        transform: translateY(20px);
    }
    
    .card-hover.animated {
        opacity: 1;
        transform: translateY(0);
    }
    
    .category-active {
        @apply bg-blue-600 text-white;
    }
    
    /* Animation pour le bouton de défilement vers le haut */
    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% {transform: translateY(0);}
        40% {transform: translateY(-10px);}
        60% {transform: translateY(-5px);}
    }
    
    .bounce {
        animation: bounce 2s infinite;
    }
</style>

<!-- Bouton de retour en haut -->
<button onclick="window.scrollTo({top: 0, behavior: 'smooth'})" class="fixed bottom-8 right-8 bg-blue-600 text-white p-3 rounded-full shadow-lg hover:bg-blue-700 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
    </svg>
    <span class="sr-only">Retour en haut</span>
</button>

<!-- Barre de recherche -->
<div id="searchBar" class="hidden fixed top-20 left-0 right-0 bg-white shadow-md z-40 p-4">
    <div class="max-w-7xl mx-auto">
        <form action="{{ route('home') }}" method="GET" class="flex">
            <input type="text" 
                   name="search" 
                   value="{{ request('search') }}"
                   placeholder="Rechercher des articles..." 
                   class="flex-1 px-4 py-2 border border-gray-300 rounded-l-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-r-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-search"></i> Rechercher
            </button>
        </form>
    </div>
</div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 relative z-10">
            <div class="max-w-3xl mx-auto text-center">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold mb-6 leading-tight">
                    Restez informé en temps réel
                </h1>
                <p class="text-xl text-blue-100 mb-10 max-w-2xl mx-auto">
                    Découvrez les dernières actualités et restez à jour avec des informations fiables et pertinentes.
                </p>
                
                <!-- Barre de recherche améliorée -->
                <form action="{{ route('home') }}" method="GET" class="max-w-2xl mx-auto">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input 
                            type="text" 
                            name="search" 
            </div>
        </div>
    </div>

    <!-- Section Catégories -->
    <div id="categories" class="py-10 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Catégories</h2>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('home', ['categorie' => '']) }}" 
                   class="px-4 py-2 rounded-full border border-gray-300 text-gray-700 hover:bg-blue-600 hover:text-white hover:border-blue-600 transition-colors {{ !request('categorie') ? 'bg-blue-600 text-white border-blue-600' : '' }}">
                    Toutes
                </a>
                @foreach($categories as $categorie)
                    <a href="{{ route('home', ['categorie' => $categorie->id]) }}" 
                       class="px-4 py-2 rounded-full border border-gray-300 text-gray-700 hover:bg-blue-600 hover:text-white hover:border-blue-600 transition-colors {{ request('categorie') == $categorie->id ? 'bg-blue-600 text-white border-blue-600' : '' }}">
                        {{ $categorie->nom }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Dernières actualités -->
    <div id="actualites" class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                    <span class="block">Dernières actualités</span>
                </h2>
                @if(request('search'))
                    <p class="mt-3 max-w-2xl mx-auto text-xl text-gray-500">
                        Résultats de recherche pour : "{{ request('search') }}"
                    </p>
                @endif
            </div>

            @if($articles->count() > 0)
                <div id="articles-container" class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                    @include('partials.articles', ['articles' => $articles])
                </div>

                <!-- Pagination -->
                @if($articles->hasPages())
                    <div class="mt-12">
                        {{ $articles->appends(request()->query())->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-12">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-100 mb-4">
                        <i class="fas fa-newspaper text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun article trouvé</h3>
                    <p class="text-gray-500 mb-6">Désolé, nous n'avons trouvé aucun article correspondant à votre recherche.</p>
                    <a href="{{ route('home') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-home mr-2"></i> Retour à l'accueil
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Newsletter -->
    <div class="bg-gray-50 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto text-center">
                <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                    Restez informé
                </h2>
                <p class="mt-4 text-lg text-gray-500">
                    Abonnez-vous à notre newsletter pour recevoir les dernières actualités directement dans votre boîte mail.
                </p>
                <form class="mt-8 sm:flex max-w-xl mx-auto">
                    <div class="w-full">
                        <label for="email-address" class="sr-only">Adresse email</label>
                        <input id="email-address" name="email" type="email" autocomplete="email" required class="w-full px-5 py-3 border border-gray-300 shadow-sm placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:max-w-xs rounded-full" placeholder="Votre adresse email">
                    </div>
                    <div class="mt-3 rounded-md shadow sm:mt-0 sm:ml-3 sm:flex-shrink-0">
                        <button type="submit" class="w-full flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-full text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            S'inscrire
                        </button>
                    </div>
                </form>
                <p class="mt-3 text-sm text-gray-500">
                    Nous respectons votre vie privée. Désinscription à tout moment.
                </p>
            </div>
        </div>
    </div>

    <!-- Pied de page -->
    <footer class="bg-gray-900 text-white">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:py-16 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div class="col-span-2">
                    <h3 class="text-2xl font-bold gradient-text mb-4">Actualités+</h3>
                    <p class="text-gray-400 text-sm">
                        Votre source d'information fiable et à jour sur les dernières actualités et tendances à travers le monde.
                    </p>
                    <div class="flex space-x-4 mt-6">
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">
                            <span class="sr-only">Facebook</span>
                            <i class="fab fa-facebook-f text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">
                            <span class="sr-only">Twitter</span>
                            <i class="fab fa-twitter text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">
                            <span class="sr-only">Instagram</span>
                            <i class="fab fa-instagram text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">
                            <span class="sr-only">LinkedIn</span>
                            <i class="fab fa-linkedin-in text-xl"></i>
                        </a>
                    </div>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-gray-300 uppercase tracking-wider mb-4">Navigation</h4>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Accueil</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Actualités</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Catégories</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">À propos</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-gray-300 uppercase tracking-wider mb-4">Légal</h4>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Mentions légales</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Politique de confidentialité</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Conditions d'utilisation</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Politique des cookies</a></li>
                    </ul>
                </div>
            </div>
            <div class="mt-12 pt-8 border-t border-gray-800">
                <p class="text-center text-gray-400 text-sm">
                    &copy; {{ date('Y') }} Actualités+. Tous droits réservés.
                </p>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Animation au défilement
            const animateCards = () => {
                const cards = document.querySelectorAll('.card-hover:not(.animated)');
                const cardObserver = new IntersectionObserver((entries, observer) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.style.opacity = '1';
                            entry.target.style.transform = 'translateY(0)';
                            entry.target.classList.add('animated');
                            observer.unobserve(entry.target);
                        }
                    });
                }, { threshold: 0.1 });

                cards.forEach((card, index) => {
                    if (!card.classList.contains('animated')) {
                        card.style.opacity = '0';
                        card.style.transform = 'translateY(20px)';
                        card.style.transition = `opacity 0.5s ease ${index * 0.1}s, transform 0.5s ease ${index * 0.1}s`;
                        cardObserver.observe(card);
                    }
                });
            };

            // Initialiser les animations
            animateCards();

            // Toggle de la barre de recherche
            const searchToggle = document.getElementById('searchToggle');
            const searchBar = document.getElementById('searchBar');
            const searchForm = searchBar ? searchBar.querySelector('form') : null;
            
            if (searchToggle && searchBar) {
                searchToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    searchBar.classList.toggle('hidden');
                    if (!searchBar.classList.contains('hidden')) {
                        searchBar.querySelector('input').focus();
                    }
                });

                // Fermer la barre de recherche en cliquant à l'extérieur
                document.addEventListener('click', function(event) {
                    if (!searchBar.contains(event.target) && event.target !== searchToggle && !searchToggle.contains(event.target)) {
                        searchBar.classList.add('hidden');
                    }
                });

                // Soumission du formulaire de recherche
                if (searchForm) {
                    searchForm.addEventListener('submit', function(e) {
                        if (searchForm.querySelector('input').value.trim() === '') {
                            e.preventDefault();
                        }
                    });
                }
            }

            // Gestion du chargement automatique pour la pagination infinie
            let isLoading = false;
            let nextPageUrl = null;
            const articlesContainer = document.getElementById('articles-container');
            
            const loadMoreArticles = () => {
                if (isLoading || !nextPageUrl) return;
                
                isLoading = true;
                
                // Afficher un indicateur de chargement
                const loadingIndicator = document.createElement('div');
                loadingIndicator.className = 'col-span-3 flex justify-center py-8';
                loadingIndicator.innerHTML = `
                    <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500"></div>
                `;
                articlesContainer.after(loadingIndicator);
                
                fetch(`${nextPageUrl}&ajax=1`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.html) {
                        // Ajouter les nouveaux articles
                        const temp = document.createElement('div');
                        temp.innerHTML = data.html;
                        
                        const newArticles = temp.querySelector('#articles-container');
                        if (newArticles) {
                            articlesContainer.insertAdjacentHTML('beforeend', newArticles.innerHTML);
                            
                            // Mettre à jour l'URL de la page suivante
                            const nextLink = temp.querySelector('.pagination a[rel="next"]');
                            nextPageUrl = nextLink ? nextLink.href : null;
                            
                            // Animer les nouveaux articles
                            animateCards();
                        }
                    }
                    
                    // Supprimer l'indicateur de chargement
                    loadingIndicator.remove();
                    isLoading = false;
                })
                .catch(error => {
                    console.error('Erreur lors du chargement des articles:', error);
                    loadingIndicator.remove();
                    isLoading = false;
                });
            };
            
            // Observer l'intersection avec le bas de la page pour charger plus d'articles
            const observer = new IntersectionObserver((entries) => {
                if (entries[0].isIntersecting && !isLoading && nextPageUrl) {
                    loadMoreArticles();
                }
            }, { threshold: 0.1 });
            
            // Initialiser l'observateur si on a une pagination
            const pagination = document.querySelector('.pagination');
            if (pagination) {
                const nextLink = pagination.querySelector('a[rel="next"]');
                if (nextLink) {
                    nextPageUrl = nextLink.href;
                    observer.observe(pagination);
                }
            }
            
            // Gérer les changements d'URL avec la navigation AJAX
            window.addEventListener('popstate', function() {
                // Recharger la page pour refléter les changements d'URL
                window.location.reload();
            });
        });
    </script>
</body>
</html>
