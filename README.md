# Airtel Money PHP SDK

Airtel Africa offre un moyen sûr d'accepter l'argent des clients.
Créez un compte ou connectez-vous en utilisant vos identifiants existants. Si vous n'avez pas de compte, inscrivez-vous.

Générez les informations d'identification de votre compte de transit à partir des paramètres de l'application. 
Ces informations sont nécessaires pour explorer les solutions d'intégration d'Airtel Africa.
Lorsque vous êtes prêt à passer en production, activez votre compte dans le tableau de bord pour obtenir les références du compte de production.
Comprendre les informations d'identification du compte
Les références des comptes sont disponibles dans votre tableau de bord, tant pour la mise en scène que pour l'environnement de production.
N'oubliez pas : Ne partagez jamais la clé ou le secret de votre application avec d'autres développeurs ou toute autre personne d'Airtel Africa, car cela compromettrait la sécurité de votre application.

Cette API est utilisée pour obtenir le jeton au porteur. La sortie de cette API contient access_token qui sera utilisé comme jeton au porteur pour l'API que nous allons appeler.

## Installation
```bash
$ composer require devscast/airtel-money
```

### Oauth
