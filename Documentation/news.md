#Integration von News-Extensions
**Die Erweiterung wurde mit freundlicher Unterstützung von [webfacemedia aus Limburg](https://www.webfacemedia.de/) umgesetzt.**

Mit dieser Anbindung hat man die Möglichkeit **[tt_news](https://extensions.typo3.org/extension/tt_news/)** bzw. **[news](https://extensions.typo3.org/extension/news/)** für die Spiel- und Vorberichte zu nutzen. Nach der Installation findet man im Spiel-Datensatz beim Spielbericht zwei neue Felder für die Zuordnung der News-Records.

#Integration von news

Für die Ausgabe im Frontend muss das mitgelieferte Static Template **"T3sports with news"** installiert werden. Die Ausgabe erfolgt bei den Spielen über folgende Marker:

    ###MATCH_NEWSPREVIEW### und ###MATCH_NEWSREPORT###

Die beiden Marker verwenden eigene Templates. Man kann hier im Prinzip den gesamten Newsbeitrag rausrendern, alternativ ist aber auch nur eine einfache Verlinkung auf den News-Beitrag möglich. Hier ein Beispiel-Template für einen Newsbericht:
```html
###NEWSREPORT###
<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">###NEWS_TITLE###</h3>
  </div>
  <div class="panel-body">
  ###NEWS_DATETIME###:
	###NEWS_TEASER###
	###NEWS_BODYTEXT###
	###NEWS_NEWSLINK###Zum Spielbericht###NEWS_NEWSLINK###
  </div>
</div>
###NEWSREPORT###
```
Das Template wird per Typoscript konfiguriert:

    lib.t3sports.match.newsreport._template.path = EXT:more4t3sports/Resources/Private/Templates/news.html
    lib.t3sports.match.newsreport._template.subpartName = ###NEWSREPORT###
    lib.t3sports.match.newspreview._template.path = EXT:more4t3sports/Resources/Private/Templates/news.html
    lib.t3sports.match.newspreview._template.subpartName = ###NEWSPREVIEW###

Die Ausgabe des News-Records kann mit folgendem Typoscript angepasst werden:
```
lib.t3sports.news {
	datetime.strftime = %d.%m.%y
	short.wrap = <p>|</p>
	bodytext.parseFunc =< lib.parseFunc_RTE
	links.news.pid = {$plugin.tx_news.settings.sitemap.detailPid}
	links.news._cfg.params.news = uid
	links.news.qualifier = tx_news_pi1
}
```
Der Link auf die Detailseite der News verwendet automatisch die konfigurierte Konstante aus **news**.


#Integration von tt_news

Für die Ausgabe im Frontend muss das mitgelieferte Static Template **"T3sports with tt_news"** installiert werden. Die Ausgabe erfolgt bei den Spielen über folgende Marker:

    ###MATCH_NEWSPREVIEW### und ###MATCH_NEWSREPORT###

Die beiden Marker verwenden eigene Templates. Man kann hier im Prinzip den gesamten Newsbeitrag rausrendern, alternativ ist aber auch nur eine einfache Verlinkung auf den News-Beitrag möglich. Hier ein Beispiel-Template für einen Newsbericht:
```html
###NEWSREPORT###
<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">###NEWS_TITLE###</h3>
  </div>
  <div class="panel-body">
  ###NEWS_DATETIME###:
	###NEWS_SHORT###
	###NEWS_BODYTEXT###
	###NEWS_NEWSLINK###Zum Spielbericht###NEWS_NEWSLINK###
  </div>
</div>
###NEWSREPORT###
```
Das Template wird per Typoscript konfiguriert:

    lib.t3sports.match.newsreport._template.path = EXT:more4t3sports/Resources/Private/Templates/tt_news.html
    lib.t3sports.match.newsreport._template.subpartName = ###NEWSREPORT###
    lib.t3sports.match.newspreview._template.path = EXT:more4t3sports/Resources/Private/Templates/tt_news.html
    lib.t3sports.match.newspreview._template.subpartName = ###NEWSPREVIEW###

Die Ausgabe des News-Records kann mit folgendem Typoscript angepasst werden:
```
lib.t3sports.ttnews {
	datetime.strftime = %d.%m.%y
	short.wrap = <p>|</p>
	bodytext.parseFunc =< lib.parseFunc_RTE
	links.news.pid = {$plugin.tt_news.singlePid}
	links.news._cfg.params.tt_news = uid
	links.news.qualifier = tx_ttnews
}
```
Der Link auf die Detailseite der News verwendet automatisch die konfigurierte Konstante aus tt_news.
