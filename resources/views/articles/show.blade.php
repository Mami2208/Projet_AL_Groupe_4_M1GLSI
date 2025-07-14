@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <article class="bg-white shadow-sm rounded-lg overflow-hidden">
            <!-- Image de l'article -->
            @if($article->image)
                <div class="h-64 md:h-96 overflow-hidden">
                    <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->titre }}" class="w-full h-full object-cover">
                </div>
            @endif
            
            <!-- En-tête de l'article -->
            <div class="p-6 md:p-8">
                <div class="flex items-center text-sm text-gray-500 mb-2">
                    <a href="{{ route('home', ['categorie' => $article->categorie_id]) }}" class="text-indigo-600 hover:text-indigo-800">
                        {{ $article->categorie->nom }}
                    </a>
                    <span class="mx-2">•</span>
                    <time datetime="{{ $article->date_publication->toDateString() }}">
                        {{ $article->date_publication->format('d/m/Y à H:i') }}
                    </time>
                </div>
                
                <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $article->titre }}</h1>
                
                <!-- Auteur -->
                <div class="flex items-center mb-6">
                    <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-medium text-lg">
                        {{ Str::substr($article->auteur->name, 0, 1) }}
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">{{ $article->auteur->name }}</p>
                        <p class="text-sm text-gray-500">Auteur</p>
                    </div>
                </div>
                
                <!-- Contenu de l'article -->
                <div class="prose max-w-none">
                    {!! $article->contenu !!}
                </div>
                
                <!-- Bouton de retour -->
                <div class="mt-8">
                    <a href="{{ route('home') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-800">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Retour aux articles
                    </a>
                </div>
            </div>
        </article>
        
        <!-- Articles similaires -->
        @if($articlesSimilaires->count() > 0)
            <div class="mt-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Articles similaires</h2>
                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    @foreach($articlesSimilaires as $articleSimilaire)
                        <div class="bg-white overflow-hidden shadow-sm rounded-lg hover:shadow-md transition-shadow duration-300">
                            @if($articleSimilaire->image)
                                <div class="h-40 overflow-hidden">
                                    <img src="{{ asset('storage/' . $articleSimilaire->image) }}" alt="{{ $articleSimilaire->titre }}" class="w-full h-full object-cover">
                                </div>
                            @endif
                            <div class="p-4">
                                <div class="text-sm text-indigo-600 font-medium mb-1">
                                    {{ $articleSimilaire->categorie->nom }}
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">
                                    <a href="{{ route('articles.show', $articleSimilaire) }}" class="hover:text-indigo-600 transition-colors duration-200">
                                        {{ $articleSimilaire->titre }}
                                    </a>
                                </h3>
                                <p class="text-gray-600 text-sm">
                                    {{ Str::limit($articleSimilaire->resume ?? Str::limit(strip_tags($articleSimilaire->contenu), 100), 100) }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
