Application:

- reine EXTERNE Api
- definiert (eigene) Navigation
	- maximal ein Client-Level Link
	- beliebig viele Module-Level Links
	- beliebig viele Application-Level Links
- definiert (eigene) Routen
- benutzt Service (eigene/fremde) für Dateninteraktion

Service:

- reine INTERNE Api
- darf KEINE Navigation definieren
- darf KEINE Routen definieren
- definiert Anwendungs-Gui
	- Aktionen MÜSSEN IMMER auf AKTUELLER Route ausgeführt werden
- definiert Datenbankverbindung
