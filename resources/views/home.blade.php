@extends('layouts.app')
@php
    use Illuminate\Support\Facades\Storage;
@endphp

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
            <img class="h-56 w-full object-cover sm:h-72 md:h-96 lg:w-full lg:h-full" src="{{ asset('images/actu.jpg') }}" alt="Femme sénégalaise souriante">
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

            <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3 articles-container">
                @forelse($articles as $article)
                    <div class="flex flex-col overflow-hidden rounded-lg shadow-lg bg-white card-hover">
                        <div class="flex-shrink-0">
                            @if($article->image && Storage::exists('public/' . $article->image))
                                <img class="h-48 w-full object-cover" src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->titre }}">
                            @else
                                <img class="h-48 w-full object-cover" src="https://via.placeholder.com/800x450?text=Image+non+disponible" alt="Image non disponible">
                            @endif
                        </div>
                        <div class="flex-1 p-6 flex flex-col justify-between">
                            <div class="flex-1">
                                <p class="text-sm font-medium {{ $article->categorie ? 'text-' . $article->categorie->couleur . '-600' : 'text-gray-600' }}">
                                    <a href="{{ $article->categorie ? route('categorie.show', ['categorie' => $article->categorie->slug]) : '#' }}" class="hover:underline">
                                        {{ $article->categorie->nom ?? 'Non classé' }}
                                    </a>
                                </p>
                                <a href="{{ route('articles.show', $article->slug) }}" class="block mt-2">
                                    <h3 class="text-xl font-semibold text-gray-900 hover:text-blue-600 transition-colors">
                                        {{ $article->titre }}
                                    </h3>
                                    <div class="mt-3 text-base text-gray-500 article-content">
                                        {!! Str::limit(strip_tags($article->contenu), 200) !!}
                                    </div>
                                    <div class="mt-2">
                                        <a href="{{ route('articles.show', $article->slug) }}" class="text-blue-600 hover:text-blue-800 font-medium text-sm inline-flex items-center">
                                            Lire la suite
                                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </a>
                            </div>
                            <div class="mt-6 flex items-center">
                                <div class="flex-shrink-0">
                                    <span class="sr-only">{{ $article->auteur->name ?? 'Auteur inconnu' }}</span>
                                    <img class="h-10 w-10 rounded-full" src="{{ $article->auteur->profile_photo_path ?? 'https://ui-avatars.com/api/?name=' . urlencode($article->auteur->name ?? 'A') . '&color=7F9CF5&background=EBF4FF' }}" alt="{{ $article->auteur->name ?? 'Auteur inconnu' }}">
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">
                                        <a href="{{ route('auteur.show', $article->auteur->username ?? 'auteur') }}" class="hover:underline">
                                            {{ $article->auteur->name ?? 'Auteur inconnu' }}
                                        </a>
                                    </p>
                                    <div class="flex space-x-1 text-sm text-gray-500">
                                        <time datetime="{{ $article->date_publication->format('Y-m-d') }}">
                                            {{ $article->date_publication->translatedFormat('j F Y') }}
                                        </time>
                                        <span aria-hidden="true">&middot;</span>
                                        <span>
                                            {{ ceil(str_word_count(strip_tags($article->contenu)) / 200) }} min de lecture
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h3 class="mt-2 text-lg font-medium text-gray-900">Aucun article trouvé</h3>
                        <p class="mt-1 text-gray-500">Il n'y a pas encore d'articles publiés pour le moment.</p>
                        @auth
                            @can('create', App\Models\Article::class)
                                <div class="mt-6">
                                    <a href="{{ route('articles.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                                        </svg>
                                        Créer un article
                                    </a>
                                </div>
                            @endcan
                        @endauth
                    </div>
                @endforelse
            </div>
            
            @if($articles->hasPages())
                <div class="mt-12 flex justify-center pagination-container">
                    <nav class="flex items-center space-x-2">
                        {{-- Bouton Précédent --}}
                        @if ($articles->onFirstPage())
                            <span class="px-4 py-2 border border-gray-300 rounded-md text-gray-400 cursor-not-allowed">
                                &laquo; Précédent
                            </span>
                        @else
                            <a href="{{ $articles->previousPageUrl() }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors">
                                &laquo; Précédent
                            </a>
                        @endif

                        {{-- Numéros de page --}}
                        @foreach ($articles->getUrlRange(1, $articles->lastPage()) as $page => $url)
                            @if ($page == $articles->currentPage())
                                <span class="px-4 py-2 border border-blue-500 bg-blue-50 text-blue-600 font-medium rounded-md">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach

                        {{-- Bouton Suivant --}}
                        @if ($articles->hasMorePages())
                            <a href="{{ $articles->nextPageUrl() }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors">
                                Suivant &raquo;
                            </a>
                        @else
                            <span class="px-4 py-2 border border-gray-300 rounded-md text-gray-400 cursor-not-allowed">
                                Suivant &raquo;
                            </span>
                        @endif
                    </nav>
                </div>

                {{-- Bouton Voir plus (chargement infini) --}}
                <div class="mt-8 text-center">
                    @if ($articles->hasMorePages())
                        <a href="{{ $articles->nextPageUrl() }}" class="load-more-button inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-300">
                            Voir plus d'articles
                            <svg class="ml-2 -mr-1 w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    @else
                        <p class="text-gray-500">Vous avez atteint la fin des articles</p>
                    @endif
                </div>
            @endif
            
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
        <!-- Débogage -->
        @if(app()->environment('local'))
            <div class="max-w-7xl mx-auto px-4 mb-8 p-4 bg-yellow-50 border-l-4 border-yellow-400">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            <strong>Débogage :</strong> {{ count($categories ?? []) }} catégories trouvées.
                            @if(isset($categories) && count($categories) > 0)
                                <br>Première catégorie : {{ $categories[0]->name ?? 'Non définie' }}
                                <br>Route categorie.show : {{ route('categorie.show', ['categorie' => $categories[0]->slug ?? 'test']) }}
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        @endif
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                    Parcourir par catégorie
                </h2>
                <p class="mt-3 max-w-2xl mx-auto text-xl text-gray-500 sm:mt-4">
                    Découvrez nos articles par thème d'actualité.
                </p>
            </div>

            <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-4 mt-8">
                @forelse($categories as $categorie)
                    @php
                        // Définir des couleurs et icônes par défaut
                        $couleurs = [
                            'Politique' => ['bg-red-100', 'text-red-600', 'border-red-500'],
                            'Environnement' => ['bg-green-100', 'text-green-600', 'border-green-500'],
                            'Technologie' => ['bg-blue-100', 'text-blue-600', 'border-blue-500'],
                            'Santé' => ['bg-purple-100', 'text-purple-600', 'border-purple-500'],
                            'Économie' => ['bg-yellow-100', 'text-yellow-600', 'border-yellow-500'],
                            'Sports' => ['bg-indigo-100', 'text-indigo-600', 'border-indigo-500'],
                            'Culture' => ['bg-pink-100', 'text-pink-600', 'border-pink-500'],
                            'International' => ['bg-teal-100', 'text-teal-600', 'border-teal-500'],
                            'default' => ['bg-gray-100', 'text-gray-600', 'border-gray-500'],
                        ];
                        
                        $couleur = $couleurs[$categorie->nom] ?? $couleurs['default'];
                        
                        $icones = [
                            'Politique' => 'fa-landmark',
                            'Environnement' => 'fa-leaf',
                            'Technologie' => 'fa-laptop-code',
                            'Santé' => 'fa-heartbeat',
                            'Économie' => 'fa-chart-line',
                            'Sports' => 'fa-futbol',
                            'Culture' => 'fa-palette',
                            'International' => 'fa-globe-americas',
                        ];
                        $icone = $icones[$categorie->nom] ?? 'fa-newspaper';
                    @endphp
                    <a href="{{ route('categorie.show', ['categorie' => $categorie->slug]) }}" class="relative p-6 border border-gray-200 rounded-lg hover:border-{{ explode('-', $couleur[2])[1] }} transition-colors group">
                        <div class="flex items-center justify-center h-12 w-12 rounded-md {{ $couleur[0] }} {{ $couleur[1] }} mb-4">
                            <i class="fas {{ $icone }} text-xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">{{ $categorie->nom }}</h3>
                        <p class="text-gray-500">{{ $categorie->description ?? 'Découvrez nos articles sur ce sujet' }}</p>
                        <span class="absolute top-4 right-4 text-gray-400 group-hover:text-{{ explode('-', $couleur[2])[1] }}">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </span>
                    </a>
                @empty
                    <div class="col-span-4 text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune catégorie disponible</h3>
                        <p class="mt-1 text-sm text-gray-500">Les catégories apparaîtront ici une fois créées.</p>
                    </div>
                @endforelse
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

<!-- Bouton de retour en haut -->
<button onclick="window.scrollTo({top: 0, behavior: 'smooth'})" id="back-to-top" class="fixed bottom-8 right-8 bg-blue-600 text-white p-3 rounded-full shadow-lg hover:bg-blue-700 transition-all duration-300 transform opacity-0 translate-y-4 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
    </svg>
</button>

@push('scripts')
<script>
    // Afficher/masquer le bouton de retour en haut
    const backToTopButton = document.getElementById('back-to-top');
    
    window.addEventListener('scroll', () => {
        if (window.pageYOffset > 300) {
            backToTopButton.classList.remove('opacity-0', 'translate-y-4');
            backToTopButton.classList.add('opacity-100', 'translate-y-0');
        } else {
            backToTopButton.classList.remove('opacity-100', 'translate-y-0');
            backToTopButton.classList.add('opacity-0', 'translate-y-4');
        }
    });

    // Animation de défilement fluide pour les ancres
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                e.preventDefault();
                targetElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Chargement en douceur des pages de pagination
    document.addEventListener('DOMContentLoaded', function() {
        const loadMoreButton = document.querySelector('.load-more-button');
        const articlesContainer = document.querySelector('.articles-container');
        let isLoading = false;

        if (loadMoreButton) {
            loadMoreButton.addEventListener('click', function(e) {
                e.preventDefault();
                
                if (isLoading) return;
                
                const nextPageUrl = this.getAttribute('href');
                if (!nextPageUrl) return;
                
                isLoading = true;
                this.classList.add('opacity-75', 'cursor-not-allowed');
                this.innerHTML = 'Chargement...';
                
                fetch(nextPageUrl)
                    .then(response => response.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const newArticles = doc.querySelector('.articles-container').innerHTML;
                        const newPagination = doc.querySelector('.pagination-container');
                        
                        // Ajouter les nouveaux articles avec une animation
                        const tempDiv = document.createElement('div');
                        tempDiv.innerHTML = newArticles;
                        const articles = tempDiv.querySelectorAll('.card-hover');
                        
                        articles.forEach(article => {
                            article.classList.add('opacity-0', 'translate-y-6');
                            articlesContainer.appendChild(article);
                            
                            // Animation d'entrée
                            setTimeout(() => {
                                article.classList.add('transition-all', 'duration-500', 'ease-out');
                                article.classList.remove('opacity-0', 'translate-y-6');
                            }, 50);
                        });
                        
                        // Mettre à jour la pagination
                        if (newPagination) {
                            document.querySelector('.pagination-container').outerHTML = newPagination.outerHTML;
                        } else {
                            document.querySelector('.pagination-container').remove();
                        }
                        
                        // Faire défiler vers le haut des nouveaux articles
                        const firstNewArticle = articles[0];
                        if (firstNewArticle) {
                            firstNewArticle.scrollIntoView({ behavior: 'smooth', block: 'start' });
                        }
                        
                        // Réinitialiser le bouton
                        isLoading = false;
                        this.classList.remove('opacity-75', 'cursor-not-allowed');
                        this.innerHTML = 'Voir plus d\'articles <svg class="ml-2 -mr-1 w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" /></svg>';
                    })
                    .catch(error => {
                        console.error('Erreur lors du chargement des articles:', error);
                        isLoading = false;
                        this.classList.remove('opacity-75', 'cursor-not-allowed');
                        this.innerHTML = 'Voir plus d\'articles';
                    });
            });
        }
    });
</script>

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

        // Gestion de la barre de recherche
        const searchToggle = document.getElementById('searchToggle');
        const searchBar = document.getElementById('searchBar');
        
        if (searchToggle && searchBar) {
            searchToggle.addEventListener('click', function(e) {
                e.preventDefault();
                searchBar.classList.toggle('hidden');
            });
            
            // Fermer la barre de recherche en cliquant en dehors
            document.addEventListener('click', function(event) {
                if (!searchBar.contains(event.target) && event.target !== searchToggle && !searchToggle.contains(event.target)) {
                    searchBar.classList.add('hidden');
                }
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
@endpush

@endsection
