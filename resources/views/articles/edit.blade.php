@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="text-2xl font-semibold text-gray-800 mb-6">Modifier l'article</h2>
                
                <form action="{{ route('articles.update', $article) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label for="titre" class="block text-sm font-medium text-gray-700">Titre *</label>
                        <input type="text" name="titre" id="titre" value="{{ old('titre', $article->titre) }}" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('titre')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="categorie_id" class="block text-sm font-medium text-gray-700">Catégorie *</label>
                        <select name="categorie_id" id="categorie_id" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @foreach($categories as $categorie)
                                <option value="{{ $categorie->id }}" {{ (old('categorie_id', $article->categorie_id) == $categorie->id) ? 'selected' : '' }}>
                                    {{ $categorie->nom }}
                                </option>
                            @endforeach
                        </select>
                        @error('categorie_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="resume" class="block text-sm font-medium text-gray-700">Résumé</label>
                        <textarea name="resume" id="resume" rows="3"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('resume', $article->resume) }}</textarea>
                        @error('resume')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="contenu" class="block text-sm font-medium text-gray-700">Contenu *</label>
                        <textarea name="contenu" id="contenu" rows="10" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('contenu', $article->contenu) }}</textarea>
                        @error('contenu')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="image" class="block text-sm font-medium text-gray-700">Image d'en-tête</label>
                        @if($article->image)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $article->image) }}" alt="Image actuelle" class="h-32 object-cover rounded">
                                <label class="mt-2 flex items-center">
                                    <input type="checkbox" name="remove_image" id="remove_image" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-600">Supprimer l'image actuelle</span>
                                </label>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">Ou téléchargez une nouvelle image :</p>
                        @endif
                        <input type="file" name="image" id="image" accept="image/*"
                            class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        @error('image')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="est_publie" id="est_publie" value="1"
                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                {{ old('est_publie', $article->est_publie) ? 'checked' : '' }}>
                            <span class="ml-2 text-sm text-gray-600">Publier l'article</span>
                        </label>
                    </div>
                    
                    <div class="flex items-center justify-between mt-6">
                        <a href="{{ route('articles.index') }}" class="text-gray-600 hover:text-gray-900">
                            Annuler
                        </a>
                        <div class="space-x-3">
                            <button type="submit" name="action" value="save" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Enregistrer les modifications
                            </button>
                            <button type="submit" name="action" value="publish" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-800 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Publier maintenant
                            </button>
                        </div>
                    </div>
                </form>
                
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <h3 class="text-lg font-medium text-red-700">Zone dangereuse</h3>
                    <p class="mt-2 text-sm text-gray-600">
                        La suppression d'un article est irréversible. Soyez certain de votre action.
                    </p>
                    <form action="{{ route('articles.destroy', $article) }}" method="POST" class="mt-4" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer définitivement cet article ? Cette action est irréversible.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-800 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                            Supprimer définitivement cet article
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/35.0.1/classic/ckeditor.js"></script>
<script>
    ClassicEditor
        .create(document.querySelector('#contenu'))
        .catch(error => {
            console.error(error);
        });
</script>
@endpush
@endsection
