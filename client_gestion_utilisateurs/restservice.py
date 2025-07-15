import requests
import json
import logging

class RestService:
    """
    Classe pour interagir avec l'API REST Laravel
    """
    
    def __init__(self, base_url='http://localhost:8000/api'):
        """
        Initialise le client REST
        
        Args:
            base_url (str): URL de base de l'API REST
        """
        self.base_url = base_url
        self.token = None
        self.user_info = None
        self.headers = {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
        
        # Activer les logs pour le débogage
        logging.basicConfig(level=logging.INFO)
        logging.getLogger('requests').setLevel(logging.DEBUG)
    
    def authenticate(self, email, password):
        """
        Authentifie un utilisateur
        
        Args:
            email (str): Email de l'utilisateur
            password (str): Mot de passe de l'utilisateur
            
        Returns:
            dict: Résultat de l'authentification
        """
        try:
            # Ajouter des logs pour le débogage
            print(f"Tentative d'authentification REST pour: {email}")
            
            # Appel à l'API REST pour l'authentification
            response = requests.post(
                f"{self.base_url}/auth/login",
                json={'email': email, 'password': password},
                headers=self.headers
            )
            
            # Convertir la réponse en dictionnaire
            result = response.json()
            
            if response.status_code == 200 and result.get('token'):
                self.token = result['token']
                self.headers['Authorization'] = f"Bearer {self.token}"
                self.user_info = result.get('user', {})
                print(f"Authentification REST réussie, token: {self.token}")
                
                return {
                    'success': True,
                    'token': self.token,
                    'user': self.user_info,
                    'message': result.get('message', 'Authentification réussie')
                }
            else:
                print(f"Échec d'authentification REST: {result.get('message', 'Raison inconnue')}")
                return {
                    'success': False,
                    'message': result.get('message', 'Échec de l\'authentification')
                }
        except Exception as e:
            print(f"Erreur de communication avec le serveur REST: {str(e)}")
            return {
                'success': False,
                'message': f"Erreur de communication avec le serveur: {str(e)}"
            }
    
    def list_users(self):
        """
        Récupère la liste des utilisateurs
        
        Returns:
            dict: Liste des utilisateurs ou message d'erreur
        """
        if not self.token:
            return {'success': False, 'message': 'Non authentifié'}
        
        try:
            print(f"Envoi de la requête REST listUsers avec token: {self.token}")
            
            # Appel à l'API REST pour lister les utilisateurs
            response = requests.get(
                f"{self.base_url}/auth/users",
                headers=self.headers
            )
            
            if response.status_code == 200:
                result = response.json()
                return {
                    'success': True,
                    'users': result.get('users', [])
                }
            else:
                result = response.json()
                return {
                    'success': False,
                    'message': result.get('message', 'Erreur lors de la récupération des utilisateurs')
                }
        except Exception as e:
            print(f"Erreur dans list_users REST: {str(e)}")
            return {
                'success': False,
                'message': f"Erreur lors de la récupération des utilisateurs: {str(e)}"
            }
    
    def add_user(self, name, email, password, role='visiteur'):
        """
        Ajoute un nouvel utilisateur
        
        Args:
            name (str): Nom de l'utilisateur
            email (str): Email de l'utilisateur
            password (str): Mot de passe de l'utilisateur
            role (str): Rôle de l'utilisateur (visiteur, editeur, admin)
            
        Returns:
            dict: Résultat de l'opération
        """
        if not self.token:
            return {'success': False, 'message': 'Non authentifié'}
        
        try:
            # Appel à l'API REST pour ajouter un utilisateur
            response = requests.post(
                f"{self.base_url}/auth/users",
                json={
                    'name': name,
                    'email': email,
                    'password': password,
                    'role': role
                },
                headers=self.headers
            )
            
            result = response.json()
            
            if response.status_code == 201:
                return {
                    'success': True,
                    'user': result.get('user', {}),
                    'message': result.get('message', 'Utilisateur ajouté avec succès')
                }
            else:
                return {
                    'success': False,
                    'message': result.get('message', 'Erreur lors de l\'ajout de l\'utilisateur')
                }
        except Exception as e:
            return {
                'success': False,
                'message': f"Erreur lors de l'ajout de l'utilisateur: {str(e)}"
            }
    
    def update_user(self, user_id, data):
        """
        Met à jour un utilisateur existant
        
        Args:
            user_id (int): ID de l'utilisateur à modifier
            data (dict): Données à mettre à jour
            
        Returns:
            dict: Résultat de l'opération
        """
        if not self.token:
            return {'success': False, 'message': 'Non authentifié'}
        
        try:
            # Appel à l'API REST pour mettre à jour un utilisateur
            response = requests.put(
                f"{self.base_url}/auth/users/{user_id}",
                json=data,
                headers=self.headers
            )
            
            result = response.json()
            
            if response.status_code == 200:
                return {
                    'success': True,
                    'user': result.get('user', {}),
                    'message': result.get('message', 'Utilisateur mis à jour avec succès')
                }
            else:
                return {
                    'success': False,
                    'message': result.get('message', 'Erreur lors de la mise à jour de l\'utilisateur')
                }
        except Exception as e:
            return {
                'success': False,
                'message': f"Erreur lors de la mise à jour de l'utilisateur: {str(e)}"
            }
    
    def delete_user(self, user_id):
        """
        Supprime un utilisateur
        
        Args:
            user_id (int): ID de l'utilisateur à supprimer
            
        Returns:
            dict: Résultat de l'opération
        """
        if not self.token:
            return {'success': False, 'message': 'Non authentifié'}
        
        try:
            # Appel à l'API REST pour supprimer un utilisateur
            response = requests.delete(
                f"{self.base_url}/auth/users/{user_id}",
                headers=self.headers
            )
            
            if response.status_code == 204:
                return {
                    'success': True,
                    'message': 'Utilisateur supprimé avec succès'
                }
            else:
                result = response.json()
                return {
                    'success': False,
                    'message': result.get('message', 'Erreur lors de la suppression de l\'utilisateur')
                }
        except Exception as e:
            return {
                'success': False,
                'message': f"Erreur lors de la suppression de l'utilisateur: {str(e)}"
            }
    
    def is_admin(self):
        """
        Vérifie si l'utilisateur connecté est un administrateur
        
        Returns:
            bool: True si l'utilisateur est admin, False sinon
        """
        return self.user_info and self.user_info.get('role') == 'admin'