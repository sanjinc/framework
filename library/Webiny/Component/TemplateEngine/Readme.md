Template Engine Component
=========================
THIS COMPONENT IS UNDER CONSTRUCTION!!

http://symfony.com/doc/current/book/templating.html

Todo:
- registracija filter i pluginova preko service manager-a
- konfiguracija template engine-a, kako prenesti parametre za putanje, cache, debug i sl
- postavi anotaciju za definiranje template-a tipa module:controller:template (pogledaj: http://symfony.com/doc/current/book/templating.html#template-naming-and-locations)
- naziv template-a bi morao biti formatira na nacin tipa naziv.jezik.engine, tipa index.html.smarty ili style.css.smarty
- vidi da ovu sintaksu podržimo {{ render(controller('AcmeArticleBundle:Article:recentArticles', {'max': 3})) }}
- definiraj auto escaping output-a po defaultu


Component\TemplateEngine
        Drivers
            Smarty
            Twig
	-> registerPlugin (plugin se registrira u driver prije fetchanja/rendera, pokupit ces sve pluginove za odgovarajuci driver i ubost ih unutra)
        -> TemplateEngineInterface
Component\View (@templateEngine)  (View-u je dependency TE, jer onda možeš koristiti 2 TE-a istovremeno za različito viewove)
         Components
              - Grid
              - Form
         -> fetch
         -> render
         -> assign