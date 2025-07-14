@extends('layouts.admin')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="text-2xl font-semibold text-gray-800 mb-6">Modifier la catégorie</h2>
                
                <form action="{{ route('categories.update', $categorie) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label for="nom" class="block text-sm font-medium text-gray-700">Nom de la catégorie *</label>
                        <input type="text" name="nom" id="nom" value="{{ old('nom', $categorie->nom) }}" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('nom')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" id="description" rows="3"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('description', $categorie->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="flex items-center justify-between mt-6">
                        <a href="{{ route('categories.index') }}" class="text-gray-600 hover:text-gray-900">
                            Annuler
                        </a>
                        <div class="space-x-3">
                            <a href="{{ route('categories.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Annuler
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Enregistrer les modifications
                            </button>
                        </div>
                    </div>
                </form>
                
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <h3 class="text-lg font-medium text-red-700">Zone dangereuse</h3>
                    <p class="mt-2 text-sm text-gray-600">
                        La suppression d'une catégorie est irréversible. Assurez-vous qu'aucun article n'est associé à cette catégorie avant de la supprimer.
                    </p>
                    <form action="{{ route('categories.destroy', $categorie) }}" method="POST" class="mt-4" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer définitivement cette catégorie ? Cette action est irréversible.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-800 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                            Supprimer définitivement cette catégorie
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
