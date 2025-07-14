@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">{{ $article->titre }}</h1>
        <div class="flex space-x-2">
            <a href="{{ route('admin.articles.edit', $article) }}" 
               class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                Éditer
            </a>
            <form action="{{ route('admin.articles.destroy', $article) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded"
                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?')">
                    Supprimer
                </button>
            </form>
            <a href="{{ route('admin.articles.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                Retour
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        @if($article->image)
            <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->titre }}" 
                 class="w-full h-64 object-cover">
        @endif
        
        <div class="p-6">
            <div class="flex items-center mb-4">
                <span class="px-3 py-1 text-sm font-semibold rounded-full 
                    bg-{{ $article->categorie->couleur ?? 'gray' }}-100 text-{{ $article->categorie->couleur ?? 'gray' }}-800">
                    {{ $article->categorie->nom }}
                </span>
                <span class="ml-4 text-sm text-gray-500">
                    Par {{ $article->user->name }} - {{ $article->created_at->format('d/m/Y') }}
                </span>
                @if($article->est_publie)
                    <span class="ml-4 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                        Publié
                    </span>
                @else
                    <span class="ml-4 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                        Brouillon
                    </span>
                @endif
            </div>

            <div class="prose max-w-none">
                {!! $article->contenu !!}
            </div>
        </div>
    </div>
</div>
@endsection
