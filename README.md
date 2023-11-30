## Craft-Groots
Installation via composer.

Cloner le plugin sur la racine du site.

Modifier le composer du projet en place pour rendre le plugin visible :
Sous le groupe repositories, ajouter ceci :
```json
        {
            "type": "path",
            "url": "globalia/craft-groots"
        }
```
Faire les commandes suivantes:
```cli
ddev composer require globalia/craft-groots
ddev composer install
```

Dans l'admin de Craft / Settings / Plugin trouver le plugin et cliquer sur settings.


Mettre les paramètres requis, principalement la localisation du module groots du provenant du front-end.

Une fois l'installation réussi les fonctionnalités suivantes seront disponibles :
```twig
{{ craft.groots.getModule( 'nom-module/nom-page.twig', parametres) }}
{{ craft.groots.getComponent( 'nom-component/nom-page.twig', parametres) }}
```

## Utilisation

Les templates appelés par CraftCms contiennent un include d'une page twig qui fera les appels aux fonctions du plugin Groots.

### Exemple

La structure du projet pourrait ressembler à ceci :
```
projet
|__ [groots]
    |__[workspace]
|__ [Templates]
        homepage.twig
    |__ [_modules]
        m01a-banner.twig
```

La page `homepage.twig` contiendrait :

```twig
{% include "_modules/m01a-banner.twig" with { content: data} %}
```

Alors que la page m01a-banner.twig contiendra:
```twig
{{ craft.groots.getModule(
    'm01a-banner/banner.twig',
    content|merge({
        title: {
            title: content.title.title,
            headingLevel: 'h1',
            size: '100'
        },
        alignment: 'left',
        titleMargin: 0,
        subtitleMargin: 5, 
        contentMargin: 5,
        extClass: content.theme
    })
)}}
```
C'est de cette façon que les modules pourront être personnalisés pour passer les paramètres propres au projet.


		



