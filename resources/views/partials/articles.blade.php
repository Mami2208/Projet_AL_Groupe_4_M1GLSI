@foreach($articles as $article)
    @php
        $couleur = $article->categorie->couleur ?? 'blue';
        $couleur_secondaire = $article->categorie->couleur_secondaire ?? 'indigo';
        $hasImage = !empty($article->image) && file_exists(public_path('storage/' . $article->image));
        $categorieNom = $article->categorie->nom ?? 'news';
    @endphp
    <div class="bg-white rounded-xl shadow-md overflow-hidden card-hover">
        <div class="h-48 bg-gradient-to-r from-{{ $couleur }}-400 to-{{ $couleur_secondaire }}-500 relative overflow-hidden">
            @if($hasImage)
                <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->titre }}" class="w-full h-full object-cover">
            @else
                <div class="absolute inset-0 bg-gradient-to-r from-{{ $couleur }}-400 to-{{ $couleur_secondaire }}-500"></div>
                <div class="absolute inset-0 flex items-center justify-center">
                    <span class="text-white text-4xl font-bold opacity-50">{{ substr($article->titre, 0, 1) }}</span>
                </div>
            @endif
            
            <div class="absolute top-3 right-3">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-white bg-opacity-90 text-gray-800 shadow">
                    @if($article->categorie)
                        {{ $categorieNom }}
                    @else
                        Non classé
                    @endif
                </span>
            </div>
        </div>
        <div class="p-6">
            <div class="flex items-center text-sm text-gray-500 mb-2">
                <span><i class="far fa-calendar-alt mr-1"></i> {{ $article->date_publication->format('d M Y') }}</span>
                <span class="mx-2">•</span>
                <span><i class="far fa-clock mr-1"></i> {{ ceil(str_word_count(strip_tags($article->contenu)) / 200) }} min de lecture</span>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">
                <a href="{{ route('articles.show', $article) }}" class="hover:text-blue-600 transition-colors">
                    {{ $article->titre }}
                </a>
            </h3>
            <p class="text-gray-600 mb-4">
                {{ Str::limit(strip_tags($article->resume ?? $article->contenu), 150) }}
            </p>
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 mr-2">
                        <i class="fas fa-user text-sm"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-900">
                        {{ $article->auteur->name ?? 'Auteur inconnu' }}
                    </span>
                </div>
                <a href="{{ route('articles.show', $article) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Lire la suite →</a>
            </div>
        </div>
    </div>
@endforeach
