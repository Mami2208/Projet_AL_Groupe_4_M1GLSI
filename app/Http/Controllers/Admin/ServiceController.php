<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Afficher la liste des services API
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $services = [
            [
                'type' => 'rest',
                'name' => 'API REST',
                'description' => 'API RESTful pour accéder aux articles et aux catégories.',
                'endpoints' => [
                    ['method' => 'GET', 'path' => '/api/articles', 'description' => 'Lister tous les articles'],
                    ['method' => 'GET', 'path' => '/api/articles/categorie/{slug}', 'description' => 'Lister les articles par catégorie'],
                    ['method' => 'GET', 'path' => '/api/articles/groupes-par-categorie', 'description' => 'Lister les articles groupés par catégorie'],
                ],
                'documentation_url' => url('/api/documentation'),
            ],
            [
                'type' => 'soap',
                'name' => 'API SOAP',
                'description' => 'Service SOAP pour la gestion des utilisateurs.',
                'endpoints' => [
                    ['method' => 'POST', 'path' => '/soap', 'description' => 'Authentifier un utilisateur (authenticate)'],
                    ['method' => 'POST', 'path' => '/soap', 'description' => 'Lister les utilisateurs (listUsers)'],
                    ['method' => 'POST', 'path' => '/soap', 'description' => 'Ajouter un utilisateur (addUser)'],
                    ['method' => 'POST', 'path' => '/soap', 'description' => 'Mettre à jour un utilisateur (updateUser)'],
                    ['method' => 'POST', 'path' => '/soap', 'description' => 'Supprimer un utilisateur (deleteUser)'],
                ],
                'documentation_url' => url('/soap/wsdl'),
            ],
        ];

        return view('admin.services.index', compact('services'));
    }
}
