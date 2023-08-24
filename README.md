# MyLittleInvoice, version Alpha 0.5 

## Description

MyLittleInvoice est un script PHP/javascript qui permet aux organisations de services de créer et de gérer :

 - Base de clients
 - Devis
 - Factures
 - Encaissements
 - Rapports d'activités

MyLittleInvoice conviendra particulièrement aux activités qui n'ont pas besoin de gérer un stock et dont les besoins en comptabilité sont réduits, comme les développeur, indépendants, associations.

Conçu autour d'un design responsive, il est accessible depuis n'importe quel support comme les ordinateurs, tablettes et smartphones.

## Licence

		This file is part of MyLittleInvoice.

    MyLittleInvoice is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License.

    MyLittleInvoice is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Foobar.  If not, see <http://www.gnu.org/licenses/>
		
## Composants Requis

Pour installer et utiliser MyLittleInvoice, vous aurez besoin coté serveur :

 - Mysql 5.5 min
 - PHP 5 min
 - Apache2

Pour le coté client, les navigateurs courant en 2015 fonctionnent parfaitement : Firefox, Google Chrome, Safari, Internet Explorer

## Installation

Pour installer MyLittleInvoice sur votre serveur, réservez un dossier dans "www" ou "http-docs" et copier-y le contenu de l'archive. 

Dans un navigateur, allez à l'adresse correspondante, vous serez automatique redirigé vers le script d'installation si celui-ci n'existe pas.

Après l'installation, vous pouvez supprimer le fichier "install.php" par précaution.

## Changelogs

### Alpha publique V0.5:

Ajout :

 - Ajout système de langue
 - Assistant d'installation et création fichier config.php
 - Protection de l'application par utilisateur et mot de passe
 - Gestion multi utilisateur
 - Bouton pour ajouter des lignes dans la création de document pour les coordonnées client

Corrections :

 - Bug sur les rapports annuels, macaron des encaissements à zéro
 - Bug sur l'affichage des factures en cours
 - Désactivation de la modification des macarons sur la page des rapports
 - Bug sur l'affichage du bandeau bleu dans la liste des devis et factures lorsque aucun document n'est affiché
 - Cacher le nom d'utilisateur dans le menu horizontal 
 - Positionnement du fil d'Ariane dans la liste des documents
 - Bug d'affichage sur la page des rapports

### Alpha publique V0.4:

Ajout :

 - Assistant d’installation qui se lance automatiquement
 - Ajout de la gestion d’utilisateur pour accéder au logiciel
 - Gestion Multi-utilisateurs
 - Ajout d’un bouton lors de l’édition d’un document pour ajouter des lignes sur les coordonnées client

Corrections :

 - Cacher le nom d’utilisateur dans le menu horizontal
 - Correction bug d’affichage sur la page des rapports
 - Correction du Fil d’arianne qui passe au dessus des bouton sur iPad

### Alpha privée V0.3:

Ajouts :

 - Ajout de la gestion du logo
 - Ajout de produits type pour accélérer les saisies de documents
 - Ajout de la page des rapports mensuels
 - Ajout de la page des rapports annuels
 - Ajout d’un bouton de modification des règlements
 - Ajout d’une page « options générales »
 - Ajout d’un champ date sur la table des clients

Corrections :

 - Correction de l’affichage du pied de page des documents sur iPad
 - Correction de l’affichage des informations des documents sur iPad
 - Correction des retours à la ligne des champs textes de documents
 - Agrandissement des infos clients sur les documents
 - Correction des champs texte sur la page des coordonnées de l’entreprise
 - Adaptation de la taille du logo dans les pdf
 - Correction du calcul du nombre de devis convertis sur le tableau de bord
 - Bouton « Annuler » sur la page des nouveaux encaissements
 - Ajout du lien vers la page des encaissements sur le tableau de bord
 - Correction d’un bug d’affichage sur la page des rapports si le CA est de 0

### Alpha privée V0.2:

Ajouts :

 - Bouton d’alerte pour les devis dont la date d’échéance est passée
 - Bouton d’alerte pour les factures dont la date d’échéance est passée
 - Ajout de la date d’échéance dans la liste des devis et factures
 - Mise en valeur des devis et factures échus par une couleur rouge sur la date
 - Ajout du solde sur la liste des factures
 - Ajout d’un lien vers la facture pour les devis aillant été transformés en facture
 - Ajout des encaissements sur la liste des factures
 - Ajout de la liste des encaissements et d’une page dédié aux nouveaux encaissements
 - Impression sur ticket de caisse, testé sur epson TM-T20
 - Curseur en forme de main lors du survol de champs éditables dans la création de documents (merci a JJR pour la suggestion)
 - Ajout des icônes dans le  titre des pages

Corrections :

 - Suppression des fichiers inutiles
 - Ajout d’une taille minimum aux champs éditables dans les factures et devis pour permettre l’édition même si le champ est vide
 - Correction d’un bug dans le calcul du total
 - Correction d’un bug qui coupait les textes d’informations en bas des documents
 - Correction d’un bug lors de l’édition en cascade des infos du client
 - Correction des champs clients lors de la création d’un devis
 - Correction du bug lors de l’enregistrement des coordonnées de l’entreprise
 - Correction de l’affichage des listes déroulantes dans firefox

## Liens

 - GitHub : https://github.com/Jonathan-RX/mylittleinvoice
 - Source Forge : https://sourceforge.net/projects/mylittleinvoice/
 - Facebook : https://www.facebook.com/mylittleinvoice
