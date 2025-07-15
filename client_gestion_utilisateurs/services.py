import requests
import json
import logging
from suds.client import Client
from suds.transport.https import HttpAuthenticated
from suds import WebFault
from suds.sax.element import Element

class SoapService:
    """
    Classe pour interagir avec les services SOAP Laravel
    """
    
    def __init__(self, wsdl_url='http://localhost:8000/soap/wsdl'):
        """
        Initialise le client SOAP
        
        Args:
            wsdl_url (str): URL du fichier WSDL
        """
        self.wsdl_url = wsdl_url
        self.token = None
        self.user_info = None
        
        # Activer les logs pour le débogage
        logging.basicConfig(level=logging.INFO)
        logging.getLogger('suds.client').setLevel(logging.DEBUG)
        logging.getLogger('suds.transport').setLevel(logging.DEBUG)
        
        try:
            # Configurer le client avec plus d'options
            self.client = Client(
                url=self.wsdl_url,
                cache=None,  # Désactiver le cache
                headers={'Content-Type': 'application/soap+xml; charset=utf-8'}
            )
        except Exception as e:
            raise ConnectionError(f"Impossible de se connecter au service SOAP: {str(e)}")
    
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
            print(f"Tentative d'authentification pour: {email}")
            
            # Appel simple sans en-tête
            result = self.client.service.authenticate(email, password)
            
            # Convertir le résultat en dictionnaire
            result_dict = self._convert_to_dict(result)
            
            if result_dict['success']:
                self.token = result_dict['token']
                self.user_info = result_dict['user']
                print(f"Authentification réussie, token: {self.token}")
            else:
                print(f"Échec d'authentification: {result_dict.get('message', 'Raison inconnue')}")
            
            return result_dict
        except WebFault as e:
            print(f"Erreur SOAP: {str(e)}")
            return {
                'success': False,
                'message': f"Erreur SOAP: {str(e)}"
            }
        except Exception as e:
            print(f"Erreur de communication avec le serveur: {str(e)}")
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
            # Créer un en-tête SOAP avec le token et le namespace
            token_header = Element('token')
            token_header.setText(self.token)
            token_header.setPrefix('ns0')
            token_header.setNamespace('http://localhost/soap')
            
            print(f"Envoi de la requête listUsers avec token: {self.token}")
            
            # Appeler la méthode avec l'en-tête
            result = self.client.service.listUsers(__inject={'soapheaders': token_header})
            return self._convert_to_dict(result)
        except WebFault as e:
            print(f"Erreur SOAP dans list_users: {str(e)}")
            return {
                'success': False,
                'message': f"Erreur SOAP: {str(e)}"
            }
        except Exception as e:
            print(f"Erreur dans list_users: {str(e)}")
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
            # Créer un en-tête SOAP avec le token et le namespace
            token_header = Element('token')
            token_header.setText(self.token)
            token_header.setPrefix('ns0')
            token_header.setNamespace('http://localhost/soap')
            
            # Appeler la méthode avec l'en-tête
            result = self.client.service.addUser(name, email, password, role, __inject={'soapheaders': token_header})
            return self._convert_to_dict(result)
        except WebFault as e:
            return {
                'success': False,
                'message': f"Erreur SOAP: {str(e)}"
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
            # Créer un en-tête SOAP avec le token et le namespace
            token_header = Element('token')
            token_header.setText(self.token)
            token_header.setPrefix('ns0')
            token_header.setNamespace('http://localhost/soap')
            
            # Créer une structure de données compatible avec SOAP
            update_data = self.client.factory.create('ns0:updateUserData')
            for key, value in data.items():
                setattr(update_data, key, value)
            
            # Appeler la méthode avec l'en-tête
            result = self.client.service.updateUser(user_id, update_data, __inject={'soapheaders': token_header})
            return self._convert_to_dict(result)
        except WebFault as e:
            return {
                'success': False,
                'message': f"Erreur SOAP: {str(e)}"
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
            # Créer un en-tête SOAP avec le token et le namespace
            token_header = Element('token')
            token_header.setText(self.token)
            token_header.setPrefix('ns0')
            token_header.setNamespace('http://localhost/soap')
            
            # Appeler la méthode avec l'en-tête
            result = self.client.service.deleteUser(user_id, __inject={'soapheaders': token_header})
            return self._convert_to_dict(result)
        except WebFault as e:
            return {
                'success': False,
                'message': f"Erreur SOAP: {str(e)}"
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
    
    def _convert_to_dict(self, suds_object):
        """
        Convertit un objet SUDS en dictionnaire Python
        
        Args:
            suds_object: Objet SUDS à convertir
            
        Returns:
            dict: Dictionnaire Python
        """
        if hasattr(suds_object, '__keylist__'):
            return {key: self._convert_to_dict(getattr(suds_object, key)) 
                   for key in suds_object.__keylist__}
        elif isinstance(suds_object, list):
            return [self._convert_to_dict(item) for item in suds_object]
        else:
            return suds_object


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


def create_service(api_type='soap', base_url='http://localhost:8000'):
    """
    Crée un service API du type spécifié
    
    Args:
        api_type (str): 'soap' ou 'rest'
        base_url (str): URL de base du serveur
        
    Returns:
        Un service API (SoapService ou RestService)
    """
    if api_type.lower() == 'soap':
        return SoapService(wsdl_url=f"{base_url}/soap/wsdl")
    elif api_type.lower() == 'rest':
        return RestService(base_url=f"{base_url}/api")
    else:
        raise ValueError("Type d'API non supporté. Utilisez 'soap' ou 'rest'")