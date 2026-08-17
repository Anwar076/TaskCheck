# TaskCheck – antwoorden technische beoordeling BI

Status: bijgewerkt na implementatie op 13 augustus 2026. De functionaliteit moet nog in de productieomgeving worden uitgerold en samen met de klant in Microsoft Entra worden geconfigureerd en geaccepteerd. Hostingdetails en organisatorische maatregelen moeten vóór verzending contractueel worden bevestigd.

## Conceptantwoord aan BI

Dank voor uw aanvullende vragen. Hieronder vindt u de huidige stand van zaken voor TaskCheck.

### Microsoft Entra ID (SSO)

TaskCheck ondersteunt na uitrol Single Sign-On via Microsoft Entra ID (voorheen Azure AD) met OpenID Connect (OIDC), Authorization Code Flow en PKCE. SAML 2.0 wordt niet ondersteund. Lokale login kan per organisatie worden geblokkeerd nadat SSO succesvol is geaccepteerd. De mobiele API gebruikt vooralsnog lokale aanmelding met een persoonlijke bearer token via Laravel Sanctum.

### SCIM provisioning/deprovisioning

TaskCheck bevat na uitrol een tenantgebonden SCIM 2.0-endpoint voor provisioning en deprovisioning van gebruikers. Het ondersteunt create, get/filter/list, replace, PATCH en deactivering. Deprovisioning deactiveert het account en trekt mobiele API-tokens in. De koppeling moet per klant in Entra worden geconfigureerd en getest.

### Entra ID-groepen en autorisaties

Entra ID-groepsobject-ID's kunnen per organisatie aan de interne rollen `admin` en `employee` worden gekoppeld. De rol wordt bij iedere SSO-login uit de gevalideerde groepsclaims bepaald. Daarnaast kunnen gebruikers aan een organisatie, vestiging en afdeling worden gekoppeld. Organisatiegebonden gegevens worden in de applicatie op `company_id` gescheiden.

### MFA

MFA wordt primair via Microsoft Conditional Access voor de Enterprise Application afgedwongen. TaskCheck kan daarnaast per organisatie eisen dat Entra de `mfa`-authenticatiemethode in de ID-tokenclaim bevestigt. Deze combinatie moet tijdens acceptatie met het beleid en tokenformat van de klant worden getest. Lokale TaskCheck-accounts hebben geen eigen MFA; daarom moet lokale login worden geblokkeerd wanneer Entra verplicht is.

### Technische Entra ID-documentatie

Er is een configuratiehandleiding beschikbaar in `docs/ENTRA-SCIM-CONFIGURATIE.md`. Deze beschrijft appregistratie, redirect URI, groepsclaims, rollenmapping, Conditional Access/MFA, SCIM, secretrotatie en acceptatietests.

### Authenticatie en autorisatie

De huidige implementatie bevat:

- lokale authenticatie met gehashte wachtwoorden via Laravel;
- e-mailverificatie voor afgeschermde webonderdelen;
- rate limiting op web-login (maximaal vijf mislukte pogingen per combinatie van e-mailadres en IP-adres binnen de limiterperiode);
- sessievernieuwing na inloggen en sessie-invalidering plus CSRF-tokenvernieuwing bij uitloggen;
- CSRF-bescherming op webformulieren, met een expliciete uitzondering voor de betaalprovider-webhook;
- bearer tokens via Laravel Sanctum voor de mobiele API, die bij uitloggen per huidig token worden ingetrokken;
- rolgebaseerde routebeveiliging voor beheerder, medewerker en platformbeheerder;
- organisatiegebonden gegevensscheiding en tests voor tenant-scope op onderdelen van de API.

De huidige rollen zijn grofmazig. Er is geen fijnmazige Entra-groepsmapping of centraal uitgewerkt RBAC-permissiemodel beschikbaar.

### Gegevensverwerking en opslag

TaskCheck verwerkt, afhankelijk van het gebruik, onder meer account- en contactgegevens, organisatie- en vestigingsgegevens, taken en checklists, inzendingen, opmerkingen, bewijsbestanden/foto's, digitale handtekeningen, notificatiegegevens, auditgegevens en factuur-/abonnementsgegevens.

De applicatie wordt gehost bij Shock Media. Shock Media publiceert dat zijn managed-hostingdienstverlening onder meer ISO 27001- en NEN 7510-gecertificeerd is. De concrete land/regio van de TaskCheck-productieomgeving, database, bestandsopslag, back-ups en eventuele uitwijklocatie moet echter aan de hand van het specifieke hostingcontract of een schriftelijke bevestiging van Shock Media worden vastgesteld. Tot die bevestiging doen wij niet de definitieve uitspraak dat alle gegevens uitsluitend in Nederland of de EER worden opgeslagen.

Daarnaast gebruikt de applicatie, afhankelijk van ingeschakelde functies, externe diensten voor onder meer e-mail, betalingen (Mollie), pushnotificaties en AI-functionaliteit (OpenAI). Deze partijen en de bijbehorende gegevensstromen, doeleinden, bewaartermijnen en locaties moeten in het verwerkingsregister en de subverwerkerslijst worden opgenomen.

### Verwerkersovereenkomst (DPA)

Ja. Bij akkoord kan een verwerkersovereenkomst worden opgesteld en door partijen worden ondertekend. Deze moet vóór ingebruikname minimaal de onderwerpen uit artikel 28 AVG bevatten, waaronder onderwerp en duur, aard en doel, categorieën gegevens en betrokkenen, beveiligingsmaatregelen, subverwerkers, ondersteuning bij rechten van betrokkenen, datalekken, verwijdering/retour na einde dienstverlening en auditafspraken.

### Beveiliging van de applicatie

De broncode laat onder meer invoervalidatie, wachtwoordhashing, CSRF-bescherming, login-rate-limiting, beveiligde sessiecookies (`HttpOnly` en standaard `SameSite=Lax`), rolcontroles, organisatiegebonden gegevensfilters en beperkte bestandstype-/bestandsgroottevalidatie zien. Laravel ondersteunt applicatie-encryptie met AES-256-CBC voor gegevens die expliciet via de encryptieservice worden verwerkt.

Niet op basis van alleen de repository bevestigd zijn: TLS- en HSTS-configuratie, encryptie van database/back-ups at rest, WAF/DDoS-maatregelen, patch- en vulnerabilitymanagement, centrale logging/SIEM, hersteltests, pentestresultaten, securityheaders, geheimenbeheer, bewaartermijnen en een formeel incidentresponsproces. Hiervoor zijn hostingconfiguratie en organisatorische bewijsstukken nodig.

## Open punten vóór verzending

1. Vraag Shock Media schriftelijk om de locaties van productie, database, uploads, back-ups en disaster recovery, plus eventuele doorgifte buiten de EER.
2. Vraag de actuele certificaten/scope of assurance-rapporten van Shock Media op en controleer dat de gebruikte TaskCheck-dienst binnen de scope valt.
3. Maak een subverwerkerslijst met ten minste Shock Media, mailprovider, Mollie, pushproviders en – indien actief – OpenAI, inclusief doel en verwerkingslocatie.
4. Stel de DPA en bijlage met technische en organisatorische maatregelen (TOM's) op en laat deze juridisch toetsen.
5. Leg bewaartermijnen en een verwijder-/exportproces per gegevenscategorie vast.
6. Rol de migratie en nieuwe identityfunctionaliteit gecontroleerd uit; configureer daarna Entra ID/OIDC, Conditional Access/MFA, groepsmapping en SCIM en voer een gezamenlijke acceptatietest uit.
7. Voer vóór harde securityclaims minimaal een deployment/configuratiereview, dependency scan en onafhankelijke penetratietest uit.
8. Corrigeer of verwijder onbewezen teksten in `resources/views/security.blade.php`, `resources/views/privacy.blade.php` en `resources/views/help.blade.php` voordat deze pagina's worden gepubliceerd.

## Technische grondslag in de repository

- Authenticatie: `app/Http/Requests/Auth/LoginRequest.php`, `app/Http/Controllers/Auth/AuthenticatedSessionController.php` en `app/Http/Controllers/Api/Mobile/AuthController.php`.
- Rollen en afscherming: `app/Http/Middleware/AdminMiddleware.php`, `EmployeeMiddleware.php`, `SuperAdminMiddleware.php` en `EnsureMobileAdmin.php`.
- Tenant-scheiding: `app/Traits/BelongsToCompany.php` en `tests/Feature/SubmissionApiTenantScopeTest.php`.
- Sessies en tokens: `config/session.php`, `config/sanctum.php` en `routes/api.php`.
- Opslag: `config/database.php` en `config/filesystems.php`.

Publieke bron voor certificeringen van de hoster: [Shock Media – Informatiebeveiliging en kwaliteit](https://shockmedia.support/certificeringen-en-veiligheid/certificeringen/informatiebeveiliging-en-kwaliteit).
