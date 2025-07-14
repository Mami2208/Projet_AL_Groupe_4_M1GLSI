from suds.client import Client
from suds.sax.element import Element
import logging

# Activer les logs pour le débogage
logging.basicConfig(level=logging.INFO)
logging.getLogger('suds.client').setLevel(logging.DEBUG)
logging.getLogger('suds.transport').setLevel(logging.DEBUG)

def test_soap_connection():
    """Test de connexion au service SOAP"""
    print("Tentative de connexion au service SOAP...")
    
    try:
        # Créer un client SOAP avec le WSDL
        client = Client('http://localhost:8000/soap/wsdl')
        
        print("Connexion établie. Services disponibles:")
        print(client)
        
        # Tester l'authentification
        print("\nTest d'authentification...")
        result = client.service.authenticate('admin@example.com', 'password')
        
        print(f"Résultat: {result}")
        
        if hasattr(result, 'success') and result.success:
            print(f"Authentification réussie! Token: {result.token}")
            
            # Tester la récupération des utilisateurs avec le token
            print("\nTest de récupération des utilisateurs...")
            
            # Créer un en-tête SOAP avec le token
            token_header = Element('token')
            token_header.setText(result.token)
            
            # Essayer avec différentes configurations d'en-têtes
            try:
                users_result = client.service.listUsers(__inject={'soapheaders': token_header})
                print(f"Résultat: {users_result}")
            except Exception as e:
                print(f"Erreur avec en-tête simple: {str(e)}")
                
                # Essayer avec un namespace
                try:
                    token_header = Element('token')
                    token_header.setText(result.token)
                    token_header.setPrefix('ns0')
                    token_header.setNamespace('http://localhost/soap')
                    
                    users_result = client.service.listUsers(__inject={'soapheaders': token_header})
                    print(f"Résultat avec namespace: {users_result}")
                except Exception as e:
                    print(f"Erreur avec namespace: {str(e)}")
        else:
            print("Échec de l'authentification")
    
    except Exception as e:
        print(f"Erreur: {str(e)}")

if __name__ == "__main__":
    test_soap_connection()
