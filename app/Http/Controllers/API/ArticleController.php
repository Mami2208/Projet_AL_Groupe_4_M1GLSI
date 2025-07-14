<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
    /**
     * Afficher la liste des articles
     */
    public function index()
    {
        $articles = Article::with(['auteur', 'categorie'])
            ->where('est_publie', true)
            ->latest('date_publication')
            ->paginate(10);
            
        return response()->json($articles);
    }

    /**
     * Afficher un article spécifique
     */
    public function show($id)
    {
        $article = Article::with(['auteur', 'categorie'])
            ->where('est_publie', true)
            ->findOrFail($id);
            
        return response()->json($article);
    }

    /**
     * Créer un nouvel article (réservé aux éditeurs et administrateurs)
     */
    public function store(Request $request)
    {
        $user = $request->user();
        
        if (!in_array($user->role, ['editeur', 'admin'])) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        $validator = Validator::make($request->all(), [
            'titre' => 'required|string|max:255',
            'contenu' => 'required|string',
            'categorie_id' => 'required|exists:categories,id',
            'est_publie' => 'boolean',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $data = $request->only(['titre', 'contenu', 'categorie_id', 'est_publie']);
        $data['auteur_id'] = $user->id;
        $data['date_publication'] = now();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('articles', 'public');
            $data['image'] = $path;
        }

        $article = Article::create($data);
        return response()->json($article, 201);
    }

    /**
     * Mettre à jour un article
     */
    public function update(Request $request, $id)
    {
        $user = $request->user();
        $article = Article::findOrFail($id);
        
        // Vérifier les autorisations
        if ($user->role !== 'admin' && ($user->id !== $article->auteur_id || $user->role !== 'editeur')) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        $validator = Validator::make($request->all(), [
            'titre' => 'sometimes|string|max:255',
            'contenu' => 'sometimes|string',
            'categorie_id' => 'sometimes|exists:categories,id',
            'est_publie' => 'sometimes|boolean',
            'image' => 'sometimes|image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $data = $request->only(['titre', 'contenu', 'categorie_id', 'est_publie']);
        
        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image si elle existe
            if ($article->image) {
                \Storage::disk('public')->delete($article->image);
            }
            $path = $request->file('image')->store('articles', 'public');
            $data['image'] = $path;
        }

        $article->update($data);
        return response()->json($article);
    }

    /**
     * Supprimer un article
     */
    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        $article = Article::findOrFail($id);
        
        // Seul l'admin ou l'auteur de l'article peut le supprimer
        if ($user->role !== 'admin' && $user->id !== $article->auteur_id) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        // Supprimer l'image associée si elle existe
        if ($article->image) {
            \Storage::disk('public')->delete($article->image);
        }

        $article->delete();
        return response()->json(['message' => 'Article supprimé avec succès']);
    }
}
