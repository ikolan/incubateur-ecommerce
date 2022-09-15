# Projet E-Commerce Incubateur

## Technologies

- PHP (7.2.5) / Symfony (5.4)
- React (17)
- Docker / Docker Compose

## Préparer l'environment de développement.

Installer les dépendances pour le frontend.

```bash
$ cd front-end
$ npm i
$ cd ..
```

Créer un fichier `.env.local` à partir de `.env` pour le backend.

```bash
$ cd back_end
$ cp .env .env.local
$ cd ..
```

Éditer `.env.local` pour configurer Symfony.

> **Example :**
>
> Pour activer le service de mail, remplacer la variable `MAILER_DSN` avec les informations du SMTP comme ceci :
>
> ```
> MAILER_DSN=smtp://user:password@smtp.service:port
> ```

Démarrer les service via Docker.

> **Note :** 
>
> Cela peut prendre un certain temps lors du premier démarrage.

```bash
$ docker compose up -d
```

Lancer un `bash` dans le container `back_end`.

```bash
$ docker compose exec back_end bash
```
Dans celui-ci, installer les dépendances pour Symfony.

```bash
back_end$ composer install
```

Générer un jeu de clés pour les JWT.

```bash
back_end$ bin/console lexik:jwt:generate-keypair
```

Générer la base de données.

```bash
back_end$ bin/console doctrine:database:create
back_end$ bin/console make:migration
back_end$ bin/console doc:migration:migrate
```

Ajouter les fixtures.

```bash
# Minimal
back_end$ bin/console doctrine:fixture:load --group default

# OU avec des données.
back_end$ bin/console doctrine:fixtures:load
```

> **Note :**
>
> Chaque utilisateur générer (avec les fixtures complete) possède les login suivant:
>
> ```Login : user<n>@test.com | Mot de passe : user<n>```
>
> *Avec `n` se trouvant entre 0 et 99 (inclus).*

On peut maintenant quitter le container.

```bash
back_end$ exit
```

## Lancer l'environment de développement.

Une fois l'environment préparer, il suffit de le lancer via Docker avec ...

```bash
$ docker compose up -d
```

Et l’arrêter avec ...

```bash
$ docker compose down
```

## Tests unitaire backend (via PHPUnit)

Pour lancer les tests unitaire, lancer un `bash` dans le container `back_end` et executer lancer PHPUnit.

```bash
$ docker compose exec back_end bash
back_end$ bin/phpunit
```