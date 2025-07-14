@extends('layouts.editor')

@section('content')
<div class="py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <!-- En-tête de l'article -->
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">{{ $article->titre }}</h1>
                        <div class="mt-2 flex items-center text-sm text-gray-500">
                            <span>Publié le {{ $article->date_publication->format('d/m/Y à H:i') }}</span>
                            <span class="mx-2">•</span>
                            <span>Par {{ $article->auteur->name }}</span>
                            <span class="mx-2">•</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $article->est_publie ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $article->est_publie ? 'Publié' : 'Brouillon' }}
                            </span>
                        </div>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('editor.articles.edit', $article->id) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-900 focus:ring focus:ring-blue-300 disabled:opacity-25 transition">
                            Modifier
                        </a>
                        <form action="{{ route('editor.articles.toggle-publish', $article) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="inline-flex items-center px-4 py-2 {{ $article->est_publie ? 'bg-yellow-600 hover:bg-yellow-700' : 'bg-green-600 hover:bg-green-700' }} border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                                {{ $article->est_publie ? 'Dépublier' : 'Publier' }}
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Image de l'article -->
                @if($article->image)
                    <div class="mb-6">
                        <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->titre }}" class="w-full h-auto rounded-lg shadow-md">
                    </div>
                @endif

                <!-- Catégorie -->
                <div class="mb-6">
                    <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">
                        {{ $article->categorie->nom }}
                    </span>
                </div>

                <!-- Résumé -->
                @if($article->resume)
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                        <p class="text-gray-700 italic">{{ $article->resume }}</p>
                    </div>
                @endif

                <!-- Contenu de l'article -->
                <div class="prose max-w-none">
                    {!! $article->contenu !!}
                </div>

                <!-- Bouton de retour -->
                <div class="mt-8">
                    <a href="{{ route('editor.dashboard') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-800">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Retour au tableau de bord
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
