# ELOQIO AI Content Reservation

Plugin WordPress qui implémente le [TDM Reservation Protocol](https://www.w3.org/community/reports/tdmrep/CG-FINAL-tdmrep-20240510/) du W3C pour signaler l'opt-out à l'entraînement IA sur un site WordPress.

Maintenu par [ELOQIO](https://eloq.io).

## Ce que fait le plugin

- Sert `/.well-known/tdmrep.json` dynamiquement, sans fichier à créer
- Ajoute l'en-tête HTTP `tdm-reservation` sur toutes les réponses front
- Injecte la balise `<meta name="tdm-reservation">` dans `<head>`
- Supporte le champ optionnel `tdm-policy` pour lier un document de politique
- Fournit un test Site Health asynchrone pour vérifier que l'endpoint répond
- Zéro dépendance, zéro tracking, zéro appel réseau externe

## Contexte juridique

TDMRep est le pendant technique de l'article 4 de la directive européenne DSM et de l'AI Act. Contrairement à `robots.txt`, il a une **valeur juridique contraignante** : les fournisseurs d'IA qui ignorent un signal TDMRep correctement déployé s'exposent à des amendes (jusqu'à 3 % du chiffre d'affaires mondial ou 15 M€) à partir d'août 2026.

## Installation développeur

```bash
git clone https://github.com/eloqio/eloqio-ai-content-reservation.git
cd eloqio-ai-content-reservation
# Symlink dans un wp-content/plugins local, ou wp-env
```

## Architecture

```
src/
├── Autoloader.php   # PSR-4 maison, zéro dépendance Composer
├── Plugin.php       # Orchestrateur, singleton, lecture des settings
├── Endpoint.php     # Intercepte /.well-known/tdmrep.json sur init
├── Headers.php      # HTTP header + meta tag via send_headers / wp_head
├── Settings.php     # Page réglages Settings API classique
└── SiteHealth.php   # Test asynchrone REST
```

Namespace racine : `ELOQIO\AiContentReservation\` — autoloader PSR-4 maison, pas de Composer.

## Stack et conventions

- PHP 7.4+, WordPress 6.5+
- Settings API classique (pas de React, pas de build step)
- Namespaces PHP plutôt que préfixes de classes (recommandation WP.org 2025)
- `defined('ABSPATH') || exit;` en tête de chaque fichier
- Sanitization/escaping rigoureux : `wp_unslash`, `sanitize_*`, `esc_*`
- Textdomain `eloqio-ai-content-reservation`, chargement just-in-time (pas de `load_plugin_textdomain`)

## Release workflow

Git → WordPress.org SVN via [10up/action-wordpress-plugin-deploy](https://github.com/10up/action-wordpress-plugin-deploy). Tag `vX.Y.Z` → déclenche le déploiement automatique vers `trunk/` et `tags/X.Y.Z/`.

Les assets WP.org (banner, icon, screenshots) vivent dans `.wordpress-org/` et sont exclus du zip distribué via `.distignore`.

## Licence

GPLv2 ou supérieure.
