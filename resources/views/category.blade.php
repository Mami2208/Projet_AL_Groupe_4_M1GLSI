@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- En-tête de la catégorie -->
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-12 w-12 rounded-md flex items-center justify-center bg-{{ $categorie->couleur ?? 'blue' }}-100">
                    @php
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
                    <i class="fas {{ $icone }} text-{{ $categorie->couleur ?? 'blue' }}-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h1 class="text-3xl font-bold text-gray-900">{{ $categorie->nom }}</h1>
                    @if($categorie->description)
                        <p class="mt-1 text-gray-600">{{ $categorie->description }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des articles -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($articles->count() > 0)
                <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                    @foreach($articles as $article)
                        <div class="flex flex-col overflow-hidden rounded-lg shadow-lg bg-white hover:shadow-xl transition-shadow duration-300">
                            @if($article->image)
                                <div class="flex-shrink-0">
                                    <img class="h-48 w-full object-cover" src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->titre }}">
                                </div>
                            @endif
                            <div class="flex-1 p-6 flex flex-col justify-between">
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-{{ $article->categorie->couleur ?? 'blue' }}-600">
                                        {{ $article->categorie->nom ?? 'Non classé' }}
                                    </p>
                                    <a href="{{ route('articles.show', $article->slug) }}" class="block mt-2">
                                        <h3 class="text-xl font-semibold text-gray-900">{{ $article->titre }}</h3>
                                        <p class="mt-3 text-base text-gray-500">
                                            {{ $article->resume ?? Str::limit(strip_tags($article->contenu), 150) }}
                                        </p>
                                    </a>
                                </div>
                                <div class="mt-6 flex items-center">
                                    <div class="flex-shrink-0">
                                        <img class="h-10 w-10 rounded-full" 
                                             src="{{ $article->auteur->profile_photo_path ?? 'https://ui-avatars.com/api/?name=' . urlencode($article->auteur->name ?? 'A') . '&color=7F9CF5&background=EBF4FF' }}" 
                                             alt="{{ $article->auteur->name ?? 'Auteur inconnu' }}">
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ $article->auteur->name ?? 'Auteur inconnu' }}
                                        </p>
                                        <div class="flex space-x-1 text-sm text-gray-500">
                                            <time datetime="{{ $article->date_publication->format('Y-m-d') }}">
                                                {{ $article->date_publication->translatedFormat('j F Y') }}
                                            </time>
                                            <span aria-hidden="true">·</span>
                                            <span>{{ ceil(str_word_count(strip_tags($article->contenu)) / 200) }} min de lecture</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                <div class="mt-8">
                    {{ $articles->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun article disponible</h3>
                    <p class="mt-1 text-sm text-gray-500">Aucun article n'a été publié dans cette catégorie pour le moment.</p>
                    <div class="mt-6">
                        <a href="{{ route('home') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            Retour à l'accueil
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
