@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- En-tête de l'auteur -->
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="md:flex md:items-center md:justify-between">
                <div class="flex-1 min-w-0">
                    <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                        Articles de {{ $author->name }}
                    </h2>
                    <div class="mt-1 flex flex-col sm:flex-row sm:flex-wrap sm:mt-0 sm:space-x-6">
                        <div class="mt-2 flex items-center text-sm text-gray-500">
                            <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                            </svg>
                            {{ $author->bio ?? 'Aucune biographie disponible' }}
                        </div>
                        @if($author->website)
                        <div class="mt-2 flex items-center text-sm text-gray-500">
                            <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M12.586 4.586a2 2 0 112.828 2.828l-3 3a2 2 0 01-2.828 0 1 1 0 00-1.414 1.414 1 1 0 001.414.001 4 4 0 015.656 0l3-3a4 4 0 00-5.656-5.656l-1.5 1.5a1 1 0 101.414 1.414l1.5-1.5zm-5 5a2 2 0 012.828 0 1 1 0 001.414-1.414 4 4 0 00-5.656 0l-3 3a4 4 0 105.656 5.656l1.5-1.5a1 1 0 10-1.414-1.414l-1.5 1.5a2 2 0 11-2.828-2.828l3-3z" clip-rule="evenodd" />
                            </svg>
                            <a href="{{ $author->website }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                {{ parse_url($author->website, PHP_URL_HOST) }}
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="mt-4 flex md:mt-0 md:ml-4">
                    <div class="flex items-center">
                        <div class="h-16 w-16 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden">
                            @if($author->profile_photo_path)
                                <img src="{{ asset('storage/' . $author->profile_photo_path) }}" alt="{{ $author->name }}" class="h-full w-full object-cover">
                            @else
                                <span class="text-2xl font-bold text-gray-600">{{ strtoupper(substr($author->name, 0, 1)) }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des articles -->
    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3" id="articles-container">
                @forelse($articles as $article)
                    <div class="flex flex-col overflow-hidden rounded-lg shadow-lg bg-white card-hover">
                        <div class="flex-shrink-0">
                            <img class="h-48 w-full object-cover" src="{{ $article->image ? asset('storage/' . $article->image) : 'https://via.placeholder.com/800x450?text=Image+non+disponible' }}" alt="{{ $article->titre }}">
                        </div>
                        <div class="flex-1 p-6 flex flex-col justify-between">
                            <div class="flex-1">
                                <p class="text-sm font-medium {{ $article->categorie ? 'text-' . $article->categorie->couleur . '-600' : 'text-gray-600' }}">
                                    <a href="{{ $article->categorie ? route('categorie.show', ['categorie' => $article->categorie->slug]) : '#' }}" class="hover:underline">
                                        {{ $article->categorie->nom ?? 'Non classé' }}
                                    </a>
                                </p>
                                <a href="{{ route('articles.show', $article->slug) }}" class="block mt-2">
                                    <h3 class="text-xl font-semibold text-gray-900">
                                        {{ $article->titre }}
                                    </h3>
                                    <p class="mt-3 text-base text-gray-500">
                                        {{ $article->resume ?? Str::limit(strip_tags($article->contenu), 120) }}
                                    </p>
                                </a>
                            </div>
                            <div class="mt-6 flex items-center">
                                <div class="flex-shrink-0">
                                    <span class="sr-only">{{ $article->auteur->name ?? 'Auteur inconnu' }}</span>
                                    <img class="h-10 w-10 rounded-full" src="{{ $article->auteur->profile_photo_path ?? 'https://ui-avatars.com/api/?name=' . urlencode($article->auteur->name ?? 'A') . '&color=7F9CF5&background=EBF4FF' }}" alt="{{ $article->auteur->name ?? 'Auteur inconnu' }}">
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">
                                        <a href="{{ route('auteur.show', $article->auteur->id) }}" class="hover:underline">
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
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun article trouvé</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            {{ $author->name }} n'a pas encore publié d'articles.
                        </p>
                    </div>
                @endforelse
            </div>

            @if($articles->hasPages())
                <div class="mt-8">
                    {{ $articles->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
