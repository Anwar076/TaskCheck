# Microsoft Entra ID en SCIM configureren voor TaskCheck

## Voorwaarden

- TaskCheck draait uitsluitend via HTTPS; voor SCIM vereist Microsoft minimaal TLS 1.2.
- De migraties zijn uitgevoerd met `php artisan migrate --force`.
- `APP_URL` wijst naar de publieke HTTPS-URL en `APP_KEY` is veilig beheerd.
- Productie gebruikt `SESSION_SECURE_COOKIE=true`, `SESSION_ENCRYPT=true` en een expliciete `CORS_ALLOWED_ORIGINS`.

## 1. Appregistratie voor OIDC

1. Maak in Microsoft Entra ID een single-tenant appregistratie.
2. Voeg als Web Redirect URI toe: `https://<taskcheck-host>/auth/entra/callback`.
3. Maak een client secret met een beperkte geldigheidsduur en leg rotatie-eigenaarschap vast.
4. Configureer in Token configuration de `groups`-claim voor de groepen die aan TaskCheck worden toegewezen. Gebruik bij voorkeur alleen groepen die aan de applicatie zijn toegewezen om token-overage te voorkomen.
5. Noteer Directory (tenant) ID, Application (client) ID en de secretwaarde.
6. Ken gebruikers/groepen toe aan de Enterprise Application.

TaskCheck gebruikt Authorization Code Flow met PKCE en valideert de ondertekening via Microsoft JWKS, plus `alg`, `kid`, issuer, audience, tenant, nonce, `exp` en `nbf`.

## 2. TaskCheck configureren

Open als organisatiebeheerder `/admin/settings/identity`.

1. Vul het unieke zakelijke e-maildomein in.
2. Vul tenant ID, client ID en client secret in.
3. Vul de Entra object-ID's van de admin- en/of medewerkergroepen in.
4. Activeer **Entra SSO actief** en sla op.
5. Test in een privévenster met zowel een toegestane als niet-toegestane gebruiker.
6. Activeer pas daarna **Lokale login blokkeren**.

Het client secret wordt met Laravel application encryption opgeslagen en nooit teruggetoond.

## 3. MFA afdwingen

Maak in Entra Conditional Access een beleid voor de TaskCheck Enterprise Application dat MFA vereist. Test dat de ID-tokenclaim `amr` de waarde `mfa` bevat. Activeer daarna in TaskCheck **MFA-claim verplicht**. TaskCheck weigert dan een SSO-sessie zonder door Entra bevestigde MFA.

Let op: Conditional Access is de primaire handhaving. De TaskCheck-controle is defense-in-depth en vereist een compatibele `amr`-claim in het ID-token.

## 4. SCIM provisioning

1. Klik in TaskCheck op **SCIM-token aanmaken** en kopieer de eenmalig getoonde token.
2. Open de Enterprise Application in Entra en kies Provisioning.
3. Gebruik de getoonde TaskCheck Tenant URL en de token als Secret Token.
4. Test Connection moet een lege SCIM ListResponse kunnen ontvangen.
5. Map minimaal:
   - `userPrincipalName` → `userName` (matching property)
   - `objectId` → `externalId` (matching property)
   - `displayName` → `displayName`
   - `accountEnabled` → `active`
6. Kies bij voorkeur **Sync only assigned users and groups** en test Provision on demand.

De endpoint ondersteunt gebruikers aanmaken, opvragen/filteren, vervangen, PATCH en deprovisioning. Deprovisioning zet `active=false`, deactiveert het account en trekt actieve mobiele tokens in. Groepsobjecten worden niet via SCIM opgeslagen; autorisatie gebruikt de groepsclaims bij iedere SSO-login.

## 5. Operationeel beheer

- Roteer de SCIM-token en client secret periodiek en onmiddellijk bij vermoeden van uitlekken.
- Houd minimaal één gecontroleerde break-glassprocedure voor platformbeheer beschikbaar.
- Bewaak mislukte SSO- en SCIM-aanvragen in de applicatie- en proxylogs zonder tokens/secrets te loggen.
- Test provisioning, deprovisioning, MFA en groepswijzigingen na iedere relevante configuratiewijziging.
- Bevestig bij Shock Media TLS, proxyheaders, back-ups, hersteltests, opslaglocaties en logretentie.

Microsoft-referenties: [OIDC in Microsoft identity platform](https://learn.microsoft.com/en-us/entra/identity-platform/v2-protocols-oidc) en [een SCIM-endpoint integreren](https://learn.microsoft.com/en-us/entra/identity/app-provisioning/use-scim-to-provision-users-and-groups).
